# How to Add Custom Input Field to a Stock Status Column on the WooCommerce Admin Products Page?

```generic
// Add input fields to stock status column
add_filter('woocommerce_admin_stock_html', 'wwccs_filter_woocommerce_admin_stock_html', 10, 2 );
function wwccs_filter_woocommerce_admin_stock_html($stock_html, $product) {
    if ( $product->managing_stock() ) {
        return '';
    }
    return $stock_html;
}

// Add input fields to stock status column
add_action( 'manage_product_poswwccs_custom_column', 'wwccs_product_stock_quantity_column_content', 10, 2 );
function wwccs_product_stock_quantity_column_content( $column, $product_id ) {
    if ( $column === 'is_in_stock' ) {
        global $product;

        if ( $product->managing_stock() ) {
            $stock_html = sprintf('<div style="margin-bottom:5px;width:120px">
            <input type="number" name="stock_qty-%d" value="%d" style="width:80px">
            <button type="button" class="update-qty button button-primary" data-id="%d">↻</button>
            </div><div class="stock-%d">', 
            $product_id, $product->get_stock_quantity('edit'), $product_id, $product_id, $product_id);

            if ( $product->is_on_backorder() ) {
                $stock_html .= '<mark class="onbackorder">' . __( 'On backorder', 'woocommerce' ) . '</mark>';
            } elseif ( $product->is_in_stock() ) {
                $stock_html .= '<mark class="instock">' . __( 'In stock', 'woocommerce' ) . '</mark>';
            } else {
                $stock_html .= '<mark class="outofstock">' . __( 'Out of stock', 'woocommerce' ) . '</mark>';
            }
            echo $stock_html .' (' . wc_stock_amount( $product->get_stock_quantity() ) . ')</div>';
        }
    }     
}

// WP Admin Ajax receiver
add_action('wp_ajax_update_stock_quantity', 'wwccs_update_stock_quantity_ajax');
function wwccs_update_stock_quantity_ajax() {
    if (isset($_POST['product_id']) && isset($_POST['update_qty'])) {
        $product = wc_get_product(intval($_POST['product_id']));

        $product->set_stock_quantity(intval($_POST['update_qty']));
        $id = $product->save();

        if ( $product->is_on_backorder() ) {
            $stock_html = '<mark class="onbackorder">' . __( 'On backorder', 'woocommerce' ) . '</mark>';
        } elseif ( $product->is_in_stock() ) {
            $stock_html = '<mark class="instock">' . __( 'In stock', 'woocommerce' ) . '</mark>';
        } else {
            $stock_html = '<mark class="outofstock">' . __( 'Out of stock', 'woocommerce' ) . '</mark>';
        }
        $stock_html .= ' (' . wc_stock_amount( $product->get_stock_quantity() ) . ')';

        echo $stock_html;
    }
    wp_die(); // Exit silently (Always at the end to avoid an Error 500)
}

// jQuery Ajax
add_action('admin_footer', 'wwccs_update_stock_quantity_js');
function wwccs_update_stock_quantity_js() {
    global $pagenow, $typenow;

    if( 'edit.php' === $pagenow && 'product' === $typenow ) :
    ?>
    <script id="update-stock-qty" type="text/javascript">
        jQuery(function($) {
            $('body').on('click', 'button.update-qty', function() {
                const productID = $(this).data('id'),
                      updateQty = $('input[name=stock_qty-'+productID+']').val();
                $.ajax({
                    url:  '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                    type: 'POST',
                    data: {
                        'action':     'update_stock_quantity',
                        'product_id': productID,
                        'update_qty': updateQty,
                    },
                    success: function(response) {
                        if ( response ) {
                            const message = '<div class="message-'+productID+'">Success !</div>';
                            $('.stock-'+productID).html(response).after();
                            $('.stock-'+productID).prepend(message);
                            setTimeout(function(){
                                $('.message-'+productID).remove();
                            }, 5000);
                        }
                    },
                    error: function(error) {
                        if ( error ) {
                            const message = '<div class="message-'+productID+'">Error !</div>';
                            $('.stock-'+productID).prepend(message);
                            setTimeout(function(){
                                $('.message-'+productID).remove();
                            }, 5000);
                        }
                    }
                });
            });
        });
    </script>
    <?php
    endif;
}
```