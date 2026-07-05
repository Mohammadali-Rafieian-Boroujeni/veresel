<?php

namespace Veresel\Providers;

defined('ABSPATH') || exit;

/**
 * Contract every product source ("Provider") must implement, so the rest
 * of the plugin (VSL_Shortcode, VSL_Elementor_Widget, ProviderEngine) can
 * treat every product source identically regardless of where its data
 * actually comes from (a WP_Query, a WooCommerce helper, a cookie, etc.).
 */
interface ProviderInterface
{
    /**
     * Fetch products for this source.
     *
     * @param array $args Normalized args (limit, offset, category, ids, product_id, ...).
     * @return \WP_Query
     */
    public function get_products(array $args = array()): \WP_Query;

    /**
     * Unique, stable identifier used to reference this provider, e.g. from
     * a shortcode's `source="best_selling"` attribute or the Elementor
     * widget's "Products Source" control.
     */
    public function get_id(): string;

    /**
     * Human-readable label shown in the admin/Elementor UI.
     */
    public function get_label(): string;
}
