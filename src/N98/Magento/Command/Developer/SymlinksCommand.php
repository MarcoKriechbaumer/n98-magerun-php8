<?php

declare(strict_types=1);

namespace N98\Magento\Command\Developer;

use N98\Magento\Command\AbstractMagentoStoreConfigCommand;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Toggle symlinks command
 *
 * @package N98\Magento\Command\Developer
 */
#[AsCommand(
    name: 'dev:symlinks',
    description: 'Toggle allow symlinks setting',
)]
class SymlinksCommand extends AbstractMagentoStoreConfigCommand
{
    protected string $toggleComment = 'Symlinks';

    protected string $configPath = 'dev/template/allow_symlink';

    protected string $scope = self::SCOPE_STORE_VIEW_GLOBAL;

    protected string $falseName = 'denied';

    protected string $trueName = 'allowed';

    protected bool $withAdminStore = true;
}
