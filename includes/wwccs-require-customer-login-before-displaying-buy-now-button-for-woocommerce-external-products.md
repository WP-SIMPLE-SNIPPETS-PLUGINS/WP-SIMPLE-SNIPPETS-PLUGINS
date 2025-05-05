# How to Require Customer Login Before Displaying 'Buy Now' Button for WooCommerce External Products?

```generic
// Remove the default WooCommerce external product Buy Product button on the individual Product page.
remove_action('woocommerce_external_add_to_cart', 'woocommerce_external_add_to_cart', 30);

// Remove external product button for non-logged-in users
add_action('woocommerce_single_product_summary', 'wwccs_custom_external_button', 35); 

function wwccs_custom_external_button() {
    global $product;

    // Check if it's an external product
    if ($product->is_type('external')) {
        if (!is_user_logged_in()) {
            // Display login prompt in place of the Buy Now button
            $redirect_url = esc_url(get_permalink());
            $login_url = esc_url(wc_get_page_permalink('myaccount') . '?redirect_to=' . urlencode($redirect_url));
            echo '<p class="login-to-purchase">Please <a href="' . $login_url . '">log in</a> to purchase this product.</p>';
        } else {
            // Display the Buy Now button for logged-in users
            $product_url = esc_url($product->add_to_cart_url());
            $button_text = esc_html($product->single_add_to_cart_text()); // Fixed line

            echo '<p class="cart">
                    <a href="' . $product_url . '" rel="nofollow" class="single_add_to_cart_button button alt" target="_blank">' . $button_text . '</a>
                  </p>';
        }
    }
}

// This will take care of the Buy Product button below the external product on the Shop page.
add_filter('woocommerce_loop_add_to_cart_link', 'wwccs_external_add_product_link', 10, 2);

function wwccs_external_add_product_link($link) {
    global $product;

    if ($product->is_type('external')) {
        $link = sprintf(
            '<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s" target="_blank">%s</a>',
            esc_url($product->add_to_cart_url()),
            esc_attr(1), // Assuming quantity is always 1
            esc_attr($product->id),
            esc_attr($product->get_sku()),
            esc_attr('button product_type_external'), // Adjusted for clarity
            esc_html($product->add_to_cart_text())
        );
    }
    return $link;
}
```