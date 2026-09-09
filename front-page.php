<?php
/**
 * front-page.php — UCI Carbons Theme 2
 * WordPress uses this automatically for the static front page.
 * Long-scroll homepage: hero → family → supply → facility → process → products → applications → knowledge → ESG → contact CTA
 */
get_header();
?>

<main id="primary" class="site-main">

    <?php get_template_part( 'template-parts/hero' ); ?>
    <?php get_template_part( 'template-parts/home-sections' ); ?>
    <?php get_template_part( 'template-parts/products' ); ?>
    <?php get_template_part( 'template-parts/carbon-ai' ); ?>
    <?php get_template_part( 'template-parts/certifications' ); ?>
    <?php //get_template_part( 'template-parts/applications' ); ?>
    <?php //get_template_part( 'template-parts/knowledge' ); ?>
    <?php //get_template_part( 'template-parts/esg' ); ?>
    <?php get_template_part( 'template-parts/export' ); ?>

</main>

<?php get_footer(); ?>
