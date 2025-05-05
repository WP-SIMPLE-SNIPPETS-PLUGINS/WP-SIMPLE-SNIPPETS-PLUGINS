# How To Add a Timepicker Product Input Field to WooCommerce Product Page?

```generic
add_action( 'woocommerce_before_add_to_cart_button', 'wwccs_display_custom_time_picker', 9 );
function wwccs_display_custom_time_picker() {
    echo '<div class="custom-time-picker">';
    woocommerce_form_field( 'custom_time', array(
        'type'         => 'time',
        'required'     => false, // Change to true if field is required
        'label'        => 'Custom Time',
        'placeholder'  => 'Select custom time...',
        'class'        => array('custom-time-picker-class'), // Add custom CSS class if needed
        'autocomplete' => 'off', // Disable browser autocomplete
    ));
    echo '</div>';
}

// Add custom time to cart item data for all products
add_filter( 'woocommerce_add_cart_item_data', 'wwccs_add_custom_time_to_cart', 10, 2 );
function wwccs_add_custom_time_to_cart( $cart_item_data, $product_id ) {
    if ( isset( $_POST['custom_time'] ) ) {
        $cart_item_data['custom_time'] = sanitize_text_field( $_POST['custom_time'] );
    }
    return $cart_item_data;
}

// Display custom time in cart and checkout
add_filter( 'woocommerce_get_item_data', 'wwccs_display_custom_time_in_cart', 10, 2 );
function wwccs_display_custom_time_in_cart( $cart_data, $cart_item ) {
    if ( isset( $cart_item['custom_time'] ) ) {
        $cart_data[] = array(
            'name'  => 'Custom Time',
            'value' => sanitize_text_field( $cart_item['custom_time'] ),
        );
    }
    return $cart_data;
}

// Save the custom time field value to the order items
add_action( 'woocommerce_checkout_create_order_line_item', 'wwccs_save_custom_time_to_order_items', 10, 4 );
function wwccs_save_custom_time_to_order_items( $item, $cart_item_key, $values, $order ) {
    if ( isset( $values['custom_time'] ) ) {
        $item->add_meta_data( 'Custom Time', $values['custom_time'], true );
    }
}

// Display custom time in admin order items table
add_filter( 'woocommerce_order_item_name', 'wwccs_display_custom_time_in_admin_order_items_table', 10, 2 );
function wwccs_display_custom_time_in_admin_order_items_table( $item_name, $item ) {
    // Check if the item has custom time associated with it
    if ( $custom_time = $item->get_meta( 'Custom Time' ) ) {
        // Append the custom time to the item name
        $item_name .= '<br><small>' . esc_html__( 'Custom Time:', 'your-textdomain' ) . ' ' . esc_html( $custom_time ) . '</small>';
    }
    return $item_name;
}
```