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

function marcan_get_option_color(string $field_name, string $fallback = ''): string
{
    $value = marcan_get_option_field($field_name, $fallback);

    return is_string($value) && $value !== '' ? $value : $fallback;
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

function marcan_get_home_hero_settings(): array
{
    $defaults = array(
        'mobile_copy' => 'Somos una inmobiliaria enfocada en hacer proyectos que impulsan el desarrollo urbano de Lima, inspirados en las verdaderas necesidades de las personas y de la ciudad.',
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

    $mobile_copy = get_field('hero_mobile_copy', $page_id);
    $autoplay = get_field('hero_autoplay', $page_id);
    $interval = get_field('hero_interval', $page_id);
    $effect = get_field('hero_effect', $page_id);

    return array(
        'mobile_copy' => is_string($mobile_copy) && $mobile_copy !== '' ? $mobile_copy : $defaults['mobile_copy'],
        'autoplay'    => $autoplay === null ? $defaults['autoplay'] : (bool) $autoplay,
        'interval'    => is_numeric($interval) ? max(1000, (int) $interval) : $defaults['interval'],
        'effect'      => is_string($effect) && $effect !== '' ? $effect : $defaults['effect'],
    );
}

function marcan_get_home_projects_settings(): array
{
    $defaults = array(
        'intro_title'                 => 'Tenemos una manera diferente de hacer las cosas',
        'intro_copy'                  => 'Somos una inmobiliaria enfocada en hacer proyectos que impulsan el desarrollo urbano de Lima, inspirados en las verdaderas necesidades de las personas y de la ciudad.',
        'intro_button_label'          => 'Conoce más sobre nosotros',
        'intro_button_url'            => home_url('/'),
        'departments_title'           => 'Departamentos en venta',
        'departments_button_label'     => 'Ver más departamentos',
        'departments_button_url'       => home_url('/'),
        'offices_title'               => 'Oficinas en venta',
        'offices_button_label'        => 'Ver más oficinas',
        'offices_button_url'          => home_url('/'),
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

    $resolve_link = static function ($link, string $fallback): string {
        if (is_array($link) && !empty($link['url'])) {
            return (string) $link['url'];
        }

        return $fallback;
    };

    return array(
        'intro_title'             => (string) (get_field('home_intro_title', $page_id) ?: $defaults['intro_title']),
        'intro_copy'              => (string) (get_field('home_intro_copy', $page_id) ?: $defaults['intro_copy']),
        'intro_button_label'      => (string) (get_field('home_intro_button_label', $page_id) ?: $defaults['intro_button_label']),
        'intro_button_url'        => $resolve_link($intro_button, $defaults['intro_button_url']),
        'departments_title'       => (string) (get_field('home_departments_title', $page_id) ?: $defaults['departments_title']),
        'departments_button_label' => (string) (get_field('home_departments_button_label', $page_id) ?: $defaults['departments_button_label']),
        'departments_button_url'  => $resolve_link($departments_button, $defaults['departments_button_url']),
        'offices_title'           => (string) (get_field('home_offices_title', $page_id) ?: $defaults['offices_title']),
        'offices_button_label'    => (string) (get_field('home_offices_button_label', $page_id) ?: $defaults['offices_button_label']),
        'offices_button_url'      => $resolve_link($offices_button, $defaults['offices_button_url']),
    );
}

function marcan_get_home_delivered_settings(): array
{
    $defaults = array(
        'title'               => 'Nuestros proyectos entregados hablan por nosotros',
        'button_label'        => 'Conoce más sobre nosotros',
        'button_url'          => home_url('/'),
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

    $page_id = marcan_get_front_page_id();

    if (!$page_id) {
        return $defaults;
    }

    $button = get_field('home_delivered_button', $page_id);

    $button_url = $defaults['button_url'];
    if (is_array($button) && !empty($button['url'])) {
        $button_url = (string) $button['url'];
    }

    return array(
        'title'             => (string) (get_field('home_delivered_title', $page_id) ?: $defaults['title']),
        'button_label'      => (string) (get_field('home_delivered_button_label', $page_id) ?: $defaults['button_label']),
        'button_url'        => $button_url,
        'image_desktop_id'  => (int) (get_field('home_delivered_image_desktop', $page_id) ?: $defaults['image_desktop_id']),
        'image_mobile_id'   => (int) (get_field('home_delivered_image_mobile', $page_id) ?: $defaults['image_mobile_id']),
        'background_color'  => (string) (get_field('home_delivered_background_color', $page_id) ?: $defaults['background_color']),
        'text_color'        => (string) (get_field('home_delivered_text_color', $page_id) ?: $defaults['text_color']),
        'button_bg_color'   => (string) (get_field('home_delivered_button_background', $page_id) ?: $defaults['button_bg_color']),
        'button_text_color' => (string) (get_field('home_delivered_button_text_color', $page_id) ?: $defaults['button_text_color']),
    );
}

function marcan_get_project_sections(string $section): WP_Query
{
    return new WP_Query(array(
        'post_type'      => 'project',
        'posts_per_page' => -1,
        'orderby'        => array('menu_order' => 'ASC', 'date' => 'ASC'),
        'post_status'    => 'publish',
        'meta_query'     => array(
            array(
                'key'     => 'home_section',
                'value'   => $section,
                'compare' => '=',
            ),
        ),
    ));
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

function marcan_get_hero_slides(): WP_Query
{
    return new WP_Query(array(
        'post_type'      => 'hero_slide',
        'posts_per_page' => -1,
        'orderby'        => array('menu_order' => 'ASC', 'date' => 'ASC'),
        'post_status'    => 'publish',
    ));
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
