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

include_once 'src/class-plugin.php';
include_once 'src/class-object-product.php';
include_once 'src/woo-timber.php';
include_once 'src/woo-hooks.php';
include_once 'src/woo-theme.php';

new Plugin();
