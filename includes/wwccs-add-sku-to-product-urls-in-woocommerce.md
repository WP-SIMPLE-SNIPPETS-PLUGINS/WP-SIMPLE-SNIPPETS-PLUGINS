# How to Add SKU to Product URLs in WooCommerce?

```generic
function wwccs_append_sku_string( $post_link, $post ) {
    if ( 'product' === $post->post_type ) {
        $sku = get_post_meta( $post->ID, '_sku', true );
        if ( $sku ) {
            $post_link .= '#' . $sku;
        }
    }
    return $post_link;
}
add_filter( 'post_type_link', 'wwccs_append_sku_string', 10, 2 );
```