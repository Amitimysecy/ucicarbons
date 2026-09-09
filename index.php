<?php
/**
 * index.php — last-resort fallback in WordPress template hierarchy.
 *
 * Before rendering a generic layout, we check if a slug-specific
 * page-{slug}.php template exists and load it directly. This ensures
 * thank-you pages and other slug-matched templates always work even
 * if the WP admin hasn't explicitly assigned the page template.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( is_page() ) {
    $slug     = get_post_field( 'post_name', get_queried_object_id() );
    $slug_tpl = get_template_directory() . '/page-' . $slug . '.php';
    if ( file_exists( $slug_tpl ) ) {
        include $slug_tpl;
        exit;
    }
    // Slug template not found → use page.php if available
    $page_tpl = get_template_directory() . '/page.php';
    if ( file_exists( $page_tpl ) ) {
        include $page_tpl;
        exit;
    }
}

// Generic fallback for non-page requests (archives, search, etc.)
get_header();
?>

<main id="primary" class="site-main" style="min-height:60vh;padding:100px 0 60px">
    <div class="wrap">
        <?php
        if ( have_posts() ) :
            while ( have_posts() ) :
                the_post();
                echo '<h1 style="font-size:clamp(28px,4vw,52px);font-weight:800;letter-spacing:-1.5px;color:var(--dark);margin-bottom:24px">';
                the_title();
                echo '</h1>';
                echo '<div style="font-size:16px;line-height:1.8;color:var(--soft);max-width:720px">';
                the_content();
                echo '</div>';
            endwhile;
        endif;
        ?>
    </div>
</main>

<?php get_footer(); ?>
