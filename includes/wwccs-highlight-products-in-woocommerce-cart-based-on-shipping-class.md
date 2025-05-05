# How to Highlight Products in WooCommerce Cart Based on Shipping Class?

```generic
// Add a custom class to cart items that need highlighting
add_filter('woocommerce_cart_item_class', 'wwccs_add_custom_class_to_cart_item_by_shipping_class', 10, 3);
function wwccs_add_custom_class_to_cart_item_by_shipping_class($class, $cart_item, $cart_item_key) {
    // Get the product object
    $product = $cart_item['data'];
    
    // Get the shipping class term slug
    $shipping_class_slug = get_term_by('id', $product->get_shipping_class_id(), 'product_shipping_class')->slug;
    
    // Define the specific shipping class slug
    $restricted_shipping_class_slug = 'heavy-items';
    
    // Check if the shipping class matches 'heavy-items'
    if ($shipping_class_slug === $restricted_shipping_class_slug) {
        $class .= ' custom-highlight'; // Add a custom class
    }
    
    return $class;
}

// Add custom CSS for the cart items with the custom-highlight class
add_action('wp_head', 'wwccs_add_custom_css_for_cart_highlight');
function wwccs_add_custom_css_for_cart_highlight() {
    if (is_cart()) {
        echo '<style>
            .woocommerce-cart-form table tbody tr.custom-highlight td {
                background-color: #ffcccc; /* Light red color */
            }
            .cart-error-message {
                margin: 20px 0;
                padding: 10px;
                background-color: #ffe6e6; /* Light red background */
                color: #d8000c; /* Dark red text */
                border: 1px solid #d8000c;
                border-radius: 5px;
                font-size: 16px;
            }
        </style>';
    }
}

// Display a combined notice message if there are restricted products
add_action('woocommerce_before_cart', 'wwccs_display_combined_error_message');
function wwccs_display_combined_error_message() {
    // Define the specific shipping class slug
    $restricted_shipping_class_slug = 'heavy-items';
    
    // Initialize array to store product names
    $restricted_products = array();
    
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        // Get the product object
        $product = $cart_item['data'];
        
        // Get the shipping class term slug
        $shipping_class_slug = get_term_by('id', $product->get_shipping_class_id(), 'product_shipping_class')->slug;
        
        // Check if the product belongs to the restricted shipping class
        if ($shipping_class_slug === $restricted_shipping_class_slug) {
            // Add product name to the array for the notice
            $restricted_products[] = get_the_title($product->get_id());
        }
    }
    
    // Display a combined notice message if there are restricted products
    if (!empty($restricted_products)) {
        $producwwccs_list = implode(', ', $restricted_products);
        echo '<div class="cart-error-message">';
        echo sprintf(
            'The following products cannot be shipped due to shipping restrictions: %s.',
            $producwwccs_list
        );
        echo '</div>';
    }
}
```