# How to Add 'Select Resource' Option in Dropdown When Using the Booking and Appointment Plugin for WooCommerce?

```generic
function bkap_default_resource_option_value() {

	return '<option value="">' . __( 'Select Resource', 'woocommerce-booking' ) . '</option>';
}
add_filter( 'bkap_default_resource_option_value', 'bkap_default_resource_option_value', 10, 1 );
```