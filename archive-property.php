<?php
/**
 * Property archive template.
 *
 * @package Marcan
 */

get_header();
?>
<div class="marcan-property-archive">
    <div class="marcan-property-archive-list">
        <?php
        if ( have_posts() ) :
            while ( have_posts() ) :
                the_post();
                get_template_part(
                    'template-parts/properties/property-card-listing',
                    null,
                    array( 'post_id' => get_the_ID(), 'layout' => 'media-left' )
                );
            endwhile;
        else :
            ?>
            <p><?php esc_html_e( 'No hay propiedades publicadas.', 'marcan' ); ?></p>
        <?php endif; ?>
    </div>
    <?php the_posts_pagination(); ?>
</div>
<?php
get_footer();
