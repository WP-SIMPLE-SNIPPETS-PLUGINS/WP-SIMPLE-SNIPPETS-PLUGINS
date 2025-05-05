# How to Make a Coupon Code Mandatory for Specific WooCommerce Product Category?

```generic
add_action( 'woocommerce_check_cart_items', 'wwccs_mandatory_coupon_code' );
function wwccs_mandatory_coupon_code() {
     // Set your product categories in the array (can be term IDs, slugs or names)
    $product_categories = array( 'electronics' );

    $found = false;

    // Loop through cart items to check for the product categories
    foreach ( WC()->cart->get_cart() as $cart_item ){
        if( has_term( $product_categories, 'product_cat', $cart_item['product_id'] ) ){
            $found = true; // cart item from the product category is found
            break; // We can stop the loop
        }
    }

    // If not found we exit
    if( ! $found ) return; // exit

    $applied_coupons = WC()->cart->get_applied_coupons();

    // Coupon not applied and product category found
    if( is_array($applied_coupons) && sizeof($applied_coupons) == 0 ) {
        // Display an error notice preventing checkout
        $message = __( 'Please enter a coupon code to be able to checkout.', 'woocommerce' );
        wc_add_notice( $message, 'error' );
    }
}
```