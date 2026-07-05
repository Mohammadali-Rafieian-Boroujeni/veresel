=== Veresel ===
Contributors: varsakala
Tags: woocommerce, product carousel, elementor, slider, ecommerce
Requires at least: 6.0
Tested up to: 6.5
Requires PHP: 8.0
WC requires at least: 8.0
WC tested up to: 8.9
Stable tag: 1.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A lightweight, fast WooCommerce product carousel with shortcode, saved presets, and a native Elementor widget.

== Description ==

Veresel adds a responsive, touch-friendly product carousel to any WooCommerce store.

**Features**

* Shortcode `[veresel]` with filtering by category, exclude-category, product IDs, featured, on-sale, and in-stock status.
* Save reusable carousel presets from the admin screen and embed them anywhere with `[veresel id="123"]`.
* Native Elementor widget — build the same carousel visually, no shortcode needed.
* Quick View modal (AJAX, nonce-protected) so shoppers can preview a product without leaving the page.
* Add-to-cart, wishlist, and compare actions on every card.
* Transient-based query caching, automatically invalidated when products change.
* Assets (CSS/JS) are only loaded on pages that actually use the carousel.

= Shortcode attributes =

* `category` — comma-separated category slugs to include
* `exclude_category` — comma-separated category slugs to exclude
* `ids` / `exclude_ids` — comma-separated product IDs
* `limit` — number of products (default 12)
* `offset` — pagination offset
* `orderby` / `order` — sort field and direction
* `featured` / `onsale` / `instock` — `true`/`false` filters
* `title` — heading shown above the carousel
* `id` — load a saved preset created from Veresel → Carousels in wp-admin

Example:

`[veresel category="shoes,bags" limit="8" onsale="true"]`

== Installation ==

1. Upload the `veresel` folder to `/wp-content/plugins/`, or install the zip via Plugins → Add New → Upload Plugin.
2. Activate the plugin through the "Plugins" screen in WordPress.
3. Make sure WooCommerce is installed and active.
4. Add `[veresel]` to any post/page, or drag the "Veresel Carousel" widget into an Elementor page.
5. Optional: go to Veresel → Carousels in wp-admin to create named presets.

== Frequently Asked Questions ==

= Does this require WooCommerce? =

Yes. Veresel queries and renders WooCommerce products (`product` post type) and uses WooCommerce template functions for price, rating, and add-to-cart.

= Does this require Elementor? =

No. The shortcode works with any theme or page builder. The Elementor widget is only registered when Elementor is active.

= How do I clear the carousel cache after changing product data manually (e.g. via SQL or an import tool)? =

Veresel automatically busts its cache when products are saved, updated, or their stock changes through normal WordPress/WooCommerce actions. If you bypass those actions entirely (e.g. direct database writes), the cache clears itself within 5 minutes, or you can deactivate/reactivate the plugin.

= Is the AJAX Quick View secure? =

Yes. The Quick View endpoint is nonce-protected (`check_ajax_referer`) and only reads public product data.

== Screenshots ==

1. Product carousel on the front end.
2. Elementor widget controls.
3. Admin "Carousels" preset screen.

== Changelog ==

= 1.1.0 =
* Added a real Provider architecture (`app/Providers/`, `Veresel\` namespace) with 10 built-in providers: Best Selling, Top Rated, Related Products, Cross-sells, Upsells, Recently Viewed, Manual Selection, Category, Tag, and Custom Query.
* `[veresel]` and the Elementor widget now accept a `source` attribute/control to use a Provider instead of the default query.
* Third-party providers can be registered via the `veresel_register_providers` filter.
* Replaced the previous empty/dead `app/Core/` scaffolding and an unused, broken `bootstrap/kernel.php` (called a static method that did not exist) with a working PSR-4 autoloader for the `Veresel\` namespace.
* Documented the architecture and Provider system in `readme.md`.

= 1.0.0 =
* Fixed a build issue where core includes were not being loaded (plugin previously failed to activate).
* Removed duplicate `VSL_Query`/`VSL_Renderer`/`VSL_Shortcode` class declarations that could cause a fatal "Cannot redeclare class" error.
* Added a native Elementor widget that reuses the same query/render engine as the shortcode (no duplicated logic).
* Connected saved carousel presets (`[veresel id="..."]`) to the shortcode; previously the admin screen generated shortcodes the plugin could not read.
* Added transient-based caching for carousel queries, invalidated automatically on product save/stock changes.
* Fixed asset version constant (was regenerated on every request, defeating browser caching).
* Hardened admin form handling: capability re-check, `wp_unslash()` before sanitizing POST data, consistent output escaping.
* `uninstall.php` now removes all options the plugin creates.

== Upgrade Notice ==

= 1.0.0 =
First stable release. If you were using an earlier development build, please re-save any carousel presets after updating.
