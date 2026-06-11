<?php
/**
 * Custom post types and taxonomies.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

function marcan_register_content_types(): void
{
    register_post_type('property', array(
        'labels' => array(
            'name'          => __('Departamentos y oficinas', 'marcan'),
            'singular_name' => __('Inmueble', 'marcan'),
            'menu_name'     => __('Departamentos y oficinas', 'marcan'),
            'add_new_item'  => __('Agregar inmueble', 'marcan'),
            'edit_item'     => __('Editar inmueble', 'marcan'),
        ),
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => array('slug' => 'propiedades'),
        'menu_icon'    => 'dashicons-building',
        'supports'     => array('title', 'thumbnail', 'page-attributes'),
        'show_in_rest' => true,
    ));

    register_post_type('hero_slide', array(
        'labels' => array(
            'name'          => __('Slides del hero', 'marcan'),
            'singular_name' => __('Slide del hero', 'marcan'),
            'menu_name'     => __('Hero home', 'marcan'),
            'add_new_item'  => __('Agregar slide', 'marcan'),
            'edit_item'     => __('Editar slide', 'marcan'),
        ),
        'public'              => false,
        'show_ui'             => false,
        'show_in_menu'        => false,
        'show_in_rest'        => true,
        'exclude_from_search' => true,
        'has_archive'         => false,
        'rewrite'             => false,
        'menu_icon'           => 'dashicons-images-alt2',
        'supports'            => array('title', 'thumbnail', 'excerpt', 'page-attributes'),
    ));

    register_post_type('iconic_project', array(
        'labels' => array(
            'name'          => __('Proyectos icónicos', 'marcan'),
            'singular_name' => __('Proyecto icónico', 'marcan'),
            'menu_name'     => __('Proyectos icónicos', 'marcan'),
            'add_new_item'  => __('Agregar proyecto icónico', 'marcan'),
            'edit_item'     => __('Editar proyecto icónico', 'marcan'),
        ),
        'public'       => true,
        'has_archive'  => false,
        'rewrite'      => array('slug' => 'proyectos-iconicos', 'with_front' => false),
        'menu_icon'    => 'dashicons-building',
        'supports'     => array('title', 'thumbnail', 'page-attributes'),
        'show_in_rest' => true,
    ));

    register_taxonomy('property_type', array('property'), array(
        'labels' => array(
            'name'          => __('Tipos de inmueble', 'marcan'),
            'singular_name' => __('Tipo de inmueble', 'marcan'),
        ),
        'hierarchical'      => true,
        'show_ui'           => false,
        'show_admin_column' => false,
        'show_in_menu'      => false,
        'show_in_rest'      => true,
        'rewrite'           => array('slug' => 'tipo-inmueble'),
    ));

    register_taxonomy('property_category', array('property'), array(
        'labels' => array(
            'name'          => __('Categoria comercial', 'marcan'),
            'singular_name' => __('Categoria comercial', 'marcan'),
        ),
        'hierarchical'      => true,
        'show_ui'           => false,
        'show_admin_column' => false,
        'show_in_menu'      => false,
        'show_in_rest'      => true,
        'rewrite'           => array('slug' => 'categoria-inmueble'),
    ));

    register_taxonomy('district', array('property'), array(
        'labels' => array(
            'name'          => __('Distritos', 'marcan'),
            'singular_name' => __('Distrito', 'marcan'),
        ),
        'hierarchical'      => true,
        'show_ui'           => false,
        'show_admin_column' => false,
        'show_in_menu'      => false,
        'show_in_rest'      => true,
        'rewrite'           => array('slug' => 'distrito'),
    ));
}
add_action('init', 'marcan_register_content_types');

function marcan_maybe_flush_rewrites(): void
{
    $version = '20260603-iconic-projects';

    if (get_option('marcan_rewrite_version') === $version) {
        return;
    }

    flush_rewrite_rules(false);
    update_option('marcan_rewrite_version', $version);
}
add_action('init', 'marcan_maybe_flush_rewrites', 20);
