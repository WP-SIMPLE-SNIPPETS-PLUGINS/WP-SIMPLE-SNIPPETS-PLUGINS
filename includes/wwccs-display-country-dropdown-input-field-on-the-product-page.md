# How to Display Country Dropdown Input Field on the Product Page?

```generic
// Add custom date range to product page
add_action( 'woocommerce_before_add_to_cart_button', 'display_country_dropdown_on_product_page', 9 );
function display_country_dropdown_on_product_page() {
    $countries = WC()->countries->get_countries();
    echo '<div class="custom-country-dropdown">';
    woocommerce_form_field( 'custom_country', array(
        'type'          => 'select',
        'required'      => true, // Change to false if the field is not required
        'label'         => 'Select Country',
        'placeholder'   => 'Choose a country...',
        'options'       => $countries,
        'class'         => array('custom-country-dropdown-class'), // Add custom CSS class if needed
    ));
    echo '</div>';
}

// Save selected country to cart item data
add_filter( 'woocommerce_add_cart_item_data', 'save_country_to_cart_item_data', 10, 2 );
function save_country_to_cart_item_data( $cart_item_data, $product_id ) {
    if ( isset( $_POST['custom_country'] ) ) {
        $cart_item_data['custom_country'] = sanitize_text_field( $_POST['custom_country'] );
    }
    return $cart_item_data;
}

// Display selected country in cart and checkout pages
add_filter( 'woocommerce_get_item_data', 'display_country_in_cart_and_checkout', 10, 2 );
function display_country_in_cart_and_checkout( $item_data, $cart_item ) {
    if ( isset( $cart_item['custom_country'] ) ) {
        $item_data[] = array(
            'key'   => 'Selected Country',
            'value' => sanitize_text_field( $cart_item['custom_country'] ),
        );
    }
    return $item_data;
}

// Save selected country to order items
add_action( 'woocommerce_checkout_create_order_line_item', 'save_country_to_order_items', 10, 4 );
function save_country_to_order_items( $item, $cart_item_key, $values, $order ) {
    if ( isset( $values['custom_country'] ) ) {
        $item->add_meta_data( 'Selected Country', $values['custom_country'], true );
    }
}

// Display selected country in admin order items table
add_filter( 'woocommerce_order_item_name', 'display_country_in_admin_order_items_table', 10, 2 );
function display_country_in_admin_order_items_table( $item_name, $item ) {
    if ( $custom_country = $item->get_meta( 'Selected Country' ) ) {
        $item_name .= '<br><small>' . esc_html__( 'Selected Country:', 'your-textdomain' ) . ' ' . esc_html( $custom_country ) . '</small>';
    }
    return $item_name;
}
```