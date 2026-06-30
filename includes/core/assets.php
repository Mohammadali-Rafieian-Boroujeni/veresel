<?php

defined('ABSPATH') || exit;

class VSL_Assets
{
    public function __construct()
    {
        add_action(
            'wp_enqueue_scripts',
            array($this, 'enqueue')
        );
    }

    public function enqueue()
    {
        global $post;

        if (
            !is_singular() ||
            !isset($post) ||
            !has_shortcode($post->post_content, 'veresel')
        ) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Styles
        |--------------------------------------------------------------------------
        */

        wp_enqueue_style(
            'veresel-swiper',
            VSL_URL . 'assets/vendor/swiper/swiper-bundle.min.css',
            array(),
            VSL_VERSION
        );

        wp_enqueue_style(
            'veresel-carousel',
            VSL_URL . 'assets/css/carousel.css',
            array('veresel-swiper'),
            VSL_VERSION
        );

        wp_enqueue_style(
            'veresel-quick-view',
            VSL_URL . 'assets/css/quick-view.css',
            array(),
            VSL_VERSION
        );

        /*
        |--------------------------------------------------------------------------
        | Scripts
        |--------------------------------------------------------------------------
        */

        wp_enqueue_script(
            'veresel-swiper',
            VSL_URL . 'assets/vendor/swiper/swiper-bundle.min.js',
            array(),
            VSL_VERSION,
            true
        );

        wp_enqueue_script(
            'veresel-carousel',
            VSL_URL . 'assets/js/swiper.js',
            array('veresel-swiper'),
            VSL_VERSION,
            true
        );

        wp_enqueue_script(
            'veresel-quick-view',
            VSL_URL . 'assets/js/quick-view.js',
            array(
                'jquery',
                'wc-add-to-cart',
                'wc-cart-fragments'
            ),
            VSL_VERSION,
            true
        );

        wp_localize_script(
            'veresel-quick-view',
            'vsl_ajax',
            array(
                'url'      => admin_url('admin-ajax.php'),
                'cart_url' => wc_get_cart_url(),
                'nonce'    => wp_create_nonce('vsl_nonce')
            )
        );
    }
}