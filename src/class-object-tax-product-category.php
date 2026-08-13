<?php

namespace WP_Lemon\Plugin\Lemon_Woo;

use Timber\Term;

/**
 * Class Product Category
 *
 * @api
 */
class ProductCategory extends Term
{


	/**
	 * Cached category image ID.
	 *
	 * @var int|null
	 */
	private $image_id = null;

	/**
	 * Cached category title.
	 *
	 * @var string|null
	 */
	private $category_title = null;

	/**
	 * Get responsive image sizes for the category image.
	 *
	 * Returns different sizes based on the number of loop columns.
	 *
	 * @api
	 * @return string The sizes attribute for responsive images.
	 */
	public function get_image_sizes()
	{
		return 3 === $this->get_loop_columns() ? '(min-width: 768px) 100w,
				(min-width: 600px) 510px,
				400px' : '(max-width: 575px) 100w,
  (max-width: 767px) 280px,
  (max-width: 991px) 350px,
  (max-width: 1197px) 230px,
  280px';
	}

	/**
	 * Get the number of columns in the product loop.
	 *
	 * @api
	 * @return int The number of columns.
	 */
	public function get_loop_columns()
	{
		return wc_get_loop_prop('columns');
	}

	/**
	 * Get the category thumbnail image ID.
	 *
	 * Returns the category's thumbnail ID or the WooCommerce placeholder image ID as fallback.
	 *
	 * @api
	 * @return int The image attachment ID.
	 */
	public function image_id()
	{
		if (null !== $this->image_id) {
			return $this->image_id;
		}

		$thumbnail_id   = (int) $this->meta('thumbnail_id');
		$this->image_id = $thumbnail_id ? $thumbnail_id : get_option('woocommerce_placeholder_image', 0);
		return $this->image_id;
	}

	/**
	 * Get the category title.
	 *
	 * Uses WooCommerce's template function to generate the category title.
	 *
	 * @api
	 * @return string The category title.
	 */
	public function title()
	{
		if (null !== $this->category_title) {
			return $this->category_title;
		}

		ob_start();
		woocommerce_template_loop_category_title($this);
		$this->category_title = (string) ob_get_clean();

		return $this->category_title;
	}
}
