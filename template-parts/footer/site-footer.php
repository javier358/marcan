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
$footer_projects_title = marcan_get_option_text('footer_projects_title', 'Proyectos actuales');
$footer_company_title = marcan_get_option_text('footer_company_title', 'Quiénes somos');
$footer_member_title = marcan_get_option_text('footer_member_title', 'Miembro de');
$footer_legal = marcan_get_option_text('footer_legal_text', 'Términos & Condiciones | Política de Privacidad | © 2025 Marcan Ingenieros');
$projects_button = function_exists('get_field') ? get_field('footer_projects_button', 'option') : null;
$projects_button_url = home_url('/');
$projects_button_target = '_self';
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
            <div class="marcan-site-footer-nav-title"><?php echo esc_html($footer_projects_title); ?></div>
            <?php $render_links('footer_projects', array(array('label' => 'Departamentos', 'url' => home_url('/')), array('label' => 'Oficinas', 'url' => home_url('/')))); ?>
            <a class="marcan-site-footer-button" href="<?php echo esc_url($projects_button_url); ?>" target="<?php echo esc_attr($projects_button_target); ?>"><?php echo esc_html(marcan_get_option_text('footer_projects_button_label', 'Ver Proyectos')); ?></a>
        </div>

        <div class="marcan-site-footer-center">
            <div class="marcan-site-footer-nav-title"><?php echo esc_html($footer_company_title); ?></div>
            <?php $render_links('footer_company', array(array('label' => 'Proyectos icónicos', 'url' => home_url('/')), array('label' => 'Blog', 'url' => home_url('/')), array('label' => 'Contáctanos', 'url' => home_url('/')))); ?>
        </div>

        <div class="marcan-site-footer-right">
            <div class="marcan-site-footer-contact">
                <p><?php echo esc_html(marcan_get_option_text('footer_address', 'Av. Santa Cruz 830 Of. 402, Miraflores.')); ?></p>
                <p><?php echo nl2br(esc_html(marcan_get_option_text('footer_phone_lines', "Contact Center: 919 490 440\nOficinas: (01) 711 9400"))); ?></p>
                <p><?php echo esc_html(marcan_get_option_text('footer_email', 'Escríbenos a ventas@marcan.com.pe')); ?></p>
            </div>

            <div class="marcan-site-footer-socials" aria-label="<?php esc_attr_e('Redes sociales', 'marcan'); ?>">
                <?php foreach ($socials as $social_url) : ?>
                    <?php if ($social_url) : ?>
                        <span class="marcan-site-footer-social"><img src="<?php echo esc_url($social_url); ?>" alt="" aria-hidden="true"></span>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <div class="marcan-site-footer-members">
                <span class="marcan-site-footer-members-title"><?php echo esc_html($footer_member_title); ?></span>
                <div class="marcan-site-footer-members-logos">
                    <?php foreach ($members as $member_url) : ?>
                        <?php if ($member_url) : ?>
                            <span class="marcan-site-footer-member"><img src="<?php echo esc_url($member_url); ?>" alt="" aria-hidden="true"></span>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
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

        <p class="marcan-site-footer-legal"><?php echo esc_html($footer_legal); ?></p>
    </div>

    <div class="marcan-site-footer-mobile">
        <div class="marcan-site-footer-mobile-group">
            <div class="marcan-site-footer-nav-title"><?php echo esc_html($footer_projects_title); ?></div>
            <?php $render_links('footer_projects', array(array('label' => 'Departamentos', 'url' => home_url('/')), array('label' => 'Oficinas', 'url' => home_url('/')))); ?>
            <a class="marcan-site-footer-button" href="<?php echo esc_url($projects_button_url); ?>" target="<?php echo esc_attr($projects_button_target); ?>"><?php echo esc_html(marcan_get_option_text('footer_projects_button_label', 'Ver Proyectos')); ?></a>
        </div>

        <div class="marcan-site-footer-mobile-group">
            <div class="marcan-site-footer-nav-title"><?php echo esc_html($footer_company_title); ?></div>
            <?php $render_links('footer_company', array(array('label' => 'Proyectos icónicos', 'url' => home_url('/')), array('label' => 'Blog', 'url' => home_url('/')), array('label' => 'Contáctanos', 'url' => home_url('/')))); ?>
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
            <span class="marcan-site-footer-members-title"><?php echo esc_html($footer_member_title); ?></span>
            <div class="marcan-site-footer-members-logos">
                <?php foreach ($members as $member_url) : ?>
                    <?php if ($member_url) : ?>
                        <span class="marcan-site-footer-member"><img src="<?php echo esc_url($member_url); ?>" alt="" aria-hidden="true"></span>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
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

            <p class="marcan-site-footer-legal"><?php echo esc_html($footer_legal); ?></p>
        </div>
    </div>
</footer>
