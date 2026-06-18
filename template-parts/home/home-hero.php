<?php
/**
 * Home hero section.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

$hero_settings = marcan_get_home_hero_settings();
$hero_slides = marcan_get_home_hero_slides();
?>

<section class="marcan-home-hero" aria-label="<?php esc_attr_e('Marcan', 'marcan'); ?>">
    <div class="marcan-home-hero-slider" data-hero-slider data-hero-effect="<?php echo esc_attr($hero_settings['effect']); ?>" data-hero-autoplay="<?php echo esc_attr($hero_settings['autoplay'] ? '1' : '0'); ?>" data-hero-interval="<?php echo esc_attr($hero_settings['interval']); ?>">
        <?php if (!empty($hero_slides)) : ?>
            <?php foreach ($hero_slides as $index => $slide) : ?>
                <?php
                $slide_images = $slide['hero_imagenes'] ?? array();
                $slide_label = (string) ($slide['etiqueta'] ?? '');
                $slide_link = $slide['enlace'] ?? array();
                $slide_duration = (int) ($slide['duracion'] ?? 0);
                $slide_effect = (string) ($slide['efecto_transicion'] ?? '');
                $is_active = $index === 0;
                $slide_title = $slide_label !== '' ? $slide_label : sprintf(__('Slide %d', 'marcan'), $index + 1);
                $slide_title_plain = wp_strip_all_tags($slide_title);
                $slide_label_attrs = marcan_font_size_attrs(marcan_get_row_font_size($slide, 'etiqueta'), 'marcan-home-hero-slide-label');
                ?>
                <article class="marcan-home-hero-slide<?php echo $is_active ? ' is-active' : ''; ?>" data-hero-slide data-hero-duration="<?php echo esc_attr($slide_duration > 0 ? $slide_duration : $hero_settings['interval']); ?>" data-hero-effect="<?php echo esc_attr($slide_effect ?: $hero_settings['effect']); ?>">
                    <?php
                    echo marcan_render_hero_picture(is_array($slide_images) ? $slide_images : array(), $slide_title_plain, array(
                        'picture_class' => 'marcan-home-hero-media',
                        'eager' => $is_active,
                    ));
                    ?>
                    <?php if ($slide_label !== '') : ?>
                        <div<?php echo $slide_label_attrs; ?>><?php echo marcan_rich_inline($slide_label); ?></div>
                    <?php endif; ?>
                    <?php if ($slide_link && is_array($slide_link) && !empty($slide_link['url'])) : ?>
                        <a class="marcan-home-hero-slide-link" href="<?php echo esc_url($slide_link['url']); ?>" target="<?php echo esc_attr($slide_link['target'] ?: '_self'); ?>">
                            <?php echo marcan_rich_inline($slide_link['title'] ?: $slide_title); ?>
                        </a>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        <?php else : ?>
            <article class="marcan-home-hero-slide is-active" data-hero-slide data-hero-duration="<?php echo esc_attr($hero_settings['interval']); ?>" data-hero-effect="<?php echo esc_attr($hero_settings['effect']); ?>">
                <div class="marcan-home-hero-media">
                    <?php echo esc_html__('No hero slides configured.', 'marcan'); ?>
                </div>
            </article>
        <?php endif; ?>
    </div>
    <div class="marcan-home-hero-mobile-copy">
        <p<?php echo marcan_font_size_attrs($hero_settings['mobile_copy_font_size'] ?? array()); ?>><?php echo esc_html(wp_strip_all_tags($hero_settings['mobile_copy'])); ?></p>
    </div>
</section>
