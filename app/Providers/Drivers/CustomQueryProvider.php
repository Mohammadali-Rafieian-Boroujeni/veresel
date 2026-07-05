<?php

namespace Veresel\Providers\Drivers;

use Veresel\Providers\ProviderInterface;

defined('ABSPATH') || exit;

/**
 * Escape hatch for developers: pass a raw WP_Query args array under
 * `query_args` (e.g. from your own custom provider registered via the
 * 'veresel_register_providers' filter) for full control over the query.
 */
class CustomQueryProvider implements ProviderInterface
{
    public function get_id(): string
    {
        return 'custom';
    }

    public function get_label(): string
    {
        return __('Custom Query', 'veresel');
    }

    public function get_products(array $args = array()): \WP_Query
    {
        $query_args = isset($args['query_args']) && is_array($args['query_args'])
            ? $args['query_args']
            : array();

        $query_args = wp_parse_args($query_args, array(
            'post_type'   => 'product',
            'post_status' => 'publish',
        ));

        return new \WP_Query($query_args);
    }
}
