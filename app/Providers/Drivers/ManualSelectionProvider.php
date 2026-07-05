<?php

namespace Veresel\Providers\Drivers;

use Veresel\Providers\ProviderInterface;

defined('ABSPATH') || exit;

/**
 * An explicit, hand-picked list of product IDs, in the exact order given.
 */
class ManualSelectionProvider implements ProviderInterface
{
    public function get_id(): string
    {
        return 'manual';
    }

    public function get_label(): string
    {
        return __('Manual Selection', 'veresel');
    }

    public function get_products(array $args = array()): \WP_Query
    {
        $ids = array();

        if (!empty($args['ids'])) {
            $ids = is_array($args['ids'])
                ? array_map('intval', $args['ids'])
                : array_map('intval', explode(',', (string) $args['ids']));
        }

        if (!empty($args['limit'])) {
            $ids = array_slice($ids, 0, absint($args['limit']));
        }

        return \VSL_Query::from_ids($ids);
    }
}
