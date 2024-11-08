<?php

/**
 * Plugin Name:     			wp-lemon x WooCommerce
 * Plugin URI:      			https://wp-lemon.nl
 * Description:     			Additional functionality for WooCommerce to wp-lemon
 * Author:          			Erik van der Bas
 * Author URI:      			https://wp-lemon.nl
 * Text Domain:     			lemon-woo
 * Domain Path:     			/languages
 * Version:         			2.0.0
 * Requires Plugins:    	woocommerce
 * WC requires at least: 	8.6
 * WC tested up to:      	9.0
 */

namespace WP_Lemon\Plugin\Lemon_Woo;

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

define('LEMON_WOO_VERSION', '2.0.0');
define('LEMON_WOO_FILE', __FILE__);

require 'plugin-update-checker/plugin-update-checker.php';

$updateChecker = PucFactory::buildUpdateChecker(
	'https://github.com/Studio-Lemon/lemon-woo/',
	__FILE__,
	'lemon-woo'
);

$updateChecker->setBranch('main');
$updateChecker->getVcsApi()->enableReleaseAssets('/lemon-woo.zip/i', 0);
$updateChecker->addFilter(
	'first_check_time',
	function ($unusedTimestamp) {
		// Always check for updates 1 hour after the first activation.
		return time() + 3600;
	}
);

require_once 'src/class-plugin.php';

/**
 * Start loading files once wp-lemon is completely loaded.
 *
 * @return void
 */
function wp_lemon_loaded()
{
	// get version of wp-lemon theme
	$theme = wp_get_theme();
	$theme_version = $theme->get('Version');

	if (version_compare($theme_version, '5.26.0', '<')) {
		wp_die(
			__('This plugin requires at least version 5.26.0 of the wp-lemon theme.', 'lemon-woo')
		);
		return;
	}


	if (class_exists('Timber\Timber')) {
		include_once 'src/class-object-product.php';
		include_once 'src/woo-timber.php';
		include_once 'src/woo-hooks.php';
		include_once 'src/woo-theme.php';
	}
}
add_action('parent_loaded', __NAMESPACE__ . '\\wp_lemon_loaded');


new Plugin();
