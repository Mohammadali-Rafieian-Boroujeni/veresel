<?php

namespace Veresel\Providers\Drivers;

use Veresel\Providers\ProviderInterface;

defined('ABSPATH') || exit;

/**
 * A product's real WooCommerce cross-sells (the items shown in the cart,
 * configured on the Linked Products tab of the product edit screen).
 */
class CrossSellProvider implements ProviderInterface
{
    public function get_id(): string
    {
        return 'cross_sell';
    }

    public function get_label(): string
    {
        return __('Cross-sells', 'veresel');
    }

    public function get_products(array $args = array()): \WP_Query
    {
        $product_id = isset($args['product_id']) ? absint($args['product_id']) : self::current_product_id();

        if (!$product_id || !function_exists('wc_get_product')) {
            return \VSL_Query::from_ids(array());
        }

        $product = wc_get_product($product_id);

        if (!$product) {
            return \VSL_Query::from_ids(array());
        }

        $ids = $product->get_cross_sell_ids();

        if (!empty($args['limit'])) {
            $ids = array_slice($ids, 0, absint($args['limit']));
        }

        return \VSL_Query::from_ids($ids);
    }

    private static function current_product_id(): int
    {
        return is_singular('product') ? (int) get_the_ID() : 0;
    }
}
