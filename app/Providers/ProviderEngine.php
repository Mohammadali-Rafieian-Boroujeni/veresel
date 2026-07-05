<?php

namespace Veresel\Providers;

defined('ABSPATH') || exit;

/**
 * Single entry point for running a named Provider. This is what
 * VSL_Shortcode and VSL_Elementor_Widget call when a `source`/`provider`
 * attribute is set, instead of the default VSL_Query::products().
 */
class ProviderEngine
{
    /** @var ProviderRegistry */
    private $registry;

    /** @var ProviderPipeline */
    private $pipeline;

    /** @var self|null */
    private static $instance = null;

    public function __construct(?ProviderRegistry $registry = null, ?ProviderPipeline $pipeline = null)
    {
        $this->registry = $registry ?: new ProviderRegistry();
        $this->pipeline = $pipeline ?: new ProviderPipeline();
    }

    /**
     * Shared instance - providers are stateless and the registry only
     * needs to boot (and fire its filter) once per request.
     */
    public static function instance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function execute(string $id, array $args = array()): \WP_Query
    {
        $provider = $this->registry->get($id);

        if (!$provider) {
            // Unknown source (e.g. a typo in a shortcode) fails safe with
            // an empty result instead of a fatal error.
            return \VSL_Query::from_ids(array());
        }

        return $this->pipeline->run($provider, $args);
    }

    public function registry(): ProviderRegistry
    {
        return $this->registry;
    }
}
