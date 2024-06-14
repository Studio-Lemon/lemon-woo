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

use function WP_Lemon\API\get_share_platforms;
use Timber\Timber;

$context = Timber::context();

$context['share_context'] = get_share_platforms( get_the_ID() );

Timber::render( 'templates/single-product.twig', $context );
