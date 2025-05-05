# How to Customize the 'No of Days Selected' Text Displayed when using a Booking and Appointment Plugin?

```generic
/**
 * Change Number of Days text.
 *
 * @param string $text Number of Days string.
 * @param int    $product_id Product ID.
 * @param array  $booking_settings Booking Settings.
 */
function bkap_selected_days_label_callback( $text, $product_id, $booking_settings ) {

	$text = __( 'Selected Days:', 'woocommerce-booking' );
	return $text;
}
add_filter( 'bkap_selected_days_label', 'bkap_selected_days_label_callback', 10, 3 );
```