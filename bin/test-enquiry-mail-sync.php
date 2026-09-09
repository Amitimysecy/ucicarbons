<?php
/**
 * One-off test of the Enquiry pipeline: admin notification email + Google
 * Sheet sync — exercised through the SAME functions the live Contact / TDS /
 * Brochure / Brochure Download forms use (uci_gsheet_sync_row, wp_mail),
 * without touching the front-end forms or requiring a browser.
 *
 * Run on the live server via WP-CLI, from the WordPress root:
 *
 *   wp eval-file wp-content/themes/<your-theme-folder>/bin/test-enquiry-mail-sync.php
 *
 * Optional flags:
 *   --keep   leave the test enquiry published (default: moved to Trash)
 *   --purge  permanently delete the test enquiry instead of trashing it
 *
 * The test enquiry is clearly named "[TEST] ..." and its Type meta is set
 * to "test" so it's obvious in Enquiries → All Enquiries if you choose --keep.
 */

if ( ! defined( 'ABSPATH' ) ) {
    fwrite( STDERR, "This must be run via WP-CLI: wp eval-file <path-to-this-file>\n" );
    exit( 1 );
}

$assoc_args = $assoc_args ?? [];
$keep       = isset( $assoc_args['keep'] );
$purge      = isset( $assoc_args['purge'] );

function uci_test_line( $label, $ok, $detail = '' ) {
    $mark = $ok ? '✅' : '❌';
    echo "{$mark} {$label}" . ( $detail !== '' ? " — {$detail}" : '' ) . "\n";
}

echo "── UCI Carbons: Enquiry mail + Google Sheet sync test ──\n\n";

/* ── 1. Create a temporary test enquiry (same shape as a real submission) ── */
$name    = 'Test User';
$email   = get_option( 'admin_email' ) ?: 'test@example.com';
$company = 'Test Co';
$country = 'India';
$message = 'This is an automated test submission — safe to ignore or delete.';

$post_id = wp_insert_post( [
    'post_type'    => 'uci_enquiry',
    'post_title'   => '[TEST] ' . $name . ' — ' . $company,
    'post_status'  => 'publish',
    'post_content' => $message,
] );

if ( ! $post_id || is_wp_error( $post_id ) ) {
    uci_test_line( 'Create test enquiry post', false, is_wp_error( $post_id ) ? $post_id->get_error_message() : 'unknown error' );
    exit( 1 );
}
uci_test_line( 'Create test enquiry post', true, "post ID {$post_id}" );

update_post_meta( $post_id, '_enq_type',    'test' );
update_post_meta( $post_id, '_enq_name',    $name );
update_post_meta( $post_id, '_enq_email',   $email );
update_post_meta( $post_id, '_enq_company', $company );
update_post_meta( $post_id, '_enq_country', $country );
update_post_meta( $post_id, '_enq_message', $message );
update_post_meta( $post_id, '_enq_ip',      '127.0.0.1' );

/* ── 2. Send the same style of notification email the live forms send ── */
$site_name = get_bloginfo( 'name' );
$sent = wp_mail(
    'kartik.gupta@ucicarbons.com',
    '[TEST] ' . $site_name . ' — Enquiry pipeline test',
    '<p>This is an automated test of the enquiry email pipeline.</p>'
    . '<p><strong>Name:</strong> ' . esc_html( $name ) . '<br>'
    . '<strong>Email:</strong> ' . esc_html( $email ) . '<br>'
    . '<strong>Company:</strong> ' . esc_html( $company ) . '</p>',
    [
        'Content-Type: text/html; charset=UTF-8',
        'Cc: info@ucicarbons.com',
        'Bcc: amit.imysecy@gmail.com',
    ]
);
uci_test_line(
    'wp_mail() admin notification',
    $sent,
    $sent ? 'sent to kartik.gupta@ucicarbons.com, cc info@ucicarbons.com, bcc amit.imysecy@gmail.com'
          : 'wp_mail() returned false — check the site\'s SMTP/mail configuration (a plugin like WP Mail SMTP, or your host\'s mail() setup)'
);

/* ── 3. Google Sheet sync (blocking, so we can show the real response) ── */
$enabled = get_option( 'uci_gsheet_sync_enabled', '0' ) === '1';
$url     = get_option( 'uci_gsheet_webhook_url', '' );

if ( ! $enabled ) {
    uci_test_line( 'Google Sheet sync', false, 'disabled — tick "Enable Sync" under Enquiries → Google Sheet Sync' );
} elseif ( ! $url ) {
    uci_test_line( 'Google Sheet sync', false, 'no Google Sheet Link saved under Enquiries → Google Sheet Sync' );
} else {
    $response = uci_gsheet_sync_row( $post_id, true );
    if ( is_wp_error( $response ) ) {
        uci_test_line( 'Google Sheet sync', false, $response->get_error_message() );
    } else {
        $code = wp_remote_retrieve_response_code( $response );
        $body = trim( wp_remote_retrieve_body( $response ) );
        uci_test_line( 'Google Sheet sync', $code >= 200 && $code < 300, "HTTP {$code}, response: " . ( $body ?: '(empty)' ) );
    }
}

/* ── 4. Clean up the test post ── */
if ( $purge ) {
    wp_delete_post( $post_id, true );
    echo "\nTest enquiry permanently deleted.\n";
} elseif ( $keep ) {
    echo "\nTest enquiry left published (post ID {$post_id}) — visible under Enquiries → All Enquiries.\n";
} else {
    wp_trash_post( $post_id );
    echo "\nTest enquiry moved to Trash (post ID {$post_id}).\n";
}

echo "\nCheck: the kartik.gupta@ucicarbons.com / info@ucicarbons.com / amit.imysecy@gmail.com inboxes, and the Google Sheet (if enabled) for the new test row.\n";
