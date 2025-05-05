# How to Hide Resource Price from Resource Dropdown in WooCommerce Booking and Appointment plugin?

```generic
/** 
 * Do not show Price in the Resource Dropdown.
 * 
 * @param string $price_str Price String.
 * @param array  $resource_data Resource Data.
 * @param int    $product_id Product ID.
 * 
 * @return string
 */
function bkap_resource_price_in_dropdown_callback( $price_str, $resource_data, $product_id ){
	return '';
}
add_filter( 'bkap_resource_price_in_dropdown', 'bkap_resource_price_in_dropdown_callback', 10, 3 );
```