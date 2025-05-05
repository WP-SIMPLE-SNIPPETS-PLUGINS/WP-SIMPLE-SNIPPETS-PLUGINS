# How to Display Custom Field Values of Product Variations in a Custom Product Tab in WooCommerce?

```generic
/* Add custom field input @ Product Data > Variations > Single Variation */
add_action('woocommerce_variation_options', 'add_custom_field_laptop_specs_to_variations', 10, 3);
function add_custom_field_laptop_specs_to_variations($loop, $variation_data, $variation) {
    echo '<div class="options_group">';
    
    // Model Number
    woocommerce_wp_text_input(array(
        'id' => "model_number_{$loop}",
        'class' => 'short',
        'label' => __('Model Number', 'woocommerce'),
        'value' => get_post_meta($variation->ID, 'model_number', true)
    ));

    // Processor (e.g., Intel i7, Apple M2)
    woocommerce_wp_text_input(array(
        'id' => "processor_{$loop}",
        'class' => 'short',
        'label' => __('Processor', 'woocommerce'),
        'value' => get_post_meta($variation->ID, 'processor', true)
    ));

    // RAM Capacity
    woocommerce_wp_text_input(array(
        'id' => "ram_{$loop}",
        'class' => 'short',
        'label' => __('RAM Capacity', 'woocommerce'),
        'value' => get_post_meta($variation->ID, 'ram', true)
    ));

    // Storage Capacity
    woocommerce_wp_text_input(array(
        'id' => "storage_{$loop}",
        'class' => 'short',
        'label' => __('Storage Capacity', 'woocommerce'),
        'value' => get_post_meta($variation->ID, 'storage', true)
    ));

    // Graphics Card
    woocommerce_wp_text_input(array(
        'id' => "graphics_card_{$loop}",
        'class' => 'short',
        'label' => __('Graphics Card', 'woocommerce'),
        'value' => get_post_meta($variation->ID, 'graphics_card', true)
    ));

    // Display Size and Resolution
    woocommerce_wp_text_input(array(
        'id' => "display_size_{$loop}",
        'class' => 'short',
        'label' => __('Display Size and Resolution', 'woocommerce'),
        'value' => get_post_meta($variation->ID, 'display_size', true)
    ));

    echo '</div>';
}

/* Save custom field on product variation save */
add_action('woocommerce_save_product_variation', 'save_custom_field_variations', 10, 2);
function save_custom_field_variations($variation_id, $i) {
    if (isset($_POST["model_number_{$i}"])) {
        update_post_meta($variation_id, 'model_number', sanitize_text_field($_POST["model_number_{$i}"]));
    }
    if (isset($_POST["processor_{$i}"])) {
        update_post_meta($variation_id, 'processor', sanitize_text_field($_POST["processor_{$i}"]));
    }
    if (isset($_POST["ram_{$i}"])) {
        update_post_meta($variation_id, 'ram', sanitize_text_field($_POST["ram_{$i}"]));
    }
    if (isset($_POST["storage_{$i}"])) {
        update_post_meta($variation_id, 'storage', sanitize_text_field($_POST["storage_{$i}"]));
    }
    if (isset($_POST["graphics_card_{$i}"])) {
        update_post_meta($variation_id, 'graphics_card', sanitize_text_field($_POST["graphics_card_{$i}"]));
    }
    if (isset($_POST["display_size_{$i}"])) {
        update_post_meta($variation_id, 'display_size', sanitize_text_field($_POST["display_size_{$i}"]));
    }
}

/* Add a custom product data tab */
add_filter('woocommerce_product_tabs', 'add_laptop_specs_tab');
function add_laptop_specs_tab($tabs) {
    $tabs['laptop_specs'] = array(
        'title' => __('Laptop Specifications', 'woocommerce'),
        'priority' => 50,
        'callback' => 'display_laptop_specs_tab_content'
    );
    return $tabs;
}

/* Display custom tab content */
function display_laptop_specs_tab_content() {
    global $product;

    if ($product->is_type('variable')) {
        foreach ($product->get_children() as $variation_id) {
            $variation = wc_get_product($variation_id);

            // Get custom specs data
            $model_number = $variation->get_meta('model_number');
            $processor = $variation->get_meta('processor');
            $ram = $variation->get_meta('ram');
            $storage = $variation->get_meta('storage');
            $graphics_card = $variation->get_meta('graphics_card');
            $display_size = $variation->get_meta('display_size');

            // Display the variation specs
            echo '<div class="variation_laptop_specs_info" id="variation-' . esc_attr($variation_id) . '" style="display:none;">';
            if ($model_number) echo '<p><strong>' . __('Model Number:', 'woocommerce') . '</strong> ' . esc_html($model_number) . '</p>';
            if ($processor) echo '<p><strong>' . __('Processor:', 'woocommerce') . '</strong> ' . esc_html($processor) . '</p>';
            if ($ram) echo '<p><strong>' . __('RAM Capacity:', 'woocommerce') . '</strong> ' . esc_html($ram) . '</p>';
            if ($storage) echo '<p><strong>' . __('Storage Capacity:', 'woocommerce') . '</strong> ' . esc_html($storage) . '</p>';
            if ($graphics_card) echo '<p><strong>' . __('Graphics Card:', 'woocommerce') . '</strong> ' . esc_html($graphics_card) . '</p>';
            if ($display_size) echo '<p><strong>' . __('Display Size and Resolution:', 'woocommerce') . '</strong> ' . esc_html($display_size) . '</p>';
            echo '</div>';
        }
        ?>
        <script>
            jQuery(document).ready(function ($) {
                $('.variation_laptop_specs_info').hide();

                $('input.variation_id').on('change', function () {
                    $('.variation_laptop_specs_info').hide();
                    let variationId = $(this).val();
                    $('#variation-' + variationId).show();
                });
            });
        </script>
        <?php
    }
}
```