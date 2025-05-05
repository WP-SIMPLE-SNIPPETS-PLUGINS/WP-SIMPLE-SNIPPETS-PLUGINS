# How to Add Custom Fields For Product Variations in WooCommerce?

```generic
// Add Variation Settings
add_action( 'woocommerce_product_after_variable_attributes', 'wwccs_variation_settings_fields', 10, 3 );

// Save Variation Settings
add_action( 'woocommerce_save_product_variation', 'wwccs_save_variation_settings_fields', 10, 2 );

/**
 * Create new fields for variations
 */
function wwccs_variation_settings_fields( $loop, $variation_data, $variation ) {
    // Text Field
    woocommerce_wp_text_input(
        array(
            'id' => '_text_field[' . $variation->ID . ']',
            'label' => __( 'My Text Field', 'woocommerce' ),
            'placeholder' => 'http://',
            'desc_tip' => 'true',
            'description' => __( 'Enter the custom value here.', 'woocommerce' ),
            'value' => get_post_meta( $variation->ID, '_text_field', true )
        )
    );

    // Number Field
    woocommerce_wp_text_input(
        array(
            'id' => '_number_field[' . $variation->ID . ']',
            'label' => __( 'My Number Field', 'woocommerce' ),
            'desc_tip' => 'true',
            'description' => __( 'Enter the custom number here.', 'woocommerce' ),
            'value' => get_post_meta( $variation->ID, '_number_field', true ),
            'custom_attributes' => array(
                'step' => 'any',
                'min' => '0'
            )
        )
    );

    // Textarea
    woocommerce_wp_textarea_input(
        array(
            'id' => '_textarea[' . $variation->ID . ']',
            'label' => __( 'My Textarea', 'woocommerce' ),
            'placeholder' => '',
            'description' => __( 'Enter the custom value here.', 'woocommerce' ),
            'value' => get_post_meta( $variation->ID, '_textarea', true ),
        )
    );

    // Select
    woocommerce_wp_select(
        array(
            'id' => '_select[' . $variation->ID . ']',
            'label' => __( 'My Select Field', 'woocommerce' ),
            'description' => __( 'Choose a size.', 'woocommerce' ),
            'value' => get_post_meta( $variation->ID, '_select', true ),
            'options' => array(
                'small' => __( 'Small', 'woocommerce' ),
                'medium' => __( 'Medium', 'woocommerce' ),
                'large' => __( 'Large', 'woocommerce' ),
                'x-large' => __( 'X-Large', 'woocommerce' )
            ),
            'custom_attributes' => array(
                'data-display' => json_encode(array(
                    'Small' => __( 'Small', 'woocommerce' ),
                    'Medium' => __( 'Medium', 'woocommerce' ),
                    'Large' => __( 'Large', 'woocommerce' ),
                    'X-Large' => __( 'X-Large', 'woocommerce' )
                )),
            ),
        )
    );

    // Checkbox
    woocommerce_wp_checkbox(
        array(
            'id' => '_checkbox[' . $variation->ID . ']',
            'label' => __('Washing Type: Wet Wash', 'woocommerce' ),
            'value' => get_post_meta( $variation->ID, '_checkbox', true ),
        )
    );

    // Hidden field
    woocommerce_wp_hidden_input(
        array(
            'id' => '_hidden_field[' . $variation->ID . ']',
            'value' => 'hidden_value'
        )
    );
}

/**
 * Save new fields for variations
 */
function wwccs_save_variation_settings_fields( $post_id ) {
    // Text Field
    $text_field = $_POST['_text_field'][ $post_id ];
    if( ! empty( $text_field ) ) {
        update_post_meta( $post_id, '_text_field', esc_attr( $text_field ) );
    }

    // Number Field
    $number_field = $_POST['_number_field'][ $post_id ];
    if( ! empty( $number_field ) ) {
        update_post_meta( $post_id, '_number_field', esc_attr( $number_field ) );
    }

    // Textarea
    $textarea = $_POST['_textarea'][ $post_id ];
    if( ! empty( $textarea ) ) {
        update_post_meta( $post_id, '_textarea', esc_attr( $textarea ) );
    }

    // Select
    $select = $_POST['_select'][ $post_id ];
    if( ! empty( $select ) ) {
        update_post_meta( $post_id, '_select', esc_attr( $select ) );
    }

    // Checkbox
    $checkbox = isset( $_POST['_checkbox'][ $post_id ] ) ? 'Wet Wash' : 'Dry Wash';
    update_post_meta( $post_id, '_checkbox', $checkbox );

    // Hidden field
    $hidden = $_POST['_hidden_field'][ $post_id ];
    if( ! empty( $hidden ) ) {
        update_post_meta( $post_id, '_hidden_field', esc_attr( $hidden ) );
    }
}

// Add filter to load variation settings fields
add_filter( 'woocommerce_available_variation', 'wwccs_load_variation_settings_fields' );

/**
 * Load custom fields for variations
 */
function wwccs_load_variation_settings_fields( $variations ) {
    // Text Field
    $variations['text_field'] = get_post_meta( $variations['variation_id'], '_text_field', true );

    // Number Field
    $variations['number_field'] = get_post_meta( $variations['variation_id'], '_number_field', true );

    // Textarea
    $variations['textarea_field'] = get_post_meta( $variations['variation_id'], '_textarea', true );

    // Select Field
    $select_value = get_post_meta( $variations['variation_id'], '_select', true );

    // Mapping array for formatted display
    $formatted_values = array(
        'small' => __( 'Small', 'woocommerce' ),
        'medium' => __( 'Medium', 'woocommerce' ),
        'large' => __( 'Large', 'woocommerce' ),
        'x-large' => __( 'X-Large', 'woocommerce' )
    );

    // Set formatted text value based on the selected value
    $variations['select_field'] = isset( $formatted_values[$select_value] ) ? $formatted_values[$select_value] : '';

    // Checkbox Field
    $checkbox_value = get_post_meta( $variations['variation_id'], '_checkbox', true );
    $variations['checkbox_field'] = $checkbox_value === 'yes' ? 'Wet Wash' : 'Dry Wash';

    return $variations;
}
```

```generic
<div class="woocommerce-variation-custom-text-field">
        {{{ data.variation.text_field }}}
    </div>

    <div class="woocommerce-variation-custom-number-field">
        {{{ data.variation.number_field }}}
    </div>

    <div class="woocommerce-variation-custom-textarea-field">
        {{{ data.variation.textarea_field }}}
    </div>

    <div class="woocommerce-variation-custom-select-field">
        {{{ data.variation.select_field }}}
    </div>

    <div class="woocommerce-variation-custom-checkbox-field">
        {{{ data.variation.checkbox_field }}}
    </div>
```