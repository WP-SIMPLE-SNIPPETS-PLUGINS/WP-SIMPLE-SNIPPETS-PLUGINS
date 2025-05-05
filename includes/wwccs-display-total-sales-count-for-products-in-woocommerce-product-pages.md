# How to Display Total Sales Count for Products in WooCommerce Product Pages?

```generic
// Enqueue custom CSS for the sales count badge
function custom_sales_badge_styles() {
    ?>
    <style>
        .sales-badge {
            font-size: 14px;
            color: #fff;
            background-color: #e74c3c;
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 10px;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'custom_sales_badge_styles' );

// Add a custom badge for the total sales count on the single product page
add_action( 'woocommerce_single_product_summary', 'display_sales_count_badge_single', 11 );

function display_sales_count_badge_single() {
    global $product;

    // Check if the product exists and has total sales
    if ( $product && $product->get_total_sales() ) {
        $total_sales = $product->get_total_sales();

        // Display the sales badge
        echo '<div class="sales-badge">Sold: ' . esc_html( $total_sales ) . '</div>';
    }
}
```