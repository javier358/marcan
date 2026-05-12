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
