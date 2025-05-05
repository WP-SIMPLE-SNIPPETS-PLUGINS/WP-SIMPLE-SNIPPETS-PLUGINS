# How to Add Step Quantity to the WooCommerce Product Page and Cart Blocks Page?

```generic
add_filter( 'woocommerce_quantity_input_args', 'wwccs_woocommerce_quantity_selected_number', 10, 2 );

function wwccs_woocommerce_quantity_selected_number( $args, $product ) {
    // Get product categories
    $categories = wp_get_post_terms( $product->get_id(), 'product_cat', array( 'fields' => 'slugs' ) );
    
    // Check if the product belongs to the 'electronic' category
    if ( array_intersect( ['electronics'], $categories ) ) {
        $args['input_value'] = 10; // Start from this value
        $args['step'] = 10; // Increment or decrement by this value
        $args['min_value'] = 10; // Ensure the minimum value is 10
    }
    return $args;
}

add_filter(
    'woocommerce_store_api_product_quantity_multiple_of',
    function( $value, $product, $cart_item ) {
        $categories = wp_get_post_terms( $product->get_id(), 'product_cat', array( 'fields' => 'slugs' ) );
        
        if ( array_intersect( ['electronics'], $categories ) ) {
            return 10;
        }
        return $value;
    },
    10,
    3
);
```