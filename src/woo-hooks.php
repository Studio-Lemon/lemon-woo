<?php

/**
 * WooCommerce hook customizations for lemon-woo.
 *
 * @package WP_Lemon\Plugin\Lemon_Woo
 */

namespace WP_Lemon\Plugin\Lemon_Woo;

/**
 * Adjust single-product zoom behavior.
 *
 * @param array<string, mixed> $zoom_options Zoom configuration passed to WooCommerce.
 * @return array<string, mixed>
 */
function product_zoom_options($zoom_options)
{
	// Changing the magnification level:
	$zoom_options['magnify'] = 0.7;

	return $zoom_options;
}
add_filter('woocommerce_single_product_zoom_options', __NAMESPACE__ . '\product_zoom_options');


add_action(
	'init',
	function () {
		remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
		add_action('wp-lemon/action/entry/single-product/content/after', 'woocommerce_output_related_products');
	}
);


add_filter(
	'woocommerce_loop_add_to_cart_args',
	function ($args) {

		$args['class'] .= ' crd__btn';

		return $args;
	}
);

/**
 * On some of our development machines, $_SERVER['SCRIPT_FILENAME'] gets hyjacked by the server.
 * This causes the woocommerce_prevent_admin_access filter to always return true, which prevents
 * us from making ajax requests in the admin-ajax.php.
 *
 * @see WC_Admin::prevent_admin_access()
 * @since 5.2.3
 * @return bool $prevent_admin_access Whether to prevent admin access and redirect to my-account page.
 */
add_filter(
	'woocommerce_prevent_admin_access',
	function ($prevent_admin_access) {

		// if is ajax request, return false
		if (wp_doing_ajax()) {
			return false;
		}

		return $prevent_admin_access;
	}
);

/**
 * Disable the password change notification email.
 *
 * @since 2.5.2
 * @return bool
 */
add_filter('woocommerce_disable_password_change_notification', '__return_true');


/**
 * Hide paid shipping methods when free shipping is available.
 *
 * Leaves all available shipping rates unchanged when free shipping is not
 * offered. When free shipping is available, keeps free shipping and local
 * pickup rates and removes all other shipping methods.
 *
 * @param array<string, mixed> $rates Array of available shipping rates.
 *
 * @since 3.1.0
 * @return array<string, mixed>
 */
function skinplus_hide_shipping_when_free_is_available($rates)
{
	$free_shipping_rates = array_filter(
		$rates,
		static fn($rate): bool => 'free_shipping' === $rate->method_id
	);

	if (empty($free_shipping_rates)) {
		return $rates;
	}

	// Keep free shipping and local pickup; hide all paid shipping options.
	return array_filter(
		$rates,
		static fn($rate): bool => in_array($rate->method_id, array('free_shipping', 'local_pickup'), true)
	);
}

add_filter('woocommerce_package_rates', __NAMESPACE__ . '\skinplus_hide_shipping_when_free_is_available');
