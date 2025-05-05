# How to Add a Custom Filter Field and Custom Column in WooCommerce Admin Orders Page?

```generic
// Add Style column to the Orders page in WooCommerce admin
function wwccs_add_style_column($columns) {
    $columns['style'] = __('Style', 'woocommerce');
    return $columns;
}
add_filter('manage_woocommerce_page_wc-orders_columns', 'wwccs_add_style_column');

// Display the Style value in the Style column
function wwccs_display_style_column($column, $post_id) {
    if ($column == 'style') {
        // Get the WooCommerce order object
        $order = wc_get_order($post_id);

        // Replace 'style' with the actual meta key used for the Style field
        $style_value = $order->get_meta('style');
        echo $style_value;
    }
}
add_action('manage_woocommerce_page_wc-orders_custom_column', 'wwccs_display_style_column', 10, 2);

// Add a custom dropdown for filtering by Style
function wwccs_add_style_filter($post_type) {
    if ('shop_order' === $post_type) {
        $domain = 'woocommerce';
        $styles = array(__('Modern', $domain), __('Rustic', $domain), __('Classic', $domain));

        $selected_value = isset($_GET['style_filter']) ? sanitize_title($_GET['style_filter']) : '';

        echo '<select name="style_filter">';
        echo '<option value="" ' . selected('', $selected_value, false) . '>Filter by Style</option>';

        foreach ($styles as $value) {
            echo '<option value="' . sanitize_title($value) . '" ' . selected(sanitize_title($value), $selected_value, false) . '>' . $value . '</option>';
        }

        echo '</select>';
    }
}
add_action('woocommerce_order_list_table_restrict_manage_orders', 'wwccs_add_style_filter');

// Filter orders by Style
function wwccs_filter_orders_by_style($vars) {
    if (isset($_GET['style_filter']) && !empty($_GET['style_filter'])) {
        $selected_style_value = sanitize_text_field($_GET['style_filter']);

        $vars['meta_query'][] = array(
            'key'     => 'style',
            'value'   => $selected_style_value,
           
        );
    }

    return $vars;
}
add_filter('woocommerce_order_list_table_prepare_items_query_args', 'wwccs_filter_orders_by_style');

// Add Style to the search fields for the Orders table
function custom_woocommerce_shop_order_search_fields($search_fields) {
    $search_fields[] = 'style';
    return $search_fields;
}
add_filter('woocommerce_order_table_search_query_meta_keys', 'custom_woocommerce_shop_order_search_fields');
```