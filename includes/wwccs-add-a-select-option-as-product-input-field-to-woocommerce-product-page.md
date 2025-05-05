# How To Add a Select Option as Product Input Field to WooCommerce Product Page?

```generic
add_action('woocommerce_before_add_to_cart_button', 'wwccs_action_woocommerce_before_add_to_cart_button', 9);
function wwccs_action_woocommerce_before_add_to_cart_button() {
    $domain = 'woocommerce';

    $args = array(
        'category' => array('accessories'), 
        'limit'    => -1,
    );

    // Retrieving products
    $product_array = wc_get_products($args);

    // Initialize options array
    $options = array( 0 => __( 'Choose an option', $domain ) );

    // Not empty
    if (!empty($product_array)) {
        foreach ($product_array as $prod) {
            $product_id = $prod->get_id();
            $options[$product_id] = $prod->get_name();
        }

        // Add select field
        woocommerce_form_field('accessory_options', array(
            'type'     => 'select',
            'label'    => __('Choose an Accessory', $domain),
            'required' => false,
            'options'  => $options,
        ));
    }
}

// Add custom selected accessory to cart item data
add_filter('woocommerce_add_cart_item_data', 'wwccs_add_accessory_option_to_cart', 10, 2);
function wwccs_add_accessory_option_to_cart($cart_item_data, $product_id) {
    if (isset($_POST['accessory_options']) && !empty($_POST['accessory_options'])) {
        $cart_item_data['accessory_options'] = sanitize_text_field($_POST['accessory_options']);
    }
    return $cart_item_data;
}

// Add the selected accessory product to the cart
add_action('woocommerce_add_to_cart', 'wwccs_add_selected_accessory_to_cart', 10, 6);
function wwccs_add_selected_accessory_to_cart($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data) {
    if (isset($cart_item_data['accessory_options']) && !empty($cart_item_data['accessory_options'])) {
        $accessory_product_id = $cart_item_data['accessory_options'];

        // Check if accessory product is already in the cart
        $accessory_in_cart = false;
        foreach (WC()->cart->get_cart() as $cart_item) {
            if ($cart_item['product_id'] == $accessory_product_id) {
                $accessory_in_cart = true;
                break;
            }
        }

        // If accessory product is not in the cart, add it
        if (!$accessory_in_cart) {
            WC()->cart->add_to_cart($accessory_product_id);
        }
    }
}

// Display the selected accessory in the cart and checkout
add_filter('woocommerce_get_item_data', 'wwccs_display_accessory_option_in_cart', 10, 2);
function wwccs_display_accessory_option_in_cart($item_data, $cart_item) {
    if (isset($cart_item['accessory_options'])) {
        $accessory_product = wc_get_product($cart_item['accessory_options']);
        if ($accessory_product) {
            $item_data[] = array(
                'name'  => __('Accessory', 'woocommerce'),
                'value' => $accessory_product->get_name(),
            );
        }
    }
    return $item_data;
}

// Save the custom accessory field value to the order items
add_action('woocommerce_checkout_create_order_line_item', 'wwccs_save_accessory_option_to_order_items', 10, 4);
function wwccs_save_accessory_option_to_order_items($item, $cart_item_key, $values, $order) {
    if (isset($values['accessory_options'])) {
        $item->add_meta_data('Accessory', $values['accessory_options'], true);
    }
}

// Display custom accessory in admin order items table
add_filter('woocommerce_order_item_name', 'wwccs_display_accessory_option_in_admin_order_items_table', 10, 2);
function wwccs_display_accessory_option_in_admin_order_items_table($item_name, $item) {
    if ($accessory = $item->get_meta('Accessory')) {
        $accessory_product = wc_get_product($accessory);
        if ($accessory_product) {
            $item_name .= '<br><small>' . __('Accessory:', 'woocommerce') . ' ' . esc_html($accessory_product->get_name()) . '</small>';
        }
    }
    return $item_name;
}
```