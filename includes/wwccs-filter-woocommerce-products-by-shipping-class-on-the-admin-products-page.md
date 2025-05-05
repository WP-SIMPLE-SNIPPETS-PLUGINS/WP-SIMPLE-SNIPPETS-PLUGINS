# How to Filter WooCommerce Products by Shipping Class on the Admin Products Page?

```generic
class wwccs_AddShippingClassColumn {
    // Column key and title.
    private $column_key = 'shipping_class';
    private $column_title = 'Shipping <br/>Class';

    // Returns an instance of this class.
    public static function get_instance() {
        static $instance = null;
        if ( null === $instance ) {
            $instance = new self;
        }
        return $instance;
    }

    // Initialize the plugin variables.
    public function __construct() {
        $this->wwccs_init();
    }

    // Set up WordPress specific actions.
    public function wwccs_init() {
        // Add the new column.
        add_filter( 'manage_product_poswwccs_columns', array( $this, 'wwccs_set_custom_column' ), 20 );
        // Populate the new column with the shipping class name.
        add_action( 'manage_product_poswwccs_custom_column' , array( $this, 'wwccs_populate_custom_column' ), 10, 2 );
        // Add CSS to ensure new column width is not distorted.
        add_action( 'admin_head', array( $this, 'wwccs_add_column_css' ) );
        // Add the filter dropdown.
        add_action( 'restrict_manage_posts', array( $this, 'wwccs_filter_by_shipping_class' ) );
        // Apply the filter query.
        add_filter( 'pre_get_posts', array( $this, 'wwccs_filter_producwwccs_by_shipping_class_query' ) );
    }

    // Add the new column.
    public function wwccs_set_custom_column( $columns ) {
        // Add the new column after the category column.
        $insert_after = 'product_cat';
        if ( isset( $insert_after ) ) {
            $position = array_search( $insert_after, array_keys( $columns ) );
            if ( false !== $position ) {
                $before = $columns;
                $after = $columns;
                array_splice( $before, $position + 1 );
                array_splice( $after, 0, $position + 1 );
                $before[ $this->column_key ] = $this->column_title;
                $columns = array_merge( $before, $after );
            }
        } else {
            // Otherwise add the new column at the end.
            $columns[ $this->column_key ] = $this->column_title;
        }

        return $columns;
    }

    // Populate the new column with the shipping class name.
    public function wwccs_populate_custom_column( $column, $post_id ) {
        if ( 'shipping_class' === $column ) {
            $product = wc_get_product( $post_id );
            $class_id = $product->get_shipping_class_id();
            if ( $class_id ) {
                $term = get_term_by( 'id', $class_id, 'product_shipping_class' );
                if ( $term && ! is_wp_error( $term ) ) {
                    echo esc_html( $term->name );
                } else {
                    echo '<span class="na">&ndash;</span>'; // No shipping class.
                }
            } else {
                echo '<span class="na">&ndash;</span>'; // No shipping class.
            }
        }
    }

    // Add CSS to ensure new column width is not distorted.
    public function wwccs_add_column_css() {
        $currentScreen = get_current_screen();
        if ( isset( $currentScreen ) && 'edit-product' === $currentScreen->id ) {
            ?>
            <style>
                table.wp-list-table .column-shipping_class {
                    width: 11% !important;
                }
            </style>
            <?php
        }
    }

    // Add a filter dropdown for shipping class.
    public function wwccs_filter_by_shipping_class() {
        if ( 'product' === get_post_type() ) {
            $shipping_classes = get_terms(array(
                'taxonomy' => 'product_shipping_class',
                'hide_empty' => false,
            ));
            $current_class = isset($_GET['shipping_class']) ? $_GET['shipping_class'] : '';
            echo '<select name="shipping_class" id="shipping_class">';
            echo '<option value="">Select Shipping Class</option>';
            foreach ($shipping_classes as $class) {
                printf(
                    '<option value="%s"%s>%s</option>',
                    esc_attr($class->term_id),
                    selected($current_class, $class->term_id, false),
                    esc_html($class->name)
                );
            }
            echo '</select>';
        }
    }

    // Apply the shipping class filter.
    public function wwccs_filter_producwwccs_by_shipping_class_query($query) {
        global $pagenow;
        if ('edit.php' === $pagenow && 'product' === $query->query['post_type']) {
            if (!empty($_GET['shipping_class'])) {
                $query->set('tax_query', array(
                    array(
                        'taxonomy' => 'product_shipping_class',
                        'field' => 'term_id',
                        'terms' => intval($_GET['shipping_class']),
                    ),
                ));
            }
        }
    }
}

// Instantiate the class.
wwccs_AddShippingClassColumn::get_instance();
```