# How to Add Free Shipping Progress Bar in WooCommerce Product Page?

```generic
add_filter( 'woocommerce_package_rates', 'wwccs_hide_free_shipping', 10, 2 );
function wwccs_hide_free_shipping( $rates, $package ) {
    // Calculate cart total
    $cart_total = WC()->cart->get_subtotal();

    // Check if cart total is less than 100
    if ( $cart_total < 100 ) {
        // Loop through shipping rates
        foreach ( $rates as $rate_id => $rate ) {
            // Check if the rate is free shipping and unset it
            if ( 'free_shipping' === $rate->method_id ) {
                unset( $rates[ $rate_id ] );
            }
        }
    }

    return $rates;
}


// Show 'Spend another X amount' on cart page.
add_filter( 'woocommerce_cart_totals_before_shipping', 'wwccs_cart_page_progress_bar', 10 );
function wwccs_cart_page_progress_bar() {
    $cart_total = WC()->cart->get_subtotal();
    $cart_remaining = 100 - $cart_total;
    if ($cart_total < 100 ) {
        echo '<div style="display: flex; align-items: center;">';
        echo '<span style="margin-right: 10px;">' . get_woocommerce_currency_symbol() . '0</span>';
        echo '<progress id="freeshippingprogress" max="100" value="'.$cart_total.'"></progress>';
        echo '<span style="margin-left: 10px;">' . get_woocommerce_currency_symbol() . '100</span>';
        echo '</div>';
        echo '<span style="color:blue;">You\'re ' . get_woocommerce_currency_symbol() . $cart_remaining . ' away from free shipping!</span>';
    } else {
        echo '<span style="color:blue;">You\'ve unlocked free shipping!</span>';
    }
};

// Show 'Spend another X amount' on checkout.
add_filter( 'woocommerce_checkout_before_order_review', 'wwccs_checkout_page_progress_bar', 10 );
function wwccs_checkout_page_progress_bar() {
    $cart_total = WC()->cart->get_subtotal();
    $cart_remaining = 100 - $cart_total;
    if ($cart_total < 100 ) {
   echo '<span style="color:blue;">Spend another ' . get_woocommerce_currency_symbol() . $cart_remaining . ' for free shipping!</span><br><progress id="freeshippingprogress" max="100" value="'.$cart_total.'"></progress>';
    } else {
        echo '<span style="color:blue;">You\'ve unlocked free shipping!</span>';
    }
};
```