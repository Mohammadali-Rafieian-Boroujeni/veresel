<?php

defined('ABSPATH') || exit;

class VSL_Renderer
{
    public static function render($query, array $atts = array()): string
    {
        if (!$query instanceof WP_Query) {
            return '';
        }

        if (!$query->have_posts()) {

            return apply_filters(
                'vsl_no_products',
                '<div class="vsl-empty">' . esc_html__('هیچ محصولی یافت نشد.', 'veresel') . '</div>',
                $atts
            );

        }

        $shop_link = isset($atts['shop_link']) ? $atts['shop_link'] : '';

        $title = isset($atts['title']) ? $atts['title'] : '';

        ob_start();

        include VSL_PATH . 'templates/wrapper-start.php';

        while ($query->have_posts()) {

            $query->the_post();

            global $product;

            include VSL_PATH . 'templates/product-card.php';

        }

        wp_reset_postdata();

        include VSL_PATH . 'templates/more-card.php';

        include VSL_PATH . 'templates/wrapper-end.php';

        return ob_get_clean();
    }
}