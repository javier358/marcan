<?php
if (!defined('ABSPATH')) {
    exit;
}

$about = marcan_get_about_settings();
$has_iconic_projects = !empty($about['iconic_projects']);
?>

<?php if ($has_iconic_projects) : ?>
    <section class="marcan-about-iconic">
        <div class="marcan-about-section-inner marcan-about-iconic-heading">
            <?php if (!empty($about['iconic_title'])) : ?>
                <h2<?php echo marcan_font_size_attrs($about['iconic_title_font_size'] ?? array(), 'marcan-about-section-title marcan-about-section-title--iconic'); ?>><?php echo marcan_rich_inline($about['iconic_title']); ?></h2>
            <?php endif; ?>
            <?php if (count($about['iconic_projects']) > 1) : ?>
                <div class="marcan-about-iconic-actions">
                    <button class="marcan-about-slider-button" type="button" data-about-scroll="prev" aria-label="Anterior">
                        <span aria-hidden="true">&larr;</span>
                    </button>
                    <button class="marcan-about-slider-button" type="button" data-about-scroll="next" aria-label="Siguiente">
                        <span aria-hidden="true">&rarr;</span>
                    </button>
                </div>
            <?php endif; ?>
        </div>

        <div class="marcan-about-timeline" data-about-slider="timeline">
            <div class="marcan-about-timeline-track">
                <?php foreach ($about['iconic_projects'] as $index => $project) : ?>
                    <?php
                    $active = $index === 0;
                    $desktop_image = !empty($project['image_desktop']) ? $project['image_desktop'] : '';
                    $mobile_image = !empty($project['image_mobile']) ? $project['image_mobile'] : $desktop_image;
                    $canson_image = !empty($project['image_canson']) ? $project['image_canson'] : '';
                    $project_name = (string) ($project['name'] ?? '');
                    $project_url = function_exists('marcan_get_iconic_project_permalink_by_name') ? marcan_get_iconic_project_permalink_by_name($project_name) : '';
                    $card_tag = $project_url !== '' ? 'a' : 'div';
                    $has_info = $project_name !== '' || !empty($project['district']) || !empty($project['year']);
                    ?>
                    <article class="marcan-about-timeline-item<?php echo $active ? ' is-active' : ''; ?>">
                        <<?php echo esc_html($card_tag); ?> class="marcan-about-timeline-card<?php echo $canson_image !== '' ? ' has-canson' : ''; ?>"<?php echo $project_url !== '' ? ' href="' . esc_url($project_url) . '"' : ''; ?>>
                            <?php if ($desktop_image !== '') : ?>
                                <img class="marcan-about-timeline-card-bg marcan-about-timeline-card-bg-desktop" src="<?php echo esc_url(marcan_about_resolve_image($desktop_image)); ?>" alt="">
                            <?php endif; ?>
                            <?php if ($mobile_image !== '') : ?>
                                <img class="marcan-about-timeline-card-bg marcan-about-timeline-card-bg-mobile" src="<?php echo esc_url(marcan_about_resolve_image($mobile_image)); ?>" alt="">
                            <?php endif; ?>
                            <?php if ($canson_image !== '') : ?>
                                <img class="marcan-about-timeline-card-bg marcan-about-timeline-card-bg-canson" src="<?php echo esc_url(marcan_about_resolve_image($canson_image)); ?>" alt="">
                            <?php endif; ?>
                            <?php if (!empty($about['timeline_arrow'])) : ?>
                                <span class="marcan-about-timeline-card-arrow" aria-hidden="true">
                                    <img src="<?php echo esc_url($about['timeline_arrow']); ?>" alt="">
                                </span>
                            <?php endif; ?>
                        </<?php echo esc_html($card_tag); ?>>
                        <?php if ($has_info) : ?>
                            <div class="marcan-about-timeline-info">
                                <?php if ($project_name !== '') : ?>
                                    <div<?php echo marcan_font_size_attrs(marcan_get_row_font_size($project, 'name'), 'marcan-about-timeline-info-title'); ?>><?php echo marcan_rich_inline($project_name); ?></div>
                                <?php endif; ?>
                                <?php if (!empty($project['district'])) : ?>
                                    <div<?php echo marcan_font_size_attrs(marcan_get_row_font_size($project, 'district'), 'marcan-about-timeline-info-district'); ?>><?php echo marcan_rich_inline($project['district']); ?></div>
                                <?php endif; ?>
                                <?php if (!empty($project['year'])) : ?>
                                    <div<?php echo marcan_font_size_attrs(marcan_get_row_font_size($project, 'year'), 'marcan-about-timeline-info-year'); ?>><?php echo marcan_rich_inline($project['year']); ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>
