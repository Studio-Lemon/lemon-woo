<?php

namespace WP_Lemon\Plugin\Lemon_Woo;

use Timber\Post;
use Timber\Timber;
use WP_Lemon\Classes\LemonPost;
use WP_Post;

/**
 * Class Product
 *
 * @api
 */
class Product extends LemonPost
{

	/**
	 * @var null|\WC_Product
	 */
	public $product = null;


	private $image_id = null;

	/**
	 * Product constructor.
	 *
	 * @example
	 * ```php
	 * // Get a product post by ID
	 * Timber::get_post( 354 );
	 * ```
	 *
	 * You can also use the `get_post` function in Twig.
	 *
	 * ```twig
	 * {% set product = get_post(354) %}
	 * ```
	 *
	 * @api
	 * @param mixed $wp_post A post object or an object of class WC_Product or
	 *                       a class that inherits from WC_Product.
	 * @return \Timber\Post
	 */
	public static function build(WP_Post $wp_post): static
	{
		$post = parent::build($wp_post);

		/**
		 * Check if the object is an instance of WC_Product or inherits from
		 * WC_Product.
		 *
		 * In that case, get the post ID from the product and then let Timber
		 * get the post through the parent
		 * constructor of this class.
		 */
		if ($wp_post instanceof \WC_Product) {
			$product = $post;
		} else {
			$product = wc_get_product($post->ID);
		}

		/**
		 * Filters the WooCommerce product
		 */
		$product = apply_filters('timber/integration/woocommerce/product', $product, $post);

		$post->product = $product;

		return $post;
	}

	public function setup()
	{
		parent::setup();

		if (!is_singular('product') && did_action('woocommerce_before_shop_loop') > 0) {
			do_action('woocommerce_shop_loop');
		}

		return $this;
	}

	/**
	 * Get the first assigned product category.
	 *
	 * @api
	 * @return bool|\Timber\Term
	 */
	public function category()
	{
		$categories = $this->product->get_category_ids();

		if ($categories) {
			$category = reset($categories);
			$category = Timber::get_term($category);

			return $category;
		}

		return false;
	}

	/**
	 * Get a WooCommerce product attribute by slug.
	 *
	 * @api
	 *
	 * @param string $slug          The name of the attribute to get.
	 * @param bool   $convert_terms Whether to convert terms to Timber\Term objects.
	 *
	 * @return array|false
	 */
	public function get_product_attribute($slug, $convert_terms = true)
	{
		$attributes = $this->product->get_attributes();

		if (!$attributes || empty($attributes)) {
			return false;
		}

		/**
		 * @var \WC_Product_Attribute|false $attribute
		 */
		$attribute = false;

		foreach ($attributes as $key => $value) {
			if ("pa_{$slug}" === $key) {
				$attribute = $attributes[$key];
				break;
			}
		}

		if (!$attribute) {
			return false;
		}

		if ($attribute->is_taxonomy()) {
			$terms = wc_get_product_terms(
				$this->product->get_id(),
				$attribute->get_name(),
				array(
					'fields' => 'all',
				)
			);

			// Turn WordPress terms into instances of Timber\Term.
			if ($convert_terms) {
				$terms = Timber::get_terms($terms);
			}

			return $terms;
		}

		return $attribute->get_options();
	}

	/**
	 * Get the product's price.
	 *
	 * @api
	 * @return string
	 */
	public function sale_price()
	{
		return $this->product->get_sale_price();
	}

	public function price_html()
	{
		return $this->product->get_price_html();
	}

	public function get_description()
	{
		$this->product->get_description();

		// trim the description to 20 words
		$description = $this->product->get_description();
		$description = wp_trim_words($description, 10);

		return $description;
	}

	public function is_on_sale()
	{
		return $this->product->is_on_sale();
	}

	public function get_price_html()
	{
		return $this->product->get_price_html();
	}


	public function get_loop_columns()
	{
		return wc_get_loop_prop('columns');
	}

	public function product_image_id()
	{
		if ($this->image_id !== null) {
			return $this->image_id;
		}

		$image = $this->product->get_image_id() ?: ($this->product->get_parent_id() ? wc_get_product($this->product->get_parent_id())->get_image_id() : null);

		$this->image_id = $image ?: get_option('woocommerce_placeholder_image', 0);

		return $this->image_id;
	}

	public function get_related_products()
	{
		$related_limit = wc_get_loop_prop('columns');
		$related_ids   = wc_get_related_products($this->ID, $related_limit);
		return Timber::get_posts($related_ids);
	}
}
