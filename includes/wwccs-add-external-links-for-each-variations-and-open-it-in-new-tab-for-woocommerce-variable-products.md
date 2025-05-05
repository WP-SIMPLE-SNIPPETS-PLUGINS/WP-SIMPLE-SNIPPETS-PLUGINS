# How to Add External Links for Each Variations and Open it in New Tab for WooCommerce Variable Products?

```generic
// Add External URL Field and Custom Button Text Field to Product Variations
add_action('woocommerce_variation_options', 'wwccs_add_fields_to_variations', 10, 3);
function wwccs_add_fields_to_variations($loop, $variation_data, $variation) {
    // External URL Field
    woocommerce_wp_text_input(array(
        'id' => "external_url_{$variation->ID}",
        'name' => "external_url_{$variation->ID}",
        'label' => __('External URL', 'woocommerce'),
        'placeholder' => 'https://example.com',
        'value' => get_post_meta($variation->ID, '_external_url', true),
    ));

    // Custom Button Text Field
    woocommerce_wp_text_input(array(
        'id' => "button_text_{$variation->ID}",
        'name' => "button_text_{$variation->ID}",
        'label' => __('Custom Add to Cart Button Text', 'woocommerce'),
        'placeholder' => 'Buy at partnersite.com',
        'value' => get_post_meta($variation->ID, '_button_text', true),
    ));
}

// Save External URL and Custom Button Text Field Value
add_action('woocommerce_save_product_variation', 'wwccs_save_variation_fields', 10, 2);
function wwccs_save_variation_fields($variation_id, $i) {
    $external_url = isset($_POST["external_url_{$variation_id}"]) ? $_POST["external_url_{$variation_id}"] : '';
    $button_text = isset($_POST["button_text_{$variation_id}"]) ? $_POST["button_text_{$variation_id}"] : '';
    
    update_post_meta($variation_id, '_external_url', esc_url($external_url));
    update_post_meta($variation_id, '_button_text', sanitize_text_field($button_text));
}

// Pass External URL and Button Text to the Frontend
add_filter('woocommerce_available_variation', 'wwccs_add_fields_to_variation');
function wwccs_add_fields_to_variation($variation_data) {
    $external_url = get_post_meta($variation_data['variation_id'], '_external_url', true);
    $button_text = get_post_meta($variation_data['variation_id'], '_button_text', true);

    if ($external_url) {
        $variation_data['external_url'] = $external_url; // Add the external URL to the variation data
    }
    if ($button_text) {
        $variation_data['button_text'] = $button_text; // Add the button text to the variation data
    }
    return $variation_data;
}

// JavaScript to handle "Add to Cart" click for external URL and custom button text
add_action('wp_footer', 'wwccs_add_custom_js');
function wwccs_add_custom_js() {
    if (is_product()) {
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            var form = $('form.variations_form');
            var button = $('button.single_add_to_cart_button');

            // Function to update button text
            function updateButton(variation) {
                if (variation && variation.button_text) {
                    button.text(variation.button_text);
                } else {
                    button.text('Add to Cart'); // Default text
                }
            }

            // When a variation is found
            form.on('found_variation', function(event, variation) {
                updateButton(variation);
            });

            // When variations are reset
            form.on('reset_data', function() {
                button.text('Add to Cart'); // Reset to default
            });

            // On page load, check if a variation is selected
            var current_variation = form.find('input[name="variation_id"]').val();
            if (current_variation) {
                var variationData = form.data('product_variations');
                if (variationData) {
                    var variation = variationData.find(function(v) {
                        return v.variation_id == current_variation;
                    });
                    updateButton(variation);
                }
            }

            // When the Add to Cart button is clicked
            form.on('submit', function(event) {
                var variation_id = form.find('input[name="variation_id"]').val();
                var variation = form.data('product_variations') ? form.data('product_variations').find(function(v) {
                    return v.variation_id == variation_id;
                }) : null;

                // Check if the external URL exists
                if (variation && variation.external_url) {
                    event.preventDefault(); // Prevent the default form submission

                    // Open the external URL in a new tab
                    window.open(variation.external_url, '_blank');
                }
                // Else, submit the form normally
            });
        });
        </script>
        <?php
    }
}
```

```generic
// Open the external URL in a new tab
                    window.open(variation.external_url, '_blank');
```

```generic
// Open the external URL in same tab
window.location.href= variation.external_url;
```