<?php

declare(strict_types=1);

namespace N98\Magento\Command\Installer;

use N98\Magento\Command\AbstractMagentoCommand;
use N98\Magento\Command\SubCommand\SubCommandFactory;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Install command
 *
 * @codeCoverageIgnore  - Travis server uses installer to create a new shop. If it not works complete build fails.
 * @package N98\Magento\Command\Installer
 */
class InstallCommand extends AbstractMagentoCommand
{
    protected array $commandConfig;

    protected SubCommandFactory $subCommandFactory;

    protected function configure(): void
    {
        $this
            ->setName('install')
            ->addOption('magentoVersion', shortcut: null, mode: InputOption::VALUE_OPTIONAL, description: 'Magento version')
            ->addOption(
                'magentoVersionByName',
                shortcut: null,
                mode: InputOption::VALUE_OPTIONAL,
                description: 'Magento version name instead of order number',
            )
            ->addOption('installationFolder', shortcut: null, mode: InputOption::VALUE_OPTIONAL, description: 'Installation folder')
            ->addOption('dbHost', shortcut: null, mode: InputOption::VALUE_OPTIONAL, description: 'Database host')
            ->addOption('dbUser', shortcut: null, mode: InputOption::VALUE_OPTIONAL, description: 'Database user')
            ->addOption('dbPass', shortcut: null, mode: InputOption::VALUE_OPTIONAL, description: 'Database password')
            ->addOption('dbName', shortcut: null, mode: InputOption::VALUE_OPTIONAL, description: 'Database name')
            ->addOption('dbPort', shortcut: null, mode: InputOption::VALUE_OPTIONAL, description: 'Database port', default: 3306)
            ->addOption('installSampleData', shortcut: null, mode: InputOption::VALUE_OPTIONAL, description: 'Install sample data')
            ->addOption(
                'useDefaultConfigParams',
                shortcut: null,
                mode: InputOption::VALUE_OPTIONAL,
                description: 'Use default installation parameters defined in the yaml file',
            )
            ->addOption('baseUrl', shortcut: null, mode: InputOption::VALUE_OPTIONAL, description: 'Installation base url')
            ->addOption(
                'replaceHtaccessFile',
                shortcut: null,
                mode: InputOption::VALUE_OPTIONAL,
                description: 'Generate htaccess file (for non vhost environment)',
            )
            ->addOption(
                'noDownload',
                shortcut: null,
                mode: InputOption::VALUE_NONE,
                description: 'If set skips download step. Used when installationFolder is already a Magento installation that has ' .
                'to be installed on the given database.',
            )
            ->addOption(
                'only-download',
                shortcut: null,
                mode: InputOption::VALUE_NONE,
                description: 'Downloads (and extracts) source code',
            )
            ->addOption(
                'forceUseDb',
                shortcut: null,
                mode: InputOption::VALUE_NONE,
                description: 'If --forceUseDb passed, force to use given database if it already exists.',
            )
            ->addOption(
                'composer-use-same-php-binary',
                shortcut: null,
                mode: InputOption::VALUE_NONE,
                description: 'If --composer-use-same-php-binary passed, will invoke composer with the same PHP binary',
            )
            ->setDescription('Install magento');
    }

    public function getHelp(): string
    {
        return <<<HELP
* Download Magento by a list of git repos and zip files (mageplus, 
  magelte, official community packages).
* Try to create database if it does not exist.
* Installs Magento sample data if available (since version 1.2.0).
* Starts Magento installer
* Sets rewrite base in .htaccess file

Example of an unattended Magento CE 2.0.0 installation:

   $ n98-magerun2.phar install --dbHost="localhost" --dbUser="mydbuser" \
     --dbPass="mysecret" --dbName="magentodb" --installSampleData=yes \
     --useDefaultConfigParams=yes \
     --magentoVersionByName="magento-ce-2.0.0" \
     --installationFolder="magento" --baseUrl="http://magento.localdomain/"

Additionally, with --noDownload option you can install Magento working 
copy already stored in --installationFolder on the given database.

See it in action: https://youtu.be/WU-CbJ86eQc
HELP;
    }

    public function isEnabled(): bool
    {
        return function_exists('exec');
    }

    /**
     * @throws RuntimeException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->commandConfig = $this->getCommandConfig();
        $this->writeSection($output, 'Magento Installation');

        $subCommandFactory = $this->createSubCommandFactory(
            $input,
            $output,
            'N98\Magento\Command\Installer\SubCommand', // sub-command namespace
        );

        // @todo load commands from config
        $subCommandFactory->create('PreCheckPhp')->execute();
        $subCommandFactory->create('SelectMagentoVersion')->execute();
        $subCommandFactory->create('ChooseInstallationFolder')->execute();
        $subCommandFactory->create('InstallComposer')->execute();

        $subCommandFactory->create('DownloadMagento')->execute();
        if ($input->getOption('only-download')) {
            return Command::SUCCESS;
        }

        $subCommandFactory->create('CreateDatabase')->execute();
        $subCommandFactory->create('RemoveEmptyFolders')->execute();
        $subCommandFactory->create('SetDirectoryPermissions')->execute();
        $subCommandFactory->create('InstallMagento')->execute();
        $subCommandFactory->create('RewriteHtaccessFile')->execute();
        $subCommandFactory->create('InstallSampleData')->execute();
        $subCommandFactory->create('PostInstallation')->execute();
        $output->writeln('<info>Successfully installed magento</info>');

        return Command::SUCCESS;
    }
}
