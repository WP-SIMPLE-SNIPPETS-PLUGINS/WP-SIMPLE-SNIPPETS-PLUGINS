# How to Remove ‘No of Days’ and ‘Price Per Day’ from Cart & Checkout Page in WooCommerce Booking and Appointment Plugin?

```generic
/**
 * Remove No. of Days and Price per day from Cart and Checkout page.
 * 
 * @param array $other_data Array of Other data.
 * @param array $cart_item Array value of Cart Item.
 */
function bkap_get_item_data_callback( $other_data, $cart_item ) {

	foreach ($other_data as $key => $value) {
		if ( __( 'No. of Days', 'woocommerce-booking' ) == $value['name'] ) {
			unset( $other_data[$key] );
		}

		if ( __( 'Per Day Price', 'woocommerce-booking' ) == $value['name'] ) {
			unset( $other_data[$key] );
		}
	}
	return $other_data;
}
add_filter( 'bkap_get_item_data', 'bkap_get_item_data_callback', 10, 2 );
```