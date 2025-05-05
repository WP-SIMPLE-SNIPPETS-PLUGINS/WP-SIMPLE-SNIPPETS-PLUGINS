# How to Save and Display Terms Acceptance in WooCommerce Orders?

```generic
add_action('woocommerce_checkout_update_order_meta', 'wwccs_save_terms_consent_to_order', 10, 1);
function wwccs_save_terms_consent_to_order($order_id) {
    if (!empty($_POST['terms'])) { // Ensure the checkbox is checked
        $order = wc_get_order($order_id);
        $customer_name = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
        $consent_text = sprintf('%s accepted the Terms and Conditions on %s',
            $customer_name,
            current_time('Y-m-d H:i:s')
        );

        // Force save using WooCommerce order meta methods
        $order->update_meta_data('_terms_consent', $consent_text);
        $order->save(); // Ensure data is stored
    }
}

// Display Terms & Conditions consent in WooCommerce Admin Order Details
add_action('woocommerce_admin_order_data_after_billing_address', 'wwccs_display_terms_consent_in_admin');
function wwccs_display_terms_consent_in_admin($order) {
    $consent_text = get_post_meta($order->get_id(), '_terms_consent', true);
    if ($consent_text) {
        echo '<p><strong>Terms Consent:</strong> ' . esc_html($consent_text) . '</p>';
    }
}

// Display Terms & Conditions consent in WooCommerce Order Emails
add_action('woocommerce_email_after_order_table', 'wwccs_add_terms_consent_to_emails', 10, 4);
function wwccs_add_terms_consent_to_emails($order, $sent_to_admin, $plain_text, $email) {
    $consent_text = get_post_meta($order->get_id(), '_terms_consent', true);
    if ($consent_text) {
        if ($plain_text) {
            echo "\nTerms Consent: " . $consent_text . "\n";
        } else {
            echo '<p><strong>Terms Consent:</strong> ' . esc_html($consent_text) . '</p>';
        }
    }
}

// Display Terms & Conditions consent in Customer's Account Order Details
add_action('woocommerce_order_details_after_order_table', 'wwccs_display_terms_consent_in_account');
function wwccs_display_terms_consent_in_account($order) {
    $consent_text = get_post_meta($order->get_id(), '_terms_consent', true);
    if ($consent_text) {
        echo '<p><strong>Terms Consent:</strong> ' . esc_html($consent_text) . '</p>';
    }
}
```