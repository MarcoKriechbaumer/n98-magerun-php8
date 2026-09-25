<?php

declare(strict_types=1);

namespace N98\Magento\Command\Design;

use N98\Magento\Command\AbstractMagentoStoreConfigCommand;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Toggle demo notice command
 *
 * @package N98\Magento\Command\Design
 */
#[AsCommand(
    name: 'design:demo-notice',
    description: 'Toggles demo store notice for a store view',
)]
class DemoNoticeCommand extends AbstractMagentoStoreConfigCommand
{
    protected string $configPath = 'design/head/demonotice';

    protected string $toggleComment = 'Demo Notice';

    protected string $scope = self::SCOPE_STORE_VIEW_GLOBAL;
}
