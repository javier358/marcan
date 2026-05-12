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
}
add_action('wp_enqueue_scripts', 'marcan_enqueue_assets');
