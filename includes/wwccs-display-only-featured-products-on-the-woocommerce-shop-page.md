# How to Display Featured Products on the WooCommerce Shop Page?

```generic
add_filter( 'woocommerce_catalog_orderby', 'wwccs_woocommerce_catalog_orderby_custom' );
 
function wwccs_woocommerce_catalog_orderby_custom( $sortby ) {
    $sortby['featured'] = __( 'Default sorting', 'woocommerce' );
    unset( $sortby['menu_order'] );
    return $sortby;
}
 
// Make the custom one the default one
 
add_filter( 'woocommerce_default_catalog_orderby', 'wwccs_woocommerce_catalog_orderby_custom_default' );
 
function wwccs_woocommerce_catalog_orderby_custom_default() {
    return 'featured';
}
 
// Set sorting for new option
 
add_filter( 'woocommerce_get_catalog_ordering_args', 'wwccs_woocommerce_catalog_orderby_custom_args' );
 
function wwccs_woocommerce_catalog_orderby_custom_args( $args ) {
    $orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
    if ( 'featured' == $orderby_value ) {
        $args['orderby'] = 'menu_order title';
        $args['order'] = '';
        $args['meta_key'] = '';
    }
    return $args;
}
 
// Adjust order to allow for featured posts
 
add_filter( 'poswwccs_orderby', 'wwccs_woocommerce_catalog_orderby_custom_featured_first', 10, 2 );
 
function wwccs_woocommerce_catalog_orderby_custom_featured_first( $order_by, $query ) {
    global $wpdb;
    if ( ! is_admin() ) {
        $orderby_value = ( isset( $_GET['orderby'] ) ? wc_clean( (string) $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) ) );
        $orderby_value_array = explode( '-', $orderby_value );
        $orderby = esc_attr( $orderby_value_array[0] );
        $order = ( ! empty( $orderby_value_array[1] ) ? $orderby_value_array[1] : 'ASC' );
        $featured_product_ids = wc_get_featured_product_ids();
        if ( $orderby == "featured" && is_array( $featured_product_ids ) && ! empty( $featured_product_ids ) ) {
            if ( empty( $order_by ) ) {
                $order_by = "FIELD(" . $wpdb->posts . ".ID,'" . implode( "','", $featured_product_ids ) . "') DESC ";
            } else {
                $order_by = "FIELD(" . $wpdb->posts . ".ID,'" . implode( "','", $featured_product_ids ) . "') DESC, " . $order_by;
            }
        }  
    }
    return $order_by;
}
```