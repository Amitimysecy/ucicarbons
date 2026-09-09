<?php
/**
 * home.php — Blog index / Knowledge Bank
 *
 * WordPress uses this template when Settings > Reading > "Posts page" is set.
 * page-knowledge.php is ignored in that scenario — this file takes over.
 */
get_header();

/* ── Helpers ──────────────────────────────────────────────────── */

/**
 * Best image for a post:
 * 1. WP featured image
 * 2. First <img> in post content  (handles WXR-imported posts)
 * 3. Try remapped /ucicarbons/ path (old subdirectory install)
 * 4. Empty — CSS gradient fallback shown instead
 */
function uci_kb_image( $post_id, $size = 'large' ) {
    if ( has_post_thumbnail( $post_id ) ) {
        return get_the_post_thumbnail_url( $post_id, $size );
    }
    $content = get_post_field( 'post_content', $post_id );
    if ( preg_match( '/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $content, $m ) ) {
        $url = $m[1];
        // If URL points to root /wp-content/ but file may still be in old subdirectory
        // Try to surface the URL anyway — browser will handle 404 gracefully with CSS fallback
        return $url;
    }
    return '';
}

function uci_kb_readtime( $post_id ) {
    $words = str_word_count( wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ) );
    return max( 1, round( $words / 200 ) ) . ' min read';
}

/* ── Filter & pagination setup ────────────────────────────────── */
$current_cat = isset( $_GET['cat'] ) ? sanitize_title( $_GET['cat'] ) : '';

// Base URL = the Posts page URL (Knowledge page)
$posts_page_id = get_option( 'page_for_posts' );
$base_url      = $posts_page_id ? get_permalink( $posts_page_id ) : home_url( '/knowledge/' );

// Use global WP query (no cat filter) OR custom query (with cat filter)
global $wp_query;

if ( $current_cat ) {
    $paged    = max( 1, isset( $_GET['pg'] ) ? absint( $_GET['pg'] ) : 1 );
    $kb_query = new WP_Query([
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => 12,
        'paged'          => $paged,
        'category_name'  => $current_cat,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);
} else {
    $kb_query = $wp_query; // WordPress already ran this, pagination included
    $paged    = max( 1, get_query_var( 'paged', 1 ) );
}

/* ── All categories for filter tabs ──────────────────────────── */
$all_cats = get_categories([
    'hide_empty' => true,
    'exclude'    => get_option( 'default_category' ),
    'orderby'    => 'count',
    'order'      => 'DESC',
]);

/* ── Category palette ─────────────────────────────────────────── */
$cat_palette = [
    'knowledge'          => [ 'badge' => '#0296D8', 'grad' => 'linear-gradient(135deg,#0F3549 0%,#0a2030 60%,#051520 100%)' ],
    'activated-carbons'  => [ 'badge' => '#F59E0B', 'grad' => 'linear-gradient(135deg,#1a1200 0%,#2a1800 60%,#0f0a00 100%)' ],
    'activated-charcoal' => [ 'badge' => '#22c55e', 'grad' => 'linear-gradient(135deg,#0a2010 0%,#0f2a18 60%,#051008 100%)' ],
    '_default'           => [ 'badge' => '#0296D8', 'grad' => 'linear-gradient(135deg,#0F3549 0%,#07202e 60%,#030e16 100%)' ],
];

function uci_palette( $slug, $key ) {
    global $cat_palette;
    return $cat_palette[ $slug ][ $key ] ?? $cat_palette['_default'][ $key ];
}

/* ── Separate featured post from grid ────────────────────────── */
$all_posts   = $kb_query->posts;
$featured    = ( $paged === 1 && ! empty( $all_posts ) ) ? $all_posts[0] : null;
$grid_posts  = $featured ? array_slice( $all_posts, 1 ) : $all_posts;
?>

<main id="primary" class="site-main">

<!-- ══ HERO ══════════════════════════════════════════════════════ -->
<section class="kb-hero">
    <div class="kb-orb kb-orb-1"></div>
    <div class="kb-orb kb-orb-2"></div>
    <div class="wrap" style="position:relative;z-index:2">
        <div class="kb-eyebrow">Knowledge Bank</div>
        <h1 class="kb-h1">Activated carbon,<br><span>explained.</span></h1>
        <p class="kb-sub">Technical guides, application explainers, and industry insights — written by people who have been making activated carbon since 1969.</p>
        <div class="kb-stats">
            <div class="kb-stat"><span class="kb-stat-n"><?php echo (int)$kb_query->found_posts; ?>+</span><span class="kb-stat-l">Articles</span></div>
            <div class="kb-stat-rule"></div>
            <div class="kb-stat"><span class="kb-stat-n">55</span><span class="kb-stat-l">Years expertise</span></div>
            <div class="kb-stat-rule"></div>
            <div class="kb-stat"><span class="kb-stat-n">Free</span><span class="kb-stat-l">Always</span></div>
        </div>
    </div>
</section>

<!-- ══ STICKY FILTER BAR ═════════════════════════════════════════ -->
<div class="kb-filter-bar">
    <div class="wrap">
        <div class="kb-tabs">
            <a class="kb-tab <?php echo ! $current_cat ? 'is-active' : ''; ?>" href="<?php echo esc_url( $base_url ); ?>">All Articles</a>
            <?php foreach ( $all_cats as $cat ) : ?>
            <a class="kb-tab <?php echo $current_cat === $cat->slug ? 'is-active' : ''; ?>"
               href="<?php echo esc_url( add_query_arg( 'cat', $cat->slug, $base_url ) ); ?>">
               <?php echo esc_html( $cat->name ); ?> <em><?php echo number_format_i18n( $cat->count ); ?></em>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- ══ POSTS ═════════════════════════════════════════════════════ -->
<section class="kb-body">
    <div class="wrap">

    <?php if ( $kb_query->have_posts() ) : ?>

    <?php /* ─ FEATURED (first post on page 1) ─ */
    if ( $featured ) :
        $fid   = $featured->ID;
        $fimg  = uci_kb_image( $fid, 'large' );
        $fcats = get_the_category( $fid );
        $fcat  = null;
        foreach ( $fcats as $c ) { if ( strtolower($c->slug) !== 'uncategorized' ) { $fcat = $c; break; } }
        $fslug  = $fcat ? $fcat->slug : '_default';
        $fbadge = uci_palette( $fslug, 'badge' );
        $fgrad  = uci_palette( $fslug, 'grad' );
    ?>
    <article class="kb-feat">
        <a href="<?php echo esc_url( get_permalink( $featured ) ); ?>" class="kb-feat-link">
            <!-- Image -->
            <div class="kb-feat-img"
                 <?php if ( $fimg ) : ?>style="background-image:url('<?php echo esc_url($fimg); ?>')"<?php else: ?>style="background:<?php echo $fgrad; ?>"<?php endif; ?>>
                <?php if ( ! $fimg ) : ?><div class="kb-grad-orb"></div><?php endif; ?>
                <div class="kb-feat-overlay"></div>
            </div>
            <!-- Text -->
            <div class="kb-feat-body">
                <div class="kb-feat-chips">
                    <?php if ( $fcat ) : ?>
                    <span class="kb-chip" style="color:<?php echo $fbadge;?>;background:<?php echo $fbadge;?>18;border:1px solid <?php echo $fbadge;?>40"><?php echo esc_html($fcat->name); ?></span>
                    <?php endif; ?>
                    <span class="kb-feat-flag">Latest</span>
                </div>
                <h2 class="kb-feat-title"><?php echo get_the_title( $featured ); ?></h2>
                <p class="kb-feat-exc"><?php echo wp_trim_words( get_the_excerpt( $featured ), 30, '…' ); ?></p>
                <div class="kb-feat-foot">
                    <span class="kb-date"><?php echo get_the_date( 'F j, Y', $featured ); ?></span>
                    <span class="kb-dot">·</span>
                    <span class="kb-rt"><?php echo uci_kb_readtime( $fid ); ?></span>
                    <span class="kb-cta">Read article <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
                </div>
            </div>
        </a>
    </article>
    <?php endif; // featured ?>

    <?php /* ─ GRID ─ */ ?>
    <?php if ( ! empty( $grid_posts ) ) : ?>
    <div class="kb-grid">
    <?php foreach ( $grid_posts as $gpost ) :
        $gid   = $gpost->ID;
        $gimg  = uci_kb_image( $gid, 'medium_large' );
        $gcats = get_the_category( $gid );
        $gcat  = null;
        foreach ( $gcats as $c ) { if ( strtolower($c->slug) !== 'uncategorized' ) { $gcat = $c; break; } }
        $gslug  = $gcat ? $gcat->slug : '_default';
        $gbadge = uci_palette( $gslug, 'badge' );
        $ggrad  = uci_palette( $gslug, 'grad' );
    ?>
    <article class="kb-card">
        <a href="<?php echo esc_url( get_permalink( $gpost ) ); ?>" class="kb-card-a">
            <div class="kb-card-img"
                 <?php if ( $gimg ) : ?>style="background-image:url('<?php echo esc_url($gimg); ?>')"<?php else: ?>style="background:<?php echo $ggrad; ?>"<?php endif; ?>>
                <?php if ( ! $gimg ) : ?><div class="kb-grad-orb" style="opacity:.7"></div><?php endif; ?>
                <?php if ( $gcat ) : ?>
                <span class="kb-card-badge" style="background:<?php echo $gbadge; ?>"><?php echo esc_html($gcat->name); ?></span>
                <?php endif; ?>
            </div>
            <div class="kb-card-body">
                <div class="kb-card-meta">
                    <time><?php echo get_the_date( 'M j, Y', $gpost ); ?></time>
                    <span>·</span>
                    <span><?php echo uci_kb_readtime( $gid ); ?></span>
                </div>
                <h3 class="kb-card-h"><?php echo get_the_title( $gpost ); ?></h3>
                <p class="kb-card-exc"><?php echo wp_trim_words( get_the_excerpt( $gpost ), 18, '…' ); ?></p>
                <span class="kb-card-read">Read article →</span>
            </div>
        </a>
    </article>
    <?php endforeach; ?>
    </div><!-- .kb-grid -->
    <?php endif; ?>

    <?php /* ─ PAGINATION ─ */ ?>
    <?php if ( $kb_query->max_num_pages > 1 ) :
        $total = $kb_query->max_num_pages;
    ?>
    <nav class="kb-pager">
        <?php if ( $paged > 1 ) :
            if ( $current_cat ) {
                $prev_url = add_query_arg( array_filter(['cat' => $current_cat, 'pg' => $paged - 1 > 1 ? $paged - 1 : null]), $base_url );
            } else {
                $prev_url = get_pagenum_link( $paged - 1 );
            }
        ?>
        <a class="kb-pager-btn" href="<?php echo esc_url( $prev_url ); ?>">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M15 18l-6-6 6-6"/></svg> Prev
        </a>
        <?php endif; ?>
        <div class="kb-pager-nums">
        <?php for ( $p = 1; $p <= $total; $p++ ) :
            if ( $current_cat ) {
                $purl = add_query_arg( array_filter(['cat' => $current_cat, 'pg' => $p > 1 ? $p : null]), $base_url );
            } else {
                $purl = get_pagenum_link( $p );
            }
        ?>
        <a class="kb-pager-n <?php echo $p === $paged ? 'current' : ''; ?>" href="<?php echo esc_url($purl); ?>"><?php echo $p; ?></a>
        <?php endfor; ?>
        </div>
        <?php if ( $paged < $total ) :
            if ( $current_cat ) {
                $next_url = add_query_arg( array_filter(['cat' => $current_cat, 'pg' => $paged + 1]), $base_url );
            } else {
                $next_url = get_pagenum_link( $paged + 1 );
            }
        ?>
        <a class="kb-pager-btn" href="<?php echo esc_url( $next_url ); ?>">
            Next <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M9 18l6-6-6-6"/></svg>
        </a>
        <?php endif; ?>
    </nav>
    <?php endif; ?>

    <?php else : ?>
    <div class="kb-empty">
        <div class="kb-empty-ico"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg></div>
        <h3>No articles found</h3>
        <p>Try a different category or <a href="<?php echo esc_url( $base_url ); ?>">view all</a>.</p>
    </div>
    <?php endif; ?>

    </div>
</section>

<!-- ══ AI STRIP ══════════════════════════════════════════════════ -->
<section class="page-ai-strip">
    <div class="wrap">
        <div class="pai-inner">
            <div class="pai-avatar">
                <svg viewBox="0 0 24 24" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M9 9h.01M15 9h.01"/><path d="M9.5 15a3.5 3.5 0 005 0" stroke-linecap="round"/></svg>
            </div>
            <div class="pai-text">
                <div class="pai-eyebrow">Carbon Expert AI</div>
                <div class="pai-headline">Have a technical question on activated carbon?</div>
                <div class="pai-sub">Our AI is trained on 55 years of carbon expertise. Ask anything.</div>
            </div>
            <div class="pai-input-row">
                <input type="text" id="paiInput-kb" class="pai-input" placeholder="e.g. What is the difference between iodine and CTC?" onkeydown="if(event.key==='Enter')aiAskFromPage('paiInput-kb')">
                <button class="pai-btn" onclick="aiAskFromPage('paiInput-kb')">Ask AI →</button>
            </div>
        </div>
    </div>
</section>

</main>

<style>
/* ── Hero ── */
.kb-hero{background:var(--dark);padding:72px 0 52px;position:relative;overflow:hidden}
.kb-orb{position:absolute;border-radius:50%;pointer-events:none}
.kb-orb-1{width:560px;height:560px;background:radial-gradient(circle,rgba(2,150,216,.13) 0%,transparent 70%);top:-200px;right:-80px}
.kb-orb-2{width:300px;height:300px;background:radial-gradient(circle,rgba(245,158,11,.08) 0%,transparent 70%);bottom:-100px;left:5%}
.kb-eyebrow{font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:var(--blue);margin-bottom:18px}
.kb-h1{font-size:clamp(40px,5vw,68px);font-weight:800;letter-spacing:-2.5px;line-height:1.04;color:#fff;margin:0 0 18px}
.kb-h1 span{color:var(--blue)}
.kb-sub{font-size:17px;line-height:1.7;color:rgba(255,255,255,.5);max-width:520px;margin:0 0 36px}
.kb-stats{display:flex;align-items:center;gap:24px;flex-wrap:wrap}
.kb-stat{display:flex;flex-direction:column;gap:2px}
.kb-stat-n{font-size:22px;font-weight:800;color:#fff;letter-spacing:-1px;line-height:1}
.kb-stat-l{font-family:var(--mono);font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:rgba(255,255,255,.35)}
.kb-stat-rule{width:1px;height:32px;background:rgba(255,255,255,.12)}

/* ── Filter bar ── */
.kb-filter-bar{position:sticky;top:68px;z-index:90;background:rgba(248,253,255,.97);backdrop-filter:blur(10px);border-bottom:1px solid var(--rule)}
.kb-tabs{display:flex;gap:2px;overflow-x:auto;padding:10px 0;scrollbar-width:none}
.kb-tabs::-webkit-scrollbar{display:none}
.kb-tab{display:inline-flex;align-items:center;gap:7px;padding:7px 16px;border-radius:8px;font-size:13px;font-weight:600;color:#666;text-decoration:none;white-space:nowrap;transition:background .15s,color .15s;flex-shrink:0}
.kb-tab:hover{background:rgba(2,150,216,.07);color:var(--blue)}
.kb-tab.is-active{background:var(--dark);color:#fff}
.kb-tab em{font-style:normal;font-size:11px;opacity:.55;background:rgba(0,0,0,.07);padding:1px 6px;border-radius:100px;font-weight:700}
.kb-tab.is-active em{background:rgba(255,255,255,.18);opacity:1}

/* ── Body section ── */
.kb-body{padding:52px 0 80px;background:var(--bg)}

/* ── Featured post ── */
.kb-feat{margin-bottom:44px}
.kb-feat-link{display:grid;grid-template-columns:1fr 1fr;border-radius:20px;overflow:hidden;background:#fff;box-shadow:0 2px 8px rgba(0,0,0,.06),0 16px 48px rgba(0,0,0,.08);text-decoration:none;color:inherit;min-height:360px;transition:box-shadow .25s,transform .25s cubic-bezier(.22,1,.36,1)}
.kb-feat-link:hover{box-shadow:0 8px 24px rgba(0,0,0,.1),0 32px 64px rgba(0,0,0,.12);transform:translateY(-2px)}
.kb-feat-img{position:relative;background-size:cover;background-position:center;overflow:hidden}
.kb-feat-overlay{position:absolute;inset:0;background:linear-gradient(to right,transparent 60%,rgba(15,53,73,.12))}
.kb-grad-orb{position:absolute;inset:0;background-image:radial-gradient(circle at 25% 45%,rgba(2,150,216,.2) 0%,transparent 55%),radial-gradient(circle at 75% 70%,rgba(245,158,11,.1) 0%,transparent 50%)}
.kb-feat-body{display:flex;flex-direction:column;justify-content:center;padding:44px 48px}
.kb-feat-chips{display:flex;align-items:center;gap:8px;margin-bottom:18px;flex-wrap:wrap}
.kb-chip{display:inline-block;padding:4px 12px;border-radius:100px;font-family:var(--mono);font-size:10px;font-weight:700;letter-spacing:.8px;text-transform:uppercase}
.kb-feat-flag{font-family:var(--mono);font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:rgba(15,53,73,.3);border:1px solid rgba(15,53,73,.15);padding:3px 10px;border-radius:100px}
.kb-feat-title{font-size:clamp(20px,2.2vw,30px);font-weight:800;letter-spacing:-.8px;line-height:1.2;color:var(--dark);margin:0 0 14px}
.kb-feat-exc{font-size:15px;line-height:1.7;color:#777;margin:0 0 26px}
.kb-feat-foot{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.kb-date,.kb-rt{font-size:13px;color:#aaa;font-weight:500}
.kb-dot{color:#ddd;font-size:13px}
.kb-cta{margin-left:auto;display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:700;color:var(--blue);transition:gap .2s}
.kb-feat-link:hover .kb-cta{gap:10px}

/* ── Grid ── */
.kb-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px}
.kb-card{border-radius:16px;overflow:hidden;background:#fff;box-shadow:0 1px 4px rgba(0,0,0,.06),0 6px 20px rgba(0,0,0,.05);transition:transform .22s cubic-bezier(.22,1,.36,1),box-shadow .22s}
.kb-card:hover{transform:translateY(-5px);box-shadow:0 4px 16px rgba(0,0,0,.09),0 24px 48px rgba(0,0,0,.1)}
.kb-card-a{display:flex;flex-direction:column;height:100%;text-decoration:none;color:inherit}
.kb-card-img{height:210px;background-size:cover;background-position:center;position:relative;overflow:hidden;flex-shrink:0;transition:transform .4s cubic-bezier(.22,1,.36,1)}
.kb-card:hover .kb-card-img{transform:scale(1.04)}
.kb-card-badge{position:absolute;top:14px;left:14px;padding:4px 11px;border-radius:100px;font-family:var(--mono);font-size:10px;font-weight:700;letter-spacing:.7px;text-transform:uppercase;color:#fff;backdrop-filter:blur(6px)}
.kb-card-body{display:flex;flex-direction:column;flex:1;padding:20px 22px 22px}
.kb-card-meta{display:flex;align-items:center;gap:6px;font-size:11.5px;color:#bbb;font-weight:500;margin-bottom:10px;font-family:var(--mono);letter-spacing:.3px}
.kb-card-h{font-size:16px;font-weight:800;line-height:1.35;color:var(--dark);margin:0 0 10px;letter-spacing:-.3px}
.kb-card-exc{font-size:13.5px;line-height:1.65;color:#888;flex:1;margin:0 0 16px}
.kb-card-read{font-size:13px;font-weight:700;color:var(--blue);margin-top:auto;transition:color .15s}
.kb-card:hover .kb-card-read{color:var(--dark)}

/* ── Pagination ── */
.kb-pager{display:flex;align-items:center;justify-content:center;gap:10px;margin-top:56px;flex-wrap:wrap}
.kb-pager-btn{display:inline-flex;align-items:center;gap:6px;padding:10px 20px;border-radius:100px;border:1.5px solid var(--rule);background:#fff;color:var(--dark);font-size:13px;font-weight:700;text-decoration:none;transition:border-color .15s,color .15s}
.kb-pager-btn:hover{border-color:var(--blue);color:var(--blue)}
.kb-pager-nums{display:flex;gap:4px}
.kb-pager-n{display:inline-flex;align-items:center;justify-content:center;width:40px;height:40px;border-radius:100px;border:1.5px solid var(--rule);background:#fff;color:var(--dark);font-size:14px;font-weight:600;text-decoration:none;transition:background .15s,border-color .15s,color .15s}
.kb-pager-n:hover{border-color:var(--blue);color:var(--blue)}
.kb-pager-n.current{background:var(--dark);border-color:var(--dark);color:#fff}

/* ── Empty ── */
.kb-empty{text-align:center;padding:80px 20px}
.kb-empty-ico{width:64px;height:64px;border-radius:50%;background:var(--rule);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;color:#bbb}
.kb-empty h3{font-size:20px;font-weight:800;color:var(--dark);margin:0 0 8px}
.kb-empty p{font-size:15px;color:#888}
.kb-empty a{color:var(--blue)}

/* ── Responsive ── */
@media(max-width:900px){
    .kb-feat-link{grid-template-columns:1fr}
    .kb-feat-img{min-height:240px}
    .kb-feat-body{padding:28px 28px 32px}
    .kb-grid{grid-template-columns:repeat(2,1fr)}
}
@media(max-width:600px){
    .kb-grid{grid-template-columns:1fr}
    .kb-feat-body{padding:24px 20px 28px}
    .kb-filter-bar{top:56px}
}
</style>

<?php get_footer(); ?>
