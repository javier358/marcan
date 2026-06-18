<?php
if (!defined('ABSPATH')) {
    exit;
}

$post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
$layout = isset($args['layout']) ? (string) $args['layout'] : 'media-left';
$kind = marcan_get_property_kind($post_id);
$title = marcan_get_property_field($post_id, 'titulo_comercial', get_the_title($post_id));
$title_plain = wp_strip_all_tags($title);
$subtitle = marcan_get_property_field($post_id, 'subtitulo', marcan_get_property_field($post_id, 'ubicacion'));
$status = marcan_get_property_field($post_id, 'estado', $kind === 'oficina' ? 'Oficinas boutique' : 'En obra');
$price = marcan_get_property_field($post_id, 'precio', $kind === 'oficina' ? 'S/ 352,500' : 'S/ 846,000');
$area = marcan_get_property_area_display($post_id, '80 m²');
$bedrooms = marcan_get_property_field($post_id, 'dormitorios', '');
$bathrooms = marcan_get_property_field($post_id, 'banos', '');
$parking = marcan_get_property_field($post_id, 'estacionamientos', '');
$short = marcan_get_property_field($post_id, 'descripcion_corta', get_the_excerpt($post_id));
$listing_intro_title = marcan_get_property_field($post_id, 'listado_intro_titulo', marcan_get_property_field($post_id, 'concepto_titulo', $title));
$listing_intro_text = marcan_get_property_field($post_id, 'listado_intro_texto', $short);
$delivery_date = marcan_get_property_field($post_id, 'fecha_entrega');
$image_id = marcan_get_property_image_id($post_id, 'home_desktop_image', 'listado_hero_imagen');
$mobile_image_id = marcan_get_property_image_id($post_id, 'home_mobile_image', 'home_desktop_image');
$view_label = $kind === 'oficina' ? __('Ver oficina', 'marcan') : __('Ver departamento', 'marcan');
$brochure_value = function_exists('get_field') ? get_field('brochure', $post_id) : get_post_meta($post_id, 'brochure', true);
$brochure_url = '';
if (is_numeric($brochure_value)) {
    $brochure_url = (string) wp_get_attachment_url((int) $brochure_value);
} elseif (is_array($brochure_value) && !empty($brochure_value['url'])) {
    $brochure_url = (string) $brochure_value['url'];
} elseif (is_string($brochure_value)) {
    $brochure_url = $brochure_value;
}
if ($brochure_url === '') {
    $brochure_url = get_permalink($post_id) . '#cotizar';
}
$bedrooms_label = $bedrooms !== '' && preg_match('/[[:alpha:]]/u', $bedrooms) ? $bedrooms : trim($bedrooms . ' ' . __('dormitorios', 'marcan'));
$bathrooms_label = $bathrooms !== '' && preg_match('/[[:alpha:]]/u', $bathrooms) ? $bathrooms : trim($bathrooms . ' ' . __('banos', 'marcan'));
$parking_label = $parking !== '' && preg_match('/[[:alpha:]]/u', $parking) ? $parking : trim($parking . ' ' . __('estacionamientos', 'marcan'));
$listing_specs = array();
if ($kind === 'departamento' && $bedrooms !== '') {
    $listing_specs[] = array('class' => 'bedrooms', 'label' => $bedrooms_label, 'font_size' => marcan_get_field_font_size('dormitorios', $post_id));
}
if ($area !== '') {
    $listing_specs[] = array('class' => 'area', 'label' => $area, 'font_size' => marcan_get_field_font_size('area', $post_id));
}
if ($bathrooms !== '') {
    $listing_specs[] = array('class' => 'bathrooms', 'label' => $bathrooms_label, 'font_size' => marcan_get_field_font_size('banos', $post_id));
}
if ($parking !== '') {
    $listing_specs[] = array('class' => 'parking', 'label' => $parking_label, 'font_size' => marcan_get_field_font_size('estacionamientos', $post_id));
}
if ($delivery_date !== '') {
    $listing_specs[] = array('class' => 'delivery', 'label' => $delivery_date, 'font_size' => marcan_get_field_font_size('fecha_entrega', $post_id));
}
$listing_specs = array_slice($listing_specs, 0, 5);
$status_attrs = marcan_font_size_attrs(marcan_get_field_font_size('estado', $post_id), 'marcan-property-badge');
$title_attrs = marcan_font_size_attrs(marcan_get_field_font_size('titulo_comercial', $post_id));
$subtitle_attrs = marcan_font_size_attrs(marcan_get_field_font_size('subtitulo', $post_id, 'ubicacion'));
$price_attrs = marcan_font_size_attrs(marcan_get_field_font_size('precio', $post_id));
$listing_intro_title_attrs = marcan_font_size_attrs(marcan_get_field_font_size('listado_intro_titulo', $post_id, 'concepto_titulo'));
$listing_intro_text_attrs = marcan_font_size_attrs(marcan_get_field_font_size('listado_intro_texto', $post_id, 'descripcion_corta'), '', true);
?>

<article class="marcan-property-listing-card <?php echo esc_attr($layout === 'info-left' ? 'is-info-left' : 'is-media-left'); ?>">
    <a class="marcan-property-listing-media" href="<?php echo esc_url(get_permalink($post_id)); ?>">
        <?php if ($image_id) : ?>
            <picture>
                <?php if ($mobile_image_id) : ?>
                    <source media="(max-width: 1280px)" srcset="<?php echo esc_url(wp_get_attachment_image_url($mobile_image_id, 'full')); ?>">
                <?php endif; ?>
                <?php echo wp_get_attachment_image($image_id, 'full', false, array('class' => 'marcan-property-listing-image', 'alt' => esc_attr($title_plain))); ?>
            </picture>
        <?php endif; ?>
    </a>
    <div class="marcan-property-listing-info">
        <span<?php echo $status_attrs; ?>><?php echo marcan_rich_inline($status); ?></span>
        <div class="marcan-property-listing-title">
            <h2<?php echo $title_attrs; ?>><?php echo marcan_rich_inline($title); ?></h2>
            <?php if ($subtitle !== '') : ?>
                <p<?php echo $subtitle_attrs; ?>><?php echo marcan_rich_inline($subtitle); ?></p>
            <?php endif; ?>
        </div>
        <div class="marcan-property-listing-price">
            <span><?php esc_html_e('Desde:', 'marcan'); ?></span>
            <strong<?php echo $price_attrs; ?>><?php echo esc_html($price); ?></strong>
        </div>
        <?php if (!empty($listing_specs)) : ?>
            <div class="marcan-property-listing-specs">
                <?php foreach ($listing_specs as $spec) : ?>
                    <span<?php echo marcan_font_size_attrs($spec['font_size'] ?? array(), 'marcan-property-listing-spec marcan-property-listing-spec-' . $spec['class']); ?>><?php echo marcan_rich_inline($spec['label']); ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="marcan-property-listing-copy">
            <?php if ($listing_intro_title !== '') : ?>
                <h3<?php echo $listing_intro_title_attrs; ?>><?php echo marcan_rich_inline($listing_intro_title); ?></h3>
            <?php endif; ?>
            <?php if ($listing_intro_text !== '') : ?>
                <div<?php echo $listing_intro_text_attrs; ?>><?php echo marcan_rich_block($listing_intro_text); ?></div>
            <?php endif; ?>
        </div>
        <div class="marcan-property-listing-actions">
            <a class="marcan-button-dark marcan-button-icon marcan-button-icon-arrow" href="<?php echo esc_url(get_permalink($post_id)); ?>"><?php echo esc_html($view_label); ?></a>
            <a class="marcan-button-line marcan-button-icon marcan-button-icon-download" href="<?php echo esc_url($brochure_url); ?>"><?php esc_html_e('Descargar brochure', 'marcan'); ?></a>
        </div>
    </div>
</article>
