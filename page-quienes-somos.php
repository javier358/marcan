<?php
/**
 * Quienes Somos page template.
 *
 * @package Marcan
 */

get_header();
?>
<main class="marcan-about-page">
    <?php get_template_part('template-parts/about/about-hero'); ?>
    <?php get_template_part('template-parts/about/about-reasons'); ?>
    <?php get_template_part('template-parts/about/about-iconic-projects'); ?>
    <?php get_template_part('template-parts/about/about-promise-awards-team'); ?>
</main>
<?php
get_footer();
