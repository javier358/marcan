<?php
/**
 * Quienes Somos settings and helpers.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

function marcan_get_about_page_id(): int
{
    $page = get_page_by_path('quienes-somos');

    return $page instanceof WP_Post ? (int) $page->ID : 0;
}

function marcan_register_about_field_group(): void
{
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    marcan_acf_add_local_field_group(array(
        'key' => 'group_marcan_about_page',
        'title' => 'Quienes Somos - Contenido',
        'fields' => array(
            marcan_acf_tab('field_marcan_tab_about_hero', '1. Hero'),
            array(
                'key' => 'field_marcan_about_hero_intro',
                'label' => 'Hero - texto principal',
                'name' => 'about_hero_intro',
                'type' => 'wysiwyg',
                'tabs' => 'all',
                'toolbar' => 'basic',
                'media_upload' => 0,
            ),
            marcan_hero_image_repeater('field_marcan_about_hero', 'about_hero_imagenes', 'Hero - imagenes'),
            marcan_acf_tab('field_marcan_tab_about_reasons', '2. Razones'),
            array(
                'key' => 'field_marcan_about_reasons_title',
                'label' => 'Razones - titulo',
                'name' => 'about_reasons_title',
                'type' => 'text',
            ),
            array(
                'key' => 'field_marcan_about_reasons_items',
                'label' => 'Razones - items',
                'name' => 'about_reasons_items',
                'type' => 'repeater',
                'layout' => 'row',
                'button_label' => 'Agregar razon',
                'sub_fields' => array(
                    array(
                        'key' => 'field_marcan_about_reason_number',
                        'label' => 'Numero',
                        'name' => 'number',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_marcan_about_reason_text',
                        'label' => 'Texto',
                        'name' => 'text',
                        'type' => 'wysiwyg',
                        'tabs' => 'all',
                        'toolbar' => 'basic',
                        'media_upload' => 0,
                    ),
                ),
            ),
            marcan_acf_tab('field_marcan_tab_about_iconic', '3. Proyectos Icónicos'),
            array(
                'key' => 'field_marcan_about_iconic_title',
                'label' => 'Icónicos - titulo',
                'name' => 'about_iconic_title',
                'type' => 'text',
            ),
            array(
                'key' => 'field_marcan_about_iconic_projects',
                'label' => 'Icónicos - proyectos',
                'name' => 'about_iconic_projects',
                'type' => 'repeater',
                'layout' => 'row',
                'button_label' => 'Agregar proyecto',
                'sub_fields' => array(
                    array(
                        'key' => 'field_marcan_about_iconic_name',
                        'label' => 'Nombre',
                        'name' => 'name',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_marcan_about_iconic_district',
                        'label' => 'Distrito',
                        'name' => 'district',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_marcan_about_iconic_year',
                        'label' => 'Año',
                        'name' => 'year',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_marcan_about_iconic_image_desktop',
                        'label' => 'Imagen desktop',
                        'name' => 'image_desktop',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'instructions' => marcan_acf_image_help('1320x1168 px', '350 KB'),
                    ),
            array(
                'key' => 'field_marcan_about_iconic_image_mobile',
                'label' => 'Imagen vertical',
                'name' => 'image_mobile',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'instructions' => marcan_acf_image_help('630x890 px', '280 KB'),
                    ),
                    array(
                        'key' => 'field_marcan_about_iconic_image_canson',
                        'label' => 'Imagen dibujo / Canson',
                        'name' => 'image_canson',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                        'library' => 'all',
                          'instructions' => marcan_acf_image_help('1627x1457 px', '450 KB') . ' Variante tipo dibujo visible inicialmente. En desktop cambia a la foto real al hacer hover; en mobile cambia cuando la card queda activa en el carrusel.',
                    ),
                ),
            ),
            array(
                'key' => 'field_marcan_about_timeline_arrow',
                'label' => 'Icónicos - flecha tarjeta activa',
                'name' => 'about_timeline_arrow',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'medium',
                'library' => 'all',
                'instructions' => marcan_acf_image_help('SVG sugerido, 102x102 px', '80 KB'),
            ),
            marcan_acf_tab('field_marcan_tab_about_promise', '4. Promesa'),
            array(
                'key' => 'field_marcan_about_promise_image_desktop',
                'label' => 'Promesa - imagen desktop',
                'name' => 'about_promise_image_desktop',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'large',
                'library' => 'all',
                'instructions' => marcan_acf_image_help('3150x1414 px', '550 KB'),
            ),
            array(
                'key' => 'field_marcan_about_promise_image_mobile',
                'label' => 'Promesa - imagen vertical',
                'name' => 'about_promise_image_mobile',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'large',
                'library' => 'all',
                'instructions' => marcan_acf_image_help('900x1200 px', '350 KB'),
            ),
            array(
                'key' => 'field_marcan_about_promise_title',
                'label' => 'Promesa - titulo',
                'name' => 'about_promise_title',
                'type' => 'text',
            ),
            array(
                'key' => 'field_marcan_about_promise_text',
                'label' => 'Promesa - texto',
                'name' => 'about_promise_text',
                'type' => 'wysiwyg',
                'tabs' => 'all',
                'toolbar' => 'basic',
                'media_upload' => 0,
            ),
            marcan_acf_tab('field_marcan_tab_about_awards', '5. Premios'),
            array(
                'key' => 'field_marcan_about_awards_title',
                'label' => 'Premios - titulo',
                'name' => 'about_awards_title',
                'type' => 'text',
            ),
            array(
                'key' => 'field_marcan_about_awards',
                'label' => 'Premios - items',
                'name' => 'about_awards',
                'type' => 'repeater',
                'layout' => 'row',
                'button_label' => 'Agregar premio',
                'sub_fields' => array(
                    array(
                        'key' => 'field_marcan_about_award_logo',
                        'label' => 'Logo',
                        'name' => 'logo',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'instructions' => marcan_acf_image_help('SVG sugerido, 112x124 px', '120 KB'),
                    ),
                    array(
                        'key' => 'field_marcan_about_award_year',
                        'label' => 'Año',
                        'name' => 'year',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_marcan_about_award_title',
                        'label' => 'Titulo',
                        'name' => 'title',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_marcan_about_award_text',
                        'label' => 'Texto',
                        'name' => 'text',
                        'type' => 'wysiwyg',
                        'tabs' => 'all',
                        'toolbar' => 'basic',
                        'media_upload' => 0,
                    ),
                    array(
                        'key' => 'field_marcan_about_award_link_label',
                        'label' => 'Etiqueta enlace',
                        'name' => 'link_label',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_marcan_about_award_link_url',
                        'label' => 'Enlace',
                        'name' => 'link_url',
                        'type' => 'url',
                    ),
                ),
            ),
            marcan_acf_tab('field_marcan_tab_about_team', '6. Equipo'),
            array(
                'key' => 'field_marcan_about_team_title',
                'label' => 'Equipo - titulo',
                'name' => 'about_team_title',
                'type' => 'text',
            ),
            array(
                'key' => 'field_marcan_about_team_members',
                'label' => 'Equipo - miembros',
                'name' => 'about_team_members',
                'type' => 'repeater',
                'layout' => 'row',
                'button_label' => 'Agregar miembro',
                'sub_fields' => array(
                    array(
                        'key' => 'field_marcan_about_team_name',
                        'label' => 'Nombre',
                        'name' => 'name',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_marcan_about_team_role',
                        'label' => 'Cargo',
                        'name' => 'role',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_marcan_about_team_image_desktop',
                        'label' => 'Imagen desktop',
                        'name' => 'image_desktop',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'instructions' => marcan_acf_image_help('600x760 px', '300 KB'),
                    ),
                    array(
                        'key' => 'field_marcan_about_team_image_mobile',
                        'label' => 'Imagen vertical',
                        'name' => 'image_mobile',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'instructions' => marcan_acf_image_help('600x760 px', '300 KB'),
                    ),
                    array(
                        'key' => 'field_marcan_about_team_linkedin',
                        'label' => 'LinkedIn',
                        'name' => 'linkedin',
                        'type' => 'url',
                        'instructions' => 'Opcional. Debe ser una URL completa, por ejemplo https://www.linkedin.com/in/nombre/. Si no hay enlace, dejar vacio.',
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array('param' => 'page_template', 'operator' => '==', 'value' => 'page-quienes-somos.php'),
            ),
        ),
        'menu_order' => 0,
        'active' => true,
    ));
}
add_action('acf/init', 'marcan_register_about_field_group');

function marcan_about_resolve_image($value, string $fallback = ''): string
{
    if (is_array($value) && !empty($value['url'])) {
        return (string) $value['url'];
    }

    if (is_numeric($value)) {
        $url = wp_get_attachment_url((int) $value);
        if (is_string($url) && $url !== '') {
            return $url;
        }
    }

    if (is_string($value) && $value !== '') {
        return $value;
    }

    return $fallback;
}

function marcan_about_resolve_link($value, string $fallback_url = '', string $fallback_label = ''): array
{
    if (is_array($value) && !empty($value['url'])) {
        return array(
            'url'    => (string) $value['url'],
            'title'  => isset($value['title']) && is_string($value['title']) && $value['title'] !== '' ? (string) $value['title'] : $fallback_label,
            'target' => isset($value['target']) && is_string($value['target']) && $value['target'] !== '' ? (string) $value['target'] : '_self',
        );
    }

    return array(
        'url'    => $fallback_url,
        'title'  => $fallback_label,
        'target' => '_self',
    );
}

function marcan_get_about_settings(): array
{
    $defaults = array(
        'hero_intro' => '',
        'hero_intro_font_size' => array(),
        'hero_images' => array(),
        'reasons_title' => '',
        'reasons_title_font_size' => array(),
        'reasons' => array(),
        'iconic_title' => '',
        'iconic_title_font_size' => array(),
        'iconic_projects' => array(),
        'timeline_arrow' => '',
        'promise_image_desktop' => '',
        'promise_image_mobile' => '',
        'promise_title' => '',
        'promise_title_font_size' => array(),
        'promise_text' => '',
        'promise_text_font_size' => array(),
        'awards_title' => '',
        'awards_title_font_size' => array(),
        'awards' => array(),
        'team_title' => '',
        'team_title_font_size' => array(),
        'team_members' => array(),
    );

    $page = marcan_get_about_page_id();

    if (!function_exists('get_field') || !$page) {
        return $defaults;
    }

    $as_text = static function (string $field) use ($page): string {
        $value = get_field($field, $page);
        return is_scalar($value) ? (string) $value : '';
    };

    $hero_intro = $as_text('about_hero_intro');
    $reasons_title = $as_text('about_reasons_title');
    $iconic_title = $as_text('about_iconic_title');
    $promise_title = $as_text('about_promise_title');
    $promise_text = $as_text('about_promise_text');
    $awards_title = $as_text('about_awards_title');
    $team_title = $as_text('about_team_title');

    $reasons = get_field('about_reasons_items', $page);
    $iconic_projects = get_field('about_iconic_projects', $page);
    $awards = get_field('about_awards', $page);
    $team_members = get_field('about_team_members', $page);

    $has_any_value = static function (array $item, array $keys): bool {
        foreach ($keys as $key) {
            if (!empty($item[$key])) {
                return true;
            }
        }

        return false;
    };

    $reasons = is_array($reasons)
        ? array_values(array_filter($reasons, static fn($item) => is_array($item) && $has_any_value($item, array('number', 'text'))))
        : array();

    $iconic_projects = is_array($iconic_projects)
        ? array_values(array_filter($iconic_projects, static fn($item) => is_array($item) && $has_any_value($item, array('name', 'district', 'year', 'image_desktop', 'image_mobile', 'image_canson'))))
        : array();

    $awards = is_array($awards)
        ? array_values(array_filter($awards, static fn($item) => is_array($item) && $has_any_value($item, array('logo', 'year', 'title', 'text', 'link_label', 'link_url'))))
        : array();

    $team_members = is_array($team_members)
        ? array_values(array_filter($team_members, static fn($item) => is_array($item) && $has_any_value($item, array('name', 'role', 'image_desktop', 'image_mobile', 'linkedin'))))
        : array();

    $hero_images = get_field('about_hero_imagenes', $page);
    $hero_images = is_array($hero_images) ? $hero_images : array();

    return array(
        'hero_intro' => $hero_intro,
        'hero_intro_font_size' => marcan_get_field_font_size('about_hero_intro', $page),
        'hero_images' => $hero_images,
        'reasons_title' => $reasons_title,
        'reasons_title_font_size' => marcan_get_field_font_size('about_reasons_title', $page),
        'reasons' => $reasons,
        'iconic_title' => $iconic_title,
        'iconic_title_font_size' => marcan_get_field_font_size('about_iconic_title', $page),
        'iconic_projects' => $iconic_projects,
        'timeline_arrow' => marcan_about_resolve_image(get_field('about_timeline_arrow', $page), $defaults['timeline_arrow']),
        'promise_image_desktop' => marcan_about_resolve_image(get_field('about_promise_image_desktop', $page), $defaults['promise_image_desktop']),
        'promise_image_mobile' => marcan_about_resolve_image(get_field('about_promise_image_mobile', $page), $defaults['promise_image_mobile']),
        'promise_title' => $promise_title,
        'promise_title_font_size' => marcan_get_field_font_size('about_promise_title', $page),
        'promise_text' => $promise_text,
        'promise_text_font_size' => marcan_get_field_font_size('about_promise_text', $page),
        'awards_title' => $awards_title,
        'awards_title_font_size' => marcan_get_field_font_size('about_awards_title', $page),
        'awards' => $awards,
        'team_title' => $team_title,
        'team_title_font_size' => marcan_get_field_font_size('about_team_title', $page),
        'team_members' => $team_members,
    );
}
