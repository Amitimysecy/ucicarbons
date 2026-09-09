<?php
/**
 * Template Name: Thank You — TDS
 * Slug: thank-you-tds
 * Shown after Request TDS form submission.
 * SEO: noindex. GA4 event: tds_request_submit
 */

add_action('wp_head', function() {
    echo '<meta name="robots" content="noindex, nofollow">' . "\n";
    echo '<title>TDS Request Received — UCI Carbons</title>' . "\n";
    echo '<meta name="description" content="Your Technical Data Sheet request has been received. UCI Carbons will send the TDS within 1 business day.">' . "\n";
    echo '<script>
window.addEventListener("load", function(){
  if(typeof gtag === "function"){
    gtag("event","generate_lead",{event_category:"contact_form",event_label:"tds_request",value:3});
    gtag("event","tds_request_submit",{event_category:"conversion"});
  }
  if(window.dataLayer){
    window.dataLayer.push({event:"form_submission",form_type:"tds_request",page_path:window.location.pathname});
  }
});
</script>' . "\n";
}, 1);

get_header();

$name  = isset($_GET['name']) ? sanitize_text_field($_GET['name']) : '';
$grade = isset($_GET['grade']) ? sanitize_text_field($_GET['grade']) : '';
$first = $name ? explode(' ', trim($name))[0] : '';
?>

<main id="primary" class="site-main">

<!-- ══ HERO ══ -->
<section style="padding:100px 0 0;background:var(--dark);position:relative;overflow:hidden">
    <div style="position:absolute;width:700px;height:700px;border-radius:50%;background:#7C3AED;opacity:.07;top:-220px;right:-140px;pointer-events:none"></div>
    <div style="position:absolute;width:280px;height:280px;border-radius:50%;background:#A78BFA;opacity:.05;bottom:-60px;left:-50px;pointer-events:none"></div>

    <div class="wrap" style="position:relative;z-index:2">
        <div style="max-width:680px;padding-bottom:64px">

            <div style="display:inline-flex;align-items:center;gap:10px;background:rgba(124,58,237,0.15);border:1px solid rgba(124,58,237,0.3);border-radius:100px;padding:8px 18px;margin-bottom:28px">
                <span style="width:8px;height:8px;border-radius:50%;background:#7C3AED;display:inline-block;animation:pulse 2s infinite"></span>
                <span style="font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:2px;color:#A78BFA;text-transform:uppercase">TDS request received</span>
            </div>

            <h1 style="font-size:clamp(42px,5.5vw,72px);font-weight:800;letter-spacing:-3px;color:#fff;line-height:1;margin-bottom:22px">
                <?php if ($first): ?><span style="color:#A78BFA"><?php echo esc_html($first); ?>,</span><br><?php endif; ?>
                Your data sheet<br><span style="color:rgba(255,255,255,0.3)">is on its way.</span>
            </h1>

            <?php if ($grade): ?>
            <div style="display:inline-flex;align-items:center;gap:10px;background:rgba(124,58,237,0.12);border:1px solid rgba(124,58,237,0.25);border-radius:10px;padding:12px 20px;margin-bottom:28px">
                <span style="font-family:var(--mono);font-size:13px;font-weight:700;color:#A78BFA">Grade requested:</span>
                <span style="font-family:var(--mono);font-size:13px;font-weight:800;color:#fff"><?php echo esc_html($grade); ?></span>
            </div>
            <?php endif; ?>

            <p style="font-size:18px;color:rgba(255,255,255,0.5);line-height:1.8;max-width:520px;margin-bottom:40px">
                We'll prepare the Technical Data Sheet(s) you requested and send them to your email address within <strong style="color:rgba(255,255,255,0.75)">1 business day</strong> — typically much sooner.
            </p>

            <div style="display:flex;flex-wrap:wrap;gap:14px">
                <a href="<?php echo esc_url(home_url('/products/')); ?>" style="display:inline-flex;align-items:center;background:#7C3AED;color:#fff;font-size:15px;font-weight:700;padding:14px 28px;border-radius:12px;text-decoration:none;gap:8px">View all grades →</a>
                <a href="<?php echo esc_url(home_url('/request-tds/')); ?>" style="display:inline-flex;align-items:center;font-size:14px;font-weight:700;color:rgba(255,255,255,0.4);text-decoration:none;padding:12px 0;transition:color .15s" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.4)'">← Request another TDS</a>
            </div>
        </div>
    </div>
</section>

<!-- ══ WHAT HAPPENS ══ -->
<section class="section" style="background:#fff">
    <div class="wrap">
        <div style="text-align:center;margin-bottom:52px">
            <div style="font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:2.5px;color:var(--soft);text-transform:uppercase;margin-bottom:10px">TDS delivery process</div>
            <h2 style="font-size:clamp(28px,3vw,42px);font-weight:800;letter-spacing:-1.5px;color:var(--dark)">How it works</h2>
        </div>

        <div style="position:relative">
            <div style="position:absolute;top:32px;left:calc(16.66% + 24px);right:calc(16.66% + 24px);height:2px;background:linear-gradient(90deg,#7C3AED,#A78BFA);opacity:.2"></div>
            <div class="g3" style="gap:32px;position:relative">

                <div style="text-align:center;padding:0 16px">
                    <div style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#7C3AED,#A78BFA);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:22px;box-shadow:0 8px 20px rgba(124,58,237,0.3)">📋</div>
                    <div style="font-size:11px;font-weight:700;font-family:var(--mono);letter-spacing:2px;color:#7C3AED;text-transform:uppercase;margin-bottom:8px">Step 01</div>
                    <div style="font-size:16px;font-weight:800;color:var(--dark);margin-bottom:10px">Request logged</div>
                    <p style="font-size:14px;color:var(--soft);line-height:1.7;margin:0">Your TDS request is recorded and the grade(s) identified.</p>
                </div>

                <div style="text-align:center;padding:0 16px">
                    <div style="width:64px;height:64px;border-radius:50%;background:#F5F3FF;border:2px solid #7C3AED;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:22px">🔬</div>
                    <div style="font-size:11px;font-weight:700;font-family:var(--mono);letter-spacing:2px;color:#7C3AED;text-transform:uppercase;margin-bottom:8px">Step 02</div>
                    <div style="font-size:16px;font-weight:800;color:var(--dark);margin-bottom:10px">Version selected</div>
                    <p style="font-size:14px;color:var(--soft);line-height:1.7;margin:0">We select the most current, application-relevant TDS version for your use case.</p>
                </div>

                <div style="text-align:center;padding:0 16px">
                    <div style="width:64px;height:64px;border-radius:50%;background:#F5F3FF;border:2px solid #7C3AED;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:22px">📨</div>
                    <div style="font-size:11px;font-weight:700;font-family:var(--mono);letter-spacing:2px;color:#7C3AED;text-transform:uppercase;margin-bottom:8px">Step 03</div>
                    <div style="font-size:16px;font-weight:800;color:var(--dark);margin-bottom:10px">Sent within 1 day</div>
                    <p style="font-size:14px;color:var(--soft);line-height:1.7;margin:0">PDF TDS delivered to your inbox — often with a short covering note from our tech team.</p>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- ══ WHAT'S IN A TDS ══ -->
<section style="background:var(--bg);padding:56px 0">
    <div class="wrap">
        <div style="display:flex;flex-wrap:wrap;gap:48px;align-items:center">
            <div style="flex:1;min-width:280px">
                <div style="font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:2.5px;color:#7C3AED;text-transform:uppercase;margin-bottom:14px">What you'll receive</div>
                <h2 style="font-size:clamp(24px,2.5vw,36px);font-weight:800;letter-spacing:-1px;color:var(--dark);margin-bottom:16px">Everything you need<br>for specification approval</h2>
                <p style="font-size:15px;color:var(--soft);line-height:1.75;margin-bottom:0">Our TDS documents include particle size, CTC activity, iodine number, surface area, moisture content, pH, hardness, and all relevant certifications — formatted for procurement and QA teams.</p>
            </div>
            <div style="flex:1;min-width:260px">
                <?php
                $tds_items = [
                    ['🔬', 'Physical & chemical specs'],
                    ['📐', 'Mesh size & particle distribution'],
                    ['✅', 'Applicable certifications (ISO/Halal/Kosher/NSF)'],
                    ['🧪', 'Test method references (ASTM/IS)'],
                    ['📦', 'Packaging & storage guidance'],
                    ['🏭', 'Manufacturing plant details'],
                ];
                foreach ($tds_items as $item):
                ?>
                <div style="display:flex;align-items:center;gap:14px;padding:12px 0;border-bottom:1px solid var(--rule)">
                    <span style="font-size:18px;width:28px;text-align:center;flex-shrink:0"><?php echo $item[0]; ?></span>
                    <span style="font-size:14px;color:var(--dark);font-weight:600"><?php echo esc_html($item[1]); ?></span>
                </div>
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
                <div style="font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:2px;color:#7C3AED;text-transform:uppercase;margin-bottom:10px">Need it urgently?</div>
                <div style="font-size:22px;font-weight:800;color:#fff;letter-spacing:-0.5px;margin-bottom:6px">Call or email Kartik Gupta directly</div>
                <div style="font-size:15px;color:rgba(255,255,255,0.45)">VP — UCI Carbons · Available Mon–Sat, 9 am–6 pm IST</div>
            </div>
            <div style="display:flex;flex-wrap:wrap;gap:12px">
                <a href="mailto:kartik.gupta@ucicarbons.com?subject=Urgent TDS Request" style="display:inline-flex;align-items:center;gap:8px;background:#7C3AED;color:#fff;font-size:14px;font-weight:700;padding:12px 22px;border-radius:10px;text-decoration:none">✉ Email for urgent TDS</a>
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
                <div class="pai-headline">Want a quick spec preview?</div>
                <div class="pai-sub">Ask our AI about any grade's typical specs while you wait for the formal TDS.</div>
            </div>
            <div class="pai-input-row">
                <input type="text" id="paiInput-ty-tds" class="pai-input" placeholder="e.g. What's the surface area of ACGOLD 6SZ?" onkeydown="if(event.key==='Enter')aiAskFromPage('paiInput-ty-tds')">
                <button class="pai-btn" onclick="aiAskFromPage('paiInput-ty-tds')">Ask AI →</button>
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
