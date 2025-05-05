# How to Allow Admin to Edit Order Numbers in WooCommerce?

```generic
add_action('woocommerce_admin_order_data_after_order_details', 'wwccs_display_admin_order_order_number_custom_field', 10, 1);
function wwccs_display_admin_order_order_number_custom_field($order){
    $custom_order_number = get_post_meta($order->get_id(), '_order_number', true);
    // Output the custom order number field
    echo '<div class="edit_order_number"><p class="form-field _order_number_field" style="width:100%;">
    <label for="_order_number">'. __("Order number", "woocommerce").':</label>
    <input type="text" id="_order_number" name="_order_number" value="'. $custom_order_number .'">
    </p></div>';
}
// Save custom order number when order is updated in the admin page
add_action('woocommerce_process_shop_order_meta', 'wwccs_save_admin_order_order_number_custom_field');
function wwccs_save_admin_order_order_number_custom_field($post_id){
    // Check the user's permissions.
    if (!current_user_can('edit_shop_order', $post_id)) {
        return;
    }
    // Make sure that '_order_number' is set.
    if (isset($_POST['_order_number'])) {
        // Get the custom order number from the POST data
        $custom_order_number = sanitize_text_field($_POST['_order_number']);
        // Update custom field value
        update_post_meta($post_id, '_order_number', $custom_order_number);
    }
}
// Filter to replace order number with custom order number
add_filter('woocommerce_order_number', 'wwccs_replace_order_number_with_custom', 10, 2);
function wwccs_replace_order_number_with_custom($order_number, $order){
    $custom_order_number = $order->get_meta('_order_number');
  
    if (!empty($custom_order_number)) {
        $order_number = $custom_order_number;
    }
    return $order_number;
}
```