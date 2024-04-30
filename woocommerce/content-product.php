<?php

/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

use Timber\Factory\PostFactory;
use Timber\Helper;
use Timber\Timber;

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
   return;
}

static $postFactory;
$postFactory = $postFactory ?: new PostFactory();
$timber_product = $postFactory->from($product->get_id());

Timber::render('components/cards/crd-product.twig', [
   'product' => $timber_product
]);
