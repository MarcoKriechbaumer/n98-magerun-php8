<?php

declare(strict_types=1);

namespace N98\Magento\Command\Admin;

use N98\Magento\Command\AbstractMagentoStoreConfigCommand;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Toggle admin notification command
 *
 * @package N98\Magento\Command\Admin
 */
#[AsCommand(
    name: 'admin:notifications',
    description: 'Toggles admin notifications',
)]
class DisableNotificationsCommand extends AbstractMagentoStoreConfigCommand
{
    protected string $configPath = 'advanced/modules_disable_output/Mage_AdminNotification';

    protected string $toggleComment = 'Admin Notifications';

    protected string $trueName = 'hidden';

    protected string $falseName = 'visible';

    protected string $scope = self::SCOPE_GLOBAL;
}
