<?php
/**
 * Global site footer.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

$footer_bg = marcan_get_option_color('footer_background_color', '#ffcb05');
$footer_text = marcan_get_option_color('footer_text_color', '#4f4f4f');
$footer_projects_title = marcan_get_option_text('footer_projects_title', 'Encuentra tu espacio');
$footer_company_title = marcan_get_option_text('footer_company_title', 'Conoce Marcan');
if ($footer_projects_title === 'Proyectos actuales') {
    $footer_projects_title = 'Encuentra tu espacio';
}
if ($footer_company_title === 'Quiénes somos') {
    $footer_company_title = 'Conoce Marcan';
}
$footer_member_title = marcan_get_option_text('footer_member_title', 'Miembro de');
$footer_legal = marcan_get_option_text('footer_legal_text', 'Términos & Condiciones | Política de Privacidad | © 2026 Marcan Ingenieros');
$terms_url = home_url('/terminos-condiciones/');
$privacy_url = get_privacy_policy_url();
if ($privacy_url === '') {
    $privacy_url = home_url('/politicas-de-privacidad/');
}
$replacements = array(
    'Términos &amp; Condiciones' => '<a href="' . esc_url($terms_url) . '">Términos &amp; Condiciones</a>',
    'Términos y Condiciones'    => '<a href="' . esc_url($terms_url) . '">Términos y Condiciones</a>',
    'Términos & Condiciones'    => '<a href="' . esc_url($terms_url) . '">Términos & Condiciones</a>',
    'Política de Privacidad'    => '<a href="' . esc_url($privacy_url) . '">Política de Privacidad</a>',
    'Políticas de Privacidad'   => '<a href="' . esc_url($privacy_url) . '">Políticas de Privacidad</a>',
);
$footer_legal = str_replace(array_keys($replacements), array_values($replacements), $footer_legal);
$projects_button = function_exists('get_field') ? get_field('footer_projects_button', 'option') : null;
$projects_button_url = marcan_page_url('departamentos');
$projects_button_target = '_self';

$footer_projects_fallback = array(
    array('label' => 'Departamentos', 'url' => marcan_page_url('departamentos')),
    array('label' => 'Oficinas', 'url' => marcan_page_url('oficinas')),
);
$footer_company_fallback = array(
    array('label' => 'Quiénes somos', 'url' => marcan_page_url('quienes-somos')),
    array('label' => 'Proyectos icónicos', 'url' => marcan_page_url('quienes-somos')),
    array('label' => 'Blog', 'url' => marcan_page_url('blog')),
    array('label' => 'Políticas de privacidad', 'url' => marcan_page_url('politicas-de-privacidad')),
    array('label' => 'Contáctanos', 'url' => '#contacto'),
);
if (is_array($projects_button) && !empty($projects_button['url'])) {
    $projects_button_url = (string) $projects_button['url'];
    $projects_button_target = !empty($projects_button['target']) ? (string) $projects_button['target'] : '_self';
}
$brand_desktop = marcan_get_option_media_attachment_url('footer_brand_logo_desktop');
$brand_mobile = marcan_get_option_media_attachment_url('footer_brand_logo_mobile');
$arrow_desktop = marcan_get_option_media_attachment_url('footer_arrow_desktop');
$arrow_mobile = marcan_get_option_media_attachment_url('footer_arrow_mobile');
$socials = array(
    marcan_get_option_media_attachment_url('footer_social_1'),
    marcan_get_option_media_attachment_url('footer_social_2'),
    marcan_get_option_media_attachment_url('footer_social_3'),
    marcan_get_option_media_attachment_url('footer_social_4'),
);
$members = array(
    marcan_get_option_media_attachment_url('footer_member_1'),
    marcan_get_option_media_attachment_url('footer_member_2'),
    marcan_get_option_media_attachment_url('footer_member_3'),
);
$complaint_page = get_page_by_path('libro-de-reclamaciones');
$complaint_url = $complaint_page instanceof WP_Post ? get_permalink($complaint_page) : home_url('/libro-de-reclamaciones/');
$complaint_icon_id = (int) get_option('complaint_icon_id');
$complaint_icon = $complaint_icon_id ? wp_get_attachment_url($complaint_icon_id) : '';
if (!is_string($complaint_icon) || $complaint_icon === '') {
    $complaint_icon = marcan_asset_uri('images/libro-reclamaciones.svg');
}

$render_links = static function (string $theme_location, array $fallback_items): void {
    if (has_nav_menu($theme_location)) {
        wp_nav_menu(array(
            'theme_location' => $theme_location,
            'container'      => false,
            'menu_class'     => 'marcan-site-footer-links',
            'fallback_cb'    => '__return_false',
        ));
        return;
    }

    echo '<ul class="marcan-site-footer-links">';
    foreach ($fallback_items as $item) {
        printf('<li><a href="%s">%s</a></li>', esc_url($item['url']), esc_html($item['label']));
    }
    echo '</ul>';
};
?>

<footer class="marcan-site-footer" style="<?php echo esc_attr('--marcan-footer-bg:' . $footer_bg . ';--marcan-footer-text:' . $footer_text . ';'); ?>">
    <div class="marcan-site-footer-desktop">
        <div class="marcan-site-footer-left">
            <div class="marcan-site-footer-nav-title"><?php echo marcan_rich_inline($footer_projects_title); ?></div>
            <?php $render_links('footer_projects', $footer_projects_fallback); ?>
            <a class="marcan-site-footer-button" href="<?php echo esc_url($projects_button_url); ?>" target="<?php echo esc_attr($projects_button_target); ?>"><?php echo marcan_rich_inline(marcan_get_option_text('footer_projects_button_label', 'Ver Proyectos')); ?></a>
        </div>

        <div class="marcan-site-footer-center">
            <div class="marcan-site-footer-nav-title"><?php echo marcan_rich_inline($footer_company_title); ?></div>
            <?php $render_links('footer_company', $footer_company_fallback); ?>
        </div>

        <div class="marcan-site-footer-right">
            <div class="marcan-site-footer-contact">
                <p class="marcan-site-footer-address"><?php echo esc_html(marcan_get_option_text('footer_address', 'Av. Santa Cruz 830 Of. 402, Miraflores.')); ?></p>
                <p class="marcan-site-footer-phone-lines"><?php echo nl2br(esc_html(marcan_get_option_text('footer_phone_lines', "Contact Center: 919 490 440\nOficinas: (01) 711 9400"))); ?></p>
                <p class="marcan-site-footer-email"><?php echo esc_html(marcan_get_option_text('footer_email', 'Escríbenos a ventas@marcan.com.pe')); ?></p>
            </div>

            <div class="marcan-site-footer-socials" aria-label="<?php esc_attr_e('Redes sociales', 'marcan'); ?>">
                <?php foreach ($socials as $social_url) : ?>
                    <?php if ($social_url) : ?>
                        <span class="marcan-site-footer-social"><img src="<?php echo esc_url($social_url); ?>" alt="" aria-hidden="true"></span>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <div class="marcan-site-footer-members">
                <span class="marcan-site-footer-members-title"><?php echo marcan_rich_inline($footer_member_title); ?></span>
                <div class="marcan-site-footer-members-logos">
                    <?php foreach ($members as $member_url) : ?>
                        <?php if ($member_url) : ?>
                            <span class="marcan-site-footer-member"><img src="<?php echo esc_url($member_url); ?>" alt="" aria-hidden="true"></span>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <?php if ($complaint_icon) : ?>
                    <a class="marcan-site-footer-complaint" href="<?php echo esc_url($complaint_url); ?>" aria-label="<?php esc_attr_e('Libro de Reclamaciones', 'marcan'); ?>">
                        <img src="<?php echo esc_url($complaint_icon); ?>" alt="<?php esc_attr_e('Libro de Reclamaciones', 'marcan'); ?>">
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="marcan-site-footer-brand" data-footer-animation>
            <?php if ($brand_desktop) : ?>
                <img class="marcan-site-footer-brand-desktop" src="<?php echo esc_url($brand_desktop); ?>" alt="" aria-hidden="true">
            <?php endif; ?>
            <?php if ($arrow_desktop) : ?>
                <img class="marcan-site-footer-brand-arrow" src="<?php echo esc_url($arrow_desktop); ?>" alt="" aria-hidden="true">
            <?php endif; ?>
        </div>

        <p class="marcan-site-footer-legal"><?php echo marcan_rich_inline($footer_legal); ?></p>
    </div>

    <div class="marcan-site-footer-mobile">
        <div class="marcan-site-footer-mobile-group">
            <div class="marcan-site-footer-nav-title"><?php echo marcan_rich_inline($footer_projects_title); ?></div>
            <?php $render_links('footer_projects', $footer_projects_fallback); ?>
            <a class="marcan-site-footer-button" href="<?php echo esc_url($projects_button_url); ?>" target="<?php echo esc_attr($projects_button_target); ?>"><?php echo marcan_rich_inline(marcan_get_option_text('footer_projects_button_label', 'Ver Proyectos')); ?></a>
        </div>

        <div class="marcan-site-footer-mobile-group">
            <div class="marcan-site-footer-nav-title"><?php echo marcan_rich_inline($footer_company_title); ?></div>
            <?php $render_links('footer_company', $footer_company_fallback); ?>
        </div>

        <div class="marcan-site-footer-mobile-group">
            <p class="marcan-site-footer-contact-line"><?php echo esc_html(marcan_get_option_text('footer_address', 'Av. Santa Cruz 830 Of. 402, Miraflores.')); ?></p>
            <p class="marcan-site-footer-contact-line"><?php echo nl2br(esc_html(marcan_get_option_text('footer_phone_lines', "Contact Center: 919 490 440\nOficinas: (01) 711 9400"))); ?></p>
            <p class="marcan-site-footer-contact-line"><?php echo esc_html(marcan_get_option_text('footer_email', 'Escríbenos a ventas@marcan.com.pe')); ?></p>
        </div>

        <div class="marcan-site-footer-socials marcan-site-footer-socials-mobile" aria-label="<?php esc_attr_e('Redes sociales', 'marcan'); ?>">
            <?php foreach ($socials as $social_url) : ?>
                <?php if ($social_url) : ?>
                    <span class="marcan-site-footer-social"><img src="<?php echo esc_url($social_url); ?>" alt="" aria-hidden="true"></span>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <div class="marcan-site-footer-mobile-group">
            <span class="marcan-site-footer-members-title"><?php echo marcan_rich_inline($footer_member_title); ?></span>
            <div class="marcan-site-footer-members-logos">
                <?php foreach ($members as $member_url) : ?>
                    <?php if ($member_url) : ?>
                        <span class="marcan-site-footer-member"><img src="<?php echo esc_url($member_url); ?>" alt="" aria-hidden="true"></span>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php if ($complaint_icon) : ?>
                <a class="marcan-site-footer-complaint" href="<?php echo esc_url($complaint_url); ?>" aria-label="<?php esc_attr_e('Libro de Reclamaciones', 'marcan'); ?>">
                    <img src="<?php echo esc_url($complaint_icon); ?>" alt="<?php esc_attr_e('Libro de Reclamaciones', 'marcan'); ?>">
                </a>
            <?php endif; ?>
        </div>

        <div class="marcan-site-footer-brand-block">
            <div class="marcan-site-footer-brand marcan-site-footer-brand-mobile" data-footer-animation>
                <?php if ($brand_mobile) : ?>
                    <img class="marcan-site-footer-brand-desktop" src="<?php echo esc_url($brand_mobile); ?>" alt="" aria-hidden="true">
                <?php endif; ?>
                <?php if ($arrow_mobile) : ?>
                    <img class="marcan-site-footer-brand-arrow" src="<?php echo esc_url($arrow_mobile); ?>" alt="" aria-hidden="true">
                <?php endif; ?>
            </div>

            <p class="marcan-site-footer-legal"><?php echo marcan_rich_inline($footer_legal); ?></p>
        </div>
    </div>
</footer>
