<?php

declare(strict_types=1);

namespace N98\Magento\Command\Developer;

use N98\Magento\Command\AbstractMagentoStoreConfigCommand;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Toggle CSS merge command
 *
 * @package N98\Magento\Command\Developer
 */
#[AsCommand(
    name: 'dev:merge-css',
    description: 'Toggles CSS Merging',
)]
class MergeCssCommand extends AbstractMagentoStoreConfigCommand
{
    protected string $toggleComment = 'CSS Merging';

    protected string $configPath = 'dev/css/merge_css_files';

    protected string $scope = self::SCOPE_STORE_VIEW_GLOBAL;
}
