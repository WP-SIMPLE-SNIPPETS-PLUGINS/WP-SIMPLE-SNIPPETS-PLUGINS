# How to Add Custom Fields to Linked Products of Admin Interface in WooCommerce?

```generic
function wwccs_woocom_linked_producwwccs_data_custom_field() {
    global $woocommerce, $post;
    ?>
    <p class="form-field">
        <label for="upsizing_products"><?php _e( 'Upsizing Product', 'woocommerce' ); ?></label>
        <select class="wc-product-search" multiple="multiple" style="width: 50%;" id="upsizing_products" name="upsizing_products[]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woocommerce' ); ?>" data-action="woocommerce_json_search_producwwccs_and_variations" data-exclude="<?php echo intval( $post->ID ); ?>">
            <?php
            $product_ids = get_post_meta( $post->ID, '_upsizing_producwwccs_ids', true );

            foreach ( $product_ids as $product_id ) {
                $product = wc_get_product( $product_id );
                if ( is_object( $product ) ) {
                    echo '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $product->get_formatted_name() ) . '</option>';
                }
            }
            ?>
        </select> <?php echo wc_help_tip( __( 'Select Products Here.', 'woocommerce' ) ); ?>
    </p>

    <?php
    // Display the upsizing products below the dropdown list
    if ( $product_ids ) {
        echo '<p><strong>Selected Upsizing Products:</strong></p>';
        echo '<ul>';

        foreach ( $product_ids as $product_id ) {
            $product = wc_get_product( $product_id );
            if ( is_object( $product ) ) {
                echo '<li>' . wp_kses_post( $product->get_formatted_name() ) . '</li>';
            }
        }

        echo '</ul>';
    }
}

// Function to save the custom fields
function wwccs_woocom_linked_producwwccs_data_custom_field_save( $post_id ){
    $product_field_type =  $_POST['upsizing_products'];
    update_post_meta( $post_id, '_upsizing_producwwccs_ids', $product_field_type );
}
function wwccs_display_upsizing_products() {
    global $product;

    // Get upsizing products associated with the current product
    $upsizing_product_ids = get_post_meta( $product->get_id(), '_upsizing_producwwccs_ids', true );

    // If upsizing products exist, display them
    if ( $upsizing_product_ids ) {
        echo '<div class="upsizing-products">';
        echo '<h2>Upsizing Products</h2>';
        echo '<ul>';

        foreach ( $upsizing_product_ids as $upsizing_product_id ) {
            $upsizing_product = wc_get_product( $upsizing_product_id );
            if ( $upsizing_product ) {
                echo '<li><a href="' . esc_url( get_permalink( $upsizing_product_id ) ) . '">' . esc_html( $upsizing_product->get_name() ) . '</a></li>';
            }
        }

        echo '</ul>';
        echo '</div>';
    }
}

// Hook the function to display upsizing products on the product page
add_action( 'woocommerce_single_product_summary', 'wwccs_display_upsizing_products', 25 );


// Add meta box to product edit page
add_action( 'woocommerce_product_options_related', 'wwccs_woocom_linked_producwwccs_data_custom_field' );

// Save custom field data
add_action( 'woocommerce_process_product_meta', 'wwccs_woocom_linked_producwwccs_data_custom_field_save' );
```