# How to Implement WooCommerce Dynamic Product Calculated Price Based on Product Input Field?

```generic
add_action('woocommerce_before_add_to_cart_button', 'wwccs_add_gift_wrapping_input_field');

function wwccs_add_gift_wrapping_input_field() {
    ?>
    <div class="gift-wrapping-option">
        <label for="gift_wrapping_quantity">Gift Wrapping Quantity:</label>
        <input type="number" name="gift_wrapping_quantity" id="gift_wrapping_quantity" value="0" min="0" step="1">
    </div>
    <?php
}

// Save Gift Wrapping Quantity to Cart Item Data
add_filter('woocommerce_add_cart_item_data', 'wwccs_save_gift_wrapping_quantity_to_cart', 10, 2);
function wwccs_save_gift_wrapping_quantity_to_cart($cart_item_data, $product_id) {
    if (isset($_POST['gift_wrapping_quantity'])) {
        $cart_item_data['gift_wrapping_quantity'] = (int) $_POST['gift_wrapping_quantity'];
        // Ensure each cart item is unique
        $cart_item_data['unique_key'] = md5(microtime() . rand());
    }
    return $cart_item_data;
}

// Display Gift Wrapping Quantity in Cart and Checkout
add_filter('woocommerce_get_item_data', 'display_gift_wrapping_quantity_cart', 10, 2);
function display_gift_wrapping_quantity_cart($item_data, $cart_item) {
    if (isset($cart_item['gift_wrapping_quantity'])) {
        $item_data[] = array(
            'name' => __('Gift Wrapping Quantity', 'woocommerce'),
            'value' => $cart_item['gift_wrapping_quantity'],
        );
    }
    return $item_data;
}

// Update Cart Item Price Based on Gift Wrapping Quantity
add_action('woocommerce_before_calculate_totals', 'wwccs_update_cart_item_price_based_on_gift_wrapping');
function wwccs_update_cart_item_price_based_on_gift_wrapping($cart) {
    if (is_admin() && !defined('DOING_AJAX'))
        return;

    foreach ($cart->get_cart() as $cart_item) {
        if (isset($cart_item['gift_wrapping_quantity'])) {
            $gift_wrapping_quantity = $cart_item['gift_wrapping_quantity'];
            $product = $cart_item['data'];
            $original_price = floatval($product->get_regular_price());
            $additional_price = $gift_wrapping_quantity * 2; // Add $10 for each gift-wrapped item
            $new_price = $original_price + $additional_price;
            $product->set_price($new_price);
        }
    }
}
```