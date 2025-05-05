# How to Refund Multiple Orders (via Bulk Actions) in WooCommerce (Compatible with HPOS Order Tables)?

```generic
// Add custom bulk action: Refund with Restock
$add_custom_bulk_actions = function ( array $bulk_actions ) {
    $bulk_actions['refund-with-restock'] = 'Refund with Restock';
    return $bulk_actions;
};

// Handle the custom bulk action: Refund with Restock
$custom_bulk_action_handler = function ( string $redirect_to, string $action, array $ids ) {
    if ( 'refund-with-restock' === $action ) {
        $changed = false;

        // Iterate over each selected order
        foreach ( $ids as $order_id ) {
            $order = wc_get_order( $order_id );

            if ( ! $order ) {
                continue;
            }

            // Process refund with restocking
            $refund = wc_create_refund( [
                'order_id'     => $order_id,
                'amount'       => $order->get_total(),
                'reason'       => 'Bulk Refund with Restock',
                'restock_items'=> true, // Restock the items
                'refund_method'=> 'refund' // This will apply the actual refund action
            ] );

            if ( $refund && is_a( $refund, 'WC_Order_Refund' ) ) {
                // Update the order status to 'refunded'
                $order->update_status( 'refunded' );
                $changed = true;

                // Custom restocking logic if "Refund with Restock" is selected
                foreach ( $order->get_items() as $item ) {
                    if ( $item->get_product_id() > 0 ) {
                        $product = $item->get_product();

                        if ( $product && $product->exists() && $product->managing_stock() ) {
                            $qty = $item->get_quantity(); // Get the item quantity
                            $product->increase_stock( $qty ); // Restock the quantity

                            do_action( 'woocommerce_auto_stock_restored', $product, $item ); // Optional custom action
                            
                            // Optionally, send stock notifications if necessary
                            $order->send_stock_notifications( $product, $product->get_stock_quantity(), $qty );
                        }
                    }
                }

                // Optionally add an order note for tracking
                $order->add_order_note( 'Stock levels increased for refunded items with restock.' );
            }
        }

        // Set redirect notice
        if ( $changed ) {
            $redirect_to = add_query_arg( 'bulk_action', 'refund-with-restock-notice', $redirect_to );
        }
    }

    return $redirect_to;
};

// Display a notice after the bulk action has been executed
$custom_bulk_action_notice = function () {
    if ( isset( $_GET['bulk_action'] ) ) {
        $action = sanitize_text_field( $_GET['bulk_action'] );
        if ( 'refund-with-restock-notice' === $action ) {
            echo '<div class="updated" style="border-left-color: #d7f"><p>Refund with Restock has been successfully applied to selected orders and stock levels have been adjusted.</p></div>';
        }
    }
};

// Register the custom bulk action
add_filter( 'bulk_actions-woocommerce_page_wc-orders', $add_custom_bulk_actions );

// Handle the custom bulk action
add_filter( 'handle_bulk_actions-woocommerce_page_wc-orders', $custom_bulk_action_handler, 10, 3 );

// Show a notice after the action is performed
add_action( 'admin_notices', $custom_bulk_action_notice );
```

```generic
// Updating label in admin order list bulk actions dropdown
function wwccs_update_custom_dropdown_bulk_actions_shop_order( $actions ) {

// Add default WooCommerce 'mark_refunded' action
$actions['mark_refunded'] = __( 'Refund without restocking', 'woocommerce' );

return $actions;
}
add_action('bulk_actions-edit-shop_order', 'wwccs_update_custom_dropdown_bulk_actions_shop_order');
add_filter( 'bulk_actions-woocommerce_page_wc-orders', 'wwccs_update_custom_dropdown_bulk_actions_shop_order', 20, 1 );
add_filter( 'handle_bulk_actions-woocommerce_page_wc-orders', 'wwccs_update_custom_dropdown_bulk_actions_shop_order', 20, 1 );
```

```generic
// Add custom bulk actions: Refund with Restock and Refund without Restock
$wwccs_add_custom_bulk_actions = function ( array $bulk_actions ) {
    return array_merge( $bulk_actions, [
        'refund-with-restock' => 'Refund with Restock',
        'refund-without-restock' => 'Refund without Restock',
    ] );
};

// Handle the custom bulk actions
$wwccs_custom_bulk_action_handler = function ( string $redirect_to, string $action, array $ids ) {
    if ( 'refund-with-restock' === $action || 'refund-without-restock' === $action ) {
        $changed = false;

        // Iterate over each selected order
        foreach ( $ids as $order_id ) {
            $order = wc_get_order( $order_id );

            if ( ! $order ) {
                continue;
            }

            // Process refund
            $refund = wc_create_refund( [
                'order_id'     => $order_id,
                'amount'       => $order->get_total(),
                'reason'       => 'Bulk Refund',
                'restock_items'=> $action === 'refund-with-restock', // Restock if Refund with Restock
                'refund_method'=> 'refund' // This will apply the actual refund action
            ] );

            if ( $refund && is_a( $refund, 'WC_Order_Refund' ) ) {
                // Update the order status to 'refunded'
                $order->update_status( 'refunded' );
                $changed = true;

                // Custom restocking logic if "Refund with Restock" is selected
                if ( 'refund-with-restock' === $action ) {
                    foreach ( $order->get_items() as $item ) {
                        if ( $item->get_product_id() > 0 ) {
                            $product = $item->get_product();

                            if ( $product && $product->exists() && $product->managing_stock() ) {
                                $qty = $item->get_quantity(); // Get the item quantity
                                $new_quantity = $product->increase_stock( $qty ); // Restock the quantity

                                do_action( 'woocommerce_auto_stock_restored', $product, $item ); // Optional custom action
                                
                                // Optionally, send stock notifications if necessary
                                $order->send_stock_notifications( $product, $new_quantity, $qty );
                            }
                        }
                    }
                    
                    // Optionally add an order note for tracking
                    $order->add_order_note( 'Stock levels increased for refunded items with restock.' );
                }
            }
        }

        // Set redirect notice
        if ( $changed ) {
            $redirect_to = add_query_arg( 'bulk_action', $action . '-notice', $redirect_to );
        }
    }

    return $redirect_to;
};

// Display a notice after the bulk action has been executed
$wwccs_custom_bulk_action_notice = function () {
    if ( isset( $_GET['bulk_action'] ) ) {
        $action = sanitize_text_field( $_GET['bulk_action'] );
        if ( 'refund-with-restock-notice' === $action ) {
            echo '<div class="updated" style="border-left-color: #d7f"><p>Refund with Restock has been successfully applied to selected orders and stock levels have been adjusted.</p></div>';
        } elseif ( 'refund-without-restock-notice' === $action ) {
            echo '<div class="updated" style="border-left-color: #d7f"><p>Refund without Restock has been successfully applied to selected orders.</p></div>';
        }
    }
};

// Register the custom bulk actions
add_filter( 'bulk_actions-woocommerce_page_wc-orders', $wwccs_add_custom_bulk_actions );

// Handle the custom bulk actions
add_filter( 'handle_bulk_actions-woocommerce_page_wc-orders', $wwccs_custom_bulk_action_handler, 10, 3 );

// Show a notice after the action is performed
add_action( 'admin_notices', $wwccs_custom_bulk_action_notice );
```

```generic
use function WooCommerce\PayPalCommerce\Api\ppcp_refund_order;

// Updating label in admin order list bulk actions dropdown
function wwccs_update_custom_dropdown_bulk_actions_shop_order( $actions ) {
    // Add default WooCommerce 'mark_refunded' action
    $actions['mark_refunded'] = __( 'Change status to Refunded', 'woocommerce' );
    return $actions;
}
add_action('bulk_actions-edit-shop_order', 'wwccs_update_custom_dropdown_bulk_actions_shop_order');
add_filter( 'bulk_actions-woocommerce_page_wc-orders', 'wwccs_update_custom_dropdown_bulk_actions_shop_order', 20, 1 );

// Handling the custom bulk action
function wwccs_handle_custom_bulk_actions_shop_order( $redirect_to, $doaction, $order_ids ) {
    if ( $doaction === 'mark_refunded' && ! empty( $order_ids ) ) {
        foreach ( $order_ids as $order_id ) {
            $order = wc_get_order( $order_id );
            if ( $order && $order->get_status() !== 'refunded' ) {
                // Update the order status to "refunded"
                $order->update_status( 'refunded' );
            }
        }
    }
    return $redirect_to;
}
add_filter( 'handle_bulk_actions-edit-shop_order', 'wwccs_handle_custom_bulk_actions_shop_order', 20, 3 );

// Hook to handle automatic refunds when status changes to "refunded"
add_action( 'woocommerce_order_status_changed', 'wwccs_auto_refund_on_status_change', 10, 4 );

function wwccs_auto_refund_on_status_change( $order_id, $old_status, $new_status, $order ) {
    if ( $new_status === 'refunded' ) {
        if ( $order->get_payment_method() === 'ppcp-gateway' ) {
            $refund_amount = $order->get_total();
            $refund_response = ppcp_refund_order( $order, $refund_amount );
           
        }
    }
}
```

```generic
// Function to process refunds for Stripe
function wwccs_custom_process_refund($order_id) {
    $order = wc_get_order($order_id);
    if (!$order) {
        return false;
    }

    $payment_method = $order->get_payment_method();
    $transaction_id = $order->get_transaction_id();

    if (!$transaction_id) {
        return false;
    }

    if ($payment_method === 'stripe') {
        // Load Stripe Gateway explicitly
        $gateways = WC()->payment_gateways->payment_gateways();
        if (!isset($gateways['stripe'])) {
            return false;
        }

        $payment_gateway = $gateways['stripe']; // Force load Stripe gateway

        if (!method_exists($payment_gateway, 'process_refund')) {
            return false;
        }

        $refund_result = $payment_gateway->process_refund($order_id, $order->get_total(), 'Refund via bulk action.');

        if (is_wp_error($refund_result)) {
            return false;
        }
        return true;
    }
    return false;
}

// Adding a custom bulk action to the WooCommerce order list
function wwccs_update_custom_dropdown_bulk_actions_shop_order($actions) {
    $actions['mark_refunded'] = __('Change status to Refunded', 'woocommerce');
    return $actions;
}
add_filter('bulk_actions-edit-shop_order', 'wwccs_update_custom_dropdown_bulk_actions_shop_order');
add_filter('bulk_actions-woocommerce_page_wc-orders', 'wwccs_update_custom_dropdown_bulk_actions_shop_order', 20, 1);

// Handling the bulk action
function wwccs_handle_custom_bulk_actions_shop_order($redirect_to, $doaction, $order_ids) {
    if ($doaction === 'mark_refunded' && !empty($order_ids)) {
        foreach ($order_ids as $order_id) {
            $order = wc_get_order($order_id);
            if ($order && $order->get_status() !== 'refunded') {
                $order->update_status('refunded'); // Update order status
            }
        }
    }
    return $redirect_to;
}
add_filter('handle_bulk_actions-edit-shop_order', 'wwccs_handle_custom_bulk_actions_shop_order', 20, 3);

// Hook to automatically trigger refund when order status changes to "refunded"
add_action('woocommerce_order_status_changed', 'wwccs_auto_refund_on_status_change', 10, 4);

function wwccs_auto_refund_on_status_change($order_id, $old_status, $new_status, $order) {
    if ($new_status === 'refunded') {
        if ($order->get_payment_method() === 'stripe') {
            wwccs_custom_process_refund($order_id);
        }
    }
}
```