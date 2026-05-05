<?php
/**
 * Marcan theme setup and property management.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

define('MARCAN_THEME_VERSION', '0.1.0');

function marcan_setup(): void
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));
    add_theme_support('custom-logo', array(
        'height'      => 150,
        'width'       => 420,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    register_nav_menus(array(
        'primary' => __('Primary Menu', 'marcan'),
        'footer'  => __('Footer Menu', 'marcan'),
    ));
}
add_action('after_setup_theme', 'marcan_setup');

function marcan_enqueue_assets(): void
{
    wp_enqueue_style('marcan-theme', get_template_directory_uri() . '/assets/css/theme.css', array(), MARCAN_THEME_VERSION);
    wp_enqueue_script('marcan-theme', get_template_directory_uri() . '/assets/js/theme.js', array(), MARCAN_THEME_VERSION, true);
}
add_action('wp_enqueue_scripts', 'marcan_enqueue_assets');

function marcan_register_property_content(): void
{
    register_post_type('propiedad', array(
        'labels' => array(
            'name'               => __('Propiedades', 'marcan'),
            'singular_name'      => __('Propiedad', 'marcan'),
            'add_new_item'       => __('Agregar propiedad', 'marcan'),
            'edit_item'          => __('Editar propiedad', 'marcan'),
            'new_item'           => __('Nueva propiedad', 'marcan'),
            'view_item'          => __('Ver propiedad', 'marcan'),
            'search_items'       => __('Buscar propiedades', 'marcan'),
            'not_found'          => __('No se encontraron propiedades', 'marcan'),
            'menu_name'          => __('Propiedades', 'marcan'),
        ),
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => array('slug' => 'propiedades'),
        'menu_icon'    => 'dashicons-building',
        'supports'     => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest' => true,
    ));

    register_taxonomy('tipo_propiedad', 'propiedad', array(
        'labels' => array(
            'name'          => __('Tipos de propiedad', 'marcan'),
            'singular_name' => __('Tipo de propiedad', 'marcan'),
        ),
        'hierarchical'      => true,
        'show_admin_column' => true,
        'show_in_rest'     => true,
        'rewrite'          => array('slug' => 'tipo-propiedad'),
    ));

    register_taxonomy('ubicacion_propiedad', 'propiedad', array(
        'labels' => array(
            'name'          => __('Ubicaciones', 'marcan'),
            'singular_name' => __('Ubicación', 'marcan'),
        ),
        'hierarchical'      => true,
        'show_admin_column' => true,
        'show_in_rest'     => true,
        'rewrite'          => array('slug' => 'ubicacion'),
    ));
}
add_action('init', 'marcan_register_property_content');

function marcan_add_property_metaboxes(): void
{
    add_meta_box(
        'marcan_property_details',
        __('Datos de la propiedad', 'marcan'),
        'marcan_render_property_metabox',
        'propiedad',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'marcan_add_property_metaboxes');

function marcan_render_property_metabox(WP_Post $post): void
{
    wp_nonce_field('marcan_save_property_details', 'marcan_property_nonce');

    $fields = array(
        'price'       => __('Precio', 'marcan'),
        'area'        => __('Área', 'marcan'),
        'bedrooms'    => __('Dormitorios', 'marcan'),
        'bathrooms'   => __('Baños', 'marcan'),
        'parking'     => __('Estacionamientos', 'marcan'),
        'address'     => __('Dirección', 'marcan'),
        'cta_label'   => __('Texto del botón', 'marcan'),
        'cta_url'     => __('URL del botón', 'marcan'),
    );

    echo '<div class="marcan-admin-grid">';
    foreach ($fields as $key => $label) {
        $value = get_post_meta($post->ID, '_marcan_' . $key, true);
        $type = in_array($key, array('bedrooms', 'bathrooms', 'parking'), true) ? 'number' : 'text';
        if ('cta_url' === $key) {
            $type = 'url';
        }

        printf(
            '<p><label for="marcan_%1$s"><strong>%2$s</strong></label><input id="marcan_%1$s" name="marcan_%1$s" type="%3$s" value="%4$s" class="widefat"></p>',
            esc_attr($key),
            esc_html($label),
            esc_attr($type),
            esc_attr($value)
        );
    }
    echo '</div>';
}

function marcan_save_property_details(int $post_id): void
{
    if (!isset($_POST['marcan_property_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['marcan_property_nonce'])), 'marcan_save_property_details')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = array('price', 'area', 'bedrooms', 'bathrooms', 'parking', 'address', 'cta_label', 'cta_url');
    foreach ($fields as $field) {
        $posted = isset($_POST['marcan_' . $field]) ? wp_unslash($_POST['marcan_' . $field]) : '';
        $value = 'cta_url' === $field ? esc_url_raw($posted) : sanitize_text_field($posted);
        update_post_meta($post_id, '_marcan_' . $field, $value);
    }
}
add_action('save_post_propiedad', 'marcan_save_property_details');

function marcan_get_property_meta(int $post_id, string $key): string
{
    return (string) get_post_meta($post_id, '_marcan_' . $key, true);
}
