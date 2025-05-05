# How to Avoid Calculating the Resource Price According to the Quantity in the Booking and Appointment Plugin for WooCommerce?

```generic
/**
 * Do not calculate the resource price according to quantity.
 *
 * @param float $time_slot_price Price.
 * @param int $product_id Product ID.
 * @param int $variation_id Variation ID.
 * @param string $product_type Product Type.
 */
function bkap_modify_booking_price( $time_slot_price, $product_id, $variation_id, $product_type ){

	if ( isset( $_POST['resource_id'] ) && '' != $_POST['resource_id'] ) {
		$resource_id    = $_POST['resource_id'];
		$resource       = new BKAP_Product_Resource( $resource_id, $product_id );
		$resource_price = $resource->get_base_cost();

		if ( 0 != $resource_price ) {
			$time_slot_price = $time_slot_price - $resource_price;
			$resource_price  = $resource_price / $_POST['quantity'];
			$time_slot_price = $time_slot_price + $resource_price;
		}
	}
	return $time_slot_price;
}
add_filter( 'bkap_modify_booking_price', 'bkap_modify_booking_price', 10, 4 );
```