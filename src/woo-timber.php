<?php

namespace WP_Lemon\Woocommerce;


add_filter(
   'timber/locations',
   function ($paths) {

      // get current plugin path
      $plugin_path = Plugin::$plugin_path;

      $paths[] = [$plugin_path . '/resources/views'];

      return $paths;
   }
);

add_filter('timber/context', function ($context) {
   $context['woocommerce'] = [
      'cart'       => woo_cart(),
      'login_page' => get_permalink(wc_get_page_id('myaccount')),
   ];
   return $context;
});


add_filter('timber/post/classmap', function ($classmap) {
   $custom_classmap = [
      'product' => Product::class,
   ];

   return array_merge($classmap, $custom_classmap);
});

add_filter(
   'timber/twig/functions',
   function ($functions) {

      $lemon_functions = [
         'timber_set_product' => [
            'callable' => __NAMESPACE__ . '\\timber_set_product',
         ],
      ];
      return array_merge($functions, $lemon_functions);
   }
);


/**
 * add ajax cartfragment
 *
 * @since 2.0
 * @author Erik van der Bas
 * @param string $fragments
 * @return void
 */
function add_to_cart_fragment($fragments)
{

   global $woocommerce;

   $count = $woocommerce->cart->cart_contents_count ?? 0;


   $fragments['.count-cart'] = '<span class="count-cart">' . $count . '</span>';
   return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', __NAMESPACE__ . '\\add_to_cart_fragment');



function woo_cart(): false | int
{
   global $woocommerce;
   if (!isset($woocommerce->cart)) {
      false;
   }

   return $woocommerce->cart->cart_contents_count ?? 0;
}




/**
 * Set product in Timber.
 *
 * Function to set the current product inside the single-product.twig
 *
 * @param object $post  Current post.
 * @return void
 */
function timber_set_product($post)
{
   global $product;

   $product = wc_get_product($post->ID);
}
