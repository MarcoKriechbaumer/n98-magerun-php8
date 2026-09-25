<?php

/**
 * Generates references/commands.md of the n98-magerun Claude skill from the command definitions of n98-magerun.
 *
 * Usage:
 *   php bin/n98-magerun list --format=json > commands.json
 *   php res/claude-skill/generate-reference.php commands.json res/claude-skill/n98-magerun/references/commands.md
 */

declare(strict_types=1);

if ($argc !== 3) {
    fwrite(STDERR, "Usage: php generate-reference.php <list.json> <commands.md>\n");
    exit(1);
}

$json = (string) file_get_contents($argv[1]);
// skip messages printed before the JSON document (e.g. warnings about the var folder)
$start = strpos($json, '{"');
$data = $start === false ? null : json_decode(substr($json, $start), true);
if (!is_array($data) || !isset($data['commands'])) {
    fwrite(STDERR, "No command list found in {$argv[1]}\n");
    exit(1);
}

// options every command has, documented once at the top
$globalOptions = [
    'help', 'silent', 'quiet', 'verbose', 'version', 'ansi', 'no-ansi', 'no-interaction',
    'root-dir', 'skip-config', 'skip-root-check', 'developer-mode',
];
$skipCommands = ['_complete', 'completion', 'help', 'list'];

$clean = static function (string $text): string {
    $text = preg_replace('#</?(info|comment|error|question|warning|debug)>#', '', $text) ?? $text;

    return trim(str_replace("\r\n", "\n", $text));
};

$cell = static function (string $text) use ($clean): string {
    return str_replace(['|', "\n"], ['\|', ' '], $clean($text));
};

$formatDefault = static function ($default): string {
    if ($default === null || $default === false || $default === []) {
        return '';
    }

    if ($default === true) {
        return 'true';
    }

    return is_array($default) ? implode(', ', $default) : (string) $default;
};

$byNamespace = [];
foreach ($data['commands'] as $command) {
    if (!empty($command['hidden']) || in_array($command['name'], $skipCommands, true)) {
        continue;
    }

    $namespace = str_contains($command['name'], ':') ? strstr($command['name'], ':', true) : '_global';
    $byNamespace[$namespace][] = $command;
}

ksort($byNamespace);

$application = $data['application'] ?? [];
$version = $application['version'] ?? 'unknown';

$out = [];
$out[] = '# n98-magerun command reference';
$out[] = '';
$out[] = sprintf(
    'Generated from `n98-magerun list --format=json` of n98-magerun %s. Do not edit, run `res/claude-skill/generate-reference.php` instead.',
    $version,
);
$out[] = '';
$out[] = '## Global options';
$out[] = '';
$out[] = 'Available for every command:';
$out[] = '';
$out[] = '| Option | Description |';
$out[] = '|---|---|';
$first = reset($data['commands']);
foreach ($globalOptions as $name) {
    $option = $first['definition']['options'][$name] ?? null;
    if ($option === null) {
        continue;
    }

    $label = $option['name'] . ($option['shortcut'] !== '' ? ', ' . $option['shortcut'] : '');
    $out[] = sprintf('| `%s` | %s |', $cell($label), $cell($option['description']));
}

$out[] = '';

foreach ($byNamespace as $namespace => $commands) {
    $out[] = '## ' . ($namespace === '_global' ? 'Commands without namespace' : $namespace);
    $out[] = '';

    usort($commands, static fn (array $a, array $b): int => strcmp($a['name'], $b['name']));

    foreach ($commands as $command) {
        $out[] = '### ' . $command['name'];
        $out[] = '';
        $out[] = $clean($command['description']);
        $out[] = '';

        $aliases = array_map(static fn (string $usage): string => strtok($usage, ' '), array_slice($command['usage'], 1));
        if ($aliases !== []) {
            $out[] = 'Aliases: `' . implode('`, `', $aliases) . '`';
            $out[] = '';
        }

        $out[] = '```';
        $out[] = 'n98-magerun ' . $command['usage'][0];
        $out[] = '```';
        $out[] = '';

        $arguments = $command['definition']['arguments'] ?? [];
        if ($arguments !== []) {
            $out[] = '| Argument | Required | Description | Default |';
            $out[] = '|---|---|---|---|';
            foreach ($arguments as $argument) {
                $out[] = sprintf(
                    '| `%s` | %s | %s | %s |',
                    $argument['name'] . ($argument['is_array'] ? '...' : ''),
                    $argument['is_required'] ? 'yes' : 'no',
                    $cell($argument['description']),
                    $cell($formatDefault($argument['default'])),
                );
            }

            $out[] = '';
        }

        $options = array_diff_key($command['definition']['options'] ?? [], array_flip($globalOptions));
        if ($options !== []) {
            $out[] = '| Option | Value | Description | Default |';
            $out[] = '|---|---|---|---|';
            foreach ($options as $option) {
                $value = $option['accept_value'] ? ($option['is_value_required'] ? 'required' : 'optional') : 'flag';
                $label = $option['name'] . ($option['shortcut'] !== '' ? ', ' . $option['shortcut'] : '');
                $out[] = sprintf(
                    '| `%s` | %s | %s | %s |',
                    $cell($label),
                    $value . ($option['is_multiple'] ? ', multiple' : ''),
                    $cell($option['description']),
                    $cell($formatDefault($option['default'])),
                );
            }

            $out[] = '';
        }

        $help = $clean($command['help'] ?? '');
        if ($help !== '' && $help !== $clean($command['description'])) {
            $out[] = '<details><summary>Help</summary>';
            $out[] = '';
            $out[] = '```';
            $out[] = $help;
            $out[] = '```';
            $out[] = '';
            $out[] = '</details>';
            $out[] = '';
        }
    }
}

$target = $argv[2];
if (!is_dir(dirname($target))) {
    mkdir(dirname($target), 0777, true);
}

file_put_contents($target, implode("\n", $out) . "\n");
fwrite(STDOUT, sprintf("Wrote %s (%d namespaces)\n", $target, count($byNamespace)));
