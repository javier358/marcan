<?php
/**
 * Front page template.
 *
 * @package Marcan
 */

get_header();

$asset_uri = get_template_directory_uri() . '/assets/';
$hero_image = $asset_uri . 'img/hero-time.jpg';
$featured_properties = new WP_Query(array(
    'post_type'      => 'property',
    'posts_per_page' => 5,
));

$department_cards = array(
    array(
        'title'    => 'Llano Zapata 430',
        'status'   => 'En obra',
        'location' => 'Llano Zapata 430 - Miraflores',
        'price'    => 'S/ 846,000',
        'details'  => '1 a 4 dormitorios | 80 m²',
        'image'    => $asset_uri . 'img/llano-zapata.jpg',
    ),
    array(
        'title'    => 'Costa de Lima',
        'status'   => 'En lanzamiento',
        'location' => 'Av. 28 de Julio 320 - Miraflores',
        'price'    => 'S/ 775,000',
        'details'  => '1 a 2 dormitorios | 80 m²',
        'image'    => $asset_uri . 'img/costa-lima.jpg',
    ),
);

$office_cards = array(
    array(
        'title'    => 'Time: Aramburú',
        'status'   => 'En obra',
        'location' => 'Av. Andrés Aramburú 609 - San Isidro',
        'price'    => 'S/ 352,500',
        'details'  => '25 m²',
        'image'    => $asset_uri . 'img/time-aramburu.jpg',
    ),
    array(
        'title'    => 'Time: Angamos',
        'status'   => 'Pre venta',
        'location' => 'Av. Angamos Oeste 500 - Miraflores',
        'price'    => 'S/ 461,000',
        'details'  => '23 m²',
        'image'    => $asset_uri . 'img/time-angamos.jpg',
    ),
    array(
        'title'    => 'Time: Benavides',
        'status'   => 'En lanzamiento',
        'location' => 'Av. Alfredo Benavides 2088 - Miraflores',
        'price'    => 'S/ 355,800',
        'details'  => '29 m²',
        'image'    => $asset_uri . 'img/time-benavides.jpg',
    ),
);
?>

<section class="marcan-home-hero" aria-label="<?php esc_attr_e('Marcan', 'marcan'); ?>">
    <div class="marcan-home-hero-media">
        <img src="<?php echo esc_url($hero_image); ?>" alt="<?php esc_attr_e('Edificio Time de Marcan', 'marcan'); ?>">
    </div>
    <div class="marcan-home-hero-mobile-copy">
        <p><?php esc_html_e('Somos una inmobiliaria enfocada en hacer proyectos que impulsan el desarrollo urbano de Lima, inspirados en las verdaderas necesidades de las personas y de la ciudad.', 'marcan'); ?></p>
    </div>
</section>

<section class="about-band">
    <div class="about-copy" data-reveal>
        <p><?php esc_html_e('Quiénes somos', 'marcan'); ?></p>
        <h2><?php esc_html_e('Somos una inmobiliaria enfocada en hacer proyectos que impulsan el desarrollo urbano de Lima, inspirados en las verdaderas necesidades de las personas y de la ciudad.', 'marcan'); ?></h2>
    </div>
    <div class="about-card" data-reveal>
        <img src="<?php echo esc_url($asset_uri . 'img/lobby.jpg'); ?>" alt="<?php esc_attr_e('Interior de proyecto Marcan', 'marcan'); ?>">
        <div>
            <h3><?php esc_html_e('Tenemos una manera diferente de hacer las cosas', 'marcan'); ?></h3>
            <a href="<?php echo esc_url(home_url('/quienes-somos/')); ?>"><?php esc_html_e('Conoce más sobre nosotros', 'marcan'); ?></a>
        </div>
    </div>
</section>

<section class="section section-light" id="propiedades">
    <div class="section-inner">
        <div class="section-heading" data-reveal>
            <p><?php esc_html_e('Departamentos en venta', 'marcan'); ?></p>
            <h2><?php esc_html_e('Vive cerca de lo que mueve tu día.', 'marcan'); ?></h2>
            <a href="<?php echo esc_url(get_post_type_archive_link('property') ?: '#'); ?>"><?php esc_html_e('Ver más departamentos', 'marcan'); ?></a>
        </div>
        <div class="marcan-project-grid project-grid-2">
            <?php foreach ($department_cards as $card) : ?>
                <?php get_template_part('template-parts/project-card', null, $card); ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section-white">
    <div class="section-inner">
        <div class="section-heading" data-reveal>
            <p><?php esc_html_e('Oficinas en venta', 'marcan'); ?></p>
            <h2><?php esc_html_e('Espacios de trabajo diseñados para crecer.', 'marcan'); ?></h2>
            <a href="<?php echo esc_url(get_post_type_archive_link('property') ?: '#'); ?>"><?php esc_html_e('Ver más oficinas', 'marcan'); ?></a>
        </div>
        <div class="marcan-project-grid project-grid-3">
            <?php foreach ($office_cards as $card) : ?>
                <?php get_template_part('template-parts/project-card', null, $card); ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php if ($featured_properties->have_posts()) : ?>
    <section class="section section-dark">
        <div class="section-inner">
            <div class="section-heading" data-reveal>
                <p><?php esc_html_e('Administrable', 'marcan'); ?></p>
                <h2><?php esc_html_e('Propiedades cargadas desde WordPress.', 'marcan'); ?></h2>
            </div>
            <div class="property-grid">
                <?php
                while ($featured_properties->have_posts()) :
                    $featured_properties->the_post();
                    get_template_part('template-parts/property-card');
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<section class="delivered-band">
    <div class="delivered-copy" data-reveal>
        <p><?php esc_html_e('Nuestros proyectos entregados hablan por nosotros', 'marcan'); ?></p>
        <a href="<?php echo esc_url(home_url('/quienes-somos/')); ?>"><?php esc_html_e('Conoce más sobre nosotros', 'marcan'); ?></a>
    </div>
    <div class="delivered-list" data-reveal>
        <span><?php esc_html_e('Proyectos actuales', 'marcan'); ?></span>
        <span><?php esc_html_e('Departamentos', 'marcan'); ?></span>
        <span><?php esc_html_e('Oficinas', 'marcan'); ?></span>
        <span><?php esc_html_e('Proyectos icónicos', 'marcan'); ?></span>
    </div>
</section>

<?php
get_footer();
