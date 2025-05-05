# How to Add Custom Product Tabs to WooCommerce Single Product Page?

```generic
add_filter( 'woocommerce_product_tabs', 'wwccs_new_product_tab' ); 
function wwccs_new_product_tab( $tabs ) {
    // Adds the new tab
    $tabs['best_sellers_tab'] = array(
        'title'     => __( 'Best Sellers', 'woocommerce' ),
        'priority'  => 10, // Adjust the priority to change the tab order
        'callback'  => 'wwccs_new_product_tab_content' // The function that will display the tab content
    );

    return $tabs; // Return the modified tabs array
}

function wwccs_new_product_tab_content() {
    echo '<h2>' . __( 'Best Sellers', 'woocommerce' ) . '</h2>';

    echo '<ul>';
    
    // Product titles
    $products = array( 'Apple iPhone', 'Beast Earbuds', 'Tshirt', 'Travel Pro Rucksack 40L-Grey', 'Android SmartPhone 5G' );

    // Loop through each product name and get the permalink by title
    foreach ( $products as $product_title ) {
        $product = get_page_by_title( $product_title, OBJECT, 'product' );

        if ( $product ) {
            // Display product title with a link to its product page
            echo '<li><a href="' . esc_url( get_permalink( $product->ID ) ) . '">' . esc_html( $product_title ) . '</a></li>';
        } else {
            // If the product is not found, show fallback text
            echo '<li>' . esc_html( $product_title ) . ' - Product not found</li>';
        }
    }

    echo '</ul>';
}
```