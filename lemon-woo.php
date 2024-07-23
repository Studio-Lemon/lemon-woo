<?php

/**
 * Plugin Name:     			wp-lemon x WooCommerce
 * Plugin URI:      			https://wp-lemon.nl
 * Description:     			Additional functionality for WooCommerce to wp-lemon
 * Author:          			Erik van der Bas
 * Author URI:      			https://wp-lemon.nl
 * Text Domain:     			lemon-woo
 * Domain Path:     			/languages
 * Version:         			1.7.0
 * Requires Plugins:    	woocommerce
 * WC requires at least: 	8.6
 * WC tested up to:      	9.0
 */

namespace WP_Lemon\Plugin\Lemon_Woo;

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

define('LEMON_WOO_VERSION', '1.7.0');
define('LEMON_WOO_FILE', __FILE__);

require 'plugin-update-checker/plugin-update-checker.php';

$updateChecker = PucFactory::buildUpdateChecker(
	'https://github.com/Studio-Lemon/lemon-woo/',
	__FILE__,
	'lemon-woo'
);

$updateChecker->setBranch('main');
$updateChecker->getVcsApi()->enableReleaseAssets();
$updateChecker->addFilter(
	'first_check_time',
	function ($unusedTimestamp) {
		// Always check for updates 1 hour after the first activation.
		return time() + 3600;
	}
);

require_once 'src/class-plugin.php';

if (class_exists('Timber\Timber')) {
	include_once 'src/class-object-product.php';

	include_once 'src/woo-timber.php';

	include_once 'src/woo-hooks.php';

	include_once 'src/woo-theme.php';
}

new Plugin();
