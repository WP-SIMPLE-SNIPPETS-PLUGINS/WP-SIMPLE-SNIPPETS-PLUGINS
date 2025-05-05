# How to Remove / Disable Billing and Shipping Address Fields in WooCommerce Checkout Blocks Page?

```generic
add_filter('woocommerce_get_country_locale', function( $locale ) {
	foreach ( $locale as $key => $value ) {
		// Make address_1, postcode, city, state fields optional and hidden for all countries
		$locale[ $key ]['address_1'] = [
			'required' => false,
			'hidden'   => true,
		];

		$locale[ $key ]['postcode'] = [
			'required' => false,
			'hidden'   => true,
		];

		$locale[ $key ]['city'] = [
			'required' => false,
			'hidden'   => true,
		];

		$locale[ $key ]['state'] = [
			'required' => false,
			'hidden'   => true,
		];

	}

	return $locale;
});
```

```generic
function wwccs_hide_phone_fields_in_block_checkout() {
    // Check if it's the checkout page
    if (is_checkout()) {
        ?>
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function() {
                // Hide all phone input fields in the checkout page (both billing and shipping)
                const phoneFields = document.querySelectorAll('input[type="tel"]');
                phoneFields.forEach(function(field) {
                    field.closest('div').style.display = 'none'; // Hide the parent div
                });
            });
        </script>
        <?php
    }
}
add_action('wp_footer', 'wwccs_hide_phone_fields_in_block_checkout');
```