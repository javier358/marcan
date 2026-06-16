<?php
/**
 * Front-end assets.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

function marcan_enqueue_assets(): void
{
    $css_path = MARCAN_THEME_PATH . 'assets/css/theme.css';
    $js_path = MARCAN_THEME_PATH . 'assets/js/theme.js';
    $css_version = file_exists($css_path) ? (string) filemtime($css_path) : MARCAN_THEME_VERSION;
    $js_version = file_exists($js_path) ? (string) filemtime($js_path) : MARCAN_THEME_VERSION;

    wp_enqueue_style('marcan-theme', marcan_asset_uri('css/theme.css'), array(), $css_version);
    wp_enqueue_script('marcan-theme', marcan_asset_uri('js/theme.js'), array(), $js_version, true);

    $recaptcha_site_key = marcan_get_option_text('contact_recaptcha_site_key', '');
    if ($recaptcha_site_key !== '') {
        wp_enqueue_script(
            'google-recaptcha',
            'https://www.google.com/recaptcha/api.js?render=' . rawurlencode($recaptcha_site_key),
            array(),
            null,
            true
        );
    }

    wp_localize_script('marcan-theme', 'marcanContactForm', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('marcan_contact_form'),
        'recaptchaSiteKey' => $recaptcha_site_key,
    ));
}
add_action('wp_enqueue_scripts', 'marcan_enqueue_assets');

function marcan_preload_home_project_images(): void
{
    if (!is_front_page() || !function_exists('get_field')) {
        return;
    }

    $project_ids = get_posts(array(
        'post_type'      => 'property',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'orderby'        => array('menu_order' => 'ASC', 'date' => 'ASC'),
        'meta_query'     => array(
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

    foreach ($project_ids as $project_id) {
        $desktop_image_id = (int) (get_field('home_desktop_image', $project_id) ?: get_field('listado_hero_imagen', $project_id) ?: get_post_thumbnail_id($project_id));
        $mobile_image_id = (int) (get_field('home_mobile_image', $project_id) ?: $desktop_image_id);
        $desktop_image_url = $desktop_image_id ? wp_get_attachment_image_url($desktop_image_id, 'full') : '';
        $mobile_image_url = $mobile_image_id ? wp_get_attachment_image_url($mobile_image_id, 'full') : '';

        if ($desktop_image_url !== '') {
            printf('<link rel="preload" as="image" href="%s" media="(min-width: 901px)">' . "\n", esc_url($desktop_image_url));
        }

        if ($mobile_image_url !== '') {
            printf('<link rel="preload" as="image" href="%s" media="(max-width: 900px)">' . "\n", esc_url($mobile_image_url));
        }
    }
}
add_action('wp_head', 'marcan_preload_home_project_images', 3);
