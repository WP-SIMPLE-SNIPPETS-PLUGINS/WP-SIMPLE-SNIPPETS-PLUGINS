# How to Optimize Duration Pricing in Booking and Appointment Plugin Based on Selected Durations?

```generic
/** 
 * Manipulating the Duration price based on the selected duration.
 * 
 * @param string $final_price Final Price Calculated based on the Settings
 * @param string $product_id Product ID
 * @param string $booking_settings Booking Settings
 * @param string $post POST data 
 */
function bkap_final_duration_price_callback( $final_price, $product_id, $booking_settings, $post ) {
        // here 2587 is Product ID, array is the combination of duration => price
	$prices = array(
				'62' => array( '1' => '1000', '2' => '1500', '3' => '2000', '4' => '2750' ),  // 1 = 30 mins, 2 = 1 hour, 3 = 1.5 hour, 4 = 2 hours
				'2589' => array( '1' => '20', '2' => '30', '3' => '35', '4' => '45' ),
			);
	
	if ( in_array( $product_id, array_keys( $prices ) ) ) {
		$price_basedon_durations = $prices[ $product_id ];
		$selected_duration       = $post['bkap_duration'];
		$final_price             = isset( $price_basedon_durations[ $selected_duration ] ) ? $price_basedon_durations[ $selected_duration ] : $final_price;
	}

	return $final_price;
}
add_filter( 'bkap_final_duration_price', 'bkap_final_duration_price_callback', 10, 4 );
```