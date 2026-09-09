<?php
/**
 * 404.php — Page Not Found
 */
get_header();
?>

<main id="primary" class="site-main">

<!-- ── Hero ── -->
<section style="background:var(--dark);padding:100px 0 80px;position:relative;overflow:hidden">
    <div style="position:absolute;width:400px;height:400px;border-radius:50%;background:rgba(2,150,216,.07);top:-120px;right:-60px;pointer-events:none"></div>
    <div class="wrap" style="position:relative;z-index:2">
        <div style="font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:var(--blue);margin-bottom:18px">404 — Page Not Found</div>
        <h1 style="font-size:clamp(32px,4vw,52px);font-weight:800;letter-spacing:-1.5px;color:#fff;margin:0 0 18px;line-height:1.1">This page doesn't exist.</h1>
        <p style="font-size:17px;color:rgba(255,255,255,.5);max-width:480px;line-height:1.7;margin:0 0 36px">The page you're looking for may have moved or been removed. Try one of the links below.</p>
        <div style="display:flex;gap:12px;flex-wrap:wrap">
            <a href="<?php echo esc_url(home_url('/')); ?>" style="padding:13px 26px;background:var(--blue);color:#fff;font-weight:700;font-size:14px;border-radius:100px;text-decoration:none">← Go Home</a>
            <a href="<?php echo esc_url(home_url('/contact/')); ?>" style="padding:13px 26px;background:rgba(255,255,255,.1);color:#fff;font-weight:700;font-size:14px;border-radius:100px;text-decoration:none;border:1px solid rgba(255,255,255,.12)">Contact Us</a>
        </div>
    </div>
</section>

<!-- ── Quick links ── -->
<section style="padding:60px 0;background:var(--bg)">
    <div class="wrap">
        <div style="font-family:var(--mono);font-size:10px;font-weight:700;letter-spacing:2.5px;text-transform:uppercase;color:#aaa;margin-bottom:28px">Popular pages</div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:14px;max-width:800px">
            <?php
            $links = [
                ['label'=>'Products',     'desc'=>'20+ activated carbon grades', 'url'=>'/products/'],
                ['label'=>'Applications', 'desc'=>'Industries we serve',          'url'=>'/applications/'],
                ['label'=>'Knowledge',    'desc'=>'Technical articles & guides',  'url'=>'/knowledge/'],
                ['label'=>'Contact',      'desc'=>'Get in touch with our team',   'url'=>'/contact/'],
            ];
            foreach ($links as $l) : ?>
            <a href="<?php echo esc_url(home_url($l['url'])); ?>"
               style="display:block;padding:18px 20px;background:#fff;border:1px solid var(--rule);border-radius:12px;text-decoration:none;color:inherit;transition:border-color .15s,box-shadow .15s"
               onmouseover="this.style.borderColor='var(--blue)';this.style.boxShadow='0 4px 16px rgba(2,150,216,.08)'"
               onmouseout="this.style.borderColor='var(--rule)';this.style.boxShadow=''">
                <div style="font-size:14px;font-weight:700;color:var(--dark);margin-bottom:4px"><?php echo $l['label']; ?></div>
                <div style="font-size:12px;color:#aaa"><?php echo $l['desc']; ?></div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

</main>

<?php get_footer(); ?>
