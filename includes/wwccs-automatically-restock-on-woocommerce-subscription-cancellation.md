# How to Automatically Restock on WooCommerce Subscription Cancellation?

```generic
add_action('woocommerce_subscription_status_cancelled', 'wwccs_adjust_inventory_on_subscription_cancel', 10, 1);

function wwccs_adjust_inventory_on_subscription_cancel($subscription) {
    if (!$subscription) return;

    foreach ($subscription->get_items() as $item) {
        $product = $item->get_product();

        if ($product && $product->managing_stock()) {
            $current_stock = $product->get_stock_quantity();
            $item_quantity = $item->get_quantity();

            // Increase the stock by the quantity in the canceled subscription
            $new_stock = $current_stock + $item_quantity;

            $product->set_stock_quantity($new_stock);
            $product->save();
        }
    }
}
```