<?php

namespace Veresel\Providers\Drivers;

use Veresel\Providers\ProviderInterface;

defined('ABSPATH') || exit;

/**
 * Products ordered by WooCommerce's real "total_sales" meta.
 */
class BestSellingProvider implements ProviderInterface
{
    public function get_id(): string
    {
        return 'best_selling';
    }

    public function get_label(): string
    {
        return __('Best Selling', 'veresel');
    }

    public function get_products(array $args = array()): \WP_Query
    {
        $query_args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => isset($args['limit']) ? absint($args['limit']) : 12,
            'offset'         => isset($args['offset']) ? absint($args['offset']) : 0,
            'meta_key'       => 'total_sales',
            'orderby'        => 'meta_value_num',
            'order'          => 'DESC',
        );

        if (!empty($args['category'])) {
            $query_args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => array_map('trim', explode(',', (string) $args['category'])),
                ),
            );
        }

        return new \WP_Query($query_args);
    }
}
