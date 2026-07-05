<?php

defined('ABSPATH') || exit;

class VSL_Ajax
{
    public function __construct()
    {
        add_action(
            'wp_ajax_vsl_quick_view',
            array($this,'quick')
        );

        add_action(
            'wp_ajax_nopriv_vsl_quick_view',
            array($this,'quick')
        );
    }

    public function quick(): void
    {
        check_ajax_referer('vsl_nonce', 'nonce');
        require_once VSL_PATH.'includes/ajax/quick-view.php';
    }
}