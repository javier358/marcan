<?php
if (!defined('ABSPATH')) {
    exit;
}

$kind = isset($args['kind']) ? (string) $args['kind'] : 'departamento';
$is_office = $kind === 'oficina';
$query = marcan_get_properties_by_kind($kind);
$first_post = $query->have_posts() ? $query->posts[0] : null;

$page_id = get_queried_object_id();
$get_listing_field = static function (string $field) use ($page_id): string {
    if ($page_id && function_exists('get_field')) {
        $value = get_field($field, $page_id);
        if (is_scalar($value) && (string) $value !== '') {
            return (string) $value;
        }
    }

    return '';
};
$get_listing_font_size = static function (string $field) use ($page_id): array {
    return $page_id ? marcan_get_field_font_size($field, $page_id) : array();
};

$title = $get_listing_field('listing_title');
$intro = $get_listing_field('listing_intro');

$hero_rows = array();
if ($page_id && function_exists('get_field')) {
    $rows = get_field('listing_hero_imagenes', $page_id);
    $hero_rows = is_array($rows) ? $rows : array();
}
$hero_picture = marcan_render_hero_picture($hero_rows, '', array(
    'img_class' => 'marcan-property-archive-hero-image',
    'eager' => true,
));

$hero_image_id = 0;
$hero_image_url = '';
if ($hero_picture === '') {
    if ($first_post) {
        $hero_image_id = marcan_get_property_image_id((int) $first_post->ID, 'listado_hero_imagen', 'home_desktop_image');
    }
    if (!$hero_image_id && !$is_office) {
        $hero_image_url = get_theme_file_uri('assets/images/marcan-departamentos-hero-figma.png');
    }
}
?>

<main class="marcan-property-archive marcan-property-archive-<?php echo esc_attr($kind); ?>">
    <section class="marcan-property-archive-hero">
        <div class="marcan-property-archive-hero-media">
            <?php if ($hero_picture !== '') : ?>
                <?php echo $hero_picture; ?>
            <?php elseif ($hero_image_url !== '') : ?>
                <img src="<?php echo esc_url($hero_image_url); ?>" alt="" class="marcan-property-archive-hero-image">
            <?php elseif ($hero_image_id) : ?>
                <?php echo wp_get_attachment_image($hero_image_id, 'full', false, array('alt' => '', 'class' => 'marcan-property-archive-hero-image')); ?>
            <?php endif; ?>
        </div>
        <div class="marcan-property-archive-hero-content">
            <?php if ($title !== '' || $intro !== '') : ?>
                <div class="marcan-property-archive-copy">
                    <?php if ($title !== '') : ?>
                        <h1<?php echo marcan_font_size_attrs($get_listing_font_size('listing_title')); ?>><?php echo marcan_rich_inline($title); ?></h1>
                    <?php endif; ?>
                    <?php if ($intro !== '') : ?>
                        <div<?php echo marcan_font_size_attrs($get_listing_font_size('listing_intro'), '', true); ?>><?php echo marcan_rich_block($intro); ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if ($is_office) : ?>
                <?php
                $reasons_title = $get_listing_field('listing_reasons_title');
                $reasons = ($page_id && function_exists('get_field')) ? get_field('listing_reasons', $page_id) : array();
                $reasons = is_array($reasons) ? array_filter($reasons, static function ($reason): bool {
                    return trim((string) ($reason['number'] ?? '')) !== '' || trim((string) ($reason['text'] ?? '')) !== '';
                }) : array();
                ?>
                <?php if ($reasons_title !== '' || !empty($reasons)) : ?>
                    <div class="marcan-property-archive-reasons">
                        <?php if ($reasons_title !== '') : ?>
                            <h2<?php echo marcan_font_size_attrs($get_listing_font_size('listing_reasons_title')); ?>><?php echo marcan_rich_inline($reasons_title); ?></h2>
                        <?php endif; ?>
                        <?php if (!empty($reasons)) : ?>
                            <ol>
                                <?php foreach ($reasons as $reason) : ?>
                                    <li><span<?php echo marcan_font_size_attrs(marcan_get_row_font_size($reason, 'number')); ?>><?php echo marcan_rich_inline((string) ($reason['number'] ?? '')); ?></span><div<?php echo marcan_font_size_attrs(marcan_get_row_font_size($reason, 'text'), '', true); ?>><?php echo marcan_rich_block((string) ($reason['text'] ?? '')); ?></div></li>
                                <?php endforeach; ?>
                            </ol>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>

    <section class="marcan-property-archive-list">
        <?php if ($query->have_posts()) : ?>
            <?php $index = 0; ?>
            <?php while ($query->have_posts()) : ?>
                <?php
                $query->the_post();
                $layout = $is_office ? 'info-left' : 'media-left';
                if (!$is_office && $index % 2 === 1) {
                    $layout = 'media-left';
                }
                get_template_part('template-parts/properties/property-card-listing', null, array('post_id' => get_the_ID(), 'layout' => $layout));
                $index++;
                ?>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        <?php endif; ?>
    </section>
</main>
