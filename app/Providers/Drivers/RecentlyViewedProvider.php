<?php

namespace Veresel\Providers\Drivers;

use Veresel\Providers\ProviderInterface;

defined('ABSPATH') || exit;

/**
 * Products the current visitor has actually viewed, read from WooCommerce's
 * own 'woocommerce_recently_viewed' cookie (set automatically by
 * WooCommerce on every single product page).
 */
class RecentlyViewedProvider implements ProviderInterface
{
    public function get_id(): string
    {
        return 'recently_viewed';
    }

    public function get_label(): string
    {
        return __('Recently Viewed', 'veresel');
    }

    public function get_products(array $args = array()): \WP_Query
    {
        $limit = isset($args['limit']) ? absint($args['limit']) : 12;

        if (empty($_COOKIE['woocommerce_recently_viewed'])) {
            return \VSL_Query::from_ids(array());
        }

        $viewed = wp_parse_id_list(
            wp_unslash($_COOKIE['woocommerce_recently_viewed'])
        );

        // Most recently viewed first.
        $viewed = array_reverse($viewed);
        $viewed = array_slice($viewed, 0, $limit);

        return \VSL_Query::from_ids($viewed);
    }
}
