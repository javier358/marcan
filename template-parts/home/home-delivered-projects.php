<?php
/**
 * Home delivered projects block.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

$delivered = marcan_get_home_delivered_settings();
$desktop_image_id = (int) $delivered['image_desktop_id'];
$mobile_image_id = (int) $delivered['image_mobile_id'];
$button_link = $delivered['button_url'];
$button_target = '_self';
if (function_exists('get_field')) {
    $page_id = marcan_get_front_page_id();
    if ($page_id) {
        $button = get_field('home_delivered_button', $page_id);
        if (is_array($button) && !empty($button['target'])) {
            $button_target = (string) $button['target'];
        }
    }
}
?>

<section class="marcan-home-delivered" aria-label="<?php esc_attr_e('Proyectos entregados', 'marcan'); ?>" style="<?php echo esc_attr('--marcan-delivered-bg:' . $delivered['background_color'] . ';--marcan-delivered-text:' . $delivered['text_color'] . ';--marcan-delivered-btn-bg:' . $delivered['button_bg_color'] . ';--marcan-delivered-btn-text:' . $delivered['button_text_color'] . ';'); ?>">
    <div class="marcan-home-delivered-media">
        <picture>
            <?php if ($mobile_image_id) : ?>
                <source media="(max-width: 900px)" srcset="<?php echo esc_url(wp_get_attachment_image_url($mobile_image_id, 'full')); ?>">
            <?php endif; ?>
            <?php if ($desktop_image_id) : ?>
                <?php echo wp_get_attachment_image($desktop_image_id, 'full', false, array('class' => 'marcan-home-delivered-image', 'alt' => '')); ?>
            <?php elseif ($mobile_image_id) : ?>
                <?php echo wp_get_attachment_image($mobile_image_id, 'full', false, array('class' => 'marcan-home-delivered-image', 'alt' => '')); ?>
            <?php endif; ?>
        </picture>
    </div>

    <div class="marcan-home-delivered-copy">
        <h2><?php echo esc_html($delivered['title']); ?></h2>
        <a class="marcan-home-delivered-button" href="<?php echo esc_url($button_link); ?>" target="<?php echo esc_attr($button_target ?: '_self'); ?>">
            <?php echo esc_html($delivered['button_label']); ?>
        </a>
    </div>
</section>
