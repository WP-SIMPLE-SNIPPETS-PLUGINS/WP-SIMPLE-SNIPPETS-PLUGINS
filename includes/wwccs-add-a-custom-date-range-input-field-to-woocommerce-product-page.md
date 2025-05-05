# How to Add a Custom Date Range Input Field To WooCommerce Product Page?

```generic
// Add custom date range to product page
add_action( 'woocommerce_before_add_to_cart_button', 'wwccs_display_custom_date_range_picker', 9 );
function wwccs_display_custom_date_range_picker() {
    echo '<div class="custom-date-range-picker">';
    // Custom Date From Field
    woocommerce_form_field( 'custom_date_from', array(
        'type'         => 'date',
        'required'     => false, // Change to true if field is required
        'label'        => 'Custom Date From',
        'placeholder'  => 'Select custom date...',
        'class'        => array('custom-date-picker-class'), // Add custom CSS class if needed
        'autocomplete' => 'off', // Disable browser autocomplete
    ));
    // Custom Date To Field
    woocommerce_form_field( 'custom_date_to', array(
        'type'         => 'date',
        'required'     => false, // Change to true if field is required
        'label'        => 'Custom Date To',
        'placeholder'  => 'Select custom date...',
        'class'        => array('custom-date-picker-class'), // Add custom CSS class if needed
        'autocomplete' => 'off', // Disable browser autocomplete
    ));
    echo '</div>';
}
add_action( 'woocommerce_add_to_cart_validation', 'wwccs_validate_custom_date_fields', 10, 3 );
function wwccs_validate_custom_date_fields( $passed, $product_id, $quantity ) {
    if ( empty( $_POST['custom_date_from'] ) || empty( $_POST['custom_date_to'] ) ) {
        wc_add_notice( __( 'Both Custom Date fields are required.', 'woocommerce' ), 'error' );
        return false;
    }
    return $passed;
}

// Save Custom Product Fields values
add_filter( 'woocommerce_add_cart_item_data', 'wwccs_save_custom_product_fields_values', 10, 2 );
function wwccs_save_custom_product_fields_values( $cart_item_data, $product_id ) {
    if ( isset( $_POST['custom_date_from'] ) && isset( $_POST['custom_date_to'] ) ) {
        $cart_item_data['custom_date_from'] = sanitize_text_field( $_POST['custom_date_from'] );
        $cart_item_data['custom_date_to']   = sanitize_text_field( $_POST['custom_date_to'] );
    }
    return $cart_item_data;
}

// Display custom date range in cart and checkout
add_filter( 'woocommerce_get_item_data', 'wwccs_display_custom_date_range_in_cart_checkout', 10, 2 );
function wwccs_display_custom_date_range_in_cart_checkout( $cart_data, $cart_item ) {
    if ( isset( $cart_item['custom_date_from'] ) && isset( $cart_item['custom_date_to'] ) ) {
        $cart_data[] = array(
            'key'   => 'Custom Date Range',
            'value' => sprintf( 'From %s to %s', $cart_item['custom_date_from'], $cart_item['custom_date_to'] ),
        );
    }
    return $cart_data;
}

// Save the custom date range to the order items
add_action( 'woocommerce_checkout_create_order_line_item', 'wwccs_save_custom_date_range_to_order_items', 10, 4 );
function wwccs_save_custom_date_range_to_order_items( $item, $cart_item_key, $values, $order ) {
    $custom_date_from = isset( $values['custom_date_from'] ) ? $values['custom_date_from'] : '';
    $custom_date_to   = isset( $values['custom_date_to'] ) ? $values['custom_date_to'] : '';

    if ( ! empty( $custom_date_from ) && ! empty( $custom_date_to ) ) {
        $item->add_meta_data( 'Custom Date Range', sprintf( 'From %s to %s', $custom_date_from, $custom_date_to ) );
    }
}

// Display custom date range in order details page
add_action( 'woocommerce_order_item_meta_end', 'wwccs_display_custom_date_range_in_order_details', 10, 3 );
function wwccs_display_custom_date_range_in_order_details( $item_id, $item, $order ) {
    $custom_date_range = $item->get_meta( 'Custom Date Range', true );
    if ( ! empty( $custom_date_range ) ) {
        echo '<br><small>Custom Date Range: ' . $custom_date_range . '</small>';
    }
}

// Display custom date range in admin order items table
add_filter( 'woocommerce_order_item_name', 'wwccs_display_custom_date_range_in_admin_order_items_table', 10, 2 );
function wwccs_display_custom_date_range_in_admin_order_items_table( $item_name, $item ) {
    $custom_date_range = $item->get_meta( 'Custom Date Range', true );
    if ( ! empty( $custom_date_range ) ) {
        $item_name .= '<br><small>Custom Date Range: ' . $custom_date_range . '</small>';
    }
    return $item_name;
}
```

```generic
// Enqueue jQuery UI Datepicker
add_action('wp_enqueue_scripts', 'wwccs_enqueue_jquery_ui');
function wwccs_enqueue_jquery_ui() {
    if (is_product()) {
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('jquery-ui-style', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css');
    }
}

// Add Custom Date Fields to WooCommerce Product Page
add_action('woocommerce_before_add_to_cart_button', 'wwccs_display_custom_date_picker', 9);
function wwccs_display_custom_date_picker() {
    global $product;

    // Retrieve "Booking Deadline (Days)" from product attributes
    $attributes = $product->get_attributes();
    $booking_deadline_days = isset($attributes['booking_deadline_days']) ? intval($attributes['booking_deadline_days']) : 0;

    // Hidden input to store Booking Deadline Days
    echo '<input type="hidden" id="booking-deadline-days" value="' . esc_attr($booking_deadline_days) . '">';

    echo '<div class="custom-date-picker-container" style="max-width: 300px;">';

    // Custom Date From Field
    woocommerce_form_field('custom_date_from', array(
        'type'        => 'text',
        'required'    => true,
        'label'       => 'Custom Date From',
        'placeholder' => 'mm/dd/yyyy',
        'class'       => array('custom-date-picker'),
        'autocomplete' => 'off',
    ));

    // Custom Date To Field
    woocommerce_form_field('custom_date_to', array(
        'type'        => 'text',
        'required'    => true,
        'label'       => 'Custom Date To',
        'placeholder' => 'mm/dd/yyyy',
        'class'       => array('custom-date-picker'),
        'autocomplete' => 'off',
    ));

    echo '</div>';
}
// Save Custom Date Fields to Cart Item Data
add_filter('woocommerce_add_cart_item_data', 'wwccs_save_custom_dates_to_cart', 10, 2);
function wwccs_save_custom_dates_to_cart($cart_item_data, $product_id) {
    if (isset($_POST['custom_date_from']) && isset($_POST['custom_date_to'])) {
        $cart_item_data['custom_date_from'] = sanitize_text_field($_POST['custom_date_from']);
        $cart_item_data['custom_date_to'] = sanitize_text_field($_POST['custom_date_to']);
    }
    return $cart_item_data;
}

// Display Custom Date Fields in Cart
add_filter('woocommerce_get_item_data', 'wwccs_display_custom_dates_in_cart', 10, 2);
function wwccs_display_custom_dates_in_cart($item_data, $cart_item) {
    if (!empty($cart_item['custom_date_from']) && !empty($cart_item['custom_date_to'])) {
        $item_data[] = array(
            'name' => 'Booking Dates',
            'value' => esc_html($cart_item['custom_date_from'] . ' - ' . $cart_item['custom_date_to'])
        );
    }
    return $item_data;
}

// Dynamic Date Blocking 
add_action('wp_footer', 'wwccs_enqueue_datepicker_script');
function wwccs_enqueue_datepicker_script() {
    if (is_product()) {
        ?>
        <script>
            jQuery(document).ready(function($) {
                if (typeof $.fn.datepicker !== "function") {
                    console.log("Error: jQuery UI Datepicker not loaded.");
                    return;
                }

                function updateDatepicker() {
                    var bookingDeadlineDays = parseInt($('#booking-deadline-days').val()) || 0;
                    var minDate = new Date();
                    minDate.setDate(minDate.getDate() + bookingDeadlineDays);

                    // Reinitialize the datepickers with updated minDate
                    $('#custom_date_from, #custom_date_to').datepicker("destroy").datepicker({
                        dateFormat: 'mm/dd/yy',
                        minDate: minDate,
                        changeMonth: true,
                        changeYear: true,
                        showAnim: "fadeIn"
                    }).css("width", "100%").attr('readonly', true);
                }

                // Run initially
                updateDatepicker();

                // Listen for Booking Deadline Days change (if applicable)
                $('#booking-deadline-days').on('change', function() {
                    updateDatepicker();
                });

                // Prevent manual input
                $('#custom_date_from, #custom_date_to').on('keydown', function(e) {
                    e.preventDefault();
                    return false;
                });
            });
        </script>
        <?php
    }
}
```