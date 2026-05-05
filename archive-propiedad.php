<?php
/**
 * Property archive template.
 *
 * @package Marcan
 */

get_header();
?>
<section class="page-shell">
    <div class="section-heading">
        <p><?php esc_html_e('Propiedades', 'marcan'); ?></p>
        <h1><?php post_type_archive_title(); ?></h1>
    </div>
    <div class="property-grid">
        <?php
        if (have_posts()) :
            while (have_posts()) :
                the_post();
                get_template_part('template-parts/property-card');
            endwhile;
        else :
            ?>
            <p><?php esc_html_e('No hay propiedades publicadas.', 'marcan'); ?></p>
        <?php endif; ?>
    </div>
    <?php the_posts_pagination(); ?>
</section>
<?php
get_footer();
