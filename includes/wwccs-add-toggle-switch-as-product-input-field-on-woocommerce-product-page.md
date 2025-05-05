# How to Add Toggle Switch as Product Input Field on WooCommerce Product Page?

```generic
// Add Toggle Switch to WooCommerce Product Page
add_action( 'woocommerce_before_add_to_cart_button', 'wwccs_add_toggle_switch_to_product_page' );
function wwccs_add_toggle_switch_to_product_page() {
    ?>
    <style>
        /* CSS for Toggle Switch */
        .toggle-switch-field {
            text-align: left; /* Align content to the left */
            margin-bottom: 20px; /* Add margin at the bottom */
        }
        .toggleButtonHandles {
            display: flex;
            align-items: center;
        }
        .toggleButton {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
            margin-right: 10px; /* Add some spacing between the toggle switch and the text */
        }
        .toggleButtonSwitch {
            padding: 0 10px;
        }
        .toggleButton input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
        }
        .toggleContent {
            display: none;
        }
        .toggleContent.active {
            display: block;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
        }
        input:checked + .slider {
            background-color: #2196F3;
        }
        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }
        input:checked + .slider:before {
            transform: translateX(26px);
        }
        .slider.round {
            border-radius: 34px;
        }
        .slider.round:before {
            border-radius: 50%;
        }
        .afterToggleLabel {
            font-size: 26px; /* Increase font size */
        }
    </style>

    <div class="toggle-switch-field">
        <div class="toggleButtonHandles">
            <div class="toggleButtonSwitch">
                <label class="toggleButton">
                    <input type="checkbox">
                    <span class="slider round"></span>
                </label>
            </div>
            <div class="afterToggleLabel">Send Notification on Price Drop</div>
        </div>
    </div>

    <script>
        // JavaScript for Toggle Switch Functionality
        jQuery(document).ready(function($) {
            $( '.toggleButton input' ).click(function() {
                $('.toggleContent').toggleClass('active');
            });
        });
    </script>
    <?php
}
```