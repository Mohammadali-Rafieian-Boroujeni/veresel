<?php

defined('ABSPATH') || exit;

class VSL_Shortcode
{
    public function __construct()
    {
        add_shortcode('veresel', [$this, 'render']);
    }

    public function render($atts = [])
    {
        $query = VSL_Query::products($atts);

        $shop_link = wc_get_page_permalink('shop');

        return VSL_Renderer::render($query, $shop_link);
    }
}