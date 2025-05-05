# How to Add Customer Order Notes Field on WooCommerce Product Pages?

```generic
// Add a custom product note above the add to cart button on single product pages
add_action('woocommerce_before_add_to_cart_button', 'wwccs_custom_product_note', 5);
function wwccs_custom_product_note() {
    echo '<br><div>';
    woocommerce_form_field('customer_note', array(
        'type' => 'textarea',
        'class' => array('my-field-class form-row-wide'),
        'label' => __('Product note'),
        'placeholder' => __('Add your note here, please…'),
        'required' => false,
    ), '');
    echo '</div>';
?>
<script type="text/javascript">
jQuery(function($){
    $('#customer_note').on('input blur', function() {
        $('#product_note').val($(this).val());
    });
});
</script>
<?php
}

// Custom hidden field in add to cart form
add_action('woocommerce_before_add_to_cart_button', 'wwccs_hidden_field_before_add_to_cart_button', 5);
function wwccs_hidden_field_before_add_to_cart_button() {
    echo '<input type="hidden" name="product_note" id="product_note" value="">';
}

// Add customer note to cart item data
add_filter('woocommerce_add_cart_item_data', 'add_product_note_to_cart_item_data', 20, 2);
function add_product_note_to_cart_item_data($cart_item_data, $product_id) {
    if (isset($_POST['product_note']) && !empty($_POST['product_note'])) {
        $product_note = sanitize_textarea_field($_POST['product_note']);
        $cart_item_data['product_note'] = $product_note;
    }
    return $cart_item_data;
}

// Save customer note with order item meta
add_action('woocommerce_checkout_create_order_line_item', 'wwccs_save_customer_note_as_order_item_meta', 10, 4);
function wwccs_save_customer_note_as_order_item_meta($item, $cart_item_key, $values, $order) {
    if (isset($values['product_note'])) {
        $item->add_meta_data('Customer Note', $values['product_note'], true);
    }
}

// Display customer note in the order items table
add_filter('woocommerce_before_order_itemmeta', 'wwccs_display_customer_note_in_order_items_table', 10, 3);
function wwccs_display_customer_note_in_order_items_table($item_output, $item, $args) {
    if ($note = $item->get_meta('Customer Note', true)) {
        $item_output .= '<br><small>' . __('Customer Note:', 'your-text-domain') . ' ' . esc_html($note) . '</small>';
    }
    return $item_output;
}
// Display customer note in the cart
add_filter('woocommerce_get_item_data', 'wwccs_display_customer_note_in_cart', 10, 2);
function wwccs_display_customer_note_in_cart($item_data, $cart_item) {
    if (isset($cart_item['product_note']) && !empty($cart_item['product_note'])) {
        $item_data[] = array(
            'name'  => __('Product Note', 'your-text-domain'),
            'value' => esc_html($cart_item['product_note'])
        );
    }
    return $item_data;
}
```