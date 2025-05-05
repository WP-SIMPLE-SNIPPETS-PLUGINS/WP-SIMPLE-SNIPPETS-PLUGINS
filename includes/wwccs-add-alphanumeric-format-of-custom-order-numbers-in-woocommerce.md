# How to Add Alphanumeric Format of Custom Order Numbers in WooCommerce?

```generic
add_filter( 'woocommerce_checkout_create_order', 'wwccs_save_order_number_metadata' );

function wwccs_save_order_number_metadata( $order ) {
    // Define the number of desired digits for the numeric part
    $digits = 4;

    // Define the letters for the alphabetic part
    $letters = range('A', 'Z');

    // Get the current sequential counter for the numeric part
    $data = get_option( 'wc_sequential_order_number' );
    $number = isset( $data['sequential'] ) ? intval( $data['sequential'] ) + 1 : 1;
    $data['sequential'] = $number;

    // Update the sequential counter
    update_option( 'wc_sequential_order_number', $data );

    // Calculate the alphabetic part of the order number
    $alphabetic_index = ($number - 1) % count($letters); // Subtract 1 to start from 'A'
    $alphabetic_part = $letters[$alphabetic_index];

    // Combine the alphabetic and numeric parts
    $order_number = $alphabetic_part . str_pad( $number, $digits, '0', STR_PAD_LEFT );

    // Add the order number as custom metadata
    $order->add_meta_data( '_order_number', $order_number, true );
}

// Add filter to read the order number from metadata
add_filter( 'woocommerce_order_number', 'wwccs_define_order_number', 10, 2 );

function wwccs_define_order_number( $order_id, $order ) {
    // Read the order number from metadata
    if ( $order_number = $order->get_meta( '_order_number' ) ) {
        $order_id = $order_number;
    }
    return $order_id;
}
```