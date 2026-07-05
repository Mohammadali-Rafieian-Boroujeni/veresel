<?php
require_once __DIR__ . '/vendor/autoload.php';

/*
Plugin Name:       Veresel
Plugin URI:        https://varsakala.com/
Description:       Product carousel for WooCommerce, with Elementor widget support.
Version:           1.1.0
Requires at least: 6.0
Requires PHP:      8.0
WC requires at least: 8.0
Author:            Varsakala
Author URI:        https://varsakala.com/
License:           GPL-2.0-or-later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:       veresel
Domain Path:       /languages
*/

defined('ABSPATH') || exit;

// Kept as a real, static version string (not time()) so browsers can
// actually cache versioned assets between requests.
define('VSL_VERSION', '1.1.0');

define('VSL_PATH', plugin_dir_path(__FILE__));

define('VSL_URL', plugin_dir_url(__FILE__));

/**
 * WooCommerce dependency check.
 *
 * Every core class (VSL_Query, VSL_Renderer, quick-view.php, the
 * Providers...) calls WooCommerce functions/classes, but only from inside
 * method bodies that run on later hooks (shortcode render, ajax request,
 * etc.) - requiring bootstrap.php itself only registers those hooks and
 * never calls a WooCommerce function eagerly, so it is safe to always
 * require it here (this also keeps the 'elementor/loaded' hook registered
 * early - see bootstrap.php - instead of racing Elementor's own
 * plugins_loaded callback).
 */

register_activation_hook(__FILE__, function () {
    if (!class_exists('WooCommerce')) {
        deactivate_plugins(plugin_basename(__FILE__));

        wp_die(
            esc_html__('Veresel requires WooCommerce to be installed and active.', 'veresel'),
            esc_html__('Plugin activation error', 'veresel'),
            array('back_link' => true)
        );
    }
});

add_action('admin_notices', function () {
    if (class_exists('WooCommerce') || !current_user_can('activate_plugins')) {
        return;
    }

    echo '<div class="notice notice-error"><p>' .
        esc_html__('Veresel is active but WooCommerce is not. Please install and activate WooCommerce.', 'veresel') .
        '</p></div>';
});

add_action('init', function () {
    load_plugin_textdomain('veresel', false, dirname(plugin_basename(__FILE__)) . '/languages');
});

require_once VSL_PATH . 'bootstrap.php';
