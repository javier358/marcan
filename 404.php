<?php
/**
 * 404 template.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main class="marcan-error-page">
    <section class="marcan-error-page-inner">
        <div class="marcan-error-page-copy">
            <h1><?php esc_html_e('¡Ay no!', 'marcan'); ?></h1>
            <h2><?php esc_html_e('No encontramos la página que buscas', 'marcan'); ?></h2>
            <p><?php esc_html_e('Pero no te preocupes, te invitamos a explorar:', 'marcan'); ?></p>
            <div class="marcan-error-page-actions">
                <a href="<?php echo esc_url(home_url('/departamentos/')); ?>"><?php esc_html_e('Ver departamentos', 'marcan'); ?></a>
                <a href="<?php echo esc_url(home_url('/oficinas/')); ?>"><?php esc_html_e('Ver oficinas', 'marcan'); ?></a>
            </div>
        </div>
        <figure class="marcan-error-page-media">
            <img src="<?php echo esc_url(marcan_asset_uri('images/hombre-404s.png')); ?>" alt="">
        </figure>
    </section>
</main>
<?php
get_footer();
