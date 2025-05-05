# How to Add URL Field with Validation as Input Field on WooCommerce Product Page?

```generic
// Adding URL field on product page
add_action( 'woocommerce_before_add_to_cart_button', 'wwccs_add_url_field_to_product_page', 9 );
function wwccs_add_url_field_to_product_page() {
    echo '<div class="custom-url-field-wrapper">';

    // URL Field
    woocommerce_form_field( 'custom_url', array(
        'type' => 'url',
        'required' => true,
        'label' => 'Product URL',
        'placeholder' => 'Enter product URL...',
        'class' => array('custom-url-field'),
    ));

    echo '</div>';
}

// Validate URL field before adding to cart
add_filter( 'woocommerce_add_to_cart_validation', 'wwccs_validate_url_field', 10, 3 );
function wwccs_validate_url_field( $passed, $product_id, $quantity ) {
    if ( empty( $_POST['custom_url'] ) || ! filter_var( $_POST['custom_url'], FILTER_VALIDATE_URL ) ) {
        wc_add_notice( 'Please enter a valid URL for the product.', 'error' );
        $passed = false;
    }
    return $passed;
}

// Save custom URL to cart item data
add_filter( 'woocommerce_add_cart_item_data', 'wwccs_save_custom_url_to_cart_item_data', 10, 2 );
function wwccs_save_custom_url_to_cart_item_data( $cart_item_data, $product_id ) {
    if ( isset( $_POST['custom_url'] ) ) {
        $cart_item_data['custom_url'] = sanitize_text_field( $_POST['custom_url'] );
        $cart_item_data['unique_key'] = md5( microtime().rand() ); // Ensure unique cart item
    }
    return $cart_item_data;
}

// Display custom URL in the cart
add_filter( 'woocommerce_get_item_data', 'wwccs_display_custom_url_cart', 10, 2 );
function wwccs_display_custom_url_cart( $item_data, $cart_item ) {
    if ( isset( $cart_item['custom_url'] ) ) {
        $item_data[] = array(
            'name' => 'Product URL',
            'value' => esc_url( $cart_item['custom_url'] )
        );
    }
    return $item_data;
}

// Save custom URL to order meta
add_action( 'woocommerce_checkout_create_order_line_item', 'wwccs_save_custom_url_to_order_items', 10, 4 );
function wwccs_save_custom_url_to_order_items( $item, $cart_item_key, $values, $order ) {
    if ( isset( $values['custom_url'] ) ) {
        $item->add_meta_data( 'Product URL', $values['custom_url'], true );
    }
}

// Add a custom column header to the admin order items table
add_filter( 'woocommerce_admin_order_item_headers', 'wwccs_add_custom_column_header' );
function wwccs_add_custom_column_header() {
    echo '<th class="custom-url">Product URL</th>';
}

// Display the custom URL in the custom column on the admin order items table
add_action( 'woocommerce_admin_order_item_values', 'wwccs_add_custom_column_value', 10, 3 );
function wwccs_add_custom_column_value( $_product, $item, $item_id ) {
    if ( $item->get_meta( 'Product URL' ) ) {
        echo '<td class="custom-url">' . esc_url( $item->get_meta( 'Product URL' ) ) . '</td>';
    } else {
        echo '<td class="custom-url">-</td>';
    }
}
```