<?php
/*
Plugin Name: CF7 Page Title
Description: Adding page title input field shortcode to Contact Form 7. Use shortcode [page_title] in form and email.
Version: 1.00
Author: Kasper M. Sonne
*/

// Define the custom field shortcode to include the page title
function cf7_add_editable_page_title_field() {
    if (is_page() || is_single()) {
        $page_title = get_the_title();
        return '<input type="text" name="page-title" value="' . esc_attr($page_title) . '">';
    }
    return '';
}
add_shortcode('cf7_page_title_field', 'cf7_add_editable_page_title_field');

// Integrate the custom field into Contact Form 7
function cf7_custom_add_shortcode() {
    wpcf7_add_form_tag('page_title', 'cf7_add_editable_page_title_field');
}
add_action('wpcf7_init', 'cf7_custom_add_shortcode');

// Process the custom field before sending mail
function cf7_custom_handle_submission($contact_form) {
    $submission = WPCF7_Submission::get_instance();
    if ($submission) {
        $posted_data = $submission->get_posted_data();
        if (isset($posted_data['page-title'])) {
            $page_title = sanitize_text_field($posted_data['page-title']);
            // You can now use $page_title as needed, for example in email content
        }
    }
}
add_action('wpcf7_before_send_mail', 'cf7_custom_handle_submission');