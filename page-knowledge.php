<?php
/**
 * Template Name: Knowledge
 * Knowledge bank — editorial blog listing with dynamic WP posts.
 */

/* ── Move styles into <head> ────────────────────────────────────────────────── */
add_action( 'wp_head', 'uci_knowledge_styles', 20 );
function uci_knowledge_styles() {
    if ( ! is_page_template('page-knowledge.php') ) return;
    echo '<style id="uci-knowledge-css">';
    include __DIR__ . '/assets/css/knowledge.css';
    echo '</style>';
}

get_header();

/* ── Helpers ──────────────────────────────────────────────────── */
/**
 * Get best image URL for a post.
 * Accepts the already-loaded WP_Post object to avoid extra DB queries.
 * 1. WP featured image  2. First <img> in post content  3. ''
 */
function uci_get_post_image( $post_id, $size = 'large', $post_obj = null ) {
    if ( has_post_thumbnail( $post_id ) ) {
        return get_the_post_thumbnail_url( $post_id, $size );
    }
    // Use already-loaded post object — no extra DB query
    $content = $post_obj ? $post_obj->post_content : get_post_field( 'post_content', $post_id );
    if ( preg_match( '/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $content, $m ) ) {
        return $m[1];
    }
    return '';
}

/**
 * Estimated read time. Pass content string directly — no DB query.
 */
function uci_read_time( $post_id, $content = null ) {
    if ( $content === null ) {
        $content = get_post_field( 'post_content', $post_id );
    }
    $words = str_word_count( wp_strip_all_tags( $content ) );
    return max( 1, round( $words / 200 ) ) . ' min read';
}

/* ── Query & filter setup ─────────────────────────────────────── */
$current_cat = isset( $_GET['cat'] ) ? sanitize_title( $_GET['cat'] ) : '';
$paged       = max( 1, isset( $_GET['pg'] ) ? absint( $_GET['pg'] ) : 1 );

$all_cats = get_categories([
    'hide_empty' => true,
    'exclude'    => get_option('default_category'),
    'orderby'    => 'count',
    'order'      => 'DESC',
]);

$query_args = [
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 12,
    'paged'          => $paged,
    'orderby'        => 'date',
    'order'          => 'DESC',
];
if ( $current_cat ) {
    $query_args['category_name'] = $current_cat;
}
$kb_query = new WP_Query( $query_args );
$base_url  = get_permalink();

/* ── Category palette ─────────────────────────────────────────── */
$cat_palette = [
    'knowledge'          => [ 'badge' => '#0296D8', 'grad' => 'linear-gradient(135deg,#0F3549 0%,#0a2030 60%,#051520 100%)' ],
    'activated-carbons'  => [ 'badge' => '#F59E0B', 'grad' => 'linear-gradient(135deg,#1a1200 0%,#2a1800 60%,#0f0a00 100%)' ],
    'activated-charcoal' => [ 'badge' => '#22c55e', 'grad' => 'linear-gradient(135deg,#0a2010 0%,#0f2a18 60%,#051008 100%)' ],
    '_default'           => [ 'badge' => '#0296D8', 'grad' => 'linear-gradient(135deg,#0F3549 0%,#07202e 60%,#030e16 100%)' ],
];

function uci_cat_palette( $slug, $key ) {
    global $cat_palette;
    return $cat_palette[ $slug ][ $key ] ?? $cat_palette['_default'][ $key ];
}

/* ── Separate featured (first) post from grid posts ──────────── */
$all_posts = $kb_query->posts;
$featured  = ! empty( $all_posts ) ? array_shift( $all_posts ) : null;
$grid_posts = $all_posts; // remaining posts
?>

<main id="primary" class="site-main">

<!-- ══════════════════════════════════════════════════════════
     HERO
══════════════════════════════════════════════════════════ -->
<section class="kb-hero">
    <div class="kb-hero-orb kb-hero-orb-1"></div>
    <div class="kb-hero-orb kb-hero-orb-2"></div>
    <div class="wrap" style="position:relative;z-index:2">
        <div class="kb-hero-eyebrow">Knowledge Bank</div>
        <h1 class="kb-hero-title">Activated carbon,<br><span>explained.</span></h1>
        <p class="kb-hero-sub">Technical guides, application explainers, and industry insights — written by people who have been making activated carbon since 1969.</p>

        <!-- Stats row -->
        <div class="kb-hero-stats">
            <div class="kb-stat">
                <span class="kb-stat-n"><?php echo $kb_query->found_posts; ?>+</span>
                <span class="kb-stat-l">Articles</span>
            </div>
            <div class="kb-stat-div"></div>
            <div class="kb-stat">
                <span class="kb-stat-n">55</span>
                <span class="kb-stat-l">Years expertise</span>
            </div>
            <div class="kb-stat-div"></div>
            <div class="kb-stat">
                <span class="kb-stat-n">Free</span>
                <span class="kb-stat-l">Always</span>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════════════════════
     FILTER TABS
══════════════════════════════════════════════════════════ -->
<div class="kb-filter-bar">
    <div class="wrap">
        <div class="kb-filters">
            <a class="kb-tab <?php echo ! $current_cat ? 'active' : ''; ?>" href="<?php echo esc_url( $base_url ); ?>">
                <span>All Articles</span>
            </a>
            <?php foreach ( $all_cats as $cat ) : ?>
            <a class="kb-tab <?php echo $current_cat === $cat->slug ? 'active' : ''; ?>"
               href="<?php echo esc_url( add_query_arg( 'cat', $cat->slug, $base_url ) ); ?>">
                <span><?php echo esc_html( $cat->name ); ?></span>
                <em><?php echo number_format_i18n( $cat->count ); ?></em>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     CONTENT AREA
══════════════════════════════════════════════════════════ -->
<section class="kb-section">
    <div class="wrap">

    <?php if ( $kb_query->have_posts() ) : ?>

    <?php /* ── FEATURED POST ────────────────────────────────── */ ?>
    <?php if ( $featured && $paged === 1 ) :
        setup_postdata( $featured );
        $f_id      = $featured->ID;
        $f_img     = uci_get_post_image( $f_id, 'large', $featured );
        $f_cats    = get_the_category( $f_id );
        $f_cat     = null;
        foreach ( $f_cats as $fc ) {
            if ( strtolower($fc->slug) !== 'uncategorized' ) { $f_cat = $fc; break; }
        }
        $f_slug  = $f_cat ? $f_cat->slug : '_default';
        $f_badge = uci_cat_palette( $f_slug, 'badge' );
        $f_grad  = uci_cat_palette( $f_slug, 'grad' );
    ?>
    <article class="kb-featured">
        <a href="<?php echo esc_url( get_permalink( $featured ) ); ?>" class="kb-featured-link">
            <!-- Image pane -->
            <div class="kb-featured-img" style="<?php echo $f_img ? 'background-image:url(\'' . esc_url($f_img) . '\')' : $f_grad; ?>;<?php if(!$f_img) echo 'background:' . $f_grad; ?>">
                <?php if ( ! $f_img ) : ?>
                <div class="kb-no-img-pattern"></div>
                <?php endif; ?>
                <div class="kb-featured-img-overlay"></div>
            </div>
            <!-- Text pane -->
            <div class="kb-featured-body">
                <div class="kb-featured-top">
                    <?php if ( $f_cat ) : ?>
                    <span class="kb-badge" style="background:<?php echo $f_badge; ?>20;color:<?php echo $f_badge; ?>;border:1px solid <?php echo $f_badge; ?>40"><?php echo esc_html( $f_cat->name ); ?></span>
                    <?php endif; ?>
                    <span class="kb-featured-label">Featured</span>
                </div>
                <h2 class="kb-featured-title"><?php echo get_the_title( $featured ); ?></h2>
                <p class="kb-featured-excerpt"><?php echo wp_trim_words( get_the_excerpt( $featured ), 28, '…' ); ?></p>
                <div class="kb-featured-foot">
                    <span class="kb-meta-date"><?php echo get_the_date( 'F j, Y', $featured ); ?></span>
                    <span class="kb-meta-sep">·</span>
                    <span class="kb-meta-read"><?php echo uci_read_time( $f_id, $featured->post_content ); ?></span>
                    <span class="kb-featured-cta">Read article <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
                </div>
            </div>
        </a>
    </article>
    <?php wp_reset_postdata(); ?>
    <?php endif; // featured post ?>

    <?php /* ── GRID POSTS ───────────────────────────────────── */ ?>
    <?php if ( ! empty( $grid_posts ) || ( ! $featured && $kb_query->have_posts() ) ) : ?>
    <div class="kb-grid">
    <?php
    // If no featured post (page 2+), show all posts in grid
    $display_posts = $featured ? $grid_posts : $all_posts;
    foreach ( $display_posts as $post ) :
        setup_postdata( $post );
        $p_id    = $post->ID;
        $p_img   = uci_get_post_image( $p_id, 'medium_large', $post );
        $p_cats  = get_the_category( $p_id );
        $p_cat   = null;
        foreach ( $p_cats as $pc ) {
            if ( strtolower($pc->slug) !== 'uncategorized' ) { $p_cat = $pc; break; }
        }
        $p_slug  = $p_cat ? $p_cat->slug : '_default';
        $p_badge = uci_cat_palette( $p_slug, 'badge' );
        $p_grad  = uci_cat_palette( $p_slug, 'grad' );
    ?>
    <article class="kb-card">
        <a href="<?php echo esc_url( get_permalink( $post ) ); ?>" class="kb-card-link">
            <!-- Card image — <img loading="lazy"> for native lazy load -->
            <div class="kb-card-img" style="<?php if ( ! $p_img ) echo 'background:' . $p_grad; ?>">
                <?php if ( $p_img ) : ?>
                <img src="<?php echo esc_url( $p_img ); ?>"
                     alt="<?php echo esc_attr( get_the_title( $post ) ); ?>"
                     loading="lazy"
                     decoding="async"
                     class="kb-card-img-el">
                <?php else : ?>
                <div class="kb-no-img-pattern"></div>
                <div class="kb-no-img-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.25)" stroke-width="1.2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                </div>
                <?php endif; ?>
                <?php if ( $p_cat ) : ?>
                <span class="kb-card-badge" style="background:<?php echo $p_badge; ?>"><?php echo esc_html( $p_cat->name ); ?></span>
                <?php endif; ?>
            </div>
            <!-- Card body -->
            <div class="kb-card-body">
                <div class="kb-card-meta">
                    <time><?php echo get_the_date( 'M j, Y', $post ); ?></time>
                    <span>·</span>
                    <span><?php echo uci_read_time( $p_id, $post->post_content ); ?></span>
                </div>
                <h3 class="kb-card-title"><?php echo get_the_title( $post ); ?></h3>
                <p class="kb-card-excerpt"><?php echo wp_trim_words( get_the_excerpt( $post ), 18, '…' ); ?></p>
                <span class="kb-card-read">Read article →</span>
            </div>
        </a>
    </article>
    <?php endforeach; wp_reset_postdata(); ?>
    </div><!-- .kb-grid -->
    <?php endif; // grid posts ?>

    <?php /* ── PAGINATION ───────────────────────────────────── */ ?>
    <?php if ( $kb_query->max_num_pages > 1 ) :
        $total = $kb_query->max_num_pages;
    ?>
    <nav class="kb-pager">
        <?php if ( $paged > 1 ) :
            $p_args = array_filter([ 'cat' => $current_cat ?: null, 'pg' => $paged - 1 > 1 ? $paged - 1 : null ]);
        ?>
        <a class="kb-pager-btn" href="<?php echo esc_url( add_query_arg( $p_args, $base_url ) ); ?>">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M15 18l-6-6 6-6"/></svg> Prev
        </a>
        <?php endif; ?>
        <div class="kb-pager-nums">
        <?php for ( $p = 1; $p <= $total; $p++ ) :
            $p_args = array_filter([ 'cat' => $current_cat ?: null, 'pg' => $p > 1 ? $p : null ]);
        ?>
        <a class="kb-pager-num <?php echo $p === $paged ? 'current' : ''; ?>"
           href="<?php echo esc_url( add_query_arg( $p_args, $base_url ) ); ?>"><?php echo $p; ?></a>
        <?php endfor; ?>
        </div>
        <?php if ( $paged < $total ) :
            $n_args = array_filter([ 'cat' => $current_cat ?: null, 'pg' => $paged + 1 ]);
        ?>
        <a class="kb-pager-btn" href="<?php echo esc_url( add_query_arg( $n_args, $base_url ) ); ?>">
            Next <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M9 18l6-6-6-6"/></svg>
        </a>
        <?php endif; ?>
    </nav>
    <?php endif; // pagination ?>

    <?php else : // no posts ?>
    <div class="kb-empty">
        <div class="kb-empty-icon">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
        </div>
        <h3>No articles found</h3>
        <p>Try a different category or <a href="<?php echo esc_url( $base_url ); ?>">view all articles</a>.</p>
    </div>
    <?php endif; ?>

    </div><!-- .wrap -->
</section>

<!-- ══════════════════════════════════════════════════════════
     AI STRIP
══════════════════════════════════════════════════════════ -->
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

<?php get_footer(); ?>
