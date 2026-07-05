<?php

namespace Veresel\Providers;

defined('ABSPATH') || exit;

/**
 * Lets code hook in immediately before/after a provider runs, without
 * needing to touch ProviderEngine or any individual provider.
 */
class ProviderPipeline
{
    public function run(ProviderInterface $provider, array $args): \WP_Query
    {
        /**
         * Filter: veresel_before_provider_query
         *
         * @param array             $args
         * @param ProviderInterface $provider
         */
        $args = apply_filters('veresel_before_provider_query', $args, $provider);

        $query = $provider->get_products($args);

        /**
         * Filter: veresel_after_provider_query
         *
         * @param \WP_Query         $query
         * @param ProviderInterface $provider
         * @param array             $args
         */
        $query = apply_filters('veresel_after_provider_query', $query, $provider, $args);

        return $query;
    }
}
