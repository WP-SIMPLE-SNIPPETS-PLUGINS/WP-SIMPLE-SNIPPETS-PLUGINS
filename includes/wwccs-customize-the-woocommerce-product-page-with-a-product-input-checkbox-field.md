# How to Customize the WooCommerce Product Page with a Product Input Checkbox Field?

```generic
add_action( 'woocommerce_before_add_to_cart_button', 'wwccs_display_custom_checkbox_field', 9 );
function wwccs_display_custom_checkbox_field() {
    echo '<div class="custom-checkbox-field">';
    woocommerce_form_field( 'wwccs_setup_installation_checkbox', array(
        'type' => 'checkbox',
        'label' => 'Add Setup and Installation Service?',
    ));
    echo '</div>';
}

// Add custom checkbox value to cart item data for all products
add_filter( 'woocommerce_add_cart_item_data', 'wwccs_add_custom_checkbox_to_cart', 10, 2 );
function wwccs_add_custom_checkbox_to_cart( $cart_item_data, $product_id ) {
    if ( isset( $_POST['wwccs_setup_installation_checkbox'] ) && $_POST['wwccs_setup_installation_checkbox'] ) {
        $cart_item_data['wwccs_setup_installation_checkbox'] = true;
    }
    return $cart_item_data;
}

// Display custom checkbox in cart and checkout
add_filter( 'woocommerce_get_item_data', 'wwccs_display_custom_checkbox_in_cart', 10, 2 );
function wwccs_display_custom_checkbox_in_cart( $cart_data, $cart_item ) {
    if ( isset( $cart_item['wwccs_setup_installation_checkbox'] ) && $cart_item['wwccs_setup_installation_checkbox'] ) {
        $cart_data[] = array(
            'name' => 'Setup and Installation Service',
            'value' => 'Yes',
        );
    }
    return $cart_data;
}

// Save the custom checkbox field value to the order items
add_action( 'woocommerce_checkout_create_order_line_item', 'wwccs_save_custom_checkbox_to_order_items', 10, 4 );
function wwccs_save_custom_checkbox_to_order_items( $item, $cart_item_key, $values, $order ) {
    if ( isset( $values['wwccs_setup_installation_checkbox'] ) && $values['wwccs_setup_installation_checkbox'] ) {
        $item->add_meta_data( 'Setup and Installation Service', 'Yes', true );
    }
}

// Display custom checkbox in admin order items table
add_filter( 'woocommerce_order_item_name', 'wwccs_setup_installation_display_in_admin_order_items_table', 10, 2 );
function wwccs_setup_installation_display_in_admin_order_items_table( $item_name, $item ) {
    // Check if the item has setup and installation service associated with it
    if ( $setup_installation = $item->get_meta( 'Setup and Installation Service' ) ) {
        // Append the setup and installation service to the item name
        $item_name .= '<br><small>' . esc_html__( 'Setup and Installation Service:', 'your-textdomain' ) . ' ' . esc_html( $setup_installation ) . '</small>';
    }
    return $item_name;
}
```