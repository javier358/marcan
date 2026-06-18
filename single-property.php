<?php
if (!defined('ABSPATH')) {
    exit;
}

get_header();

while (have_posts()) :
    the_post();
    $post_id = get_the_ID();
    $kind = marcan_get_property_kind($post_id);
    $is_office = $kind === 'oficina';
    $title = marcan_get_property_field($post_id, 'titulo_comercial', get_the_title());
    $title_plain = wp_strip_all_tags($title);
    $subtitle = marcan_get_property_field($post_id, 'subtitulo', marcan_get_property_field($post_id, 'ubicacion'));
    $status = marcan_get_property_field($post_id, 'estado', $is_office ? 'Oficinas boutique' : 'En obra');
    $price = marcan_get_property_field($post_id, 'precio', $is_office ? 'S/ 352,500' : 'S/ 846,000');
    $area = marcan_get_property_area_display($post_id, $is_office ? '25 m²' : '80 m²');
    $bedrooms = marcan_get_property_field($post_id, 'dormitorios');
    $delivery = marcan_get_property_field($post_id, 'fecha_entrega', $is_office ? 'Entrega marzo 2027' : 'Entrega agosto 2026');
    $intro = marcan_get_property_field($post_id, 'descripcion_corta', get_the_excerpt());
    $concept_title = marcan_get_property_field($post_id, 'concepto_titulo', $is_office ? '¿Por qué invertir?' : 'Concepto');
    $concept_text = marcan_get_property_field($post_id, 'concepto_texto', wp_strip_all_tags(get_the_content()));
    $designer_name = marcan_get_property_field($post_id, 'disenador_interiores_nombre');
    $designer_name_plain = wp_strip_all_tags($designer_name);
    $designer_role = marcan_get_property_field($post_id, 'disenador_interiores_cargo');
    $designer_photo_id = marcan_get_property_image_id($post_id, 'disenador_interiores_foto');
    $architecture_title = marcan_get_property_field($post_id, 'arquitectura_titulo');
    $architecture_title_plain = wp_strip_all_tags($architecture_title);
    $architecture_text = marcan_get_property_field($post_id, 'arquitectura_texto');
    $architecture_image_value = function_exists('get_field') ? get_field('arquitectura_imagen', $post_id) : get_post_meta($post_id, 'arquitectura_imagen', true);
    $architecture_image_id = is_array($architecture_image_value) ? (int) ($architecture_image_value['ID'] ?? 0) : (int) $architecture_image_value;
    $architecture_studio_name = marcan_get_property_field($post_id, 'arquitectura_estudio_nombre');
    $architecture_studio_name_plain = wp_strip_all_tags($architecture_studio_name);
    $architecture_studio_role = marcan_get_property_field($post_id, 'arquitectura_estudio_cargo');
    $architecture_studio_photo_value = function_exists('get_field') ? get_field('arquitectura_estudio_foto', $post_id) : get_post_meta($post_id, 'arquitectura_estudio_foto', true);
    $architecture_studio_photo_id = is_array($architecture_studio_photo_value) ? (int) ($architecture_studio_photo_value['ID'] ?? 0) : (int) $architecture_studio_photo_value;
    $hero_rows = function_exists('get_field') ? get_field('detalle_hero_imagenes', $post_id) : array();
    $hero_rows = is_array($hero_rows) ? $hero_rows : array();
    $hero_id = marcan_hero_primary_image_id($hero_rows);
    if (!$hero_id) {
        $hero_id = marcan_get_property_image_id($post_id, 'home_desktop_image');
    }
    $hero_picture = marcan_render_hero_picture($hero_rows, '', array(
        'img_class' => 'marcan-property-single-hero-image',
        'eager' => true,
    ));
    $hero_original_url = $hero_id ? wp_get_original_image_url($hero_id) : '';
    if ($hero_original_url === '' && $hero_id) {
        $hero_original_url = (string) wp_get_attachment_image_url($hero_id, 'full');
    }
    $has_hero_media = $hero_picture !== '' || $hero_id;
    $content_image_id = marcan_get_property_image_id($post_id, 'detalle_imagen_ancha', 'listado_hero_imagen');
    $common = function_exists('get_field') ? get_field('areas_comunes', $post_id) : array();
    $common_mobile = function_exists('get_field') ? get_field('areas_comunes_mobile', $post_id) : array();
    $internal = function_exists('get_field') ? get_field('areas_internas', $post_id) : array();
    $internal_mobile = function_exists('get_field') ? get_field('areas_internas_mobile', $post_id) : array();
    $units = function_exists('get_field') ? get_field('unidades', $post_id) : array();
    $virtual_tours_rows = function_exists('get_field') ? get_field('recorridos_virtuales', $post_id) : array();
    // Tamaño general por repeater-tabla (uno para todo el repeater, no por fila)
    $units_font_size = marcan_get_field_font_size('unidades', $post_id);
    $tours_font_size = marcan_get_field_font_size('recorridos_virtuales', $post_id);
    $nearby_font_size = marcan_get_field_font_size('lugares_cercanos', $post_id);
    $related = marcan_get_properties_by_kind($kind, 3);
    $related_count = 0;
    foreach ($related->posts as $related_post) {
        if ((int) $related_post->ID !== $post_id) {
            $related_count++;
        }
    }
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
        $brochure_url = '#brochure';
    }
    $map_card_title = $title_plain . ' | Marcan';
    $map_address = marcan_get_property_field($post_id, 'ubicacion', $subtitle);
    $map_address_plain = wp_strip_all_tags($map_address);
    $map_heading = marcan_get_property_field($post_id, 'ubicacion_titulo', __('Ubicación perfecta cerca a todo', 'marcan'));
    $map_nearby_title = marcan_get_property_field($post_id, 'lugares_cercanos_titulo', __('Lugares de interés cercanos', 'marcan'));
    $map_nearby_content = function_exists('get_field') ? get_field('lugares_cercanos', $post_id) : marcan_get_property_field($post_id, 'lugares_cercanos');
    $map_description = marcan_get_property_field($post_id, 'ubicacion_descripcion');
    $map_google_url = marcan_get_property_field($post_id, 'google_maps_url');
    $map_waze_url = marcan_get_property_field($post_id, 'waze_url');
    $related_intro = marcan_get_property_field($post_id, 'relacionados_intro_texto');
    if ($related_intro === '') {
        $related_intro = $is_office
            ? __('Revisa las oficinas que tenemos para ti', 'marcan')
            : __('Revisa las opciones que tenemos para ti', 'marcan');
    }
    $title_attrs = marcan_font_size_attrs(marcan_get_field_font_size('titulo_comercial', $post_id));
    $subtitle_attrs = marcan_font_size_attrs(marcan_get_field_font_size('subtitulo', $post_id, 'ubicacion'), 'marcan-property-sticky-subtitle');
    $status_attrs = marcan_font_size_attrs(marcan_get_field_font_size('estado', $post_id), 'marcan-property-status-pill');
    $delivery_attrs = marcan_font_size_attrs(marcan_get_field_font_size('fecha_entrega', $post_id), 'marcan-property-delivery');
    $price_attrs = marcan_font_size_attrs(marcan_get_field_font_size('precio', $post_id));
    $bedrooms_attrs = marcan_font_size_attrs(marcan_get_field_font_size('dormitorios', $post_id), 'marcan-property-price-spec marcan-property-price-spec-bedrooms');
    $intro_attrs = marcan_font_size_attrs(marcan_get_field_font_size('descripcion_corta', $post_id), 'marcan-property-intro', true);
    $concept_title_attrs = marcan_font_size_attrs(marcan_get_field_font_size('concepto_titulo', $post_id));
    $concept_text_attrs = marcan_font_size_attrs(marcan_get_field_font_size('concepto_texto', $post_id), '', true);
    $designer_name_attrs = marcan_font_size_attrs(marcan_get_field_font_size('disenador_interiores_nombre', $post_id));
    $designer_role_attrs = marcan_font_size_attrs(marcan_get_field_font_size('disenador_interiores_cargo', $post_id));
    $map_heading_attrs = marcan_font_size_attrs(marcan_get_field_font_size('ubicacion_titulo', $post_id));
    $map_nearby_title_attrs = marcan_font_size_attrs(marcan_get_field_font_size('lugares_cercanos_titulo', $post_id));
    $map_description_attrs = marcan_font_size_attrs(marcan_get_field_font_size('ubicacion_descripcion', $post_id), 'marcan-property-map-description', true);
    $quote_attrs = marcan_font_size_attrs(marcan_get_field_font_size('frase_proyecto', $post_id));
    $quote_author_attrs = marcan_font_size_attrs(marcan_get_field_font_size('autor_frase', $post_id));
    $architecture_title_attrs = marcan_font_size_attrs(marcan_get_field_font_size('arquitectura_titulo', $post_id));
    $architecture_text_attrs = marcan_font_size_attrs(marcan_get_field_font_size('arquitectura_texto', $post_id), '', true);
    $architecture_studio_name_attrs = marcan_font_size_attrs(marcan_get_field_font_size('arquitectura_estudio_nombre', $post_id));
    $architecture_studio_role_attrs = marcan_font_size_attrs(marcan_get_field_font_size('arquitectura_estudio_cargo', $post_id));
    $related_intro_attrs = marcan_font_size_attrs(marcan_get_field_font_size('relacionados_intro_texto', $post_id));
    $related_quote_label = $is_office
        ? marcan_get_option_text('ui_property_btn_quote_office', 'Cotizar oficina')
        : marcan_get_option_text('ui_property_btn_quote_project', 'Cotizar proyecto');
    $map_embed_src = '';

    if ($map_google_url === '' && $map_address_plain !== '') {
        $map_google_url = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($map_address_plain);
    }

    if ($map_waze_url === '' && $map_address_plain !== '') {
        $map_waze_url = 'https://waze.com/ul?q=' . rawurlencode($map_address_plain) . '&navigate=yes';
    }

    if ($map_google_url !== '') {
        $map_embed_src = marcan_google_maps_embed_src($map_google_url, $map_address_plain);
    }

    $virtual_tours = array();
    $resolve_tour_src = static function (string $value): string {
        $value = trim($value);
        if ($value === '') {
            return '';
        }

        if (preg_match('/<iframe[^>]+src=[\'"]([^\'"]+)[\'"]/i', $value, $matches)) {
            return esc_url_raw(html_entity_decode($matches[1]));
        }

        return esc_url_raw($value);
    };

    if (is_array($virtual_tours_rows)) {
        foreach ($virtual_tours_rows as $tour_row) {
            if (isset($tour_row['activo']) && !$tour_row['activo']) {
                continue;
            }

            $tour_title = trim((string) ($tour_row['titulo'] ?? ''));
            $tour_group = trim((string) ($tour_row['grupo'] ?? ''));
            $tour_url = $resolve_tour_src((string) ($tour_row['url'] ?? ''));
            $tour_src = $tour_url;

            if ($tour_title === '' || $tour_src === '') {
                continue;
            }

            $virtual_tours[] = array(
                'title' => $tour_title,
                'group' => $tour_group,
                'src' => $tour_src,
                'external' => $tour_url !== '' ? $tour_url : $tour_src,
            );
        }
    }

    $area_label = preg_match('/desde/i', $area) ? $area : trim(sprintf(__('Desde %s', 'marcan'), $area));
    $normalize_unit_option = static function ($value): string {
        $value = trim((string) $value);
        if ($value === '') {
            return '';
        }

        if (preg_match('/^\d+(?:[.,]\d+)?/', $value, $matches)) {
            return str_replace(',', '.', $matches[0]);
        }

        return $value;
    };
    $parse_unit_number = static function ($value): float {
        $value = trim((string) $value);
        if ($value === '') {
            return 0;
        }

        $normalized = preg_replace('/[^\d.,]/', '', $value);
        if ($normalized === null || $normalized === '') {
            return 0;
        }

        if (substr_count($normalized, ',') > 1 && strpos($normalized, '.') === false) {
            $normalized = str_replace(',', '', $normalized);
        } elseif (strpos($normalized, ',') !== false && strpos($normalized, '.') !== false) {
            $normalized = str_replace(',', '', $normalized);
        } elseif (preg_match('/,\d{3}$/', $normalized)) {
            $normalized = str_replace(',', '', $normalized);
        } else {
            $normalized = str_replace(',', '.', $normalized);
        }

        return (float) $normalized;
    };
    $unit_filter_values = array('habitaciones' => array(), 'banos' => array());
    $unit_ranges = array(
        'area_m2' => array('min' => null, 'max' => null),
        'precio' => array('min' => null, 'max' => null),
    );
    if (is_array($units)) {
        foreach ($units as $unit) {
            foreach ($unit_filter_values as $filter_key => $values) {
                $value = isset($unit[$filter_key]) ? trim((string) $unit[$filter_key]) : '';
                if ($value !== '') {
                    $option_value = $filter_key === 'habitaciones' ? $normalize_unit_option($value) : $value;
                    if ($option_value !== '') {
                        $unit_filter_values[$filter_key][$option_value] = $option_value;
                    }
                }
            }
            foreach ($unit_ranges as $range_key => $range) {
                $range_value = $parse_unit_number($unit[$range_key] ?? '');
                if ($range_value <= 0) {
                    continue;
                }
                $unit_ranges[$range_key]['min'] = $range['min'] === null ? $range_value : min($range['min'], $range_value);
                $unit_ranges[$range_key]['max'] = $range['max'] === null ? $range_value : max($range['max'], $range_value);
            }
        }
        foreach ($unit_filter_values as $filter_key => $values) {
            ksort($values, SORT_NATURAL);
            $unit_filter_values[$filter_key] = $values;
        }
    }
    $area_range_min = (int) floor((float) ($unit_ranges['area_m2']['min'] ?? 0));
    $area_range_max = (int) ceil((float) ($unit_ranges['area_m2']['max'] ?? 0));
    $price_range_min = (int) floor((float) ($unit_ranges['precio']['min'] ?? 0));
    $price_range_max = (int) ceil((float) ($unit_ranges['precio']['max'] ?? 0));
    ?>

    <main class="marcan-property-single marcan-property-single-<?php echo esc_attr($kind); ?>">
        <?php if ($has_hero_media) : ?>
        <section class="marcan-property-single-hero">
            <?php if ($hero_picture !== '') : ?>
                <?php echo $hero_picture; ?>
            <?php elseif ($hero_id) : ?>
                <picture>
                    <img class="marcan-property-single-hero-image" src="<?php echo esc_url($hero_original_url); ?>" alt="" decoding="async" fetchpriority="high">
                </picture>
            <?php endif; ?>
        </section>
        <?php endif; ?>

        <section class="marcan-property-sticky-quote" id="cotizar">
            <div>
                <h1<?php echo $title_attrs; ?>><?php echo marcan_rich_inline($title); ?></h1>
                <p class="marcan-property-sticky-meta">
                    <span<?php echo $subtitle_attrs; ?>><?php echo marcan_rich_inline($subtitle); ?></span>
                    <?php if ($status !== '') : ?>
                        <span class="marcan-property-sticky-divider" aria-hidden="true"></span>
                        <span<?php echo $status_attrs; ?>><?php echo marcan_rich_inline($status); ?></span>
                    <?php endif; ?>
                </p>
            </div>
            <div class="marcan-property-sticky-actions">
                <?php if (marcan_section_is_active($post_id, 'mostrar_brochure', array('brochure'))) : ?>
                <a class="marcan-button-line marcan-button-icon marcan-button-icon-download" href="<?php echo esc_url($brochure_url); ?>"><?php echo esc_html(marcan_get_option_text('ui_property_btn_brochure', 'Descargar brochure')); ?></a>
                <?php endif; ?>
                <a class="marcan-button-dark marcan-button-icon marcan-button-icon-arrow" href="#cotizar"><?php echo esc_html($is_office ? marcan_get_option_text('ui_property_btn_quote_office', 'Cotizar oficina') : marcan_get_option_text('ui_property_btn_quote_project', 'Cotizar proyecto')); ?></a>
            </div>
        </section>

        <section class="marcan-property-single-content">
            <div class="marcan-property-single-summary">
                <p<?php echo $delivery_attrs; ?>><?php echo marcan_rich_inline($delivery); ?></p>
                <div class="marcan-property-price-block">
                    <span><?php echo esc_html(marcan_get_option_text('ui_card_price_label', 'Desde:')); ?></span>
                    <strong<?php echo $price_attrs; ?>><?php echo esc_html($price); ?></strong>
                    <?php if (!$is_office && $bedrooms !== '') : ?>
                        <small<?php echo $bedrooms_attrs; ?>><?php echo marcan_rich_inline($bedrooms); ?></small>
                    <?php endif; ?>
                    <small class="marcan-property-price-spec marcan-property-price-spec-area"><?php echo esc_html($area_label); ?></small>
                </div>
                <?php if ($intro !== '') : ?>
                    <div<?php echo $intro_attrs; ?>><?php echo marcan_rich_block($intro); ?></div>
                <?php endif; ?>
            </div>
            <?php if ($content_image_id) : ?>
                <div class="marcan-property-single-wide-image">
                    <?php echo wp_get_attachment_image($content_image_id, 'full', false, array('alt' => '')); ?>
                </div>
            <?php endif; ?>

            <?php if (marcan_section_is_active($post_id, 'mostrar_concepto', array('concepto_titulo', 'concepto_texto', 'frase_proyecto', 'autor_frase'))) : ?>
            <div class="marcan-property-concept">
                <div class="marcan-property-concept-copy">
                    <h2<?php echo $concept_title_attrs; ?>><?php echo marcan_rich_inline($concept_title); ?></h2>
                    <div<?php echo $concept_text_attrs; ?>><?php echo marcan_rich_block($concept_text); ?></div>
                </div>
                <?php if (marcan_section_is_active($post_id, 'mostrar_disenador', array('disenador_interiores_nombre', 'disenador_interiores_cargo', 'disenador_interiores_foto')) && ($designer_name !== '' || $designer_role !== '' || $designer_photo_id)) : ?>
                    <aside class="marcan-property-designer">
                        <?php if ($designer_photo_id) : ?>
                            <figure class="marcan-property-designer-photo">
                                <?php echo wp_get_attachment_image($designer_photo_id, 'medium_large', false, array('alt' => esc_attr($designer_name_plain))); ?>
                            </figure>
                        <?php endif; ?>
                        <?php if ($designer_name !== '' || $designer_role !== '') : ?>
                            <div class="marcan-property-designer-info">
                                <?php if ($designer_name !== '') : ?>
                                    <h3<?php echo $designer_name_attrs; ?>><?php echo marcan_rich_inline($designer_name); ?></h3>
                                <?php endif; ?>
                                <?php if ($designer_role !== '') : ?>
                                    <p<?php echo $designer_role_attrs; ?>><?php echo marcan_rich_inline($designer_role); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </aside>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if (marcan_section_is_active($post_id, 'mostrar_tours', array('recorridos_virtuales'))) : ?>
            <section class="marcan-property-tour">
                <?php if (!empty($virtual_tours)) : ?>
                    <div class="marcan-property-tour-shell" data-marcan-tour>
                        <aside class="marcan-property-tour-menu" aria-label="<?php esc_attr_e('Recorridos virtuales', 'marcan'); ?>">
                            <h2 class="marcan-property-tour-title"><?php echo esc_html(marcan_get_option_text('ui_property_tours_title', 'Recorridos virtuales')); ?></h2>
                            <div class="marcan-property-tour-nav">
                                <?php
                                $tour_groups = array();
                                foreach ($virtual_tours as $tour_item) {
                                    $tour_group = trim((string) ($tour_item['group'] ?? ''));
                                    if ($tour_group === '') {
                                        $tour_group = __('Recorridos', 'marcan');
                                    }
                                    if (!isset($tour_groups[$tour_group])) {
                                        $tour_groups[$tour_group] = array();
                                    }
                                    $tour_groups[$tour_group][] = $tour_item;
                                }
                                ?>
                                <?php foreach ($tour_groups as $tour_group => $tour_group_items) : ?>
                                    <div class="marcan-property-tour-group is-collapsed">
                                        <button class="marcan-property-tour-group-toggle" type="button" data-tour-group-toggle aria-expanded="false">
                                            <span<?php echo marcan_font_size_attrs($tours_font_size); ?>><?php echo marcan_rich_inline($tour_group); ?></span>
                                            <span aria-hidden="true"></span>
                                        </button>
                                        <div class="marcan-property-tour-group-items">
                                            <?php foreach ($tour_group_items as $tour_item) : ?>
                                                <button
                                                    class="marcan-property-tour-button"
                                                    type="button"
                                                    data-tour-src="<?php echo esc_url($tour_item['src']); ?>"
                                                    data-tour-external="<?php echo esc_url($tour_item['external']); ?>"
                                                    data-tour-title="<?php echo esc_attr(wp_strip_all_tags($tour_item['title'])); ?>"
                                                    aria-pressed="false"
                                                >
                                                    <span<?php echo marcan_font_size_attrs($tours_font_size); ?>><?php echo marcan_rich_inline($tour_item['title']); ?></span>
                                                </button>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button class="marcan-property-tour-collapse" type="button" data-tour-collapse aria-expanded="true" aria-label="<?php esc_attr_e('Minimizar selector de recorridos', 'marcan'); ?>">
                                <img class="marcan-property-tour-arrow" src="<?php echo esc_url(marcan_asset_uri('images/figma-tour-arrow-left-v2.svg')); ?>" alt="" aria-hidden="true">
                            </button>
                        </aside>
                        <div class="marcan-property-tour-frame">
                            <?php echo wp_get_attachment_image($hero_id, 'full', false, array('class' => 'marcan-property-tour-poster', 'alt' => '')); ?>
                            <iframe
                                title="<?php esc_attr_e('Recorrido virtual', 'marcan'); ?>"
                                hidden
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; fullscreen; xr-spatial-tracking"
                                allowfullscreen
                            ></iframe>
                            <div class="marcan-property-tour-loading" data-tour-loading hidden>
                                <span aria-hidden="true"></span>
                                <p><?php esc_html_e('Cargando recorrido', 'marcan'); ?></p>
                            </div>
                            <a class="marcan-property-tour-external" href="#" target="_blank" rel="noopener" data-tour-external-link hidden>
                                <?php esc_html_e('Abrir recorrido', 'marcan'); ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </section>
            <?php endif; ?>

            <?php if (marcan_section_is_active($post_id, 'mostrar_unidades', array('unidades'))) : ?>
            <section class="marcan-property-units">
                <h2>
                    <span><?php esc_html_e('Revisa las opciones que tenemos en', 'marcan'); ?></span>
                    <strong><?php echo marcan_rich_inline(sprintf(__('%s desde %s', 'marcan'), wp_strip_all_tags($title), $price)); ?></strong>
                </h2>
                <?php if (is_array($units) && !empty($units)) : ?>
                    <div class="marcan-property-filter-actions">
                        <button class="marcan-property-filter-toggle" type="button" data-property-filter-toggle aria-expanded="true">
                            <span><?php esc_html_e('Ocultar Filtros', 'marcan'); ?></span>
                        </button>
                        <button class="marcan-property-filter-clear" type="button" data-property-filter-clear><?php esc_html_e('Borrar filtros', 'marcan'); ?></button>
                    </div>
                    <div class="marcan-property-filter-bar" data-property-filters hidden>
                    <div class="marcan-property-filter-modal-header">
                        <h3><?php esc_html_e('Filtros de Búsqueda', 'marcan'); ?></h3>
                        <button class="marcan-property-filter-modal-close" type="button" data-property-filter-close aria-label="<?php esc_attr_e('Cerrar filtros', 'marcan'); ?>"></button>
                    </div>
                    <?php
                    $check_filters = array(
                        'habitaciones' => $is_office ? __('Tipo de unidad', 'marcan') : __('Habitaciones', 'marcan'),
                        'banos' => __('Baños', 'marcan'),
                    );
                    foreach ($check_filters as $filter_key => $filter_label) :
                        ?>
                        <fieldset class="marcan-property-filter-group marcan-property-filter-checks" data-filter-group="<?php echo esc_attr($filter_key); ?>">
                            <legend><?php echo esc_html($filter_label); ?></legend>
                            <div class="marcan-property-filter-options">
                                <?php foreach ($unit_filter_values[$filter_key] as $option_value => $option_label) : ?>
                                    <label class="marcan-property-check-option">
                                        <input type="checkbox" value="<?php echo esc_attr($option_value); ?>" data-property-check="<?php echo esc_attr($filter_key); ?>">
                                        <span><?php echo esc_html($option_label); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </fieldset>
                    <?php endforeach; ?>
                    <?php if ($area_range_max > $area_range_min) : ?>
                        <div class="marcan-property-filter-group marcan-property-filter-range" data-property-range="area_m2" data-range-unit="m²">
                            <span class="marcan-property-filter-label"><?php esc_html_e('Área m²', 'marcan'); ?></span>
                            <div class="marcan-property-range-values"><span data-range-label="min"><?php echo esc_html($area_range_min); ?> m²</span><span data-range-label="max"><?php echo esc_html($area_range_max); ?> m²</span></div>
                            <div class="marcan-property-range-control">
                                <input type="range" min="<?php echo esc_attr($area_range_min); ?>" max="<?php echo esc_attr($area_range_max); ?>" value="<?php echo esc_attr($area_range_min); ?>" data-range-min>
                                <input type="range" min="<?php echo esc_attr($area_range_min); ?>" max="<?php echo esc_attr($area_range_max); ?>" value="<?php echo esc_attr($area_range_max); ?>" data-range-max>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($price_range_max > $price_range_min) : ?>
                        <div class="marcan-property-filter-group marcan-property-filter-range" data-property-range="precio" data-range-unit="S/">
                            <span class="marcan-property-filter-label"><?php esc_html_e('Precio', 'marcan'); ?></span>
                            <div class="marcan-property-range-values"><span data-range-label="min">S/ <?php echo esc_html(number_format($price_range_min)); ?></span><span data-range-label="max">S/ <?php echo esc_html(number_format($price_range_max)); ?></span></div>
                            <div class="marcan-property-range-control">
                                <input type="range" min="<?php echo esc_attr($price_range_min); ?>" max="<?php echo esc_attr($price_range_max); ?>" step="1000" value="<?php echo esc_attr($price_range_min); ?>" data-range-min>
                                <input type="range" min="<?php echo esc_attr($price_range_min); ?>" max="<?php echo esc_attr($price_range_max); ?>" step="1000" value="<?php echo esc_attr($price_range_max); ?>" data-range-max>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="marcan-property-filter-modal-actions">
                        <button class="marcan-property-filter-modal-clear" type="button" data-property-filter-modal-clear><?php esc_html_e('Borrar', 'marcan'); ?></button>
                        <button class="marcan-property-filter-modal-save" type="button" data-property-filter-save><?php esc_html_e('Guardar', 'marcan'); ?></button>
                    </div>
                    </div>
                <?php endif; ?>
                <div<?php echo marcan_font_size_attrs($units_font_size, 'marcan-property-pricing-table', true); ?> data-property-units>
                    <div class="marcan-property-pricing-head">
                        <span><?php esc_html_e('Código', 'marcan'); ?></span>
                        <span><?php esc_html_e('Piso', 'marcan'); ?></span>
                        <span><?php echo esc_html($is_office ? __('Tipo', 'marcan') : __('Habitaciones', 'marcan')); ?></span>
                        <span><?php esc_html_e('Baños', 'marcan'); ?></span>
                        <span><?php esc_html_e('Área', 'marcan'); ?></span>
                        <span><?php esc_html_e('Precio', 'marcan'); ?></span>
                        <span aria-hidden="true"></span>
                    </div>
                    <?php if (is_array($units) && !empty($units)) : ?>
                        <?php $used_unit_slugs = array(); ?>
                        <?php foreach ($units as $unit_index => $unit) : ?>
                            <?php
                            $unit_plan = $unit['plano'] ?? 0;
                            $unit_plan_id = is_array($unit_plan) && !empty($unit_plan['ID']) ? (int) $unit_plan['ID'] : (int) $unit_plan;
                            $unit_bedrooms_display = trim((string) ($unit['habitaciones'] ?? ''));
                            if (!$is_office) {
                                $unit_bedrooms_display = trim((string) preg_replace('/\s*(?:dorm(?:itorio)?s?)\.?\s*$/iu', '', $unit_bedrooms_display));
                            }
                            $unit_bedrooms_value = $normalize_unit_option($is_office ? ($unit['estado'] ?? '') : ($unit['habitaciones'] ?? ''));
                            $unit_bathrooms_value = trim((string) ($unit['banos'] ?? ''));
                            $unit_area_number = $parse_unit_number($unit['area_m2'] ?? '');
                            $unit_area_display = marcan_format_measurement($unit['area_m2'] ?? '', $unit['area_unidad'] ?? 'm2');
                            $unit_price_number = $parse_unit_number($unit['precio'] ?? '');
                            $unit_quote_url = marcan_resolve_file_url($unit['cotizacion_pdf'] ?? '');
                            $unit_expanded = $unit_index === 0;
                            $unit_status = trim((string) ($unit['estado'] ?? ''));
                            if (!$is_office && ($unit_status === '' || strtolower($unit_status) === 'disponible')) {
                                $unit_status = __('Departamento vista interior', 'marcan');
                            } elseif ($unit_status === '') {
                                $unit_status = $is_office ? __('Oficina disponible', 'marcan') : __('Departamento vista interior', 'marcan');
                            }
                            $unit_code = trim((string) ($unit['codigo'] ?? ''));
                            $unit_slug_base = sanitize_title($unit_code);
                            if ($unit_slug_base === '') {
                                $unit_slug_base = 'unidad-' . ($unit_index + 1);
                            }
                            $unit_slug = $unit_slug_base;
                            $unit_slug_suffix = 2;
                            while (isset($used_unit_slugs[$unit_slug])) {
                                $unit_slug = $unit_slug_base . '-' . $unit_slug_suffix;
                                $unit_slug_suffix++;
                            }
                            $used_unit_slugs[$unit_slug] = true;
                            $unit_share_url = add_query_arg('tipologia', $unit_slug, get_permalink($post_id)) . '#tipologia-' . rawurlencode($unit_slug);
                            $unit_plan_full_url = $unit_plan_id ? wp_get_attachment_image_url($unit_plan_id, 'full') : '';
                            $unit_plan_title = $unit_code !== '' ? sprintf(__('Tipologia %s', 'marcan'), $unit_code) : sprintf(__('Tipologia %d', 'marcan'), $unit_index + 1);
                            $unit_code_attrs = marcan_font_size_attrs(marcan_get_row_font_size($unit, 'codigo'));
                            $unit_status_attrs = marcan_font_size_attrs(marcan_get_row_font_size($unit, 'estado'));
                            $unit_price_attrs = marcan_font_size_attrs(marcan_get_row_font_size($unit, 'precio'));
                            ?>
                            <article
                                class="marcan-property-unit-card<?php echo $unit_expanded ? ' is-expanded' : ''; ?>"
                                id="tipologia-<?php echo esc_attr($unit_slug); ?>"
                                data-unit-row
                                data-unit-slug="<?php echo esc_attr($unit_slug); ?>"
                                data-habitaciones="<?php echo esc_attr($unit_bedrooms_value); ?>"
                                data-banos="<?php echo esc_attr($unit_bathrooms_value); ?>"
                                data-area_m2="<?php echo esc_attr($unit_area_number); ?>"
                                data-precio="<?php echo esc_attr($unit_price_number); ?>"
                            >
                                <button class="marcan-property-pricing-row" type="button" data-unit-toggle aria-expanded="<?php echo $unit_expanded ? 'true' : 'false'; ?>">
                                    <span class="marcan-property-unit-desktop-value marcan-property-unit-code-cell">
                                        <?php if ($unit_plan_id) : ?>
                                            <?php echo wp_get_attachment_image($unit_plan_id, 'thumbnail', false, array('class' => 'marcan-property-unit-row-plan', 'alt' => '')); ?>
                                        <?php endif; ?>
                                        <span<?php echo $unit_code_attrs; ?>><?php echo esc_html($unit['codigo'] ?? ''); ?></span>
                                    </span>
                                    <span class="marcan-property-unit-desktop-value"><?php echo esc_html($unit['piso'] ?? ''); ?></span>
                                    <span class="marcan-property-unit-desktop-value"><?php if ($is_office) : ?><span<?php echo $unit_status_attrs; ?>><?php echo esc_html($unit['estado'] ?? ''); ?></span><?php else : ?><?php echo esc_html($unit['habitaciones'] ?? ''); ?><?php endif; ?></span>
                                    <span class="marcan-property-unit-desktop-value"><?php echo esc_html($unit['banos'] ?? ''); ?></span>
                                    <span class="marcan-property-unit-desktop-value"><?php echo esc_html($unit_area_display); ?></span>
                                    <span class="marcan-property-unit-desktop-value"><span<?php echo $unit_price_attrs; ?>><?php echo esc_html($unit['precio'] ?? ''); ?></span></span>
                                    <span class="marcan-property-unit-mobile-summary">
                                        <span class="marcan-property-unit-mobile-topline">
                                            <span<?php echo $unit_code_attrs; ?>><?php echo esc_html(trim($unit_code . (!empty($unit['piso']) ? ' - ' . sprintf(__('Piso %s', 'marcan'), $unit['piso']) : ''))); ?></span>
                                            <span<?php echo $unit_price_attrs; ?>><?php echo esc_html(trim(($unit_area_display !== '' ? $unit_area_display : '') . (($unit['precio'] ?? '') !== '' ? ' - ' . ($unit['precio'] ?? '') : ''))); ?></span>
                                        </span>
                                        <span class="marcan-property-unit-mobile-features">
                                            <span<?php echo $is_office ? $unit_status_attrs : ''; ?>><?php echo esc_html(sprintf('%s: %s', $is_office ? __('Tipo', 'marcan') : __('Habitaciones', 'marcan'), $is_office ? ($unit['estado'] ?? '') : ($unit['habitaciones'] ?? ''))); ?></span>
                                            <span><?php echo esc_html(sprintf(__('Baños: %s', 'marcan'), $unit['banos'] ?? '')); ?></span>
                                        </span>
                                    </span>
                                    <span class="marcan-property-unit-toggle-icon" aria-hidden="true"><?php echo $unit_expanded ? '−' : '+'; ?></span>
                                </button>
                                <div class="marcan-property-unit-detail" data-unit-detail <?php echo $unit_expanded ? '' : 'hidden'; ?>>
                                    <div class="marcan-property-unit-plan-layout">
                                        <figure
                                            class="marcan-property-unit-plan"
                                            <?php echo $unit_plan_id ? 'data-unit-plan-zoom data-unit-plan-full-src="' . esc_url($unit_plan_full_url) . '" data-unit-plan-title="' . esc_attr($unit_plan_title) . '"' : ''; ?>
                                        >
                                            <?php if ($unit_plan_id) : ?>
                                                <?php echo wp_get_attachment_image($unit_plan_id, 'large', false, array('alt' => esc_attr($unit_code))); ?>
                                                <div class="marcan-property-unit-plan-controls" aria-label="<?php esc_attr_e('Controles del plano', 'marcan'); ?>">
                                                    <button class="marcan-property-unit-plan-action is-zoom" type="button" data-unit-plan-zoom-toggle aria-label="<?php esc_attr_e('Activar lupa del plano', 'marcan'); ?>" aria-pressed="false">
                                                        <?php echo marcan_svg('lucide-search'); ?>
                                                    </button>
                                                    <button class="marcan-property-unit-plan-action is-expand" type="button" data-unit-plan-expand aria-label="<?php esc_attr_e('Ver plano completo', 'marcan'); ?>">
                                                        <span aria-hidden="true"></span>
                                                    </button>
                                                </div>
                                            <?php else : ?>
                                                <span><?php esc_html_e('Plano no disponible', 'marcan'); ?></span>
                                            <?php endif; ?>
                                        </figure>
                                        <div class="marcan-property-unit-info">
                                            <h3<?php echo $unit_status_attrs; ?>><?php echo esc_html($unit_status); ?></h3>
                                            <dl class="marcan-property-unit-features">
                                                <?php if (!empty($unit['piso'])) : ?>
                                                    <div><dt><?php esc_html_e('Piso', 'marcan'); ?></dt><dd><?php echo esc_html($unit['piso']); ?></dd></div>
                                                <?php endif; ?>
                                                <?php if (!empty($unit[$is_office ? 'estado' : 'habitaciones'])) : ?>
                                                    <div><dt><?php echo esc_html($is_office ? __('Tipo', 'marcan') : __('Dormitorios', 'marcan')); ?></dt><dd><?php echo esc_html($is_office ? $unit['estado'] : $unit_bedrooms_display); ?></dd></div>
                                                <?php endif; ?>
                                                <?php if (!empty($unit['banos'])) : ?>
                                                    <div><dt><?php esc_html_e('Baños', 'marcan'); ?></dt><dd><?php echo esc_html($unit['banos']); ?></dd></div>
                                                <?php endif; ?>
                                                <?php if ($unit_area_display !== '') : ?>
                                                    <div><dt><?php esc_html_e('Área', 'marcan'); ?></dt><dd><?php echo esc_html($unit_area_display); ?></dd></div>
                                                <?php endif; ?>
                                                <?php if (!empty($unit['precio'])) : ?>
                                                    <div><dt><?php esc_html_e('Precio', 'marcan'); ?></dt><dd<?php echo $unit_price_attrs; ?>><?php echo esc_html($unit['precio']); ?></dd></div>
                                                <?php endif; ?>
                                            </dl>
                                            <div class="marcan-property-unit-actions">
                                                <?php if ($unit_quote_url !== '') : ?>
                                                    <a class="marcan-button-line marcan-button-icon marcan-button-icon-download" href="<?php echo esc_url($unit_quote_url); ?>" target="_blank" rel="noopener"><?php echo esc_html(marcan_get_option_text('ui_property_btn_download_quote', 'Descargar cotización')); ?></a>
                                                <?php endif; ?>
                                                <button class="marcan-button-dark marcan-button-icon marcan-button-icon-arrow" type="button" data-open-contact-modal><?php echo esc_html(marcan_get_option_text('ui_property_btn_contact', 'Contáctanos')); ?></button>
                                                <button class="marcan-button-line marcan-property-unit-share" type="button" data-unit-share data-share-url="<?php echo esc_url($unit_share_url); ?>" data-share-title="<?php echo esc_attr(sprintf(__('Tipología %1$s en %2$s', 'marcan'), $unit_code, $title_plain)); ?>">
                                                    <span><?php echo esc_html(marcan_get_option_text('ui_property_btn_share', 'Compartir')); ?></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <p class="marcan-property-units-empty" data-property-units-empty hidden><?php esc_html_e('No hay unidades con esos filtros.', 'marcan'); ?></p>
                </div>
            </section>
            <?php endif; ?>

            <?php if (marcan_section_is_active($post_id, 'mostrar_ubicacion', array('google_maps_url', 'waze_url', 'ubicacion_titulo', 'ubicacion_descripcion'))) : ?>
            <section class="marcan-property-map">
                <div class="marcan-property-map-inner">
                    <div class="marcan-property-map-canvas">
                        <?php if ($map_embed_src !== '') : ?>
                            <iframe
                                src="<?php echo esc_url($map_embed_src); ?>"
                                title="<?php echo esc_attr($map_card_title); ?>"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                allowfullscreen></iframe>
                        <?php endif; ?>
                    </div>
                    <div class="marcan-property-map-info">
                        <h2<?php echo $map_heading_attrs; ?>><?php echo marcan_rich_inline($map_heading); ?></h2>
                        <div class="marcan-property-map-actions">
                            <?php if ($map_google_url !== '') : ?>
                                <a class="marcan-property-map-button marcan-property-map-button-white" href="<?php echo esc_url($map_google_url); ?>" target="_blank" rel="noopener"><?php echo esc_html(marcan_get_option_text('ui_property_map_google', 'Ver en Google Maps')); ?></a>
                            <?php endif; ?>
                            <?php if ($map_waze_url !== '') : ?>
                                <a class="marcan-property-map-button marcan-property-map-button-yellow" href="<?php echo esc_url($map_waze_url); ?>" target="_blank" rel="noopener"><?php echo esc_html(marcan_get_option_text('ui_property_map_waze', 'Ver en Waze')); ?></a>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($map_nearby_content) && is_array($map_nearby_content)) : ?>
                            <div class="marcan-property-map-nearby">
                                <p<?php echo $map_nearby_title_attrs; ?>><?php echo marcan_rich_inline($map_nearby_title); ?></p>
                                <div<?php echo marcan_font_size_attrs($nearby_font_size, 'marcan-property-map-nearby-grid', true); ?>>
                                    <?php foreach ($map_nearby_content as $group) : ?>
                                        <?php
                                        $cat = trim((string) ($group['categoria'] ?? ''));
                                        $items_raw = trim((string) ($group['items'] ?? ''));
                                        $items = $items_raw !== '' ? array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $items_raw)))) : array();
                                        if ($cat === '' && empty($items)) continue;
                                        ?>
                                        <div class="marcan-property-map-nearby-group">
                                            <?php if ($cat !== '') : ?>
                                                <strong<?php echo marcan_font_size_attrs(marcan_get_row_font_size($group, 'categoria')); ?>><?php echo esc_html($cat); ?></strong>
                                            <?php endif; ?>
                                            <?php if (!empty($items)) : ?>
                                                <ol>
                                                    <?php foreach ($items as $item) : ?>
                                                        <li<?php echo marcan_font_size_attrs(marcan_get_row_font_size($group, 'items')); ?>><?php echo esc_html($item); ?></li>
                                                    <?php endforeach; ?>
                                                </ol>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if ($map_description !== '') : ?>
                    <div<?php echo $map_description_attrs; ?>><?php echo marcan_rich_block($map_description); ?></div>
                <?php endif; ?>
            </section>
            <?php endif; ?>

            <?php if (marcan_section_is_active($post_id, 'mostrar_galeria', array('areas_comunes', 'areas_internas'))) : ?>
            <?php foreach (array('areas_comunes' => array('desktop' => $common, 'mobile' => $common_mobile), 'areas_internas' => array('desktop' => $internal, 'mobile' => $internal_mobile)) as $label => $gallery_set) : ?>
            <?php $gallery = $gallery_set['desktop']; $gallery_mobile_ids = is_array($gallery_set['mobile']) ? array_values($gallery_set['mobile']) : array(); ?>
                <?php
                $gallery_ids = is_array($gallery) ? $gallery : array();
                if (empty($gallery_ids) && $content_image_id) {
                    $gallery_ids = array($content_image_id, $hero_id, $content_image_id);
                }

                $gallery_items = array();
                foreach ($gallery_ids as $image_id) {
                    $image_id = is_array($image_id) && !empty($image_id['ID']) ? (int) $image_id['ID'] : (int) $image_id;
                    if (!$image_id) {
                        continue;
                    }

                    $caption = trim((string) wp_get_attachment_caption($image_id));
                    if ($caption === '') {
                        $raw_title = trim((string) get_the_title($image_id));
                        if (strpos($raw_title, '_') !== false) {
                            $raw_title = substr($raw_title, strpos($raw_title, '_') + 1);
                        }
                        $clean = ucfirst(trim(str_replace(array('_', '-'), ' ', $raw_title)));
                        $caption = trim(preg_replace('/\bextendid[oa]s?\b/i', '', $clean));
                        $caption = trim(preg_replace('/\s+/', ' ', $caption));
                    }
                    if ($caption === '') {
                        $caption = $label === 'areas_comunes' ? __('Area comun', 'marcan') : __('Departamento', 'marcan');
                    }

                    $gallery_items[] = array(
                        'id' => $image_id,
                        'caption' => $caption,
                    );
                }

                if (empty($gallery_items)) {
                    continue;
                }
                ?>
                <section class="marcan-property-gallery-row marcan-property-gallery-row-<?php echo esc_attr($label); ?>" data-property-gallery>
                    <div class="marcan-property-gallery-side">
                        <h2><?php echo esc_html($label === 'areas_comunes' ? __('Áreas comunes', 'marcan') : __('Departamento', 'marcan')); ?></h2>
                        <div class="marcan-property-gallery-nav">
                            <?php foreach ($gallery_items as $item_index => $gallery_item) : ?>
                                <button class="<?php echo $item_index === 0 ? 'is-active' : ''; ?>" type="button" data-gallery-jump="<?php echo esc_attr($item_index); ?>">
                                    <?php echo esc_html($gallery_item['caption']); ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="marcan-property-gallery-track" data-gallery-track>
                        <?php foreach ($gallery_items as $item_index => $gallery_item) : ?>
                            <?php $mobile_img_id = $gallery_mobile_ids[$item_index] ?? 0; ?>
                            <figure data-gallery-item="<?php echo esc_attr($item_index); ?>">
                                <?php $slider_img_id = $mobile_img_id ?: $gallery_item['id']; ?>
                                <button type="button" class="marcan-property-gallery-image-button" data-gallery-image="<?php echo esc_url(wp_get_attachment_image_url($gallery_item['id'], 'full')); ?>" data-gallery-title="<?php echo esc_attr($gallery_item['caption']); ?>">
                                    <?php echo wp_get_attachment_image($slider_img_id, 'full', false, array('alt' => esc_attr($gallery_item['caption']))); ?>
                                    <span aria-hidden="true"></span>
                                </button>
                                <figcaption><?php echo esc_html($gallery_item['caption']); ?></figcaption>
                            </figure>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endforeach; ?>
            <?php endif; ?>

            <?php if (marcan_section_is_active($post_id, 'mostrar_arquitectura', array('arquitectura_titulo', 'arquitectura_texto', 'arquitectura_imagen'))) : ?>
            <section class="marcan-property-quote">
                <span><?php echo esc_html(marcan_get_option_text('ui_property_about_label', 'Sobre el proyecto')); ?></span>
                <blockquote<?php echo $quote_attrs; ?>><?php echo marcan_rich_block(marcan_get_property_field($post_id, 'frase_proyecto', 'Time: Aramburu se creo con el enfoque y balance de la naturaleza y el mar, vibran los detalles en cada espacio')); ?></blockquote>
                <cite<?php echo $quote_author_attrs; ?>><?php echo marcan_rich_inline(marcan_get_property_field($post_id, 'autor_frase', 'Manuel de Rivero 51-1 Arquitectos')); ?></cite>
            </section>

            <?php if ($architecture_title !== '' || $architecture_text !== '' || $architecture_image_id) : ?>
                <section class="marcan-property-architecture<?php echo $architecture_image_id ? '' : ' marcan-property-architecture--no-image'; ?>">
                    <div class="marcan-property-architecture-info">
                        <div class="marcan-property-architecture-copy">
                            <?php if ($architecture_title !== '') : ?>
                                <h2<?php echo $architecture_title_attrs; ?>><?php echo marcan_rich_inline($architecture_title); ?></h2>
                            <?php endif; ?>
                            <?php if ($architecture_text !== '') : ?>
                                <div<?php echo $architecture_text_attrs; ?>><?php echo marcan_rich_block($architecture_text); ?></div>
                            <?php endif; ?>
                        </div>
                        <?php if ($architecture_studio_name !== '' || $architecture_studio_role !== '' || $architecture_studio_photo_id) : ?>
                            <aside class="marcan-property-architecture-studio">
                                <?php if ($architecture_studio_photo_id) : ?>
                                    <figure><?php echo wp_get_attachment_image($architecture_studio_photo_id, 'medium', false, array('alt' => esc_attr($architecture_studio_name_plain))); ?></figure>
                                <?php endif; ?>
                                <?php if ($architecture_studio_name !== '') : ?><h3<?php echo $architecture_studio_name_attrs; ?>><?php echo marcan_rich_inline($architecture_studio_name); ?></h3><?php endif; ?>
                                <?php if ($architecture_studio_role !== '') : ?><p<?php echo $architecture_studio_role_attrs; ?>><?php echo marcan_rich_inline($architecture_studio_role); ?></p><?php endif; ?>
                            </aside>
                        <?php endif; ?>
                    </div>
                    <?php if ($architecture_image_id) : ?>
                        <figure class="marcan-property-architecture-image">
                            <?php echo wp_get_attachment_image($architecture_image_id, 'full', false, array('alt' => esc_attr($architecture_title_plain))); ?>
                        </figure>
                    <?php endif; ?>
                </section>
            <?php endif; ?>
            <?php endif; ?>
        </section>

        <?php if ($related_intro !== '') : ?>
            <section class="marcan-property-related-intro">
                <div class="marcan-property-related-intro-inner">
                    <h2>
                        <span<?php echo $related_intro_attrs; ?>><?php echo marcan_rich_inline($related_intro); ?></span>
                        <?php if ($price !== '') : ?><strong><?php echo esc_html(sprintf(__('desde %s', 'marcan'), $price)); ?></strong><?php endif; ?>
                    </h2>
                    <button class="marcan-property-related-intro-button" type="button" data-open-contact-modal><?php echo marcan_rich_inline($related_quote_label); ?></button>
                </div>
            </section>
        <?php endif; ?>

        <section class="marcan-property-related<?php echo $related_count === 1 ? ' is-single' : ''; ?><?php echo $related_count >= 3 ? ' has-slider' : ''; ?>">
            <h2><?php echo esc_html($is_office ? marcan_get_option_text('ui_property_related_office', 'Otras oficinas en venta') : marcan_get_option_text('ui_property_related_dept', 'Otros departamentos en venta')); ?></h2>
            <?php if ($related_count === 1) : ?>
                <div class="marcan-property-related-grid is-single">
                    <?php if ($related->have_posts()) : ?>
                        <?php while ($related->have_posts()) : $related->the_post(); ?>
                            <?php if (get_the_ID() === $post_id) { continue; } ?>
                            <?php get_template_part('template-parts/properties/property-card-listing', null, array('post_id' => get_the_ID(), 'layout' => 'media-left')); ?>
                        <?php endwhile; wp_reset_postdata(); ?>
                    <?php endif; ?>
                </div>
            <?php else : ?>
                <div class="marcan-home-project-slider marcan-property-related-slider<?php echo $related_count === 2 ? ' has-two-cards' : ''; ?>"<?php echo $related_count >= 3 ? ' data-project-slider' : ''; ?>>
                    <div class="marcan-home-project-slider-track marcan-property-related-slider-track">
                        <?php if ($related->have_posts()) : ?>
                            <?php while ($related->have_posts()) : $related->the_post(); ?>
                                <?php if (get_the_ID() === $post_id) { continue; } ?>
                                <?php get_template_part('template-parts/properties/property-card-home', null, array('post_id' => get_the_ID())); ?>
                            <?php endwhile; wp_reset_postdata(); ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    </main>
<?php endwhile; ?>

<?php get_footer(); ?>
