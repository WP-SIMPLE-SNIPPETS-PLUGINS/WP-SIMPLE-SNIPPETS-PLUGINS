# How to Change the Duration Multiplier Text on the Frontend When Using the Booking and Appointment Plugin for WooCommerce?

```generic
/** 
 * Changes the displayed text format for duration based on time.
 *
 * @param string $into_hr_min Original String.
 * @param string $duration Duration Value.
 * @param string $duration_type Duration Type.
 * @param int    $product_id Product ID.
 * @param array  $bkap_setting Booking Settings.
 */
function bkap_hour_min_text_for_duration_field( $into_hr_min, $duration, $duration_type, $product_id, $bkap_setting ){
	$into_hr_min = sprintf( __( '%1$s %2$s', 'woocommerce-booking' ), $duration, $duration_type );
	return $into_hr_min;
}
add_filter( 'bkap_hour_min_text_for_duration_field', 'bkap_hour_min_text_for_duration_field', 10, 5 );
```