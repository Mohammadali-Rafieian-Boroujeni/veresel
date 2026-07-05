<?php

namespace Veresel\Providers;

defined('ABSPATH') || exit;

/**
 * Holds every known Provider, keyed by its id. Built-in providers are
 * registered on first use; third parties can add or replace providers
 * via the 'veresel_register_providers' filter.
 */
class ProviderRegistry
{
    /** @var ProviderInterface[] */
    private $providers = array();

    /** @var bool */
    private $booted = false;

    public function register(ProviderInterface $provider): void
    {
        $id = $provider->get_id();

        if (!is_string($id) || '' === trim($id)) {
            return;
        }

        $this->providers[$id] = $provider;
    }

    public function get(string $id): ?ProviderInterface
    {
        $this->boot();

        return $this->providers[$id] ?? null;
    }

    /**
     * @return ProviderInterface[]
     */
    public function all(): array
    {
        $this->boot();

        return $this->providers;
    }

    private function boot(): void
    {
        if ($this->booted) {
            return;
        }

        $this->booted = true;

        foreach (self::built_in_providers() as $provider) {
            $this->register($provider);
        }

        /**
         * Filter: veresel_register_providers
         *
         * Add or replace product source providers available as a
         * shortcode/widget `source`. Receives the current list (keyed by
         * provider id) and must return an array of ProviderInterface
         * instances; anything not implementing the interface is ignored.
         *
         * @param ProviderInterface[] $providers
         */
        $providers = apply_filters('veresel_register_providers', $this->providers);

        if (is_array($providers)) {
            foreach ($providers as $provider) {
                if ($provider instanceof ProviderInterface) {
                    $this->register($provider);
                }
            }
        }
    }

    /**
     * @return ProviderInterface[]
     */
    private static function built_in_providers(): array
    {
        return array(
            new Drivers\BestSellingProvider(),
            new Drivers\TopRatedProvider(),
            new Drivers\RelatedProductsProvider(),
            new Drivers\CrossSellProvider(),
            new Drivers\UpsellProvider(),
            new Drivers\RecentlyViewedProvider(),
            new Drivers\ManualSelectionProvider(),
            new Drivers\CategoryProvider(),
            new Drivers\TagProvider(),
            new Drivers\CustomQueryProvider(),
        );
    }
}
