<?php
/**
 * Single post template.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

$dept_query = new WP_Query(array(
    'post_type'      => 'property',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'meta_query'     => array(array(
        'key'   => 'tipo_inmueble',
        'value' => 'departamento',
    )),
));

$ofi_query = new WP_Query(array(
    'post_type'      => 'property',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'meta_query'     => array(array(
        'key'   => 'tipo_inmueble',
        'value' => 'oficina',
    )),
));

get_header();
?>
<main class="marcan-single">
    <?php while (have_posts()) : the_post(); ?>

    <section class="marcan-single-hero">
        <div class="marcan-single-hero-top">
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('blog'))); ?>" class="marcan-single-back" aria-label="<?php esc_attr_e('Volver', 'marcan'); ?>">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M16.667 10H3.333M3.333 10L10 16.667M3.333 10L10 3.333" stroke="#4f4f4f" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('F j, Y')); ?></time>
        </div>
        <h1><?php the_title(); ?></h1>
        <?php if (has_post_thumbnail()) : ?>
            <div class="marcan-single-hero-image">
                <?php the_post_thumbnail('full'); ?>
            </div>
        <?php endif; ?>
    </section>

    <section class="marcan-single-body">
        <article class="marcan-single-content">
            <?php the_content(); ?>
        </article>

        <aside class="marcan-single-sidebar">
            <?php if ($dept_query->have_posts()) : ?>
                <div class="marcan-single-sidebar-group">
                    <h2><?php esc_html_e('Departamentos para ti', 'marcan'); ?></h2>
                    <?php while ($dept_query->have_posts()) : $dept_query->the_post(); ?>
                        <?php
                        $nombre   = function_exists('get_field') ? get_field('titulo_comercial', get_the_ID()) : '';
                        $estado   = function_exists('get_field') ? get_field('estado', get_the_ID()) : '';
                        $ubicacion = function_exists('get_field') ? get_field('ubicacion', get_the_ID()) : '';
                        if (!$nombre) $nombre = get_the_title();
                        ?>
                        <div class="marcan-single-sidebar-card">
                            <?php
                            $mobile_img_id = function_exists('get_field') ? get_field('home_mobile_image', get_the_ID()) : '';
                            ?>
                            <a href="<?php the_permalink(); ?>" class="marcan-single-sidebar-card-image">
                                <?php if ($mobile_img_id) : ?>
                                    <?php echo wp_get_attachment_image((int) $mobile_img_id, 'medium_large', false, array('alt' => esc_attr(get_the_title()))); ?>
                                <?php elseif (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium_large'); ?>
                                <?php endif; ?>
                            </a>
                            <div class="marcan-single-sidebar-card-meta">
                                <span class="marcan-single-sidebar-card-name"><?php echo esc_html($nombre); ?></span>
                                <?php if ($estado) : ?>
                                    <span class="marcan-single-sidebar-card-sep" aria-hidden="true"></span>
                                    <span class="marcan-single-sidebar-card-badge"><?php echo esc_html($estado); ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if ($ubicacion) : ?>
                                <span class="marcan-single-sidebar-card-district"><?php echo esc_html($ubicacion); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>

            <?php if ($ofi_query->have_posts()) : ?>
                <div class="marcan-single-sidebar-group">
                    <h2><?php esc_html_e('Oficinas para ti', 'marcan'); ?></h2>
                    <?php while ($ofi_query->have_posts()) : $ofi_query->the_post(); ?>
                        <?php
                        $nombre   = function_exists('get_field') ? get_field('titulo_comercial', get_the_ID()) : '';
                        $estado   = function_exists('get_field') ? get_field('estado', get_the_ID()) : '';
                        $ubicacion = function_exists('get_field') ? get_field('ubicacion', get_the_ID()) : '';
                        if (!$nombre) $nombre = get_the_title();
                        ?>
                        <div class="marcan-single-sidebar-card">
                            <?php
                            $mobile_img_id = function_exists('get_field') ? get_field('home_mobile_image', get_the_ID()) : '';
                            ?>
                            <a href="<?php the_permalink(); ?>" class="marcan-single-sidebar-card-image">
                                <?php if ($mobile_img_id) : ?>
                                    <?php echo wp_get_attachment_image((int) $mobile_img_id, 'medium_large', false, array('alt' => esc_attr(get_the_title()))); ?>
                                <?php elseif (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium_large'); ?>
                                <?php endif; ?>
                            </a>
                            <div class="marcan-single-sidebar-card-meta">
                                <span class="marcan-single-sidebar-card-name"><?php echo esc_html($nombre); ?></span>
                                <?php if ($estado) : ?>
                                    <span class="marcan-single-sidebar-card-sep" aria-hidden="true"></span>
                                    <span class="marcan-single-sidebar-card-badge"><?php echo esc_html($estado); ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if ($ubicacion) : ?>
                                <span class="marcan-single-sidebar-card-district"><?php echo esc_html($ubicacion); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
            <?php wp_reset_postdata(); ?>
        </aside>
    </section>

    <section class="marcan-single-contact">
        <div class="marcan-single-contact-form-area">
            <h2><?php esc_html_e('Contáctanos', 'marcan'); ?></h2>
            <form class="marcan-single-contact-form" data-contact-form novalidate>
                <div class="marcan-single-contact-fields">
                    <div class="marcan-single-contact-field">
                        <label for="contact-name"><?php esc_html_e('Nombre*', 'marcan'); ?></label>
                        <input type="text" id="contact-name" name="name" placeholder="<?php esc_attr_e('Ingresa tu nombre', 'marcan'); ?>" required>
                    </div>
                    <div class="marcan-single-contact-field">
                        <label for="contact-lastname"><?php esc_html_e('Apellido*', 'marcan'); ?></label>
                        <input type="text" id="contact-lastname" name="lastname" placeholder="<?php esc_attr_e('Ingresa tu apellido', 'marcan'); ?>" required>
                    </div>
                    <div class="marcan-single-contact-field">
                        <label for="contact-email"><?php esc_html_e('Email*', 'marcan'); ?></label>
                        <input type="email" id="contact-email" name="email" placeholder="nombre@correo.com" required>
                    </div>
                    <div class="marcan-single-contact-field">
                        <label for="contact-phone"><?php esc_html_e('Teléfono/Celular*', 'marcan'); ?></label>
                        <input type="tel" id="contact-phone" name="phone" placeholder="999 999 999" required>
                    </div>
                </div>
                <div class="marcan-single-contact-checks">
                    <label class="marcan-single-contact-check">
                        <input type="checkbox" name="privacy" required>
                        <span><?php esc_html_e('He leído y acepto las Políticas de privacidad y otorgo mi consentimiento para el envío de información relacionada con las consultas efectuadas a través del formulario web.', 'marcan'); ?></span>
                    </label>
                    <label class="marcan-single-contact-check">
                        <input type="checkbox" name="marketing">
                        <span><?php esc_html_e('Otorgo mi consentimiento para el envío de publicidad y/o anuncios comerciales y/o invitaciones a eventos; así como el envío de encuestas sobre proyectos o servicios de Inmobiliaria y Constructora Marcan S.A', 'marcan'); ?></span>
                    </label>
                </div>
                <input type="hidden" name="action" value="marcan_contact_submit">
                <input type="hidden" name="source_url" value="<?php echo esc_url(get_permalink()); ?>">
                <input type="hidden" name="source_title" value="<?php echo esc_attr(get_the_title()); ?>">
                <?php wp_nonce_field('marcan_contact', 'marcan_contact_nonce'); ?>
                <button type="submit" class="marcan-single-contact-submit">
                    <span><?php esc_html_e('Contactar', 'marcan'); ?></span>
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M18.333 1.667L9.167 10.833M18.333 1.667L12.5 18.333L9.167 10.833M18.333 1.667L1.667 7.5L9.167 10.833" stroke="#fbfafa" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </form>
        </div>
        <div class="marcan-single-contact-info">
            <h3><?php esc_html_e('Contáctanos ahora', 'marcan'); ?></h3>
            <div class="marcan-single-contact-info-details">
                <p><strong><?php esc_html_e('Contact Center:', 'marcan'); ?></strong> 919 490 440<br>
                <strong><?php esc_html_e('Oficinas:', 'marcan'); ?></strong> (01) 711 9400</p>
                <p><strong><?php esc_html_e('Correo:', 'marcan'); ?></strong> ventas@marcan.com.pe</p>
                <p><strong><?php esc_html_e('Sala de Ventas:', 'marcan'); ?></strong><br>
                Av. 28 de Julio 1150, Miraflores, Lima, Perú</p>
                <p><strong><?php esc_html_e('Horarios:', 'marcan'); ?></strong><br>
                <?php esc_html_e('Lun a Sab 8:00am - 08:00pm', 'marcan'); ?><br>
                <?php esc_html_e('Dom 10:00am - 03:00pm', 'marcan'); ?></p>
            </div>
        </div>
    </section>

    <?php endwhile; ?>
</main>
<?php get_footer(); ?>
