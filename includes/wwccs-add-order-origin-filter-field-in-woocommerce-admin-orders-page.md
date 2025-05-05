# How to Add Order Origin Filter Field in WooCommerce >Admin Orders Page?

```generic
// Add a custom dropdown filter to filter orders by UTM Source (HPOS)
function wwccs_hpos_custom_utm_source_dropdown_filter() {
    // Define the UTM source values you want to filter by. Match these values with your meta data.
    $utm_sources = array(
        'direct' => 'Direct',
        'unknown' => 'Unknown',
        'linkedin' => 'LinkedIn',
        'quora' => 'Quora',
        'referral' => 'Referral'
        // Add more UTM sources here if needed
    );

    echo '<select name="utm_source_filter" class="postform">';
    echo '<option value="">Filter by Source</option>'; // Default "Filter by" option

    $current_filter = isset($_GET['utm_source_filter']) ? sanitize_text_field($_GET['utm_source_filter']) : '';

    foreach ($utm_sources as $utm_key => $utm_label) {
        $selected = $current_filter === $utm_key ? 'selected' : '';
        echo '<option value="' . esc_attr($utm_key) . '" ' . $selected . '>' . esc_html($utm_label) . '</option>';
    }

    echo '</select>';
}
add_action('woocommerce_order_list_table_restrict_manage_orders', 'wwccs_hpos_custom_utm_source_dropdown_filter');

// Filter orders based on selected UTM Source (HPOS)
function wwccs_hpos_filter_orders_by_utm_source($query_args) {
    if (isset($_GET['utm_source_filter']) && !empty($_GET['utm_source_filter'])) {
        $selected_utm_source = sanitize_text_field($_GET['utm_source_filter']);
        
        // Initialize the meta query if not set
        $meta_query = isset($query_args['meta_query']) ? $query_args['meta_query'] : array();
        
        if ($selected_utm_source === 'unknown') {
            // Handle the case for 'unknown' UTM source
            $meta_query[] = array(
                'relation' => 'OR',
                array(
                    'key' => '_wc_order_attribution_utm_source',
                    'compare' => 'NOT EXISTS' // No value set for UTM Source (unknown)
                ),
                array(
                    'key' => '_wc_order_attribution_utm_source',
                    'value' => '', // Empty UTM Source value (unknown)
                    'compare' => '='
                )
            );
        } else {
            $meta_query[] = array(
                'key' => '_wc_order_attribution_utm_source',
                'value' => $selected_utm_source,
                'compare' => 'LIKE' // Use 'LIKE' to handle variations
            );
        }

        // Set the modified meta query
        $query_args['meta_query'] = $meta_query;
    }
    
    return $query_args;
}
add_filter('woocommerce_order_query_args', 'wwccs_hpos_filter_orders_by_utm_source');
```