# How to Add a Product to Cart with Price Override in WooCommerce?

```generic
add_filter('woocommerce_add_cart_item_data', 'wwccs_custom_price_adjustment', 10, 2);

function wwccs_custom_price_adjustment($cart_item_data, $product_id) {
    // Add a flag to indicate price adjustment
    $cart_item_data['adjust_price'] = true;
    return $cart_item_data;
}

add_action('woocommerce_before_calculate_totals', 'wwccs_apply_price_adjustment');

function wwccs_apply_price_adjustment($cart) {
    // Check if the cart is empty or in admin
    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

    // Loop through cart items
    foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
        // Check if our custom price adjustment flag exists
        if (isset($cart_item['adjust_price'])) {
            // Adjust the price of the existing product in the cart
            $original_price = $cart_item['data']->get_price(); // Get the original price
            $additional_price = 25.00; // Set your additional price
            $new_price = $original_price + $additional_price; // Calculate new price
            $cart_item['data']->set_price($new_price); // Set the new price
        }
    }
}

add_action('woocommerce_product_query', 'wwccs_show_only_instock_products');

function wwccs_show_only_instock_products($query) {
    $meta_query = $query->get('meta_query');
    $meta_query[] = array(
        'key'       => '_stock_status',
        'compare'   => '=',
        'value'     => 'instock'
    );
    $query->set('meta_query', $meta_query);
}
```