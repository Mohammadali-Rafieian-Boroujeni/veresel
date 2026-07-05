<?php

defined('ABSPATH') || exit;

class VSL_Autoloader
{
    public static function register(): void
    {
        spl_autoload_register(array(__CLASS__, 'load'));
    }

    protected static function load($class)
    {
        if (strpos($class, 'VSL_') !== 0) {
            return;
        }

        $class = strtolower(str_replace('VSL_', '', $class));

        $folders = array(
            'core',
            'engine',
            'helpers',
            'shortcode',
            'admin',
        );

        foreach ($folders as $folder) {

            $file = VSL_PATH . "includes/{$folder}/{$class}.php";

            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
}