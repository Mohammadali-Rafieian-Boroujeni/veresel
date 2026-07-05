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
}, 20);

require_once __DIR__ . '/widget.php';

$registered = false;
$register_veresel_widget = function ($widgets_manager = null) use (&$registered) {
    if (!class_exists('\VSL_Elementor_Widget') || $registered) {
        return;
    }

    if ($widgets_manager === null && class_exists('\Elementor\Plugin') && isset(\Elementor\Plugin::$instance->widgets_manager)) {
        $widgets_manager = \Elementor\Plugin::$instance->widgets_manager;
    }

    if ($widgets_manager instanceof \Elementor\Widgets_Manager || $widgets_manager instanceof \Elementor\Widgets_Manager) {
        $widget = new \VSL_Elementor_Widget();

        if (method_exists($widgets_manager, 'register')) {
            $widgets_manager->register($widget);
        } elseif (method_exists($widgets_manager, 'register_widget_type')) {
            $widgets_manager->register_widget_type($widget);
        }

        $registered = true;
    }
};

// Elementor >= 3.5
add_action('elementor/widgets/register', function ($widgets_manager) use ($register_veresel_widget) {
    $register_veresel_widget($widgets_manager);
});

// Elementor < 3.5 (kept for backward compatibility)
add_action('elementor/widgets/widgets_registered', function () use ($register_veresel_widget) {
    $register_veresel_widget();
});

if (did_action('elementor/loaded')) {
    $register_veresel_widget();
}
