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

$context = Timber::context();

Timber::render('templates/single-product.twig', $context);
