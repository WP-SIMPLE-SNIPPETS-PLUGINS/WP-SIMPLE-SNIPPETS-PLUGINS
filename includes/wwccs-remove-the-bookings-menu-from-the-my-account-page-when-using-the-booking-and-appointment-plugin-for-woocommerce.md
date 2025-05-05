# How to Remove the Bookings menu from the 'My Account' page when using the Booking and Appointment plugin for WooCommerce?

```generic
/**
 * Remove the Bookings menu from the My Accounts page
 *
 * @param array $menu My Account Menu.
 */
function bkap_remo_booking_endpoint( $menu ) {

	unset( $menu['bookings'] );

	return $menu;
}
add_filter( 'woocommerce_account_menu_items', 'bkap_remo_booking_endpoint', 20, 1 );
```