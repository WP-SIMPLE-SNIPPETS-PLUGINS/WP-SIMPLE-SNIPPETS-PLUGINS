# How to Set Custom Delivery Dates for Each WooCommerce Products (Including Holidays & Weekends)

```generic
// Add a custom field for delivery lead time in product settings
add_action('woocommerce_product_options_general_product_data', 'wwccs_add_delivery_lead_time_field');
function wwccs_add_delivery_lead_time_field() {
    woocommerce_wp_text_input(array(
        'id' => 'delivery_lead_time',
        'label' => __('Delivery Lead Time (in days)', 'woocommerce'),
        'description' => __('Enter the number of days required for delivery for this product.', 'woocommerce'),
        'desc_tip' => true,
        'type' => 'number',
        'custom_attributes' => array(
            'min' => '0', // Minimum delivery time
        ),
    ));
}

// Save the custom field value
add_action('woocommerce_process_product_meta', 'wwccs_save_delivery_lead_time_field');
function wwccs_save_delivery_lead_time_field($post_id) {
    $delivery_lead_time = isset($_POST['delivery_lead_time']) ? sanitize_text_field($_POST['delivery_lead_time']) : '';
    update_post_meta($post_id, 'delivery_lead_time', $delivery_lead_time);
}

// Display the estimated delivery date on the product page
add_action('woocommerce_after_add_to_cart_form', 'wwccs_show_estimated_delivery_date');
function wwccs_show_estimated_delivery_date() {
    global $product;

    // Get the delivery lead time for the current product
    $lead_time = get_post_meta($product->get_id(), 'delivery_lead_time', true);

    // Fallback to a default lead time if none is set
    $lead_time = $lead_time ? intval($lead_time) : 1;

   // Define public holidays
    $public_holidays = array(
        '2025-01-20', // Monday (Martin Luther King Jr. Day)
        '2025-01-22', // Wednesday (Custom Example Holiday)
    );


    // Calculate the initial delivery date
    $delivery_date = new DateTime(current_time('mysql'));
    $delivery_date->modify("+{$lead_time} days");

    // Adjust for public holidays and weekends
    while (in_array($delivery_date->format('Y-m-d'), $public_holidays) || in_array($delivery_date->format('N'), array(6, 7))) {
        $delivery_date->modify('+1 day');
    }

    // Format the delivery date
    $formatted_date = $delivery_date->format('l, jS F');

    // Display the message
    echo "<div class='woocommerce-message' style='clear:both'>Estimated delivery date: <strong>{$formatted_date}</strong></div>";
}
```