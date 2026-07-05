<?php

defined('ABSPATH') || exit;

class VSL_Query
{
    /**
     * Default cache lifetime in seconds. Filterable via 'vsl_query_cache_ttl'.
     */
    const CACHE_TTL = 300;

    /**
     * Register cache-busting hooks once. Called from bootstrap.php right
     * after this file is required.
     */
    public static function hooks(): void
    {
        $bump = array(self::class, 'bump_cache_generation');

        add_action('save_post_product', $bump);
        add_action('woocommerce_update_product', $bump);
        add_action('woocommerce_new_product', $bump);
        add_action('delete_post', $bump);
        add_action('woocommerce_product_set_stock', $bump);
        add_action('woocommerce_product_set_stock_status', $bump);
        add_action('woocommerce_variation_set_stock', $bump);
    }

    /**
     * Invalidate every cached carousel query in one cheap operation, by
     * bumping a generation counter that is part of every cache key -
     * avoids having to enumerate/delete individual transients.
     */
    public static function bump_cache_generation(): void
    {
        $gen = (int) get_option('vsl_cache_gen', 1);
        update_option('vsl_cache_gen', $gen + 1, false);
    }

    public static function products(array $atts = array()): WP_Query
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

        $cache_key = self::cache_key($atts);

        $cached_ids = get_transient($cache_key);

        if (is_array($cached_ids)) {
            return self::query_from_cached_ids($cached_ids, $atts);
        }

        $query = new WP_Query(self::build_args($atts));

        $ttl = (int) apply_filters('vsl_query_cache_ttl', self::CACHE_TTL, $atts);

        if ($ttl > 0) {
            set_transient($cache_key, wp_list_pluck($query->posts, 'ID'), $ttl);
        }

        return $query;
    }

    /**
     * Build a stable cache key for a given set of (already-normalized)
     * shortcode/query attributes. Includes a site-wide generation counter
     * so a single option bump invalidates every cached carousel at once.
     */
    protected static function cache_key($atts)
    {
        $gen = (int) get_option('vsl_cache_gen', 1);

        return 'vsl_q_' . md5(wp_json_encode($atts)) . '_' . $gen;
    }

    /**
     * Build a WP_Query for an explicit, ordered list of product IDs.
     *
     * Public on purpose: Provider drivers (see app/Providers/Drivers/)
     * reuse this instead of re-implementing "post__in + preserve order"
     * WP_Query construction themselves.
     */
    public static function from_ids(array $ids): WP_Query
    {
        return self::query_from_cached_ids($ids, array());
    }

    /**
     * Rebuild a lightweight WP_Query from a cached list of post IDs,
     * preserving the originally requested order.
     */
    protected static function query_from_cached_ids($ids, $atts)
    {
        if (empty($ids)) {
            // Cache a deliberate "no results" as an empty post__in query
            // rather than re-running the (expensive) original query.
            return new WP_Query(array(
                'post_type'      => 'product',
                'post__in'       => array(0),
                'posts_per_page' => 1,
            ));
        }

        return new WP_Query(array(
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'post__in'            => $ids,
            'orderby'             => 'post__in',
            'posts_per_page'      => count($ids),
            'ignore_sticky_posts' => true,
        ));
    }

    /**
     * Build the WP_Query args array for a fresh (non-cached) lookup.
     */
    protected static function build_args($atts)
    {
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

        return $args;
    }
}
