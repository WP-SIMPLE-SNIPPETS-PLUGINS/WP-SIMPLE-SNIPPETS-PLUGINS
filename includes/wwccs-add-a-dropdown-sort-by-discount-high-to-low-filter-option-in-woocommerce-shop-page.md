# How to Add a Dropdown "Sort by Discount: High to Low" Filter Option in WooCommerce Shop page

```generic
// Add "Sort by Discount: High to Low" option in the sorting dropdown
function add_custom_discount_sorting_option( $sorting_options ) {
    $sorting_options['discount_high'] = __( 'Sort by Discount: High to Low', 'woocommerce' );
    return $sorting_options;
}
add_filter( 'woocommerce_default_catalog_orderby_options', 'add_custom_discount_sorting_option' );
add_filter( 'woocommerce_catalog_orderby', 'add_custom_discount_sorting_option' );

// Use poswwccs_clauses to dynamically join price meta and sort by discount
function wwccs_add_discount_sorting_clauses( $clauses ) {
    if ( isset( $_GET['orderby'] ) && 'discount_high' === $_GET['orderby'] ) {
        global $wpdb;

        // Join to fetch the regular and sale prices
        $clauses['join'] .= " 
            LEFT JOIN {$wpdb->postmeta} AS meta_regular 
                ON ({$wpdb->posts}.ID = meta_regular.post_id AND meta_regular.meta_key = '_regular_price')
            LEFT JOIN {$wpdb->postmeta} AS meta_sale 
                ON ({$wpdb->posts}.ID = meta_sale.post_id AND meta_sale.meta_key = '_sale_price')
        ";

        // Use a CASE statement to calculate discount:
        // If sale price exists and is lower than regular price, calculate the discount percentage.
        // Otherwise, return -1 so that non-discounted products are pushed to the bottom.
        $clauses['orderby'] = "
            CASE 
                WHEN meta_sale.meta_value IS NOT NULL 
                     AND meta_sale.meta_value != '' 
                     AND CAST(meta_sale.meta_value AS DECIMAL(10,2)) < CAST(meta_regular.meta_value AS DECIMAL(10,2))
                THEN ((CAST(meta_regular.meta_value AS DECIMAL(10,2)) - CAST(meta_sale.meta_value AS DECIMAL(10,2))) / CAST(meta_regular.meta_value AS DECIMAL(10,2)))
                ELSE -1
            END DESC
        ";
    }
    return $clauses;
}
add_filter( 'poswwccs_clauses', 'wwccs_add_discount_sorting_clauses' );
```