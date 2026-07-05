<?php

defined('ABSPATH') || exit;

require_once VSL_PATH . 'includes/core/autoloader.php';

VSL_Autoloader::register();

/*
|--------------------------------------------------------------------------
| Core
|--------------------------------------------------------------------------
*/

require_once VSL_PATH . 'includes/core/plugin.php';
require_once VSL_PATH . 'includes/core/loader.php';
require_once VSL_PATH . 'includes/core/assets.php';

/*
|--------------------------------------------------------------------------
| Engine
|--------------------------------------------------------------------------
| NOTE: includes/core/query.php and includes/core/renderer.php are the
| canonical, feature-complete implementations (multi-category, onsale,
| instock filtering, empty-state handling). The older duplicates that used
| to live in includes/engine/query.php and includes/engine/renderer.php
| declared the SAME class names (VSL_Query, VSL_Renderer) with an
| incompatible, simpler signature and have been moved to
| includes/_deprecated/ to prevent a "Cannot redeclare class" fatal error.
| includes/engine/carousel.php and swiper.php are unique (VSL_Carousel,
| VSL_Swiper) and are kept.
*/

require_once VSL_PATH . 'includes/core/query.php';
require_once VSL_PATH . 'includes/core/renderer.php';
require_once VSL_PATH . 'includes/engine/carousel.php';
require_once VSL_PATH . 'includes/engine/swiper.php';

VSL_Query::hooks();

/*
|--------------------------------------------------------------------------
| Shortcode
|--------------------------------------------------------------------------
| includes/core/shortcode.php is canonical (matches core Query/Renderer
| signatures). The old includes/shortcode/carousel.php duplicate has also
| been moved to includes/_deprecated/.
*/

require_once VSL_PATH . 'includes/core/shortcode.php';

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

require_once VSL_PATH . 'includes/helpers/helpers.php';

/*
|--------------------------------------------------------------------------
| Ajax
|--------------------------------------------------------------------------
*/

require_once VSL_PATH . 'includes/ajax/ajax.php';

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

require_once VSL_PATH . 'includes/admin/admin.php';

/*
|--------------------------------------------------------------------------
| Start Plugin
|--------------------------------------------------------------------------
*/

VSL_Plugin::instance();


/**
 * Elementor Bridge
 *
 * Hooked to the 'elementor/loaded' action (instead of checking
 * did_action() immediately) so this works no matter which plugin's
 * folder happens to load first alphabetically.
 */
add_action('elementor/loaded', function () {
    $bridge = VSL_PATH . 'includes/elementor/bootstrap.php';
    if (file_exists($bridge)) {
        require_once $bridge;
    }
});
