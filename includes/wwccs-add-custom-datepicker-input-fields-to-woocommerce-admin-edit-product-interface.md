# How to Add Custom Datepicker Input Fields to WooCommerce Admin Edit Product Interface?

```generic
add_action( 'woocommerce_product_options_general_product_data', 'wwccs_add_admin_product_custom_general_fields' );
function wwccs_add_admin_product_custom_general_fields() {
    global $product_object;

    echo '<div class="options_group custom_dates_fields">
        <p class="form-field custom_date_from_field" style="display:block;">
            <label for="_custom_date_from">' . esc_html__( 'Custom date range', 'woocommerce' ) . '</label>
            ' . wc_help_tip( __("This is a description for that date range fields (in a help tip)…", "woocommerce") ) . '
            <input type="text" class="short" name="_custom_date_from" id="_custom_date_from" value="' . esc_attr( $product_object->get_meta('_custom_date_from') ) . '" placeholder="' . esc_html( _x( 'From&hellip;', 'placeholder', 'woocommerce' ) ) . ' YYYY-MM-DD" maxlength="10" pattern="' . esc_attr( apply_filters( 'woocommerce_date_input_html_pattern', '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])' ) ) . '" />
        </p>
        <p class="form-field custom_date_to_field" style="display:block;">
            <input type="text" class="short" name="_custom_date_to" id="_custom_date_to" value="' . esc_attr( $product_object->get_meta('_custom_date_to') ) . '" placeholder="' . esc_html( _x( 'To&hellip;', 'placeholder', 'woocommerce' ) ) . '  YYYY-MM-DD" maxlength="10" pattern="' . esc_attr( apply_filters( 'woocommerce_date_input_html_pattern', '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])' ) ) . '" />
    </div>';

    ?>
    <script>
    jQuery( function($){
        $( '.custom_dates_fields' ).each( function() {
            $( this ).find( 'input' ).datepicker({
                defaultDate: '',
                dateFormat: 'yy-mm-dd',
                numberOfMonths: 1,
                showButtonPanel: true,
                onSelect: function() {
                    var datepicker = $( this );
                        option         = $( datepicker ).next().is( '.hasDatepicker' ) ? 'minDate' : 'maxDate',
                        otherDateField = 'minDate' === option ? $( datepicker ).next() : $( datepicker ).prev(),
                        date           = $( datepicker ).datepicker( 'getDate' );

                    $( otherDateField ).datepicker( 'option', option, date );
                    $( datepicker ).change();
                }
            });
            $( this ).find( 'input' ).each( function() { date_picker_select( $( this ) ); } );
        });
    })
    </script>
    <?php
}

// Save Custom Admin Product Fields values
add_action( 'woocommerce_admin_process_product_object', 'wwccs_save_admin_product_custom_general_fields_values' );
function wwccs_save_admin_product_custom_general_fields_values( $product ){
    if( isset($_POST['_custom_date_from']) && isset($_POST['_custom_date_to']) ) {
        $product->update_meta_data( '_custom_date_from', esc_attr($_POST['_custom_date_from']) );
        $product->update_meta_data( '_custom_date_to', esc_attr($_POST['_custom_date_to']) );
    }
}
// Add custom date range to product page
add_action( 'woocommerce_before_add_to_cart_button', 'wwccs_display_custom_date_range', 10 );
function wwccs_display_custom_date_range() {
    // Retrieve the custom date range for the current product
    $custom_date_from = get_post_meta( get_the_ID(), '_custom_date_from', true );
    $custom_date_to = get_post_meta( get_the_ID(), '_custom_date_to', true );

    // Check if both dates are set
    if ( $custom_date_from && $custom_date_to ) {
        // Display the date range with custom styling
        echo '<div class="custom-date-range" style="font-weight: bold; color: green;">';
        echo '<p>Valid from: ' . esc_html( $custom_date_from ) . ' to ' . esc_html( $custom_date_to ) . '</p>';
        echo '</div>';
    }
}
```