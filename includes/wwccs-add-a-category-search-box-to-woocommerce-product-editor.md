# How to Add a Category Search Box to WooCommerce Product Editor?

```generic
// Add a Category Search Box to WooCommerce Product Editor
function wwccs_add_category_search_field() {
    global $pagenow;
    if ( $pagenow === 'post.php' || $pagenow === 'post-new.php' ) {
        // Internationalized strings
        $search_placeholder = esc_attr__( 'Search categories...', 'your-text-domain' );
        $clear_search_text  = esc_html__( 'Clear Search', 'your-text-domain' );

        ?>
        <style type="text/css">
            .category-search {
                margin-bottom: 10px;
                width: 100%;
            }
            .clear-category-search {
                margin-bottom: 10px;
            }
        </style>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                // Target both default and product category boxes
                var categoryBoxes = $('#categorydiv .inside, #product_catdiv .inside');

                categoryBoxes.each(function() {
                    var categoryBox = $(this);
                    // Add search field and 'Clear Search' button with accessibility features
                    categoryBox.prepend(`
                        <input type="text" class="category-search" placeholder="<?php echo $search_placeholder; ?>" aria-label="<?php echo $search_placeholder; ?>" />
                        <button type="button" class="button clear-category-search" aria-label="<?php echo $clear_search_text; ?>"><?php echo $clear_search_text; ?></button>
                    `);
                });

                var debounceTimeout;

                // Live search function with debounce
                $('.category-search').on('keyup', function() {
                    var categoryBox = $(this).closest('.inside');
                    clearTimeout(debounceTimeout);
                    debounceTimeout = setTimeout(function() {
                        var searchTerm = categoryBox.find('.category-search').val().toLowerCase();

                        categoryBox.find('.categorychecklist li').each(function() {
                            var categoryName = $(this).text().toLowerCase();
                            var isChecked = $(this).find('input[type="checkbox"]').is(':checked');

                            if (categoryName.indexOf(searchTerm) !== -1 || isChecked) {
                                $(this).show();
                            } else {
                                $(this).hide();
                            }
                        });
                    }, 300); // Debounce delay in milliseconds
                });

                // Clear search functionality
                $('.clear-category-search').on('click', function() {
                    var categoryBox = $(this).closest('.inside');
                    categoryBox.find('.category-search').val('');
                    categoryBox.find('.categorychecklist li').show();
                });
            });
        </script>
        <?php
    }
}
add_action('admin_print_footer_scripts', 'wwccs_add_category_search_field');
```