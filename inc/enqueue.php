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
    wp_enqueue_style('marcan-theme', marcan_asset_uri('css/theme.css'), array(), MARCAN_THEME_VERSION);
    wp_enqueue_script('marcan-theme', marcan_asset_uri('js/theme.js'), array(), MARCAN_THEME_VERSION, true);
}
add_action('wp_enqueue_scripts', 'marcan_enqueue_assets');
