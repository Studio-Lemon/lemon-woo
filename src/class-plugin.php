<?php

namespace WP_Lemon\Plugin\Lemon_Woo;

class Plugin {

	const TEXT_DOMAIN = 'lemon-woo';

	const PLUGIN_NAME = 'Lemon x Woocommerce';

	private static $plugin_path = '';
	private static $plugin_uri  = '';

	public function __construct() {
		self::$plugin_path = realpath( __DIR__ . '/..' );
		self::$plugin_uri  = plugins_url( 'lemon-woo' );

		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'add_assets' ) );
	}

	public static function add_assets() {
		wp_enqueue_script( 'lemon-woo-js', self::get_uri() . '/dist/main.js', null, LEMON_WOO_VERSION );
		wp_enqueue_style( 'lemon-woo-css', self::get_uri() . '/dist/app.css', null, LEMON_WOO_VERSION );
	}

	public static function get_path(): string {
		return self::$plugin_path;
	}

	public static function get_uri(): string {
		return self::$plugin_uri;
	}
}
