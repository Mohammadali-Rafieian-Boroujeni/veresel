<?php

defined('ABSPATH') || exit;

class VSL_Shortcode
{
    public function __construct()
    {
        add_shortcode('veresel', array($this, 'render'));
    }

    public function render($atts = array())
    {
        $atts = shortcode_atts(array(

            'title' => 'محصولات',

            'limit' => 12,

            'category' => '',

            'orderby' => 'date',

            'order' => 'DESC',

            'featured' => false,

            'onsale' => false,

            'instock' => false,

            'offset' => 0,

        ), $atts, 'veresel');

        $atts['shop_link'] = wc_get_page_permalink('shop');

        $query = VSL_Query::products($atts);

        return VSL_Renderer::render($query, $atts);
    }
}