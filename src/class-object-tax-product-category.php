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

   private $image_id = null;

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
      return 3 == $this->get_loop_columns() ? '(min-width: 768px) 100w,
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
      if ($this->image_id !== null) {
         return $this->image_id;
      }

      $this->image_id = (int) $this->meta('thumbnail_id') ?: get_option('woocommerce_placeholder_image', 0);
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
      if ($this->category_title !== null) {
         return $this->category_title;
      }

      $this->category_title = woocommerce_template_loop_category_title($this);
      return $this->category_title;
   }
}
