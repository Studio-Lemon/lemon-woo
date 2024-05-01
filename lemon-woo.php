<?php

/**
 * Plugin Name:     wp-lemon x WooCommerce
 * Plugin URI:      https://wp-lemon.nl
 * Description:     Additional functionality for WooCommerce to wp-lemon
 * Author:          Erik van der Bas
 * Author URI:      https://wp-lemon.nl
 * Text Domain:     lemon-woo
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         lemon-woo
 */

namespace WP_Lemon\Woocommerce;

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

include_once 'src/class-plugin.php';
include_once 'src/class-object-product.php';
include_once 'src/woo-timber.php';
include_once 'src/woo-hooks.php';
include_once 'src/woo-theme.php';

new Plugin();


require 'plugin-update-checker/plugin-update-checker.php';

$updateChecker = PucFactory::buildUpdateChecker(
   'https://github.com/Studio-Lemon/lemon-woo/',
   __FILE__,
   'lemon-woo'
);

$updateChecker->setBranch('main');

$updateChecker->addFilter('first_check_time', function ($unusedTimestamp) {
   //Always check for updates 1 hour after the first activation.
   return time() + 3600;
});