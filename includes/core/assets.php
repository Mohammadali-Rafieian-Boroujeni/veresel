<?php

defined('ABSPATH') || exit;

class VSL_Assets
{
    public function __construct()
    {
        add_action(
            'wp_enqueue_scripts',
            array($this, 'maybe_enqueue')
        );
    }

    /**
     * Decide whether the current request needs carousel assets, covering
     * both the classic shortcode and pages built with the Elementor widget.
     */
    public function maybe_enqueue(): void
    {
        global $post;

        $needs_assets = is_singular()
            && isset($post)
            && has_shortcode($post->post_content, 'veresel');

        if (!$needs_assets && did_action('elementor/loaded')) {
            $needs_assets = self::page_uses_elementor_widget();
        }

        if (!$needs_assets) {
            return;
        }

        self::enqueue();
    }

    /**
     * Detect whether the current post (built with Elementor) contains the
     * Veresel carousel widget, including inside the Elementor editor itself.
     */
    protected static function page_uses_elementor_widget(): bool
    {
        global $post;

        if (!isset($post)) {
            return false;
        }

        if (
            class_exists('\Elementor\Plugin')
            && isset(\Elementor\Plugin::$instance->preview)
            && \Elementor\Plugin::$instance->preview->is_preview_mode()
        ) {
            return true;
        }

        $data = get_post_meta($post->ID, '_elementor_data', true);

        return is_string($data) && false !== strpos($data, 'veresel_carousel');
    }

    /**
     * Register/enqueue the carousel + quick view styles and scripts.
     *
     * Safe to call more than once (e.g. from both the shortcode detection
     * path and the Elementor widget render path); WordPress dedupes
     * handles automatically so assets are never printed twice.
     */
    public static function enqueue(): void
    {
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