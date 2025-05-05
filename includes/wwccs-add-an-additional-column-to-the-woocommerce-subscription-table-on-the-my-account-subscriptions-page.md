# How to Add an Additional Column to the WooCommerce Subscription Table on the My Account > Subscriptions page?

```generic
add_action( 'woocommerce_account_subscriptions_endpoint', 'wwccs_replace_subscription_table_with_custom', 1 );

function wwccs_replace_subscription_table_with_custom() {
    // Start output buffering to capture WooCommerce Subscriptions output
    ob_start();
}

add_action( 'woocommerce_account_subscriptions_endpoint', 'wwccs_render_custom_subscription_table', 100 );

function wwccs_render_custom_subscription_table() {
    // Get the captured output
    $content = ob_get_clean();

    // Check if the default table output is present and replace it
    if ( strpos( $content, 'woocommerce-orders-table--subscriptions' ) !== false ) {
        // Get subscriptions for the current user
        $subscriptions = wcs_get_users_subscriptions();

        if ( empty( $subscriptions ) ) {
            echo '<p class="no_subscriptions">' . esc_html__( 'You have no active subscriptions.', 'woocommerce-subscriptions' ) . '</p>';
            return;
        }
        
        // Output the custom table instead of the default table
        ?>
        <table class="woocommerce-orders-table woocommerce-orders-table--subscriptions">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Subscription', 'woocommerce-subscriptions' ); ?></th>
                    <th><?php esc_html_e( 'Status', 'woocommerce-subscriptions' ); ?></th>
                    <th><?php esc_html_e( 'Next Payment', 'woocommerce-subscriptions' ); ?></th>
                    <th><?php esc_html_e( 'Total', 'woocommerce-subscriptions' ); ?></th>
                    <th><?php esc_html_e( 'Product Name', 'woocommerce-subscriptions' ); ?></th> <!-- New Column -->
                    <th><?php esc_html_e( 'Actions', 'woocommerce-subscriptions' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $subscriptions as $subscription ) : ?>
                    <tr>
                        <td>
                            <a href="<?php echo esc_url( $subscription->get_view_order_url() ); ?>">
                                <?php echo esc_html( '#' . $subscription->get_order_number() ); ?>
                            </a>
                        </td>
                        <td><?php echo esc_html( wcs_get_subscription_status_name( $subscription->get_status() ) ); ?></td>
                        <td><?php echo esc_html( $subscription->get_date_to_display( 'next_payment' ) ); ?></td>
                        <td><?php echo wp_kses_post( $subscription->get_formatted_order_total() ); ?></td>
                        <td>
                            <?php
                            $items = $subscription->get_items();
                            $product_names = [];
                            foreach ( $items as $item ) {
                                $product = $item->get_product();
                                if ( $product ) {
                                    $product_names[] = $product->get_name();
                                }
                            }
                            echo esc_html( implode( ', ', $product_names ) ?: __( 'No Product', 'woocommerce-subscriptions' ) );
                            ?>
                        </td>
                        <td>
                            <a href="<?php echo esc_url( $subscription->get_view_order_url() ); ?>" class="button"><?php esc_html_e( 'View', 'woocommerce-subscriptions' ); ?></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
    } else {
       
        echo $content;
    }
}
```