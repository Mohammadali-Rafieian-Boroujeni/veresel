<?php

defined('ABSPATH') || exit;

class VSL_Query
{
    public static function products($atts = array())
    {
        $defaults = array(

            'limit'            => 12,
            'offset'           => 0,

            'category'         => '',
            'exclude_category' => '',

            'ids'              => '',
            'exclude_ids'      => '',

            'orderby'          => 'date',
            'order'            => 'DESC',

            'featured'         => false,
            'onsale'           => false,
            'instock'          => false,

        );

        $atts = shortcode_atts($defaults, $atts, 'veresel');

        $args = array(

            'post_type'           => 'product',
            'post_status'         => 'publish',

            'posts_per_page'      => absint($atts['limit']),
            'offset'              => absint($atts['offset']),

            'orderby'             => sanitize_text_field($atts['orderby']),
            'order'               => sanitize_text_field($atts['order']),

            'ignore_sticky_posts' => true,

            'tax_query'           => array(),

            'meta_query'          => array(),

        );

        /*
        |--------------------------------------------------------------------------
        | Include Categories
        |--------------------------------------------------------------------------
        */

        if (!empty($atts['category'])) {

            $args['tax_query'][] = array(

                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => array_map(
                    'trim',
                    explode(',', $atts['category'])
                )

            );

        }

        /*
        |--------------------------------------------------------------------------
        | Exclude Categories
        |--------------------------------------------------------------------------
        */

        if (!empty($atts['exclude_category'])) {

            $args['tax_query'][] = array(

                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => array_map(
                    'trim',
                    explode(',', $atts['exclude_category'])
                ),

                'operator' => 'NOT IN'

            );

        }

        /*
        |--------------------------------------------------------------------------
        | IDs
        |--------------------------------------------------------------------------
        */

        if (!empty($atts['ids'])) {

            $args['post__in'] = array_map(
                'intval',
                explode(',', $atts['ids'])
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Exclude IDs
        |--------------------------------------------------------------------------
        */

        if (!empty($atts['exclude_ids'])) {

            $args['post__not_in'] = array_map(
                'intval',
                explode(',', $atts['exclude_ids'])
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Featured
        |--------------------------------------------------------------------------
        */

        if (filter_var($atts['featured'], FILTER_VALIDATE_BOOLEAN)) {

            $args['tax_query'][] = array(

                'taxonomy' => 'product_visibility',

                'field'    => 'name',

                'terms'    => array('featured')

            );

        }

        /*
        |--------------------------------------------------------------------------
        | On Sale
        |--------------------------------------------------------------------------
        */

        if (filter_var($atts['onsale'], FILTER_VALIDATE_BOOLEAN)) {

            $args['post__in'] = wc_get_product_ids_on_sale();

        }

        /*
        |--------------------------------------------------------------------------
        | In Stock
        |--------------------------------------------------------------------------
        */

        if (filter_var($atts['instock'], FILTER_VALIDATE_BOOLEAN)) {

            $args['meta_query'][] = array(

                'key'     => '_stock_status',

                'value'   => 'instock',

                'compare' => '='

            );

        }

        return new WP_Query($args);
    }
}