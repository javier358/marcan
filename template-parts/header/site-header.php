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
<header class="marcan-site-header" data-marcan-header>
    <a class="marcan-header-logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
        <?php $desktop_logo = marcan_get_media_attachment_url('marcan_header_logo_desktop_id'); ?>
        <?php $mobile_logo = marcan_get_media_attachment_url('marcan_header_logo_mobile_id'); ?>
        <span class="marcan-logo-desktop" aria-hidden="true">
            <?php if ($desktop_logo) : ?>
                <img src="<?php echo esc_url($desktop_logo); ?>" alt="" width="110" height="22">
            <?php else : ?>
                <?php echo marcan_svg('marcan-logo-desktop'); ?>
            <?php endif; ?>
        </span>
        <span class="marcan-logo-mobile" aria-hidden="true">
            <?php if ($mobile_logo) : ?>
                <img src="<?php echo esc_url($mobile_logo); ?>" alt="" width="100" height="20">
            <?php else : ?>
                <?php echo marcan_svg('marcan-logo-mobile'); ?>
            <?php endif; ?>
        </span>
    </a>

    <button class="marcan-menu-button" type="button" aria-expanded="false" aria-controls="primary-menu" data-menu-toggle>
        <span><?php esc_html_e('MENU', 'marcan'); ?></span>
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
