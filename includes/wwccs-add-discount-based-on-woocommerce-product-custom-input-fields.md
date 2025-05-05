# How to Add Discount Based on WooCommerce Product Custom Input Fields?

```generic
add_action( 'woocommerce_before_add_to_cart_button', 'wwccs_custom_product_price_field', 5 );
function wwccs_custom_product_price_field(){
    ?>
    <div class="custom-text text">
        <p><?php _e("Quantity of adults:"); ?></p>
        <input type="text" name="qtty_adults" value="" title="<?php _e("Quantity Adults"); ?>" class="qtty-field">
    </div>
    <div class="custom-text text">
        <p><?php _e("Quantity of children:"); ?></p>
        <input type="text" name="qtty_kids" value="" title="<?php _e("Quantity Kids"); ?>" class="qtty-field">
    </div>
    <?php
}

// Add selected add-on option as custom cart item data
add_filter( 'woocommerce_add_cart_item_data', 'wwccs_filter_add_cart_item_data_callback', 10, 3 );
function wwccs_filter_add_cart_item_data_callback( $cart_item_data, $product_id, $variation_id ) {
    $children_discount = 5; // Set the children discount amount here
    if ( isset( $_POST['qtty_kids'] ) ) {
        $cart_item_data['children_discount'] = (float) $children_discount * (int) sanitize_text_field( $_POST['qtty_kids'] );
        $cart_item_data['unique_key']        = md5( microtime().rand() ); // Make each item unique
    }
    return $cart_item_data;
}


// Set a discount based on a product custom field(s)
add_action('woocommerce_cart_calculate_fees' , 'wwccs_add_children_discount', 10, 1 );
function wwccs_add_children_discount( $cart ){
    if ( is_admin() && ! defined('DOING_AJAX') )
        return;

    if ( did_action('woocommerce_cart_calculate_fees') >= 2 )
        return;

    $discount = 0; // Initialising

    // Loop through cart items
    foreach ( $cart->get_cart() as $cart_item ) {
        if( isset( $cart_item['children_discount'] ) ) {
            $discount += $cart_item['children_discount'];
        }
    }

    if ( $discount > 0 )
        $cart->add_fee( __("Discount for children", "woocommerce"), -$discount );
}
```