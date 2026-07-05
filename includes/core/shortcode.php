<?php

defined('ABSPATH') || exit;

class VSL_Shortcode
{
    public function __construct()
    {
        add_shortcode('veresel', array($this, 'render'));
    }

    public function render($atts = array()): string
    {
        $raw_atts = is_array($atts) ? $atts : array();

        // A preset id (saved via the admin "Carousels" screen) acts as the
        // base configuration; any attribute explicitly written in the
        // shortcode still overrides the preset's value.
        $preset = array();

        if (!empty($raw_atts['id'])) {
            $preset = $this->get_preset(absint($raw_atts['id']));
        }

        $defaults = array_merge(
            array(

                'id' => '',

                'title' => __('محصولات', 'veresel'),

                'limit' => 12,

                'category' => '',

                'orderby' => 'date',

                'order' => 'DESC',

                'featured' => false,

                'onsale' => false,

                'instock' => false,

                'offset' => 0,

                // Provider architecture (app/Providers/): when 'source' is
                // set, products come from Veresel\Providers\ProviderEngine
                // instead of the default VSL_Query::products() below. Left
                // empty by default so existing shortcodes keep working
                // exactly as before.
                'source' => '',

                'tag' => '',

                'ids' => '',

                'product_id' => 0,

            ),
            $preset
        );

        $atts = shortcode_atts($defaults, $raw_atts, 'veresel');

        unset($atts['id']);

        $atts['shop_link'] = wc_get_page_permalink('shop');

        if (!empty($atts['source']) && class_exists('\Veresel\Providers\ProviderEngine')) {
            $query = \Veresel\Providers\ProviderEngine::instance()->execute($atts['source'], $atts);
        } else {
            $query = VSL_Query::products($atts);
        }

        return VSL_Renderer::render($query, $atts);
    }

    /**
     * Look up a saved carousel preset by its id.
     *
     * Presets are created from wp-admin -> Veresel -> Carousels and stored
     * as an array of {id, name, category, limit} in the 'vsl_carousels'
     * option (see VSL_Admin::carousels()).
     *
     * @param int $id Preset id.
     * @return array Preset values mapped to shortcode/VSL_Query attribute keys.
     */
    private function get_preset(int $id): array
    {
        $items = get_option('vsl_carousels', array());

        if (!is_array($items)) {
            return array();
        }

        foreach ($items as $item) {

            if (!isset($item['id']) || (int) $item['id'] !== $id) {
                continue;
            }

            return array(
                'title'    => isset($item['name']) ? $item['name'] : '',
                'category' => isset($item['category']) ? $item['category'] : '',
                'limit'    => isset($item['limit']) ? absint($item['limit']) : 12,
            );

        }

        return array();
    }
}