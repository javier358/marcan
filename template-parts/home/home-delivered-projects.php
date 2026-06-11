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
$has_title = !empty($delivered['title']) || !empty($delivered['mobile_title']);
$has_button = $button_link !== '' && (!empty($delivered['button_label']) || !empty($delivered['mobile_button_label']));
$has_delivered = $desktop_image_id || $mobile_image_id || $has_title || $has_button;
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

<?php if ($has_delivered) : ?>
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

    <?php if ($has_title || $has_button) : ?>
        <div class="marcan-home-delivered-copy">
            <?php if ($has_title) : ?>
                <h2>
                    <?php if (!empty($delivered['title'])) : ?>
                        <span class="marcan-home-desktop-text"><?php echo marcan_rich_inline($delivered['title']); ?></span>
                    <?php endif; ?>
                    <?php if (!empty($delivered['mobile_title'])) : ?>
                        <span class="marcan-home-mobile-text"><?php echo marcan_rich_inline($delivered['mobile_title']); ?></span>
                    <?php endif; ?>
                </h2>
            <?php endif; ?>
            <?php if ($has_button) : ?>
                <a class="marcan-home-delivered-button" href="<?php echo esc_url($button_link); ?>" target="<?php echo esc_attr($button_target ?: '_self'); ?>">
                    <?php if (!empty($delivered['button_label'])) : ?>
                        <span class="marcan-home-desktop-text"><?php echo marcan_rich_inline($delivered['button_label']); ?></span>
                    <?php endif; ?>
                    <?php if (!empty($delivered['mobile_button_label'])) : ?>
                        <span class="marcan-home-mobile-text"><?php echo marcan_rich_inline($delivered['mobile_button_label']); ?></span>
                    <?php endif; ?>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>
<?php endif; ?>
