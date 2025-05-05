# How to Move the Resource Field Below the Date and Time Selection in Booking and Appointment plugin for WooCommerce?

```generic
/**
 * This function will move the resource and person fields below the date and time selection.
 */
function bkap_move_persons_resource_fields() {
	if ( is_product() ) {
		?>

	<script>
	jQuery( document ).ready( function () {
		
		// Moving REsource at bottom.
		if ( jQuery( '#bkap_front_resource_selection' ).length > 0 ) {
			jQuery( ".bkap_resource_container" ).insertBefore( ".bkap-form-error" );
		}
		// Moving Persons at bottom.
		if ( jQuery( ".bkap_persons_container" ).length > 0 ) {
			jQuery( ".bkap_persons_container" ).insertBefore( ".bkap-form-error" );
		}
	});
	</script>
		<?php
	}
}
add_action( 'wp_head', 'bkap_move_persons_resource_fields' );
```