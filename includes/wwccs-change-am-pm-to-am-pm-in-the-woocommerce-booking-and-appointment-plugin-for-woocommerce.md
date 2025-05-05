# How to Change AM/PM to am/pm in the WooCommerce Booking and Appointment Plugin for WooCommerce?

```generic
/** 
 * Changing time slots format on the front end dropdown.
 *
 * @param array $timeslots Array of Time Slots.
 * @todo - This option is not working with the Timezone option enabled.
 */
function bkap_time_slot_filter_after_chronological( $timeslots ) {

	foreach ( $timeslots as $key => $value ) {

		$explode_time = explode( ' - ', $value );
		$time         = date( 'g.ia', strtotime( $explode_time[0] ) );

		if ( isset( $explode_time[1] ) ) {
			$to_time = date( 'g.ia', strtotime( $explode_time[1] ) );
			$time    = $time . ' - ' . $to_time;
		}

		$timeslots[ $key ] = $time;
	}
	return $timeslots;
}
add_filter( 'bkap_time_slot_filter_after_chronological', 'bkap_time_slot_filter_after_chronological', 10, 1 );
```