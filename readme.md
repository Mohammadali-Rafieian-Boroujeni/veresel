# Veresel

A WooCommerce product carousel plugin with shortcode, saved presets, and a native Elementor widget.

See `readme.txt` for the WordPress.org-format description, installation instructions, and changelog.

## Architecture

- `includes/core/` — the canonical query/render/shortcode engine (`VSL_Query`, `VSL_Renderer`, `VSL_Shortcode`).
- `includes/elementor/` — Elementor category + widget bridge.
- `includes/admin/` — wp-admin screens (Dashboard, Carousels presets, Settings...).
- `includes/ajax/` — AJAX endpoints (Quick View).
- `app/Providers/` — the Provider architecture (see below).
- `includes/_deprecated/` — old/duplicate/dead code kept for reference only; never require'd.

## Providers

By default, `[veresel]` and the Elementor widget query products directly through `VSL_Query` (category, featured/onsale/instock filters, etc.).

For product sources that need different logic entirely — best sellers, related products, cross-sells, recently viewed, a hand-picked list — the plugin ships a small Provider architecture in `app/Providers/`, autoloaded via the `Veresel\` PSR-4 namespace (see `composer.json` / `vendor/autoload.php`).

Set the `source` attribute (shortcode) or the "Products Source" control (Elementor widget) to one of the built-in provider ids:

| id | Provider | Notes |
|---|---|---|
| `best_selling` | `BestSellingProvider` | Orders by WooCommerce's real `total_sales` meta |
| `top_rated` | `TopRatedProvider` | Orders by WooCommerce's real average rating meta |
| `related` | `RelatedProductsProvider` | Uses `wc_get_related_products()`; needs `product_id` or a current single product |
| `cross_sell` | `CrossSellProvider` | Uses `WC_Product::get_cross_sell_ids()` |
| `upsell` | `UpsellProvider` | Uses `WC_Product::get_upsell_ids()` |
| `recently_viewed` | `RecentlyViewedProvider` | Reads WooCommerce's `woocommerce_recently_viewed` cookie |
| `manual` | `ManualSelectionProvider` | Explicit `ids` (comma-separated), in the given order |
| `category` | `CategoryProvider` | Thin wrapper around `VSL_Query::products()` |
| `tag` | `TagProvider` | Filters by the `product_tag` taxonomy via a `tag` attribute |
| `custom` | `CustomQueryProvider` | Pass a raw `query_args` array for full control |

Example:

```
[veresel source="best_selling" limit="8" category="shoes"]
[veresel source="related" limit="4"]
[veresel source="manual" ids="12,34,56"]
```

### Registering your own Provider

Implement `Veresel\Providers\ProviderInterface` and register it via the `veresel_register_providers` filter:

```php
add_filter('veresel_register_providers', function (array $providers) {
    $providers['my_source'] = new My_Custom_Provider();
    return $providers;
});
```

Your provider's `get_products(array $args): WP_Query` receives whatever attributes were passed to the shortcode/widget, and must return a `WP_Query`. If you just need to turn a list of product IDs into a properly-ordered `WP_Query`, reuse `VSL_Query::from_ids($ids)` instead of writing that logic yourself.

Two more filters are available for cross-cutting concerns (logging, extra `WP_Query` args, caching, analytics, etc.):

- `veresel_before_provider_query( array $args, ProviderInterface $provider )` — filter the args right before a provider runs.
- `veresel_after_provider_query( WP_Query $query, ProviderInterface $provider, array $args )` — filter the resulting query right after.
