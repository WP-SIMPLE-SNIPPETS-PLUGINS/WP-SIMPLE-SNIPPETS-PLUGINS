# How to Modify Booking Price Based on Selected Variation and Fixed Block Pricing in Booking and Appointment plugin for WooCommerce?

```generic
/**
 * This will modify the variation price based on the selected variation and fixed block.
 *
 * @param string $price Price.
 * @param int    $product_id Product ID.
 * @param int    $variation_id Variation ID.
 * @param string $product_type Type of Product.
 * @param string $checkin_date Check-in Date.
 * @param string $checkout_date Check-out Date.
 */
function bkap_custom_price_for_fixed_block( $price, $product_id, $variation_id, $product_type, $checkin_date, $checkout_date ) {

	if ( '' != $variation_id ) {

		$number_of_days = strval( $_POST['diff_days'] );
		$var_id         = strval( $variation_id );
		$custom_price   = array(
			'2198' => array( '14' => 2000, '30' => 3000 ), // 2198 - Variation ID of Gold color variation.
			
			
			'2196' => array( '14' => 4000, '30' => 5000 ), // 2196 - Variation ID of Blush Pink color variation.
                       '2197' => array( '14' => 5000, '30' => 6000 ), // 2197 - Variation ID of White color variation.
			
		);

		if ( isset( $custom_price[ $variation_id ][ $number_of_days ] ) ) { // check if variation price is set for selected number of days.
			$price = $custom_price[ $variation_id ][ $number_of_days ];
		}
	}

	return $price;
}
add_filter( 'bkap_custom_price_for_fixed_block', 'bkap_custom_price_for_fixed_block', 10, 6 );
```