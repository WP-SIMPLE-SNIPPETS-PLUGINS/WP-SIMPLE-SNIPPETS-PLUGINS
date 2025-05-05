# How to Customize the WooCommerce Product Page with a Product Input Number Field?

```generic
add_filter( 'woocommerce_get_item_data', 'wwccs_display_custom_text_in_cart', 10, 2 );
function wwccs_display_custom_text_in_cart( $cart_data, $cart_item ) {
    if ( isset( $cart_item['custom_text'] ) ) {
        $cart_data[] = array(
            'name' => 'Custom Text',
            'value' => sanitize_text_field( $cart_item['custom_text'] ),
        );
    }
    if ( isset( $cart_item['gift_wrapping_quantity'] ) ) {
        $cart_data[] = array(
            'name' => 'Gift Wrapping Quantity',
            'value' => intval( $cart_item['gift_wrapping_quantity'] ),
        );
    }
    return $cart_data;
}

// Save the custom text field value to the order items
add_action( 'woocommerce_checkout_create_order_line_item', 'wwccs_save_custom_text_to_order_items', 10, 4 );
function wwccs_save_custom_text_to_order_items( $item, $cart_item_key, $values, $order ) {
    if ( isset( $values['custom_text'] ) ) {
        $item->add_meta_data( 'Custom Text', $values['custom_text'], true );
    }
    if ( isset( $values['gift_wrapping_quantity'] ) ) {
        $item->add_meta_data( 'Gift Wrapping Quantity', $values['gift_wrapping_quantity'], true );
    }
}

// Display custom text in admin order items table
add_filter( 'woocommerce_order_item_name', 'wwccs_custom_text_display_in_admin_order_items_table', 10, 2 );
function wwccs_custom_text_display_in_admin_order_items_table( $item_name, $item ) {
    // Check if the item has custom text associated with it
    if ( $custom_text = $item->get_meta( 'Custom Text' ) ) {
        // Append the custom text to the item name
        $item_name .= '<br><small>' . esc_html__( 'Custom Text:', 'your-textdomain' ) . ' ' . esc_html( $custom_text ) . '</small>';
    }
    // Check if the item has gift wrapping quantity associated with it
    if ( $gift_wrapping_quantity = $item->get_meta( 'Gift Wrapping Quantity' ) ) {
        // Append the gift wrapping quantity to the item name
        $item_name .= '<br><small>' . esc_html__( 'Gift Wrapping Quantity:', 'your-textdomain' ) . ' ' . esc_html( $gift_wrapping_quantity ) . '</small>';
    }
    return $item_name;
}

// Product input field for gift wrapping
add_action( 'woocommerce_before_add_to_cart_button', 'wwccs_gift_wrapping_input', 9 );
function wwccs_gift_wrapping_input() {
    woocommerce_form_field( 'gift_wrapping_quantity', array(
       'type' => 'number', // Change the type to 'number'
       'required' => true,
       'label' => 'Number of items for gift wrapping',
       'input_class' => array( 'input-text', 'qty', 'text', 'gift-wrapping-quantity' ), // Add input classes if needed
    ));
}

// Hook to add gift wrapping quantity to cart item data
add_filter( 'woocommerce_add_cart_item_data', 'wwccs_add_gift_wrapping_to_cart_item_data', 10, 2 );
function wwccs_add_gift_wrapping_to_cart_item_data( $cart_item_data, $product_id ) {
    if ( isset( $_POST['gift_wrapping_quantity'] ) ) {
        $cart_item_data['gift_wrapping_quantity'] = intval( $_POST['gift_wrapping_quantity'] );
    }
    return $cart_item_data;
}
```