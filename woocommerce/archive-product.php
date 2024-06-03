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

$products = Timber::get_posts();
$context['products'] = $products;

if (is_product_category()) {
   $queried_object      = get_queried_object();
   $term_id             = $queried_object->term_id;
   $context['category'] = get_term($term_id, 'product_cat');
   $context['title']    = single_term_title('', false);

   if ($queried_object && !empty($term->description)) {
      $context['show_description'] = true;
   }
}

Timber::render('templates/woocommerce-archive.twig', $context);
