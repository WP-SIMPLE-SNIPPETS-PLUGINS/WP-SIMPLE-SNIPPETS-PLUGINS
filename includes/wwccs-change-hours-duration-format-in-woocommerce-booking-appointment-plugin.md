# How to Change Hours Duration Format in WooCommerce Booking & Appointment Plugin?

```generic
function bkap_hour_min_text_for_duration_field_callback(){
	return '';
}
add_filter( 'bkap_hour_min_text_for_duration_field', 'bkap_hour_min_text_for_duration_field_callback' );
```