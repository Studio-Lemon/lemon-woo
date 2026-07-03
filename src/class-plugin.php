<?php
/**
 * Plugin bootstrap class for lemon-woo assets and paths.
 *
 * @package WP_Lemon\Plugin\Lemon_Woo
 */

namespace WP_Lemon\Plugin\Lemon_Woo;

/**
 * Main plugin class.
 */
class Plugin {


	const TEXT_DOMAIN = 'lemon-woo';

	const PLUGIN_NAME = 'Lemon x Woocommerce';

	/**
	 * Absolute plugin path.
	 *
	 * @var string
	 */
	private static $plugin_path = '';

	/**
	 * Public plugin URI.
	 *
	 * @var string
	 */
	private static $plugin_uri  = '';

	/**
	 * Plugin base file path.
	 *
	 * @var string
	 */
	public static $basefile;

	/**
	 * Initialize plugin paths and hooks.
	 */
	public function __construct() {
		self::$plugin_path = plugin_dir_path( LEMON_WOO_FILE );
		self::$plugin_uri  = plugins_url( 'lemon-woo' );

		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'add_assets' ), 9 );
	}

	/**
	 * Enqueue frontend plugin assets.
	 */
	public static function add_assets() {
		wp_enqueue_script( 'lemon-woo', self::get_uri() . '/dist/main.js', null, LEMON_WOO_VERSION, true );
		wp_enqueue_style( 'lemon-woo', self::get_uri() . '/dist/app.css', null, LEMON_WOO_VERSION );
	}

	/**
	 * Get the absolute plugin path.
	 *
	 * @return string
	 */
	public static function get_path(): string {
		return self::$plugin_path;
	}

	/**
	 * Get the plugin URI.
	 *
	 * @return string
	 */
	public static function get_uri(): string {
		return self::$plugin_uri;
	}
}
