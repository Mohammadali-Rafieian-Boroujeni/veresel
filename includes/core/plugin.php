<?php

defined('ABSPATH') || exit;

class VSL_Plugin
{

    private static $instance = null;

    public static function instance(): self
    {

        if ( self::$instance === null ) {

            self::$instance = new self();

        }

        return self::$instance;

    }

    private function __construct()
    {

        new VSL_Loader();

    }

}