# How to Automatically Update Order Status Based on Set Day and Time in WooCommerce?

```generic
add_action('init', 'wwccs_orders');

function wwccs_orders() {
    // Define your target day and time
    $target_day = 'Fri'; // Friday
    $target_time = '9:00 AM'; // Time in 12-hour format

    // Get current day and time in WordPress timezone
    $current_day = date('D', current_time('timestamp'));
    $current_time = date('g:i A', current_time('timestamp'));

    // Check if it's the target day and time
    if ($current_day === $target_day && $current_time === $target_time) {
        global $wpdb;

        // Query to get all orders with 'wc-processing' status
        $my_query = "SELECT * FROM {$wpdb->prefix}wc_order_stats WHERE status='wc-processing'";
        $results = $wpdb->get_results($my_query);

        foreach ($results as $result) {
            $order_id = $result->order_id;
            $order = new WC_Order($order_id);
            if (!empty($order)) {
                $order->update_status('completed');
            }
        }
    }
}
```