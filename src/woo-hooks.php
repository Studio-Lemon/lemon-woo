<?php

namespace WP_Lemon\Plugin\Lemon_Woo;

function product_zoom_options($zoom_options)
{
	// Changing the magnification level:
	$zoom_options['magnify'] = 0.7;

	return $zoom_options;
}
add_filter('woocommerce_single_product_zoom_options', __NAMESPACE__ . '\product_zoom_options');


add_action('init', function () {
	remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
	add_action('wp-lemon/action/entry/single-product/content/after', 'woocommerce_output_related_products');
});


add_filter('woocommerce_loop_add_to_cart_args', function ($args) {

	$args['class'] .= ' crd__btn';

	return $args;
});

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
		if (defined('DOING_AJAX') && DOING_AJAX === true) {
			return false;
		}

		return $prevent_admin_access;
	}
);
