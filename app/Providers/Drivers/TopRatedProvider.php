<?php

namespace Veresel\Providers\Drivers;

use Veresel\Providers\ProviderInterface;

defined('ABSPATH') || exit;

/**
 * Products ordered by WooCommerce's real average-rating meta.
 */
class TopRatedProvider implements ProviderInterface
{
    public function get_id(): string
    {
        return 'top_rated';
    }

    public function get_label(): string
    {
        return __('Top Rated', 'veresel');
    }

    public function get_products(array $args = array()): \WP_Query
    {
        $query_args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => isset($args['limit']) ? absint($args['limit']) : 12,
            'offset'         => isset($args['offset']) ? absint($args['offset']) : 0,
            'meta_key'       => '_wc_average_rating',
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
