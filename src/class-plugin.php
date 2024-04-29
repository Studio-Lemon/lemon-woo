<?php

namespace WP_Lemon\Woocommerce;

class Plugin
{
   const VERSION = '0.1.0';

   const TEXT_DOMAIN = 'woo-lemon';

   const PLUGIN_NAME = 'Woo Lemon';

   public static $plugin_path = '';

   public function __construct()
   {
      self::$plugin_path = realpath(dirname(__FILE__) . '/..');
   }
}
