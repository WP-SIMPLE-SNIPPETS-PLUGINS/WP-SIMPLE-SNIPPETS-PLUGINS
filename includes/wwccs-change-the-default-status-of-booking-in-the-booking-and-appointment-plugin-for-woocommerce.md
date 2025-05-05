# How to Change the Default Status of Booking in the Booking and Appointment plugin for WooCommerce?

```generic
/**
 * Change default booking status to paid when order is placed.
 *
 * @param string $status Status of Booking.
 */
function bkap_booking_status_on_create_order( $status ) {
	return 'Paid';
}
add_filter( 'bkap_booking_status_on_create_order', 'bkap_booking_status_on_create_order', 10, 1 );
```