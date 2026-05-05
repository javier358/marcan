<?php
/**
 * Default fallback template.
 *
 * @package Marcan
 */

get_header();
?>

<section class="page-shell">
    <?php if (have_posts()) : ?>
        <?php
        while (have_posts()) :
            the_post();
            ?>
            <article <?php post_class('content-page'); ?>>
                <h1><?php the_title(); ?></h1>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </article>
            <?php
        endwhile;
        ?>
    <?php else : ?>
        <article class="content-page">
            <h1><?php esc_html_e('Contenido no encontrado', 'marcan'); ?></h1>
            <p><?php esc_html_e('Todavía no hay contenido publicado.', 'marcan'); ?></p>
        </article>
    <?php endif; ?>
</section>

<?php
get_footer();
