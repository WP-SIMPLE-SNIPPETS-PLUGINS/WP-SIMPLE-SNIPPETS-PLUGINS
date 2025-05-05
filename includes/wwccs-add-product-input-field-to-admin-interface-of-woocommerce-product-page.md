# How to Add Product Input Field to Admin Interface of WooCommerce Product Page?

```generic
add_action( 'woocommerce_product_options_general_product_data', 'wwccs_add_custom_general_settings_fields' );
function wwccs_add_custom_general_settings_fields() {

    echo '<div class="options_group">';

    woocommerce_wp_text_input( array(
        'id'          => '_text_field_1',
        'label'       => __( 'Custom Input Field 1', 'woocommerce' ),
    ) );

    woocommerce_wp_text_input( array(
        'id'          => '_text_field_2',
        'label'       => __( 'Custom Input Field 2', 'woocommerce' ),
    ) );

    woocommerce_wp_text_input( array(
        'id'          => '_text_field_3',
        'label'       => __( 'Custom Input Field 3', 'woocommerce' ),
    ) );

    echo '</div>';
}

// Admin: Save product custom text fields values
add_action( 'woocommerce_process_product_meta', 'wwccs_save_custom_general_settings_fields_values', 20, 1 );
function wwccs_save_custom_general_settings_fields_values($post_id){
    if ( isset($_POST['_text_field_1']) )
        update_post_meta( $post_id, '_text_field_1', sanitize_text_field($_POST['_text_field_1']) );

    if ( isset($_POST['_text_field_2']) )
        update_post_meta( $post_id, '_text_field_2', sanitize_text_field($_POST['_text_field_2']) );

    if ( isset($_POST['_text_field_3']) )
        update_post_meta( $post_id, '_text_field_3', sanitize_text_field($_POST['_text_field_3']) );
 }



// Frontend: Display custom fields values before add to cart button on single product pages
add_action( 'woocommerce_before_add_to_cart_button', 'wwccs_display_custom_fields_before_add_to_cart', 10 );
function wwccs_display_custom_fields_before_add_to_cart() {
    global $product;

    $fields_values = array(); // Initializing

    if( $text_field_1 = $product->get_meta('_text_field_1') )
        $fields_values[] = $text_field_1; // Set the value in the array

    if( $text_field_2 = $product->get_meta('_text_field_2') )
        $fields_values[] = $text_field_2; // Set the value in the array

    if( $text_field_3 = $product->get_meta('_text_field_3') )
        $fields_values[] = $text_field_3; // Set the value in the array

    // If the array of values is not empty
    if( sizeof( $fields_values ) > 0 ){

        echo '<div>';

        // Loop through each existing custom field value
        foreach( $fields_values as $key => $value ) {
            // Wrap each value with <strong> tags to make it bold
            echo '<p><strong>' . $value . '</strong></p>';
        }

        echo '</div>';

    }
}
```