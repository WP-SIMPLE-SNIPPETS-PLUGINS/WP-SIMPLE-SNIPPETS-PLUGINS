# How to Add a WooCommerce Order Number Search Field to the WP Admin Top Bar?

```generic
function wwccs_custom_admin_bar_search_form() {
    global $wp_admin_bar;

    $search_query = '';
    if (isset($_GET['post_type']) && $_GET['post_type'] == 'shop_order') {
        $search_query = $_GET['s'];
    }

    $wp_admin_bar->add_menu(array(
        'id' => 'custom_admin_bar_search_form',
        'parent' => 'top-secondary',
        'title' => '<form method="get" action="'.get_site_url().'/wp-admin/edit.php?post_type=shop_order">
            <input name="s" type="text" value="' . esc_attr($search_query) . '" style="height:20px;margin:5px 0;line-height:1em;"/> 
            <input type="submit" style="padding:3px 7px;line-height:1" value="Search Orders"/> 
            <input name="post_type" value="shop_order" type="hidden">
        </form>'
    ));
}
add_action('admin_bar_menu', 'wwccs_custom_admin_bar_search_form', 100);
```