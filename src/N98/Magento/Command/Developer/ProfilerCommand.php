<?php

declare(strict_types=1);

namespace N98\Magento\Command\Developer;

use N98\Magento\Command\AbstractMagentoStoreConfigCommand;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Toggle profiler command
 *
 * @package N98\Magento\Command\Developer
 */
#[AsCommand(
    name: 'dev:profiler',
    description: 'Toggles profiler for debugging',
)]
class ProfilerCommand extends AbstractMagentoStoreConfigCommand
{
    protected string $configPath = 'dev/debug/profiler';

    protected string $toggleComment = 'Profiler';

    protected string $scope = self::SCOPE_STORE_VIEW_GLOBAL;
}
