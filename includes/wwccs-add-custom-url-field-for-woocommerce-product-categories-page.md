# How to Add Custom URL Field for WooCommerce Product Categories Page?

```generic
// Add custom field to the Add New Term page
function wwccs_custom_url_add_new_meta_field() { ?>
    <div class="form-field">
        <label for="term_meta[custom_term_meta]">Custom URL</label>
        <input type="text" name="term_meta[custom_term_meta]" id="term_meta[custom_term_meta]" value="">
        <p class="description">Add a custom product URL for the category.</p>
    </div>
<?php }
add_action('product_cat_add_form_fields', 'wwccs_custom_url_add_new_meta_field');

// Add custom field to the Edit Term page
function wwccs_custom_url_edit_meta_field($term) {
    $term_id = $term->term_id;
    $term_meta = get_option("taxonomy_$term_id");
    $value = isset($term_meta['custom_term_meta']) ? esc_attr($term_meta['custom_term_meta']) : ''; ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="term_meta[custom_term_meta]">Custom URL</label></th>
        <td>
            <input type="text" name="term_meta[custom_term_meta]" id="term_meta[custom_term_meta]" value="<?php echo $value; ?>">
            <p class="description">Add a custom product URL for the category.</p>
        </td>
    </tr>
<?php }
add_action('product_cat_edit_form_fields', 'wwccs_custom_url_edit_meta_field');

// Save custom field values
function wwccs_save_custom_url_meta($term_id) {
    if (isset($_POST['term_meta'])) {
        $term_meta = get_option("taxonomy_$term_id") ?: [];
        foreach ($_POST['term_meta'] as $key => $value) {
            $term_meta[$key] = sanitize_text_field($value);
        }
        update_option("taxonomy_$term_id", $term_meta);
    }
}
add_action('edited_product_cat', 'wwccs_save_custom_url_meta');
add_action('create_product_cat', 'wwccs_save_custom_url_meta');

// Display custom URL on product category pages
function wwccs_display_custom_url_on_category_page() {
    if (is_product_category()) {
        $term = get_queried_object();
        $term_id = $term->term_id;
        $term_meta = get_option("taxonomy_$term_id");
        if (!empty($term_meta['custom_term_meta'])) {
            echo '<p>
                <a href="' . esc_url($term_meta['custom_term_meta']) . '" 
                   target="_blank" 
                   style="font-weight: bold; text-decoration: underline;">
                   Explore Featured Courses
                </a>
              </p>';
        }
    }
}
add_action('woocommerce_archive_description', 'wwccs_display_custom_url_on_category_page');
```