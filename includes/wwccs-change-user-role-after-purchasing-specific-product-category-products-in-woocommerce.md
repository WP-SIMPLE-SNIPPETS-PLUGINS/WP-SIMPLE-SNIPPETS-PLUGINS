# How to Change User Role After Purchasing Specific Product Category Products in WooCommerce?

```generic
add_action( 'woocommerce_thankyou', 'wwccs_change_user_role_for_premium_membership' );

function wwccs_change_user_role_for_premium_membership( $order_id ) {
    if ( ! $order_id ) return;
    
    $order = wc_get_order( $order_id );
    $user_id = $order->get_user_id();
    
    if ( ! $user_id ) return; // Ensure the user is logged in
    
    $user = new WP_User( $user_id );
    
    // Category slug that triggers role change
    $premium_category = 'premium-membership';
    $premium_role = 'premium_member';

    foreach ( $order->get_items() as $item ) {
        $product_id = $item->get_product_id();
        $product_categories = wp_get_post_terms( $product_id, 'product_cat', array( 'fields' => 'slugs' ) );

        if ( in_array( $premium_category, $product_categories ) ) {
            $user->set_role( $premium_role ); // Assign only the premium role
            break; // Exit once a match is found
        }
    }
}
```