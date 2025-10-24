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
    * @var null|\WC_Category
    */
   public $category = null;

   private $image_id = null;

   private $category_title = null;

   public function get_image_sizes()
   {
      return 3 == $this->get_loop_columns() ? '(min-width: 768px) 400px,
				(min-width: 600px) 510px,
				400px' : '(min-width: 768px) 300px,
				(min-width: 600px) 510px,
				400px';
   }


   public function get_loop_columns()
   {
      return wc_get_loop_prop('columns');
   }

   public function image_id()
   {
      if ($this->image_id !== null) {
         return $this->image_id;
      }

      $this->image_id = (int) $this->meta('thumbnail_id') ?: get_option('woocommerce_placeholder_image', 0);
      return $this->image_id;
   }

   public function title()
   {
      if ($this->category_title !== null) {
         return $this->category_title;
      }

      $this->category_title = woocommerce_template_loop_category_title($this);
      return $this->category_title;
   }
}
