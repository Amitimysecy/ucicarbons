<?php
$contact_url = get_permalink(get_page_by_path('contact'))     ?: home_url('/contact/');
$tds_url     = get_permalink(get_page_by_path('request-tds')) ?: home_url('/request-tds/');
?>
<!-- ── EXPORT COUNTRIES ── -->
<section class="export-section">
    <div class="wrap">
        <div class="sec-eyebrow" style="color:var(--blue)">Global reach</div>
        <h2 class="sec-h" style="color:#fff;margin-bottom:12px">From USA to Japan.<br>From Sweden to Africa.</h2>
        <p style="font-size:16px;color:var(--soft);max-width:500px;margin-bottom:36px">Exporting activated carbon to 30+ countries across six continents. UCI carbon ships wherever purity matters.</p>

        <div class="export-flags-wrap" style="margin-bottom:28px">
            <div class="export-flag-chip"><span class="flag">🇺🇸</span><span class="cname">USA</span></div>
            <div class="export-flag-chip"><span class="flag">🇧🇷</span><span class="cname">Brazil</span></div>
            <div class="export-flag-chip"><span class="flag">🇬🇧</span><span class="cname">UK</span></div>
            <div class="export-flag-chip"><span class="flag">🇩🇪</span><span class="cname">Germany</span></div>
            <div class="export-flag-chip"><span class="flag">🇳🇱</span><span class="cname">Netherlands</span></div>
            <div class="export-flag-chip"><span class="flag">🇸🇪</span><span class="cname">Sweden</span></div>
            <div class="export-flag-chip"><span class="flag">🇦🇺</span><span class="cname">Australia</span></div>
            <div class="export-flag-chip"><span class="flag">🇯🇵</span><span class="cname">Japan</span></div>
            <div class="export-flag-chip"><span class="flag">🇮🇩</span><span class="cname">Indonesia</span></div>
            <div class="export-flag-chip"><span class="flag">🌍</span><span class="cname">Africa</span></div>
            <div class="export-flag-chip"><span class="flag">🇲🇾</span><span class="cname">Malaysia</span></div>
            <div class="export-flag-chip"><span class="flag">🇿🇦</span><span class="cname">South Africa</span></div>
            <div class="export-flag-chip"><span class="flag">🇨🇦</span><span class="cname">Canada</span></div>
            <div class="export-flag-chip"><span class="flag">🇸🇬</span><span class="cname">Singapore</span></div>
            <div class="export-flag-chip"><span class="flag">🇮🇹</span><span class="cname">Italy</span></div>
            <div class="export-flag-chip"><span class="flag">🇻🇳</span><span class="cname">Vietnam</span></div>
            <div class="export-flag-chip"><span class="flag">🇦🇷</span><span class="cname">Argentina</span></div>
        </div>

        <!-- Stats row hidden per client request -->
        <?php /* <div class="export-stat-row">...</div> */ ?>
    </div>
</section>

<!-- ── ENQUIRY CTA BANNER ── -->
<div class="enquiry-banner">
    <h2>Problem-free carbon. On time, every time.</h2>
    <p>55 years of production. 30+ export markets. TDS and samples within 24 hours.</p>
    <div class="actions">
        <a class="btn-white" href="<?php echo esc_url($tds_url); ?>">Request a sample →</a>
        <a class="btn-outline-white" href="<?php echo esc_url($contact_url); ?>">Contact us</a>
    </div>
</div>
