# How to Add a 'Checkout' Button on WooCommerce Product Page?

```generic
add_action( 'woocommerce_after_add_to_cart_button', 'wwccs_add_proceed_to_checkout_button', 20 );

function wwccs_add_proceed_to_checkout_button() {
    // Get the WooCommerce checkout URL
    $checkout_url = wc_get_checkout_url();

    // Output the "Proceed to Checkout" button with inline styling
    echo '<a href="' . esc_url( $checkout_url ) . '" class="button" style="background-color:  #f7b619; padding: 10px 20px; font-size: 16px; border: none; border-radius: 5px; text-decoration: none; display: inline-block; cursor: pointer; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); transition: transform 0.2s, box-shadow 0.2s;">Proceed to Checkout</a>';
}
```