<?php
/**
 * Blog page settings and helpers.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

function marcan_get_blog_page_id(): int
{
    $page = get_page_by_path('blog');

    return $page instanceof WP_Post ? (int) $page->ID : 0;
}

function marcan_register_blog_field_group(): void
{
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    marcan_acf_add_local_field_group(array(
        'key' => 'group_marcan_blog_page',
        'title' => 'Blog - Contenido',
        'fields' => array(
            marcan_acf_tab('field_marcan_tab_blog_labels', '1. Etiquetas'),
            array('key' => 'field_marcan_blog_featured_label', 'label' => 'Hero - etiqueta', 'name' => 'blog_featured_label', 'type' => 'text', 'default_value' => 'Nuevo'),
            array('key' => 'field_marcan_blog_important_label', 'label' => 'Columna principal - etiqueta', 'name' => 'blog_important_label', 'type' => 'text', 'default_value' => 'Noticia importante'),
            array('key' => 'field_marcan_blog_all_label', 'label' => 'Columna lateral - etiqueta', 'name' => 'blog_all_label', 'type' => 'text', 'default_value' => 'Todas las publicaciones'),
            marcan_acf_tab('field_marcan_tab_blog_about', '2. Institucional'),
            array('key' => 'field_marcan_blog_about_image', 'label' => 'Institucional - imagen izquierda', 'name' => 'blog_about_image', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium_large', 'library' => 'all'),
            array('key' => 'field_marcan_blog_about_label', 'label' => 'Institucional - etiqueta izquierda', 'name' => 'blog_about_label', 'type' => 'text', 'default_value' => 'Sobre nosotros'),
            array('key' => 'field_marcan_blog_about_title', 'label' => 'Institucional - título izquierdo', 'name' => 'blog_about_title', 'type' => 'text', 'default_value' => 'Construimos confianza, creamos bienestar.'),
            array('key' => 'field_marcan_blog_about_text', 'label' => 'Institucional - texto izquierdo', 'name' => 'blog_about_text', 'type' => 'textarea', 'rows' => 4, 'default_value' => 'Diseñamos espacios pensados para que las personas vivan mejor y más felices.'),
            array('key' => 'field_marcan_blog_vision_image', 'label' => 'Institucional - imagen derecha', 'name' => 'blog_vision_image', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium_large', 'library' => 'all'),
            array('key' => 'field_marcan_blog_vision_label', 'label' => 'Institucional - etiqueta derecha', 'name' => 'blog_vision_label', 'type' => 'text', 'default_value' => 'Nuestra visión'),
            array('key' => 'field_marcan_blog_vision_title', 'label' => 'Institucional - título derecho', 'name' => 'blog_vision_title', 'type' => 'text', 'default_value' => 'Ser la inmobiliaria más confiable del país'),
            array('key' => 'field_marcan_blog_vision_text', 'label' => 'Institucional - texto derecho', 'name' => 'blog_vision_text', 'type' => 'textarea', 'rows' => 4, 'default_value' => 'Una empresa sólida, rentable y profesional, con un equipo íntegro, adaptable y enfocado en resultados.'),
            marcan_acf_tab('field_marcan_tab_blog_stats', '3. Cifras'),
            array('key' => 'field_marcan_blog_stats', 'label' => 'Cifras', 'name' => 'blog_stats', 'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Agregar cifra', 'sub_fields' => array(
                array('key' => 'field_marcan_blog_stat_number', 'label' => 'Número', 'name' => 'number', 'type' => 'text'),
                array('key' => 'field_marcan_blog_stat_label', 'label' => 'Texto', 'name' => 'label', 'type' => 'text'),
            )),
            marcan_acf_tab('field_marcan_tab_blog_cta', '4. CTA'),
            array('key' => 'field_marcan_blog_cta_title', 'label' => 'CTA - título', 'name' => 'blog_cta_title', 'type' => 'text', 'default_value' => 'Creamos proyectos que marcan'),
            array('key' => 'field_marcan_blog_cta_departments_label', 'label' => 'CTA - botón departamentos', 'name' => 'blog_cta_departments_label', 'type' => 'text', 'default_value' => 'Departamentos en venta'),
            array('key' => 'field_marcan_blog_cta_offices_label', 'label' => 'CTA - botón oficinas', 'name' => 'blog_cta_offices_label', 'type' => 'text', 'default_value' => 'Oficinas en venta'),
        ),
        'location' => array(
            array(
                array('param' => 'page', 'operator' => '==', 'value' => marcan_get_blog_page_id()),
            ),
        ),
        'active' => true,
    ));
}
add_action('acf/init', 'marcan_register_blog_field_group');

function marcan_get_blog_page_content(): array
{
    $page_id = marcan_get_blog_page_id();
    $get = static function (string $field, $fallback = '') use ($page_id) {
        $value = $page_id && function_exists('get_field') ? get_field($field, $page_id) : '';

        return $value !== '' && $value !== false && $value !== null ? $value : $fallback;
    };
    $stats = $get('blog_stats', array());

    if (!is_array($stats) || !$stats) {
        $stats = array(
            array('number' => '37', 'label' => 'años de experiencia'),
            array('number' => '9', 'label' => 'proyectos de oficinas boutique'),
            array('number' => '12', 'label' => 'proyectos de departamentos'),
        );
    }

    return array(
        'featured_label' => (string) $get('blog_featured_label', 'Nuevo'),
        'featured_label_font_size' => marcan_get_field_font_size('blog_featured_label', $page_id),
        'important_label' => (string) $get('blog_important_label', 'Noticia importante'),
        'important_label_font_size' => marcan_get_field_font_size('blog_important_label', $page_id),
        'all_label' => (string) $get('blog_all_label', 'Todas las publicaciones'),
        'all_label_font_size' => marcan_get_field_font_size('blog_all_label', $page_id),
        'about_image_id' => (int) $get('blog_about_image', 0),
        'about_label' => (string) $get('blog_about_label', 'Sobre nosotros'),
        'about_label_font_size' => marcan_get_field_font_size('blog_about_label', $page_id),
        'about_title' => (string) $get('blog_about_title', 'Construimos confianza, creamos bienestar.'),
        'about_title_font_size' => marcan_get_field_font_size('blog_about_title', $page_id),
        'about_text' => (string) $get('blog_about_text', 'Diseñamos espacios pensados para que las personas vivan mejor y más felices.'),
        'about_text_font_size' => marcan_get_field_font_size('blog_about_text', $page_id),
        'vision_image_id' => (int) $get('blog_vision_image', 0),
        'vision_label' => (string) $get('blog_vision_label', 'Nuestra visión'),
        'vision_label_font_size' => marcan_get_field_font_size('blog_vision_label', $page_id),
        'vision_title' => (string) $get('blog_vision_title', 'Ser la inmobiliaria más confiable del país'),
        'vision_title_font_size' => marcan_get_field_font_size('blog_vision_title', $page_id),
        'vision_text' => (string) $get('blog_vision_text', 'Una empresa sólida, rentable y profesional, con un equipo íntegro, adaptable y enfocado en resultados.'),
        'vision_text_font_size' => marcan_get_field_font_size('blog_vision_text', $page_id),
        'stats' => $stats,
        'cta_title' => (string) $get('blog_cta_title', 'Creamos proyectos que marcan'),
        'cta_title_font_size' => marcan_get_field_font_size('blog_cta_title', $page_id),
        'cta_departments_label' => (string) $get('blog_cta_departments_label', 'Departamentos en venta'),
        'cta_departments_label_font_size' => marcan_get_field_font_size('blog_cta_departments_label', $page_id),
        'cta_offices_label' => (string) $get('blog_cta_offices_label', 'Oficinas en venta'),
        'cta_offices_label_font_size' => marcan_get_field_font_size('blog_cta_offices_label', $page_id),
    );
}
