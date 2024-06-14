<?php

namespace WP_Lemon\Plugin\Lemon_Woo;

function product_zoom_options( $zoom_options ) {
	// Changing the magnification level:
	$zoom_options['magnify'] = 0.7;

	return $zoom_options;
}
add_filter( 'woocommerce_single_product_zoom_options', __NAMESPACE__ . '\product_zoom_options' );
