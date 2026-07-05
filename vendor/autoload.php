<?php

/**
 * Manual PSR-4 autoloader for the `Veresel\` namespace (see composer.json,
 * which maps "Veresel\\" => "app/").
 *
 * If you run `composer install` in this plugin, Composer will generate a
 * real vendor/autoload.php and overwrite this file - it resolves classes
 * identically, since it follows the exact same PSR-4 mapping. This manual
 * version exists so the plugin works out of the box even without Composer
 * ever being run (e.g. plain FTP/zip install on a shared host).
 */

spl_autoload_register(function ($class) {
    $prefix   = 'Veresel\\';
    $base_dir = __DIR__ . '/../app/';

    $len = strlen($prefix);

    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file           = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});
