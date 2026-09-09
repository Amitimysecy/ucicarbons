<?php

if (!defined('ABSPATH')) {
    exit;
}

/* ── Mega Menu system (registers nav locations + render functions) ── */
require get_template_directory() . '/inc/mega-menu.php';

/*
|--------------------------------------------------------------------------
| Theme Setup
|--------------------------------------------------------------------------
*/

function uci_theme_setup() {

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', [
        'height'      => 80,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);
    
    register_nav_menus([
        'primary'              => 'Primary Menu',
        'mobile'               => 'Mobile Menu',
        'footer_products'      => 'Footer Products',
        'footer_applications'  => 'Footer Applications',
        'footer_company'       => 'Footer Company',
    ]);
}
add_action('after_setup_theme', 'uci_theme_setup');


/*
|--------------------------------------------------------------------------
| Enqueue CSS & JS
|--------------------------------------------------------------------------
*/

function uci_enqueue_assets() {

    // Google Fonts
    wp_enqueue_style(
        'uci-google-fonts',
        'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=Source+Code+Pro:wght@500;600;700&family=Fraunces:ital,opsz,wght@1,9..144,300;1,9..144,400&display=swap',
        [],
        null
    );

    $main_css = get_template_directory() . '/assets/css/main.css';
    wp_enqueue_style(
        'uci-main',
        get_template_directory_uri() . '/assets/css/main.css',
        [],
        file_exists( $main_css ) ? filemtime( $main_css ) : '1.0'
    );

    // Only load specifications.css on the specifications page
    if ( is_page_template('page-specifications.php') || is_page('specifications') ) {
        $spec_css = get_template_directory() . '/assets/css/specifications.css';
        wp_enqueue_style(
            'uci-specifications',
            get_template_directory_uri() . '/assets/css/specifications.css',
            ['uci-main'],
            file_exists( $spec_css ) ? filemtime( $spec_css ) : '1.0'
        );
    }

    wp_enqueue_script(
        'uci-main-js',
        get_template_directory_uri() . '/assets/js/main.js',
        [],
        file_exists(
            get_template_directory() . '/assets/js/main.js'
        )
            ? filemtime(
                get_template_directory() . '/assets/js/main.js'
            )
            : null,
        true
    );

    // Cache page permalink lookups — saves 3 DB queries per page load
    $uci_contact_url  = get_transient('uci_url_contact');
    $uci_tds_url      = get_transient('uci_url_tds');
    $uci_brochure_url = get_transient('uci_url_brochure');
    if ( ! $uci_contact_url ) {
        $p = get_page_by_path('contact');
        $uci_contact_url = $p ? get_permalink($p) : home_url('/contact/');
        set_transient('uci_url_contact', $uci_contact_url, DAY_IN_SECONDS);
    }
    if ( ! $uci_tds_url ) {
        $p = get_page_by_path('request-tds');
        $uci_tds_url = $p ? get_permalink($p) : home_url('/request-tds/');
        set_transient('uci_url_tds', $uci_tds_url, DAY_IN_SECONDS);
    }
    if ( ! $uci_brochure_url ) {
        $p = get_page_by_path('request-brochure');
        $uci_brochure_url = $p ? get_permalink($p) : home_url('/request-brochure/');
        set_transient('uci_url_brochure', $uci_brochure_url, DAY_IN_SECONDS);
    }

    wp_localize_script('uci-main-js', 'uciVars', [
        'contactUrl'  => $uci_contact_url,
        'tdsUrl'      => $uci_tds_url,
        'brochureUrl' => $uci_brochure_url,
        'homeUrl'     => home_url('/'),
        'ajaxUrl'     => admin_url('admin-ajax.php'),
        'nonce'       => wp_create_nonce('uci_carbon_ai'),
    ]);
}
add_action('wp_enqueue_scripts', 'uci_enqueue_assets');


/* ══════════════════════════════════════════════
   ENQUIRIES — CUSTOM POST TYPE
   ══════════════════════════════════════════════ */
add_action('init', 'uci_register_enquiry_cpt');
function uci_register_enquiry_cpt() {
    register_post_type('uci_enquiry', [
        'label'               => 'Enquiries',
        'labels'              => [
            'name'          => 'Enquiries',
            'singular_name' => 'Enquiry',
            'menu_name'     => 'Enquiries',
            'add_new'       => 'Add New',
            'all_items'     => 'All Enquiries',
            'view_item'     => 'View Enquiry',
        ],
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_icon'           => 'dashicons-email-alt',
        'menu_position'       => 25,
        'supports'            => ['title', 'editor'],
        'capability_type'     => 'post',
        'map_meta_cap'        => true,
        'has_archive'         => false,
        'rewrite'             => false,
        'query_var'           => false,
    ]);
}

/* Custom admin columns */
add_filter('manage_uci_enquiry_posts_columns', 'uci_enquiry_columns');
function uci_enquiry_columns( $cols ) {
    return [
        'cb'           => '<input type="checkbox">',
        'enquiry_type' => 'Type',
        'title'        => 'Name',
        'enquiry_email'=> 'Email',
        'enquiry_co'   => 'Company',
        'enquiry_ctry' => 'Country',
        'enquiry_grade'=> 'Grade / Application',
        'date'         => 'Date',
    ];
}
add_action('manage_uci_enquiry_posts_custom_column', 'uci_enquiry_column_content', 10, 2);
function uci_enquiry_column_content( $col, $post_id ) {
    $labels = ['contact'=>'Contact Us','tds'=>'Request TDS','brochure'=>'Request Brochure','brochure_dl'=>'Brochure Download'];
    $colors = ['contact'=>'#0296D8','tds'=>'#7C3AED','brochure'=>'#059669','brochure_dl'=>'#D97706'];
    switch ( $col ) {
        case 'enquiry_type':
            $t = get_post_meta($post_id,'_enq_type',true);
            $c = $colors[$t] ?? '#888';
            echo '<span style="background:'.$c.';color:#fff;padding:2px 10px;border-radius:4px;font-size:11px;font-weight:700">'.esc_html($labels[$t] ?? strtoupper($t)).'</span>';
            break;
        case 'enquiry_email': echo '<a href="mailto:'.esc_attr(get_post_meta($post_id,'_enq_email',true)).'">'.esc_html(get_post_meta($post_id,'_enq_email',true)).'</a>'; break;
        case 'enquiry_co':    echo esc_html(get_post_meta($post_id,'_enq_company',true)); break;
        case 'enquiry_ctry':  echo esc_html(get_post_meta($post_id,'_enq_country',true)); break;
        case 'enquiry_grade':
            $v = get_post_meta($post_id,'_enq_grade',true) ?: get_post_meta($post_id,'_enq_application',true) ?: get_post_meta($post_id,'_enq_brochure_type',true);
            echo esc_html($v);
            break;
    }
}

/* Meta box for enquiry detail */
add_action('add_meta_boxes', function() {
    add_meta_box('uci_enq_details','Enquiry Details','uci_enquiry_metabox','uci_enquiry','normal','high');
});
function uci_enquiry_metabox( $post ) {
    $fields = [
        '_enq_type'         => 'Form Type',
        '_enq_name'         => 'Name',
        '_enq_email'        => 'Email',
        '_enq_company'      => 'Company',
        '_enq_country'      => 'Country',
        '_enq_grade'        => 'Grade',
        '_enq_application'  => 'Application',
        '_enq_brochure_type'=> 'Brochure Type',
        '_enq_ip'           => 'IP Address',
    ];
    echo '<table class="widefat striped" style="margin-top:8px"><tbody>';
    foreach ( $fields as $key => $label ) {
        $val = get_post_meta($post->ID, $key, true);
        if ( !$val ) continue;
        echo '<tr><th style="width:160px">' . esc_html($label) . '</th><td>';
        if ( $key === '_enq_email' ) echo '<a href="mailto:'.esc_attr($val).'">'.esc_html($val).'</a>';
        else echo esc_html($val);
        echo '</td></tr>';
    }
    echo '</tbody></table>';
    echo '<p style="margin-top:12px"><strong>Message:</strong></p>';
    echo '<div style="background:#f9f9f9;padding:12px;border:1px solid #ddd;border-radius:4px;white-space:pre-wrap">' . esc_html( get_post_meta($post->ID,'_enq_message',true) ) . '</div>';
}


/* ══════════════════════════════════════════════
   ENQUIRIES — READ-ONLY + EXPORT (CSV / Print / Email)
   ══════════════════════════════════════════════ */

/* 1. Remove Edit / Quick Edit row actions */
add_filter('post_row_actions', 'uci_enquiry_row_actions', 10, 2);
function uci_enquiry_row_actions( $actions, $post ) {
    if ( $post->post_type === 'uci_enquiry' ) {
        unset( $actions['edit'], $actions['inline hide-if-no-js'] );
    }
    return $actions;
}

/* 2. Redirect wp-admin edit screen back to list */
add_action('load-post.php', 'uci_enquiry_block_edit');
function uci_enquiry_block_edit() {
    $post_id = absint( $_GET['post'] ?? 0 );
    if ( $post_id && get_post_type($post_id) === 'uci_enquiry' && ( $_GET['action'] ?? '' ) === 'edit' ) {
        wp_safe_redirect( admin_url('edit.php?post_type=uci_enquiry&uci_msg=readonly') );
        exit;
    }
}

/* 3. Admin notice for read-only redirect */
add_action('admin_notices', 'uci_enquiry_readonly_notice');
function uci_enquiry_readonly_notice() {
    if ( isset($_GET['uci_msg']) && $_GET['uci_msg'] === 'readonly'
         && ( get_current_screen()->post_type ?? '' ) === 'uci_enquiry' ) {
        echo '<div class="notice notice-info is-dismissible"><p>Enquiries are <strong>read-only</strong>. Use the Export buttons to download data.</p></div>';
    }
}

/* 4. Export buttons above the list table */
add_action('restrict_manage_posts', 'uci_enquiry_export_buttons');
function uci_enquiry_export_buttons( $post_type ) {
    if ( $post_type !== 'uci_enquiry' ) return;
    $csv_url   = wp_nonce_url( admin_url('admin-post.php?action=uci_export_csv'),   'uci_export_csv' );
    $print_url = wp_nonce_url( admin_url('admin-post.php?action=uci_export_print'), 'uci_export_print' );
    $email_url = wp_nonce_url( admin_url('admin-post.php?action=uci_email_all'),    'uci_email_all' );
    echo '<a href="' . esc_url($csv_url)   . '" class="button button-primary" style="margin-right:6px">⬇ Export CSV</a>';
    echo '<a href="' . esc_url($print_url) . '" class="button" style="margin-right:6px" target="_blank">🖨 Print / PDF</a>';
    echo '<a href="' . esc_url($email_url) . '" class="button" onclick="return confirm(\'Email all enquiries summary to admin?\')">✉ Email All to Admin</a>';
}

/* 5. Helper: fetch all enquiry data */
function uci_get_all_enquiries() {
    return get_posts([
        'post_type'      => 'uci_enquiry',
        'numberposts'    => -1,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);
}

/* 6. CSV export action */
add_action('admin_post_uci_export_csv', 'uci_export_enquiries_csv');
function uci_export_enquiries_csv() {
    if ( ! current_user_can('manage_options') ) wp_die('Unauthorised');
    check_admin_referer('uci_export_csv');

    $posts = uci_get_all_enquiries();
    $filename = 'uci-enquiries-' . date('Y-m-d') . '.csv';

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');

    $out = fopen('php://output', 'w');
    fputcsv($out, ['Date', 'Type', 'Name', 'Email', 'Company', 'Country', 'Grade / Application', 'Message']);

    foreach ( $posts as $p ) {
        $grade = get_post_meta($p->ID,'_enq_grade',true)
              ?: get_post_meta($p->ID,'_enq_application',true)
              ?: get_post_meta($p->ID,'_enq_brochure_type',true);
        $type_map = ['contact'=>'Contact Us','tds'=>'Request TDS','brochure'=>'Request Brochure','brochure_dl'=>'Brochure Download'];
        $type_raw = get_post_meta($p->ID,'_enq_type',true);
        fputcsv($out, [
            get_the_date('Y-m-d H:i', $p),
            $type_map[$type_raw] ?? strtoupper($type_raw),
            get_post_meta($p->ID,'_enq_name',true),
            get_post_meta($p->ID,'_enq_email',true),
            get_post_meta($p->ID,'_enq_company',true),
            get_post_meta($p->ID,'_enq_country',true),
            $grade,
            get_post_meta($p->ID,'_enq_message',true),
        ]);
    }
    fclose($out);
    exit;
}

/* 7. Print / PDF export — browser-printable HTML */
add_action('admin_post_uci_export_print', 'uci_export_enquiries_print');
function uci_export_enquiries_print() {
    if ( ! current_user_can('manage_options') ) wp_die('Unauthorised');
    check_admin_referer('uci_export_print');

    $posts     = uci_get_all_enquiries();
    $type_map  = ['contact'=>'Contact Us','tds'=>'Request TDS','brochure'=>'Request Brochure','brochure_dl'=>'Brochure Download'];
    $type_col  = ['contact'=>'#0296D8','tds'=>'#7C3AED','brochure'=>'#059669'];
    $site_name = get_bloginfo('name');
    $today     = date('d M Y');

    echo '<!DOCTYPE html><html><head><meta charset="UTF-8">';
    echo '<title>UCI Enquiries — ' . esc_html($today) . '</title>';
    echo '<style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:Arial,sans-serif;font-size:12px;color:#1a1a1a;padding:20px}
        h1{font-size:18px;margin-bottom:4px}
        .meta{font-size:11px;color:#666;margin-bottom:16px}
        table{width:100%;border-collapse:collapse;font-size:11px}
        th{background:#0F3549;color:#fff;padding:7px 8px;text-align:left;font-size:10px;letter-spacing:.5px;text-transform:uppercase}
        td{padding:7px 8px;border-bottom:1px solid #e5e7eb;vertical-align:top}
        tr:nth-child(even) td{background:#f8fafc}
        .badge{display:inline-block;padding:2px 7px;border-radius:3px;color:#fff;font-size:10px;font-weight:700}
        .msg{max-width:220px;word-break:break-word;color:#555}
        @media print{
            @page{size:A4 landscape;margin:12mm}
            body{padding:0}
            .no-print{display:none}
        }
    </style></head><body>';

    echo '<h1>' . esc_html($site_name) . ' — Enquiries</h1>';
    echo '<div class="meta">Exported ' . esc_html($today) . ' · ' . count($posts) . ' records</div>';

    echo '<p class="no-print" style="margin-bottom:12px"><button onclick="window.print()" style="padding:8px 18px;background:#0296D8;color:#fff;border:none;border-radius:4px;cursor:pointer;font-size:13px">🖨 Print / Save as PDF</button></p>';

    echo '<table><thead><tr><th>Date</th><th>Type</th><th>Name</th><th>Email</th><th>Company</th><th>Country</th><th>Grade / App</th><th>Message</th></tr></thead><tbody>';

    foreach ( $posts as $p ) {
        $grade   = get_post_meta($p->ID,'_enq_grade',true)
                ?: get_post_meta($p->ID,'_enq_application',true)
                ?: get_post_meta($p->ID,'_enq_brochure_type',true);
        $type_r  = get_post_meta($p->ID,'_enq_type',true);
        $col     = $type_col[$type_r] ?? '#888';
        $label   = $type_map[$type_r] ?? strtoupper($type_r);
        $email   = get_post_meta($p->ID,'_enq_email',true);
        echo '<tr>';
        echo '<td style="white-space:nowrap">' . esc_html(get_the_date('d M Y', $p)) . '</td>';
        echo '<td><span class="badge" style="background:' . $col . '">' . esc_html($label) . '</span></td>';
        echo '<td>' . esc_html(get_post_meta($p->ID,'_enq_name',true)) . '</td>';
        echo '<td>' . esc_html($email) . '</td>';
        echo '<td>' . esc_html(get_post_meta($p->ID,'_enq_company',true)) . '</td>';
        echo '<td>' . esc_html(get_post_meta($p->ID,'_enq_country',true)) . '</td>';
        echo '<td>' . esc_html($grade) . '</td>';
        echo '<td class="msg">' . esc_html(wp_trim_words(get_post_meta($p->ID,'_enq_message',true), 20)) . '</td>';
        echo '</tr>';
    }
    echo '</tbody></table></body></html>';
    exit;
}

/* 8. Email all enquiries summary to admin */
add_action('admin_post_uci_email_all', 'uci_email_all_enquiries');
function uci_email_all_enquiries() {
    if ( ! current_user_can('manage_options') ) wp_die('Unauthorised');
    check_admin_referer('uci_email_all');

    $posts      = uci_get_all_enquiries();
    $type_map   = ['contact'=>'Contact Us','tds'=>'Request TDS','brochure'=>'Request Brochure','brochure_dl'=>'Brochure Download'];
    $admin_email = get_option('admin_email');
    $site_name   = get_bloginfo('name');
    $today       = date('d M Y');

    $rows = '';
    foreach ( $posts as $p ) {
        $grade = get_post_meta($p->ID,'_enq_grade',true)
              ?: get_post_meta($p->ID,'_enq_application',true)
              ?: get_post_meta($p->ID,'_enq_brochure_type',true);
        $type_r = get_post_meta($p->ID,'_enq_type',true);
        $rows .= '<tr style="border-bottom:1px solid #e5e7eb">'
            . '<td style="padding:8px;white-space:nowrap">' . esc_html(get_the_date('d M Y', $p)) . '</td>'
            . '<td style="padding:8px">' . esc_html($type_map[$type_r] ?? $type_r) . '</td>'
            . '<td style="padding:8px">' . esc_html(get_post_meta($p->ID,'_enq_name',true)) . '</td>'
            . '<td style="padding:8px"><a href="mailto:' . esc_attr(get_post_meta($p->ID,'_enq_email',true)) . '">' . esc_html(get_post_meta($p->ID,'_enq_email',true)) . '</a></td>'
            . '<td style="padding:8px">' . esc_html(get_post_meta($p->ID,'_enq_company',true)) . '</td>'
            . '<td style="padding:8px">' . esc_html(get_post_meta($p->ID,'_enq_country',true)) . '</td>'
            . '<td style="padding:8px">' . esc_html($grade) . '</td>'
            . '</tr>';
    }

    $html = '<!DOCTYPE html><html><body style="font-family:Arial,sans-serif;color:#1a1a1a">'
        . '<h2 style="color:#0F3549">' . esc_html($site_name) . ' — Enquiries Export</h2>'
        . '<p style="color:#666">Exported ' . esc_html($today) . ' · ' . count($posts) . ' total records</p>'
        . '<table style="width:100%;border-collapse:collapse;font-size:12px">'
        . '<thead><tr style="background:#0F3549;color:#fff">'
        . '<th style="padding:8px">Date</th><th style="padding:8px">Type</th><th style="padding:8px">Name</th>'
        . '<th style="padding:8px">Email</th><th style="padding:8px">Company</th>'
        . '<th style="padding:8px">Country</th><th style="padding:8px">Grade / App</th>'
        . '</tr></thead><tbody>' . $rows . '</tbody></table>'
        . '<p style="margin-top:16px;font-size:11px;color:#888">Sent from WordPress Admin · ' . esc_html($site_name) . '</p>'
        . '</body></html>';

    $sent = wp_mail(
        $admin_email,
        $site_name . ' — All Enquiries (' . $today . ')',
        $html,
        ['Content-Type: text/html; charset=UTF-8']
    );

    $msg = $sent ? 'email_sent' : 'email_failed';
    wp_safe_redirect( admin_url('edit.php?post_type=uci_enquiry&uci_msg=' . $msg) );
    exit;
}

/* 9. Admin notice for email send result */
add_action('admin_notices', 'uci_enquiry_email_notice');
function uci_enquiry_email_notice() {
    $screen = get_current_screen();
    if ( ( $screen->post_type ?? '' ) !== 'uci_enquiry' ) return;
    if ( isset($_GET['uci_msg']) ) {
        if ( $_GET['uci_msg'] === 'email_sent' ) {
            echo '<div class="notice notice-success is-dismissible"><p>✅ Enquiries summary emailed to admin.</p></div>';
        } elseif ( $_GET['uci_msg'] === 'email_failed' ) {
            echo '<div class="notice notice-error is-dismissible"><p>❌ Email failed. Check your WordPress mail settings.</p></div>';
        }
    }
}

/* ══════════════════════════════════════════════
   CONTACT FORM — AJAX HANDLER
   Saves enquiry + sends HTML email
   ══════════════════════════════════════════════ */
add_action('wp_ajax_uci_contact_form',        'uci_contact_form_handler');
add_action('wp_ajax_nopriv_uci_contact_form', 'uci_contact_form_handler');

function uci_contact_form_handler() {
    // Changed 09-Sep-2026 15:04 IST - AK
    check_ajax_referer( 'uci_contact', 'uci_nonce' );

    // Rate limit: 10 per IP per hour
    $ip    = sanitize_text_field( $_SERVER['REMOTE_ADDR'] ?? 'unknown' );
    $t_key = 'uci_contact_rate_' . md5($ip);
    $count = (int) get_transient($t_key);
    if ( $count >= 10 ) { wp_send_json_error(['message'=>'Too many requests']); return; }
    set_transient($t_key, $count + 1, HOUR_IN_SECONDS);

    // Sanitise
    $type    = sanitize_text_field( $_POST['form_type']      ?? 'contact' );
    $name    = sanitize_text_field( $_POST['name']           ?? '' );
    $company = sanitize_text_field( $_POST['company']        ?? '' );
    $email   = sanitize_email(      $_POST['email']          ?? '' );
    $country = sanitize_text_field( $_POST['country']        ?? '' );
    $message = sanitize_textarea_field( $_POST['message']    ?? '' );
    $grade   = sanitize_text_field( $_POST['grade']          ?? '' );
    $app     = sanitize_text_field( $_POST['application']    ?? '' );
    $brtype  = sanitize_text_field( $_POST['brochure_type']  ?? '' );

    if ( empty($name) || empty($email) || ! is_email($email) ) {
        wp_send_json_error(['message'=>'Invalid data']); return;
    }

    $type_labels = ['contact'=>'Contact Us','tds'=>'Request TDS','brochure'=>'Request Brochure','brochure_dl'=>'Brochure Download'];
    $type_label  = $type_labels[$type] ?? 'Enquiry';

    /* ── 1. Save to WP backend ── */
    $post_id = wp_insert_post([
        'post_type'   => 'uci_enquiry',
        'post_title'  => $name . ( $company ? ' — ' . $company : '' ),
        'post_status' => 'publish',
        'post_content'=> $message,
    ]);
    if ( $post_id && ! is_wp_error($post_id) ) {
        update_post_meta($post_id, '_enq_type',         $type);
        update_post_meta($post_id, '_enq_name',         $name);
        update_post_meta($post_id, '_enq_email',        $email);
        update_post_meta($post_id, '_enq_company',      $company);
        update_post_meta($post_id, '_enq_country',      $country);
        update_post_meta($post_id, '_enq_grade',        $grade);
        update_post_meta($post_id, '_enq_application',  $app);
        update_post_meta($post_id, '_enq_brochure_type',$brtype);
        update_post_meta($post_id, '_enq_message',      $message);
        update_post_meta($post_id, '_enq_ip',           $ip);
    }

    /* ── 2. Build details rows for email ── */
    $rows = '';
    $detail_fields = array_filter([
        'Form Type'      => $type_label,
        'Name'           => $name,
        'Company'        => $company,
        'Email'          => $email,
        'Country'        => $country,
        'Grade'          => $grade,
        'Application'    => $app,
        'Brochure Type'  => $brtype,
    ]);
    foreach ( $detail_fields as $label => $value ) {
        $rows .= '
        <tr>
          <td style="padding:10px 16px;font-size:13px;font-weight:700;color:#5A7A8A;font-family:monospace;letter-spacing:0.5px;background:#F8FDFF;border-bottom:1px solid #E4F2F8;width:140px;white-space:nowrap">' . esc_html(strtoupper($label)) . '</td>
          <td style="padding:10px 16px;font-size:14px;color:#0F3549;border-bottom:1px solid #E4F2F8">' . esc_html($value) . '</td>
        </tr>';
    }
    if ( $message ) {
        $rows .= '
        <tr>
          <td style="padding:10px 16px;font-size:13px;font-weight:700;color:#5A7A8A;font-family:monospace;letter-spacing:0.5px;background:#F8FDFF;border-bottom:1px solid #E4F2F8;vertical-align:top;white-space:nowrap">MESSAGE</td>
          <td style="padding:10px 16px;font-size:14px;color:#0F3549;border-bottom:1px solid #E4F2F8;line-height:1.7;white-space:pre-wrap">' . esc_html($message) . '</td>
        </tr>';
    }

    $type_color  = ['contact'=>'#0296D8','tds'=>'#7C3AED','brochure'=>'#059669','brochure_dl'=>'#D97706'][$type] ?? '#0296D8';
    $admin_url   = admin_url('edit.php?post_type=uci_enquiry');
    $reply_url   = 'mailto:' . rawurlencode($email) . '?subject=' . rawurlencode('Re: Your enquiry — UCI Carbons') . '&body=' . rawurlencode("Dear $name,\n\nThank you for reaching out to UCI Carbons. ");
    $site_name   = get_bloginfo('name');

    /* ── 3. HTML email body ── */
    $html_email = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>New Enquiry</title></head>
<body style="margin:0;padding:0;background:#F0F4F8;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#F0F4F8;padding:32px 0">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%">

  <!-- Header -->
  <tr>
    <td style="background:#0F3549;border-radius:16px 16px 0 0;padding:28px 36px">
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td>
            <div style="font-size:11px;font-weight:700;letter-spacing:2.5px;color:{$type_color};text-transform:uppercase;font-family:monospace;margin-bottom:8px">New {$type_label}</div>
            <div style="font-size:22px;font-weight:800;color:#fff;letter-spacing:-0.5px">UCI Carbons — Enquiry Received</div>
          </td>
          <td align="right" style="vertical-align:middle">
            <div style="background:{$type_color};color:#fff;font-size:11px;font-weight:800;letter-spacing:1px;padding:6px 14px;border-radius:6px;text-transform:uppercase;font-family:monospace;white-space:nowrap">{$type_label}</div>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- Body -->
  <tr>
    <td style="background:#fff;padding:0">
      <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse">
        {$rows}
      </table>
    </td>
  </tr>

  <!-- CTA -->
  <tr>
    <td style="background:#fff;padding:24px 36px 32px;border-top:2px solid {$type_color}">
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td>
            <a href="{$reply_url}" style="display:inline-block;background:{$type_color};color:#fff;font-size:14px;font-weight:700;padding:13px 28px;border-radius:10px;text-decoration:none;letter-spacing:0.3px">Reply to {$name} →</a>
          </td>
          <td align="right" style="vertical-align:middle">
            <a href="{$admin_url}" style="font-size:13px;color:{$type_color};text-decoration:none;font-weight:600">View in WP Admin →</a>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- Footer -->
  <tr>
    <td style="background:#F8FDFF;border:1px solid #E4F2F8;border-top:none;border-radius:0 0 16px 16px;padding:20px 36px">
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td>
            <div style="font-size:12px;color:#7B9BAA;line-height:1.6">
              <strong style="color:#0F3549">UCI Carbons Group</strong> · Rajindra Carbons, Bhagowal, Hoshiarpur, Punjab 146001, India<br>
              This email was sent automatically when a visitor submitted a form on ucicarbons.com
            </div>
          </td>
          <td align="right" style="vertical-align:top">
            <div style="font-size:11px;font-family:monospace;color:#BAD4DE;letter-spacing:1px">ISO 9001 · ISO 14001<br>HALAL · KOSHER · NSF</div>
          </td>
        </tr>
      </table>
    </td>
  </tr>

</table>
</td></tr>
</table>
</body>
</html>
HTML;

    /* ── 4. Send email ── */
    $to      = 'kartik.gupta@ucicarbons.com';
    $subject = '[UCI Carbons] ' . $type_label . ' from ' . $name . ( $company ? ' — ' . $company : '' );
    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>',
        'Cc: info@ucicarbons.com',
        'Bcc: amit.imysecy@gmail.com', // restored 09-Sep-2026 - AK's own address
    ];
    wp_mail( $to, $subject, $html_email, $headers );

    /* ── 5. Auto-reply to sender ── */
    $auto_messages = [
        'contact'  => "Thank you for getting in touch with UCI Carbons. Our team will review your enquiry and respond within 24 hours.",
        'tds'      => "Your Technical Data Sheet request has been received. We'll send the relevant TDS within 24 hours.",
        'brochure' => "Your brochure request has been received. We'll send the documentation within 24 hours.",
    ];
    $auto_msg = $auto_messages[$type] ?? $auto_messages['contact'];

    $site_url = esc_url( home_url('/') );

    /* Build optional summary lines for auto-reply */
    $summary_extra = '';
    if ( $company ) $summary_extra .= '<strong>Company:</strong> ' . esc_html($company) . '<br>';
    if ( $country ) $summary_extra .= '<strong>Country:</strong> ' . esc_html($country) . '<br>';
    if ( $grade )   $summary_extra .= '<strong>Grade:</strong> '   . esc_html($grade)   . '<br>';
    if ( $app )     $summary_extra .= '<strong>Application:</strong> ' . esc_html($app) . '<br>';
    if ( $brtype )  $summary_extra .= '<strong>Brochure type:</strong> ' . esc_html($brtype) . '<br>';

    $auto_html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>We received your enquiry</title></head>
<body style="margin:0;padding:0;background:#F0F4F8;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#F0F4F8;padding:32px 0">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%">
  <tr>
    <td style="background:#0F3549;border-radius:16px 16px 0 0;padding:36px 36px 28px">
      <div style="font-size:11px;font-weight:700;letter-spacing:2.5px;color:#0296D8;text-transform:uppercase;font-family:monospace;margin-bottom:10px">UCI Carbons · Confirmation</div>
      <div style="font-size:24px;font-weight:800;color:#fff;letter-spacing:-0.5px;line-height:1.2">We've received your<br><span style="color:#29B6F6">{$type_label}.</span></div>
    </td>
  </tr>
  <tr>
    <td style="background:#fff;padding:32px 36px">
      <p style="font-size:16px;color:#0F3549;margin:0 0 18px;line-height:1.7">Dear <strong>{$name}</strong>,</p>
      <p style="font-size:15px;color:#5A7A8A;margin:0 0 28px;line-height:1.75">{$auto_msg}</p>
      <table width="100%" cellpadding="0" cellspacing="0" style="background:#F8FDFF;border:1px solid #E4F2F8;border-radius:12px;overflow:hidden">
        <tr>
          <td style="padding:20px 24px">
            <div style="font-size:11px;font-weight:700;color:#7B9BAA;letter-spacing:2px;text-transform:uppercase;font-family:monospace;margin-bottom:12px">Your submission summary</div>
            <div style="font-size:14px;color:#0F3549;line-height:1.8">
              <strong>Form:</strong> {$type_label}<br>
              <strong>Name:</strong> {$name}<br>
              <strong>Email:</strong> {$email}<br>
              {$summary_extra}
            </div>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td style="background:#fff;padding:0 36px 32px">
      <p style="font-size:14px;color:#5A7A8A;line-height:1.7;margin:0 0 20px">If you have an urgent requirement or need to speak directly with our technical team:</p>
      <table cellpadding="0" cellspacing="0">
        <tr>
          <td style="padding-right:16px">
            <a href="mailto:kartik.gupta@ucicarbons.com" style="display:inline-block;background:#0296D8;color:#fff;font-size:13px;font-weight:700;padding:11px 22px;border-radius:9px;text-decoration:none">✉ Email Kartik directly</a>
          </td>
          <td>
            <a href="tel:+919501005395" style="display:inline-block;background:#0F3549;color:#fff;font-size:13px;font-weight:700;padding:11px 22px;border-radius:9px;text-decoration:none">📱 +91 9501005395</a>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td style="background:#F8FDFF;border:1px solid #E4F2F8;border-top:none;border-radius:0 0 16px 16px;padding:20px 36px">
      <div style="font-size:12px;color:#7B9BAA;line-height:1.6">
        <strong style="color:#0F3549">Rajindra Carbons · UCI Carbons Group</strong><br>
        Bhagowal, Hoshiarpur, Punjab 146001, India · <a href="{$site_url}" style="color:#0296D8;text-decoration:none">ucicarbons.com</a>
      </div>
    </td>
  </tr>
</table>
</td></tr>
</table>
</body>
</html>
HTML;

    wp_mail(
        $email,
        'We received your ' . strtolower($type_label) . ' — UCI Carbons',
        $auto_html,
        ['Content-Type: text/html; charset=UTF-8']
    );

    /* ── 6. Return thank-you URL (separate page per type for SEO + tracking) ── */
    $slugs = [
        'contact'  => 'thank-you-contact',
        'tds'      => 'thank-you-tds',
        'brochure' => 'thank-you-brochure',
    ];
    $slug = $slugs[$type] ?? 'thank-you-contact';

    $params = ['name' => $name];
    if ( $type === 'tds' && $grade ) $params['grade'] = $grade;

    $thank_you_url = home_url( '/' . $slug . '/?' . http_build_query($params) );
    wp_send_json_success(['redirect' => $thank_you_url]);
}

/* ══════════════════════════════════════════════
   CARBON AI — AJAX ENDPOINT
   Calls the Anthropic Claude API server-side.
   Set your key in WP Admin → Settings → Carbon AI
   ══════════════════════════════════════════════ */
add_action('wp_ajax_uci_carbon_ai',        'uci_carbon_ai_handler');
add_action('wp_ajax_nopriv_uci_carbon_ai', 'uci_carbon_ai_handler');

function uci_carbon_ai_handler() {
    check_ajax_referer('uci_carbon_ai', 'nonce');

    // Basic rate limit: 20 requests per IP per hour
    $ip    = sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    $t_key = 'uci_ai_rate_' . md5($ip);
    $count = (int) get_transient($t_key);
    if ($count >= 20) {
        wp_send_json_error(['message' => 'Rate limit reached. Please try again in an hour.'], 429);
    }
    set_transient($t_key, $count + 1, HOUR_IN_SECONDS);

    $question = sanitize_text_field(wp_unslash($_POST['question'] ?? ''));
    if (!$question) {
        wp_send_json_error(['message' => 'Please enter a question.']);
    }

    $api_key = defined('UCI_ANTHROPIC_KEY')
        ? UCI_ANTHROPIC_KEY
        : get_option('uci_anthropic_key', '');

    if (!$api_key) {
        wp_send_json_error(['message' => 'Carbon AI is not yet configured. Please email kartik.gupta@ucicarbons.com for help.']);
    }

    $system = 'You are Carbon AI, the expert assistant for Rajindra Carbons (UCI Carbons Group) — a leading activated carbon manufacturer in Hoshiarpur, Punjab, India, founded 1969. Three generations of the Gupta family. 8 rotary kilns, 9,000 MT/year, exports to 30+ countries, 500+ clients.

PRODUCTS:
• Wood PAC: UW-22, UW-24, UW-26, UW-32, DL Premium (BP/USP pharma grade)
• Wood GAC: NC 830, NC 850, UCI RC830, UCI RC1240, 55N, 55NS
• Wood Pellets: P-3 (3mm, 950 m²/g), P-4 (4mm, 1300 m²/g), P-6 (6mm)
• Coconut GAC: 4×8C, 12×40C, 12×40B (food/beverage acid-washed), 12×30B
• Gold Recovery: ACGOLD 6SZ, ACGOLD 8SZ, ACGOLD 6SFY (K-value up to 65, CTC 55–65%, hardness 99%+)
• Edible Oil: AC 200E (200 mesh), AC 325E (325 mesh)
• Gas/CBRN: ABEK Grade (impregnated), PAC-950, PAC-1300

CERTIFICATIONS: ISO 9001, ISO 14001, HALAL, KOSHER, NSF, GMP
CONTACT: kartik.gupta@ucicarbons.com · +91 9501005395

Answer questions about activated carbon products, grades, applications, specifications, shipping, and certifications concisely (3–5 sentences max). Be specific and technical. If someone wants a TDS or sample, direct them to kartik.gupta@ucicarbons.com.';

    $response = wp_remote_post('https://api.anthropic.com/v1/messages', [
        'timeout' => 30,
        'headers' => [
            'x-api-key'         => $api_key,
            'anthropic-version' => '2023-06-01',
            'content-type'      => 'application/json',
        ],
        'body' => json_encode([
            'model'      => 'claude-haiku-4-5-20251001',
            'max_tokens' => 450,
            'system'     => $system,
            'messages'   => [
                ['role' => 'user', 'content' => $question],
            ],
        ]),
    ]);

    if (is_wp_error($response)) {
        wp_send_json_error(['message' => 'Connection error — please try again or email kartik.gupta@ucicarbons.com.']);
    }

    $body   = json_decode(wp_remote_retrieve_body($response), true);
    $answer = $body['content'][0]['text'] ?? '';

    if (!$answer) {
        wp_send_json_error(['message' => 'No response — please email kartik.gupta@ucicarbons.com directly.']);
    }

    wp_send_json_success(['answer' => $answer]);
}


/* ── SIMPLE NAV WALKER (used when mega menu is disabled) ── */
/* Desktop simple nav walker — supports 3 levels */
class UCI_Simple_Walker extends Walker_Nav_Menu {
    function start_lvl( &$output, $depth = 0, $args = null ) {
        // depth 0 = opening level-2 ul; depth 1 = opening level-3 ul
        $cls     = $depth === 0 ? 'nav-submenu' : 'nav-sub-submenu';
        $output .= '<ul class="' . $cls . '">';
    }
    function end_lvl( &$output, $depth = 0, $args = null ) {
        $output .= '</ul>';
    }
    function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $classes   = empty($item->classes) ? [] : (array) $item->classes;
        $is_active = in_array('current-menu-item', $classes) || in_array('current-menu-ancestor', $classes);
        $has_child = in_array('menu-item-has-children', $classes);
        $li_class  = $has_child ? 'has-sub' : '';
        if ( $depth === 0 ) {
            $a_class = 'nav-link' . ($is_active ? ' active' : '');
        } elseif ( $depth === 1 ) {
            $a_class = 'nav-sublink' . ($is_active ? ' active' : '');
        } else {
            $a_class = 'nav-sublink nav-sublink--deep' . ($is_active ? ' active' : '');
        }
        $output .= '<li class="' . esc_attr($li_class) . '">';
        $output .= '<a href="' . esc_url($item->url) . '" class="' . esc_attr($a_class) . '">'
                 . esc_html($item->title) . '</a>';
    }
    function end_el( &$output, $item, $depth = 0, $args = null ) {
        $output .= '</li>';
    }
}

/* Mobile nav walker — all levels visible, outputs <a> + <div> (no <li>) */
class UCI_Mobile_Walker extends Walker_Nav_Menu {
    function start_lvl( &$output, $depth = 0, $args = null ) {
        $output .= '<div class="' . ( $depth === 0 ? 'mobile-sub-links' : 'mobile-sub-sub-links' ) . '">';
    }
    function end_lvl( &$output, $depth = 0, $args = null ) {
        $output .= '</div>';
    }
    function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $classes   = empty($item->classes) ? [] : (array) $item->classes;
        $is_active = in_array('current-menu-item', $classes) || in_array('current-menu-ancestor', $classes);
        if ( $depth === 0 ) {
            $cls = 'mobile-nav-link' . ($is_active ? ' active' : '');
        } elseif ( $depth === 1 ) {
            $cls = 'mobile-sub-link' . ($is_active ? ' active' : '');
        } else {
            $cls = 'mobile-sub-link mobile-sub-link--deep' . ($is_active ? ' active' : '');
        }
        // Self-contained <a> — children are appended by start_lvl after this
        $output .= '<a href="' . esc_url($item->url) . '" class="' . esc_attr($cls) . '">'
                 . esc_html($item->title) . '</a>';
    }
    function end_el( &$output, $item, $depth = 0, $args = null ) {
        // <a> already closed in start_el; children div opened/closed by start_lvl/end_lvl
    }
}


/* ── CARBON AI SETTINGS PAGE ── */
add_action('admin_menu', function () {
    add_options_page('Carbon AI', 'Carbon AI', 'manage_options', 'uci-carbon-ai', 'uci_carbon_ai_settings_page');
});

function uci_carbon_ai_settings_page() {
    if (isset($_POST['uci_anthropic_key']) && check_admin_referer('uci_ai_save')) {
        update_option('uci_anthropic_key', sanitize_text_field(wp_unslash($_POST['uci_anthropic_key'])));
        update_option('uci_mega_menu_enabled',    isset($_POST['uci_mega_menu_enabled'])    ? '1' : '0');
        update_option('uci_product_card_links',   isset($_POST['uci_product_card_links'])   ? '1' : '0');
        echo '<div class="notice notice-success is-dismissible"><p>Settings saved.</p></div>';
    }
    $key          = get_option('uci_anthropic_key', '');
    $mega_enabled = get_option('uci_mega_menu_enabled', '1');
    $card_links   = get_option('uci_product_card_links', '0');
    ?>
    <div class="wrap">
        <h1>Carbon AI — Settings</h1>
        <form method="post">
            <?php wp_nonce_field('uci_ai_save'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">Anthropic API Key</th>
                    <td>
                        <input type="password" name="uci_anthropic_key" value="<?php echo esc_attr($key); ?>"
                               class="regular-text" placeholder="sk-ant-api03-…" />
                        <p class="description">
                            Get your key from
                            <a href="https://console.anthropic.com/keys" target="_blank">console.anthropic.com/keys</a>.
                            Uses <code>claude-haiku-4-5-20251001</code> — fast and cost-effective.
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Mega Menu</th>
                    <td>
                        <label>
                            <input type="checkbox" name="uci_mega_menu_enabled" value="1" <?php checked('1', $mega_enabled); ?> />
                            Enable mega menu on Products, Applications, Knowledge &amp; ESG nav items
                        </label>
                        <p class="description">When unchecked, desktop nav uses the WordPress registered menu (Appearance → Menus) with 3-level submenus.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Mobile Menu</th>
                    <td>
                        <p class="description">
                            Manage the mobile hamburger menu from
                            <a href="<?php echo esc_url(admin_url('nav-menus.php')); ?>">Appearance → Menus</a>
                            — assign any menu to the <strong>Mobile Menu</strong> location.
                            If no menu is assigned there, the mobile overlay falls back to the hardcoded links.
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Product Card Links</th>
                    <td>
                        <label>
                            <input type="checkbox" name="uci_product_card_links" value="1" <?php checked('1', $card_links); ?> />
                            Enable grade card click-through to individual product pages
                        </label>
                        <p class="description">When unchecked, clicking a grade card does nothing — only the TDS and Sample buttons work.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button('Save Settings'); ?>
        </form>
    </div>
    <?php
}



/*
|--------------------------------------------------------------------------
| Excerpt Length
|--------------------------------------------------------------------------
*/

function uci_excerpt_length() {
    return 25;
}
add_filter('excerpt_length', 'uci_excerpt_length');


/*
|--------------------------------------------------------------------------
| Body Classes
|--------------------------------------------------------------------------
*/

function uci_body_classes($classes) {

    if (is_front_page()) {
        $classes[] = 'uci-home';
    }

    if (is_page_template('page-specifications.php')) {
        $classes[] = 'uci-specifications-page';
    }

    return $classes;
}
add_filter('body_class', 'uci_body_classes');
/* ══════════════════════════════════════════════
   TEMPLATE LOADER — force correct template by page slug
   Fixes thank-you pages not rendering when template
   isn't explicitly set in WP Admin → Page Attributes.
   ══════════════════════════════════════════════ */
add_filter('template_include', 'uci_force_slug_template', 99);
function uci_force_slug_template( $template ) {

    // Dead "Method 1" branch removed 09-Sep-2026 15:04 IST - AK (WP's own
    // template hierarchy already resolves page-{slug}.php before this filter runs)
    // ── Catch thank-you pages by URL even if WP page missing ──
    // Strips subdirectory prefix (e.g. /ucicarbons/) before matching.
    $path     = trim( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' );
    $site_sub = trim( parse_url( site_url(), PHP_URL_PATH ), '/' );
    if ( $site_sub && strpos( $path, $site_sub . '/' ) === 0 ) {
        $path = substr( $path, strlen( $site_sub ) + 1 );
    }
    $path = trim( $path, '/' );

    $thankyou = [
        'thank-you-tds',
        'thank-you-contact',
        'thank-you-brochure',
    ];
    if ( in_array( $path, $thankyou, true ) ) {
        $slug_tpl = get_template_directory() . '/page-' . $path . '.php';
        if ( file_exists( $slug_tpl ) ) {
            return $slug_tpl;
        }
    }

    return $template;
}

/* ══════════════════════════════════════════════
   BROCHURE PDF DOWNLOAD — server-side handler
   Fixes Mobile Safari (ignores HTML download attr)
   and adds Content-Length for Chrome progress bar.
   URL: /?dl=brochure
   ══════════════════════════════════════════════ */
add_action( 'template_redirect', 'uci_serve_brochure_pdf' );
function uci_serve_brochure_pdf() {
    if ( ( $_GET['dl'] ?? '' ) !== 'brochure' ) return;

    $file = get_template_directory() . '/assets/pdf/rajindra-carbons-brochure-2026.pdf';

    if ( ! file_exists( $file ) ) {
        wp_die( 'Brochure file not found. Please contact us at info@ucicarbons.com', 404 );
    }

    // Clear any WP output buffering so headers can be sent cleanly
    while ( ob_get_level() ) {
        ob_end_clean();
    }

    $filesize = filesize( $file );
    $filename = 'Rajindra-Carbons-Brochure-2026.pdf';

    // iOS (iPhone/iPad) cannot save "attachment" downloads — open inline in browser PDF viewer
    $ua     = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $is_ios = (bool) preg_match( '/iPad|iPhone|iPod/i', $ua );
    $disp   = $is_ios ? 'inline' : 'attachment';

    header( 'Content-Type: application/pdf' );
    header( 'Content-Disposition: ' . $disp . '; filename="' . $filename . '"' );
    header( 'Content-Length: ' . $filesize );
    header( 'Content-Transfer-Encoding: binary' );
    header( 'Accept-Ranges: bytes' );
    header( 'Cache-Control: private, no-cache, must-revalidate' );
    header( 'Pragma: public' );
    header( 'Expires: 0' );

    readfile( $file );
    exit;
}
