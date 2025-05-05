# How to Add a Dropdown Field to Filter by Brands on the WooCommerce Shop Page?

```generic
function wwccs_display_brand_filter_dropdown() {
    if ( is_shop() || is_product_category() || is_product_taxonomy() ) {
        $brands = get_terms( array(
            'taxonomy'   => 'product_brand',
            'hide_empty' => true,
        ));

        if ( ! empty( $brands ) ) {
            echo '<form method="GET" action="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '">';
            echo '<select name="filter_brand" onchange="this.form.submit();">';
            echo '<option value="">' . esc_html__( 'Filter by Brand', 'woocommerce' ) . '</option>';
            
            foreach ( $brands as $brand ) {
                $selected = ( isset( $_GET['filter_brand'] ) && $_GET['filter_brand'] == $brand->slug ) ? 'selected' : '';
                echo '<option value="' . esc_attr( $brand->slug ) . '" ' . $selected . '>' . esc_html( $brand->name ) . '</option>';
            }

            echo '</select>';
            echo '</form>';
        }
    }
}
add_action( 'woocommerce_before_shop_loop', 'wwccs_display_brand_filter_dropdown' );
function wwccs_filter_producwwccs_by_brand( $query ) {
    if ( ! is_admin() && $query->is_main_query() && ( is_shop() || is_product_category() || is_product_taxonomy() ) ) {
        if ( isset( $_GET['filter_brand'] ) && ! empty( $_GET['filter_brand'] ) ) {
            $query->set( 'tax_query', array(
                array(
                    'taxonomy' => 'product_brand',
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field( $_GET['filter_brand'] ),
                ),
            ));
        }
    }
}
add_action( 'pre_get_posts', 'wwccs_filter_producwwccs_by_brand' );
```