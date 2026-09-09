<?php
/**
 * Template Name: Request Brochure
 * Product brochure & documentation request form.
 */
get_header();

$contact_url = home_url('/contact/');
$tds_url     = home_url('/request-tds/');
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
                    <a href="<?php echo esc_url($tds_url); ?>" class="contact-tab">Request TDS</a>
                    <a href="<?php echo esc_url( home_url('/request-brochure/') ); ?>" class="contact-tab active">Request Brochure</a>
                </div>

                <!-- Form body -->
                <div style="padding:32px">
                    <div id="cform-brochure-success" style="display:none;text-align:center;padding:48px 20px">
                        <div style="font-size:40px;margin-bottom:16px">✓</div>
                        <div style="font-size:20px;font-weight:800;color:var(--dark);margin-bottom:8px">Brochure request received.</div>
                        <p style="font-size:15px;color:var(--soft)">Thank you. We'll send it within 24 hours.</p>
                    </div>
                    <form id="cform-brochure" onsubmit="uciSubmitForm(event,'cform-brochure','cform-brochure-success')" novalidate>
                        <?php wp_nonce_field('uci_contact','uci_nonce'); ?>
                        <input type="hidden" name="form_type" value="brochure">
                        <div class="form-row">
                            <div class="form-group"><label class="form-label">Name *</label><input class="form-input" type="text" name="name" required placeholder="Your name"></div>
                            <div class="form-group"><label class="form-label">Company</label><input class="form-input" type="text" name="company" placeholder="Company name"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group"><label class="form-label">Email *</label><input class="form-input" type="email" name="email" required placeholder="email@company.com"></div>
                            <div class="form-group"><label class="form-label">Country</label><input class="form-input" type="text" name="country" placeholder="Country"></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Brochure type</label>
                            <select class="form-input" name="brochure_type">
                                <option value="">Select brochure...</option>
                                <option>General product brochure (all grades)</option>
                                <option>Water Treatment — grades &amp; specs</option>
                                <option>Gold Recovery — ACGOLD series</option>
                                <option>Pharmaceutical — BP/USP grades</option>
                                <option>Food &amp; Beverage — coconut grades</option>
                                <option>Edible Oil — powder grades</option>
                                <option>Air &amp; VOC — granular &amp; pellet</option>
                                <option>ESG &amp; sustainability documentation</option>
                                <option>Certifications pack (ISO/Halal/Kosher/NSF)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-primary" style="width:100%;padding:14px;font-size:15px;text-align:center">Request Brochure →</button>
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
                <div class="pai-headline">Not sure what to ask for?</div>
                <div class="pai-sub">Let our AI identify the right grade before you write to us.</div>
            </div>
            <div class="pai-input-row">
                <input type="text" id="paiInput-brochure" class="pai-input" placeholder="e.g. I need documentation for a pharma audit..." onkeydown="if(event.key==='Enter')aiAskFromPage('paiInput-brochure')">
                <button class="pai-btn" onclick="aiAskFromPage('paiInput-brochure')">Ask AI →</button>
            </div>
        </div>
    </div>
</section>

</main>

<?php get_footer(); ?>
