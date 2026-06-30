<?php

defined('ABSPATH') || exit;

class VSL_Query
{

    public static function products($atts = array())
    {

        $defaults = array(

            'limit'     => 12,

            'category'  => '',

            'orderby'   => 'date',

            'order'     => 'DESC'

        );

        $atts = wp_parse_args($atts, $defaults);

        $args = array(

            'post_type'      => 'product',

            'post_status'    => 'publish',

            'posts_per_page' => absint($atts['limit']),

            'orderby'        => sanitize_text_field($atts['orderby']),

            'order'          => sanitize_text_field($atts['order'])

        );

        if (!empty($atts['category'])) {

            $args['tax_query'] = array(

                array(

                    'taxonomy' => 'product_cat',

                    'field'    => 'slug',

                    'terms'    => sanitize_text_field($atts['category'])

                )

            );

        }

        return new WP_Query($args);

    }

}