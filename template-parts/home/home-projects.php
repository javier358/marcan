<?php
/**
 * Home project slider sections.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

$project_settings = marcan_get_home_projects_settings();
$departments_query = marcan_get_project_sections('departamentos');
$offices_query = marcan_get_project_sections('oficinas');

function marcan_render_home_project_card(WP_Post $post, string $section_class = ''): void
{
    $post_id = (int) $post->ID;
    $badge = trim((string) get_field('home_badge_label', $post_id));
    $location = trim((string) get_field('home_location', $post_id));
    $price_label = trim((string) get_field('home_price_label', $post_id));
    $price = trim((string) get_field('home_price', $post_id));
    $bedrooms = trim((string) get_field('home_bedrooms_text', $post_id));
    $area = trim((string) get_field('home_area_text', $post_id));
    $cta_label = trim((string) get_field('home_cta_label', $post_id));
    $cta_link = get_field('home_cta_link', $post_id);
    $desktop_image_id = (int) get_field('home_desktop_image', $post_id);
    $mobile_image_id = (int) get_field('home_mobile_image', $post_id);
    $image_left = is_numeric(get_field('home_image_left', $post_id)) ? (float) get_field('home_image_left', $post_id) : 0;
    $image_top = is_numeric(get_field('home_image_top', $post_id)) ? (float) get_field('home_image_top', $post_id) : 0;
    $image_width = is_numeric(get_field('home_image_width', $post_id)) ? (float) get_field('home_image_width', $post_id) : 100;
    $image_height = is_numeric(get_field('home_image_height', $post_id)) ? (float) get_field('home_image_height', $post_id) : 100;
    $image_fit = get_field('home_image_fit', $post_id) === 'fill' ? 'fill' : 'cover';
    $image_style = sprintf(
        '--project-image-left:%s%%;--project-image-top:%s%%;--project-image-width:%s%%;--project-image-height:%s%%;--project-image-fit:%s;',
        esc_attr((string) $image_left),
        esc_attr((string) $image_top),
        esc_attr((string) $image_width),
        esc_attr((string) $image_height),
        esc_attr($image_fit)
    );
    $arrow_id = (int) get_option('marcan_project_icon_arrow_id');
    $divider_id = (int) get_option('marcan_project_icon_divider_id');
    $bedrooms_icon_id = (int) get_option('marcan_project_icon_bedrooms_id');
    $area_icon_id = (int) get_option('marcan_project_icon_area_id');
    $title = get_the_title($post_id);
    $default_price_label = $price_label !== '' ? $price_label : __('Desde:', 'marcan');
    $default_cta_label = $cta_label !== '' ? $cta_label : html_entity_decode('Ver m&aacute;s', ENT_QUOTES, 'UTF-8');
    ?>
    <article class="marcan-home-project-card <?php echo esc_attr($section_class); ?>" data-reveal>
        <a class="marcan-home-project-card-link" href="<?php echo esc_url($cta_link && is_array($cta_link) && !empty($cta_link['url']) ? $cta_link['url'] : get_permalink($post_id)); ?>" target="<?php echo esc_attr($cta_link && is_array($cta_link) && !empty($cta_link['target']) ? $cta_link['target'] : '_self'); ?>">
            <div class="marcan-home-project-card-media" style="<?php echo esc_attr($image_style); ?>">
                <picture>
                    <?php if ($mobile_image_id) : ?>
                        <source media="(max-width: 900px)" srcset="<?php echo esc_url(wp_get_attachment_image_url($mobile_image_id, 'full')); ?>">
                    <?php endif; ?>
                    <?php if ($desktop_image_id) : ?>
                        <?php echo wp_get_attachment_image($desktop_image_id, 'full', false, array('class' => 'marcan-home-project-card-image', 'alt' => esc_attr($title), 'sizes' => '(max-width: 900px) 315px, 990px')); ?>
                    <?php elseif ($mobile_image_id) : ?>
                        <?php echo wp_get_attachment_image($mobile_image_id, 'full', false, array('class' => 'marcan-home-project-card-image', 'alt' => esc_attr($title), 'sizes' => '(max-width: 900px) 315px, 990px')); ?>
                    <?php endif; ?>
                </picture>
                <?php if ($arrow_id) : ?>
                    <span class="marcan-home-project-card-arrow" aria-hidden="true">
                        <?php echo wp_get_attachment_image($arrow_id, 'full', false, array('alt' => '')); ?>
                    </span>
                <?php endif; ?>
            </div>
            <div class="marcan-home-project-card-body">
                <div class="marcan-home-project-card-main">
                    <div class="marcan-home-project-card-heading">
                        <div class="marcan-home-project-card-title-row">
                            <h3><?php echo esc_html($title); ?></h3>
                            <?php if ($divider_id) : ?>
                                <span class="marcan-home-project-card-divider" aria-hidden="true"><?php echo wp_get_attachment_image($divider_id, 'full', false, array('alt' => '')); ?></span>
                            <?php endif; ?>
                            <?php if ($badge !== '') : ?>
                                <span class="marcan-home-project-card-badge"><?php echo esc_html($badge); ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if ($location !== '') : ?>
                            <p class="marcan-home-project-card-location"><?php echo esc_html($location); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="marcan-home-project-card-actions">
                        <span class="marcan-home-project-card-cta"><?php echo esc_html($default_cta_label); ?></span>
                    </div>
                </div>
                <div class="marcan-home-project-card-side">
                    <div class="marcan-home-project-card-price">
                        <span><?php echo esc_html($default_price_label); ?></span>
                        <?php if ($price !== '') : ?>
                            <strong><?php echo esc_html($price); ?></strong>
                        <?php endif; ?>
                    </div>
                    <div class="marcan-home-project-card-specs">
                        <?php if ($bedrooms !== '') : ?>
                            <div class="marcan-home-project-card-spec">
                                <?php if ($bedrooms_icon_id) : ?>
                                    <span class="marcan-home-project-card-spec-icon"><?php echo wp_get_attachment_image($bedrooms_icon_id, 'full', false, array('alt' => '')); ?></span>
                                <?php endif; ?>
                                <span><?php echo esc_html($bedrooms); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ($area !== '') : ?>
                            <div class="marcan-home-project-card-spec">
                                <?php if ($area_icon_id) : ?>
                                    <span class="marcan-home-project-card-spec-icon"><?php echo wp_get_attachment_image($area_icon_id, 'full', false, array('alt' => '')); ?></span>
                                <?php endif; ?>
                                <span><?php echo esc_html($area); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </a>
    </article>
    <?php
}
?>

<section class="marcan-home-projects" aria-label="<?php esc_attr_e('Proyectos destacados', 'marcan'); ?>">
    <div class="marcan-home-projects-intro">
        <div class="marcan-home-projects-intro-copy">
            <h2><?php echo esc_html($project_settings['intro_title']); ?></h2>
            <p><?php echo esc_html($project_settings['intro_copy']); ?></p>
        </div>
        <h2 class="marcan-home-projects-intro-title"><?php echo esc_html($project_settings['intro_title']); ?></h2>
        <a class="marcan-home-projects-intro-button" href="<?php echo esc_url($project_settings['intro_button_url']); ?>">
            <?php echo esc_html($project_settings['intro_button_label']); ?>
        </a>
    </div>

    <div class="marcan-home-projects-group">
        <div class="marcan-home-projects-heading">
            <h2><?php echo esc_html($project_settings['departments_title']); ?></h2>
            <a class="marcan-home-projects-heading-button" href="<?php echo esc_url($project_settings['departments_button_url']); ?>">
                <?php echo esc_html($project_settings['departments_button_label']); ?>
            </a>
        </div>
        <div class="marcan-home-project-slider" data-project-slider data-project-section="departamentos">
            <div class="marcan-home-project-slider-track">
                <?php if ($departments_query->have_posts()) : ?>
                    <?php while ($departments_query->have_posts()) : ?>
                        <?php $departments_query->the_post(); ?>
                        <?php marcan_render_home_project_card(get_post(), 'is-department'); ?>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="marcan-home-projects-group marcan-home-projects-group-offices">
        <div class="marcan-home-projects-heading">
            <h2><?php echo esc_html($project_settings['offices_title']); ?></h2>
            <a class="marcan-home-projects-heading-button" href="<?php echo esc_url($project_settings['offices_button_url']); ?>">
                <?php echo esc_html($project_settings['offices_button_label']); ?>
            </a>
        </div>
        <div class="marcan-home-project-slider" data-project-slider data-project-section="oficinas">
            <div class="marcan-home-project-slider-track">
                <?php if ($offices_query->have_posts()) : ?>
                    <?php while ($offices_query->have_posts()) : ?>
                        <?php $offices_query->the_post(); ?>
                        <?php marcan_render_home_project_card(get_post(), 'is-office'); ?>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
