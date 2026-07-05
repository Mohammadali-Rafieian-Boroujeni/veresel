<?php

defined('ABSPATH') || exit;

/**
 * Elementor bridge: registers the "Veresel" widget category and the
 * product carousel widget itself when Elementor is active.
 */

add_action('elementor/elements/categories_registered', function ($manager) {
    if (method_exists($manager, 'add_category')) {
        $manager->add_category('veresel', array(
            'title' => 'Veresel',
            'icon'  => 'fa fa-shopping-cart',
        ));
    }
});

require_once __DIR__ . '/widget.php';

// Elementor >= 3.5
add_action('elementor/widgets/register', function ($widgets_manager) {
    $widgets_manager->register(new \VSL_Elementor_Widget());
});

// Elementor < 3.5 (kept for backward compatibility)
add_action('elementor/widgets/widgets_registered', function () {
    if (
        class_exists('\Elementor\Plugin')
        && isset(\Elementor\Plugin::$instance->widgets_manager)
        && method_exists(\Elementor\Plugin::$instance->widgets_manager, 'register_widget_type')
    ) {
        \Elementor\Plugin::$instance->widgets_manager->register_widget_type(new \VSL_Elementor_Widget());
    }
});
