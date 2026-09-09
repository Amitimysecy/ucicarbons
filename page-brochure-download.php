<?php
/**
 * Template Name: Brochure Download
 * Standalone gated download — compact single-viewport design.
 */

/* ── Handle form submission ───────────────────────────────── */
$download_ready = false;
$errors         = [];
$submitted_name = '';

if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['brochure_nonce'] ) ) {
    if ( ! wp_verify_nonce( $_POST['brochure_nonce'], 'brochure_download' ) ) {
        $errors[] = 'Security check failed. Please try again.';
    } else {
        $name  = sanitize_text_field( $_POST['bd_name'] ?? '' );
        $email = sanitize_email( $_POST['bd_email'] ?? '' );
        $co    = sanitize_text_field( $_POST['bd_company'] ?? '' );

        if ( strlen( $name ) < 2 )  $errors[] = 'Please enter your full name.';
        if ( ! is_email( $email ) ) $errors[] = 'Please enter a valid email address.';

        if ( empty( $errors ) ) {
            $download_ready = true;
            $submitted_name = $name;

            $post_id = wp_insert_post([
                'post_type'   => 'uci_enquiry',
                'post_title'  => $name . ' — Brochure Download',
                'post_status' => 'publish',
                'post_date'   => current_time('mysql'),
            ]);
            if ( $post_id && ! is_wp_error( $post_id ) ) {
                update_post_meta( $post_id, '_enq_type',    'brochure_dl' );
                update_post_meta( $post_id, '_enq_name',    $name );
                update_post_meta( $post_id, '_enq_email',   $email );
                update_post_meta( $post_id, '_enq_company', $co );
                update_post_meta( $post_id, '_enq_ip',      $_SERVER['REMOTE_ADDR'] ?? '' );
            }

            $site    = get_bloginfo('name');
            $ts      = current_time('d M Y, H:i');
            $pdf_file = get_template_directory() . '/assets/pdf/rajindra-carbons-brochure-2026.pdf';
            $attach  = file_exists( $pdf_file ) ? [ $pdf_file ] : [];

            // ── 1. Admin notification ──────────────────────────────
            wp_mail(
                'kartik.gupta@ucicarbons.com',
                "{$site} — Brochure Download: {$name}",
                "<p><strong>Name:</strong> {$name}<br>"
                . "<strong>Email:</strong> {$email}<br>"
                . "<strong>Company:</strong> " . ( $co ?: '—' ) . "<br>"
                . "<strong>Time:</strong> {$ts}</p>",
                [
                    'Content-Type: text/html; charset=UTF-8',
                    'Cc: info@ucicarbons.com',
                    // Bcc to personal Gmail removed 09-Sep-2026 15:04 IST - AK
                ]
            );

            // ── 2. Auto-reply to user with PDF attached ────────────
            $co_line = $co ? "<br><strong>Company:</strong> {$co}" : '';
            wp_mail(
                $email,
                'Your Rajindra Carbons Brochure — UCI Group',
                "<!DOCTYPE html><html><body style='font-family:Arial,sans-serif;color:#1a2e3b;max-width:560px;margin:0 auto;padding:24px'>"
                . "<div style='text-align:center;margin-bottom:24px'>"
                . "<p style='font-size:11px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:#0296D8;margin:0'>Rajindra Carbons · UCI Group</p>"
                . "</div>"
                . "<h2 style='font-size:20px;color:#0D2E42;margin:0 0 12px'>Dear {$name},</h2>"
                . "<p style='font-size:15px;line-height:1.7;color:#444;margin:0 0 16px'>"
                . "Thank you for your interest in Rajindra Carbons. Please find our <strong>Company Brochure 2026</strong> attached to this email."
                . "</p>"
                . "<p style='font-size:15px;line-height:1.7;color:#444;margin:0 0 24px'>"
                . "The brochure covers our complete product range of activated carbon grades for water treatment, pharma, food &amp; industrial applications."
                . "</p>"
                . "<div style='background:#f0f6fa;border-radius:10px;padding:16px 20px;margin-bottom:24px;font-size:13px;color:#64808f'>"
                . "If you have any questions or would like to request samples or a Technical Data Sheet, reply to this email or contact us at "
                . "<a href='mailto:info@ucicarbons.com' style='color:#0296D8'>info@ucicarbons.com</a>."
                . "</div>"
                . "<p style='font-size:13px;color:#aaa;border-top:1px solid #e5e5e5;padding-top:16px;margin:0'>"
                . "Rajindra Carbons · UCI Carbons Group · <a href='https://ucicarbons.com' style='color:#0296D8'>ucicarbons.com</a>"
                . "</p>"
                . "</body></html>",
                [ 'Content-Type: text/html; charset=UTF-8' ],
                $attach
            );
        }
    }
}

$pdf_url     = home_url( '/?dl=brochure' );
$favicon_url = get_template_directory_uri() . '/assets/images/favicon-32x32.png';
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Company Brochure 2026 — Rajindra Carbons · UCI Group</title>
<meta name="robots" content="noindex">
<link rel="icon" href="<?php echo esc_url( $favicon_url ); ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Serif+Display&display=swap" rel="stylesheet">
<?php wp_head(); ?>
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --navy:   #0D2E42;
    --blue:   #0296D8;
    --blue2:  #024A80;
    --ink:    #1a2e3b;
    --muted:  #64808f;
    --border: #dde8ed;
    --bg:     #eef4f7;
    --white:  #ffffff;
}

body {
    font-family: 'DM Sans', -apple-system, sans-serif;
    background: var(--bg);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px 16px;
}
body::before {
    content: '';
    position: fixed;
    inset: 0;
    background-image:
        linear-gradient(rgba(2,150,216,.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(2,150,216,.04) 1px, transparent 1px);
    background-size: 32px 32px;
    pointer-events: none;
}

/* ── Card ── */
.bd-wrap {
    width: 100%;
    max-width: 430px;
    position: relative;
}
.bd-card {
    background: var(--white);
    border-radius: 20px;
    box-shadow:
        0 1px 2px rgba(0,0,0,.04),
        0 8px 24px rgba(13,46,66,.10),
        0 32px 64px rgba(13,46,66,.08);
    overflow: hidden;
}

/* ── Header ── */
.bd-head {
    background: linear-gradient(145deg, #0D2E42 0%, #024A80 55%, #0369A1 100%);
    padding: 22px 26px 18px;
    position: relative;
    overflow: hidden;
}
.bd-head::after {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 160px; height: 160px;
    border-radius: 50%;
    background: rgba(77,216,255,.06);
    pointer-events: none;
}
.bd-logo-row {
    display: flex;
    align-items: center;
    gap: 9px;
    margin-bottom: 14px;
}
.bd-logo-drop { width: 30px; height: 30px; flex-shrink: 0; }
.bd-logo-drop svg { width: 100%; height: 100%; }
.bd-logo-name {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.8px;
    text-transform: uppercase;
    color: rgba(255,255,255,.9);
    line-height: 1.2;
}
.bd-logo-sub {
    font-size: 9px;
    font-weight: 500;
    letter-spacing: 1.4px;
    text-transform: uppercase;
    color: rgba(255,255,255,.35);
    margin-top: 2px;
}
.bd-head-label {
    font-size: 9.5px;
    font-weight: 600;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: rgba(77,216,255,.7);
    margin-bottom: 4px;
}
.bd-head-title {
    font-family: 'DM Serif Display', Georgia, serif;
    font-size: 21px;
    color: #fff;
    line-height: 1.2;
    letter-spacing: -.2px;
}
.bd-head-desc {
    font-size: 12px;
    color: rgba(255,255,255,.42);
    margin-top: 5px;
    line-height: 1.5;
}

/* ── Body ── */
.bd-body { padding: 20px 26px 24px; }

.bd-error {
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 8px;
    padding: 9px 13px;
    font-size: 13px;
    color: #b91c1c;
    margin-bottom: 12px;
}

.bd-row { margin-bottom: 11px; }

.bd-label {
    display: block;
    font-size: 11px;
    font-weight: 600;
    color: var(--ink);
    letter-spacing: .2px;
    margin-bottom: 4px;
}
.bd-label span { color: #b0bec5; font-weight: 500; }

.bd-input {
    width: 100%;
    padding: 9px 12px;
    border: 1.5px solid var(--border);
    border-radius: 8px;
    font-size: 14px;
    font-family: inherit;
    color: var(--ink);
    background: #f7fbfd;
    transition: border-color .15s, box-shadow .15s, background .15s;
}
.bd-input:focus {
    outline: none;
    border-color: var(--blue);
    background: var(--white);
    box-shadow: 0 0 0 3px rgba(2,150,216,.10);
}
.bd-input::placeholder { color: #a8bec8; }

.bd-submit {
    margin-top: 14px;
    width: 100%;
    padding: 11px;
    background: linear-gradient(135deg, #0296D8 0%, #024A80 100%);
    color: #fff;
    border: none;
    border-radius: 9px;
    font-size: 14px;
    font-weight: 600;
    font-family: inherit;
    cursor: pointer;
    letter-spacing: .1px;
    transition: opacity .15s, transform .1s, box-shadow .15s;
    box-shadow: 0 4px 14px rgba(2,150,216,.28);
}
.bd-submit:hover {
    opacity: .93;
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(2,150,216,.34);
}
.bd-submit:active { transform: translateY(0); }

.bd-privacy {
    font-size: 10.5px;
    color: #a0b4bc;
    text-align: center;
    margin-top: 9px;
    line-height: 1.5;
}

/* ── Success ── */
.bd-success { text-align: center; padding: 4px 0 0; }
.bd-success-check {
    width: 50px; height: 50px;
    border-radius: 50%;
    background: #d1fae5;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 12px;
}
.bd-success-check svg { width: 24px; height: 24px; color: #059669; }
.bd-success-title {
    font-family: 'DM Serif Display', Georgia, serif;
    font-size: 19px;
    color: var(--navy);
    margin-bottom: 5px;
}
.bd-success-sub {
    font-size: 13px;
    color: var(--muted);
    margin-bottom: 18px;
    line-height: 1.55;
}
.bd-dl-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 26px;
    background: linear-gradient(135deg, #0296D8, #024A80);
    color: #fff;
    text-decoration: none;
    border-radius: 9px;
    font-size: 13.5px;
    font-weight: 600;
    box-shadow: 0 4px 14px rgba(2,150,216,.30);
    transition: opacity .15s, transform .15s;
}
.bd-dl-btn:hover { opacity: .92; transform: translateY(-2px); }
.bd-file-info {
    font-size: 10.5px;
    color: #b0bec5;
    margin-top: 9px;
    font-family: monospace;
    letter-spacing: .3px;
}

/* ── Footer ── */
.bd-foot {
    margin-top: 12px;
    font-size: 11px;
    color: #8aa5b0;
    text-align: center;
}
.bd-foot a { color: var(--blue); text-decoration: none; }
.bd-foot a:hover { text-decoration: underline; }

@media (max-width: 480px) {
    .bd-head { padding: 18px 20px 14px; }
    .bd-body { padding: 16px 20px 20px; }
}
</style>
</head>
<body>

<div class="bd-wrap">
    <div class="bd-card">

        <div class="bd-head">
            <div class="bd-logo-row">
                <div class="bd-logo-drop">
                    <svg viewBox="0 0 52 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="dg" x1="50%" y1="0%" x2="50%" y2="100%">
                                <stop offset="0%"   stop-color="#4DD8FF"/>
                                <stop offset="40%"  stop-color="#0296D8"/>
                                <stop offset="100%" stop-color="#024A80"/>
                            </linearGradient>
                        </defs>
                        <path d="M26 4C26 4 5 26 5 40a21 21 0 0042 0C47 26 26 4 26 4z" fill="url(#dg)"/>
                        <path d="M26 10C26 10 14 24 12 34" stroke="rgba(255,255,255,0.28)" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                        <circle cx="26" cy="41" r="8"   fill="none" stroke="rgba(255,255,255,0.18)" stroke-width="1"/>
                        <circle cx="26" cy="41" r="3.5" fill="rgba(255,255,255,0.25)"/>
                    </svg>
                </div>
                <div>
                    <div class="bd-logo-name">Rajindra Carbons</div>
                    <div class="bd-logo-sub">UCI Carbons Group · Est. 1969</div>
                </div>
            </div>
            <div class="bd-head-label">Company Brochure 2026</div>
            <h1 class="bd-head-title">Download Our Brochure</h1>
            <p class="bd-head-desc">Product range, grades &amp; specs — water treatment, pharma &amp; food.</p>
        </div>

        <div class="bd-body">

        <?php if ( $download_ready ) : ?>

            <div class="bd-success">
                <div class="bd-success-check">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6L9 17l-5-5"/>
                    </svg>
                </div>
                <h2 class="bd-success-title">Thank you, <?php echo esc_html( $submitted_name ); ?>!</h2>
                <p class="bd-success-sub">Your brochure is ready to download.<br><strong>We have also sent a copy to your email address.</strong></p>
                <a href="<?php echo esc_url( $pdf_url ); ?>" class="bd-dl-btn">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/>
                        <line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    Download Brochure PDF
                </a>
                <div class="bd-file-info">PDF · ~1.3 MB</div>
            </div>

        <?php else : ?>

            <?php if ( ! empty( $errors ) ) : ?>
            <div class="bd-error"><?php echo esc_html( implode( ' ', $errors ) ); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <?php wp_nonce_field( 'brochure_download', 'brochure_nonce' ); ?>

                <div class="bd-row">
                    <label class="bd-label" for="bd_name">Full Name *</label>
                    <input class="bd-input" type="text" id="bd_name" name="bd_name"
                           placeholder="e.g. Ramesh Patel"
                           value="<?php echo esc_attr( $_POST['bd_name'] ?? '' ); ?>"
                           required autocomplete="name">
                </div>

                <div class="bd-row">
                    <label class="bd-label" for="bd_email">Email Address *</label>
                    <input class="bd-input" type="email" id="bd_email" name="bd_email"
                           placeholder="e.g. ramesh@company.com"
                           value="<?php echo esc_attr( $_POST['bd_email'] ?? '' ); ?>"
                           required autocomplete="email">
                </div>

                <div class="bd-row">
                    <label class="bd-label" for="bd_company">Company <span>(optional)</span></label>
                    <input class="bd-input" type="text" id="bd_company" name="bd_company"
                           placeholder="e.g. ABC Water Solutions"
                           value="<?php echo esc_attr( $_POST['bd_company'] ?? '' ); ?>"
                           autocomplete="organization">
                </div>

                <button type="submit" class="bd-submit">Get Brochure →</button>
            </form>
            <p class="bd-privacy">🔒 We never share your details. No spam, ever.</p>

        <?php endif; ?>

        </div>
    </div>

    <div class="bd-foot">
        <a href="https://ucicarbons.com/">← Back to ucicarbons.com</a>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
