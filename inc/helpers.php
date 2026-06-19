<?php
/**
 * Shared theme helpers.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

function marcan_asset_uri(string $path): string
{
    return MARCAN_THEME_URI . 'assets/' . ltrim($path, '/');
}

function marcan_svg(string $name): string
{
    $path = MARCAN_THEME_PATH . 'assets/images/' . $name . '.svg';

    if (!file_exists($path)) {
        return '';
    }

    return (string) file_get_contents($path);
}

function marcan_get_media_attachment_id(string $option_name): int
{
    return (int) get_option($option_name);
}

function marcan_get_media_attachment_url(string $option_name): string
{
    $attachment_id = marcan_get_media_attachment_id($option_name);

    if ($attachment_id && function_exists('wp_get_attachment_url')) {
        $url = wp_get_attachment_url($attachment_id);
        if (is_string($url) && $url !== '') {
            return $url;
        }
    }

    return '';
}

function marcan_get_option_field(string $field_name, $fallback = null)
{
    if (function_exists('get_field')) {
        $value = get_field($field_name, 'option');

        if ($value !== null && $value !== '' && $value !== array()) {
            return $value;
        }
    }

    $legacy = get_option($field_name);

    if ($legacy !== false && $legacy !== null && $legacy !== '') {
        return $legacy;
    }

    return $fallback;
}

function marcan_get_option_text(string $field_name, string $fallback = ''): string
{
    $value = marcan_get_option_field($field_name, $fallback);

    return is_scalar($value) ? (string) $value : $fallback;
}

function marcan_get_context_text(int $post_id, string $field_name, string $option_field = '', string $fallback = ''): string
{
    if ($post_id > 0 && function_exists('get_field')) {
        $value = get_field($field_name, $post_id);
        if (is_scalar($value) && trim((string) $value) !== '') {
            return (string) $value;
        }
    }

    if ($post_id > 0) {
        $meta_value = get_post_meta($post_id, $field_name, true);
        if (is_scalar($meta_value) && trim((string) $meta_value) !== '') {
            return (string) $meta_value;
        }
    }

    return $option_field !== '' ? marcan_get_option_text($option_field, $fallback) : $fallback;
}

function marcan_normalize_phone_number(string $value): string
{
    return preg_replace('/\D+/', '', $value) ?? '';
}

function marcan_get_context_whatsapp_number(int $post_id = 0): string
{
    $default_number = '51919490440';
    $global_number = marcan_normalize_phone_number(marcan_get_option_text('whatsapp_contacto', $default_number));
    $context_number = '';

    if ($post_id > 0) {
        $raw_context_number = function_exists('get_field') ? get_field('whatsapp_contacto', $post_id) : get_post_meta($post_id, 'whatsapp_contacto', true);
        if (is_scalar($raw_context_number)) {
            $context_number = marcan_normalize_phone_number((string) $raw_context_number);
        }
    }

    if ($context_number !== '') {
        return $context_number;
    }

    return $global_number !== '' ? $global_number : $default_number;
}

function marcan_get_context_whatsapp_url(int $post_id = 0, string $message = ''): string
{
    $number = marcan_get_context_whatsapp_number($post_id);
    $url = 'https://wa.me/' . $number;

    if ($message !== '') {
        $url = add_query_arg('text', $message, $url);
    }

    return $url;
}

function marcan_get_option_color(string $field_name, string $fallback = ''): string
{
    $value = marcan_get_option_field($field_name, $fallback);

    return is_string($value) && $value !== '' ? $value : $fallback;
}

function marcan_font_size_choices(): array
{
    return array(
        '12px', '14px', '15px', '16px', '18px', '20px', '21px', '22px', '24px',
        '25px', '26px', '28px', '30px', '32px', '36px', '40px', '45px', '48px',
    );
}

function marcan_normalize_font_size_value($value): string
{
    if (!is_scalar($value)) {
        return '';
    }

    $value = trim((string) $value);
    return in_array($value, marcan_font_size_choices(), true) ? $value : '';
}

function marcan_get_field_font_size(string $field_name, $post_id = 0, string $fallback_field = ''): array
{
    $source = $post_id ?: false;
    $group = array();

    if (function_exists('get_field')) {
        $value = $source === false ? get_field($field_name . '_font_size') : get_field($field_name . '_font_size', $source);
        if (is_array($value)) {
            $group = $value;
        }
    }

    if (empty($group) && $source !== false) {
        $desktop_meta = get_post_meta((int) $source, $field_name . '_font_size_desktop', true);
        $mobile_meta = get_post_meta((int) $source, $field_name . '_font_size_mobile', true);
        if ($desktop_meta !== '' || $mobile_meta !== '') {
            $group = array('desktop' => $desktop_meta, 'mobile' => $mobile_meta);
        }
    }

    if (empty($group) && $fallback_field !== '') {
        return marcan_get_field_font_size($fallback_field, $post_id);
    }

    return is_array($group) ? $group : array();
}

function marcan_get_option_font_size(string $field_name): array
{
    if (!function_exists('get_field')) {
        return array();
    }

    $value = get_field($field_name . '_font_size', 'option');
    return is_array($value) ? $value : array();
}

function marcan_get_row_font_size($row, string $field_name): array
{
    if (!is_array($row)) {
        return array();
    }

    $value = $row[$field_name . '_font_size'] ?? array();
    return is_array($value) ? $value : array();
}

function marcan_font_size_group_has_override($font_size_group): bool
{
    if (!is_array($font_size_group)) {
        return false;
    }

    return marcan_normalize_font_size_value($font_size_group['desktop'] ?? '') !== ''
        || marcan_normalize_font_size_value($font_size_group['mobile'] ?? '') !== '';
}

function marcan_resolve_context_font_size($context_font_size, string $field_name, $post_id = 0, string $fallback_field = ''): array
{
    if (marcan_font_size_group_has_override($context_font_size)) {
        return is_array($context_font_size) ? $context_font_size : array();
    }

    return marcan_get_field_font_size($field_name, $post_id, $fallback_field);
}

function marcan_font_size_attrs($font_size_group, string $class = '', bool $rich = false): string
{
    $group = is_array($font_size_group) ? $font_size_group : array();
    $desktop = marcan_normalize_font_size_value($group['desktop'] ?? '');
    $mobile = marcan_normalize_font_size_value($group['mobile'] ?? '');
    $has_override = $desktop !== '' || $mobile !== '';

    $classes = trim($class);
    if ($has_override) {
        $classes = trim($classes . ' marcan-field-font-size' . ($rich ? ' marcan-field-font-size--rich' : ''));
    }

    $attrs = '';
    if ($classes !== '') {
        $attrs .= ' class="' . esc_attr($classes) . '"';
    }

    if ($has_override) {
        $styles = array();
        if ($desktop !== '') {
            $styles[] = '--marcan-font-size-desktop:' . $desktop;
        }
        if ($mobile !== '') {
            $styles[] = '--marcan-font-size-mobile:' . $mobile;
        }
        $attrs .= ' style="' . esc_attr(implode(';', $styles)) . '"';
    }

    return $attrs;
}

function marcan_get_option_media_attachment_id(string $field_name, string $legacy_option_name = ''): int
{
    $value = marcan_get_option_field($field_name, 0);

    if (is_numeric($value)) {
        return (int) $value;
    }

    if ($legacy_option_name !== '') {
        return (int) get_option($legacy_option_name);
    }

    return 0;
}

function marcan_get_option_media_attachment_url(string $field_name, string $legacy_option_name = ''): string
{
    $attachment_id = marcan_get_option_media_attachment_id($field_name, $legacy_option_name);

    if ($attachment_id && function_exists('wp_get_attachment_url')) {
        $url = wp_get_attachment_url($attachment_id);
        if (is_string($url) && $url !== '') {
            return $url;
        }
    }

    return '';
}

function marcan_get_front_page_id(): int
{
    return (int) get_option('page_on_front');
}

/**
 * URL de una página del tema por slug, con fallback al home.
 */
function marcan_page_url(string $slug, ?string $fallback = null): string
{
    $page = get_page_by_path($slug);

    if ($page instanceof WP_Post) {
        $url = get_permalink($page->ID);
        if (is_string($url) && $url !== '') {
            return $url;
        }
    }

    return $fallback !== null ? $fallback : home_url('/');
}

function marcan_get_home_hero_settings(): array
{
    $defaults = array(
        'autoplay'    => true,
        'interval'    => 5000,
        'effect'      => 'fade',
    );

    if (!function_exists('get_field')) {
        return $defaults;
    }

    $page_id = marcan_get_front_page_id();

    if (!$page_id) {
        return $defaults;
    }

    $autoplay = get_field('hero_autoplay', $page_id);
    $interval = get_field('hero_interval', $page_id);
    $effect = get_field('hero_effect', $page_id);

    return array(
        'autoplay'    => $autoplay === null ? $defaults['autoplay'] : (bool) $autoplay,
        'interval'    => is_numeric($interval) ? max(1000, (int) $interval) : $defaults['interval'],
        'effect'      => is_string($effect) && $effect !== '' ? $effect : $defaults['effect'],
    );
}

function marcan_get_home_projects_settings(): array
{
    $defaults = array(
        'intro_title'                 => 'Tenemos una manera diferente de hacer las cosas',
        'intro_title_font_size'       => array(),
        'intro_copy'                  => 'Somos una inmobiliaria enfocada en hacer proyectos que impulsan el desarrollo urbano de Lima, inspirados en las verdaderas necesidades de las personas y de la ciudad.',
        'intro_copy_font_size'        => array(),
        'intro_button_label'          => 'Conoce más sobre nosotros',
        'intro_button_label_font_size' => array(),
        'intro_button_url'            => '',
        'departments_title'           => 'Departamentos en venta',
        'departments_title_font_size' => array(),
        'departments_button_label'     => 'Ver más departamentos',
        'departments_button_label_font_size' => array(),
        'departments_button_url'       => '',
        'offices_title'               => 'Oficinas en venta',
        'offices_title_font_size'     => array(),
        'offices_button_label'        => 'Ver más oficinas',
        'offices_button_label_font_size' => array(),
        'offices_button_url'          => '',
    );

    if (!function_exists('get_field')) {
        return $defaults;
    }

    $page_id = marcan_get_front_page_id();

    if (!$page_id) {
        return $defaults;
    }

    $intro_button = get_field('home_intro_button', $page_id);
    $departments_button = get_field('home_departments_button', $page_id);
    $offices_button = get_field('home_offices_button', $page_id);

    $resolve_link = static function ($link): string {
        if (is_array($link) && !empty($link['url'])) {
            return (string) $link['url'];
        }

        return '';
    };

    return array(
        'intro_title'             => (string) (get_field('home_intro_title', $page_id) ?: ''),
        'intro_title_font_size'   => marcan_get_field_font_size('home_intro_title', $page_id),
        'intro_copy'              => (string) (get_field('home_intro_copy', $page_id) ?: ''),
        'intro_copy_font_size'    => marcan_get_field_font_size('home_intro_copy', $page_id),
        'intro_button_label'      => (string) (get_field('home_intro_button_label', $page_id) ?: ''),
        'intro_button_label_font_size' => marcan_get_field_font_size('home_intro_button_label', $page_id),
        'intro_button_url'        => $resolve_link($intro_button),
        'departments_title'       => (string) (get_field('home_departments_title', $page_id) ?: ''),
        'departments_title_font_size' => marcan_get_field_font_size('home_departments_title', $page_id),
        'departments_button_label' => (string) (get_field('home_departments_button_label', $page_id) ?: ''),
        'departments_button_label_font_size' => marcan_get_field_font_size('home_departments_button_label', $page_id),
        'departments_button_url'  => $resolve_link($departments_button),
        'offices_title'           => (string) (get_field('home_offices_title', $page_id) ?: ''),
        'offices_title_font_size' => marcan_get_field_font_size('home_offices_title', $page_id),
        'offices_button_label'    => (string) (get_field('home_offices_button_label', $page_id) ?: ''),
        'offices_button_label_font_size' => marcan_get_field_font_size('home_offices_button_label', $page_id),
        'offices_button_url'      => $resolve_link($offices_button),
        'card_font_sizes'         => array(
            'title' => marcan_get_field_font_size('home_card_title', $page_id),
            'badge' => marcan_get_field_font_size('home_card_badge', $page_id),
            'location' => marcan_get_field_font_size('home_card_location', $page_id),
            'price_label' => marcan_get_field_font_size('home_card_price_label', $page_id),
            'price' => marcan_get_field_font_size('home_card_price', $page_id),
            'specs' => marcan_get_field_font_size('home_card_specs', $page_id),
            'cta' => marcan_get_field_font_size('home_card_cta', $page_id),
        ),
    );
}

function marcan_get_home_delivered_settings(): array
{
    $defaults = array(
        'title'               => 'Nuestros proyectos entregados hablan por nosotros',
        'title_font_size'     => array(),
        'button_label'        => 'Conoce más sobre nosotros',
        'button_label_font_size' => array(),
        'button_url'          => '',
        'image_desktop_id'    => 0,
        'image_mobile_id'     => 0,
        'background_color'    => '#f3f2f1',
        'text_color'          => '#4f4f4f',
        'button_bg_color'     => '#4f4f4f',
        'button_text_color'   => '#fbfafa',
    );

    if (!function_exists('get_field')) {
        return $defaults;
    }

    $field_source = marcan_get_front_page_id();
    if (!$field_source) {
        $field_source = 'option';
    }

    $button = get_field('home_delivered_button', $field_source);

    $button_url = '';
    if (is_array($button) && !empty($button['url'])) {
        $button_url = (string) $button['url'];
    }

    return array(
        'title'             => (string) (get_field('home_delivered_title', $field_source) ?: ''),
        'title_font_size'   => marcan_get_field_font_size('home_delivered_title', $field_source),
        'button_label'      => (string) (get_field('home_delivered_button_label', $field_source) ?: ''),
        'button_label_font_size' => marcan_get_field_font_size('home_delivered_button_label', $field_source),
        'button_url'        => $button_url,
        'image_desktop_id'  => (int) (get_field('home_delivered_image_desktop', $field_source) ?: $defaults['image_desktop_id']),
        'image_mobile_id'   => (int) (get_field('home_delivered_image_mobile', $field_source) ?: $defaults['image_mobile_id']),
        'background_color'  => (string) (get_field('home_delivered_background_color', $field_source) ?: $defaults['background_color']),
        'text_color'        => (string) (get_field('home_delivered_text_color', $field_source) ?: $defaults['text_color']),
        'button_bg_color'   => (string) (get_field('home_delivered_button_background', $field_source) ?: $defaults['button_bg_color']),
        'button_text_color' => (string) (get_field('home_delivered_button_text_color', $field_source) ?: $defaults['button_text_color']),
    );
}

function marcan_get_project_sections(string $section): WP_Query
{
    return new WP_Query(array(
        'post_type'      => 'property',
        'posts_per_page' => -1,
        'orderby'        => array('menu_order' => 'ASC', 'date' => 'ASC'),
        'post_status'    => 'publish',
        'meta_query'     => array(
            'relation' => 'AND',
            array(
                'key'     => 'home_section',
                'value'   => $section,
                'compare' => '=',
            ),
            array(
                'relation' => 'OR',
                array(
                    'key'     => 'mostrar_en_listado',
                    'value'   => '0',
                    'compare' => '!=',
                ),
                array(
                    'key'     => 'mostrar_en_listado',
                    'compare' => 'NOT EXISTS',
                ),
            ),
        ),
    ));
}

function marcan_get_properties_by_kind(string $kind, int $limit = -1): WP_Query
{
    $home_section = $kind === 'oficina' || $kind === 'oficinas' ? 'oficinas' : 'departamentos';

    return new WP_Query(array(
        'post_type'      => 'property',
        'posts_per_page' => $limit,
        'orderby'        => array('menu_order' => 'ASC', 'date' => 'ASC'),
        'post_status'    => 'publish',
        'meta_query'     => array(
            'relation' => 'AND',
            array(
                'key'     => 'tipo_inmueble',
                'value'   => $kind,
                'compare' => '=',
            ),
            array(
                'relation' => 'OR',
                array(
                    'key'     => 'mostrar_en_listado',
                    'value'   => '0',
                    'compare' => '!=',
                ),
                array(
                    'key'     => 'mostrar_en_listado',
                    'compare' => 'NOT EXISTS',
                ),
            ),
        ),
    ));
}

function marcan_get_property_kind(int $post_id): string
{
    $kind = function_exists('get_field') ? (string) get_field('tipo_inmueble', $post_id) : '';

    if ($kind === '') {
        $kind = (string) get_post_meta($post_id, 'tipo_inmueble', true);
    }

    return $kind === 'oficina' ? 'oficina' : 'departamento';
}

function marcan_get_property_image_id(int $post_id, string $field, string $fallback_field = ''): int
{
    $value = function_exists('get_field') ? get_field($field, $post_id) : get_post_meta($post_id, $field, true);

    if (is_numeric($value)) {
        return (int) $value;
    }

    if (is_array($value) && !empty($value['ID'])) {
        return (int) $value['ID'];
    }

    if ($fallback_field !== '') {
        return marcan_get_property_image_id($post_id, $fallback_field);
    }

    return (int) get_post_thumbnail_id($post_id);
}

function marcan_get_property_field(int $post_id, string $field, string $fallback = ''): string
{
    if (function_exists('get_field')) {
        $value = get_field($field, $post_id);

        if (is_scalar($value) && $value !== '') {
            return (string) $value;
        }
    }

    $meta = get_post_meta($post_id, $field, true);

    if (is_scalar($meta) && $meta !== '') {
        return (string) $meta;
    }

    return $fallback;
}

function marcan_get_project_card_field(int $post_id, string $field, string $fallback = ''): string
{
    if (function_exists('get_field')) {
        $value = get_field($field, $post_id);

        if (is_array($value) && !empty($value['url'])) {
            return (string) $value['url'];
        }

        if (is_scalar($value) && $value !== '') {
            return (string) $value;
        }
    }

    $meta = get_post_meta($post_id, $field, true);

    if (is_array($meta) && !empty($meta['url'])) {
        return (string) $meta['url'];
    }

    if (is_scalar($meta) && $meta !== '') {
        return (string) $meta;
    }

    return $fallback;
}

/**
 * Mapa dispositivo => min-width (px) para los <source> del <picture> de banners.
 * Ordenado de mayor a menor: el navegador toma el primer <source> que calza.
 */
function marcan_hero_breakpoint_map(): array
{
    return array(
        'desktop_xl'       => 1920,
        'desktop'          => 1366,
        'laptop'           => 1024,
        'tablet'           => 768,
        'mobile_landscape' => 480,
        'mobile_portrait'  => 0,
    );
}

/**
 * Renderiza un <picture> responsive a partir de las filas del repeater de imagenes
 * (marcan_hero_image_repeater). Cada breakpoint usa su imagen cargada o la del
 * siguiente tamano mayor disponible; si no existe, cae al tamano menor mas cercano.
 *
 * @param array  $rows Filas del repeater: cada una ['dispositivo' => string, 'imagen' => int].
 * @param string $alt  Texto alternativo.
 * @param array  $opts ['picture_class','img_class','eager'(bool),'size'(default 'full')].
 */
function marcan_render_hero_picture(array $rows, string $alt = '', array $opts = array()): string
{
    $picture_class = (string) ($opts['picture_class'] ?? '');
    $img_class     = (string) ($opts['img_class'] ?? '');
    $eager         = !empty($opts['eager']);
    $size          = (string) ($opts['size'] ?? 'full');

    $by_device = array();
    foreach ($rows as $row) {
        $device = (string) ($row['dispositivo'] ?? '');
        $img_id = (int) ($row['imagen'] ?? 0);
        if ($device !== '' && $img_id) {
            $by_device[$device] = $img_id;
        }
    }
    if (empty($by_device)) {
        return '';
    }

    $map = marcan_hero_breakpoint_map();
    $devices_ascending = array_reverse(array_keys($map));
    $resolve_device = static function (string $target) use ($by_device, $devices_ascending): int {
        if (!empty($by_device[$target])) {
            return (int) $by_device[$target];
        }

        $target_index = array_search($target, $devices_ascending, true);
        if ($target_index === false) {
            return 0;
        }

        $total = count($devices_ascending);
        for ($i = $target_index + 1; $i < $total; $i++) {
            $device = $devices_ascending[$i];
            if (!empty($by_device[$device])) {
                return (int) $by_device[$device];
            }
        }

        for ($i = $target_index - 1; $i >= 0; $i--) {
            $device = $devices_ascending[$i];
            if (!empty($by_device[$device])) {
                return (int) $by_device[$device];
            }
        }

        return 0;
    };

    $fallback_id = $resolve_device('mobile_portrait');

    $fallback_url = $fallback_id ? wp_get_attachment_image_url($fallback_id, $size) : '';
    if (!$fallback_url) {
        return '';
    }

    $sources = '';
    foreach ($map as $device => $min) {
        if ($min <= 0) {
            continue;
        }
        $img_id = $resolve_device((string) $device);
        if (!$img_id) {
            continue;
        }
        $url = wp_get_attachment_image_url($img_id, $size);
        if (!$url) {
            continue;
        }
        $sources .= sprintf('<source media="(min-width: %dpx)" srcset="%s">', $min, esc_url($url));
    }

    $img = sprintf(
        '<img src="%s" alt="%s"%s decoding="async"%s>',
        esc_url($fallback_url),
        esc_attr($alt),
        $img_class !== '' ? ' class="' . esc_attr($img_class) . '"' : '',
        $eager ? ' fetchpriority="high"' : ' loading="lazy"'
    );

    return sprintf(
        '<picture%s>%s%s</picture>',
        $picture_class !== '' ? ' class="' . esc_attr($picture_class) . '"' : '',
        $sources,
        $img
    );
}

/**
 * Devuelve un unico id de imagen representativo de las filas del repeater de banner
 * (para usos de una sola imagen, ej. miniatura/poster). Prefiere desktop; si no hay,
 * toma el de mayor tamano disponible.
 *
 * @param array $rows Filas del repeater (['dispositivo','imagen']).
 */
function marcan_hero_primary_image_id(array $rows): int
{
    $by_device = array();
    foreach ($rows as $row) {
        $device = (string) ($row['dispositivo'] ?? '');
        $img_id = (int) ($row['imagen'] ?? 0);
        if ($device !== '' && $img_id) {
            $by_device[$device] = $img_id;
        }
    }
    if (empty($by_device)) {
        return 0;
    }
    if (!empty($by_device['desktop'])) {
        return (int) $by_device['desktop'];
    }
    // De mayor a menor min-width.
    foreach (marcan_hero_breakpoint_map() as $device => $min) {
        if (!empty($by_device[$device])) {
            return (int) $by_device[$device];
        }
    }

    return 0;
}

function marcan_get_home_hero_slides(): array
{
    if (!function_exists('get_field')) {
        return array();
    }

    $page_id = marcan_get_front_page_id();
    if (!$page_id) {
        return array();
    }

    $slides = get_field('hero_slides', $page_id);

    return is_array($slides) ? $slides : array();
}

function marcan_get_property_meta(int $post_id, string $key): string
{
    $field_map = array(
        'price'     => 'precio',
        'area'      => 'area',
        'bedrooms'  => 'dormitorios',
        'bathrooms' => 'banos',
        'parking'   => 'estacionamientos',
        'address'   => 'ubicacion',
    );

    $field_name = $field_map[$key] ?? $key;

    if (function_exists('get_field')) {
        $value = get_field($field_name, $post_id);

        if (is_scalar($value)) {
            return (string) $value;
        }
    }

    return (string) get_post_meta($post_id, $field_name, true);
}

function marcan_format_measurement($number, $unit = 'm²', string $fallback = ''): string
{
    $number = trim((string) $number);
    $unit = trim((string) $unit);

    if ($number === '') {
        return $fallback;
    }

    if ($unit === '') {
        $unit = 'm²';
    }

    $unit = str_replace('m2', 'm²', $unit);

    return trim($number . ' ' . $unit);
}

function marcan_get_property_area_display(int $post_id, string $fallback = ''): string
{
    $area_number = function_exists('get_field') ? get_field('area_numero', $post_id) : get_post_meta($post_id, 'area_numero', true);
    $area_unit = function_exists('get_field') ? get_field('area_unidad', $post_id) : get_post_meta($post_id, 'area_unidad', true);
    $legacy_area = marcan_get_property_field($post_id, 'area', $fallback);

    return marcan_format_measurement($area_number, $area_unit, $legacy_area);
}

function marcan_get_home_area_display(int $post_id, string $fallback = ''): string
{
    $area_number = function_exists('get_field') ? get_field('home_area_numero', $post_id) : get_post_meta($post_id, 'home_area_numero', true);
    $area_unit = function_exists('get_field') ? get_field('home_area_unidad', $post_id) : get_post_meta($post_id, 'home_area_unidad', true);
    $legacy_area = trim((string) (function_exists('get_field') ? (get_field('home_area_text', $post_id) ?: get_field('area', $post_id)) : get_post_meta($post_id, 'home_area_text', true)));

    return marcan_format_measurement($area_number, $area_unit, $legacy_area !== '' ? $legacy_area : $fallback);
}

function marcan_resolve_file_url($value): string
{
    if (is_numeric($value)) {
        return (string) wp_get_attachment_url((int) $value);
    }

    if (is_array($value) && !empty($value['url'])) {
        return (string) $value['url'];
    }

    if (is_string($value)) {
        return trim($value);
    }

    return '';
}

function marcan_resolve_google_maps_url(string $url): string
{
    $url = trim($url);
    if ($url === '' || !str_contains($url, 'maps.app.goo.gl')) {
        return $url;
    }

    $cache_key = 'marcan_maps_url_' . md5($url);
    $cached = get_transient($cache_key);
    if (is_string($cached) && $cached !== '') {
        return $cached;
    }

    $response = wp_remote_head($url, array(
        'redirection' => 0,
        'timeout' => 4,
    ));

    if (is_wp_error($response)) {
        return $url;
    }

    $location = wp_remote_retrieve_header($response, 'location');
    if (is_array($location)) {
        $location = reset($location);
    }
    $location = is_string($location) ? trim($location) : '';

    if ($location !== '' && str_contains($location, 'google.com/maps')) {
        set_transient($cache_key, $location, WEEK_IN_SECONDS);
        return $location;
    }

    return $url;
}

function marcan_google_maps_embed_src(string $google_url, string $fallback_query = ''): string
{
    $resolved_url = marcan_resolve_google_maps_url($google_url);

    if (preg_match('/!3d(-?\d+(?:\.\d+)?)!4d(-?\d+(?:\.\d+)?)/', $resolved_url, $matches)) {
        return 'https://www.google.com/maps?q=' . rawurlencode($matches[1] . ',' . $matches[2]) . '&z=17&output=embed';
    }

    if (preg_match('/@(-?\d+(?:\.\d+)?),(-?\d+(?:\.\d+)?),(\d+(?:\.\d+)?)z/', $resolved_url, $matches)) {
        return 'https://www.google.com/maps?q=' . rawurlencode($matches[1] . ',' . $matches[2]) . '&z=' . rawurlencode($matches[3]) . '&output=embed';
    }

    $query = '';
    $parts = wp_parse_url($resolved_url);
    if (!empty($parts['query'])) {
        parse_str($parts['query'], $params);
        if (!empty($params['query']) && is_string($params['query'])) {
            $query = $params['query'];
        } elseif (!empty($params['q']) && is_string($params['q'])) {
            $query = $params['q'];
        }
    }

    if ($query === '' && !empty($parts['path']) && preg_match('#/maps/place/([^/@]+)#', $parts['path'], $matches)) {
        $query = rawurldecode(str_replace('+', ' ', $matches[1]));
    }

    if ($query === '') {
        $query = trim($fallback_query);
    }

    if ($query === '') {
        return '';
    }

    return 'https://www.google.com/maps?q=' . rawurlencode($query) . '&output=embed';
}

function marcan_rich_inline($value): string
{
    $value = trim((string) $value);
    if ($value === '') {
        return '';
    }

    $allowed = array(
        'a' => array(
            'href' => true,
            'target' => true,
            'rel' => true,
            'title' => true,
        ),
        'br' => array(),
        'strong' => array(),
        'b' => array(),
        'em' => array(),
        'i' => array(),
        'span' => array(
            'class' => true,
        ),
    );

    $value = preg_replace('/<\/p>\s*<p[^>]*>/i', '<br>', $value);
    $value = preg_replace('/<\/?p[^>]*>/i', '', (string) $value);

    return wp_kses($value, $allowed);
}

function marcan_rich_block($value): string
{
    $value = trim((string) $value);
    if ($value === '') {
        return '';
    }

    return wp_kses_post(wpautop($value));
}

function marcan_section_is_active(int $post_id, string $toggle_name, array $field_names): bool
{
    $toggle = get_field($toggle_name, $post_id);
    if ($toggle !== null && !$toggle) {
        return false;
    }

    foreach ($field_names as $name) {
        $val = get_field($name, $post_id);
        if ($val !== '' && $val !== false && $val !== null) {
            if (is_array($val) && empty(array_filter($val))) {
                continue;
            }
            return true;
        }
    }

    return false;
}

/**
 * Convierte un arreglo de secciones legales (intro/title/paragraphs/list) en HTML
 * listo para pasar por the_content. Los párrafos pueden contener enlaces <a> de
 * confianza (contenido propio por defecto), por eso no se escapan.
 */
function marcan_build_legal_default_html(array $sections): string
{
    $html = '';

    foreach ($sections as $section) {
        if (isset($section['subtitle'])) {
            continue;
        }

        if (!empty($section['intro'])) {
            foreach ((array) ($section['paragraphs'] ?? array()) as $p) {
                $html .= '<p>' . $p . '</p>';
            }
            continue;
        }

        if (isset($section['title'])) {
            $html .= '<h2>' . $section['title'] . '</h2>';
        }

        foreach ((array) ($section['paragraphs'] ?? array()) as $p) {
            $html .= '<p>' . $p . '</p>';
        }

        if (!empty($section['list'])) {
            $html .= '<ul>';
            foreach ((array) $section['list'] as $item) {
                $html .= '<li>' . $item . '</li>';
            }
            $html .= '</ul>';
        }
    }

    return $html;
}

/**
 * Devuelve el cuerpo de una página legal: el campo SCF si tiene contenido, o el
 * HTML por defecto a partir de las secciones de respaldo (sin regresión visual).
 */
function marcan_get_legal_body(string $field_name, $post_id, array $default_sections): string
{
    if (function_exists('get_field')) {
        $value = get_field($field_name, $post_id ?: false);
        if (is_string($value) && trim($value) !== '') {
            return $value;
        }
    }

    return marcan_build_legal_default_html($default_sections);
}

/**
 * Secciones por defecto de la Política de privacidad (respaldo si el campo SCF está vacío).
 */
function marcan_privacy_default_sections(): array
{
    return array(
        array('intro' => true, 'paragraphs' => array(
            'Inmobiliaria y Constructora Marcan S.A. reconoce la seguridad de los datos personales proporcionados a través de su portal web motivo por el cual brinda a la información el mayor interés y cuidado.',
            'En este sentido, a través de esta política brinda las condiciones y medidas de seguridad con el propósito de salvaguardar el derecho de privacidad y confidencialidad de los datos personales conferidos, de acuerdo con lo establecido en la Ley N° 29733 y su Reglamento el Decreto Supremo N° 003-2013-JUS.',
        )),
        array('title' => 'Identificación, domicilio y titular de los datos personales', 'paragraphs' => array(
            'Inmobiliaria y Constructora Marcan S.A. debidamente identificada con RUC N° 20133793195 y con domicilio en Avenida Santa Cruz N° 820, oficina 402, distrito de Miraflores.',
            'El banco de datos donde se registra la información recopilada por el sitio web es denominado banco de datos personales: "usuarios de la página web" y "quejas y reclamos".',
        )),
        array('title' => 'Finalidad de la recopilación de los datos personales', 'paragraphs' => array(
            'Recopilar la información de los usuarios web para gestionar las relaciones comerciales y de calidad.',
        )),
        array('title' => 'Datos personales obligatorios', 'paragraphs' => array(
            'Para llevar a cabo las finalidades descritas en el numeral anterior, se les solicitará a los usuarios web cuando menos los siguientes datos: Nombre, Apellido Paterno, Apellido Materno, E-mail, Teléfono Fijo, Teléfono Celular, Proyecto inmobiliario y unidad o unidades inmobiliarias de su interés o en el cual haya adquirido una propiedad, Dirección (en caso de no tener relación con la empresa).',
        )),
        array('title' => 'Consecuencias de no proporcionar los datos personales requeridos', 'paragraphs' => array(
            'En caso no se nos proporcione los datos personales considerados como obligatorios, no se podrá establecer una relación comercial con usted, motivo por el cual nos veremos impedidos de cumplir con los ofrecimientos establecidos en nuestra plataforma virtual.',
        )),
        array('title' => 'Plazo de conservación de los datos personales', 'paragraphs' => array(
            'Los datos personales conferidos serán conservados el tiempo necesario para cumplir con las finalidades para la cuales fueron recopilados o hasta que el titular revoque su consentimiento.',
        )),
        array('title' => 'Cómo utilizamos los datos personales recopilados', 'paragraphs' => array(
            'Utilizamos la información recopilada a través de nuestro portal web para distintos propósitos, entre los cuales tenemos:',
        ), 'list' => array(
            'Entregar información a los usuarios sobre las consultas hechas a través del formulario.',
            'Preparar y entregar al titular de los datos personales publicidad de nuestros proyectos, promociones o comunicados.',
            'Realizar encuestas sobre proyectos o servicios.',
            'Envío de invitaciones a actividades convocadas por Inmobiliaria y Constructora Marcan S.A.',
            'Actualizar y consultar datos de contacto y otra información relevante.',
            'Evaluar la capacidad de endeudamiento, comportamiento de pago y de consumo, patrimonio, gustos y preferencias de consumo del titular de los datos personales.',
            'Generar modelos predictivos y alimentarlos con los datos personales.',
            'Elaborar estadísticas y/o estudios de comportamiento, gustos y/o tendencias.',
            'Las demás finalidades que se informen de manera previa y expresa a los titulares de datos personales previo a su tratamiento.',
        )),
        array('title' => 'Flujo transfronterizo de la información', 'paragraphs' => array(
            'Inmobiliaria y Constructora Marcan S.A. realiza la comunicación de datos personales a nivel internacional, remitiendo información a los Estados Unidos de América a la empresa Inmotion Hosting, Inc., con la finalidad de utilizar el servicio de web hosting a efectos de permitir el flujo de archivos y datos que conforman la página web, así como de los datos que se recopilan a través de ella.',
        )),
        array('title' => 'Ejercicio de derechos ARCO', 'paragraphs' => array(
            'Los usuarios web podrán restringir la recopilación o el uso de la información personal proporcionada a través de este formulario. Así mismo, podrán rectificar su información en caso que los datos sean incompletos o inexactos y/o cancelarlos cuando ya no se requieren para la finalidad conferida.',
            'Para hacer ejercicio de sus derechos ARCO escríbanos a <a href="mailto:ventas@marcan.com.pe">ventas@marcan.com.pe</a>.',
        )),
        array('title' => 'Seguridad y confidencialidad', 'paragraphs' => array(
            'Inmobiliaria y Constructora Marcan S.A. cuenta con las medidas técnicas, organizativas y legales necesarias a fin de garantizar la seguridad y confidencialidad de los datos personales. Los datos serán tratados teniendo en consideración los principios de legalidad, consentimiento, proporcionalidad, calidad, finalidad, disposición de recurso, seguridad y nivel de protección adecuado, protegiendo de esta manera los datos conferidos a cabalidad.',
        )),
        array('title' => 'Vigencia y modificación de la política de privacidad', 'paragraphs' => array(
            'Inmobiliaria y Constructora Marcan S.A. podrá modificar en cualquier momento la política de protección de datos personales. Cualquier cambio circunstancial será oportunamente comunicado antes de su implementación en su respectivo portal web <a href="https://marcan.com.pe">www.marcan.com.pe</a>.',
        )),
        array('title' => 'Consultas', 'paragraphs' => array(
            'En caso de tener consultas respecto al alcance de la presente política no dude en comunicarse con nosotros a <a href="mailto:ventas@marcan.com.pe">ventas@marcan.com.pe</a>.',
        )),
        array('title' => 'Integridad del documento', 'paragraphs' => array(
            'Los anexos a la política de privacidad conforman parte integrante del presente documento; y, rigen de acuerdo a lo estipulado en cuanto le sea aplicable.',
        )),
    );
}

function marcan_get_privacy_body($post_id = 0): string
{
    return marcan_get_legal_body('privacy_body', $post_id, marcan_privacy_default_sections());
}

/**
 * Secciones por defecto de los Términos y condiciones (respaldo si el campo SCF está vacío).
 */
function marcan_terms_default_sections(): array
{
    return array(
        array('intro' => true, 'paragraphs' => array(
            'El presente documento establece los Términos y Condiciones de uso del sitio web www.marcan.com.pe (en adelante, "el Sitio Web"), propiedad de Inmobiliaria y Constructora Marcan S.A., identificada con RUC N° 20133793195, con domicilio en Avenida Santa Cruz N° 820, oficina 402, distrito de Miraflores, Lima, Perú.',
            'El acceso y uso del Sitio Web implica la aceptación plena y sin reservas de los presentes Términos y Condiciones. Si no está de acuerdo con los mismos, le solicitamos abstenerse de utilizar el Sitio Web.',
        )),
        array('title' => 'Uso del Sitio Web', 'paragraphs' => array(
            'El usuario se compromete a utilizar el Sitio Web de conformidad con la ley, la moral, las buenas costumbres y el orden público, así como con lo dispuesto en los presentes Términos y Condiciones.',
            'Queda prohibido el uso del Sitio Web con fines ilícitos, lesivos de derechos de terceros o que puedan dañar, inutilizar, sobrecargar o deteriorar el Sitio Web o impedir su normal utilización.',
        )),
        array('title' => 'Propiedad intelectual', 'paragraphs' => array(
            'Todos los contenidos del Sitio Web —incluyendo textos, fotografías, gráficos, imágenes, iconos, logotipos, marcas, diseños, software y demás elementos— son propiedad exclusiva de Inmobiliaria y Constructora Marcan S.A. o de terceros que han autorizado su uso, y están protegidos por la legislación peruana sobre propiedad intelectual.',
            'Queda prohibida la reproducción, distribución, comunicación pública, transformación o cualquier otra forma de explotación de los contenidos del Sitio Web sin la autorización previa y por escrito de Inmobiliaria y Constructora Marcan S.A.',
        )),
        array('title' => 'Información sobre los proyectos inmobiliarios', 'paragraphs' => array(
            'La información contenida en el Sitio Web respecto de los proyectos inmobiliarios —incluyendo precios, metrajes, planos, características, fechas de entrega y disponibilidad— es de carácter referencial y puede estar sujeta a modificaciones sin previo aviso.',
            'Las imágenes, renders, recorridos virtuales y demás representaciones visuales de los proyectos son de carácter ilustrativo y pueden diferir del producto final entregado.',
            'Los precios publicados no incluyen IGV ni gastos notariales o registrales, salvo indicación expresa en contrario.',
        )),
        array('title' => 'Exclusión de garantías y responsabilidad', 'paragraphs' => array(
            'Inmobiliaria y Constructora Marcan S.A. no garantiza la disponibilidad, continuidad o infalibilidad del funcionamiento del Sitio Web, ni la ausencia de virus u otros elementos dañinos en el mismo.',
            'Inmobiliaria y Constructora Marcan S.A. no será responsable por los daños y perjuicios de cualquier naturaleza que pudieran derivarse de la falta de disponibilidad o continuidad del funcionamiento del Sitio Web, de los fallos en el acceso a sus distintas páginas, ni de la transmisión de virus o elementos dañinos.',
        )),
        array('title' => 'Enlaces a terceros', 'paragraphs' => array(
            'El Sitio Web puede contener enlaces a sitios web de terceros. Estos enlaces se proporcionan únicamente para conveniencia del usuario. Inmobiliaria y Constructora Marcan S.A. no controla ni asume responsabilidad alguna por el contenido, las políticas de privacidad o las prácticas de dichos sitios web de terceros.',
        )),
        array('title' => 'Modificaciones', 'paragraphs' => array(
            'Inmobiliaria y Constructora Marcan S.A. se reserva el derecho de modificar en cualquier momento los presentes Términos y Condiciones. Las modificaciones serán publicadas en el Sitio Web y entrarán en vigor desde el momento de su publicación. Se recomienda al usuario revisar periódicamente los Términos y Condiciones.',
        )),
        array('title' => 'Legislación aplicable y jurisdicción', 'paragraphs' => array(
            'Los presentes Términos y Condiciones se rigen por la legislación peruana. Para cualquier controversia derivada del uso del Sitio Web, las partes se someten a la jurisdicción de los jueces y tribunales del distrito de Miraflores, Lima, Perú, renunciando a cualquier otro fuero que pudiera corresponderles.',
        )),
        array('title' => 'Contacto', 'paragraphs' => array(
            'Para cualquier consulta sobre los presentes Términos y Condiciones, puede contactarnos a través del formulario de contacto disponible en el Sitio Web o escribiendo a <a href="mailto:ventas@marcan.com.pe">ventas@marcan.com.pe</a>.',
        )),
    );
}

function marcan_get_terms_body($post_id = 0): string
{
    return marcan_get_legal_body('terms_body', $post_id, marcan_terms_default_sections());
}
