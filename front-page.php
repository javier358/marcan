<?php
/**
 * Front page template.
 *
 * @package Marcan
 */

get_header();
?>

<section class="intro-hero" aria-label="<?php esc_attr_e('Marcan', 'marcan'); ?>">
    <div class="intro-logo" data-reveal>marcan</div>
    <a class="intro-chevron" href="#propiedades" aria-label="<?php esc_attr_e('Ver propiedades', 'marcan'); ?>"></a>
</section>

<section class="section section-dark" id="propiedades">
    <div class="section-inner">
        <div class="section-heading" data-reveal>
            <p><?php esc_html_e('Propiedades', 'marcan'); ?></p>
            <h1><?php esc_html_e('Espacios listos para vivir e invertir.', 'marcan'); ?></h1>
        </div>
        <div class="property-grid">
            <?php
            $properties = new WP_Query(array(
                'post_type'      => 'propiedad',
                'posts_per_page' => 6,
            ));

            if ($properties->have_posts()) :
                while ($properties->have_posts()) :
                    $properties->the_post();
                    get_template_part('template-parts/property-card');
                endwhile;
                wp_reset_postdata();
            else :
                ?>
                <article class="empty-state">
                    <h2><?php esc_html_e('Agrega tu primera propiedad', 'marcan'); ?></h2>
                    <p><?php esc_html_e('Desde el panel de WordPress entra a Propiedades y crea una nueva ficha con imagen, precio, ubicación y características.', 'marcan'); ?></p>
                </article>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
get_footer();
