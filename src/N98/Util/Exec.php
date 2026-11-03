<?php

declare(strict_types=1);

namespace N98\Util;

use RuntimeException;

/**
 * Class Exec
 *
 * @package N98\Util
 */
class Exec
{
    /**
     * @var string
     */
    public const REDIRECT_STDERR_TO_STDOUT = ' 2>&1';

    /**
     * @var int (0-255)
     */
    public const CODE_CLEAN_EXIT = 0;

    /**
     * Every error in a pipe will be exited with an error code
     */
    public const SET_O_PIPEFAIL = 'set -o pipefail;';

    /**
     * File descriptor on which the command can read $fd3Input, as /dev/fd/3
     */
    public const INPUT_FILE_DESCRIPTOR = 3;

    /**
     * @param string|null $fd3Input data for the command on file descriptor 3, e.g. a MySQL option file with the
     *                              password, so it is neither part of the command line nor written to disk
     */
    public static function run(
        string $command,
        ?string &$output = null,
        ?int &$returnCode = null,
        ?string $fd3Input = null,
    ): void {
        if (!self::allowed() || ($fd3Input !== null && !function_exists('proc_open'))) {
            $message = sprintf("No PHP exec(), can not execute command '%s'.", self::maskPasswords($command));
            throw new RuntimeException($message);
        }

        if (OperatingSystem::isBashCompatibleShell() && self::isPipefailOptionAvailable()) {
            $command = self::SET_O_PIPEFAIL . $command;
        }

        $command .= self::REDIRECT_STDERR_TO_STDOUT;

        if ($fd3Input === null) {
            exec($command, $outputArray, $returnCode);
        } else {
            $stdout = '';
            $returnCode = self::open($command, [1 => ['pipe', 'w']], $fd3Input, $stdout);
            // same lines as exec() returns
            $outputArray = $stdout === '' ? [] : array_map(rtrim(...), explode("\n", rtrim($stdout, "\n")));
        }

        $output = self::parseCommandOutput($outputArray);

        if ($returnCode !== self::CODE_CLEAN_EXIT) {
            throw new RuntimeException(
                sprintf('Exit status %d for command %s. Output was: %s', $returnCode, self::maskPasswords($command), $output),
            );
        }
    }

    /**
     * Runs a command on the terminal of this process (standard input, output and error), e.g. an interactive
     * client, and returns its exit status.
     *
     * @param string|null $fd3Input see run()
     */
    public static function passthru(string $command, ?string $fd3Input = null): int
    {
        if (!function_exists('proc_open')) {
            $message = sprintf("No PHP proc_open(), can not execute command '%s'.", self::maskPasswords($command));
            throw new RuntimeException($message);
        }

        return self::open($command, [0 => STDIN, 1 => STDOUT, 2 => STDERR], $fd3Input);
    }

    /**
     * Exec class is allowed to run
     */
    public static function allowed(): bool
    {
        return function_exists('exec');
    }

    /**
     * Replaces the value of mysql/mysqldump "-p<password>" arguments, so the
     * database password does not end up in error messages.
     *
     * Matches the value as quoted by escapeshellarg(): single quotes on
     * POSIX shells (with ' escaped as '\''), double quotes on Windows.
     */
    private static function maskPasswords(string $command): string
    {
        return (string) preg_replace(
            '/(?<!\S)-p(?:\'(?:[^\']|\'\\\\\'\')*\'|"[^"]*")/',
            "-p'***'",
            $command,
        );
    }

    /**
     * Descriptors that are not in $descriptors are inherited from this process.
     *
     * @param array<int, mixed> $descriptors
     * @param string|null $stdout content of descriptor 1, if it is a pipe
     */
    private static function open(string $command, array $descriptors, ?string $fd3Input, ?string &$stdout = null): int
    {
        if ($fd3Input !== null) {
            $descriptors[self::INPUT_FILE_DESCRIPTOR] = ['pipe', 'r'];
        }

        $process = proc_open($command, $descriptors, $pipes);
        if (!is_resource($process)) {
            throw new RuntimeException(sprintf("Can not execute command '%s'.", self::maskPasswords($command)));
        }

        if ($fd3Input !== null) {
            // fits into the pipe buffer, so this does not block even if the command never reads it. If the
            // command already exited, the write fails and the command's exit status reports the error.
            @fwrite($pipes[self::INPUT_FILE_DESCRIPTOR], $fd3Input);
            fclose($pipes[self::INPUT_FILE_DESCRIPTOR]);
        }

        if (isset($pipes[1])) {
            $stdout = (string) stream_get_contents($pipes[1]);
            fclose($pipes[1]);
        }

        return proc_close($process);
    }

    /**
     * string from array of strings representing one line per entry
     * @param list<string> $commandOutput
     */
    private static function parseCommandOutput(array $commandOutput): string
    {
        return implode(PHP_EOL, $commandOutput) . PHP_EOL;
    }

    private static function isPipefailOptionAvailable(): bool
    {
        exec('set -o | grep pipefail 2>&1', $output, $returnCode);
        return $returnCode === self::CODE_CLEAN_EXIT;
    }
}
