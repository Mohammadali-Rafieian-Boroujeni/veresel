<?php

defined('ABSPATH') || exit;

class VSL_Hooks
{
    public static function do($hook, ...$args)
    {
        do_action('vsl_' . $hook, ...$args);
    }

    public static function apply($hook, $value, ...$args)
    {
        return apply_filters('vsl_' . $hook, $value, ...$args);
    }
}