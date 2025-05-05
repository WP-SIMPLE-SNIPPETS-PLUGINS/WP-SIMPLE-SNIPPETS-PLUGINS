# How to Add Email and Phone Number Field on WoCommerce Product Page?

```generic
add_action( 'woocommerce_before_add_to_cart_button', 'wwccs_add_email_phone_fields_to_product_page', 9 );
function wwccs_add_email_phone_fields_to_product_page() {
    echo '<div class="custom-email-phone-fields">';
    
    // Email Field
    woocommerce_form_field( 'custom_email', array(
        'type'          => 'email',
        'required'      => true, // Change to false if the field is not required
        'label'         => 'Email Address',
        'placeholder'   => 'Enter your email address...',
        'class'         => array('custom-email-field'), // Add custom CSS class if needed
    ));
    
    // Phone Number Field
    woocommerce_form_field( 'custom_phone', array(
        'type'          => 'tel',
        'required'      => true, // Change to false if the field is not required
        'label'         => 'Phone Number',
        'placeholder'   => 'Enter your phone number...',
        'class'         => array('custom-phone-field'), // Add custom CSS class if needed
    ));
    
    echo '</div>';
}

// Validate email and phone number fields before adding to cart
add_filter( 'woocommerce_add_to_cart_validation', 'wwccs_validate_email_phone_fields', 10, 3 );
function wwccs_validate_email_phone_fields( $passed, $product_id, $quantity ) {
    if ( empty( $_POST['custom_email'] ) || empty( $_POST['custom_phone'] ) ) {
        wc_add_notice( 'Please enter both email address and phone number.', 'error' );
        $passed = false;
    }
    return $passed;
}

// Save email and phone number to cart item data
add_filter( 'woocommerce_add_cart_item_data', 'wwccs_save_email_phone_to_cart_item_data', 10, 2 );
function wwccs_save_email_phone_to_cart_item_data( $cart_item_data, $product_id ) {
    if ( isset( $_POST['custom_email'] ) && isset( $_POST['custom_phone'] ) ) {
        $cart_item_data['custom_email'] = sanitize_email( $_POST['custom_email'] );
        $cart_item_data['custom_phone'] = sanitize_text_field( $_POST['custom_phone'] );
    }
    return $cart_item_data;
}

// Display email and phone number in cart and checkout pages
add_filter( 'woocommerce_get_item_data', 'wwccs_display_email_phone_in_cart_and_checkout', 10, 2 );
function wwccs_display_email_phone_in_cart_and_checkout( $item_data, $cart_item ) {
    if ( isset( $cart_item['custom_email'] ) && isset( $cart_item['custom_phone'] ) ) {
        $item_data[] = array(
            'key'   => 'Email',
            'value' => sanitize_email( $cart_item['custom_email'] ),
        );
        $item_data[] = array(
            'key'   => 'Phone',
            'value' => sanitize_text_field( $cart_item['custom_phone'] ),
        );
    }
    return $item_data;
}
```