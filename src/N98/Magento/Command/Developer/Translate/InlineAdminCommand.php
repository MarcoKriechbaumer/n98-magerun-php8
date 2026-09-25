<?php

declare(strict_types=1);

namespace N98\Magento\Command\Developer\Translate;

use Mage_Core_Model_Store;
use N98\Magento\Command\AbstractMagentoStoreConfigCommand;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Toggle admin inline translation command
 *
 * @package N98\Magento\Command\Developer\Translate
 */
#[AsCommand(
    name: 'dev:translate:admin',
    description: 'Toggle inline translation tool for admin',
)]
class InlineAdminCommand extends AbstractMagentoStoreConfigCommand
{
    protected string $configPath = 'dev/translate_inline/active_admin';

    protected string $toggleComment = 'Inline Translation (Admin)';

    protected string $scope = self::SCOPE_GLOBAL;

    /**
     * If required, handle the output and possible change of the developer IP restrictions
     */
    protected function _afterSave(Mage_Core_Model_Store $mageCoreModelStore, bool $disabled): void
    {
        $this->detectAskAndSetDeveloperIp($mageCoreModelStore, $disabled);
    }
}
