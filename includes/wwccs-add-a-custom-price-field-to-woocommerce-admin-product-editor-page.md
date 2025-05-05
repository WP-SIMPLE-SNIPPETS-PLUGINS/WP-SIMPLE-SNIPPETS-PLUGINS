# How to Add a Custom Price Field To WooCommerce Admin Product Editor Page?

```generic
// 1. Add RRP field input @ product edit page

add_action( 'woocommerce_product_options_pricing', 'wwccs_add_RRP_to_products' );      

function wwccs_add_RRP_to_products() {          
    woocommerce_wp_text_input( array( 
        'id' => 'rrp', 
        'class' => 'short wc_input_price', 
        'label' => __( 'RRP', 'woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
        'data_type' => 'price', 
    ));      
}


// 2. Save RRP field via custom field

add_action( 'save_post_product', 'wwccs_save_RRP' );

function wwccs_save_RRP( $product_id ) {
    global $typenow;
    if ( 'product' === $typenow ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( isset( $_POST['rrp'] ) ) {
            update_post_meta( $product_id, 'rrp', $_POST['rrp'] );
        }
    }
}


// 3. Display RRP field @ single product page

add_action( 'woocommerce_single_product_summary', 'wwccs_display_RRP', 9 );

function wwccs_display_RRP() {
    global $product;
    if ( $product->get_type() <> 'variable' && $rrp = get_post_meta( $product->get_id(), 'rrp', true ) ) {
        echo '<div class="woocommerce_rrp">';
        _e( 'RRP: ', 'woocommerce' );
        echo '<span>' . wc_price( $rrp ) . '</span>';
        echo '</div>';
    }
}
```