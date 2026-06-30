<?php

defined('ABSPATH') || exit;

class VSL_Loader
{
    public function __construct()
    {
        new VSL_Assets();

        new VSL_Shortcode();
        new VSL_Ajax();

        if (is_admin()) {

            new VSL_Admin();

        }
    }
}