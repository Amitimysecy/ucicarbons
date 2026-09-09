<?php
/**
 * single.php — Blog post / Knowledge article template
 */
get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>

<!-- ── Post Hero ── -->
<section style="background:var(--dark);padding:120px 0 60px;position:relative;overflow:hidden">
    <!-- subtle bg texture -->
    <div style="position:absolute;inset:0;background:radial-gradient(ellipse 80% 60% at 60% 50%,rgba(129,212,250,.08) 0%,transparent 70%);pointer-events:none"></div>
    <div class="wrap" style="position:relative;max-width:800px">

        <!-- Breadcrumb -->
        <nav style="margin-bottom:28px;font-size:13px;color:rgba(255,255,255,.45);display:flex;align-items:center;gap:0;flex-wrap:wrap">
            <a href="<?php echo esc_url(home_url('/')); ?>" style="color:rgba(255,255,255,.45);text-decoration:none">Home</a>
            <span style="margin:0 8px">/</span>
            <a href="<?php echo esc_url(home_url('/knowledge/')); ?>" style="color:rgba(255,255,255,.45);text-decoration:none">Knowledge</a>
            <span style="margin:0 8px">/</span>
            <span style="color:rgba(255,255,255,.75)"><?php the_title(); ?></span>
        </nav>

        <!-- Category + date chips -->
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;flex-wrap:wrap">
            <?php
            $cats = get_the_category();
            foreach ( $cats as $cat ) :
                if ( strtolower($cat->slug) === 'uncategorized' ) continue;
            ?>
            <span style="padding:4px 12px;background:rgba(255,255,255,.12);border-radius:100px;font-size:11px;font-weight:700;letter-spacing:.7px;text-transform:uppercase;color:rgba(255,255,255,.8)"><?php echo esc_html($cat->name); ?></span>
            <?php endforeach; ?>
            <span style="font-size:13px;color:rgba(255,255,255,.4)"><?php echo get_the_date('F j, Y'); ?></span>
        </div>

        <!-- Title -->
        <h1 style="font-size:clamp(28px,4.5vw,52px);font-weight:800;letter-spacing:-1.5px;line-height:1.08;color:#fff;margin:0 0 20px">
            <?php the_title(); ?>
        </h1>

        <!-- Excerpt / intro -->
        <?php if ( has_excerpt() ) : ?>
        <p style="font-size:17px;line-height:1.65;color:rgba(255,255,255,.6);margin:0;max-width:640px">
            <?php the_excerpt(); ?>
        </p>
        <?php endif; ?>
    </div>
</section>

<!-- ── Featured image (if set) ── -->
<?php if ( has_post_thumbnail() ) : ?>
<div style="background:var(--dark);padding-bottom:0">
    <div class="wrap" style="max-width:800px;padding-bottom:0">
        <div style="border-radius:16px 16px 0 0;overflow:hidden;max-height:420px">
            <?php the_post_thumbnail('large', ['style'=>'width:100%;height:100%;object-fit:cover;display:block']); ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ── Article body ── -->
<section style="background:#fff;padding:56px 0 80px">
    <div class="wrap" style="max-width:800px">

        <article class="kbody" style="font-size:16px;line-height:1.85;color:#333;max-width:720px">
            <?php the_content(); ?>
        </article>

        <!-- CTA strip -->
        <div style="margin-top:60px;padding:32px 36px;background:var(--dark);border-radius:16px;display:flex;align-items:center;justify-content:space-between;gap:24px;flex-wrap:wrap">
            <div>
                <div style="font-size:18px;font-weight:800;color:#fff;margin-bottom:4px">Need activated carbon for your application?</div>
                <div style="font-size:14px;color:rgba(255,255,255,.5)">UCI Carbons supplies 20+ grades globally. Get TDS or request a sample.</div>
            </div>
            <div style="display:flex;gap:12px;flex-shrink:0">
                <a href="<?php echo esc_url(home_url('/products/')); ?>" style="padding:12px 22px;background:var(--gold);color:#fff;font-weight:700;font-size:13px;border-radius:100px;text-decoration:none;white-space:nowrap">Browse Products</a>
                <a href="<?php echo esc_url(home_url('/contact/')); ?>" style="padding:12px 22px;background:rgba(255,255,255,.12);color:#fff;font-weight:700;font-size:13px;border-radius:100px;text-decoration:none;white-space:nowrap">Contact Us</a>
            </div>
        </div>

        <!-- Back link -->
        <div style="margin-top:36px">
            <a href="<?php echo esc_url(home_url('/knowledge/')); ?>" style="font-size:14px;font-weight:700;color:var(--dark);text-decoration:none;display:inline-flex;align-items:center;gap:6px">
                ← Back to Knowledge
            </a>
        </div>

    </div>
</section>

<?php endwhile; ?>

<style>
/* Article body typography */
.kbody h2 {
    font-size: 24px;
    font-weight: 800;
    letter-spacing: -.5px;
    color: var(--dark);
    margin: 48px 0 14px;
    padding-top: 8px;
    border-top: 1px solid rgba(0,0,0,.08);
}
.kbody h3 {
    font-size: 18px;
    font-weight: 700;
    color: var(--dark);
    margin: 32px 0 10px;
}
.kbody p { margin: 0 0 20px; }
.kbody ul, .kbody ol {
    padding-left: 22px;
    margin: 0 0 20px;
}
.kbody li { margin-bottom: 10px; }
.kbody strong { color: var(--dark); font-weight: 700; }
.kbody a { color: var(--dark); font-weight: 600; text-decoration: underline; }
.kbody a:hover { color: var(--gold); }
.kbody table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
    margin: 28px 0;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 0 0 1px rgba(0,0,0,.08);
}
.kbody th {
    background: var(--dark);
    color: #fff;
    padding: 12px 16px;
    text-align: left;
    font-weight: 700;
    font-size: 12px;
    letter-spacing: .4px;
    text-transform: uppercase;
}
.kbody td {
    padding: 12px 16px;
    border-bottom: 1px solid rgba(0,0,0,.07);
    vertical-align: top;
    line-height: 1.6;
}
.kbody tr:last-child td { border-bottom: 0; }
.kbody tr:nth-child(even) td { background: rgba(0,0,0,.02); }
.kbody img {
    width: 100%;
    height: auto;
    border-radius: 12px;
    display: block;
    margin: 8px 0;
}
.kbody figure { margin: 32px 0; }
.kbody figcaption {
    font-size: 13px;
    color: #999;
    text-align: center;
    margin-top: 8px;
}
.kbody blockquote {
    margin: 28px 0;
    padding: 20px 24px;
    background: rgba(245,158,11,.07);
    border-left: 4px solid var(--gold);
    border-radius: 0 12px 12px 0;
    font-style: italic;
    color: var(--dark);
}
</style>

<?php get_footer(); ?>
