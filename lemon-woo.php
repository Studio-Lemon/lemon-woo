<?php

/**
 * Plugin Name:                 wp-lemon x WooCommerce
 * Plugin URI:                  https://wp-lemon.nl
 * Description:                 Additional functionality for WooCommerce to wp-lemon
 * Author:                      Erik van der Bas
 * Author URI:                  https://wp-lemon.nl
 * Text Domain:                 lemon-woo
 * Domain Path:                 /resources/languages
 * x-release-please-start-version
 * Version:                     3.1.0
 * x-release-please-end
 * Requires Plugins:        woocommerce
 * WC requires at least:    8.6
 * WC tested up to:         9.0
 *
 * @package WP_Lemon\Plugin\Lemon_Woo
 */

namespace WP_Lemon\Plugin\Lemon_Woo;

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

define('LEMON_WOO_VERSION', '3.1.0'); // x-release-please-version
define('LEMON_WOO_FILE', __FILE__);
define('LEMON_WOO_REQUIRED_WP_VERSION', '5.50.0');

require 'plugin-update-checker/plugin-update-checker.php';

$update_checker = PucFactory::buildUpdateChecker(
	'https://github.com/Studio-Lemon/lemon-woo/',
	__FILE__,
	'lemon-woo'
);

$update_checker->setBranch('main');
$vcs_api = $update_checker->getVcsApi();

/** @var \YahnisElsts\PluginUpdateChecker\v5p6\Vcs\GitHubApi $vcs_api */
$vcs_api->enableReleaseAssets('/lemon-woo\.zip/', 2);

$update_checker->addFilter(
	'first_check_time',
	function ($unused_timestamp) {
		unset($unused_timestamp);

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
	$theme = wp_get_theme('wp-lemon');

	if (! $theme->exists()) {
		return;
	}

	$theme_version = $theme->get('Version');

	if (version_compare($theme_version, '5.26.0', '<')) {

		_doing_it_wrong(
			'lemon-woo',
			esc_html__('This plugin requires at least version 5.26.0 of the wp-lemon theme.', 'lemon-woo'),
			'2.0.0'
		);
		return;
	}

	if (class_exists('Timber\Timber')) {
		include_once 'src/class-object-product.php';
		include_once 'src/class-object-tax-product-category.php';
		include_once 'src/woo-timber.php';
		include_once 'src/woo-hooks.php';
		include_once 'src/woo-theme.php';
	}
}
add_action('parent_loaded', __NAMESPACE__ . '\\wp_lemon_loaded');

/**
 * Prevent plugin updates when the required theme version is unavailable.
 *
 * @param mixed                $response   Existing upgrader pre-install response.
 * @param array<string, mixed> $hook_extra Upgrader context.
 * @return mixed
 */
function check_requirements($response, $hook_extra)
{
	// Only run during plugin updates, not core or theme updates.
	if (empty($hook_extra['plugin']) || 'lemon-woo/lemon-woo.php' !== $hook_extra['plugin']) {
		return $response;
	}

	$theme = wp_get_theme('wp-lemon');

	if (! $theme->exists()) {
		return new \WP_Error(
			'theme_not_found',
			__('This plugin requires the wp-lemon theme to be installed and active.', 'lemon-woo')
		);
	}

	$theme_version = $theme->get('Version');

	if (version_compare($theme_version, LEMON_WOO_REQUIRED_WP_VERSION, '<=')) {
		return new \WP_Error(
			'theme_version_incompatible',
			/* translators: %s: minimum required wp-lemon theme version. */
			sprintf(__('This plugin requires at least version %s of the wp-lemon theme.', 'lemon-woo'), LEMON_WOO_REQUIRED_WP_VERSION)
		);
	}

	return $response;
}
add_filter('upgrader_pre_install', __NAMESPACE__ . '\\check_requirements', 10, 2);


new Plugin();
