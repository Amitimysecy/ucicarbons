<?php
/**
 * Default page template.
 * Used for plain CMS pages. The template_include filter in
 * functions.php ensures slug-specific templates are preferred,
 * so this file only renders if no page-{slug}.php exists.
 */
get_header();
?>

<main id="primary" class="site-main" style="min-height:60vh;padding:100px 0 60px">
    <div class="wrap">
        <?php
        while ( have_posts() ) :
            the_post();
            ?>
            <h1 style="font-size:clamp(28px,4vw,52px);font-weight:800;letter-spacing:-1.5px;color:var(--dark);margin-bottom:24px">
                <?php the_title(); ?>
            </h1>
            <div class="entry-content" style="font-size:16px;line-height:1.8;color:var(--soft);max-width:720px">
                <?php the_content(); ?>
            </div>
            <?php
        endwhile;
        ?>
    </div>
</main>

<?php get_footer(); ?>
