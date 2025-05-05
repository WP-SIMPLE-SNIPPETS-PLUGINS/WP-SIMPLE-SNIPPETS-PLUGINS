# How to Add Custom Input Field in WooCommerce Admin for Product Variation SKU Field ?

```generic
add_action('woocommerce_variation_options', 'wwccs_field_variable', 10, 3);

function wwccs_field_variable($loop, $variation_data, $variation){
    woocommerce_wp_text_input(
        array(
            'id' => '_customsku', // Corrected field ID to match the saving function
            'name' => '_customsku[' . $loop . ']', // Add name attribute 
            'value' => get_post_meta($variation->ID, '_customsku', true),
            'label' => esc_html__('CustomSKU', 'my_field'),
            'desc_tip' => true,
            'description' => __('Enter the CustomSKU', 'my_field'),
        )
    );
}

// Save the field inputted value
add_action('woocommerce_save_product_variation', 'wwccs_save_variation_custom_sku_input_field_value', 10, 2 );

function wwccs_save_variation_custom_sku_input_field_value($variation_id, $i) {
    if(isset($_POST['_customsku'][$i])) {
        $custom_sku = sanitize_text_field($_POST['_customsku'][$i]);
        update_post_meta($variation_id, '_customsku', $custom_sku);
    }
}
```