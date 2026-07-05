<?php

namespace Veresel\Providers\Drivers;

use Veresel\Providers\ProviderInterface;

defined('ABSPATH') || exit;

/**
 * Products filtered by WooCommerce product tags (a separate taxonomy from
 * product categories, so this is not duplicating VSL_Query's category
 * logic - it queries a different taxonomy entirely).
 */
class TagProvider implements ProviderInterface
{
    public function get_id(): string
    {
        return 'tag';
    }

    public function get_label(): string
    {
        return __('Tag', 'veresel');
    }

    public function get_products(array $args = array()): \WP_Query
    {
        $tags = '';

        if (!empty($args['tag'])) {
            $tags = $args['tag'];
        } elseif (!empty($args['tags'])) {
            $tags = $args['tags'];
        }

        $query_args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => isset($args['limit']) ? absint($args['limit']) : 12,
            'offset'         => isset($args['offset']) ? absint($args['offset']) : 0,
        );

        if (!empty($tags)) {
            $query_args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_tag',
                    'field'    => 'slug',
                    'terms'    => array_map('trim', explode(',', (string) $tags)),
                ),
            );
        }

        return new \WP_Query($query_args);
    }
}
