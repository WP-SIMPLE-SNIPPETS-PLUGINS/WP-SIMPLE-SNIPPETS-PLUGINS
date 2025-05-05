# How to Hide 'Out of Stock' Products in WooCommerce?

```generic
add_action('woocommerce_product_query', 'wwccs_show_only_instock_products');

function wwccs_show_only_instock_products($query) {
        $meta_query = $query->get('meta_query');
        $meta_query[] = array(
                'key'       => '_stock_status',
                'compare'   => '=',
                'value'     => 'instock'
        );
        $query->set('meta_query', $meta_query);
}
```