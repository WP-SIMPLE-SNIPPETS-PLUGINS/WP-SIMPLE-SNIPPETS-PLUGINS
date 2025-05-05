# How to Display Price × Number of Days Calculation in Cart and Checkout Pages with WooCommerce Booking and Appointment Plugin?

```generic
function bkap_woocommerce_cart_item_subtotal( $product_subtotal, $cart_item, $cart_item_key ) {

	if ( isset( $cart_item['bkap_booking'] ) ) {
		
		$booking = $cart_item['bkap_booking'][0];
		if ( isset( $booking[ 'hidden_date_checkout' ] ) ) {
			$checkin_date_str  = strtotime( $booking[ 'hidden_date' ] );
			$checkout_date_str = strtotime( $booking[ 'hidden_date_checkout' ] );
			$checkin_date      = date( 'Y-m-d', $checkin_date_str );
			$checkout_date     = date( 'Y-m-d', $checkout_date_str );
			$number_of_days    = $checkout_date_str - $checkin_date_str;
			$no_of_nights      = floor( $number_of_days / 86400 );
			$per_day_price     = $booking[ 'price' ] / $no_of_nights;
			$product_subtotal = wc_price( $per_day_price ) . ' x ' . $no_of_nights . ' nights = ' . $product_subtotal;
		}
	}
	return $product_subtotal;
}
add_filter( 'woocommerce_cart_item_subtotal', 'bkap_woocommerce_cart_item_subtotal', 10, 3 );
```