<?php
/**
 * Template Name: Thank You (legacy redirect)
 * Redirects to the appropriate type-specific thank-you page.
 * Keep this page in WP but do NOT assign it to any new forms.
 */
$type = isset($_GET['type']) ? sanitize_text_field($_GET['type']) : 'contact';
$name = isset($_GET['name']) ? sanitize_text_field($_GET['name']) : '';

$slugs = [
    'contact'  => 'thank-you-contact',
    'tds'      => 'thank-you-tds',
    'brochure' => 'thank-you-brochure',
];
$slug   = $slugs[$type] ?? 'thank-you-contact';
$params = ['name' => $name];

if ( ! headers_sent() ) {
    wp_redirect( home_url( '/' . $slug . '/?' . http_build_query($params) ), 301 );
    exit;
}
// Fallback if headers already sent
get_header();
echo '<script>window.location="' . esc_js( home_url('/' . $slug . '/?' . http_build_query($params)) ) . '";</script>';
get_footer();
