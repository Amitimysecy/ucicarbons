<?php
/**
 * Template Name: Thank You — Contact
 * Slug: thank-you-contact
 * Shown after Contact Us form submission.
 * SEO: noindex (conversion page). GA4 event: generate_lead / contact_form_submit
 */

/* ── SEO: noindex + canonical + title override ── */
add_action('wp_head', function() {
    echo '<meta name="robots" content="noindex, nofollow">' . "\n";
    echo '<title>Thank You — We\'ll Be In Touch | UCI Carbons</title>' . "\n";
    echo '<meta name="description" content="Your message has been received by UCI Carbons. Our team will respond within 24 hours.">' . "\n";
    /* ── GA4 lead event ── */
    echo '<script>
window.addEventListener("load", function(){
  if(typeof gtag === "function"){
    gtag("event","generate_lead",{event_category:"contact_form",event_label:"contact_us",value:1});
  }
  if(window.dataLayer){
    window.dataLayer.push({event:"form_submission",form_type:"contact_us",page_path:window.location.pathname});
  }
});
</script>' . "\n";
}, 1);

get_header();

$name  = isset($_GET['name']) ? sanitize_text_field($_GET['name']) : '';
$first = $name ? explode(' ', trim($name))[0] : '';
?>

<main id="primary" class="site-main">

<!-- ══ HERO ══ -->
<section style="padding:100px 0 0;background:var(--dark);position:relative;overflow:hidden">
    <div style="position:absolute;width:700px;height:700px;border-radius:50%;background:#0296D8;opacity:.07;top:-220px;right:-140px;pointer-events:none"></div>
    <div style="position:absolute;width:280px;height:280px;border-radius:50%;background:#29B6F6;opacity:.05;bottom:-60px;left:-50px;pointer-events:none"></div>

    <div class="wrap" style="position:relative;z-index:2">
        <div style="max-width:680px;padding-bottom:64px">

            <!-- Badge -->
            <div style="display:inline-flex;align-items:center;gap:10px;background:rgba(2,150,216,0.15);border:1px solid rgba(2,150,216,0.3);border-radius:100px;padding:8px 18px;margin-bottom:28px">
                <span style="width:8px;height:8px;border-radius:50%;background:#0296D8;display:inline-block;animation:pulse 2s infinite"></span>
                <span style="font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:2px;color:#0296D8;text-transform:uppercase">Message received</span>
            </div>

            <h1 style="font-size:clamp(42px,5.5vw,72px);font-weight:800;letter-spacing:-3px;color:#fff;line-height:1;margin-bottom:22px">
                <?php if ($first): ?><span style="color:var(--sky)"><?php echo esc_html($first); ?>,</span><br><?php endif; ?>
                We'll be in touch<br><span style="color:rgba(255,255,255,0.3)">within 24 hours.</span>
            </h1>

            <p style="font-size:18px;color:rgba(255,255,255,0.5);line-height:1.8;max-width:520px;margin-bottom:40px">
                Your enquiry has landed with our technical team. Expect a personalised response directly to your inbox — usually same business day.
            </p>

            <div style="display:flex;flex-wrap:wrap;gap:14px">
                <a href="<?php echo esc_url(home_url('/products/')); ?>" class="btn-primary">Browse our grades →</a>
                <a href="<?php echo esc_url(home_url('/')); ?>" style="display:inline-flex;align-items:center;font-size:14px;font-weight:700;color:rgba(255,255,255,0.4);text-decoration:none;padding:12px 0;transition:color .15s" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.4)'">← Back to home</a>
            </div>
        </div>
    </div>
</section>

<!-- ══ WHAT HAPPENS NEXT ══ -->
<section class="section" style="background:#fff">
    <div class="wrap">
        <div style="text-align:center;margin-bottom:52px">
            <div style="font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:2.5px;color:var(--soft);text-transform:uppercase;margin-bottom:10px">What happens next</div>
            <h2 style="font-size:clamp(28px,3vw,42px);font-weight:800;letter-spacing:-1.5px;color:var(--dark)">Our response process</h2>
        </div>

        <div style="position:relative">
            <!-- connector line -->
            <div style="position:absolute;top:32px;left:calc(16.66% + 24px);right:calc(16.66% + 24px);height:2px;background:linear-gradient(90deg,#0296D8,#29B6F6);opacity:.2"></div>

            <div class="g3" style="gap:32px;position:relative">

                <div style="text-align:center;padding:0 16px">
                    <div style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#0296D8,#29B6F6);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:22px;box-shadow:0 8px 20px rgba(2,150,216,0.3)">✉</div>
                    <div style="font-size:11px;font-weight:700;font-family:var(--mono);letter-spacing:2px;color:var(--blue);text-transform:uppercase;margin-bottom:8px">Step 01</div>
                    <div style="font-size:16px;font-weight:800;color:var(--dark);margin-bottom:10px">Enquiry logged</div>
                    <p style="font-size:14px;color:var(--soft);line-height:1.7;margin:0">Instantly saved to our CRM and assigned to the right specialist.</p>
                </div>

                <div style="text-align:center;padding:0 16px">
                    <div style="width:64px;height:64px;border-radius:50%;background:#EFF9FF;border:2px solid #0296D8;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:22px">🔬</div>
                    <div style="font-size:11px;font-weight:700;font-family:var(--mono);letter-spacing:2px;color:var(--blue);text-transform:uppercase;margin-bottom:8px">Step 02</div>
                    <div style="font-size:16px;font-weight:800;color:var(--dark);margin-bottom:10px">Technical review</div>
                    <p style="font-size:14px;color:var(--soft);line-height:1.7;margin:0">Our team reviews your application context to craft a relevant, accurate reply.</p>
                </div>

                <div style="text-align:center;padding:0 16px">
                    <div style="width:64px;height:64px;border-radius:50%;background:#EFF9FF;border:2px solid #0296D8;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:22px">📬</div>
                    <div style="font-size:11px;font-weight:700;font-family:var(--mono);letter-spacing:2px;color:var(--blue);text-transform:uppercase;margin-bottom:8px">Step 03</div>
                    <div style="font-size:16px;font-weight:800;color:var(--dark);margin-bottom:10px">Reply in 24 hours</div>
                    <p style="font-size:14px;color:var(--soft);line-height:1.7;margin:0">A personalised response — often with grade recommendations or a call invite.</p>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- ══ DIRECT CONTACT ══ -->
<section style="background:var(--bg);padding:56px 0">
    <div class="wrap">
        <div style="background:var(--dark);border-radius:20px;padding:40px 48px;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:28px">
            <div>
                <div style="font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:2px;color:var(--blue);text-transform:uppercase;margin-bottom:10px">Need it faster?</div>
                <div style="font-size:22px;font-weight:800;color:#fff;letter-spacing:-0.5px;margin-bottom:6px">Speak directly with Kartik Gupta, VP</div>
                <div style="font-size:15px;color:rgba(255,255,255,0.45)">UCI Carbons Group · Available Mon–Sat, 9 am–6 pm IST</div>
            </div>
            <div style="display:flex;flex-wrap:wrap;gap:12px">
                <a href="mailto:kartik.gupta@ucicarbons.com" style="display:inline-flex;align-items:center;gap:8px;background:var(--blue);color:#fff;font-size:14px;font-weight:700;padding:12px 22px;border-radius:10px;text-decoration:none">✉ Email directly</a>
                <a href="tel:+919501005395" style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.08);color:#fff;font-size:14px;font-weight:700;padding:12px 22px;border-radius:10px;text-decoration:none;border:1px solid rgba(255,255,255,0.15)">📱 +91 95010 05395</a>
            </div>
        </div>
    </div>
</section>

<!-- ══ EXPLORE WHILE YOU WAIT ══ -->
<section class="section" style="background:#fff">
    <div class="wrap">
        <div style="text-align:center;margin-bottom:40px">
            <div style="font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:2.5px;color:var(--soft);text-transform:uppercase;margin-bottom:10px">While you wait</div>
            <h2 style="font-size:clamp(24px,2.5vw,34px);font-weight:800;letter-spacing:-1px;color:var(--dark)">Explore UCI Carbons</h2>
        </div>
        <div class="g3" style="gap:24px">
            <?php
            $cards = [
                ['url'=>'/products/',     'emoji'=>'⚗️',  'title'=>'Product Range',     'desc'=>'Browse our full activated carbon catalogue — granular, powdered, pelletised, speciality grades.'],
                ['url'=>'/applications/', 'emoji'=>'🏭',  'title'=>'Applications',       'desc'=>'Water treatment, gold recovery, pharma, food & beverage — find the grade for your process.'],
                ['url'=>'/esg/',          'emoji'=>'🌱',  'title'=>'Sustainability',      'desc'=>'100% renewable energy, zero waste targets, HALAL · KOSHER · NSF certified.'],
            ];
            foreach ($cards as $card): ?>
            <a href="<?php echo esc_url(home_url($card['url'])); ?>" style="display:block;background:var(--bg);border:1px solid var(--rule);border-radius:16px;padding:28px;text-decoration:none;transition:transform .15s,box-shadow .15s,border-color .15s" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,.07)';this.style.borderColor='var(--blue)'" onmouseout="this.style.transform='';this.style.boxShadow='';this.style.borderColor='var(--rule)'">
                <div style="font-size:26px;margin-bottom:12px"><?php echo $card['emoji']; ?></div>
                <div style="font-size:15px;font-weight:800;color:var(--dark);margin-bottom:7px"><?php echo esc_html($card['title']); ?></div>
                <div style="font-size:13px;color:var(--soft);line-height:1.65"><?php echo esc_html($card['desc']); ?></div>
            </a>
            <?php endforeach; ?>
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
                <div class="pai-headline">Have a technical question in the meantime?</div>
                <div class="pai-sub">Our AI can answer grade, specification, and application questions right now.</div>
            </div>
            <div class="pai-input-row">
                <input type="text" id="paiInput-ty" class="pai-input" placeholder="e.g. What iodine number does UCI UW 26 have?" onkeydown="if(event.key==='Enter')aiAskFromPage('paiInput-ty')">
                <button class="pai-btn" onclick="aiAskFromPage('paiInput-ty')">Ask AI →</button>
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
