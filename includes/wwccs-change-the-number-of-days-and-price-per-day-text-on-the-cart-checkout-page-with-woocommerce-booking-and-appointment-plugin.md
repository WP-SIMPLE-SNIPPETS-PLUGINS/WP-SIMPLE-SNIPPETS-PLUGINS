# How to Change the 'Number of Days' and 'Price per Day' Text on the Cart & Checkout Page with WooCommerce Booking and Appointment Plugin?

```generic
function bkap_get_item_data_callback( $other_data, $cart_item ) {

	$new_data = $other_data;

	foreach ($other_data as $key => $value) {
		if ( __( 'No. of Days', 'woocommerce-booking' ) == $value['name'] ) {
			$new_data[$key]['name'] = __( 'Nights selected', 'woocommerce-booking' );
		}

		if ( __( 'Per Day Price', 'woocommerce-booking' ) == $value['name'] ) {
			$new_data[$key]['name'] = __( 'Price per night', 'woocommerce-booking' );
		}
	}
	return $new_data;
}
add_filter( 'bkap_get_item_data', 'bkap_get_item_data_callback', 10, 2 );
```