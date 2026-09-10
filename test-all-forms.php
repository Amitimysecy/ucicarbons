<?php
/**
 * Browser-based test of ALL 4 enquiry forms (Contact, Request TDS,
 * Request Brochure, Brochure Download) — for cPanel hosts without
 * SSH/WP-CLI access.
 *
 * ── HOW TO USE ──────────────────────────────────────────────────
 * 1. Upload this file via cPanel File Manager into your theme folder
 *    (same folder as functions.php / index.php).
 * 2. Log into wp-admin as an administrator, in the SAME browser.
 * 3. Visit: https://ucicarbons.com/wp-content/themes/<your-theme-folder>/test-all-forms.php
 * 4. Click "Run test" on the page that loads.
 * 5. Check the report, then check your inboxes + the Google Sheet.
 * 6. DELETE THIS FILE from the server when you're done testing.
 *    (It is admin-gated, but it sends real test emails and Sheet
 *    rows on every run, so it shouldn't be left on a live site.)
 * ───────────────────────────────────────────────────────────────
 */

/* ── Locate and load WordPress ── */
$wp_load = null;
$dir = __DIR__;
for ( $i = 0; $i < 6; $i++ ) {
    if ( file_exists( $dir . '/wp-load.php' ) ) { $wp_load = $dir . '/wp-load.php'; break; }
    $parent = dirname( $dir );
    if ( $parent === $dir ) break;
    $dir = $parent;
}
if ( ! $wp_load ) {
    die( 'Could not find wp-load.php. Move this file into your theme folder inside wp-content/themes/.' );
}
require $wp_load;

/* ── Must be a logged-in administrator ── */
if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
    wp_die( 'Access denied. Log into wp-admin as an administrator in this browser first, then reload this page.' );
}

/* ── The 4 form types to test, with realistic sample data per type ── */
$test_cases = [
    'contact'     => [ 'label' => 'Contact Us',        'grade' => '',           'application' => 'Edible Oil Refining', 'brochure_type' => '' ],
    'tds'         => [ 'label' => 'Request TDS',        'grade' => 'UCI UW-22',  'application' => '',                    'brochure_type' => '' ],
    'brochure'    => [ 'label' => 'Request Brochure',   'grade' => 'UCI 55N',    'application' => '',                    'brochure_type' => 'General product brochure (all grades)' ],
    'brochure_dl' => [ 'label' => 'Brochure Download',  'grade' => '',           'application' => '',                    'brochure_type' => '' ],
];

$did_run = isset( $_POST['uci_run_test'] ) && check_admin_referer( 'uci_test_all_forms' );
$results = [];

if ( $did_run ) {
    $keep  = isset( $_POST['keep'] );
    $purge = isset( $_POST['purge'] );

    foreach ( $test_cases as $type => $case ) {
        $row = [ 'type' => $type, 'label' => $case['label'] ];

        $name    = 'Test User';
        $email   = get_option( 'admin_email' ) ?: 'test@example.com';
        $company = 'Test Co';
        $country = 'India';
        $message = 'Automated test submission for "' . $case['label'] . '" — safe to ignore or delete.';

        $post_id = wp_insert_post( [
            'post_type'    => 'uci_enquiry',
            'post_title'   => '[TEST] ' . $case['label'] . ' — ' . $name,
            'post_status'  => 'publish',
            'post_content' => $message,
        ] );

        if ( ! $post_id || is_wp_error( $post_id ) ) {
            $row['post_ok']   = false;
            $row['post_note'] = is_wp_error( $post_id ) ? $post_id->get_error_message() : 'unknown error';
            $results[] = $row;
            continue;
        }
        $row['post_ok']   = true;
        $row['post_id']   = $post_id;

        update_post_meta( $post_id, '_enq_type',          $type );
        update_post_meta( $post_id, '_enq_name',          $name );
        update_post_meta( $post_id, '_enq_email',         $email );
        update_post_meta( $post_id, '_enq_company',       $company );
        update_post_meta( $post_id, '_enq_country',       $country );
        update_post_meta( $post_id, '_enq_grade',         $case['grade'] );
        update_post_meta( $post_id, '_enq_application',   $case['application'] );
        update_post_meta( $post_id, '_enq_brochure_type', $case['brochure_type'] );
        update_post_meta( $post_id, '_enq_message',       $message );
        update_post_meta( $post_id, '_enq_ip',            '127.0.0.1' );

        /* Mail */
        $sent = wp_mail(
            'kartik.gupta@ucicarbons.com',
            '[TEST] ' . get_bloginfo( 'name' ) . ' — ' . $case['label'] . ' pipeline test',
            '<p>Automated test of the "' . esc_html( $case['label'] ) . '" enquiry pipeline.</p>'
            . '<p><strong>Name:</strong> ' . esc_html( $name ) . '<br><strong>Email:</strong> ' . esc_html( $email ) . '</p>',
            [
                'Content-Type: text/html; charset=UTF-8',
                'Cc: info@ucicarbons.com',
                'Bcc: amit.imysecy@gmail.com',
            ]
        );
        $row['mail_ok'] = $sent;

        /* Google Sheet sync (blocking, to capture the real response) */
        $enabled = get_option( 'uci_gsheet_sync_enabled', '0' ) === '1';
        $url     = get_option( 'uci_gsheet_webhook_url', '' );
        if ( ! $enabled ) {
            $row['sync_ok']   = null;
            $row['sync_note'] = 'Sync disabled';
        } elseif ( ! $url ) {
            $row['sync_ok']   = null;
            $row['sync_note'] = 'No Sheet URL saved';
        } else {
            $response = uci_gsheet_sync_row( $post_id, true );
            if ( is_wp_error( $response ) ) {
                $row['sync_ok']   = false;
                $row['sync_note'] = $response->get_error_message();
            } else {
                $code = wp_remote_retrieve_response_code( $response );
                $body = trim( wp_remote_retrieve_body( $response ) );
                $row['sync_ok']   = ( $code >= 200 && $code < 300 );
                $row['sync_note'] = "HTTP {$code}: " . ( $body ?: '(empty)' );
            }
        }

        /* Cleanup */
        if ( $purge ) {
            wp_delete_post( $post_id, true );
            $row['cleanup'] = 'deleted permanently';
        } elseif ( $keep ) {
            $row['cleanup'] = 'kept published';
        } else {
            wp_trash_post( $post_id );
            $row['cleanup'] = 'moved to Trash';
        }

        $results[] = $row;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Enquiry Forms Test</title>
<style>
    body { font-family: -apple-system, Arial, sans-serif; background:#F0F4F8; color:#0F3549; padding:32px; }
    .wrap { max-width: 760px; margin: 0 auto; background:#fff; border-radius:12px; padding:28px 32px; box-shadow:0 4px 20px rgba(15,53,73,.08); }
    h1 { font-size:20px; margin:0 0 6px; }
    .warn { background:#FEF2F2; border:1px solid #FECACA; color:#B91C1C; padding:10px 14px; border-radius:8px; font-size:13px; margin-bottom:20px; }
    table { width:100%; border-collapse:collapse; margin-top:16px; font-size:13px; }
    th, td { text-align:left; padding:8px 10px; border-bottom:1px solid #E4F2F8; vertical-align:top; }
    th { background:#0F3549; color:#fff; }
    .ok { color:#059669; font-weight:700; }
    .bad { color:#B91C1C; font-weight:700; }
    .skip { color:#999; }
    button { background:#0296D8; color:#fff; border:none; padding:11px 22px; border-radius:8px; font-size:14px; font-weight:600; cursor:pointer; }
    label { font-size:13px; margin-right:16px; }
</style>
</head>
<body>
<div class="wrap">
    <h1>Enquiry Forms Test — Contact / TDS / Brochure / Brochure Download</h1>
    <div class="warn">⚠️ Delete this file from the server once you're done testing.</div>

    <?php if ( ! $did_run ) : ?>
        <form method="post">
            <?php wp_nonce_field( 'uci_test_all_forms' ); ?>
            <input type="hidden" name="uci_run_test" value="1">
            <p><label><input type="checkbox" name="keep"> Keep test enquiries published (default: moved to Trash)</label></p>
            <p><label><input type="checkbox" name="purge"> Permanently delete test enquiries (overrides "keep")</label></p>
            <button type="submit">Run test for all 4 forms</button>
        </form>
    <?php else : ?>
        <table>
            <tr><th>Form</th><th>Test post</th><th>Email sent</th><th>Google Sheet sync</th><th>Cleanup</th></tr>
            <?php foreach ( $results as $r ) : ?>
            <tr>
                <td><strong><?php echo esc_html( $r['label'] ); ?></strong><br><span style="color:#888">type=<?php echo esc_html( $r['type'] ); ?></span></td>
                <td>
                    <?php if ( $r['post_ok'] ) : ?>
                        <span class="ok">✅ ID <?php echo (int) $r['post_id']; ?></span>
                    <?php else : ?>
                        <span class="bad">❌ <?php echo esc_html( $r['post_note'] ?? '' ); ?></span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ( ! isset( $r['mail_ok'] ) ) : ?>
                        <span class="skip">—</span>
                    <?php elseif ( $r['mail_ok'] ) : ?>
                        <span class="ok">✅ sent</span>
                    <?php else : ?>
                        <span class="bad">❌ wp_mail() failed</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ( ! array_key_exists( 'sync_ok', $r ) ) : ?>
                        <span class="skip">—</span>
                    <?php elseif ( $r['sync_ok'] === null ) : ?>
                        <span class="skip"><?php echo esc_html( $r['sync_note'] ); ?></span>
                    <?php elseif ( $r['sync_ok'] ) : ?>
                        <span class="ok">✅ <?php echo esc_html( $r['sync_note'] ); ?></span>
                    <?php else : ?>
                        <span class="bad">❌ <?php echo esc_html( $r['sync_note'] ); ?></span>
                    <?php endif; ?>
                </td>
                <td><?php echo esc_html( $r['cleanup'] ?? '—' ); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <p style="margin-top:20px;font-size:13px;color:#666">
            Now check the <strong>kartik.gupta@ucicarbons.com</strong> / <strong>info@ucicarbons.com</strong> / <strong>amit.imysecy@gmail.com</strong> inboxes,
            and your Google Sheet, for 4 new test rows/emails (one per form).
        </p>
        <p><a href="<?php echo esc_url( $_SERVER['PHP_SELF'] ); ?>">← Run again</a></p>
    <?php endif; ?>
</div>
</body>
</html>
