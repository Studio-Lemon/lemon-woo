<?php

/**
 * Woocommerce template file
 *
 * Build up context for both the archive and single product templates.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage WP_Lemon
 * @since 2.0.0
 */

use Timber\Timber;

$context             = Timber::context();
$shop_page           = wc_get_page_id('shop');
$products            = Timber::get_posts();
$context['products'] = $products;
$context['post']     = Timber::get_post($shop_page);

Timber::render('templates/woocommerce-archive.twig', $context);
