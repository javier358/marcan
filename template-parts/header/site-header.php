<?php
/**
 * Pixel-mapped home header from Figma nodes 8002:455 and 9010:3144.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<?php
$header_logo_desktop = marcan_get_option_media_attachment_url('header_logo_desktop', 'marcan_header_logo_desktop_id');
$header_logo_mobile = marcan_get_option_media_attachment_url('header_logo_mobile', 'marcan_header_logo_mobile_id');
$header_menu_label = marcan_get_option_text('header_menu_label', 'MENU');
$header_background = marcan_get_option_color('header_background_color', 'rgba(234,234,232,0.72)');
$header_text_color = marcan_get_option_color('header_text_color', '#4f4f4f');
$header_blur = is_numeric(marcan_get_option_field('header_blur_amount', 70)) ? (int) marcan_get_option_field('header_blur_amount', 70) : 70;
$header_styles = sprintf('--marcan-header-bg:%s;--marcan-header-text:%s;--marcan-header-blur:%dpx;', $header_background, $header_text_color, $header_blur);
?>
<header class="marcan-site-header" data-marcan-header style="<?php echo esc_attr($header_styles); ?>">
    <a class="marcan-header-logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
        <span class="marcan-logo-desktop" aria-hidden="true">
            <?php if ($header_logo_desktop) : ?>
                <img src="<?php echo esc_url($header_logo_desktop); ?>" alt="" width="110" height="22">
            <?php else : ?>
                <?php echo marcan_svg('marcan-logo-desktop'); ?>
            <?php endif; ?>
        </span>
        <span class="marcan-logo-mobile" aria-hidden="true">
            <?php if ($header_logo_mobile) : ?>
                <img src="<?php echo esc_url($header_logo_mobile); ?>" alt="" width="100" height="20">
            <?php else : ?>
                <?php echo marcan_svg('marcan-logo-mobile'); ?>
            <?php endif; ?>
        </span>
    </a>

    <button class="marcan-menu-button" type="button" aria-expanded="false" aria-controls="primary-menu" data-menu-toggle>
        <span><?php echo esc_html($header_menu_label); ?></span>
        <svg class="marcan-menu-icon" width="34" height="34" viewBox="0 0 34 34" aria-hidden="true" focusable="false">
            <path d="M10 13l7 8 7-8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>

    <nav class="marcan-primary-nav" id="primary-menu" data-primary-nav aria-label="<?php esc_attr_e('Menú principal', 'marcan'); ?>" hidden>
        <?php
        wp_nav_menu(array(
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => 'marcan-menu-list',
        ));
        ?>
    </nav>
</header>
