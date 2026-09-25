<?php

declare(strict_types=1);

namespace N98\Magento\Command\Developer\Log;

use N98\Magento\Command\AbstractMagentoStoreConfigCommand;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Toggle log command
 *
 * @package N98\Magento\Command\Developer\Log
 */
#[AsCommand(
    name: 'dev:log',
    description: 'Toggle development log (system.log, exception.log)',
)]
class LogCommand extends AbstractMagentoStoreConfigCommand
{
    protected string $toggleComment = 'Development Log';

    protected string $configPath = 'dev/log/active';

    protected string $scope = self::SCOPE_STORE_VIEW_GLOBAL;
}
