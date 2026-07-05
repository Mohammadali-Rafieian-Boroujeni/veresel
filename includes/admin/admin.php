<?php

defined('ABSPATH') || exit;

class VSL_Admin
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'menu'));
    }

    public function menu(): void
    {
        add_menu_page('Veresel', 'Veresel', 'manage_options', 'veresel', array($this, 'dashboard'), 'dashicons-images-alt2', 56);
        add_submenu_page('veresel', 'Dashboard', 'Dashboard', 'manage_options', 'veresel', array($this, 'dashboard'));
        add_submenu_page('veresel', 'Carousels', 'Carousels', 'manage_options', 'veresel-carousels', array($this, 'carousels'));
        add_submenu_page('veresel', 'Card Designer', 'Card Designer', 'manage_options', 'veresel-card', array($this, 'card'));
        add_submenu_page('veresel', 'Quick View', 'Quick View', 'manage_options', 'veresel-qv', array($this, 'qv'));
        add_submenu_page('veresel', 'Mobile', 'Mobile', 'manage_options', 'veresel-mobile', array($this, 'mobile'));
        add_submenu_page('veresel', 'Performance', 'Performance', 'manage_options', 'veresel-performance', array($this, 'perf'));
        add_submenu_page('veresel', 'Settings', 'Settings', 'manage_options', 'veresel-settings', array($this, 'settings'));
    }

    private function page(string $title, string $body): void
    {
        echo '<div class="wrap"><h1>' . esc_html($title) . '</h1><div style="background:#fff;padding:20px;border:1px solid #ddd;border-radius:8px">';
        // $body is always a developer-authored string from this file, never
        // raw user input, so it is intentionally not escaped here.
        echo wp_kses_post($body);
        echo '</div></div>';
    }

    public function dashboard(): void
    {
        $this->page('Veresel Dashboard', '<p>' . esc_html__('Welcome to Veresel.', 'veresel') . '</p>');
    }

    public function card(): void
    {
        $this->page('Card Designer', esc_html__('Coming soon.', 'veresel'));
    }

    public function qv(): void
    {
        $this->page('Quick View', esc_html__('Coming soon.', 'veresel'));
    }

    public function mobile(): void
    {
        $this->page('Mobile Layout', esc_html__('Coming soon.', 'veresel'));
    }

    public function perf(): void
    {
        $this->page('Performance', esc_html__('Coming soon.', 'veresel'));
    }

    public function settings(): void
    {
        $this->page('Settings', esc_html__('Global plugin settings.', 'veresel'));
    }

    /**
     * Carousel presets screen: lets the admin create named carousel presets
     * (consumed later via the [veresel id="..."] shortcode attribute, see
     * VSL_Shortcode::get_preset()) and set site-wide defaults.
     */
    public function carousels(): void
    {
        // Defense in depth: add_submenu_page() already restricts page access
        // to 'manage_options', but this method writes to the database, so
        // it re-checks the capability explicitly before touching anything.
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have permission to access this page.', 'veresel'));
        }

        if (isset($_POST['vsl_add'])) {

            check_admin_referer('vsl_carousel');

            $items   = get_option('vsl_carousels', array());
            $items[] = array(
                'id'       => time(),
                'name'     => sanitize_text_field(wp_unslash($_POST['name'] ?? '')),
                'category' => sanitize_text_field(wp_unslash($_POST['category'] ?? '')),
                'limit'    => absint($_POST['limit'] ?? 0),
            );

            update_option('vsl_carousels', $items);

            echo '<div class="updated"><p>' . esc_html__('Carousel created.', 'veresel') . '</p></div>';
        }

        if (isset($_POST['vsl_save'])) {

            check_admin_referer('vsl_carousel');

            update_option('vsl_default_limit', absint($_POST['limit'] ?? 0));
            update_option('vsl_default_mobile', (float) sanitize_text_field(wp_unslash($_POST['mobile'] ?? '')));

            echo '<div class="updated"><p>' . esc_html__('Saved.', 'veresel') . '</p></div>';
        }

        $limit  = (int) get_option('vsl_default_limit', 8);
        $mobile = get_option('vsl_default_mobile', '1.2');
        $items  = get_option('vsl_carousels', array());

        echo '<div class="wrap"><h1>' . esc_html__('Carousels', 'veresel') . '</h1>';

        echo '<h2>' . esc_html__('Add Carousel', 'veresel') . '</h2><form method="post">';
        wp_nonce_field('vsl_carousel');
        echo '<p><input name="name" placeholder="' . esc_attr__('Name', 'veresel') . '"></p>';
        echo '<p><input name="category" placeholder="' . esc_attr__('Category slug', 'veresel') . '" class="regular-text"></p>';
        echo '<p><input name="limit" type="number" value="8"></p>';
        echo '<p><button class="button button-primary" name="vsl_add">' . esc_html__('Create', 'veresel') . '</button></p></form>';

        if ($items) {

            echo '<hr><table class="widefat"><tr><th>' . esc_html__('Name', 'veresel') . '</th><th>' . esc_html__('Category', 'veresel') . '</th><th>' . esc_html__('Limit', 'veresel') . '</th><th>' . esc_html__('Shortcode', 'veresel') . '</th></tr>';

            foreach ($items as $c) {
                echo '<tr><td>' . esc_html($c['name']) . '</td><td>' . esc_html($c['category']) . '</td><td>' . intval($c['limit']) . '</td><td>[veresel id=&quot;' . intval($c['id']) . '&quot;]</td></tr>';
            }

            echo '</table>';
        }

        echo '<form method="post">';
        wp_nonce_field('vsl_carousel');
        echo '<table class="form-table">';
        echo '<tr><th>' . esc_html__('Default products', 'veresel') . '</th><td><input name="limit" type="number" value="' . esc_attr($limit) . '"></td></tr>';
        echo '<tr><th>' . esc_html__('Mobile slides', 'veresel') . '</th><td><input name="mobile" value="' . esc_attr($mobile) . '"></td></tr>';
        echo '</table><p><input class="button button-primary" name="vsl_save" type="submit" value="' . esc_attr__('Save', 'veresel') . '"></p></form></div>';
    }
}
