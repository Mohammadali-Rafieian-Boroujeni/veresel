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
        $resolved_id = $this->normalize_id($id, $args);

        if ('' === $resolved_id) {
            return \VSL_Query::products($args);
        }

        $provider = $this->registry->get($resolved_id);

        if (!$provider) {
            return \VSL_Query::products($args);
        }

        return $this->pipeline->run($provider, $args);
    }

    public function has(string $id): bool
    {
        return null !== $this->registry->get($this->normalize_id($id, array()));
    }

    public function registry(): ProviderRegistry
    {
        return $this->registry;
    }

    private function normalize_id(string $id, array $args = array()): string
    {
        $value = trim($id);

        if ('' === $value && isset($args['provider']) && is_string($args['provider'])) {
            $value = trim($args['provider']);
        }

        return $value;
    }
}
