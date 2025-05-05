# How to Customize the WooCommerce Product Page with a Product Input Text Field?

```generic
add_action( 'woocommerce_before_add_to_cart_button', 'wwccs_display_custom_text_field', 9 );
function wwccs_display_custom_text_field() {
    echo '<div class="custom-text-field">';
    woocommerce_form_field( 'custom_text', array(
       'type' => 'text',
       'required' => false, // Change to true if field is required
       'label' => 'Custom Text',
       'placeholder' => 'Enter custom text here...',
    ));
    echo '</div>';
}

// Add custom text to cart item data for all products
add_filter( 'woocommerce_add_cart_item_data', 'wwccs_add_custom_text_to_cart', 10, 2 );
function wwccs_add_custom_text_to_cart( $cart_item_data, $product_id ) {
    if ( isset( $_POST['custom_text'] ) ) {
        $cart_item_data['custom_text'] = sanitize_text_field( $_POST['custom_text'] );
    }
    return $cart_item_data;
}

// Display custom text in cart and checkout
add_filter( 'woocommerce_get_item_data', 'wwccs_display_custom_text_in_cart', 10, 2 );
function wwccs_display_custom_text_in_cart( $cart_data, $cart_item ) {
    if ( isset( $cart_item['custom_text'] ) ) {
        $cart_data[] = array(
            'name' => 'Custom Text',
            'value' => sanitize_text_field( $cart_item['custom_text'] ),
        );
    }
    return $cart_data;
}

// Save the custom text field value to the order items
add_action( 'woocommerce_checkout_create_order_line_item', 'wwccs_bbloomer_save_custom_text_to_order_items', 10, 4 );
function wwccs_bbloomer_save_custom_text_to_order_items( $item, $cart_item_key, $values, $order ) {
    if ( isset( $values['custom_text'] ) ) {
        $item->add_meta_data( 'Custom Text', $values['custom_text'], true );
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
    return $item_name;
}
```