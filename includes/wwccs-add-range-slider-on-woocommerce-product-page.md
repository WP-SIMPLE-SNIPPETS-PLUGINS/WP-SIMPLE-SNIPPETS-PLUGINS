# How to Add Range Slider on WooCommerce Product Page?

```generic
function wwccs_add_range_slider_to_product_page() {
    // Check if we are on a WooCommerce product page
    if (is_product()) {
        // Output HTML for range slider above "Add to Cart" button
        ?>
        <div class="size-range-slider-container" style="margin-bottom: 20px;">
            <label for="size-range-slider">Shoe Size Range:</label>
            <input type="range" id="size-range-slider" name="size-range-slider" min="5" max="12" value="8" step="1" style="margin-right: 10px;">
            <input type="text" id="size-range-display" name="size-range-display" readonly style="margin-right: 10px;">
            <input type="hidden" id="selected-size" name="selected_size" value="8">
        </div>
        <script>
            // JavaScript to update range slider and hidden input field
            jQuery(document).ready(function($) {
                var minSize = 5; // Minimum shoe size
                var maxSize = 12; // Maximum shoe size
                var defaultSize = 8; // Default selected shoe size

                // Set minimum and maximum values for the range slider
                $('#size-range-slider').attr('min', minSize);
                $('#size-range-slider').attr('max', maxSize);

                // Set default selected size
                $('#size-range-slider').val(defaultSize);
                $('#size-range-display').val(defaultSize);
                $('#selected-size').val(defaultSize);

                // Update selected size when slider value changes
                $('#size-range-slider').on('input', function() {
                    var selectedSize = $(this).val();
                    $('#size-range-display').val(selectedSize);
                    $('#selected-size').val(selectedSize);
                });
            });
        </script>
        <?php
    }
}
add_action( 'woocommerce_after_add_to_cart_button', 'wwccs_add_range_slider_to_product_page' );
```