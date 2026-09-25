<?php

declare(strict_types=1);

namespace N98\Util;

/**
 * Utility class to snapshot a set of autoloader and restore any of the snapshot if removed.
 *
 * Based on SPL autoloader.
 *
 * @package N98\Util
 *
 * @author Tom Klingenberg (https://github.com/ktomk)
 */
class AutoloadRestorer
{
    /**
     * @var array|false
     */
    private $snapshot;

    public function __construct()
    {
        $this->snapshot = spl_autoload_functions();
    }

    /**
     * restore all autoload callbacks that have been unregistered
     */
    public function restore(): void
    {
        $unregisteredLoaders = $this->getUnregisteredLoaders();
        foreach ($unregisteredLoaders as $unregisteredLoader) {
            spl_autoload_register($unregisteredLoader);
        }
    }

    /**
     * move all snapshot callbacks in front of autoload callbacks registered later on (e.g. Magento's composer
     * autoloader), so classes of shared vendor packages are resolved with the versions n98-magerun ships
     */
    public function prepend(): void
    {
        if (!$this->snapshot) {
            return;
        }

        foreach (array_reverse($this->snapshot) as $callback) {
            spl_autoload_unregister($callback);
            spl_autoload_register($callback, throw: true, prepend: true);
        }
    }

    private function getUnregisteredLoaders(): array
    {
        $unregistered   = [];
        $current        = spl_autoload_functions();

        if (!$this->snapshot || $current === [] || $current === false) {
            return $unregistered;
        }

        foreach ($this->snapshot as $callback) {
            if (in_array($callback, $current, strict: true)) {
                continue;
            }

            $unregistered[] = $callback;
        }

        return $unregistered;
    }
}
