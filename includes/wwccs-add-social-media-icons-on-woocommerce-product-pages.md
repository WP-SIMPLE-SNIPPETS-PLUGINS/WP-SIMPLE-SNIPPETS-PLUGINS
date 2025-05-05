# How to Add Social Media Icons on WooCommerce Product Pages?

```generic
// Hook to display social media buttons after the "Add to Cart" button
add_action('woocommerce_before_add_to_cart_button', 'my_social_btn');

function my_social_btn() {
    global $wp;
    $current_url = home_url(add_query_arg(array(), $wp->request));
    echo '<div class="my-custom-social" style="display: flex; justify-content: flex-start;">';
    echo '<a href="https://www.facebook.com/sharer/sharer.php?u=' . $current_url . '" class="social fb" target="_blank">
            <img src="http://localhost/wpsite/wordpress/wp-content/uploads/2024/06/facebook.png" alt="Facebook" class="social-icon" style="margin-right: 10px;">
        </a>';
    echo '<a href="https://twitter.com/intent/tweet?url=' . $current_url . '" class="social tw" target="_blank">
            <img src="http://localhost/wpsite/wordpress/wp-content/uploads/2024/06/twitter.png" alt="Twitter" class="social-icon" style="margin-right: 10px;">
        </a>';
    echo '<a href="https://www.instagram.com" target="_blank" class="social ig">
            <img src="http://localhost/wpsite/wordpress/wp-content/uploads/2024/06/insta.jpg" alt="Instagram" class="social-icon" style="margin-right: 10px;">
        </a>';
    echo '<a href="https://wa.me/1234567890" target="_blank" class="social wa">
            <img src="http://localhost/wpsite/wordpress/wp-content/uploads/2024/06/whatsapp.jpg" alt="WhatsApp" class="social-icon">
        </a>';
    echo '</div>';
}
```