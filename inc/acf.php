<?php
/**
 * SCF/ACF integration.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

function marcan_acf_json_save_point(string $path): string
{
    return MARCAN_THEME_PATH . 'acf-json';
}
add_filter('acf/settings/save_json', 'marcan_acf_json_save_point');

function marcan_acf_json_load_point(array $paths): array
{
    $paths[] = MARCAN_THEME_PATH . 'acf-json';
    return $paths;
}
add_filter('acf/settings/load_json', 'marcan_acf_json_load_point');

function marcan_register_field_groups(): void
{
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_marcan_property_data',
        'title' => 'Datos del inmueble',
        'fields' => array(
            array('key' => 'field_marcan_commercial_title', 'label' => 'Título comercial', 'name' => 'titulo_comercial', 'type' => 'text'),
            array('key' => 'field_marcan_subtitle', 'label' => 'Subtítulo', 'name' => 'subtitulo', 'type' => 'text'),
            array('key' => 'field_marcan_price', 'label' => 'Precio', 'name' => 'precio', 'type' => 'text'),
            array('key' => 'field_marcan_currency', 'label' => 'Moneda', 'name' => 'moneda', 'type' => 'select', 'choices' => array('S/' => 'S/', 'US$' => 'US$')),
            array('key' => 'field_marcan_location', 'label' => 'Ubicación', 'name' => 'ubicacion', 'type' => 'text'),
            array('key' => 'field_marcan_area', 'label' => 'Área', 'name' => 'area', 'type' => 'text'),
            array('key' => 'field_marcan_bedrooms', 'label' => 'Dormitorios', 'name' => 'dormitorios', 'type' => 'number'),
            array('key' => 'field_marcan_bathrooms', 'label' => 'Baños', 'name' => 'banos', 'type' => 'number'),
            array('key' => 'field_marcan_parking', 'label' => 'Estacionamientos', 'name' => 'estacionamientos', 'type' => 'number'),
            array('key' => 'field_marcan_status', 'label' => 'Estado', 'name' => 'estado', 'type' => 'text'),
            array('key' => 'field_marcan_gallery', 'label' => 'Galería', 'name' => 'galeria', 'type' => 'gallery'),
            array('key' => 'field_marcan_video', 'label' => 'Video', 'name' => 'video', 'type' => 'url'),
            array('key' => 'field_marcan_map', 'label' => 'Mapa', 'name' => 'mapa', 'type' => 'textarea'),
            array('key' => 'field_marcan_amenities', 'label' => 'Amenities', 'name' => 'amenities', 'type' => 'textarea'),
            array('key' => 'field_marcan_specs', 'label' => 'Ficha técnica', 'name' => 'ficha_tecnica', 'type' => 'textarea'),
            array('key' => 'field_marcan_documents', 'label' => 'Documentos descargables', 'name' => 'documentos', 'type' => 'file'),
            array('key' => 'field_marcan_cta', 'label' => 'CTA / contacto', 'name' => 'cta_contacto', 'type' => 'link'),
        ),
        'location' => array(
            array(
                array('param' => 'post_type', 'operator' => '==', 'value' => 'property'),
            ),
        ),
        'active' => true,
    ));

    acf_add_local_field_group(array(
        'key' => 'group_marcan_home_projects_settings',
        'title' => 'Home - Proyectos',
        'fields' => array(
            array(
                'key' => 'field_marcan_home_intro_title',
                'label' => 'Título introductorio',
                'name' => 'home_intro_title',
                'type' => 'text',
            ),
            array(
                'key' => 'field_marcan_home_intro_copy',
                'label' => 'Texto introductorio',
                'name' => 'home_intro_copy',
                'type' => 'textarea',
                'rows' => 4,
                'new_lines' => 'wpautop',
            ),
            array(
                'key' => 'field_marcan_home_intro_button_label',
                'label' => 'Etiqueta botón introductorio',
                'name' => 'home_intro_button_label',
                'type' => 'text',
            ),
            array(
                'key' => 'field_marcan_home_intro_button',
                'label' => 'Enlace botón introductorio',
                'name' => 'home_intro_button',
                'type' => 'link',
            ),
            array(
                'key' => 'field_marcan_home_departments_title',
                'label' => 'Título departamentos',
                'name' => 'home_departments_title',
                'type' => 'text',
            ),
            array(
                'key' => 'field_marcan_home_departments_button_label',
                'label' => 'Etiqueta botón departamentos',
                'name' => 'home_departments_button_label',
                'type' => 'text',
            ),
            array(
                'key' => 'field_marcan_home_departments_button',
                'label' => 'Enlace botón departamentos',
                'name' => 'home_departments_button',
                'type' => 'link',
            ),
            array(
                'key' => 'field_marcan_home_offices_title',
                'label' => 'Título oficinas',
                'name' => 'home_offices_title',
                'type' => 'text',
            ),
            array(
                'key' => 'field_marcan_home_offices_button_label',
                'label' => 'Etiqueta botón oficinas',
                'name' => 'home_offices_button_label',
                'type' => 'text',
            ),
            array(
                'key' => 'field_marcan_home_offices_button',
                'label' => 'Enlace botón oficinas',
                'name' => 'home_offices_button',
                'type' => 'link',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_type',
                    'operator' => '==',
                    'value' => 'front_page',
                ),
            ),
        ),
        'active' => true,
    ));

    acf_add_local_field_group(array(
        'key' => 'group_marcan_project_home_card',
        'title' => 'Tarjeta home de proyecto',
        'fields' => array(
            array(
                'key' => 'field_marcan_project_home_section',
                'label' => 'Sección home',
                'name' => 'home_section',
                'type' => 'select',
                'choices' => array(
                    'departamentos' => 'Departamentos',
                    'oficinas' => 'Oficinas',
                ),
                'default_value' => 'departamentos',
                'ui' => 1,
                'return_format' => 'value',
            ),
            array(
                'key' => 'field_marcan_project_home_badge',
                'label' => 'Etiqueta',
                'name' => 'home_badge_label',
                'type' => 'text',
            ),
            array(
                'key' => 'field_marcan_project_home_location',
                'label' => 'Ubicación',
                'name' => 'home_location',
                'type' => 'text',
            ),
            array(
                'key' => 'field_marcan_project_home_price_label',
                'label' => 'Etiqueta de precio',
                'name' => 'home_price_label',
                'type' => 'text',
                'default_value' => 'Desde:',
            ),
            array(
                'key' => 'field_marcan_project_home_price',
                'label' => 'Precio',
                'name' => 'home_price',
                'type' => 'text',
            ),
            array(
                'key' => 'field_marcan_project_home_bedrooms',
                'label' => 'Dormitorios',
                'name' => 'home_bedrooms_text',
                'type' => 'text',
            ),
            array(
                'key' => 'field_marcan_project_home_area',
                'label' => 'Metros',
                'name' => 'home_area_text',
                'type' => 'text',
            ),
            array(
                'key' => 'field_marcan_project_home_cta_label',
                'label' => 'Etiqueta CTA',
                'name' => 'home_cta_label',
                'type' => 'text',
                'default_value' => 'Ver más',
            ),
            array(
                'key' => 'field_marcan_project_home_cta_link',
                'label' => 'Enlace CTA',
                'name' => 'home_cta_link',
                'type' => 'link',
            ),
            array(
                'key' => 'field_marcan_project_home_desktop_image',
                'label' => 'Imagen desktop',
                'name' => 'home_desktop_image',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'medium_large',
                'library' => 'all',
            ),
            array(
                'key' => 'field_marcan_project_home_mobile_image',
                'label' => 'Imagen móvil',
                'name' => 'home_mobile_image',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'medium_large',
                'library' => 'all',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'project',
                ),
            ),
        ),
        'active' => true,
    ));

    acf_add_local_field_group(array(
        'key' => 'group_marcan_home_hero_settings',
        'title' => 'Hero de inicio',
        'fields' => array(
            array(
                'key' => 'field_marcan_hero_mobile_copy',
                'label' => 'Texto móvil',
                'name' => 'hero_mobile_copy',
                'type' => 'textarea',
                'rows' => 4,
                'new_lines' => 'wpautop',
            ),
            array(
                'key' => 'field_marcan_hero_autoplay',
                'label' => 'Autoplay',
                'name' => 'hero_autoplay',
                'type' => 'true_false',
                'ui' => 1,
                'default_value' => 1,
            ),
            array(
                'key' => 'field_marcan_hero_interval',
                'label' => 'Intervalo',
                'name' => 'hero_interval',
                'type' => 'number',
                'default_value' => 5000,
                'min' => 1000,
                'step' => 500,
            ),
            array(
                'key' => 'field_marcan_hero_effect',
                'label' => 'Efecto',
                'name' => 'hero_effect',
                'type' => 'select',
                'choices' => array(
                    'fade' => 'Fade',
                    'zoom' => 'Zoom',
                ),
                'default_value' => 'fade',
                'return_format' => 'value',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_type',
                    'operator' => '==',
                    'value' => 'front_page',
                ),
            ),
        ),
        'active' => true,
    ));

    acf_add_local_field_group(array(
        'key' => 'group_marcan_hero_slide',
        'title' => 'Slide del hero',
        'fields' => array(
            array(
                'key' => 'field_marcan_hero_desktop_image',
                'label' => 'Imagen desktop',
                'name' => 'imagen_desktop',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'large',
                'library' => 'all',
            ),
            array(
                'key' => 'field_marcan_hero_mobile_image',
                'label' => 'Imagen móvil',
                'name' => 'imagen_movil',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'large',
                'library' => 'all',
            ),
            array(
                'key' => 'field_marcan_hero_slide_label',
                'label' => 'Etiqueta',
                'name' => 'etiqueta',
                'type' => 'text',
            ),
            array(
                'key' => 'field_marcan_hero_slide_link',
                'label' => 'Enlace',
                'name' => 'enlace',
                'type' => 'link',
            ),
            array(
                'key' => 'field_marcan_hero_slide_duration',
                'label' => 'Duración',
                'name' => 'duracion',
                'type' => 'number',
                'default_value' => 5000,
                'min' => 1000,
                'step' => 500,
            ),
            array(
                'key' => 'field_marcan_hero_slide_effect',
                'label' => 'Efecto de transición',
                'name' => 'efecto_transicion',
                'type' => 'select',
                'choices' => array(
                    'fade' => 'Fade',
                    'zoom' => 'Zoom',
                ),
                'default_value' => 'fade',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'hero_slide',
                ),
            ),
        ),
        'active' => true,
    ));
}
add_action('acf/init', 'marcan_register_field_groups');
