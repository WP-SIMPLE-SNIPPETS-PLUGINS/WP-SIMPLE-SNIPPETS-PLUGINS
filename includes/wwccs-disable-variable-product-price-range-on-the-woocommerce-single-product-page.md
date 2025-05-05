# How to Disable Variable Product Price Range on the WooCommerce Single Product Page?

```generic
add_filter( 'woocommerce_format_price_range', 'wwccs_disable_variable_price_range', 10, 3 );

function wwccs_disable_variable_price_range( $price, $from, $to ) {
    // Check if it's a variable product
    if ( is_product() && has_term( 'variable', 'product_type' ) ) {
        return ''; // Return empty to hide the price range
    }
    return $price; // Otherwise, return the normal price range
}
```