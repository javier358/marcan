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
            'name'          => __('Propiedades', 'marcan'),
            'singular_name' => __('Propiedad', 'marcan'),
            'menu_name'     => __('Propiedades', 'marcan'),
            'add_new_item'  => __('Agregar propiedad', 'marcan'),
            'edit_item'     => __('Editar propiedad', 'marcan'),
        ),
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => array('slug' => 'propiedades'),
        'menu_icon'    => 'dashicons-building',
        'supports'     => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest' => true,
    ));

    register_post_type('project', array(
        'labels' => array(
            'name'          => __('Proyectos', 'marcan'),
            'singular_name' => __('Proyecto', 'marcan'),
            'menu_name'     => __('Proyectos', 'marcan'),
            'add_new_item'  => __('Agregar proyecto', 'marcan'),
            'edit_item'     => __('Editar proyecto', 'marcan'),
        ),
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => array('slug' => 'proyectos'),
        'menu_icon'    => 'dashicons-admin-multisite',
        'supports'     => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest' => true,
    ));

    register_taxonomy('property_type', array('property', 'project'), array(
        'labels' => array(
            'name'          => __('Tipos de inmueble', 'marcan'),
            'singular_name' => __('Tipo de inmueble', 'marcan'),
        ),
        'hierarchical'      => true,
        'show_admin_column' => true,
        'show_in_rest'     => true,
        'rewrite'          => array('slug' => 'tipo-inmueble'),
    ));

    register_taxonomy('district', array('property', 'project'), array(
        'labels' => array(
            'name'          => __('Distritos', 'marcan'),
            'singular_name' => __('Distrito', 'marcan'),
        ),
        'hierarchical'      => true,
        'show_admin_column' => true,
        'show_in_rest'     => true,
        'rewrite'          => array('slug' => 'distrito'),
    ));
}
add_action('init', 'marcan_register_content_types');
