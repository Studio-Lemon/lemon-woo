<?php

namespace WP_Lemon\Plugin\Lemon_Woo;

use Automattic\WooCommerce\Utilities\FeaturesUtil;
use Timber\Timber;


remove_filter('woocommerce_before_main_content', 'woocommerce_output_content_wrapper');
remove_filter('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end');


add_action('before_woocommerce_init', function () {
	if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
		FeaturesUtil::declare_compatibility('custom_order_tables', LEMON_WOO_FILE, true);
	}
});


add_filter(
	'timber/locations',
	function ($paths) {
		$plugin_path = Plugin::get_path();
		array_splice($paths['__main__'], 1, 0, $plugin_path . '/resources/views');

		return $paths;
	},
	11
);

add_filter(
	'timber/context',
	function ($context) {

		if (!class_exists('WooCommerce')) {
			return $context;
		}
		$context['woocommerce'] = array(
			'cart'       => array(
				'count' => woo_cart(),
			),
			'login_page' => get_permalink(wc_get_page_id('myaccount')),
		);

		$context['pages']['woocommerce'] = array(
			'myaccount' => get_permalink(wc_get_page_id('myaccount')),
			'shop'      => get_permalink(wc_get_page_id('shop')),
			'cart'      => get_permalink(wc_get_page_id('cart')),
		);

		return $context;
	},
	12
);

add_filter(
	'timber/post/classmap',
	function ($classmap) {
		$custom_classmap = array(
			'product' => Product::class,
		);

		return array_merge($classmap, $custom_classmap);
	}
);

add_filter(
	'timber/twig/functions',
	function ($functions) {
		$lemon_functions = array(
			'timber_set_product' => array(
				'callable' => __NAMESPACE__ . '\\timber_set_product',
			),
		);

		return array_merge($functions, $lemon_functions);
	}
);

/**
 * add ajax cartfragment.
 *
 * @since 2.0
 *
 * @author Erik van der Bas
 *
 * @param string $fragments
 */
function add_to_cart_fragment($fragments)
{
	global $woocommerce;

	$count = $woocommerce->cart->cart_contents_count ?? 0;

	$fragments['.js-cart-count'] = Timber::compile('components/cart-count.twig', array('count' => $count));

	return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', __NAMESPACE__ . '\\add_to_cart_fragment');

function woo_cart(): false|int
{
	global $woocommerce;
	if (!isset($woocommerce->cart)) {
	}

	return $woocommerce->cart->cart_contents_count ?? 0;
}

/**
 * Set product in Timber.
 *
 * Function to set the current product inside the single-product.twig
 *
 * @param object $post current post
 */
function timber_set_product($post)
{
	global $product;

	$product = wc_get_product($post->ID);
}

add_filter(
	'wc_get_template_part',
	function ($template, $slug, $name) {
		$my_path = Plugin::get_path() . 'woocommerce/' . $name;

		if ($slug) {
			$my_path = Plugin::get_path() . 'woocommerce/' . $slug . '-' . $name;
		}

		$my_path = $my_path . '.php';

		return file_exists($my_path) ? $my_path : $template;
	},
	10,
	3
);

add_filter(
	'wc_get_template',
	function ($template, $template_name, $args, $template_path, $default_path) {
		$file_path = Plugin::get_path() . 'woocommerce/' . str_replace('_', '-', $template_name);

		return file_exists($file_path) ? $file_path : $template;
	},
	10,
	5
);

add_filter('template_include', function ($template) {
	if (is_product()) {
		return Plugin::get_path() . 'woocommerce/single-product.php';
	} elseif (is_post_type_archive('product') || is_page(wc_get_page_id('shop'))) {
		return Plugin::get_path() . 'woocommerce/archive-product.php';
	}

	return $template;
}, 99);
