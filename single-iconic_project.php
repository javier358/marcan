<?php
/**
 * Single iconic project template.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

while (have_posts()) :
    the_post();

    $post_id = get_the_ID();
    $title = get_the_title();
    $district = marcan_get_iconic_project_field($post_id, 'iconic_district');
    $address = marcan_get_iconic_project_field($post_id, 'iconic_address');
    $year = marcan_get_iconic_project_field($post_id, 'iconic_year');
    $status = marcan_get_iconic_project_field($post_id, 'iconic_status', __('Entregado', 'marcan'));
    $summary = marcan_get_iconic_project_field($post_id, 'iconic_summary', get_the_excerpt());
    $hero_rows = function_exists('get_field') ? get_field('iconic_hero_imagenes', $post_id) : array();
    $hero_rows = is_array($hero_rows) ? $hero_rows : array();
    $hero_id = marcan_hero_primary_image_id($hero_rows);
    $hero_picture = marcan_render_hero_picture($hero_rows, '', array(
        'img_class' => 'marcan-iconic-hero-image',
        'eager' => true,
    ));
    $detail_image_id = marcan_get_iconic_project_image_id($post_id, 'iconic_detail_image');
    $concept_title = marcan_get_iconic_project_field($post_id, 'iconic_concept_title', __('Concepto', 'marcan'));
    $concept_text = marcan_get_iconic_project_field($post_id, 'iconic_concept_text');
    $designer_photo_id = marcan_get_iconic_project_image_id($post_id, 'iconic_designer_photo');
    $designer_name = marcan_get_iconic_project_field($post_id, 'iconic_designer_name');
    $designer_name_plain = wp_strip_all_tags($designer_name);
    $designer_role = marcan_get_iconic_project_field($post_id, 'iconic_designer_role');
    $facade_image_id = marcan_get_iconic_project_image_id($post_id, 'iconic_facade_image');
    $facade_title = marcan_get_iconic_project_field($post_id, 'iconic_facade_title');
    $facade_text = marcan_get_iconic_project_field($post_id, 'iconic_facade_text');
    $gallery_ids = marcan_get_iconic_project_gallery($post_id);
    $details_title = marcan_get_iconic_project_field($post_id, 'iconic_details_title', __('Detalles que marcan', 'marcan'));
    $details_text = marcan_get_iconic_project_field($post_id, 'iconic_details_text');
    $cta_title = marcan_get_iconic_project_field($post_id, 'iconic_cta_title', __('Creamos proyectos que marcan', 'marcan'));
    $status_attrs = marcan_font_size_attrs(marcan_get_field_font_size('iconic_status', $post_id), 'marcan-iconic-pill');
    $district_attrs = marcan_font_size_attrs(marcan_get_field_font_size('iconic_district', $post_id, 'iconic_address'));
    $summary_attrs = marcan_font_size_attrs(marcan_get_field_font_size('iconic_summary', $post_id), 'marcan-iconic-summary', true);
    $concept_title_attrs = marcan_font_size_attrs(marcan_get_field_font_size('iconic_concept_title', $post_id));
    $concept_text_attrs = marcan_font_size_attrs(marcan_get_field_font_size('iconic_concept_text', $post_id), '', true);
    $designer_name_attrs = marcan_font_size_attrs(marcan_get_field_font_size('iconic_designer_name', $post_id), 'marcan-iconic-designer-name');
    $designer_role_attrs = marcan_font_size_attrs(marcan_get_field_font_size('iconic_designer_role', $post_id), 'marcan-iconic-designer-role');
    $facade_title_attrs = marcan_font_size_attrs(marcan_get_field_font_size('iconic_facade_title', $post_id));
    $facade_text_attrs = marcan_font_size_attrs(marcan_get_field_font_size('iconic_facade_text', $post_id), '', true);
    $details_title_attrs = marcan_font_size_attrs(marcan_get_field_font_size('iconic_details_title', $post_id));
    $details_text_attrs = marcan_font_size_attrs(marcan_get_field_font_size('iconic_details_text', $post_id), '', true);
    $cta_title_attrs = marcan_font_size_attrs(marcan_get_field_font_size('iconic_cta_title', $post_id));
    $related_projects = marcan_get_iconic_projects($post_id);
    $about_settings = function_exists('marcan_get_about_settings') ? marcan_get_about_settings() : array();
    $timeline_arrow = isset($about_settings['timeline_arrow']) ? (string) $about_settings['timeline_arrow'] : '';
    $departments_url = home_url('/departamentos/');
    $offices_url = home_url('/oficinas/');
    ?>
    <main class="marcan-iconic-single">
        <?php if ($hero_picture !== '') : ?>
            <section class="marcan-iconic-hero">
                <?php echo $hero_picture; ?>
            </section>
        <?php elseif ($hero_id > 0) : ?>
            <section class="marcan-iconic-hero">
                <picture>
                    <?php echo wp_get_attachment_image($hero_id, 'full', false, array('class' => 'marcan-iconic-hero-image', 'alt' => '')); ?>
                </picture>
            </section>
        <?php endif; ?>

        <section class="marcan-iconic-intro">
            <div class="marcan-iconic-title-block">
                <?php if ($status !== '') : ?>
                    <span<?php echo $status_attrs; ?>><?php echo marcan_rich_inline($status); ?></span>
                <?php endif; ?>
                <h1><?php echo marcan_rich_inline($title); ?></h1>
                <p<?php echo $district_attrs; ?>><?php echo marcan_rich_inline($district !== '' ? $district : $address); ?></p>
            </div>
            <?php if ($summary !== '') : ?>
                <div<?php echo $summary_attrs; ?>><?php echo marcan_rich_block($summary); ?></div>
            <?php endif; ?>
        </section>

        <?php if ($detail_image_id > 0) : ?>
            <section class="marcan-iconic-wide-image">
                <?php echo wp_get_attachment_image($detail_image_id, 'full', false, array('alt' => '')); ?>
            </section>
        <?php endif; ?>

        <?php if ($concept_text !== '' || $designer_name !== '') : ?>
            <section class="marcan-iconic-concept">
                <?php if ($concept_text !== '') : ?>
                    <div class="marcan-iconic-copy">
                        <h2<?php echo $concept_title_attrs; ?>><?php echo marcan_rich_inline($concept_title); ?></h2>
                        <div<?php echo $concept_text_attrs; ?>><?php echo marcan_rich_block($concept_text); ?></div>
                    </div>
                <?php endif; ?>

                <?php if ($designer_name !== '' || $designer_photo_id > 0) : ?>
                    <aside class="marcan-iconic-designer">
                        <?php if ($designer_photo_id > 0) : ?>
                            <?php echo wp_get_attachment_image($designer_photo_id, 'medium_large', false, array('alt' => esc_attr($designer_name_plain))); ?>
                        <?php endif; ?>
                        <?php if ($designer_name !== '') : ?>
                            <p<?php echo $designer_name_attrs; ?>><?php echo marcan_rich_inline($designer_name); ?></p>
                        <?php endif; ?>
                        <?php if ($designer_role !== '') : ?>
                            <p<?php echo $designer_role_attrs; ?>><?php echo marcan_rich_inline($designer_role); ?></p>
                        <?php endif; ?>
                    </aside>
                <?php endif; ?>
            </section>
        <?php endif; ?>

        <?php if ($facade_image_id > 0 || $facade_text !== '') : ?>
            <section class="marcan-iconic-facade">
                <?php if ($facade_image_id > 0) : ?>
                    <?php echo wp_get_attachment_image($facade_image_id, 'full', false, array('alt' => '')); ?>
                <?php endif; ?>
                <?php if ($facade_text !== '') : ?>
                    <div class="marcan-iconic-facade-card">
                        <h2<?php echo $facade_title_attrs; ?>><?php echo marcan_rich_inline($facade_title !== '' ? $facade_title : __('La fachada', 'marcan')); ?></h2>
                        <div<?php echo $facade_text_attrs; ?>><?php echo marcan_rich_block($facade_text); ?></div>
                    </div>
                <?php endif; ?>
            </section>
        <?php endif; ?>

        <?php if (!empty($gallery_ids)) : ?>
            <?php
            $gallery_items = array();
            foreach ($gallery_ids as $image_id) {
                $image_id = is_array($image_id) && !empty($image_id['ID']) ? (int) $image_id['ID'] : (int) $image_id;
                if (!$image_id) continue;
                $caption = trim((string) wp_get_attachment_caption($image_id));
                if ($caption === '') {
                    $raw_title = trim((string) get_the_title($image_id));
                    if (strpos($raw_title, '_') !== false) {
                        $raw_title = substr($raw_title, strpos($raw_title, '_') + 1);
                    }
                    $caption = ucfirst(trim(str_replace(array('_', '-'), ' ', $raw_title)));
                }
                if ($caption === '') $caption = sprintf(__('Imagen %d', 'marcan'), count($gallery_items) + 1);
                $gallery_items[] = array('id' => $image_id, 'caption' => $caption);
            }
            ?>
            <section class="marcan-iconic-gallery" data-property-gallery aria-label="<?php esc_attr_e('Galeria del proyecto', 'marcan'); ?>">
                <div class="marcan-iconic-gallery-track" data-gallery-track>
                    <?php foreach ($gallery_items as $item_index => $gallery_item) : ?>
                        <figure data-gallery-item="<?php echo esc_attr($item_index); ?>">
                            <button type="button" class="marcan-iconic-gallery-button marcan-property-gallery-image-button" data-gallery-image="<?php echo esc_url(wp_get_attachment_image_url($gallery_item['id'], 'full')); ?>" data-gallery-title="<?php echo esc_attr($gallery_item['caption']); ?>">
                                <?php echo wp_get_attachment_image($gallery_item['id'], 'full', false, array('alt' => esc_attr($gallery_item['caption']))); ?>
                                <span aria-hidden="true"></span>
                            </button>
                        </figure>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <?php if ($details_text !== '') : ?>
            <section class="marcan-iconic-details">
                <h2<?php echo $details_title_attrs; ?>><?php echo marcan_rich_inline($details_title); ?></h2>
                <div<?php echo $details_text_attrs; ?>><?php echo marcan_rich_block($details_text); ?></div>
            </section>
        <?php endif; ?>

        <?php if (!empty($related_projects)) : ?>
            <section class="marcan-iconic-related">
                <div class="marcan-about-section-inner marcan-about-iconic-heading">
                    <h2 class="marcan-about-section-title marcan-about-section-title--iconic"><?php esc_html_e('Conoce otros proyectos icónicos', 'marcan'); ?></h2>
                    <div class="marcan-about-iconic-actions">
                        <button class="marcan-about-slider-button" type="button" data-about-scroll="prev" aria-label="<?php esc_attr_e('Anterior', 'marcan'); ?>">
                            <span aria-hidden="true">&larr;</span>
                        </button>
                        <button class="marcan-about-slider-button" type="button" data-about-scroll="next" aria-label="<?php esc_attr_e('Siguiente', 'marcan'); ?>">
                            <span aria-hidden="true">&rarr;</span>
                        </button>
                    </div>
                </div>

                <div class="marcan-about-timeline" data-about-slider="timeline">
                    <div class="marcan-about-timeline-track">
                        <?php foreach ($related_projects as $index => $related_project) : ?>
                            <?php
                            $related_id = (int) $related_project->ID;
                            $related_hero_rows = function_exists('get_field') ? get_field('iconic_hero_imagenes', $related_id) : array();
                            $related_image_id = marcan_hero_primary_image_id(is_array($related_hero_rows) ? $related_hero_rows : array());
                            $related_canson_id = marcan_get_iconic_project_image_id($related_id, 'iconic_lineal_image');
                            $related_district = marcan_get_iconic_project_field($related_id, 'iconic_district');
                            $related_year = marcan_get_iconic_project_field($related_id, 'iconic_year');
                            ?>
                            <article class="marcan-about-timeline-item<?php echo $index === 0 ? ' is-active' : ''; ?>">
                                <a class="marcan-about-timeline-card<?php echo $related_canson_id > 0 ? ' has-canson' : ''; ?>" href="<?php echo esc_url(get_permalink($related_id)); ?>">
                                    <?php if ($related_image_id > 0) : ?>
                                        <?php echo wp_get_attachment_image($related_image_id, 'full', false, array('class' => 'marcan-about-timeline-card-bg marcan-about-timeline-card-bg-desktop', 'alt' => '')); ?>
                                    <?php endif; ?>
                                    <?php if ($related_canson_id > 0) : ?>
                                        <?php echo wp_get_attachment_image($related_canson_id, 'full', false, array('class' => 'marcan-about-timeline-card-bg marcan-about-timeline-card-bg-canson', 'alt' => '')); ?>
                                    <?php endif; ?>
                                    <?php if ($timeline_arrow !== '') : ?>
                                        <span class="marcan-about-timeline-card-arrow" aria-hidden="true">
                                            <img src="<?php echo esc_url($timeline_arrow); ?>" alt="">
                                        </span>
                                    <?php endif; ?>
                                </a>
                                <div class="marcan-about-timeline-info">
                                    <div class="marcan-about-timeline-info-title"><?php echo marcan_rich_inline(get_the_title($related_id)); ?></div>
                                    <div<?php echo marcan_font_size_attrs(marcan_get_field_font_size('iconic_district', $related_id), 'marcan-about-timeline-info-district'); ?>><?php echo marcan_rich_inline($related_district); ?></div>
                                    <div<?php echo marcan_font_size_attrs(marcan_get_field_font_size('iconic_year', $related_id), 'marcan-about-timeline-info-year'); ?>><?php echo marcan_rich_inline($related_year); ?></div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <section class="marcan-iconic-cta">
            <h2<?php echo $cta_title_attrs; ?>><?php echo marcan_rich_inline($cta_title); ?></h2>
            <div>
                <a class="marcan-iconic-cta-button is-yellow" href="<?php echo esc_url($departments_url); ?>"><?php esc_html_e('Departamentos en venta', 'marcan'); ?></a>
                <a class="marcan-iconic-cta-button is-white" href="<?php echo esc_url($offices_url); ?>"><?php esc_html_e('Oficinas en venta', 'marcan'); ?></a>
            </div>
        </section>
    </main>
    <?php
endwhile;

get_footer();
