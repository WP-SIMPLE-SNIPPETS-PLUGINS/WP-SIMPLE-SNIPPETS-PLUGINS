# How to Enable Decimal Quantities and Stock for WooCommerce Products?

```generic
// Custom conditional function (check for product categories)
function wwccs_enabled_decimal_quantities( $product ) {
    $targeted_terms = array(25, 79); // Here define your product category terms (names, slugs, or IDs)

    return has_term( $targeted_terms, 'product_cat', $product->get_id() );
}

// Defined quantity arguments 
add_filter( 'woocommerce_quantity_input_args', 'wwccs_custom_quantity_input_args', 9000, 2 );
function wwccs_custom_quantity_input_args( $args, $product ) {
    if( wwccs_enabled_decimal_quantities( $product ) ) {
        if( ! is_cart() ) {
            $args['input_value'] = 0.5; // Starting value
        }
        $args['min_value']   = 0.5; // Minimum value
        $args['step']        = 0.5; // Quantity steps
    }
    return $args;
}

// For Ajax add to cart button (define the min value)
add_filter( 'woocommerce_loop_add_to_cart_args', 'wwccs_custom_loop_add_to_cart_quantity_arg', 10, 2 );
function wwccs_custom_loop_add_to_cart_quantity_arg( $args, $product ) {
    if( wwccs_enabled_decimal_quantities( $product ) ) {
        $args['quantity'] = 0.5; // Min value
    }
    return $args;
}

// For product variations (define the min value)
add_filter( 'woocommerce_available_variation', 'wwccs_filter_wc_available_variation_price_html', 10, 3);
function wwccs_filter_wc_available_variation_price_html( $data, $product, $variation ) {
    if( wwccs_enabled_decimal_quantities( $product ) ) {
        $data['min_qty'] = 0.5;
    }
    return $data;
}

// Enable decimal quantities for stock (in frontend and backend)
remove_filter('woocommerce_stock_amount', 'intval');
add_filter('woocommerce_stock_amount', 'floatval');
```