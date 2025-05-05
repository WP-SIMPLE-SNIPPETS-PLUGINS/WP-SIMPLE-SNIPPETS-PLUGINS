# How to Add Custom Validation on Email Domains in WooCommerce Checkout Blocks & Classic Checkout Page

```generic
add_action( 'woocommerce_store_api_checkout_update_order_from_request', function( $order, $request ) {
    $cart = WC()->cart->get_cart();
    $required_product_id = 2269; // Replace with your required product ID
    $email_domain = 'tychesoftwares.com'; // Replace with the required email domain
    $product_found = false;

    // Check if the required product is in the cart
    foreach ( $cart as $cart_item ) {
        if ( $cart_item['product_id'] == $required_product_id ) {
            $product_found = true;
            break;
        }
    }

    // Get the customer email from the request
    $billing_email = isset( $request['billing_address']['email'] ) ? sanitize_email( $request['billing_address']['email'] ) : '';

    // Extract domain from email
    $email_parts = explode( '@', $billing_email );
    $customer_domain = isset( $email_parts[1] ) ? $email_parts[1] : '';

    // Validation: If the required product is in the cart, enforce the email check
    if ( $product_found && $customer_domain !== $email_domain ) {
        throw new Exception( __( 'To purchase this product, you must use an email address ending in @' . $email_domain, 'woocommerce' ) );
    }
}, 10, 2 );
```