# How to Change Price Title Based on the Booking Price in WooCommerce Booking and Appointment Plugin ?

```generic
/** 
 * Change Price Heading Based on the Booking Price set in Booking Meta Box
 *
 * @param string $price Price String
 * @param obj    $product Product Object
 * 
 * Note: Currently available only for Fixed Time Booking Type.
 * Created based on client requirements. To display the price for all the types will take time so later we can work.
 * 
 * @return string
 */
function bkap_woocommerce_get_price_html( $price, $product ) {	

	if ( ! function_exists( 'is_booking_active' ) ) {
		return $price;
	}
	
	$product_id = $product->get_id();
	$bookable   = bkap_common::bkap_get_bookable_status( $product_id );

	if ( $bookable ) {
		$data             = get_post( $product_id );
		$booking_type     = $data->_bkap_booking_type;
		$booking_settings = $data->woocommerce_booking_settings;
		$current_time     = current_time( 'timestamp' );
		$jny_date         = date( 'j-n-Y', $current_time );
		$special_price    = bkap_special_booking_price::get_price( $product_id, $jny_date ); // later pass blank date to get price array 

		switch ( $booking_type ) {
			case 'only_day':				
				break;
			case 'multiple_days':				
				break;
			case 'date_time':
				$bkap_price            = array();
				$booking_time_settings = $data->_bkap_time_settings;
				$price_blank           = false;

				foreach( $booking_time_settings as $daydate => $time_data ) {
					foreach ( $time_data as $key => $value ) {
						if ( '' != $value['slot_price'] ) {
							$bkap_price[] = $value['slot_price'];
						} else {
							$price_blank = true;
						}
					}
				}

				if ( ! empty( $bkap_price) ) {
					$bkap_price     = array_unique( $bkap_price );
					$bkap_min_price = min( $bkap_price );
					$bkap_max_price = max( $bkap_price );

					if ( $bkap_min_price == $bkap_max_price ) {

						if ( count( $bkap_price ) == 1 ) { // When only one price then see if special price is set
							if ( '' != $special_price && $price_blank ) {
								if ( $special_price < $bkap_min_price ) {
									$price1 = $special_price;
									$price2 = $bkap_min_price;
								} else {
									$price1 = $bkap_min_price;
									$price2 = $special_price;
								}
								$price1 = wc_price( $price1 );
								$price2 = wc_price( $price2 );

								$price = $price1 . ' - ' . $price2;
							} else {
								$price = wc_price( $bkap_min_price );
							}
						} else {
							$price = wc_price( $bkap_min_price );
						}
						
					} else {

						if ( '' != $special_price && $price_blank && $special_price < $bkap_min_price ) {
							$bkap_min_price = $special_price;
						}

						$bkap_min_price = wc_price( $bkap_min_price );
						$bkap_max_price = wc_price( $bkap_max_price );
						$price = $bkap_min_price . ' - ' . $bkap_max_price;
					}
				}
				break;
			case 'duration_time':				
				break;
		}
	}

	return $price;
}
add_filter( 'woocommerce_get_price_html', 'bkap_woocommerce_get_price_html', 10, 2 );
```