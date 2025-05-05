# How to Automatically Set WooCommerce Billing Postcode from a Custom Input Field?

```generic
add_action('woocommerce_after_add_to_cart_button', 'wwccs_custom_field_delivery_postcode', 10 );
function wwccs_custom_field_delivery_postcode() {
    woocommerce_form_field('postcode_note', array(
        'type' => 'text',
        'class' => array('my-field-class', 'form-row-wide'),
        'label' => __('Postcode'),
        'placeholder' => __('Enter your service postcode...'),
        'required' => true,
    ), '');
}

// Add the postcode to cart item data
add_filter('woocommerce_add_cart_item_data', 'wwccs_add_delivery_postcode_to_cart_item_data', 20, 2);
function wwccs_add_delivery_postcode_to_cart_item_data($cart_item_data, $product_id) {
    if (isset($_POST['postcode_note']) && !empty($_POST['postcode_note'])) {
        $postcode_note = sanitize_text_field($_POST['postcode_note']);
        $cart_item_data['postcode_note'] = $postcode_note;

        // Set the postcode in the customer session for shipping and billing
        WC()->customer->set_shipping_postcode($postcode_note);
        WC()->customer->set_billing_postcode($postcode_note);
    }
    return $cart_item_data;
}

// Set the postcode in the shipping calculator and checkout fields
add_action('woocommerce_cart_calculate_fees', 'wwccs_set_customer_postcode_in_session');
function wwccs_set_customer_postcode_in_session() {
    if (isset($_POST['postcode_note']) && !empty($_POST['postcode_note'])) {
        $postcode_note = sanitize_text_field($_POST['postcode_note']);
        WC()->customer->set_shipping_postcode($postcode_note);
        WC()->customer->set_billing_postcode($postcode_note);
    }
}
```