# How to Modify Cart Item Images for Specific Product Categories in WooCommerce Cart and Checkout Blocks?

```generic
add_filter(
  'woocommerce_store_api_cart_item_images',
  function ($product_images, $cart_item, $cart_item_key) {
      $category_id = 91; // Change this to your target category ID
      
      // Get the product object
      $product = wc_get_product($cart_item['product_id']);
      
      // Check if the product belongs to the specific category
      if (has_term($category_id, 'product_cat', $product->get_id())) {
          $image_path = "https://picsum.photos/seed/$cart_item_key/200";
          return [
              (object)[
                  'id'        => (int) 0,
                  'src'       => $image_path,
                  'thumbnail' => $image_path,
                  'srcset'    => '',
                  'sizes'     => '',
                  'name'      => 'Random product image',
                  'alt'       => 'Random product image',
              ]
          ];
      }

      // Return original images for other products
      return $product_images;
  },
  10,
  3
);
```