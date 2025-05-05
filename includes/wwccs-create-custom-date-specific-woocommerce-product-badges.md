# How to Create Custom Date-Specific WooCommerce Product Badges?

```generic
// Add custom fields for product badges in the WooCommerce product editor
add_action( 'woocommerce_product_options_general_product_data', 'wwccs_add_date_specific_product_badge_fields' );

function wwccs_add_date_specific_product_badge_fields() {
    global $product_object;

    // Badge text field
    woocommerce_wp_text_input( array(
        'id'            => '_badge_text',
        'label'         => __( 'Badge Text', 'woocommerce' ),
        'description'   => __( 'Enter the badge text to display on the product page.', 'woocommerce' ),
        'desc_tip'      => true,
        'type'          => 'text',
        'value'         => $product_object->get_meta( '_badge_text' ),
    ) );

    // Start date field
    woocommerce_wp_text_input( array(
        'id'            => '_badge_start_date',
        'label'         => __( 'Badge Start Date', 'woocommerce' ),
        'placeholder'   => 'YYYY-MM-DD',
        'description'   => __( 'Enter the start date for the badge display.', 'woocommerce' ),
        'type'          => 'date',
        'value'         => $product_object->get_meta( '_badge_start_date' ),
    ) );

    // End date field
    woocommerce_wp_text_input( array(
        'id'            => '_badge_end_date',
        'label'         => __( 'Badge End Date', 'woocommerce' ),
        'placeholder'   => 'YYYY-MM-DD',
        'description'   => __( 'Enter the end date for the badge display.', 'woocommerce' ),
        'type'          => 'date',
        'value'         => $product_object->get_meta( '_badge_end_date' ),
    ) );
}
// Save custom field values for badge text, start date, and end date
add_action( 'woocommerce_admin_process_product_object', 'wwccs_save_date_specific_badge_fields' );

function wwccs_save_date_specific_badge_fields( $product ) {
    if ( isset( $_POST['_badge_text'] ) ) {
        $product->update_meta_data( '_badge_text', sanitize_text_field( $_POST['_badge_text'] ) );
    }
    if ( isset( $_POST['_badge_start_date'] ) ) {
        $product->update_meta_data( '_badge_start_date', sanitize_text_field( $_POST['_badge_start_date'] ) );
    }
    if ( isset( $_POST['_badge_end_date'] ) ) {
        $product->update_meta_data( '_badge_end_date', sanitize_text_field( $_POST['_badge_end_date'] ) );
    }
    $product->save();
}
add_action( 'woocommerce_before_add_to_cart_form', 'wwccs_display_date_specific_badge_on_product_page', 1 );

function wwccs_display_date_specific_badge_on_product_page() {
    global $product;
    if ( is_a( $product, 'WC_Product' ) ) {
        $badge_text = $product->get_meta( '_badge_text' );
        $start_date = $product->get_meta( '_badge_start_date' );
        $end_date = $product->get_meta( '_badge_end_date' );

        // Get the current date in Y-m-d format
        $current_date = date( 'Y-m-d' );

        // If badge text exists
        if ( !empty( $badge_text ) ) {
            // If both start date and end date are not set, or the current date is within the specified range
            if (
                (empty( $start_date ) && empty( $end_date )) || // No start or end date, always show the badge
                ( !empty( $start_date ) && $current_date >= $start_date ) && // Start date is set and current date is after or equal to it
                ( !empty( $end_date ) && $current_date <= $end_date ) || // End date is set and current date is before or equal to it
                ( !empty( $start_date ) && !empty( $end_date ) && $current_date >= $start_date && $current_date <= $end_date ) // Both dates are set and current date is within the range
            ) {
                // Display the badge
                echo '<div class="woocommerce-message badge-text">' . esc_html( $badge_text ) . '</div>';
            }
        }
    }
}
```