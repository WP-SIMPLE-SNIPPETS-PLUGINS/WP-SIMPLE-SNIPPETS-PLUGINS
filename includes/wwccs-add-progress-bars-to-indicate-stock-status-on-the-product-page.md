# How to Add Progress Bars to Indicate Stock Status on the Product Page?

```generic
add_action( 'woocommerce_before_add_to_cart_form', 'wwccs_stock_status_progress_bar', 10, 0 );
function wwccs_stock_status_progress_bar() {
	global $product;
	if (!$product->managing_stock()) return; // Don't show the progress bar if stock isn't being managed
	$stock_quantity = $product->get_stock_quantity();
	echo 'Only ' . $stock_quantity . ' tickets remaining!<br><progress max="100" value="'.$stock_quantity.'"></progress>'; // 100 being the fill level of the progress bar (the left most value)
}
```