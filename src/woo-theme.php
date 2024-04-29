<?php

namespace WP_Lemon\Woocommerce;

use Timber\Timber;

function add_cart()
{
   $context = Timber::context();
   Timber::render('components/cart.twig', $context);
}

add_filter('wp-lemon/action/menu-toggle/before', __NAMESPACE__ . '\\add_cart');
