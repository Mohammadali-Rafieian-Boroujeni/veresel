<?php

namespace Veresel\Providers\Drivers;

use Veresel\Providers\ProviderInterface;

defined('ABSPATH') || exit;

/**
 * Category-filtered products. Category filtering already exists in
 * VSL_Query, so this provider exists only so "category" is selectable as
 * an explicit `source` in the shortcode/widget UI - it does not
 * reimplement the underlying tax_query logic.
 */
class CategoryProvider implements ProviderInterface
{
    public function get_id(): string
    {
        return 'category';
    }

    public function get_label(): string
    {
        return __('Category', 'veresel');
    }

    public function get_products(array $args = array()): \WP_Query
    {
        return \VSL_Query::products($args);
    }
}
