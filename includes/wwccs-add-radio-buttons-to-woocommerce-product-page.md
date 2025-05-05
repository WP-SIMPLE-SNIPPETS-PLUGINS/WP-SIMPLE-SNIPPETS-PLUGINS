# How to Add Radio Buttons to WooCommerce Product Page?

```generic
// Display Radio Buttons on Product Page
add_action('woocommerce_before_add_to_cart_button', 'wwccs_product_radio_choice');

function wwccs_product_radio_choice() {
    $chosen = WC()->session->get('radio_chosen');
    $chosen = empty($chosen) ? 'add_text' : $chosen;

    $args = array(
        'type' => 'radio',
        'class' => array('form-row-wide'),
        'options' => array(
            'add_text' => 'Add a Text ($5)',
            'add_logo' => 'Add a Logo ($10)',
        ),
        'default' => $chosen
    );

    echo '<div id="product-radio">';
    echo '<h3>Customize Your Order!</h3>';
    woocommerce_form_field('radio_choice', $args, $chosen);
    echo '</div>';
}

// Capture selected radio option on the product page and send it to the server
add_action('wp_footer', 'wwccs_capture_selected_radio_option');

function wwccs_capture_selected_radio_option() {
    if (is_product()) {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('input[name=radio_choice]').change(function() {
                    var selectedOption = $(this).val();
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        data: {
                            'action': 'wwccs_set_ajax_data',
                            'radio': selectedOption,
                        },
                        success: function(response) {
                            // Optional: You can handle any response from the server here
                        }
                    });
                });
            });
        </script>
        <?php
    }
}

// Set selected radio option in session
add_action('wp_ajax_wwccs_set_ajax_data', 'wwccs_set_selected_radio_option');
add_action('wp_ajax_nopriv_wwccs_set_ajax_data', 'wwccs_set_selected_radio_option');

function wwccs_set_selected_radio_option() {
    if (isset($_POST['radio'])) {
        $radio = sanitize_key($_POST['radio']);
        WC()->session->set('radio_chosen', $radio);
        echo json_encode($radio);
    }
    wp_die(); // Always use wp_die() at the end of AJAX functions to avoid "0" response
}

// Add selected radio option to cart item meta
add_action('woocommerce_add_cart_item_data', 'wwccs_add_radio_option_to_cart_item_data', 10, 3);

function wwccs_add_radio_option_to_cart_item_data($cart_item_data, $product_id, $variation_id) {
    $radio_choice = WC()->session->get('radio_chosen');
    if ($radio_choice) {
        $cart_item_data['radio_choice'] = $radio_choice;
    }
    return $cart_item_data;
}

// Adjust the cart item price based on the selected radio option
add_action('woocommerce_before_calculate_totals', 'wwccs_adjust_cart_item_price', 20, 1);

function wwccs_adjust_cart_item_price($cart) {
    if (is_admin() && !defined('DOING_AJAX')) return;

    foreach ($cart->get_cart() as $cart_item) {
        if (isset($cart_item['radio_choice'])) {
            $radio_choice = $cart_item['radio_choice'];
            $product = $cart_item['data'];

            $original_price = $product->get_regular_price();
            $additional_price = 0;

            if ("add_text" == $radio_choice) {
                $additional_price = 5;
            } elseif ("add_logo" == $radio_choice) {
                $additional_price = 10;
            }

            $new_price = $original_price + $additional_price;
            $product->set_price($new_price);
        }
    }
}
```