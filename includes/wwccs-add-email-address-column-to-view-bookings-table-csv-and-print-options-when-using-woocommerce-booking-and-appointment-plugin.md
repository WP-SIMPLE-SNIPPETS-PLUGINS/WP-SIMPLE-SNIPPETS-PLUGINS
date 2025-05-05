# How to Add Email Address Column to View Bookings table, CSV and Print Options when using WooCommerce Booking and Appointment Plugin?

```generic
/* Adding Column on View Bookings */
function bkap_view_booking_columns( $columns ) {

	$additional_columns = array( 'bkap_customer_email' => __( 'Email Address', 'woocommerce-booking' ) );
	// Adding column after Booked by hence 5.
	$columns = array_slice( $columns, 0, 5, true ) + $additional_columns + array_slice( $columns, 5, count( $columns ) - 5, true );

	return $columns;
}
add_filter( 'bkap_view_booking_columns', 'bkap_view_booking_columns', 10,  );

function bkap_view_booking_column_data( $column, $data ) {

	switch ( $column) {
		case 'bkap_customer_email':
			echo $data['bkap_customer_email'];
			break;
		default:
			# code...
			break;
	}
}
add_filter( 'bkap_view_booking_column_data', 'bkap_view_booking_column_data', 10, 2 );

/* Tyche Softwares: Adding Booking Data on View Bookings page */
function bkap_view_booking_individual_data( $booking_data, $booking, $booking_id ) {

	$booking_obj    = new BKAP_Booking( $booking_id );
	$customer       = $booking_obj->get_customer();
	$customer_email = $customer->email;

	$booking_data['bkap_customer_email'] = $customer_email;

	return $booking_data;

}
add_filter( 'bkap_view_booking_individual_data', 'bkap_view_booking_individual_data', 10, 3 );

/* Adding column to CSV and Print */
function bkap_bookings_csv_columns( $columns ) {
	$additional_columns = array( 'bkap_customer_email' => __( 'Email Address', 'woocommerce-booking' ) );
	// Adding column after Booked by hence 4.
	$columns = array_slice( $columns, 0, 4, true ) + $additional_columns + array_slice( $columns, 4, count( $columns ) - 4, true );
	return $columns;
}
add_filter( 'bkap_bookings_csv_columns', 'bkap_bookings_csv_columns', 10, 1 );

/* Adding Booking Data to CSV */
function bkap_bookings_csv_individual_data( $row, $booking, $booking_id, $data ) {

	extract( $data );
	// Fetching Custom Email.
	$booking_obj    = new BKAP_Booking( $booking_id );
	$customer       = $booking_obj->get_customer();
	$customer_email = $customer->email;

	// Adding Customer Email infomration after Booked By column data.
	$row = $status . ',' . $booking_id . ',"' . $product_name . '",' . $booked_by . ',' . $customer_email . ',' . $order_id . ',"' . $start_date . '","' . $end_date . '","' . $persons . '",' . $quantity . ',' . $order_date . ',"' . $final_amt . '",' . $meeting_link;
	return $row;
}
add_filter( 'bkap_bookings_csv_individual_row_data', 'bkap_bookings_csv_individual_data', 10, 4 );

/* Adding Booking Data to Print td */
function bkap_view_bookings_print_individual_row_data( $print_data_row_data_td, $booking, $booking_id, $data ) {
	extract( $data );
	// Fetching Customer Email.
	$booking_obj    = new BKAP_Booking( $booking_id );
	$customer       = $booking_obj->get_customer();
	$customer_email = $customer->email;
	// Adding Customer Email after Booked by column data.
	$print_data_row_data_td  = '';
	$print_data_row_data_td .= '<td style="border:1px solid black;padding:5px;">' . $status . '</td>';
	$print_data_row_data_td .= '<td style="border:1px solid black;padding:5px;">' . $booking->id . '</td>';
	$print_data_row_data_td .= '<td style="border:1px solid black;padding:5px;">' . $product_name . '</td>';
	$print_data_row_data_td .= '<td style="border:1px solid black;padding:5px;">' . $booked_by . '</td>';
	$print_data_row_data_td .= '<td style="border:1px solid black;padding:5px;">' . $customer_email . '</td>';
	$print_data_row_data_td .= '<td style="border:1px solid black;padding:5px;">' . $booking_obj->order_id . '</td>';
	$print_data_row_data_td .= '<td style="border:1px solid black;padding:5px;">' . $start_date . '</td>';
	$print_data_row_data_td .= '<td style="border:1px solid black;padding:5px;">' . $end_date . '</td>';
	$print_data_row_data_td .= '<td style="border:1px solid black;padding:5px;">' . $persons . '</td>';
	$print_data_row_data_td .= '<td style="border:1px solid black;padding:5px;">' . $quantity . '</td>';
	$print_data_row_data_td .= '<td style="border:1px solid black;padding:5px;">' . $order_date . '</td>';
	$print_data_row_data_td .= '<td style="border:1px solid black;padding:5px;">' . $final_amt . '</td>';
	$print_data_row_data_td .= '<td style="border:1px solid black;padding:5px;"><small>' . $meeting_link . '</small></td>';

	return $print_data_row_data_td;
}
add_filter( 'bkap_view_bookings_print_individual_row_data', 'bkap_view_bookings_print_individual_row_data', 10, 4 );
```