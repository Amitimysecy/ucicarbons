<?php
/**
 * Template Name: Product Grade
 * Individual grade page — content matches the product card view.
 * Shows: grade code, description, base/form tags, app tags, TDS + Sample CTAs.
 */
get_header();

$id        = get_the_ID();
$code      = get_post_meta($id, '_grade_code',         true) ?: get_the_title();
$fam_slug  = get_post_meta($id, '_grade_family_slug',  true) ?: '';
$fam_name  = get_post_meta($id, '_grade_family_name',  true) ?: '';
$color     = get_post_meta($id, '_grade_family_color', true) ?: '#0296D8';
$bg        = get_post_meta($id, '_grade_family_bg',    true) ?: '#EFF9FF';
$form      = get_post_meta($id, '_grade_form',         true) ?: '';
$base      = get_post_meta($id, '_grade_base',         true) ?: '';
$mesh      = get_post_meta($id, '_grade_mesh',         true) ?: '';
$desc      = get_post_meta($id, '_grade_desc',         true) ?: '';
$apps      = json_decode(get_post_meta($id, '_grade_apps', true), true) ?: [];

/* URLs */
$products_url = get_permalink(get_page_by_path('products'))              ?: home_url('/products/');
$family_url   = $fam_slug ? (get_permalink(get_page_by_path('products/' . $fam_slug)) ?: home_url('/products/' . $fam_slug . '/')) : $products_url;
$tds_page     = get_page_by_path('request-tds');
$tds_base     = $tds_page ? get_permalink($tds_page) : home_url('/request-tds/');
$tds_link     = esc_url(add_query_arg('grade', $code, $tds_base));
$cont_page    = get_page_by_path('contact');
$cont_base    = $cont_page ? get_permalink($cont_page) : home_url('/contact/');
$sample_link  = esc_url(add_query_arg('grade', $code, $cont_base));
?>

<main id="primary" class="site-main">

<!-- ══ HERO / CARD VIEW ══ -->
<section style="padding:100px 0 56px;background:var(--dark);position:relative;overflow:hidden">
    <div style="position:absolute;width:500px;height:500px;border-radius:50%;background:<?php echo esc_attr($color); ?>;opacity:.08;top:-150px;right:-80px;pointer-events:none"></div>

    <div class="wrap" style="position:relative;z-index:2">

        <!-- Breadcrumb -->
        <div style="font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:1.5px;color:rgba(255,255,255,0.3);text-transform:uppercase;margin-bottom:28px;display:flex;align-items:center;gap:8px;flex-wrap:wrap">
            <a href="<?php echo esc_url($products_url); ?>" style="color:rgba(255,255,255,0.3);text-decoration:none" onmouseover="this.style.color='<?php echo esc_attr($color); ?>'" onmouseout="this.style.color='rgba(255,255,255,0.3)'">Products</a>
            <span>›</span>
            <?php if ($fam_name && $fam_slug): ?>
            <a href="<?php echo esc_url($family_url); ?>" style="color:rgba(255,255,255,0.3);text-decoration:none" onmouseover="this.style.color='<?php echo esc_attr($color); ?>'" onmouseout="this.style.color='rgba(255,255,255,0.3)'"><?php echo esc_html($fam_name); ?></a>
            <span>›</span>
            <?php endif; ?>
            <span style="color:<?php echo esc_attr($color); ?>"><?php echo esc_html($code); ?></span>
        </div>

        <!-- Card-style content block -->
        <div style="max-width:600px">

            <!-- Grade code -->
            <h1 style="font-family:var(--mono);font-size:clamp(36px,4.5vw,60px);font-weight:800;letter-spacing:-2px;color:#fff;line-height:1;margin-bottom:18px"><?php echo esc_html($code); ?></h1>

            <!-- Description -->
            <?php if ($desc): ?>
            <p style="font-size:17px;color:rgba(255,255,255,0.55);line-height:1.75;margin-bottom:28px"><?php echo esc_html($desc); ?></p>
            <?php endif; ?>

            <!-- Base + Form tags (mirrors .grade-tags-row) -->
            <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:16px">
                <?php if ($base): ?>
                <span style="font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.12);color:rgba(255,255,255,0.55);padding:5px 14px;border-radius:100px"><?php echo esc_html(ucfirst($base)); ?></span>
                <?php endif; ?>
                <?php if ($form): ?>
                <span style="font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;background:<?php echo esc_attr($color); ?>22;border:1px solid <?php echo esc_attr($color); ?>44;color:<?php echo esc_attr($color); ?>;padding:5px 14px;border-radius:100px"><?php echo esc_html(ucfirst($form)); ?></span>
                <?php endif; ?>
                <?php if ($mesh): ?>
                <span style="font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.4);padding:5px 14px;border-radius:100px"><?php echo esc_html($mesh); ?></span>
                <?php endif; ?>
            </div>

            <!-- Application tags (mirrors .grade-app-tags) -->
            <?php if (!empty($apps)): ?>
            <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:36px">
                <?php foreach ($apps as $app): ?>
                <span style="font-size:12px;font-weight:600;color:rgba(255,255,255,0.45);background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);padding:4px 12px;border-radius:6px"><?php echo esc_html($app); ?></span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- CTA buttons (mirrors .grade-ctarow) -->
            <div style="display:flex;flex-wrap:wrap;gap:14px;align-items:center">
                <a href="<?php echo $tds_link; ?>" style="display:inline-flex;align-items:center;background:<?php echo esc_attr($color); ?>;color:#fff;font-size:15px;font-weight:700;padding:14px 28px;border-radius:12px;text-decoration:none;letter-spacing:0.2px">Request TDS →</a>
                <a href="<?php echo $sample_link; ?>" style="display:inline-flex;align-items:center;font-size:14px;font-weight:700;color:rgba(255,255,255,0.5);text-decoration:none;padding:14px 22px;border:1px solid rgba(255,255,255,0.15);border-radius:12px;transition:all .15s" onmouseover="this.style.color='#fff';this.style.borderColor='rgba(255,255,255,0.4)'" onmouseout="this.style.color='rgba(255,255,255,0.5)';this.style.borderColor='rgba(255,255,255,0.15)'">Request Sample</a>
                <a href="<?php echo esc_url($products_url); ?>" style="font-size:13px;font-weight:600;color:rgba(255,255,255,0.25);text-decoration:none;padding:8px 0" onmouseover="this.style.color='rgba(255,255,255,0.6)'" onmouseout="this.style.color='rgba(255,255,255,0.25)'">← All grades</a>
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
                <div class="pai-headline">Questions about <?php echo esc_html($code); ?>?</div>
                <div class="pai-sub">Ask about comparisons, process compatibility, certifications, or which size suits your filter.</div>
            </div>
            <div class="pai-input-row">
                <input type="text" id="paiInput-grade" class="pai-input" placeholder="e.g. How does <?php echo esc_attr($code); ?> compare with ACGOLD 6SZ?" onkeydown="if(event.key==='Enter')aiAskFromPage('paiInput-grade')">
                <button class="pai-btn" onclick="aiAskFromPage('paiInput-grade')">Ask AI →</button>
            </div>
        </div>
    </div>
</section>

</main>
<?php get_footer(); ?>
