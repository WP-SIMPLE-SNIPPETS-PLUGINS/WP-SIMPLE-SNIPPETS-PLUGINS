# How to Customize Select Resource Text for Specific Products on Shop Page in Booking & Appointment Plugin?

```generic
/**
 * Changing the Select Resource Text on the Shop page for list of products
 * 
 * @param string $text Select Resource Text.
 * @param int $product_id Product ID
 * 
 * @return string
 */
function bkap_change_select_resource_text_callback( $text, $product_id ) {

    $ids = array( 123, 456, 789 ); // Product ids of resource product for which you want to change the text. 
    if ( in_array( $product_id, $ids ) ) {
        $text = __( 'Choose Your Doctor', 'woocommerce-booking' );
    }

    return $text;
}
add_filter( 'bkap_change_select_resource_text', 'bkap_change_select_resource_text_callback', 10, 2 );
```