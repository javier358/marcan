<?php
/**
 * Iconic projects helpers.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

function marcan_get_iconic_project_field(int $post_id, string $field, string $fallback = ''): string
{
    if (function_exists('get_field')) {
        $value = get_field($field, $post_id);
        if (is_scalar($value) && (string) $value !== '') {
            return (string) $value;
        }
    }

    $value = get_post_meta($post_id, $field, true);
    if (is_scalar($value) && (string) $value !== '') {
        return (string) $value;
    }

    return $fallback;
}

function marcan_get_iconic_project_image_id(int $post_id, string $field, string $fallback_field = ''): int
{
    if (function_exists('get_field')) {
        $value = get_field($field, $post_id);
        if (is_numeric($value)) {
            return (int) $value;
        }
        if (is_array($value) && !empty($value['ID'])) {
            return (int) $value['ID'];
        }
    }

    $value = get_post_meta($post_id, $field, true);
    if (is_numeric($value)) {
        return (int) $value;
    }

    if ($fallback_field !== '') {
        return marcan_get_iconic_project_image_id($post_id, $fallback_field);
    }

    return 0;
}

function marcan_get_iconic_project_gallery(int $post_id, string $field = 'iconic_gallery'): array
{
    if (function_exists('get_field')) {
        $value = get_field($field, $post_id);
        if (is_array($value)) {
            return array_values(array_filter(array_map(static function ($item): int {
                if (is_numeric($item)) {
                    return (int) $item;
                }
                if (is_array($item) && !empty($item['ID'])) {
                    return (int) $item['ID'];
                }
                return 0;
            }, $value)));
        }
    }

    $raw = get_post_meta($post_id, $field, true);
    if (is_array($raw)) {
        return array_values(array_filter(array_map('intval', $raw)));
    }

    return array();
}

function marcan_find_iconic_project_by_name(string $name): ?WP_Post
{
    $slug = sanitize_title($name);
    $post = get_page_by_path($slug, OBJECT, 'iconic_project');

    if ($post instanceof WP_Post) {
        return $post;
    }

    $posts = get_posts(array(
        'post_type'      => 'iconic_project',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'title'          => $name,
        'fields'         => 'all',
    ));

    return !empty($posts) && $posts[0] instanceof WP_Post ? $posts[0] : null;
}

function marcan_get_iconic_project_permalink_by_name(string $name): string
{
    $post = marcan_find_iconic_project_by_name($name);

    return $post instanceof WP_Post ? get_permalink($post) : '';
}

function marcan_get_iconic_projects(int $exclude_id = 0): array
{
    $posts = get_posts(array(
        'post_type'      => 'iconic_project',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => array('menu_order' => 'ASC', 'date' => 'ASC'),
        'exclude'        => $exclude_id > 0 ? array($exclude_id) : array(),
    ));

    if (!empty($posts)) {
        return $posts;
    }

    return array();
}
