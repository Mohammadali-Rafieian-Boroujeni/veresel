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
*/

require_once VSL_PATH . 'includes/engine/query.php';
require_once VSL_PATH . 'includes/engine/renderer.php';
require_once VSL_PATH . 'includes/engine/carousel.php';
require_once VSL_PATH . 'includes/engine/swiper.php';

/*
|--------------------------------------------------------------------------
| Shortcode
|--------------------------------------------------------------------------
*/

require_once VSL_PATH . 'includes/shortcode/carousel.php';

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

if ( did_action('elementor/loaded') ) {
 require_once VSL_PATH . 'includes/elementor/bootstrap.php';
}
