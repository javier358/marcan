<?php
/**
 * Politicas de privacidad page template.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main class="marcan-page-shell marcan-page-shell-privacy">
    <section class="marcan-privacy-hero">
        <div class="marcan-privacy-hero-inner">
            <a href="javascript:history.back()" class="marcan-privacy-back" aria-label="<?php esc_attr_e('Volver', 'marcan'); ?>">
                <img src="<?php echo esc_url(marcan_asset_uri('images/figma-tour-arrow-left-v2.svg')); ?>" alt="" aria-hidden="true">
            </a>
            <h1><?php the_title(); ?></h1>
        </div>
    </section>

    <section class="marcan-privacy-body">
        <div class="marcan-privacy-prose">
            <?php echo apply_filters('the_content', marcan_get_privacy_body(get_queried_object_id())); ?>
        </div>
    </section>

    <section class="marcan-privacy-contact">
        <div class="marcan-privacy-contact-card">
            <h2><?php esc_html_e('¿Tienes consultas?', 'marcan'); ?></h2>
            <p><?php esc_html_e('Si deseas ejercer tus derechos ARCO o tienes dudas sobre nuestra política de privacidad, contáctanos.', 'marcan'); ?></p>
            <div class="marcan-privacy-contact-links">
                <a href="mailto:ventas@marcan.com.pe" class="marcan-privacy-contact-btn">ventas@marcan.com.pe</a>
                <button class="marcan-privacy-contact-btn marcan-privacy-contact-btn-dark" type="button" data-open-contact-modal><?php esc_html_e('Formulario de contacto', 'marcan'); ?></button>
            </div>
        </div>
    </section>
</main>
<?php
get_footer();
