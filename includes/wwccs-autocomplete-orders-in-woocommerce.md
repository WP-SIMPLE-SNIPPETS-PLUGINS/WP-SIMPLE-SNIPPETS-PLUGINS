# How to Autocomplete Orders in WooCommerce?

```generic
add_action('woocommerce_thankyou', 'wwccs_custom_woocommerce_auto_complete_order');

function wwccs_custom_woocommerce_auto_complete_order($order_id) {
    if (!$order_id) {
        return;
    }

    $order = wc_get_order($order_id);

    // Define the payment methods for which orders should be auto-completed
    $auto_complete_payment_methods = array('stripe', 'ppcp-gateway', 'bacs', 'cod', 'cheque'); // Include all necessary payment methods

    // Check if the order payment method is in the defined array
    if (in_array($order->get_payment_method(), $auto_complete_payment_methods)) {
        // Auto-complete if the order is currently in 'processing' or 'on-hold' status
        if ($order->get_status() === 'processing' || $order->get_status() === 'on-hold') {
            $order->update_status('completed');
        }
    }
}
```