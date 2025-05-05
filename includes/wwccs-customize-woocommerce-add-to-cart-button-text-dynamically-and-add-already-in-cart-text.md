# How to Customize WooCommerce Add to Cart Button Text Dynamically and add “Already in cart” text?

```generic
// Add custom text field to the product editor
function wwccs_add_custom_add_to_cart_text_field() {
    global $post;

    // Make sure we are on the right product type
    if ( 'product' != $post->post_type ) {
        return;
    }

    echo '<div class="options_group">';
    woocommerce_wp_text_input( 
        array( 
            'id' => '_custom_add_to_cart_text',
            'label' => __( 'Custom Add to Cart Text', 'custom-add-to-cart-button-text' ),
            'desc_tip' => 'true',
            'description' => __( 'Enter custom text for the Add to Cart button.', 'custom-add-to-cart-button-text' ),
            'value' => get_post_meta( $post->ID, '_custom_add_to_cart_text', true ) 
        )
    );
    echo '</div>';
}

// Save custom text input
function wwccs_save_custom_add_to_cart_text_field( $post_id ) {
    if ( 'product' != get_post_type( $post_id ) ) {
        return;
    }

    if ( isset( $_POST['_custom_add_to_cart_text'] ) ) {
        update_post_meta( $post_id, '_custom_add_to_cart_text', sanitize_text_field( $_POST['_custom_add_to_cart_text'] ) );
    }
}

// Change Add to Cart button text on the product page
function wwccs_modify_single_product_button_text( $text ) {
    global $post;
    $custom_text = get_post_meta( $post->ID, '_custom_add_to_cart_text', true );
    if ( ! empty( $custom_text ) ) {
        $text = $custom_text;
    }
    return $text;
}

// Hooks to add custom field and save custom text
add_action( 'woocommerce_product_options_general_product_data', 'wwccs_add_custom_add_to_cart_text_field' );
add_action( 'woocommerce_process_product_meta', 'wwccs_save_custom_add_to_cart_text_field' );
add_filter( 'woocommerce_product_single_add_to_cart_text', 'wwccs_modify_single_product_button_text' );
// Modify Add to Cart button text based on whether the product is in the cart or not
function wwccs_modify_product_button_text( $text ) {
    global $post;

    // Fetch the custom text from product meta data
    $custom_text = get_post_meta( $post->ID, '_custom_add_to_cart_text', true );

    // Check if the current product is already in the cart
    if ( is_product_in_cart( $post->ID ) ) {
        // Change button text if the product is in the cart
        $text = 'Already in Cart! Buy Again';
    }
    // If custom text is available, use it. Otherwise, default to 'Add to Cart'
    elseif ( ! empty( $custom_text ) ) {
        $text = $custom_text;
    }

    return $text;
}

// Hook into the WooCommerce filter to modify the Add to Cart button text
add_filter( 'woocommerce_product_single_add_to_cart_text', 'wwccs_modify_product_button_text' );

// Function to check if the product is in the cart
function is_product_in_cart( $product_id ) {
    // Get the global WooCommerce cart object
    $cart = WC()->cart;

    // Loop through each item in the cart
    foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
        // If the product ID is found in the cart, return true
        if ( $cart_item['product_id'] == $product_id ) {
            return true;
        }
    }

    // If product is not in the cart, return false
    return false;
}
```