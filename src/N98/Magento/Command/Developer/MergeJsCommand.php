<?php

declare(strict_types=1);

namespace N98\Magento\Command\Developer;

use N98\Magento\Command\AbstractMagentoStoreConfigCommand;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Toggle JS merge command
 *
 * @package N98\Magento\Command\Developer
 */
#[AsCommand(
    name: 'dev:merge-js',
    description: 'Toggles JS Merging',
)]
class MergeJsCommand extends AbstractMagentoStoreConfigCommand
{
    protected string $toggleComment = 'JS Merging';

    protected string $configPath = 'dev/js/merge_files';

    protected string $scope = self::SCOPE_STORE_VIEW_GLOBAL;
}
