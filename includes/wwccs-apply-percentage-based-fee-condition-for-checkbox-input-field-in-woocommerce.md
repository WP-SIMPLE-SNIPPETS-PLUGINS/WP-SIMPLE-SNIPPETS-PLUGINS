# How to Apply Percentage-Based Fee for Checkbox Input Field in WooCommerce?

```generic
// Display custom checkbox field and calculate subtotal and total on product page
add_action( 'woocommerce_before_add_to_cart_button', 'wwccs_display_logo_checkbox_field', 9 );
function wwccs_display_logo_checkbox_field() {
    echo '<div class="custom-checkbox-field">';
    woocommerce_form_field( 'wwccs_choose_logo_checkbox', array(
        'type'  => 'checkbox',
        'label' => 'Choose Logo',
    ));
    echo '</div>';

    // Add jQuery script for dynamic calculation
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // Calculate subtotal and total based on checkbox selection
        $('#wwccs_choose_logo_checkbox').change(function() {
            var productPrice = parseFloat(<?php echo wc_get_product()->get_price(); ?>);
            var logoPrice = $(this).is(':checked') ? productPrice * 0.15 : 0; // Set to 15% of product price if checked, otherwise 0
            var total = productPrice + logoPrice;

            // Update subtotal and total values
            $('#subtotal').text('$' + productPrice.toFixed(2));
            $('#logo-price').text('+$' + logoPrice.toFixed(2));
            $('#total').text('$' + total.toFixed(2));

            // Store the total price in a hidden field for cart handling
            $('#wwccs_total_price').val(total.toFixed(2));
        });

        // Initialize the total price on page load
        var initialPrice = parseFloat(<?php echo wc_get_product()->get_price(); ?>);
        $('#wwccs_total_price').val(initialPrice.toFixed(2));
    });
    </script>
    <input type="hidden" id="wwccs_total_price" name="wwccs_total_price" value="">
    <?php
}

// Display subtotal, logo price, and total above Add to Cart button in a table
add_action( 'woocommerce_before_add_to_cart_button', 'wwccs_display_subtotal_logo_and_total', 10 );
function wwccs_display_subtotal_logo_and_total() {
    $product_price = wc_get_product()->get_price();
    echo '<table style="margin-bottom: 10px; width: 100%; border-collapse: collapse;">';
    echo '<tr><td>Subtotal:</td><td id="subtotal">$' . number_format($product_price, 2) . '</td></tr>';
    echo '<tr><td>Logo (optional):</td><td id="logo-price">+$0.00</td></tr>'; // Initialize as $0.00
    echo '<tr><td>Total:</td><td id="total">$' . number_format($product_price, 2) . '</td></tr>';
    echo '</table>';
}

// Add custom total price to the cart item data
add_filter( 'woocommerce_add_cart_item_data', 'wwccs_add_custom_price_to_cart', 10, 2 );
function wwccs_add_custom_price_to_cart( $cart_item_data, $product_id ) {
    if ( isset( $_POST['wwccs_choose_logo_checkbox'] ) && $_POST['wwccs_choose_logo_checkbox'] ) {
        if ( isset( $_POST['wwccs_total_price'] ) ) {
            $cart_item_data['custom_total_price'] = (float) sanitize_text_field( $_POST['wwccs_total_price'] );
        }
    }
    return $cart_item_data;
}

// Set the custom price in the cart
add_action( 'woocommerce_before_calculate_totals', 'wwccs_set_custom_cart_total', 10 );
function wwccs_set_custom_cart_total( $cart ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        return;
    }

    // Loop through each cart item
    foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
        if ( isset( $cart_item['custom_total_price'] ) ) {
            // Set the price to the custom total price
            $cart_item['data']->set_price( $cart_item['custom_total_price'] );
        }
    }
}
```