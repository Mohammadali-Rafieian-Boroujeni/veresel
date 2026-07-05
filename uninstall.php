<?php

// If this file is called directly, abort.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

/*
|--------------------------------------------------------------------------
| Clean up all data Veresel ever writes to the database.
|--------------------------------------------------------------------------
| Keep this list in sync with every update_option()/add_option() call in
| the plugin (currently: includes/admin/admin.php).
*/

delete_option('vsl_carousels');
delete_option('vsl_default_limit');
delete_option('vsl_default_mobile');

// Site-wide cache-busting counter used by VSL_Query's transient caching.
delete_option('vsl_cache_gen');

// In case Veresel is ever network-activated on multisite, clean every site.
if (is_multisite()) {

    $site_ids = get_sites(array('fields' => 'ids'));

    foreach ($site_ids as $site_id) {

        switch_to_blog($site_id);

        delete_option('vsl_carousels');
        delete_option('vsl_default_limit');
        delete_option('vsl_default_mobile');
        delete_option('vsl_cache_gen');

        restore_current_blog();
    }
}
