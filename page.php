<?php
/**
 * Default page template.
 *
 * @package Marcan
 */

get_header();
?>
<section class="page-shell">
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
</section>
<?php
get_footer();
