# How to Remove Booking Details from WooCommerce Emails using Booking and Appointment Plugin?

```generic
/**
 * Removing Booking details from WooCommerce Emails.
 */
function unset_booking_order_item_meta_data( $formatted_meta, $item ) {

	$start_date = bkap_option( 'email_start_date' );
	$end_date   = bkap_option( 'email_end_date' );
	$time       = bkap_option( 'email_time' );

	if ( did_action( 'woocommerce_email_order_meta' ) || did_action( 'woocommerce_email_after_order_table' ) ) {
		foreach ( $formatted_meta as $key => $meta ) {
			if ( in_array( $meta->key, array( $start_date, $end_date, $time ), true ) ) {
				unset( $formatted_meta[ $key ] );
			}
		}
	}
}
add_filter( 'woocommerce_order_item_get_formatted_meta_data', 'unset_booking_order_item_meta_data', 10, 2 );
```