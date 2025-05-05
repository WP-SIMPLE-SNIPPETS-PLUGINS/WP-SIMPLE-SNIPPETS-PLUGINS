# How to Add Phone Number Mask to Phone Input Field on WooCommerce Product Page?

```generic
function wwccs_enqueue_phone_mask_script() {
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'intl-tel-input', 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js', array( 'jquery' ), '', true );
    wp_enqueue_style( 'intl-tel-input-css', 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css' );
    wp_enqueue_script( 'intl-tel-input-utils', 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js', array( 'intl-tel-input' ), '', true );
}
add_action( 'wp_enqueue_scripts', 'wwccs_enqueue_phone_mask_script' );

add_action( 'woocommerce_before_add_to_cart_button', 'wwccs_add_phone_number_field_to_product_page', 9 );
function wwccs_add_phone_number_field_to_product_page() {
    echo '<div class="custom-phone-field">';
    woocommerce_form_field( 'phone_number', array(
        'type' => 'tel',
        'required' => true,
        'label' => 'Phone Number',
        'placeholder' => 'Enter your phone number...',
        'class' => array('input-text', 'custom-phone-field'),
    ));
    echo '</div>';
}

add_action( 'wp_footer', 'wwccs_initialize_phone_number_mask' );
function wwccs_initialize_phone_number_mask() {
    ?>
    <script>
    jQuery(document).ready(function($) {
        var input = $("#phone_number");
        var iti = window.intlTelInput(input[0], {
            initialCountry: "auto",
            geoIpLookup: function(callback) {
                $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "us";
                    callback(countryCode);
                });
            },
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js" // Load utils.js for formatting and validation
        });

        // Format phone number on blur event
        input.on('blur', function() {
            var formattedNumber = iti.getNumber();
            input.val(formattedNumber);
        });
    });
    </script>
    <?php
}
```