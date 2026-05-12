<?php
/**
 * Home hero section.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

$asset_uri = get_template_directory_uri() . '/assets/';
$fallback_image = $asset_uri . 'img/hero-time.jpg';
$hero_settings = marcan_get_home_hero_settings();
$hero_query = marcan_get_hero_slides();
$slide_count = $hero_query->post_count;
?>

<section class="marcan-home-hero" aria-label="<?php esc_attr_e('Marcan', 'marcan'); ?>">
    <div class="marcan-home-hero-slider" data-hero-slider data-hero-effect="<?php echo esc_attr($hero_settings['effect']); ?>" data-hero-autoplay="<?php echo esc_attr($hero_settings['autoplay'] ? '1' : '0'); ?>" data-hero-interval="<?php echo esc_attr($hero_settings['interval']); ?>">
        <?php if ($hero_query->have_posts()) : ?>
            <?php $index = 0; ?>
            <?php while ($hero_query->have_posts()) : ?>
                <?php
                $hero_query->the_post();
                $desktop_image_id = (int) get_field('imagen_desktop', get_the_ID());
                $mobile_image_id = (int) get_field('imagen_movil', get_the_ID());
                $slide_label = (string) get_field('etiqueta', get_the_ID());
                $slide_link = get_field('enlace', get_the_ID());
                $slide_duration = (int) get_field('duracion', get_the_ID());
                $slide_effect = (string) get_field('efecto_transicion', get_the_ID());
                $is_active = $index === 0;
                ?>
                <article class="marcan-home-hero-slide<?php echo $is_active ? ' is-active' : ''; ?>" data-hero-slide data-hero-duration="<?php echo esc_attr($slide_duration > 0 ? $slide_duration : $hero_settings['interval']); ?>" data-hero-effect="<?php echo esc_attr($slide_effect ?: $hero_settings['effect']); ?>">
                    <div class="marcan-home-hero-media marcan-home-hero-media-desktop">
                        <?php
                        if ($desktop_image_id) {
                            echo wp_get_attachment_image($desktop_image_id, 'full', false, array(
                                'alt' => esc_attr(get_the_title()),
                            ));
                        } else {
                            printf('<img src="%s" alt="%s">', esc_url($fallback_image), esc_attr(get_the_title()));
                        }
                        ?>
                    </div>
                    <div class="marcan-home-hero-media marcan-home-hero-media-mobile">
                        <?php
                        if ($mobile_image_id) {
                            echo wp_get_attachment_image($mobile_image_id, 'full', false, array(
                                'alt' => esc_attr(get_the_title()),
                            ));
                        } elseif ($desktop_image_id) {
                            echo wp_get_attachment_image($desktop_image_id, 'full', false, array(
                                'alt' => esc_attr(get_the_title()),
                            ));
                        } else {
                            printf('<img src="%s" alt="%s">', esc_url($fallback_image), esc_attr(get_the_title()));
                        }
                        ?>
                    </div>
                    <?php if ($slide_label !== '') : ?>
                        <div class="marcan-home-hero-slide-label"><?php echo esc_html($slide_label); ?></div>
                    <?php endif; ?>
                    <?php if ($slide_link && is_array($slide_link) && !empty($slide_link['url'])) : ?>
                        <a class="marcan-home-hero-slide-link" href="<?php echo esc_url($slide_link['url']); ?>" target="<?php echo esc_attr($slide_link['target'] ?: '_self'); ?>">
                            <?php echo esc_html($slide_link['title'] ?: get_the_title()); ?>
                        </a>
                    <?php endif; ?>
                </article>
                <?php $index++; ?>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <article class="marcan-home-hero-slide is-active" data-hero-slide data-hero-duration="<?php echo esc_attr($hero_settings['interval']); ?>" data-hero-effect="<?php echo esc_attr($hero_settings['effect']); ?>">
                <div class="marcan-home-hero-media marcan-home-hero-media-desktop">
                    <img src="<?php echo esc_url($fallback_image); ?>" alt="<?php esc_attr_e('Edificio Time de Marcan', 'marcan'); ?>">
                </div>
                <div class="marcan-home-hero-media marcan-home-hero-media-mobile">
                    <img src="<?php echo esc_url($fallback_image); ?>" alt="<?php esc_attr_e('Edificio Time de Marcan', 'marcan'); ?>">
                </div>
            </article>
        <?php endif; ?>
    </div>
    <div class="marcan-home-hero-mobile-copy">
        <p><?php echo esc_html($hero_settings['mobile_copy']); ?></p>
    </div>
</section>
