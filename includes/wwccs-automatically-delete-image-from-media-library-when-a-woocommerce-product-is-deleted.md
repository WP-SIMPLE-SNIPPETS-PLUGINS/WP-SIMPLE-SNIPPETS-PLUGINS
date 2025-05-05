# How to Automatically Delete Image from Media Library When a WooCommerce Product is Deleted?

```generic
// Automatically Delete Woocommerce Images After Deleting a Product
add_action( 'before_delete_post', 'wwccs_delete_product_images', 10, 1 );

function wwccs_delete_product_images( $post_id )
{
    $product = wc_get_product( $post_id );

    if ( !$product ) {
        return;
    }

    $featured_image_id = $product->get_image_id();
    $image_galleries_id = $product->get_gallery_image_ids();

    if( !empty( $featured_image_id ) ) {
        wp_delete_post( $featured_image_id );
    }

    if( !empty( $image_galleries_id ) ) {
        foreach( $image_galleries_id as $single_image_id ) {
            wp_delete_post( $single_image_id );
        }
    }
}
```