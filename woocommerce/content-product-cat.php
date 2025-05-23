<?php

/**
 * The template for displaying product category thumbnails within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product-cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.7.0
 */

defined('ABSPATH') || exit;

use Timber\Timber;

Timber::render(
	'components/cards/crd-product-cat.twig',
	array(
		'category'         => $category,
		'link'             => esc_url(get_term_link($category, 'product_cat')),
	)
);
