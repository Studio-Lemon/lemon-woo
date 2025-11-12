<?php

namespace WP_Lemon\Plugin\Lemon_Woo;

use Timber\Timber;

function add_cart()
{
	$current_hook = current_filter();

	if ($current_hook === 'wp-lemon/action/header/menu-toggle/before') {
		remove_action('wp-lemon/action/menu-toggle/before', __NAMESPACE__ . '\\add_cart', 9);
	}

	if (!class_exists('WooCommerce')) {
		return false;
	}

	$context = Timber::context();

	$context['show_account'] = apply_filters('lemon-woo/filter/show-account', true);

	Timber::render('components/cart.twig', $context);
}


add_action('wp-lemon/action/header/menu-toggle/before', __NAMESPACE__ . '\\add_cart');

if (!array_key_exists('wp-lemon/action/header/menu-toggle/before', $GLOBALS['wp_filter'])) {
	add_action('wp-lemon/action/menu-toggle/before', __NAMESPACE__ . '\\add_cart');
}



/**
 * Add archive page to navwalker.
 *
 * @param mixed $archive_pages the current archive pages
 * @param int   $post_id       the current post id
 * @param mixed $item          the current menu item
 * @param array $classes       the current menu item classes
 */
function add_archive($archive_pages, $post_id, $item, $classes)
{
	if (!class_exists('WooCommerce')) {
		return;
	}
	if ($post_id == wc_get_page_id('shop')) {
		$archive_pages = array('product');
	}

	return $archive_pages;
}
add_filter('wp-lemon/filter/navwalker/archive-pages', __NAMESPACE__ . '\\add_archive', 10, 4);




/**
 * Add additional css for rank math seo in product overview.
 *
 * @return void
 */
function add_rank_math_seo()
{
	echo '<style>
	.post-type-product #rank_math_seo_details {
		width: 25%;
	}
</style>';
}
add_action('admin_head', __NAMESPACE__ . '\\add_rank_math_seo');
