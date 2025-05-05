# How to Add Color Swatches as Product Input Fields to WooCommerce Product Page?

```generic
add_action( 'woocommerce_before_add_to_cart_button', 'wwccs_display_color_swatches', 9 );
function wwccs_display_color_swatches() {
global $product;
$product_id = $product->get_id(); // Get the product ID
// Check if the product ID matches the desired ID (759 in this case)
if ( $product_id === 759 ) {
echo '<div class="color-swatches">';
echo '<label class="color-swatch" style="background-color:#ffcccc; width: 60px; height: 60px; margin-right: 20px; display: inline-block; position: relative; text-align: center;">';
echo '<input type="radio" name="color" value="#ffcccc" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); cursor: pointer;">';
echo '</label>';
echo '<label class="color-swatch" style="background-color:#ccccff; width: 60px; height: 60px; margin-right: 20px; display: inline-block; position: relative; text-align: center;">';
echo '<input type="radio" name="color" value="#ccccff" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); cursor: pointer;">';
echo '</label>';
echo '<label class="color-swatch" style="background-color:#ccffcc; width: 60px; height: 60px; margin-right: 20px; display: inline-block; position: relative; text-align: center;">';
echo '<input type="radio" name="color" value="#ccffcc" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); cursor: pointer;">';
echo '</label>';
echo '</div>';
}
}

// Add selected color swatch to cart item data for a specific product
add_filter( 'woocommerce_add_cart_item_data', 'wwccs_add_color_to_cart', 10, 2 );
function wwccs_add_color_to_cart( $cart_item_data, $product_id ) {
// Check if the product ID matches the desired ID (759 in this case)
if ( $product_id === 759 && isset( $_POST['color'] ) && ! empty( $_POST['color'] ) ) {
$cart_item_data['color'] = sanitize_text_field( $_POST['color'] );
}
return $cart_item_data;
}

// Display selected color swatch in cart and checkout for a specific product
add_filter( 'woocommerce_get_item_data', 'wwccs_display_color_in_cart', 10, 2 );
function wwccs_display_color_in_cart( $cart_data, $cart_item ) {
// Check if the product ID matches the desired ID (759 in this case)
if ( isset( $cart_item['color'] ) && $cart_item['product_id'] === 759 ) {
$cart_data[] = array(
'name' => 'Color',
'value' => '<div class="color-swatch" style="background-color:' . esc_attr($cart_item['color']) . '; width: 80px; height: 80px; display: inline-block; margin-right: 10px;"></div>',
);
}
return $cart_data;
}

// Save the selected color to the order items for a specific product
add_action( 'woocommerce_checkout_create_order_line_item', 'wwccs_save_color_to_order_items', 10, 4 );
function wwccs_save_color_to_order_items( $item, $cart_item_key, $values, $order ) {
// Check if the product ID matches the desired ID (759 in this case)
if ( isset( $values['color'] ) && $values['product_id'] === 759 ) {
$item->add_meta_data( 'Color', $values['color'], true );
}
}

// Display selected color in admin order items table for a specific product
add_filter( 'woocommerce_order_item_name', 'wwccs_color_display_in_admin_order_items_table', 10, 2 );
function wwccs_color_display_in_admin_order_items_table( $item_name, $item ) {
// Check if the item has a selected color associated with it
$product_id = $item->get_product_id();
if ( $product_id === 759 && $color = $item->get_meta( 'Color' ) ) {
// Append the selected color to the item name
$item_name .= '<br><small>' . esc_html__( 'Color:', 'your-textdomain' ) . ' <div class="color-swatch" style="background-color:' . $color . '; width: 20px; height: 20px; display: inline-block;"></div></small>';
}
return $item_name;
}
```