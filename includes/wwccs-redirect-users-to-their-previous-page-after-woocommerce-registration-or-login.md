# How to Redirect Users to Their Previous Page After WooCommerce Registration or Login?

```generic
// Capture the referrer URL before login or registration
function wwccs_capture_referrer_url_for_auth() {
    if ( isset( $_SERVER['HTTP_REFERER'] ) && !empty( $_SERVER['HTTP_REFERER'] ) && !strstr( $_SERVER['HTTP_REFERER'], 'my-account' ) ) {
        set_transient( 'auth_referrer_url', esc_url( $_SERVER['HTTP_REFERER'] ), 60 * 60 * 24 ); // Store for 24 hours
    }
}
add_action( 'woocommerce_before_customer_login_form', 'wwccs_capture_referrer_url_for_auth' );
add_action( 'woocommerce_register_form', 'wwccs_capture_referrer_url_for_auth' );

// Redirect the user after login to the referrer URL
add_filter( 'woocommerce_login_redirect', 'wwccs_redirect_after_login_to_referrer', 10, 2 );
function wwccs_redirect_after_login_to_referrer( $redirect, $user ) {
    $referrer_url = get_transient( 'auth_referrer_url' );
    
    if ( $referrer_url ) {
        delete_transient( 'auth_referrer_url' ); // Clean up the transient after use
        return $referrer_url; // Redirect to the referrer URL
    }

    return $redirect; // Fallback to default WooCommerce redirect if no referrer
}

// Redirect the user after registration to the referrer URL
add_filter( 'woocommerce_registration_redirect', 'wwccs_redirect_after_registration_to_referrer' );
function wwccs_redirect_after_registration_to_referrer( $redirect ) {
    $referrer_url = get_transient( 'auth_referrer_url' );

    if ( $referrer_url ) {
        delete_transient( 'auth_referrer_url' ); // Clean up the transient after use
        return $referrer_url; // Redirect to the referrer URL
    }

    return $redirect; // Fallback to default WooCommerce redirect if no referrer
}
```