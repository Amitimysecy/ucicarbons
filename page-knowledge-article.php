<?php
/**
 * Template Name: Knowledge Article
 * Individual knowledge bank article page.
 */
get_header();

$id        = get_the_ID();
$cat       = get_post_meta( $id, '_kb_category',  true );
$read_time = get_post_meta( $id, '_kb_read_time', true );
$level     = get_post_meta( $id, '_kb_level',     true );
$excerpt   = get_post_meta( $id, '_kb_excerpt',   true ) ?: get_the_excerpt();
$img       = get_post_meta( $id, '_kb_image',     true );
$img_bg    = get_post_meta( $id, '_kb_img_bg',    true ) ?: '#0F3549';

$cat_colors = [
    'Fundamentals'    => '#0296D8',
    'Water'           => '#0296D8',
    'Pharma'          => '#0260A8',
    'Gold Recovery'   => '#D97706',
    'Food & Beverage' => '#7C3AED',
    'Air & VOC'       => '#0277BD',
    'Consumer'        => '#7C3AED',
    'ESG'             => '#166534',
];
$accent  = isset( $cat_colors[ $cat ] ) ? $cat_colors[ $cat ] : '#0296D8';
$kb_url  = get_permalink( get_page_by_path( 'knowledge' ) ) ?: home_url( '/knowledge/' );
?>

<main id="primary" class="site-main">

<!-- ── ARTICLE HERO ── -->
<section style="padding:0;background:<?php echo esc_attr($img_bg); ?>;position:relative;overflow:hidden;min-height:440px;display:flex;align-items:flex-end">

    <!-- Background image -->
    <?php if ( $img ) : ?>
    <div style="position:absolute;inset:0;background-image:url('<?php echo esc_url($img); ?>');background-size:cover;background-position:center;opacity:.22"></div>
    <?php endif; ?>

    <!-- Dark gradient overlay -->
    <div style="position:absolute;inset:0;background:linear-gradient(to top,<?php echo esc_attr($img_bg); ?> 0%,rgba(0,0,0,0) 60%)"></div>

    <!-- Accent circle -->
    <div style="position:absolute;width:500px;height:500px;border-radius:50%;background:<?php echo esc_attr($accent); ?>;opacity:.1;top:-150px;right:-80px;pointer-events:none"></div>

    <div class="wrap" style="position:relative;z-index:2;padding-bottom:52px;padding-top:80px;width:100%">

        <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;flex-wrap:wrap">
            <a href="<?php echo esc_url( $kb_url ); ?>" style="display:inline-flex;align-items:center;gap:6px;font-family:var(--mono);font-size:10px;font-weight:700;letter-spacing:1.5px;color:rgba(255,255,255,0.5);text-transform:uppercase;text-decoration:none">← KNOWLEDGE BANK</a>
            <?php if ( $cat ) : ?>
            <span style="display:inline-block;background:<?php echo esc_attr($accent); ?>;color:#fff;font-family:var(--mono);font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;padding:4px 14px;border-radius:20px">
                <?php echo esc_html( $cat ); ?>
            </span>
            <?php endif; ?>
        </div>

        <h1 style="font-size:clamp(28px,3.8vw,52px);font-weight:800;letter-spacing:-1.5px;color:#fff;line-height:1.1;max-width:820px;margin-bottom:18px">
            <?php the_title(); ?>
        </h1>

        <?php if ( $excerpt ) : ?>
        <p style="font-size:18px;color:rgba(255,255,255,0.6);max-width:640px;line-height:1.7;margin-bottom:20px">
            <?php echo esc_html( $excerpt ); ?>
        </p>
        <?php endif; ?>

        <?php if ( $read_time || $level ) : ?>
        <div style="display:flex;align-items:center;gap:10px;font-family:var(--mono);font-size:11px;color:rgba(255,255,255,0.4);letter-spacing:1px">
            <?php if ( $read_time ) echo '<span>' . esc_html($read_time) . '</span>'; ?>
            <?php if ( $read_time && $level ) echo '<span style="opacity:.4">·</span>'; ?>
            <?php if ( $level )     echo '<span>' . esc_html($level)     . '</span>'; ?>
        </div>
        <?php endif; ?>

    </div>
</section>

<!-- ── ARTICLE BODY ── -->
<section class="section" style="background:var(--bg)">
    <div class="wrap">
        <div style="display:grid;grid-template-columns:1fr 280px;gap:64px;align-items:start;max-width:1100px">

            <!-- Main content -->
            <div class="article-content">
                <?php
                // Output content without wpautop (already has proper HTML paragraphs)
                remove_filter( 'the_content', 'wpautop' );
                the_content();
                add_filter( 'the_content', 'wpautop' );
                ?>
            </div>

            <!-- Sidebar -->
            <aside style="position:sticky;top:100px">
                <div style="background:var(--white);border:1px solid var(--rule);border-radius:16px;padding:24px">

                    <?php if ( $cat ) : ?>
                    <div style="font-family:var(--mono);font-size:10px;font-weight:700;letter-spacing:1.5px;color:var(--soft);text-transform:uppercase;margin-bottom:12px">Category</div>
                    <div style="display:inline-block;background:<?php echo esc_attr($accent); ?>;color:#fff;font-size:12px;font-weight:700;padding:4px 14px;border-radius:20px;margin-bottom:20px"><?php echo esc_html($cat); ?></div>
                    <?php endif; ?>

                    <?php if ( $read_time ) : ?>
                    <div style="font-family:var(--mono);font-size:10px;font-weight:700;letter-spacing:1.5px;color:var(--soft);text-transform:uppercase;margin-bottom:6px">Read time</div>
                    <div style="font-size:14px;color:var(--dark);font-weight:600;margin-bottom:20px"><?php echo esc_html($read_time); ?> · <?php echo esc_html($level); ?></div>
                    <?php endif; ?>

                    <div style="border-top:1px solid var(--rule);padding-top:20px;margin-top:4px">
                        <div style="font-size:13px;font-weight:700;color:var(--dark);margin-bottom:8px">Have a question?</div>
                        <p style="font-size:13px;color:var(--soft);line-height:1.6;margin-bottom:14px">Our technical team has 55 years of activated carbon expertise.</p>
                        <a href="<?php echo esc_url( home_url('/contact/') ); ?>" class="btn-primary" style="display:block;text-align:center;text-decoration:none;font-size:13px;padding:11px 16px">Contact team →</a>
                    </div>

                    <div style="border-top:1px solid var(--rule);padding-top:16px;margin-top:16px">
                        <a href="<?php echo esc_url($kb_url); ?>" style="font-size:13px;color:var(--blue);font-weight:600;text-decoration:none">← Back to Knowledge Bank</a>
                    </div>
                </div>
            </aside>

        </div>
    </div>
</section>

<!-- ── CARBON AI STRIP ── -->
<section class="page-ai-strip">
    <div class="wrap">
        <div class="pai-inner">
            <div class="pai-avatar">
                <svg viewBox="0 0 24 24" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M9 9h.01M15 9h.01"/><path d="M9.5 15a3.5 3.5 0 005 0" stroke-linecap="round"/></svg>
            </div>
            <div class="pai-text">
                <div class="pai-eyebrow">Carbon Expert AI</div>
                <div class="pai-headline">Have a follow-up question?</div>
                <div class="pai-sub">Our AI is trained on 55 years of carbon expertise. Ask anything.</div>
            </div>
            <div class="pai-input-row">
                <input type="text" id="paiInput-article" class="pai-input" placeholder="e.g. Which grade for municipal water treatment?" onkeydown="if(event.key==='Enter')aiAskFromPage('paiInput-article')">
                <button class="pai-btn" onclick="aiAskFromPage('paiInput-article')">Ask AI →</button>
            </div>
        </div>
    </div>
</section>

</main>

<?php get_footer(); ?>
