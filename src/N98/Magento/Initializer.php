<?php

declare(strict_types=1);

namespace N98\Magento;

use N98\Util\AutoloadRestorer;
use RuntimeException;

/**
 * Magento initializer (Magento 1)
 *
 * @package N98\Magento
 *
 * @author Tom Klingenberg (https://github.com/ktomk)
 */
class Initializer
{
    /**
     * Bootstrap filename
     */
    public const PATH_APP_BOOTSTRAP_PHP = 'app/bootstrap.php';

    /**
     * Mage filename
     */
    public const PATH_APP_MAGE_PHP = 'app/Mage.php';

    /**
     * Mage classname
     */
    public const CLASS_MAGE = 'Mage';

    /**
     * @var string path to Magento root directory
     */
    private string $magentoPath;

    /**
     * Initializer constructor.
     */
    public function __construct(string $magentoPath)
    {
        $this->magentoPath = $magentoPath;
    }

    /**
     * Bootstrap Magento application
     */
    public static function bootstrap(string $magentoPath): void
    {
        $initializer = new Initializer($magentoPath);
        $initializer->requireMage();
    }

    /**
     * Require app/Mage.php if class Mage does not yet exists. Preserves auto-loaders
     *
     * @see \Mage (final class)
     */
    public function requireMage(): void
    {
        if (class_exists(self::CLASS_MAGE, autoload: false)) {
            return;
        }

        $this->requireOnce();

        if (!class_exists(self::CLASS_MAGE, autoload: false)) {
            throw new RuntimeException(sprintf('Failed to load definition of "%s" class', self::CLASS_MAGE));
        }
    }

    /**
     * Require app/Mage.php in its own scope while preserving all autoloader.
     */
    private function requireOnce(): void
    {
        // Create a new AutoloadRestorer to capture current auto-loaders
        $autoloadRestorer = new AutoloadRestorer();

        $pharWrapperRegistered = in_array('phar', stream_get_wrappers(), strict: true);

        $path = $this->magentoPath . '/' . self::PATH_APP_BOOTSTRAP_PHP;
        initialiser_require_once($path);

        // OpenMage bootstrap unregisters the phar stream wrapper, n98-magerun.phar needs it to load its own classes
        if ($pharWrapperRegistered && !in_array('phar', stream_get_wrappers(), strict: true)) {
            stream_wrapper_restore('phar');
        }

        $path = $this->magentoPath . '/' . self::PATH_APP_MAGE_PHP;
        initialiser_require_once($path);

        // Restore auto-loaders that might be removed by extensions that overwrite Varien/Autoload
        $autoloadRestorer->restore();

        // Magento's composer autoloader is registered in front, give n98-magerun's auto-loaders priority again
        $autoloadRestorer->prepend();
    }
}

/**
 * use require-once inside a function with its own variable scope and no $this (?)
 */
function initialiser_require_once(): void
{
    require_once func_get_arg(0);
}
