# How to Add a File Upload Product Input Field to WooCommerce Product Page?

```generic
add_action('woocommerce_before_add_to_cart_button', 'wwccs_display_file_upload_field', 9);
function wwccs_display_file_upload_field() {
    global $product;
    $product_id = $product->get_id(); // Get the product ID
    
    // Check if the product ID matches the desired ID (759 in this case)
    if ($product_id === 759) {
        echo '<div class="file-upload">';
        echo '<input type="file" name="file_upload" accept=".jpg, .jpeg, .png">';
        echo '</div>';
    }
}

// Add selected file upload data to cart item data for a specific product
add_filter('woocommerce_add_cart_item_data', 'wwccs_add_file_upload_to_cart', 10, 2);
function wwccs_add_file_upload_to_cart($cart_item_data, $product_id) {
    // Check if the product ID matches the desired ID (759 in this case)
    if ($product_id === 759 && isset($_FILES['file_upload']) && !empty($_FILES['file_upload']['name'])) {
        $upload_dir = wp_upload_dir(); // Get the WordPress upload directory
        $file_name = $_FILES['file_upload']['name'];
        $file_tmp = $_FILES['file_upload']['tmp_name'];

        // Move the uploaded file to the upload directory
        if (move_uploaded_file($file_tmp, $upload_dir['path'] . '/' . $file_name)) {
            // Add file path to cart item data
            $cart_item_data['file_upload'] = $upload_dir['url'] . '/' . $file_name;
        } else {
            // Handle file upload error
            wc_add_notice('Failed to upload file.', 'error');
        }
    }
    return $cart_item_data;
}

// Save the selected file upload to the order items for a specific product
add_action('woocommerce_checkout_create_order_line_item', 'wwccs_save_file_upload_to_order_items', 10, 4);
function wwccs_save_file_upload_to_order_items($item, $cart_item_key, $values, $order) {
    // Check if the product ID matches the desired ID (759 in this case)
    if (isset($values['file_upload']) && $values['product_id'] === 759) {
        // Get file URL from the cart item data
        $file_url = $values['file_upload'];
        $file_name = basename($file_url);

        // Generate download link with download attribute
        $download_link = '<a href="' . esc_url($file_url) . '" download="' . esc_attr($file_name) . '">' . esc_html($file_name) . '</a>';

        // Save file name with download link to order item meta data
        $item->add_meta_data('Uploaded File', $download_link, true);
    }
}

// Display the download link in the order details (frontend and admin)
add_action('woocommerce_order_item_meta_end', 'display_download_link_order_item_meta', 10, 3);
function display_download_link_order_item_meta($item_id, $item, $order) {
    // Check if the order item has meta data for the uploaded file
    $file_upload_meta = $item->get_meta('Uploaded File');
    if ($file_upload_meta) {
        // Display the download link
        echo '<br>' . $file_upload_meta;
    }
}

// Add download link to admin order items table
add_action('woocommerce_admin_order_item_headers', 'wwccs_admin_order_item_headers');
function wwccs_admin_order_item_headers() {
    echo '<th class="download-file">Uploaded File</th>';
}

add_action('woocommerce_admin_order_item_values', 'wwccs_admin_order_item_values', 10, 3);
function wwccs_admin_order_item_values($product, $item, $item_id) {
    $file_upload_meta = $item->get_meta('Uploaded File');
    echo '<td>';
    if ($file_upload_meta) {
        echo $file_upload_meta;
    } else {
        echo '';
    }
    echo '</td>';
}
```