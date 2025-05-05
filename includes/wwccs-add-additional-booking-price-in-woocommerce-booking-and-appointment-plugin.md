# How to Add Additional Booking Price in WooCommerce Booking and Appointment Plugin?

```generic
// Add booking fee to price on product page 
function new_price($total_price) {
  $new_price = $total_price + 15;
  return $new_price;
}
add_filter( 'bkap_modify_booking_price' , 'new_price');
```