<?php
if (!defined('ABSPATH')) {
    exit;
}

$post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
$kind = marcan_get_property_kind($post_id);
$badge = trim((string) (get_field('home_badge_label', $post_id) ?: get_field('estado', $post_id)));
$location = trim((string) (get_field('home_location', $post_id) ?: get_field('ubicacion', $post_id)));
$price_label = trim((string) get_field('home_price_label', $post_id));
$price = trim((string) (get_field('home_price', $post_id) ?: get_field('precio', $post_id)));
$bedrooms = trim((string) (get_field('home_bedrooms_text', $post_id) ?: get_field('dormitorios', $post_id)));
$area = marcan_get_home_area_display($post_id);
$cta_label = trim((string) get_field('home_cta_label', $post_id));
$cta_link = get_field('home_cta_link', $post_id);
$desktop_image_id = (int) (get_field('home_desktop_image', $post_id) ?: get_field('listado_hero_imagen', $post_id) ?: get_post_thumbnail_id($post_id));
$mobile_image_id = (int) (get_field('home_mobile_image', $post_id) ?: $desktop_image_id);
$desktop_image = $desktop_image_id ? wp_get_attachment_image_src($desktop_image_id, 'full') : false;
$mobile_image_url = $mobile_image_id ? wp_get_attachment_image_url($mobile_image_id, 'full') : '';
$arrow_id = (int) get_option('marcan_project_icon_arrow_id');
$divider_id = (int) get_option('marcan_project_icon_divider_id');
$bedrooms_icon_id = (int) get_option('marcan_project_icon_bedrooms_id');
$area_icon_id = (int) get_option('marcan_project_icon_area_id');
$title = trim((string) get_field('home_title', $post_id));
$title = $title !== '' ? $title : get_the_title($post_id);
$title_plain = wp_strip_all_tags($title);
$url = $cta_link && is_array($cta_link) && !empty($cta_link['url']) ? $cta_link['url'] : get_permalink($post_id);
$target = $cta_link && is_array($cta_link) && !empty($cta_link['target']) ? $cta_link['target'] : '_self';
$title_attrs = marcan_font_size_attrs(marcan_get_field_font_size('titulo_comercial', $post_id));
$badge_attrs = marcan_font_size_attrs(marcan_get_field_font_size('estado', $post_id), 'marcan-home-project-card-badge');
$location_attrs = marcan_font_size_attrs(marcan_get_field_font_size('ubicacion', $post_id), 'marcan-home-project-card-location');
$price_label_attrs = marcan_font_size_attrs(marcan_get_field_font_size('home_price_label', $post_id));
$price_attrs = marcan_font_size_attrs(marcan_get_field_font_size('precio', $post_id));
$bedrooms_attrs = marcan_font_size_attrs(marcan_get_field_font_size('dormitorios', $post_id));
$cta_attrs = marcan_font_size_attrs(marcan_get_field_font_size('home_cta_label', $post_id), 'marcan-home-project-card-cta');
?>
<article class="marcan-home-project-card marcan-property-related-home-card <?php echo esc_attr($kind === 'oficina' ? 'is-office' : 'is-department'); ?>">
    <a class="marcan-home-project-card-link" href="<?php echo esc_url($url); ?>" target="<?php echo esc_attr($target); ?>">
        <div class="marcan-home-project-card-media">
            <picture>
                <?php if ($mobile_image_url !== '') : ?>
                    <source media="(max-width: 900px)" srcset="<?php echo esc_url($mobile_image_url); ?>">
                <?php endif; ?>
                <?php if ($desktop_image) : ?>
                    <img class="marcan-home-project-card-image" src="<?php echo esc_url($desktop_image[0]); ?>" width="<?php echo esc_attr((string) $desktop_image[1]); ?>" height="<?php echo esc_attr((string) $desktop_image[2]); ?>" alt="<?php echo esc_attr($title_plain); ?>" loading="eager" decoding="async">
                <?php endif; ?>
            </picture>
            <?php if ($arrow_id) : ?>
                <span class="marcan-home-project-card-arrow" aria-hidden="true"><?php echo wp_get_attachment_image($arrow_id, 'full', false, array('alt' => '')); ?></span>
            <?php endif; ?>
        </div>
        <div class="marcan-home-project-card-body">
            <div class="marcan-home-project-card-main">
                <div class="marcan-home-project-card-heading">
                    <div class="marcan-home-project-card-title-row">
                        <h3<?php echo $title_attrs; ?>><?php echo marcan_rich_inline($title); ?></h3>
                        <?php if ($divider_id) : ?>
                            <span class="marcan-home-project-card-divider" aria-hidden="true"><?php echo wp_get_attachment_image($divider_id, 'full', false, array('alt' => '')); ?></span>
                        <?php endif; ?>
                        <?php if ($badge !== '') : ?>
                            <span<?php echo $badge_attrs; ?>><?php echo marcan_rich_inline($badge); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if ($location !== '') : ?>
                        <p<?php echo $location_attrs; ?>><?php echo marcan_rich_inline($location); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="marcan-home-project-card-side">
                <div class="marcan-home-project-card-price">
                    <span<?php echo $price_label_attrs; ?>><?php echo marcan_rich_inline($price_label !== '' ? $price_label : __('Desde:', 'marcan')); ?></span>
                    <?php if ($price !== '') : ?><strong<?php echo $price_attrs; ?>><?php echo esc_html($price); ?></strong><?php endif; ?>
                </div>
                <div class="marcan-home-project-card-specs">
                    <?php if ($bedrooms !== '') : ?>
                        <div class="marcan-home-project-card-spec">
                            <?php if ($bedrooms_icon_id) : ?><span class="marcan-home-project-card-spec-icon"><?php echo wp_get_attachment_image($bedrooms_icon_id, 'full', false, array('alt' => '')); ?></span><?php endif; ?>
                            <span<?php echo $bedrooms_attrs; ?>><?php echo marcan_rich_inline($bedrooms); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($area !== '') : ?>
                        <div class="marcan-home-project-card-spec">
                            <?php if ($area_icon_id) : ?><span class="marcan-home-project-card-spec-icon"><?php echo wp_get_attachment_image($area_icon_id, 'full', false, array('alt' => '')); ?></span><?php endif; ?>
                            <span><?php echo esc_html($area); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="marcan-home-project-card-actions">
                <span<?php echo $cta_attrs; ?>><?php echo marcan_rich_inline($cta_label !== '' ? $cta_label : __('Ver más', 'marcan')); ?></span>
            </div>
        </div>
    </a>
</article>
