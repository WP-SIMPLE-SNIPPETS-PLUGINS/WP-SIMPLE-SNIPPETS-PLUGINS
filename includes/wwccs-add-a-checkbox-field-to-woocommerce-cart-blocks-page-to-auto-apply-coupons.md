# How to Add a Checkbox to WooCommerce Cart Blocks to Auto-Apply Coupons?

```generic
// Listen for updates from the Cart Blocks page to apply or remove the discount coupon
add_action( 'woocommerce_blocks_loaded', function() {
    woocommerce_store_api_register_update_callback(
        [
            'namespace' => 'custom-cart-discount',
            'callback'  => function( $data ) {
                $coupon_code = '10PERCENTOFF'; // Ensure this coupon exists in WooCommerce
                
                if ( isset( $data['checked'] ) && filter_var( $data['checked'], FILTER_VALIDATE_BOOLEAN ) === true ) {
                    WC()->cart->apply_coupon( $coupon_code );
                } else {
                    WC()->cart->remove_coupon( $coupon_code );
                }
            }
        ]
    );
});

// Enqueue inline JavaScript to add the discount checkbox below the product table on the Cart Blocks page
function wwccs_custom_cart_inline_script() {
    if ( is_cart() ) {
        wp_add_inline_script(
            'wc-blocks-checkout', // Ensure this handle is present; you may replace it with 'jquery' if needed.
            "
            document.addEventListener('DOMContentLoaded', function () {
                // Function to insert the discount checkbox after the product table
                function insertDiscountCheckbox() {
                    // Look for the product table in the cart blocks container
                    const cartTable = document.querySelector('.wc-block-cart table');
                    if (cartTable && !document.getElementById('apply_discount')) {
                        // Create checkbox and label elements
                        const checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.id = 'apply_discount';
                        checkbox.style.marginRight = '10px';
                        
                        const label = document.createElement('label');
                        label.htmlFor = 'apply_discount';
                        label.textContent = 'Get 10% off instantly when you complete your purchase today!';
                        label.style.marginRight = '20px';
                        
                        // Insert them immediately after the product table
                        cartTable.insertAdjacentElement('afterend', checkbox);
                        cartTable.insertAdjacentElement('afterend', label);
                        
                        // Add event listener for checkbox changes
                        checkbox.addEventListener('change', function () {
                            if (window.wc && window.wc.blocksCheckout) {
                                const { extensionCartUpdate } = window.wc.blocksCheckout;
                                extensionCartUpdate({
                                    namespace: 'custom-cart-discount',
                                    data: {
                                        checked: checkbox.checked
                                    }
                                });
                            }
                        });
                    }
                }
                
                // Use a MutationObserver to watch for when the cart table is rendered
                const observer = new MutationObserver(function(mutations, obs) {
                    if (document.querySelector('.wc-block-cart table')) {
                        insertDiscountCheckbox();
                        obs.disconnect(); // Stop observing once inserted
                    }
                });
                
                // Start observing the cart container for changes
                const cartContainer = document.querySelector('.wc-block-cart');
                if (cartContainer) {
                    observer.observe(cartContainer, {
                        childList: true,
                        subtree: true
                    });
                } else {
                    // Fallback: if cart container is not found, try again after a delay
                    setTimeout(insertDiscountCheckbox, 1000);
                }
            });
            ",
            'after'
        );
    }
}
add_action( 'wp_enqueue_scripts', 'wwccs_custom_cart_inline_script' );
```