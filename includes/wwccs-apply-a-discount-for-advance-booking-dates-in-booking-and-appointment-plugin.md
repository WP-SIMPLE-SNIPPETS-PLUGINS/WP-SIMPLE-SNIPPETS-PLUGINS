# How to Apply a Discount for Advance Booking Dates in Booking and Appointment Plugin?

```generic
/**
 * 50% discount if the check-in date is 4 days after the current date.
 *
 * @param array  $cart_arr Cart Array.
 * @param int    $product_id Product ID.
 * @param int    $variation_id Variation ID.
 * @param array  $cart_item_meta Cart Item Array.
 * @param array  $booking_settings Booking Settings.
 * @param object $global_settings Global Booking Settings.
 * 
 * @return array $cart_arr Cart Array.
 */
function bkap_addon_add_cart_item_data( $cart_arr, $product_id, $variation_id, $cart_item_meta, $booking_settings, $global_settings ) {
	$hidden_date   = $cart_arr['hidden_date'];
	$current_time  = current_time( 'timestamp' );
	$current_date  = date( 'j-n-Y', $current_time );
	$booking_date  = new DateTime( $hidden_date );
	$checkout_date = new DateTime( $current_date );
	$difference    = $checkout_date->diff( $booking_date );
	if ( $difference->d > 4 ) { // if checking date is after 4 days. 
		$cart_arr['price'] = $cart_arr['price'] / 2; // 50% discount.
	}
	return $cart_arr;
}
add_filter( 'bkap_addon_add_cart_item_data', 'bkap_addon_add_cart_item_data', 10, 6 );
```