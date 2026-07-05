<?php

namespace Veresel\Providers\Drivers;

use Veresel\Providers\ProviderInterface;

defined('ABSPATH') || exit;

/**
 * Products related to a given (or the current) product, using WooCommerce's
 * own relatedness algorithm (wc_get_related_products()).
 */
class RelatedProductsProvider implements ProviderInterface
{
    public function get_id(): string
    {
        return 'related';
    }

    public function get_label(): string
    {
        return __('Related Products', 'veresel');
    }

    public function get_products(array $args = array()): \WP_Query
    {
        $product_id = isset($args['product_id']) ? absint($args['product_id']) : self::current_product_id();
        $limit      = isset($args['limit']) ? absint($args['limit']) : 12;

        if (!$product_id || !function_exists('wc_get_related_products')) {
            return \VSL_Query::from_ids(array());
        }

        $ids = wc_get_related_products($product_id, $limit);

        return \VSL_Query::from_ids($ids);
    }

    private static function current_product_id(): int
    {
        global $product;

        if ($product instanceof \WC_Product) {
            return $product->get_id();
        }

        return is_singular('product') ? (int) get_the_ID() : 0;
    }
}
