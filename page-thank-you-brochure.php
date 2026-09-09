<?php
/**
 * Template Name: Thank You — Brochure
 * Slug: thank-you-brochure
 * Shown after Request Brochure form submission.
 * SEO: noindex. GA4 event: brochure_request_submit
 */

add_action('wp_head', function() {
    echo '<meta name="robots" content="noindex, nofollow">' . "\n";
    echo '<title>Brochure Request Received — UCI Carbons</title>' . "\n";
    echo '<meta name="description" content="Your brochure request has been received. UCI Carbons will send the documentation within 24 hours.">' . "\n";
    echo '<script>
window.addEventListener("load", function(){
  if(typeof gtag === "function"){
    gtag("event","generate_lead",{event_category:"contact_form",event_label:"brochure_request",value:2});
    gtag("event","brochure_request_submit",{event_category:"conversion"});
  }
  if(window.dataLayer){
    window.dataLayer.push({event:"form_submission",form_type:"brochure_request",page_path:window.location.pathname});
  }
});
</script>' . "\n";
}, 1);

get_header();

$name    = isset($_GET['name']) ? sanitize_text_field($_GET['name']) : '';
$first   = $name ? explode(' ', trim($name))[0] : '';
?>

<main id="primary" class="site-main">

<!-- ══ HERO ══ -->
<section style="padding:100px 0 0;background:var(--dark);position:relative;overflow:hidden">
    <div style="position:absolute;width:700px;height:700px;border-radius:50%;background:#059669;opacity:.07;top:-220px;right:-140px;pointer-events:none"></div>
    <div style="position:absolute;width:280px;height:280px;border-radius:50%;background:#34D399;opacity:.05;bottom:-60px;left:-50px;pointer-events:none"></div>

    <div class="wrap" style="position:relative;z-index:2">
        <div style="max-width:680px;padding-bottom:64px">

            <div style="display:inline-flex;align-items:center;gap:10px;background:rgba(5,150,105,0.15);border:1px solid rgba(5,150,105,0.3);border-radius:100px;padding:8px 18px;margin-bottom:28px">
                <span style="width:8px;height:8px;border-radius:50%;background:#059669;display:inline-block;animation:pulse 2s infinite"></span>
                <span style="font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:2px;color:#34D399;text-transform:uppercase">Brochure request received</span>
            </div>

            <h1 style="font-size:clamp(42px,5.5vw,72px);font-weight:800;letter-spacing:-3px;color:#fff;line-height:1;margin-bottom:22px">
                <?php if ($first): ?><span style="color:#34D399"><?php echo esc_html($first); ?>,</span><br><?php endif; ?>
                Documentation<br><span style="color:rgba(255,255,255,0.3)">coming your way.</span>
            </h1>

            <p style="font-size:18px;color:rgba(255,255,255,0.5);line-height:1.8;max-width:520px;margin-bottom:40px">
                We'll send the brochure(s) you selected to your inbox within <strong style="color:rgba(255,255,255,0.75)">24 hours</strong>. Keep an eye out for an email from <span style="color:#34D399;font-family:var(--mono);font-size:16px">kartik.gupta@ucicarbons.com</span>.
            </p>

            <div style="display:flex;flex-wrap:wrap;gap:14px">
                <a href="<?php echo esc_url(home_url('/esg/')); ?>" style="display:inline-flex;align-items:center;background:#059669;color:#fff;font-size:15px;font-weight:700;padding:14px 28px;border-radius:12px;text-decoration:none;gap:8px">Explore our ESG story →</a>
                <a href="<?php echo esc_url(home_url('/request-brochure/')); ?>" style="display:inline-flex;align-items:center;font-size:14px;font-weight:700;color:rgba(255,255,255,0.4);text-decoration:none;padding:12px 0;transition:color .15s" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.4)'">← Request another brochure</a>
            </div>
        </div>
    </div>
</section>

<!-- ══ BROCHURES OVERVIEW ══ -->
<section class="section" style="background:#fff">
    <div class="wrap">
        <div style="text-align:center;margin-bottom:48px">
            <div style="font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:2.5px;color:var(--soft);text-transform:uppercase;margin-bottom:10px">What we're sending</div>
            <h2 style="font-size:clamp(28px,3vw,42px);font-weight:800;letter-spacing:-1.5px;color:var(--dark)">Our documentation library</h2>
        </div>

        <div class="g3" style="gap:20px">
            <?php
            $brochures = [
                ['icon'=>'📦', 'title'=>'General product brochure', 'desc'=>'Complete overview of all activated carbon grades across wood and coconut shell base.'],
                ['icon'=>'💧', 'title'=>'Water Treatment', 'desc'=>'Grade-by-grade guide for potable water, wastewater, and industrial effluent applications.'],
                ['icon'=>'🪙', 'title'=>'Gold Recovery (ACGOLD)', 'desc'=>'Specialist documentation for our ACGOLD series including CIP/CIL performance data.'],
                ['icon'=>'💊', 'title'=>'Pharmaceutical', 'desc'=>'BP/USP-grade activated carbons for API manufacturing and pharmaceutical purification.'],
                ['icon'=>'🍃', 'title'=>'Food & Beverage', 'desc'=>'Coconut shell grades for sugar decolourisation, beverage clarification, and flavour removal.'],
                ['icon'=>'🌿', 'title'=>'ESG & Sustainability', 'desc'=>'Our renewable energy commitment, zero waste targets, and circular-economy approach.'],
            ];
            foreach ($brochures as $b):
            ?>
            <div style="background:var(--bg);border:1px solid var(--rule);border-radius:14px;padding:22px 20px">
                <div style="font-size:22px;margin-bottom:10px"><?php echo $b['icon']; ?></div>
                <div style="font-size:14px;font-weight:800;color:var(--dark);margin-bottom:6px"><?php echo esc_html($b['title']); ?></div>
                <div style="font-size:13px;color:var(--soft);line-height:1.6"><?php echo esc_html($b['desc']); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ══ CERTIFICATIONS PREVIEW ══ -->
<section style="background:var(--bg);padding:56px 0">
    <div class="wrap">
        <div style="background:var(--dark);border-radius:20px;padding:40px 48px">
            <div style="text-align:center;margin-bottom:36px">
                <div style="font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:2.5px;color:#34D399;text-transform:uppercase;margin-bottom:10px">Included in certifications pack</div>
                <h2 style="font-size:clamp(22px,2.5vw,32px);font-weight:800;letter-spacing:-0.5px;color:#fff">Our accreditations</h2>
            </div>
            <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:16px">
                <?php
                $certs = ['ISO 9001:2015','ISO 14001:2015','HALAL Certified','KOSHER Certified','NSF / ANSI 61','FSSAI Approved','Reach Compliant','RoHS Compliant'];
                foreach ($certs as $cert):
                ?>
                <div style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.12);border-radius:8px;padding:10px 18px;font-size:13px;font-weight:700;color:#fff;font-family:var(--mono);letter-spacing:0.5px"><?php echo esc_html($cert); ?></div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- ══ DIRECT CONTACT ══ -->
<section style="background:#fff;padding:56px 0">
    <div class="wrap">
        <div style="background:var(--dark);border-radius:20px;padding:40px 48px;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:28px">
            <div>
                <div style="font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:2px;color:#34D399;text-transform:uppercase;margin-bottom:10px">Need it sooner?</div>
                <div style="font-size:22px;font-weight:800;color:#fff;letter-spacing:-0.5px;margin-bottom:6px">Reach Kartik Gupta directly</div>
                <div style="font-size:15px;color:rgba(255,255,255,0.45)">VP — UCI Carbons · Available Mon–Sat, 9 am–6 pm IST</div>
            </div>
            <div style="display:flex;flex-wrap:wrap;gap:12px">
                <a href="mailto:kartik.gupta@ucicarbons.com?subject=Urgent Brochure Request" style="display:inline-flex;align-items:center;gap:8px;background:#059669;color:#fff;font-size:14px;font-weight:700;padding:12px 22px;border-radius:10px;text-decoration:none">✉ Email for urgent request</a>
                <a href="tel:+919501005395" style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.08);color:#fff;font-size:14px;font-weight:700;padding:12px 22px;border-radius:10px;text-decoration:none;border:1px solid rgba(255,255,255,0.15)">📱 +91 95010 05395</a>
            </div>
        </div>
    </div>
</section>

<!-- ══ AI STRIP ══ -->
<section class="page-ai-strip">
    <div class="wrap">
        <div class="pai-inner">
            <div class="pai-avatar">
                <svg viewBox="0 0 24 24" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M9 9h.01M15 9h.01"/><path d="M9.5 15a3.5 3.5 0 005 0" stroke-linecap="round"/></svg>
            </div>
            <div class="pai-text">
                <div class="pai-eyebrow">Carbon Expert AI</div>
                <div class="pai-headline">Questions about our certifications or grades?</div>
                <div class="pai-sub">Ask our AI anything about specifications, compliance, or applications while you wait.</div>
            </div>
            <div class="pai-input-row">
                <input type="text" id="paiInput-ty-br" class="pai-input" placeholder="e.g. Is your coconut carbon NSF 61 certified?" onkeydown="if(event.key==='Enter')aiAskFromPage('paiInput-ty-br')">
                <button class="pai-btn" onclick="aiAskFromPage('paiInput-ty-br')">Ask AI →</button>
            </div>
        </div>
    </div>
</section>

<style>
@keyframes pulse {
  0%,100%{opacity:1;transform:scale(1)}
  50%{opacity:.5;transform:scale(1.4)}
}
</style>

</main>
<?php get_footer(); ?>
