# How to Add Custom Icons to Radio Button Input Fields in WooCommerce?

```generic
// Load FontAwesome
function load_font_awesome() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
}
add_action('wp_enqueue_scripts', 'load_font_awesome');

// Display Radio Buttons on Product Page
add_action('woocommerce_before_add_to_cart_button', 'wwccs_product_subscription_plans');

function wwccs_product_subscription_plans() {
    $chosen = WC()->session->get('subscription_plan_chosen');
    $chosen = empty($chosen) ? 'basic_plan' : $chosen;

    $options = array(
    'basic_plan'    => '<i class="fas fa-check-circle" style="vertical-align:middle; margin-right: 8px; color: orange;"></i>Basic Plan ($5)',
    'advanced_plan' => '<i class="fas fa-star" style="vertical-align:middle; margin-right: 8px; color:  orange;"></i>Advanced Plan ($10)',
    'premium_plan'  => '<i class="fas fa-crown" style="vertical-align:middle; margin-right: 8px; color:  orange;"></i>Premium Plan ($15)',
);

    echo '<div id="subscription-plans">';
    echo '<h3>Select a membership plan to access discounts!</h3>';
    
    foreach ($options as $key => $label) {
        $checked = $chosen === $key ? 'checked' : '';
        echo '<label style="display:block; margin-bottom: 8px;">';
        echo '<input type="radio" name="subscription_plan" value="' . esc_attr($key) . '" ' . $checked . '> ';
        echo $label;
        echo '</label>';
    }

    echo '</div>';
}

// Capture selected radio option on the product page and send it to the server
add_action('wp_footer', 'wwccs_capture_selected_subscription_plan');

function wwccs_capture_selected_subscription_plan() {
    if (is_product()) {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('input[name=subscription_plan]').change(function() {
                    var selectedOption = $(this).val();
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        data: {
                            'action': 'wwccs_set_subscription_plan_data',
                            'subscription_plan': selectedOption,
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
add_action('wp_ajax_wwccs_set_subscription_plan_data', 'wwccs_set_subscription_plan_option');
add_action('wp_ajax_nopriv_wwccs_set_subscription_plan_data', 'wwccs_set_subscription_plan_option');

function wwccs_set_subscription_plan_option() {
    if (isset($_POST['subscription_plan'])) {
        $subscription_plan = sanitize_key($_POST['subscription_plan']);
        WC()->session->set('subscription_plan_chosen', $subscription_plan);
        echo json_encode($subscription_plan);
    }
    wp_die(); // Always use wp_die() at the end of AJAX functions to avoid "0" response
}

// Add selected radio option to cart item meta
add_action('woocommerce_add_cart_item_data', 'wwccs_add_subscription_plan_to_cart_item_data', 10, 3);

function wwccs_add_subscription_plan_to_cart_item_data($cart_item_data, $product_id, $variation_id) {
    $subscription_plan = WC()->session->get('subscription_plan_chosen');
    if ($subscription_plan) {
        $cart_item_data['subscription_plan'] = $subscription_plan;
    }
    return $cart_item_data;
}

// Adjust the cart item price based on the selected radio option
add_action('woocommerce_before_calculate_totals', 'wwccs_adjust_cart_item_price', 20, 1);

function wwccs_adjust_cart_item_price($cart) {
    if (is_admin() && !defined('DOING_AJAX')) return;

    foreach ($cart->get_cart() as $cart_item) {
        if (isset($cart_item['subscription_plan'])) {
            $subscription_plan = $cart_item['subscription_plan'];
            $product = $cart_item['data'];

            $original_price = $product->get_regular_price();
            $additional_price = 0;

            if ("basic_plan" == $subscription_plan) {
                $additional_price = 5;
            } elseif ("advanced_plan" == $subscription_plan) {
                $additional_price = 10;
            } elseif ("premium_plan" == $subscription_plan) {
                $additional_price = 15;
            }

            $new_price = $original_price + $additional_price;
            $product->set_price($new_price);
        }
    }
}
```