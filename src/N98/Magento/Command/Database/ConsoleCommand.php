<?php

declare(strict_types=1);

namespace N98\Magento\Command\Database;

use N98\Util\Exec;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Database console command
 *
 * @package N98\Magento\Command\Database
 */
class ConsoleCommand extends AbstractDatabaseCommand
{
    protected function configure(): void
    {
        $this
            ->setName('db:console')
            ->setAliases(['mysql-client'])
            ->addOption(
                'use-mycli-instead-of-mysql',
                shortcut: null,
                mode: InputOption::VALUE_NONE,
                description: 'Use `mycli` as the MySQL client instead of `mysql`',
            )
            ->addOption(
                'no-auto-rehash',
                shortcut: null,
                mode: InputOption::VALUE_NONE,
                description: 'Same as `-A` option to MySQL client to turn off ' .
                'auto-complete (avoids long initial connection time).',
            )
            ->setDescription('Opens mysql client by database config from local.xml');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->detectDbSettings($output);

        $useMycli = (bool) $input->getOption('use-mycli-instead-of-mysql');
        $databaseHelper = $this->getDatabaseHelper();

        // the mysql option file with the password has to be the first argument; mycli gets the password as -p
        $args = [
            $useMycli ? 'mycli' : 'mysql',
            $databaseHelper->getMysqlClientToolConnectionString(passwordOnFd3: !$useMycli),
        ];

        if ($input->getOption('no-auto-rehash')) {
            $args[] = '--no-auto-rehash';
        }

        Exec::passthru(implode(' ', $args), $useMycli ? null : $databaseHelper->getMysqlClientToolOptionFile());

        return Command::SUCCESS;
    }
}
