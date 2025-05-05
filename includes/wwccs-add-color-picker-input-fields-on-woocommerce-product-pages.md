# How to Add Color Picker Input Fields on WooCommerce Product Pages?

```generic
// Add color picker slider above "Add to Cart" button on WooCommerce product pages
function wwccs_add_color_picker_to_product_page() {
    // Check if we are on a WooCommerce product page
    if (is_product()) {
        // Output HTML for color picker slider above "Add to Cart" button
        ?>
        <div class="color-picker-container">
            <label for="color-picker">Select Color:</label>
            <input type="color" id="color-picker" name="color-picker" value="#ff0000"> <!-- Set default color value -->
            <input type="hidden" id="selected-color" name="selected_color" value="#ff0000"> <!-- Set default color value -->
        </div>
<br>
        <script>
            // JavaScript to update hidden input field with selected color
            jQuery(document).ready(function($) {
                // Update selected color when color picker value changes
                $('#color-picker').on('change', function() {
                    var selectedColor = $(this).val();
                    $('#selected-color').val(selectedColor);
                });
            });
        </script>
        <?php
    }
}
add_action( 'woocommerce_before_add_to_cart_button', 'wwccs_add_color_picker_to_product_page' );

// Display selected color in cart
function wwccs_display_selected_color_in_cart( $item_data, $cart_item ) {
    if ( ! empty( $cart_item['selected_color'] ) ) {
        $color_html = '<div style="width: 20px; height: 20px; background-color: ' . esc_html( $cart_item['selected_color'] ) . ';"></div>';
        $item_data[] = array(
            'key'     => __( 'Selected Color', 'woocommerce' ),
            'value'   => $color_html,
            'display' => '',
        );
    }
    return $item_data;
}
add_filter( 'woocommerce_get_item_data', 'wwccs_display_selected_color_in_cart', 10, 2 );

// Display selected color in order
function wwccs_display_selected_color_in_order( $cart_item, $order_item ) {
    if ( ! empty( $cart_item['selected_color'] ) ) {
        $color_html = '<div style="width: 20px; height: 20px; background-color: ' . esc_html( $cart_item['selected_color'] ) . ';"></div>';
        $order_item->add_meta_data( __( 'Selected Color', 'woocommerce' ), $color_html );
    }
}
add_action( 'woocommerce_checkout_create_order_line_item', 'wwccs_display_selected_color_in_order', 10, 2 );
```