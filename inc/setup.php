<?php
/**
 * Theme support and menus.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

function marcan_setup(): void
{
    load_theme_textdomain('marcan', MARCAN_THEME_PATH . 'languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));
    add_theme_support('custom-logo', array(
        'height'      => 22,
        'width'       => 110,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    register_nav_menus(array(
        'primary' => __('Menú principal', 'marcan'),
        'footer'  => __('Menú footer', 'marcan'),
    ));
}
add_action('after_setup_theme', 'marcan_setup');
