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

function marcan_register_options_page(): void
{
    if (!function_exists('acf_add_options_page')) {
        return;
    }

    acf_add_options_page(array(
        'page_title' => 'MARCAN Global',
        'menu_title' => 'MARCAN Global',
        'menu_slug'  => 'marcan-global-settings',
        'capability' => 'edit_theme_options',
        'redirect'   => false,
    ));
}
add_action('acf/init', 'marcan_register_options_page');

function marcan_register_field_groups(): void
{
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_marcan_global_header',
        'title' => 'Global - Header',
        'fields' => array(
            array('key' => 'field_marcan_header_logo_desktop', 'label' => 'Logo desktop', 'name' => 'header_logo_desktop', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all'),
            array('key' => 'field_marcan_header_logo_mobile', 'label' => 'Logo movil', 'name' => 'header_logo_mobile', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all'),
            array('key' => 'field_marcan_header_menu_label', 'label' => 'Etiqueta menu', 'name' => 'header_menu_label', 'type' => 'text', 'default_value' => 'MENU'),
            array('key' => 'field_marcan_header_background_color', 'label' => 'Color fondo', 'name' => 'header_background_color', 'type' => 'text', 'default_value' => 'rgba(234,234,232,0.72)'),
            array('key' => 'field_marcan_header_text_color', 'label' => 'Color texto', 'name' => 'header_text_color', 'type' => 'text', 'default_value' => '#4f4f4f'),
            array('key' => 'field_marcan_header_blur_amount', 'label' => 'Blur', 'name' => 'header_blur_amount', 'type' => 'number', 'default_value' => 70, 'min' => 0, 'step' => 1),
            array('key' => 'field_marcan_header_dropdown_background_color', 'label' => 'Color dropdown', 'name' => 'header_dropdown_background_color', 'type' => 'text', 'default_value' => 'rgba(234,234,232,0.72)'),
        ),
        'location' => array(
            array(
                array('param' => 'options_page', 'operator' => '==', 'value' => 'marcan-global-settings'),
            ),
        ),
        'active' => true,
    ));

    acf_add_local_field_group(array(
        'key' => 'group_marcan_global_footer',
        'title' => 'Global - Footer',
        'fields' => array(
            array('key' => 'field_marcan_footer_background_color', 'label' => 'Color fondo', 'name' => 'footer_background_color', 'type' => 'text', 'default_value' => '#ffcb05'),
            array('key' => 'field_marcan_footer_text_color', 'label' => 'Color texto', 'name' => 'footer_text_color', 'type' => 'text', 'default_value' => '#4f4f4f'),
            array('key' => 'field_marcan_footer_projects_title', 'label' => 'Titulo proyectos', 'name' => 'footer_projects_title', 'type' => 'text', 'default_value' => 'Proyectos actuales'),
            array('key' => 'field_marcan_footer_company_title', 'label' => 'Titulo quienes somos', 'name' => 'footer_company_title', 'type' => 'text', 'default_value' => 'Quiénes somos'),
            array('key' => 'field_marcan_footer_member_title', 'label' => 'Titulo miembros', 'name' => 'footer_member_title', 'type' => 'text', 'default_value' => 'Miembro de'),
            array('key' => 'field_marcan_footer_brand_logo_desktop', 'label' => 'Marca desktop', 'name' => 'footer_brand_logo_desktop', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all'),
            array('key' => 'field_marcan_footer_brand_logo_mobile', 'label' => 'Marca movil', 'name' => 'footer_brand_logo_mobile', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all'),
            array('key' => 'field_marcan_footer_arrow_desktop', 'label' => 'Flecha desktop', 'name' => 'footer_arrow_desktop', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all'),
            array('key' => 'field_marcan_footer_arrow_mobile', 'label' => 'Flecha movil', 'name' => 'footer_arrow_mobile', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all'),
            array('key' => 'field_marcan_footer_social_1', 'label' => 'Social 1', 'name' => 'footer_social_1', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all'),
            array('key' => 'field_marcan_footer_social_2', 'label' => 'Social 2', 'name' => 'footer_social_2', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all'),
            array('key' => 'field_marcan_footer_social_3', 'label' => 'Social 3', 'name' => 'footer_social_3', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all'),
            array('key' => 'field_marcan_footer_social_4', 'label' => 'Social 4', 'name' => 'footer_social_4', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all'),
            array('key' => 'field_marcan_footer_member_1', 'label' => 'Miembro 1', 'name' => 'footer_member_1', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all'),
            array('key' => 'field_marcan_footer_member_2', 'label' => 'Miembro 2', 'name' => 'footer_member_2', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all'),
            array('key' => 'field_marcan_footer_member_3', 'label' => 'Miembro 3', 'name' => 'footer_member_3', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all'),
            array('key' => 'field_marcan_footer_address', 'label' => 'Direccion', 'name' => 'footer_address', 'type' => 'text'),
            array('key' => 'field_marcan_footer_phone_lines', 'label' => 'Telefonos', 'name' => 'footer_phone_lines', 'type' => 'textarea', 'rows' => 2),
            array('key' => 'field_marcan_footer_email', 'label' => 'Correo', 'name' => 'footer_email', 'type' => 'text'),
            array('key' => 'field_marcan_footer_legal_text', 'label' => 'Texto legal', 'name' => 'footer_legal_text', 'type' => 'text', 'default_value' => 'Términos & Condiciones | Política de Privacidad | © 2025 Marcan Ingenieros'),
            array('key' => 'field_marcan_footer_projects_button_label', 'label' => 'CTA proyectos', 'name' => 'footer_projects_button_label', 'type' => 'text', 'default_value' => 'Ver Proyectos'),
            array('key' => 'field_marcan_footer_projects_button', 'label' => 'Enlace CTA proyectos', 'name' => 'footer_projects_button', 'type' => 'link'),
        ),
        'location' => array(
            array(
                array('param' => 'options_page', 'operator' => '==', 'value' => 'marcan-global-settings'),
            ),
        ),
        'active' => true,
    ));

    acf_add_local_field_group(array(
        'key' => 'group_marcan_home_delivered_projects',
        'title' => 'Home - Proyectos entregados',
        'fields' => array(
            array('key' => 'field_marcan_home_delivered_title', 'label' => 'Titulo', 'name' => 'home_delivered_title', 'type' => 'text'),
            array('key' => 'field_marcan_home_delivered_button_label', 'label' => 'Etiqueta boton', 'name' => 'home_delivered_button_label', 'type' => 'text'),
            array('key' => 'field_marcan_home_delivered_button', 'label' => 'Enlace boton', 'name' => 'home_delivered_button', 'type' => 'link'),
            array('key' => 'field_marcan_home_delivered_image_desktop', 'label' => 'Imagen desktop', 'name' => 'home_delivered_image_desktop', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'large', 'library' => 'all'),
            array('key' => 'field_marcan_home_delivered_image_mobile', 'label' => 'Imagen movil', 'name' => 'home_delivered_image_mobile', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'large', 'library' => 'all'),
            array('key' => 'field_marcan_home_delivered_background_color', 'label' => 'Color fondo', 'name' => 'home_delivered_background_color', 'type' => 'text', 'default_value' => '#f3f2f1'),
            array('key' => 'field_marcan_home_delivered_text_color', 'label' => 'Color texto', 'name' => 'home_delivered_text_color', 'type' => 'text', 'default_value' => '#4f4f4f'),
            array('key' => 'field_marcan_home_delivered_button_background', 'label' => 'Color boton', 'name' => 'home_delivered_button_background', 'type' => 'text', 'default_value' => '#4f4f4f'),
            array('key' => 'field_marcan_home_delivered_button_text_color', 'label' => 'Color texto boton', 'name' => 'home_delivered_button_text_color', 'type' => 'text', 'default_value' => '#fbfafa'),
        ),
        'location' => array(
            array(
                array('param' => 'page_type', 'operator' => '==', 'value' => 'front_page'),
            ),
        ),
        'active' => true,
    ));

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
            array('key' => 'field_marcan_detail_hero_desktop', 'label' => 'Detalle - hero desktop', 'name' => 'detalle_hero_desktop', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'large', 'library' => 'all'),
            array('key' => 'field_marcan_detail_hero_mobile', 'label' => 'Detalle - hero movil', 'name' => 'detalle_hero_movil', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'large', 'library' => 'all'),
            array('key' => 'field_marcan_short_description', 'label' => 'Descripcion corta', 'name' => 'descripcion_corta', 'type' => 'textarea', 'rows' => 3),
            array('key' => 'field_marcan_district_text', 'label' => 'Distrito', 'name' => 'distrito', 'type' => 'text'),
            array('key' => 'field_marcan_project_stage', 'label' => 'Etapa comercial', 'name' => 'etapa_comercial', 'type' => 'text'),
            array('key' => 'field_marcan_delivery_date', 'label' => 'Fecha de entrega', 'name' => 'fecha_entrega', 'type' => 'text'),
            array('key' => 'field_marcan_floorplans', 'label' => 'Planos / tipologias', 'name' => 'planos', 'type' => 'gallery', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all'),
            array('key' => 'field_marcan_brochure', 'label' => 'Brochure', 'name' => 'brochure', 'type' => 'file', 'return_format' => 'id', 'library' => 'all'),
            array('key' => 'field_marcan_contact_whatsapp', 'label' => 'WhatsApp contacto', 'name' => 'whatsapp_contacto', 'type' => 'text'),
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
            array(
                'key' => 'field_marcan_project_home_image_left',
                'label' => 'Imagen - posicion X (%)',
                'name' => 'home_image_left',
                'type' => 'number',
                'default_value' => 0,
                'step' => '0.01',
            ),
            array(
                'key' => 'field_marcan_project_home_image_top',
                'label' => 'Imagen - posicion Y (%)',
                'name' => 'home_image_top',
                'type' => 'number',
                'default_value' => 0,
                'step' => '0.01',
            ),
            array(
                'key' => 'field_marcan_project_home_image_width',
                'label' => 'Imagen - ancho (%)',
                'name' => 'home_image_width',
                'type' => 'number',
                'default_value' => 100,
                'step' => '0.01',
            ),
            array(
                'key' => 'field_marcan_project_home_image_height',
                'label' => 'Imagen - alto (%)',
                'name' => 'home_image_height',
                'type' => 'number',
                'default_value' => 100,
                'step' => '0.01',
            ),
            array(
                'key' => 'field_marcan_project_home_image_fit',
                'label' => 'Imagen - ajuste',
                'name' => 'home_image_fit',
                'type' => 'select',
                'choices' => array(
                    'cover' => 'Cover',
                    'fill' => 'Fill / Figma crop',
                ),
                'default_value' => 'cover',
                'return_format' => 'value',
                'ui' => 1,
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
