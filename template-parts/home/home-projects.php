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
$departments_slider_class = $departments_query->post_count === 1 ? ' has-one-card' : ($departments_query->post_count === 2 ? ' has-two-cards' : '');
$offices_slider_class = $offices_query->post_count === 1 ? ' has-one-card' : ($offices_query->post_count === 2 ? ' has-two-cards' : '');

function marcan_render_home_project_card(WP_Post $post, string $section_class = '', array $card_font_sizes = array()): void
{
    $post_id = (int) $post->ID;
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
    $image_style = '--project-image-left:0%;--project-image-top:0%;--project-image-width:100%;--project-image-height:100%;--project-image-fit:cover;';
    $arrow_id = (int) get_option('marcan_project_icon_arrow_id');
    $divider_id = (int) get_option('marcan_project_icon_divider_id');
    $bedrooms_icon_id = (int) get_option('marcan_project_icon_bedrooms_id');
    $area_icon_id = (int) get_option('marcan_project_icon_area_id');
    $title = trim((string) get_field('home_title', $post_id));
    $title = $title !== '' ? $title : get_the_title($post_id);
    $title_plain = wp_strip_all_tags($title);
    $default_price_label = $price_label !== '' ? $price_label : marcan_get_option_text('ui_card_price_label', '');
    $default_cta_label = $cta_label !== '' ? $cta_label : marcan_get_option_text('ui_card_cta_more', '');
    $title_attrs = marcan_font_size_attrs(marcan_resolve_context_font_size($card_font_sizes['title'] ?? array(), 'titulo_comercial', $post_id));
    $badge_attrs = marcan_font_size_attrs(marcan_resolve_context_font_size($card_font_sizes['badge'] ?? array(), 'estado', $post_id), 'marcan-home-project-card-badge');
    $location_attrs = marcan_font_size_attrs(marcan_resolve_context_font_size($card_font_sizes['location'] ?? array(), 'ubicacion', $post_id), 'marcan-home-project-card-location');
    $price_label_attrs = marcan_font_size_attrs(marcan_resolve_context_font_size($card_font_sizes['price_label'] ?? array(), 'home_price_label', $post_id));
    $price_attrs = marcan_font_size_attrs(marcan_resolve_context_font_size($card_font_sizes['price'] ?? array(), 'precio', $post_id));
    $specs_attrs = marcan_font_size_attrs(marcan_resolve_context_font_size($card_font_sizes['specs'] ?? array(), 'dormitorios', $post_id));
    $cta_attrs = marcan_font_size_attrs(marcan_resolve_context_font_size($card_font_sizes['cta'] ?? array(), 'home_cta_label', $post_id), 'marcan-home-project-card-cta');
    ?>
    <article class="marcan-home-project-card <?php echo esc_attr($section_class); ?>">
        <a class="marcan-home-project-card-link" href="<?php echo esc_url($cta_link && is_array($cta_link) && !empty($cta_link['url']) ? $cta_link['url'] : get_permalink($post_id)); ?>" target="<?php echo esc_attr($cta_link && is_array($cta_link) && !empty($cta_link['target']) ? $cta_link['target'] : '_self'); ?>">
            <div class="marcan-home-project-card-media" style="<?php echo esc_attr($image_style); ?>">
                <picture>
                    <?php if ($mobile_image_url !== '') : ?>
                        <source media="(max-width: 900px)" srcset="<?php echo esc_url($mobile_image_url); ?>">
                    <?php endif; ?>
                    <?php if ($desktop_image) : ?>
                        <img class="marcan-home-project-card-image" src="<?php echo esc_url($desktop_image[0]); ?>" width="<?php echo esc_attr((string) $desktop_image[1]); ?>" height="<?php echo esc_attr((string) $desktop_image[2]); ?>" alt="<?php echo esc_attr($title_plain); ?>" loading="eager" decoding="async">
                    <?php elseif ($mobile_image_url !== '') : ?>
                        <img class="marcan-home-project-card-image" src="<?php echo esc_url($mobile_image_url); ?>" alt="<?php echo esc_attr($title_plain); ?>" loading="eager" decoding="async">
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
                    <div class="marcan-home-project-card-actions">
                        <?php if ($default_cta_label !== '') : ?><span<?php echo $cta_attrs; ?>><?php echo marcan_rich_inline($default_cta_label); ?></span><?php endif; ?>
                    </div>
                </div>
                <div class="marcan-home-project-card-side">
                    <div class="marcan-home-project-card-price">
                        <?php if ($default_price_label !== '') : ?><span<?php echo $price_label_attrs; ?>><?php echo marcan_rich_inline($default_price_label); ?></span><?php endif; ?>
                        <?php if ($price !== '') : ?>
                            <strong<?php echo $price_attrs; ?>><?php echo esc_html($price); ?></strong>
                        <?php endif; ?>
                    </div>
                    <div class="marcan-home-project-card-specs">
                        <?php if ($bedrooms !== '') : ?>
                            <div class="marcan-home-project-card-spec">
                                <?php if ($bedrooms_icon_id) : ?>
                                    <span class="marcan-home-project-card-spec-icon"><?php echo wp_get_attachment_image($bedrooms_icon_id, 'full', false, array('alt' => '')); ?></span>
                                <?php endif; ?>
                                <span<?php echo $specs_attrs; ?>><?php echo marcan_rich_inline($bedrooms); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ($area !== '') : ?>
                            <div class="marcan-home-project-card-spec">
                                <?php if ($area_icon_id) : ?>
                                    <span class="marcan-home-project-card-spec-icon"><?php echo wp_get_attachment_image($area_icon_id, 'full', false, array('alt' => '')); ?></span>
                                <?php endif; ?>
                                <span<?php echo $specs_attrs; ?>><?php echo esc_html($area); ?></span>
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

<?php if ($departments_query->have_posts() || $offices_query->have_posts()) : ?>
<section class="marcan-home-projects" aria-label="<?php esc_attr_e('Proyectos destacados', 'marcan'); ?>">
    <?php $has_intro = !empty($project_settings['intro_title']) || !empty($project_settings['intro_copy']) || (!empty($project_settings['intro_button_url']) && !empty($project_settings['intro_button_label'])); ?>
    <?php if ($has_intro) : ?>
        <div class="marcan-home-projects-intro">
            <?php if (!empty($project_settings['intro_title']) || !empty($project_settings['intro_copy'])) : ?>
                <div class="marcan-home-projects-intro-copy">
                    <?php if (!empty($project_settings['intro_title'])) : ?>
                        <h2<?php echo marcan_font_size_attrs($project_settings['intro_title_font_size'] ?? array()); ?>><?php echo marcan_rich_inline($project_settings['intro_title']); ?></h2>
                    <?php endif; ?>
                    <?php if (!empty($project_settings['intro_copy'])) : ?>
                        <div<?php echo marcan_font_size_attrs($project_settings['intro_copy_font_size'] ?? array(), '', true); ?>><?php echo marcan_rich_block($project_settings['intro_copy']); ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($project_settings['intro_title'])) : ?>
                <h2 class="marcan-home-projects-intro-title">
                    <span<?php echo marcan_font_size_attrs($project_settings['intro_title_font_size'] ?? array()); ?>><?php echo marcan_rich_inline($project_settings['intro_title']); ?></span>
                </h2>
            <?php endif; ?>
            <?php if (!empty($project_settings['intro_button_url']) && !empty($project_settings['intro_button_label'])) : ?>
                <a class="marcan-home-projects-intro-button" href="<?php echo esc_url($project_settings['intro_button_url']); ?>">
                    <span<?php echo marcan_font_size_attrs($project_settings['intro_button_label_font_size'] ?? array()); ?>><?php echo marcan_rich_inline($project_settings['intro_button_label']); ?></span>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($departments_query->have_posts()) : ?>
    <div class="marcan-home-projects-group">
        <div class="marcan-home-projects-heading">
            <?php if (!empty($project_settings['departments_title'])) : ?>
                <h2>
                    <span<?php echo marcan_font_size_attrs($project_settings['departments_title_font_size'] ?? array()); ?>><?php echo marcan_rich_inline($project_settings['departments_title']); ?></span>
                </h2>
            <?php endif; ?>
            <?php if (!empty($project_settings['departments_button_url']) && !empty($project_settings['departments_button_label'])) : ?>
                <a class="marcan-home-projects-heading-button" href="<?php echo esc_url($project_settings['departments_button_url']); ?>">
                    <span<?php echo marcan_font_size_attrs($project_settings['departments_button_label_font_size'] ?? array()); ?>><?php echo marcan_rich_inline($project_settings['departments_button_label']); ?></span>
                </a>
            <?php endif; ?>
        </div>
        <div class="marcan-home-project-slider<?php echo esc_attr($departments_slider_class); ?>" data-project-slider data-project-section="departamentos">
            <div class="marcan-home-project-slider-track">
                <?php if ($departments_query->have_posts()) : ?>
                    <?php while ($departments_query->have_posts()) : ?>
                        <?php $departments_query->the_post(); ?>
                        <?php marcan_render_home_project_card(get_post(), 'is-department', $project_settings['card_font_sizes'] ?? array()); ?>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($offices_query->have_posts()) : ?>
    <div class="marcan-home-projects-group marcan-home-projects-group-offices">
        <div class="marcan-home-projects-heading">
            <?php if (!empty($project_settings['offices_title'])) : ?>
                <h2>
                    <span<?php echo marcan_font_size_attrs($project_settings['offices_title_font_size'] ?? array()); ?>><?php echo marcan_rich_inline($project_settings['offices_title']); ?></span>
                </h2>
            <?php endif; ?>
            <?php if (!empty($project_settings['offices_button_url']) && !empty($project_settings['offices_button_label'])) : ?>
                <a class="marcan-home-projects-heading-button" href="<?php echo esc_url($project_settings['offices_button_url']); ?>">
                    <span<?php echo marcan_font_size_attrs($project_settings['offices_button_label_font_size'] ?? array()); ?>><?php echo marcan_rich_inline($project_settings['offices_button_label']); ?></span>
                </a>
            <?php endif; ?>
        </div>
        <div class="marcan-home-project-slider<?php echo esc_attr($offices_slider_class); ?>" data-project-slider data-project-section="oficinas">
            <div class="marcan-home-project-slider-track">
                <?php if ($offices_query->have_posts()) : ?>
                    <?php while ($offices_query->have_posts()) : ?>
                        <?php $offices_query->the_post(); ?>
                        <?php marcan_render_home_project_card(get_post(), 'is-office', $project_settings['card_font_sizes'] ?? array()); ?>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</section>
<?php endif; ?>
