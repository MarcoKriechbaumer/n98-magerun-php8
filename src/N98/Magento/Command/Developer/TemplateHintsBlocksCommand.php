<?php

declare(strict_types=1);

namespace N98\Magento\Command\Developer;

use Mage_Core_Model_Store;
use N98\Magento\Command\AbstractMagentoStoreConfigCommand;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Toggle template blocks hints command
 *
 * @package N98\Magento\Command\Developer
 */
#[AsCommand(
    name: 'dev:template-hints-blocks',
    description: 'Toggles template hints block names',
)]
class TemplateHintsBlocksCommand extends AbstractMagentoStoreConfigCommand
{
    protected string $configPath = 'dev/debug/template_hints_blocks';

    protected string $toggleComment = 'Template Hints Blocks';

    protected string $scope = self::SCOPE_STORE_VIEW;

    protected bool $withAdminStore = true;

    /**
     * If required, handle the output and possible change of the developer IP restrictions
     */
    protected function _afterSave(Mage_Core_Model_Store $mageCoreModelStore, bool $disabled): void
    {
        $this->detectAskAndSetDeveloperIp($mageCoreModelStore, $disabled);
    }
}
