<?php
/**
 * Template Name: Request TDS
 * Technical Data Sheet request form.
 */
get_header();

$contact_url  = home_url('/contact/');
$brochure_url = home_url('/request-brochure/');

// Pre-fill grade from query string (passed from product/application pages)
$grade_param = isset($_GET['grade']) ? sanitize_text_field($_GET['grade']) : '';

// Full grade list — values must match exactly what page-products.php passes
$grade_options = [
    // Wood PAC
    'UCI UW-22', 'UCI UW-24', 'UCI UW-26', 'UCI UW-32',
    'UCI NC 830', 'UCI NC 850',
    // Wood GAC
    'UCI RC 830 (3×6)', 'UCI RC 830 (8×16)', 'UCI GX-50',
    // Coconut
    'UCI ACGOLD 6SZ', 'UCI ACGOLD 8SFY',
    'UCI 4×8C', 'UCI 12×40C', 'UCI 12×30B', 'UCI 12×40B',
    // Pellets
    'UCI P-3', 'UCI P-4', 'UCI P-6',
    // Specialty
    'UCI DL Premium', 'UCI 55N', 'UCI 55NS', 'UCI Ag-PAC', 'UCI ABEK-P3',
    // Other
    'Other / multiple grades',
];
?>

<main id="primary" class="site-main">

<!-- ── HERO ── -->
<section style="padding:100px 0 56px;background:var(--dark);position:relative;overflow:hidden">
    <div style="position:absolute;width:500px;height:500px;border-radius:50%;background:var(--blue);opacity:.06;top:-120px;right:-80px;pointer-events:none"></div>
    <div class="wrap" style="position:relative;z-index:2">
        <div style="font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:2.5px;color:var(--blue);text-transform:uppercase;margin-bottom:12px">Get in touch</div>
        <h1 style="font-size:clamp(36px,4.5vw,60px);font-weight:800;letter-spacing:-2px;color:#fff;line-height:1;margin-bottom:16px">
            Let's talk about<br><span style="color:var(--sky)">your specification.</span>
        </h1>
        <p style="font-size:17px;color:rgba(255,255,255,0.5);max-width:500px;line-height:1.7">
            We respond within 24 hours. Technical data sheets issued within 1 business day.
        </p>
    </div>
</section>

<!-- ── FORM + SIDEBAR ── -->
<section class="section" style="background:var(--bg)">
    <div class="wrap">
        <div class="g2" style="align-items:start;gap:48px">

            <!-- LEFT: form with tab bar -->
            <div class="contact-form-wrap">

                <!-- Tab bar -->
                <div class="contact-tabs">
                    <a href="<?php echo esc_url($contact_url); ?>" class="contact-tab">Contact Us</a>
                    <a href="<?php echo esc_url( home_url('/request-tds/') ); ?>" class="contact-tab active">Request TDS</a>
                    <a href="<?php echo esc_url($brochure_url); ?>" class="contact-tab">Request Brochure</a>
                </div>

                <!-- Form body -->
                <div style="padding:32px">
                    <div id="cform-tds-success" style="display:none;text-align:center;padding:48px 20px">
                        <div style="font-size:40px;margin-bottom:16px">✓</div>
                        <div style="font-size:20px;font-weight:800;color:var(--dark);margin-bottom:8px">TDS request received.</div>
                        <p style="font-size:15px;color:var(--soft)">Thank you. We'll respond within 24 hours.</p>
                    </div>
                    <form id="cform-tds" onsubmit="uciSubmitForm(event,'cform-tds','cform-tds-success')" novalidate>
                        <?php wp_nonce_field('uci_contact','uci_nonce'); ?>
                        <input type="hidden" name="form_type" value="tds">
                        <div class="form-row">
                            <div class="form-group"><label class="form-label">Name *</label><input class="form-input" type="text" name="name" required placeholder="Your name"></div>
                            <div class="form-group"><label class="form-label">Company</label><input class="form-input" type="text" name="company" placeholder="Company name"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group"><label class="form-label">Email *</label><input class="form-input" type="email" name="email" required placeholder="email@company.com"></div>
                            <div class="form-group"><label class="form-label">Country</label><input class="form-input" type="text" name="country" placeholder="Country"></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Grade(s) of interest *</label>
                            <select class="form-input" name="grade" required>
                                <option value="">Select grade...</option>
                                <?php foreach ( $grade_options as $grade ) : ?>
                                    <option value="<?php echo esc_attr($grade); ?>" <?php selected( $grade_param, $grade ); ?>>
                                        <?php echo esc_html($grade); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Application context</label>
                            <textarea class="form-input" name="message" placeholder="Brief description of your application — helps us send the most relevant TDS version..."></textarea>
                        </div>
                        <button type="submit" class="btn-primary" style="width:100%;padding:14px;font-size:15px;text-align:center">Request TDS →</button>
                    </form>
                </div>
            </div>

            <!-- RIGHT: sidebar -->
            <?php get_template_part('template-parts/contact-sidebar'); ?>

        </div>
    </div>
</section>

<!-- ── AI STRIP ── -->
<section class="page-ai-strip">
    <div class="wrap">
        <div class="pai-inner">
            <div class="pai-avatar">
                <svg viewBox="0 0 24 24" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M9 9h.01M15 9h.01"/><path d="M9.5 15a3.5 3.5 0 005 0" stroke-linecap="round"/></svg>
            </div>
            <div class="pai-text">
                <div class="pai-eyebrow">Carbon Expert AI</div>
                <div class="pai-headline">Not sure which grade to request?</div>
                <div class="pai-sub">Describe your application — our AI will recommend the right TDS.</div>
            </div>
            <div class="pai-input-row">
                <input type="text" id="paiInput-tds" class="pai-input" placeholder="e.g. 500 m³/day potable water, chlorine and taste removal..." onkeydown="if(event.key==='Enter')aiAskFromPage('paiInput-tds')">
                <button class="pai-btn" onclick="aiAskFromPage('paiInput-tds')">Ask AI →</button>
            </div>
        </div>
    </div>
</section>

</main>

<?php get_footer(); ?>
