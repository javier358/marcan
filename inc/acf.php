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

/**
 * Helper para crear un campo de pestaña (tab) ACF.
 */
function marcan_acf_tab(string $key, string $label): array
{
    return array(
        'key' => $key,
        'label' => $label,
        'name' => '',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0,
    );
}

function marcan_acf_wysiwyg(string $key, string $label, string $name, string $toolbar = 'basic', int $media_upload = 0, string $instructions = ''): array
{
    return array(
        'key' => $key,
        'label' => $label,
        'name' => $name,
        'type' => 'wysiwyg',
        'tabs' => 'all',
        'toolbar' => $toolbar,
        'media_upload' => $media_upload,
        'delay' => 0,
        'instructions' => $instructions,
    );
}

function marcan_acf_image_help(string $size, string $max_weight = '350 KB'): string
{
    return sprintf(
        'Formato recomendado: WEBP. Tambien permite PNG y JPG. Medida exacta recomendada: %s. Peso maximo recomendado: %s.',
        $size,
        $max_weight
    );
}

/**
 * Catalogo fijo de tamanos de pantalla para los banners/heroes, ordenado de mayor
 * a menor. Cada entrada: device => [min-width, etiqueta, medida, peso maximo].
 * Fuente unica de verdad usada por el ACF y por el render del <picture>.
 */
function marcan_hero_devices(): array
{
    return array(
        'desktop_xl'       => array('min' => 1920, 'label' => 'Monitor grande', 'size' => '2560x1440 px', 'weight' => '650 KB'),
        'desktop'          => array('min' => 1366, 'label' => 'Desktop',        'size' => '1920x1246 px', 'weight' => '550 KB'),
        'laptop'           => array('min' => 1024, 'label' => 'Laptop',         'size' => '1366x854 px',  'weight' => '450 KB'),
        'tablet'           => array('min' => 768,  'label' => 'Tablet',         'size' => '1024x768 px',  'weight' => '400 KB'),
        'mobile_landscape' => array('min' => 480,  'label' => 'Movil horizontal','size' => '854x480 px',  'weight' => '350 KB'),
        'mobile_portrait'  => array('min' => 0,    'label' => 'Movil vertical', 'size' => '480x854 px',   'weight' => '350 KB'),
    );
}

/**
 * Devuelve los 6 campos de imagen FIJOS (uno por tamano de pantalla) para un banner.
 * Siempre presentes, ordenados de mayor a menor, cada uno etiquetado con su medida y
 * peso maximo, y vacios hasta que el cliente suba la imagen. Se reparten en columnas
 * (wrapper width) para ocupar poco espacio. El cliente no elige ni agrega nada.
 *
 * Usar con el operador spread dentro del array de campos:
 *   ...marcan_hero_image_fields('field_marcan_home_slide', 'hero_img'),
 *
 * @param string $key_prefix Prefijo unico de keys ACF (ej. 'field_marcan_home_slide').
 * @param string $base       Prefijo del nombre de los campos (ej. 'hero_img').
 *                           Cada campo se llama "{base}_{device}".
 */
function marcan_hero_image_fields(string $key_prefix, string $base): array
{
    $fields = array();
    foreach (marcan_hero_devices() as $device => $info) {
        $fields[] = array(
            'key' => $key_prefix . '_' . $device,
            'label' => $info['label'] . ' — ' . $info['size'],
            'name' => $base . '_' . $device,
            'type' => 'image',
            'return_format' => 'id',
            'preview_size' => 'medium',
            'library' => 'all',
            'instructions' => 'Medida ' . $info['size'] . ' · max ' . $info['weight'] . ' · WEBP (o PNG/JPG).',
            'wrapper' => array('width' => '33'),
        );
    }

    return $fields;
}

/**
 * Devuelve un repeater de imagenes responsive para heroes/banners.
 *
 * Mantiene las keys que ya fueron guardadas en acf-json y postmeta:
 * "{prefix}_hero_imgs", "{prefix}_hero_img_device" y "{prefix}_hero_img_file".
 */
function marcan_hero_image_repeater(string $key_prefix, string $name, string $label): array
{
    $choices = array();
    foreach (array_reverse(marcan_hero_devices(), true) as $device => $info) {
        $choices[$device] = sprintf(
            '%s · %s · max %s',
            $info['label'],
            $info['size'],
            $info['weight']
        );
    }

    return array(
        'key' => $key_prefix . '_hero_imgs',
        'label' => $label,
        'name' => $name,
        'type' => 'repeater',
        'layout' => 'table',
        'button_label' => 'Agregar imagen',
        'instructions' => 'Sube una imagen por cada tamano de pantalla. Cada fila indica la medida exacta, el peso maximo y el formato recomendados. Si dejas un tamano sin imagen, se usa la del tamano mas cercano disponible. Formato recomendado: WEBP (tambien PNG/JPG).',
        'max' => 6,
        'sub_fields' => array(
            array(
                'key' => $key_prefix . '_hero_img_device',
                'label' => 'Pantalla',
                'name' => 'dispositivo',
                'type' => 'select',
                'return_format' => 'value',
                'allow_null' => 0,
                'default_value' => 'desktop',
                'choices' => $choices,
            ),
            array(
                'key' => $key_prefix . '_hero_img_file',
                'label' => 'Imagen',
                'name' => 'imagen',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'thumbnail',
                'library' => 'all',
                'instructions' => 'Formato recomendado: WEBP. Tambien PNG o JPG.',
            ),
        ),
    );
}

function marcan_acf_promote_editorial_text_fields(array $field): array
{
    $type = (string) ($field['type'] ?? '');
    if ($type !== 'text' && $type !== 'textarea') {
        return $field;
    }

    $name = (string) ($field['name'] ?? '');
    $label = (string) ($field['label'] ?? '');
    $key = (string) ($field['key'] ?? '');

    if ($key === 'field_marcan_unit_status') {
        return $field;
    }

    // Proteger sub-campos de repeaters: siempre input simple, nunca wysiwyg
    $repeater_subfield_prefixes = array(
        'field_marcan_unit_', 'field_marcan_virtual_tour_',
        'field_marcan_map_nearby_', 'field_marcan_ofi_reason_',
        'field_marcan_home_hero_slide_', 'field_marcan_blog_stat_',
        'field_marcan_about_iconic_', 'field_marcan_about_reasons_',
        'field_marcan_about_award_', 'field_marcan_about_team_',
    );
    foreach ($repeater_subfield_prefixes as $prefix) {
        if (str_starts_with($key, $prefix)) {
            return $field;
        }
    }

    $haystack = strtolower(remove_accents($name . ' ' . $label));

    $technical_patterns = array(
        'url', 'link', 'email', 'correo', 'phone', 'telefono', 'whatsapp', 'color',
        'precio', 'price', 'area', 'metro', 'unidad', 'orden', 'order', 'slug',
        'codigo', 'code', 'numero', 'number', 'width', 'height', 'left', 'top',
        'recipient', 'destinatario', 'subject', 'asunto', 'css', 'hex', 'rgba',
    );

    foreach ($technical_patterns as $pattern) {
        if (str_contains($haystack, $pattern)) {
            return $field;
        }
    }

    $editorial_patterns = array(
        'titulo', 'title', 'subtitulo', 'subtitle', 'texto', 'text', 'copy',
        'etiqueta', 'label', 'badge', 'estado', 'ubicacion', 'location',
        'descripcion', 'description', 'heading', 'nombre', 'name', 'cargo',
        'role', 'autor', 'author', 'frase', 'quote', 'distrito', 'district',
        'fecha', 'date', 'anio', 'year', 'cta', 'dormitorios', 'bedrooms',
    );

    foreach ($editorial_patterns as $pattern) {
        if (str_contains($haystack, $pattern)) {
            $field['type'] = 'wysiwyg';
            $field['tabs'] = 'all';
            $field['toolbar'] = 'basic';
            $field['media_upload'] = 0;
            $field['delay'] = 0;
            return $field;
        }
    }

    return $field;
}
add_filter('acf/load_field', 'marcan_acf_promote_editorial_text_fields');

function marcan_acf_admin_validation_text(string $translation, string $text, string $domain): string
{
    if ($domain !== 'secure-custom-fields' && $domain !== 'acf') {
        return $translation;
    }

    if ($text === 'Validation failed') {
        return 'No se pudo guardar: revisa los campos marcados en rojo.';
    }

    if ($text === '%d fields require attention') {
        return '%d campos tienen datos incompletos o con formato incorrecto.';
    }

    if ($text === '1 field requires attention') {
        return '1 campo tiene datos incompletos o con formato incorrecto.';
    }

    if ($text === '%s value is required') {
        return 'El campo %s es obligatorio. Complétalo o elimina la fila si no se usará.';
    }

    return $translation;
}
add_filter('gettext', 'marcan_acf_admin_validation_text', 20, 3);

function marcan_acf_url_validation_text($valid, $value, array $field, string $input)
{
    if ($valid === true || $value === '' || $value === null) {
        return $valid;
    }

    return sprintf(
        'El campo "%s" debe tener una URL completa, por ejemplo https://marcan.com.pe/. Si no tienes un enlace, deja este campo vacío.',
        isset($field['label']) ? (string) $field['label'] : 'URL'
    );
}
add_filter('acf/validate_value/type=url', 'marcan_acf_url_validation_text', 20, 4);

function marcan_register_field_groups(): void
{
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    /* ───────────────────────── GLOBAL: HEADER + FOOTER (Options) ───────────────────────── */
    acf_add_local_field_group(array(
        'key' => 'group_marcan_global_header',
        'title' => 'Global - Header',
        'fields' => array(
            marcan_acf_tab('field_marcan_tab_header_logos', '1. Logos'),
            array('key' => 'field_marcan_header_logo_desktop', 'label' => 'Logo desktop', 'name' => 'header_logo_desktop', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all', 'instructions' => 'PNG transparente o SVG. Sugerido: 220×44 px.'),
            array('key' => 'field_marcan_header_logo_mobile', 'label' => 'Logo móvil', 'name' => 'header_logo_mobile', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all', 'instructions' => 'PNG transparente o SVG. Sugerido: 140×30 px.'),
            marcan_acf_tab('field_marcan_tab_header_style', '2. Estilo'),
            array('key' => 'field_marcan_header_menu_label', 'label' => 'Etiqueta menú', 'name' => 'header_menu_label', 'type' => 'text', 'default_value' => 'MENU', 'instructions' => 'Texto del botón que abre el menú.'),
            array('key' => 'field_marcan_header_background_color', 'label' => 'Color fondo', 'name' => 'header_background_color', 'type' => 'text', 'default_value' => 'rgba(255,255,255,0.74)', 'instructions' => 'Color CSS o rgba(). Ej: rgba(255,255,255,0.74).'),
            array('key' => 'field_marcan_header_text_color', 'label' => 'Color texto', 'name' => 'header_text_color', 'type' => 'text', 'default_value' => '#4f4f4f', 'instructions' => 'Color HEX. Ej: #4f4f4f.'),
            array('key' => 'field_marcan_header_blur_amount', 'label' => 'Blur', 'name' => 'header_blur_amount', 'type' => 'number', 'default_value' => 70, 'min' => 0, 'step' => 1, 'instructions' => 'Desenfoque del fondo en px.'),
            array('key' => 'field_marcan_header_dropdown_background_color', 'label' => 'Color dropdown', 'name' => 'header_dropdown_background_color', 'type' => 'text', 'default_value' => 'rgba(255,255,255,0.74)', 'instructions' => 'Fondo del menú desplegable. Color CSS o rgba().'),
        ),
        'location' => array(
            array(
                array('param' => 'options_page', 'operator' => '==', 'value' => 'marcan-global-settings'),
            ),
        ),
        'menu_order' => 0,
        'active' => true,
    ));

    acf_add_local_field_group(array(
        'key' => 'group_marcan_global_footer',
        'title' => 'Global - Footer',
        'fields' => array(
            marcan_acf_tab('field_marcan_tab_footer_style', 'Estilo'),
            array('key' => 'field_marcan_footer_background_color', 'label' => 'Color fondo', 'name' => 'footer_background_color', 'type' => 'text', 'default_value' => '#ffcb05', 'instructions' => 'Color HEX. Ej: #ffcb05.'),
            array('key' => 'field_marcan_footer_text_color', 'label' => 'Color texto', 'name' => 'footer_text_color', 'type' => 'text', 'default_value' => '#4f4f4f', 'instructions' => 'Color HEX. Ej: #4f4f4f.'),
            marcan_acf_tab('field_marcan_tab_footer_titles', 'Títulos'),
            array('key' => 'field_marcan_footer_projects_title', 'label' => 'Título proyectos', 'name' => 'footer_projects_title', 'type' => 'text', 'default_value' => 'Encuentra tu espacio'),
            array('key' => 'field_marcan_footer_company_title', 'label' => 'Título empresa', 'name' => 'footer_company_title', 'type' => 'text', 'default_value' => 'Conoce Marcan'),
            array('key' => 'field_marcan_footer_member_title', 'label' => 'Título miembros', 'name' => 'footer_member_title', 'type' => 'text', 'default_value' => 'Miembro de'),
            marcan_acf_tab('field_marcan_tab_footer_brand', 'Marca y flechas'),
            array('key' => 'field_marcan_footer_brand_logo_desktop', 'label' => 'Marca desktop', 'name' => 'footer_brand_logo_desktop', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all', 'instructions' => 'Logo/marca para footer. PNG o SVG.'),
            array('key' => 'field_marcan_footer_brand_logo_mobile', 'label' => 'Marca móvil', 'name' => 'footer_brand_logo_mobile', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all', 'instructions' => 'Versión móvil de la marca. PNG o SVG.'),
            array('key' => 'field_marcan_footer_arrow_desktop', 'label' => 'Flecha desktop', 'name' => 'footer_arrow_desktop', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all', 'instructions' => 'Ícono flecha. SVG recomendado.'),
            array('key' => 'field_marcan_footer_arrow_mobile', 'label' => 'Flecha móvil', 'name' => 'footer_arrow_mobile', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all', 'instructions' => 'Ícono flecha móvil. SVG recomendado.'),
            marcan_acf_tab('field_marcan_tab_footer_social', 'Redes sociales'),
            array('key' => 'field_marcan_footer_social_1', 'label' => 'Social 1', 'name' => 'footer_social_1', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all', 'instructions' => 'Ícono SVG, 24×24 px.'),
            array('key' => 'field_marcan_footer_social_2', 'label' => 'Social 2', 'name' => 'footer_social_2', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all', 'instructions' => 'Ícono SVG, 24×24 px.'),
            array('key' => 'field_marcan_footer_social_3', 'label' => 'Social 3', 'name' => 'footer_social_3', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all', 'instructions' => 'Ícono SVG, 24×24 px.'),
            array('key' => 'field_marcan_footer_social_4', 'label' => 'Social 4', 'name' => 'footer_social_4', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all', 'instructions' => 'Ícono SVG, 24×24 px.'),
            marcan_acf_tab('field_marcan_tab_footer_members', 'Miembros'),
            array('key' => 'field_marcan_footer_member_1', 'label' => 'Miembro 1', 'name' => 'footer_member_1', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all', 'instructions' => 'Logo de gremio/asociación. PNG o SVG.'),
            array('key' => 'field_marcan_footer_member_2', 'label' => 'Miembro 2', 'name' => 'footer_member_2', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all', 'instructions' => 'Logo de gremio/asociación. PNG o SVG.'),
            array('key' => 'field_marcan_footer_member_3', 'label' => 'Miembro 3', 'name' => 'footer_member_3', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all', 'instructions' => 'Logo de gremio/asociación. PNG o SVG.'),
            marcan_acf_tab('field_marcan_tab_footer_contact', 'Contacto y legal'),
            array('key' => 'field_marcan_footer_address', 'label' => 'Dirección', 'name' => 'footer_address', 'type' => 'text'),
            array('key' => 'field_marcan_footer_phone_lines', 'label' => 'Teléfonos', 'name' => 'footer_phone_lines', 'type' => 'textarea', 'rows' => 2, 'instructions' => 'Una línea por teléfono.'),
            array('key' => 'field_marcan_global_whatsapp_contact', 'label' => 'WhatsApp contacto', 'name' => 'whatsapp_contacto', 'type' => 'text', 'default_value' => '51919490440', 'instructions' => 'Formato internacional sin signos. Ejemplo: 51919490440'),
            array('key' => 'field_marcan_contact_form_recipients', 'label' => 'Destinatarios formulario', 'name' => 'contact_form_recipients', 'type' => 'textarea', 'rows' => 3, 'instructions' => 'Correos que recibirán cada envío. Separar por coma, punto y coma o salto de línea.'),
            array('key' => 'field_marcan_contact_form_subject', 'label' => 'Asunto formulario', 'name' => 'contact_form_subject', 'type' => 'text', 'default_value' => 'Nuevo contacto desde la web Marcan'),
            array('key' => 'field_marcan_contact_modal_address', 'label' => 'Dirección modal contacto', 'name' => 'contact_modal_address', 'type' => 'text', 'default_value' => 'Av. 28 de Julio 1150, Miraflores, Lima, Perú'),
            array('key' => 'field_marcan_contact_modal_title', 'label' => 'Modal contacto - titulo', 'name' => 'contact_modal_title', 'type' => 'text', 'default_value' => 'Conversemos'),
            marcan_acf_wysiwyg('field_marcan_contact_privacy_text', 'Consentimiento privacidad', 'contact_privacy_text', 'basic', 0, 'Texto legal mostrado junto al primer checkbox del formulario.'),
            marcan_acf_wysiwyg('field_marcan_contact_marketing_text', 'Consentimiento marketing', 'contact_marketing_text', 'basic', 0, 'Texto legal mostrado junto al segundo checkbox del formulario.'),
            array('key' => 'field_marcan_contact_sidebar_title', 'label' => 'Sidebar contacto - titulo', 'name' => 'contact_sidebar_title', 'type' => 'text', 'default_value' => 'Contáctanos ahora'),
            marcan_acf_wysiwyg('field_marcan_contact_sidebar_copy', 'Sidebar contacto - datos', 'contact_sidebar_copy', 'basic', 0, 'Datos de contacto del lateral. Permite saltos de línea, negritas y enlaces.'),
            array('key' => 'field_marcan_footer_email', 'label' => 'Correo', 'name' => 'footer_email', 'type' => 'text'),
            array('key' => 'field_marcan_footer_legal_text', 'label' => 'Texto legal', 'name' => 'footer_legal_text', 'type' => 'text', 'default_value' => 'Términos & Condiciones | Política de Privacidad | © 2026 Marcan Ingenieros'),
            array('key' => 'field_marcan_footer_projects_button_label', 'label' => 'CTA proyectos', 'name' => 'footer_projects_button_label', 'type' => 'text', 'default_value' => 'Ver Proyectos'),
            array('key' => 'field_marcan_footer_projects_button', 'label' => 'Enlace CTA proyectos', 'name' => 'footer_projects_button', 'type' => 'link'),
        ),
        'location' => array(
            array(
                array('param' => 'options_page', 'operator' => '==', 'value' => 'marcan-global-settings'),
            ),
        ),
        'menu_order' => 1,
        'active' => true,
    ));

    /* ───────────────────────── INICIO (Front page) ───────────────────────── */
    acf_add_local_field_group(array(
        'key' => 'group_marcan_global_typography',
        'title' => 'Global - Tipografia',
        'fields' => array(
            marcan_acf_tab('field_marcan_tab_typography_desktop', 'Desktop'),
            array('key' => 'field_marcan_type_title_desktop', 'label' => 'Títulos desktop', 'name' => 'type_title_desktop', 'type' => 'text', 'default_value' => '40px', 'instructions' => 'Tamaño en px para títulos principales (sistema heredado). Ej: 40px.'),
            array('key' => 'field_marcan_type_subtitle_desktop', 'label' => 'Subtítulos desktop', 'name' => 'type_subtitle_desktop', 'type' => 'text', 'default_value' => '24px', 'instructions' => 'Tamaño en px para subtítulos de sección (sistema heredado). Ej: 24px.'),
            array('key' => 'field_marcan_type_description_desktop', 'label' => 'Descripciones desktop', 'name' => 'type_description_desktop', 'type' => 'text', 'default_value' => '18px', 'instructions' => 'Tamaño CSS para párrafos y descripciones largas.'),
            marcan_acf_tab('field_marcan_tab_typography_tablet', 'Tablet'),
            array('key' => 'field_marcan_type_title_tablet', 'label' => 'Títulos tablet', 'name' => 'type_title_tablet', 'type' => 'text', 'default_value' => '40px'),
            array('key' => 'field_marcan_type_subtitle_tablet', 'label' => 'Subtitulos tablet', 'name' => 'type_subtitle_tablet', 'type' => 'text', 'default_value' => '30px'),
            array('key' => 'field_marcan_type_description_tablet', 'label' => 'Descripciones tablet', 'name' => 'type_description_tablet', 'type' => 'text', 'default_value' => '16px'),
            marcan_acf_tab('field_marcan_tab_typography_mobile', 'Mobile'),
            array('key' => 'field_marcan_type_title_mobile', 'label' => 'Títulos mobile', 'name' => 'type_title_mobile', 'type' => 'text', 'default_value' => '32px'),
            array('key' => 'field_marcan_type_subtitle_mobile', 'label' => 'Subtitulos mobile', 'name' => 'type_subtitle_mobile', 'type' => 'text', 'default_value' => '24px'),
            array('key' => 'field_marcan_type_description_mobile', 'label' => 'Descripciones mobile', 'name' => 'type_description_mobile', 'type' => 'text', 'default_value' => '15px'),
        ),
        'location' => array(
            array(
                array('param' => 'options_page', 'operator' => '==', 'value' => 'marcan-global-settings'),
            ),
        ),
        'menu_order' => 2,
        'active' => true,
    ));

    acf_add_local_field_group(array(
        'key' => 'group_marcan_home_typography',
        'title' => 'Global - Tipografia',
        'fields' => array(
            marcan_acf_tab('field_marcan_tab_mt_desktop', 'Escritorio'),
            array('key' => 'field_marcan_mt_titular_d', 'label' => 'Titular — Escritorio', 'name' => 'mt_titular_d', 'type' => 'text', 'default_value' => '40px', 'instructions' => 'Título grande serif del home: «Hacemos las cosas diferente» y el título de «Proyectos entregados». Vista escritorio. Ej: 40px.'),
            array('key' => 'field_marcan_mt_subtitulo_d', 'label' => 'Bajada / subtítulo — Escritorio', 'name' => 'mt_subtitulo_d', 'type' => 'text', 'default_value' => '24px', 'instructions' => 'Texto de introducción que va bajo el titular del home. Vista escritorio. Ej: 24px.'),
            array('key' => 'field_marcan_mt_seccion_d', 'label' => 'Título de sección — Escritorio', 'name' => 'mt_seccion_d', 'type' => 'text', 'default_value' => '26px', 'instructions' => 'Títulos «Departamentos en venta» y «Oficinas en venta». Vista escritorio. Ej: 26px.'),
            array('key' => 'field_marcan_mt_card_d', 'label' => 'Título de tarjeta — Escritorio', 'name' => 'mt_card_d', 'type' => 'text', 'default_value' => '25px', 'instructions' => 'Nombre del proyecto en las tarjetas (ej. «Llano Zapata 430»). Vista escritorio. Ej: 25px.'),
            array('key' => 'field_marcan_mt_precio_d', 'label' => 'Precio — Escritorio', 'name' => 'mt_precio_d', 'type' => 'text', 'default_value' => '25px', 'instructions' => 'Precio en las tarjetas (ej. «S/ 965,000»). Vista escritorio. Ej: 25px.'),
            array('key' => 'field_marcan_mt_datos_d', 'label' => 'Datos / características — Escritorio', 'name' => 'mt_datos_d', 'type' => 'text', 'default_value' => '21px', 'instructions' => 'Características de la tarjeta: dormitorios, m², etc. Vista escritorio. Ej: 21px.'),
            array('key' => 'field_marcan_mt_texto_d', 'label' => 'Texto / cuerpo — Escritorio', 'name' => 'mt_texto_d', 'type' => 'text', 'default_value' => '18px', 'instructions' => 'Párrafos de cuerpo largo (descripciones, blog, páginas legales). Vista escritorio. Ej: 18px.'),
            array('key' => 'field_marcan_mt_menu_d', 'label' => 'Botón MENÚ — Escritorio', 'name' => 'mt_menu_d', 'type' => 'text', 'default_value' => '20px', 'instructions' => 'Texto del botón «MENÚ» del encabezado. Vista escritorio. Ej: 20px.'),
            array('key' => 'field_marcan_mt_menuitem_d', 'label' => 'Ítems del menú — Escritorio', 'name' => 'mt_menuitem_d', 'type' => 'text', 'default_value' => '20px', 'instructions' => 'Enlaces del menú desplegable (Quiénes somos, Departamentos, etc.). Vista escritorio. Ej: 20px.'),
            marcan_acf_tab('field_marcan_tab_mt_mobile', 'Móvil'),
            array('key' => 'field_marcan_mt_titular_m', 'label' => 'Titular — Móvil', 'name' => 'mt_titular_m', 'type' => 'text', 'default_value' => '26px', 'instructions' => 'Título grande serif del home en celular. Ej: 26px.'),
            array('key' => 'field_marcan_mt_subtitulo_m', 'label' => 'Bajada / subtítulo — Móvil', 'name' => 'mt_subtitulo_m', 'type' => 'text', 'default_value' => '16px', 'instructions' => 'Texto de introducción del hero en celular. Ej: 16px.'),
            array('key' => 'field_marcan_mt_seccion_m', 'label' => 'Título de sección — Móvil', 'name' => 'mt_seccion_m', 'type' => 'text', 'default_value' => '21px', 'instructions' => 'Títulos «Departamentos en venta» / «Oficinas en venta» en celular. Ej: 21px.'),
            array('key' => 'field_marcan_mt_card_m', 'label' => 'Título de tarjeta — Móvil', 'name' => 'mt_card_m', 'type' => 'text', 'default_value' => '21px', 'instructions' => 'Nombre del proyecto en las tarjetas en celular. Ej: 21px.'),
            array('key' => 'field_marcan_mt_precio_m', 'label' => 'Precio — Móvil', 'name' => 'mt_precio_m', 'type' => 'text', 'default_value' => '21px', 'instructions' => 'Precio en las tarjetas en celular. Ej: 21px.'),
            array('key' => 'field_marcan_mt_datos_m', 'label' => 'Datos / características — Móvil', 'name' => 'mt_datos_m', 'type' => 'text', 'default_value' => '14px', 'instructions' => 'Características de la tarjeta (dormitorios, m²) en celular. Ej: 14px.'),
            array('key' => 'field_marcan_mt_texto_m', 'label' => 'Texto / cuerpo — Móvil', 'name' => 'mt_texto_m', 'type' => 'text', 'default_value' => '16px', 'instructions' => 'Párrafos de cuerpo largo en celular. Ej: 16px.'),
            array('key' => 'field_marcan_mt_menu_m', 'label' => 'Botón MENÚ — Móvil', 'name' => 'mt_menu_m', 'type' => 'text', 'default_value' => '18px', 'instructions' => 'Texto del botón «MENÚ» del encabezado en celular. Ej: 18px.'),
            array('key' => 'field_marcan_mt_menuitem_m', 'label' => 'Ítems del menú — Móvil', 'name' => 'mt_menuitem_m', 'type' => 'text', 'default_value' => '16px', 'instructions' => 'Enlaces del menú desplegable en celular. Ej: 16px.'),
        ),
        'location' => array(
            array(
                array('param' => 'options_page', 'operator' => '==', 'value' => 'marcan-global-settings'),
            ),
        ),
        'menu_order' => 3,
        'active' => true,
    ));

    acf_add_local_field_group(array(
        'key' => 'group_marcan_home_page',
        'title' => 'Inicio - Contenido',
        'fields' => array(
            marcan_acf_tab('field_marcan_tab_home_hero', '1. Hero'),
            array('key' => 'field_marcan_hero_mobile_copy', 'label' => 'Texto móvil', 'name' => 'hero_mobile_copy', 'type' => 'wysiwyg', 'tabs' => 'all', 'toolbar' => 'basic', 'media_upload' => 0, 'instructions' => 'Texto introductorio que aparece bajo el slider en móvil.'),
            array('key' => 'field_marcan_hero_autoplay', 'label' => 'Autoplay', 'name' => 'hero_autoplay', 'type' => 'true_false', 'ui' => 1, 'default_value' => 1, 'instructions' => 'Reproducir el slider automáticamente.'),
            array('key' => 'field_marcan_hero_interval', 'label' => 'Intervalo', 'name' => 'hero_interval', 'type' => 'number', 'default_value' => 5000, 'min' => 1000, 'step' => 500, 'instructions' => 'Milisegundos entre slides (1000 = 1 segundo).'),
            array('key' => 'field_marcan_hero_effect', 'label' => 'Efecto', 'name' => 'hero_effect', 'type' => 'select', 'choices' => array('fade' => 'Fade', 'zoom' => 'Zoom'), 'default_value' => 'fade', 'return_format' => 'value'),
            array('key' => 'field_marcan_home_hero_slides', 'label' => 'Slides del hero', 'name' => 'hero_slides', 'type' => 'repeater', 'layout' => 'row', 'button_label' => 'Agregar slide', 'sub_fields' => array(
                marcan_hero_image_repeater('field_marcan_home_slide', 'hero_imagenes', 'Imagenes del slide'),
                array('key' => 'field_marcan_home_hero_slide_label', 'label' => 'Etiqueta', 'name' => 'etiqueta', 'type' => 'text'),
                array('key' => 'field_marcan_home_hero_slide_link', 'label' => 'Enlace', 'name' => 'enlace', 'type' => 'link'),
                array('key' => 'field_marcan_home_hero_slide_duration', 'label' => 'Duración', 'name' => 'duracion', 'type' => 'number', 'default_value' => 5000, 'min' => 1000, 'step' => 500, 'instructions' => 'Milisegundos que dura este slide.'),
                array('key' => 'field_marcan_home_hero_slide_effect', 'label' => 'Efecto de transición', 'name' => 'efecto_transicion', 'type' => 'select', 'choices' => array('fade' => 'Fade', 'zoom' => 'Zoom'), 'default_value' => 'fade'),
            )),

            marcan_acf_tab('field_marcan_tab_home_projects', '2. Sección Proyectos'),
            array('key' => 'field_marcan_home_intro_title', 'label' => 'Título introductorio', 'name' => 'home_intro_title', 'type' => 'text'),
            array('key' => 'field_marcan_home_intro_mobile_title', 'label' => 'Título introductorio móvil', 'name' => 'home_intro_mobile_title', 'type' => 'text'),
            marcan_acf_wysiwyg('field_marcan_home_intro_copy', 'Texto introductorio', 'home_intro_copy'),
            array('key' => 'field_marcan_home_intro_button_label', 'label' => 'Etiqueta botón introductorio', 'name' => 'home_intro_button_label', 'type' => 'text'),
            array('key' => 'field_marcan_home_intro_mobile_button_label', 'label' => 'Etiqueta botón introductorio móvil', 'name' => 'home_intro_mobile_button_label', 'type' => 'text'),
            array('key' => 'field_marcan_home_intro_button', 'label' => 'Enlace botón introductorio', 'name' => 'home_intro_button', 'type' => 'link'),
            array('key' => 'field_marcan_home_departments_title', 'label' => 'Título departamentos', 'name' => 'home_departments_title', 'type' => 'text'),
            array('key' => 'field_marcan_home_departments_mobile_title', 'label' => 'Título departamentos móvil', 'name' => 'home_departments_mobile_title', 'type' => 'text'),
            array('key' => 'field_marcan_home_departments_button_label', 'label' => 'Etiqueta botón departamentos', 'name' => 'home_departments_button_label', 'type' => 'text'),
            array('key' => 'field_marcan_home_departments_mobile_button_label', 'label' => 'Etiqueta botón departamentos móvil', 'name' => 'home_departments_mobile_button_label', 'type' => 'text'),
            array('key' => 'field_marcan_home_departments_button', 'label' => 'Enlace botón departamentos', 'name' => 'home_departments_button', 'type' => 'link'),
            array('key' => 'field_marcan_home_offices_title', 'label' => 'Título oficinas', 'name' => 'home_offices_title', 'type' => 'text'),
            array('key' => 'field_marcan_home_offices_mobile_title', 'label' => 'Título oficinas móvil', 'name' => 'home_offices_mobile_title', 'type' => 'text'),
            array('key' => 'field_marcan_home_offices_button_label', 'label' => 'Etiqueta botón oficinas', 'name' => 'home_offices_button_label', 'type' => 'text'),
            array('key' => 'field_marcan_home_offices_mobile_button_label', 'label' => 'Etiqueta botón oficinas móvil', 'name' => 'home_offices_mobile_button_label', 'type' => 'text'),
            array('key' => 'field_marcan_home_offices_button', 'label' => 'Enlace botón oficinas', 'name' => 'home_offices_button', 'type' => 'link'),

            marcan_acf_tab('field_marcan_tab_home_delivered', '3. Proyectos Entregados'),
            array('key' => 'field_marcan_home_delivered_title', 'label' => 'Título', 'name' => 'home_delivered_title', 'type' => 'text'),
            array('key' => 'field_marcan_home_delivered_mobile_title', 'label' => 'Título móvil', 'name' => 'home_delivered_mobile_title', 'type' => 'text'),
            array('key' => 'field_marcan_home_delivered_button_label', 'label' => 'Etiqueta botón', 'name' => 'home_delivered_button_label', 'type' => 'text'),
            array('key' => 'field_marcan_home_delivered_mobile_button_label', 'label' => 'Etiqueta botón móvil', 'name' => 'home_delivered_mobile_button_label', 'type' => 'text'),
            array('key' => 'field_marcan_home_delivered_button', 'label' => 'Enlace botón', 'name' => 'home_delivered_button', 'type' => 'link'),
            array('key' => 'field_marcan_home_delivered_image_desktop', 'label' => 'Imagen desktop', 'name' => 'home_delivered_image_desktop', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'large', 'library' => 'all', 'instructions' => marcan_acf_image_help('1577x1563 px', '450 KB')),
            array('key' => 'field_marcan_home_delivered_image_mobile', 'label' => 'Imagen vertical', 'name' => 'home_delivered_image_mobile', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'large', 'library' => 'all', 'instructions' => marcan_acf_image_help('720x900 px', '300 KB')),
            array('key' => 'field_marcan_home_delivered_background_color', 'label' => 'Color fondo', 'name' => 'home_delivered_background_color', 'type' => 'text', 'default_value' => '#f3f2f1', 'instructions' => 'Color HEX.'),
            array('key' => 'field_marcan_home_delivered_text_color', 'label' => 'Color texto', 'name' => 'home_delivered_text_color', 'type' => 'text', 'default_value' => '#4f4f4f', 'instructions' => 'Color HEX.'),
            array('key' => 'field_marcan_home_delivered_button_background', 'label' => 'Color botón', 'name' => 'home_delivered_button_background', 'type' => 'text', 'default_value' => '#4f4f4f', 'instructions' => 'Color HEX.'),
            array('key' => 'field_marcan_home_delivered_button_text_color', 'label' => 'Color texto botón', 'name' => 'home_delivered_button_text_color', 'type' => 'text', 'default_value' => '#fbfafa', 'instructions' => 'Color HEX.'),
        ),
        'location' => array(
            array(
                array('param' => 'page_type', 'operator' => '==', 'value' => 'front_page'),
            ),
        ),
        'menu_order' => 0,
        'active' => true,
    ));

    /* ───────────────────────── LISTADO DEPARTAMENTOS (page-departamentos.php) ───────────────────────── */
    acf_add_local_field_group(array(
        'key' => 'group_marcan_listing_departamentos',
        'title' => 'Departamentos - Contenido',
        'fields' => array(
            marcan_acf_tab('field_marcan_tab_dep_hero', '1. Hero'),
            marcan_hero_image_repeater('field_marcan_dep_hero', 'listing_hero_imagenes', 'Imagenes del hero'),
            marcan_acf_tab('field_marcan_tab_dep_text', '2. Textos'),
            array('key' => 'field_marcan_dep_title', 'label' => 'Título', 'name' => 'listing_title', 'type' => 'text'),
            marcan_acf_wysiwyg('field_marcan_dep_intro', 'Texto introductorio', 'listing_intro'),
            array('key' => 'field_marcan_dep_search_title', 'label' => 'Título búsqueda', 'name' => 'listing_search_title', 'type' => 'text'),
            marcan_acf_wysiwyg('field_marcan_dep_search_copy', 'Texto busqueda', 'listing_search_copy'),
        ),
        'location' => array(
            array(
                array('param' => 'page_template', 'operator' => '==', 'value' => 'page-departamentos.php'),
            ),
        ),
        'menu_order' => 0,
        'active' => true,
    ));

    /* ───────────────────────── LISTADO OFICINAS (page-oficinas.php) ───────────────────────── */
    acf_add_local_field_group(array(
        'key' => 'group_marcan_listing_oficinas',
        'title' => 'Oficinas - Contenido',
        'fields' => array(
            marcan_acf_tab('field_marcan_ofi_tab_hero', '1. Hero'),
            marcan_hero_image_repeater('field_marcan_ofi_hero', 'listing_hero_imagenes', 'Imagenes del hero'),
            marcan_acf_tab('field_marcan_ofi_tab_text', '2. Textos'),
            array('key' => 'field_marcan_ofi_title', 'label' => 'Título', 'name' => 'listing_title', 'type' => 'text'),
            marcan_acf_wysiwyg('field_marcan_ofi_intro', 'Texto introductorio', 'listing_intro'),
            array('key' => 'field_marcan_ofi_search_title', 'label' => 'Título búsqueda', 'name' => 'listing_search_title', 'type' => 'text'),
            marcan_acf_wysiwyg('field_marcan_ofi_search_copy', 'Texto busqueda', 'listing_search_copy'),
            marcan_acf_tab('field_marcan_ofi_tab_reasons', '3. Razones para invertir'),
            array('key' => 'field_marcan_ofi_reasons_title', 'label' => 'Título razones', 'name' => 'listing_reasons_title', 'type' => 'text'),
            array('key' => 'field_marcan_ofi_reasons', 'label' => 'Razones', 'name' => 'listing_reasons', 'type' => 'repeater', 'layout' => 'row', 'button_label' => 'Agregar razón', 'sub_fields' => array(
                array('key' => 'field_marcan_ofi_reason_number', 'label' => 'Número', 'name' => 'number', 'type' => 'text'),
                marcan_acf_wysiwyg('field_marcan_ofi_reason_text', 'Texto', 'text'),
            )),
        ),
        'location' => array(
            array(
                array('param' => 'page_template', 'operator' => '==', 'value' => 'page-oficinas.php'),
            ),
        ),
        'menu_order' => 0,
        'active' => true,
    ));

    /* ───────────────────────── PROPIEDAD (CPT property) ───────────────────────── */
    acf_add_local_field_group(array(
        'key' => 'group_marcan_property_all',
        'title' => 'Datos del inmueble',
        'fields' => array(
            /* 1. Hero */
            marcan_acf_tab('field_marcan_tab_prop_media', '1. Hero'),
            marcan_hero_image_repeater('field_marcan_detail_hero', 'detalle_hero_imagenes', 'Detalle - imagenes del hero'),
            array('key' => 'field_marcan_detail_wide_image', 'label' => 'Detalle - imagen ancha', 'name' => 'detalle_imagen_ancha', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'large', 'library' => 'all', 'instructions' => marcan_acf_image_help('3025x1552 px', '650 KB')),
            array('key' => 'field_marcan_listing_hero_image', 'label' => 'Listado - imagen hero', 'name' => 'listado_hero_imagen', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'large', 'library' => 'all', 'instructions' => marcan_acf_image_help('2236x1357 px', '450 KB')),
            array('key' => 'field_marcan_project_home_desktop_image', 'label' => 'Imagen para tarjeta Home (desktop)', 'name' => 'home_desktop_image', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium_large', 'library' => 'all', 'instructions' => marcan_acf_image_help('2063x1432 px', '450 KB') . ' Si se deja vacio se usa la imagen de listado.'),
            array('key' => 'field_marcan_project_home_mobile_image', 'label' => 'Imagen para tarjeta Home (vertical)', 'name' => 'home_mobile_image', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium_large', 'library' => 'all', 'instructions' => marcan_acf_image_help('630x780 px', '280 KB') . ' Si se deja vacio se usa la imagen desktop.'),

            /* 2. Identidad */
            marcan_acf_tab('field_marcan_tab_prop_identity', '2. Identidad'),
            marcan_acf_wysiwyg('field_marcan_commercial_title', 'T\u00edtulo comercial', 'titulo_comercial', 'basic'),
            marcan_acf_wysiwyg('field_marcan_subtitle', 'Subt\u00edtulo', 'subtitulo', 'basic'),
            marcan_acf_wysiwyg('field_marcan_short_description', 'Descripci\u00f3n corta', 'descripcion_corta'),
            marcan_acf_wysiwyg('field_marcan_status', 'Estado', 'estado', 'basic'),
            marcan_acf_wysiwyg('field_marcan_delivery_date', 'Fecha de entrega', 'fecha_entrega', 'basic'),
            array('key' => 'field_marcan_property_kind', 'label' => 'Tipo de inmueble', 'name' => 'tipo_inmueble', 'type' => 'select', 'choices' => array('departamento' => 'Departamento', 'oficina' => 'Oficina'), 'default_value' => 'departamento', 'ui' => 1, 'return_format' => 'value'),
            array('key' => 'field_marcan_project_home_section', 'label' => 'Ubicaci\u00f3n en Home', 'name' => 'home_section', 'type' => 'select', 'choices' => array('departamentos' => 'Departamentos', 'oficinas' => 'Oficinas'), 'default_value' => 'departamentos', 'ui' => 1, 'return_format' => 'value', 'instructions' => 'En qu\u00e9 slider del Home aparece. Por defecto usa el Tipo de inmueble.'),
            array('key' => 'field_marcan_show_listing', 'label' => 'Mostrar en listados', 'name' => 'mostrar_en_listado', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            array('key' => 'field_marcan_featured_home', 'label' => 'Mostrar en Home', 'name' => 'destacado_home', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            array('key' => 'field_marcan_listing_order', 'label' => 'Orden de listado', 'name' => 'orden_listado', 'type' => 'number', 'default_value' => 0),

            /* 3. Ficha t\u00e9cnica */
            marcan_acf_tab('field_marcan_tab_prop_specs', '3. Ficha t\u00e9cnica'),
            array('key' => 'field_marcan_show_specs', 'label' => 'Mostrar secci\u00f3n', 'name' => 'mostrar_ficha_tecnica', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            array('key' => 'field_marcan_price', 'label' => 'Precio', 'name' => 'precio', 'type' => 'text'),
            marcan_acf_wysiwyg('field_marcan_location', 'Ubicaci\u00f3n', 'ubicacion', 'basic'),
            array('key' => 'field_marcan_area_number', 'label' => '\u00c1rea', 'name' => 'area_numero', 'type' => 'number', 'step' => '0.01'),
            array('key' => 'field_marcan_area_unit', 'label' => 'Unidad de \u00e1rea', 'name' => 'area_unidad', 'type' => 'select', 'choices' => array('m2' => 'm²', 'm lineal' => 'm lineal', 'ha' => 'ha'), 'default_value' => 'm2', 'ui' => 1, 'return_format' => 'value'),
            marcan_acf_wysiwyg('field_marcan_bedrooms', 'Dormitorios', 'dormitorios', 'basic'),
            array('key' => 'field_marcan_bathrooms', 'label' => 'Ba\u00f1os', 'name' => 'banos', 'type' => 'number'),
            array('key' => 'field_marcan_parking', 'label' => 'Estacionamientos', 'name' => 'estacionamientos', 'type' => 'number'),
            marcan_acf_wysiwyg('field_marcan_project_home_price_label', 'Etiqueta de precio', 'home_price_label', 'basic', 0, 'Texto antes del precio en la tarjeta Home. Ej: "Desde:".'),
            array('key' => 'field_marcan_brochure', 'label' => 'Brochure', 'name' => 'brochure', 'type' => 'file', 'return_format' => 'id', 'library' => 'all', 'instructions' => 'PDF del brochure.'),
            array('key' => 'field_marcan_show_brochure', 'label' => 'Mostrar bot\u00f3n brochure', 'name' => 'mostrar_brochure', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1),

            /* 4. Concepto */
            marcan_acf_tab('field_marcan_tab_prop_concept', '4. Concepto'),
            array('key' => 'field_marcan_show_concept', 'label' => 'Mostrar secci\u00f3n', 'name' => 'mostrar_concepto', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            marcan_acf_wysiwyg('field_marcan_concept_title', 'T\u00edtulo concepto', 'concepto_titulo', 'basic'),
            marcan_acf_wysiwyg('field_marcan_concept_text', 'Texto concepto', 'concepto_texto', 'full'),
            marcan_acf_wysiwyg('field_marcan_quote_text', 'Frase del proyecto', 'frase_proyecto'),
            marcan_acf_wysiwyg('field_marcan_quote_author', 'Autor frase', 'autor_frase', 'basic'),
            marcan_acf_wysiwyg('field_marcan_related_intro_text', 'Texto antes de relacionados', 'relacionados_intro_texto', 'basic', 0, 'Opcional. Si se deja vac\u00edo se usa el texto por defecto del tipo de inmueble.'),
            marcan_acf_wysiwyg('field_marcan_project_home_cta_label', 'Etiqueta CTA para Home', 'home_cta_label', 'basic', 0, 'Texto del bot\u00f3n en la tarjeta Home. Ej: "Ver m\u00e1s". Si se deja vac\u00edo se muestra "Ver m\u00e1s".'),
            array('key' => 'field_marcan_project_home_cta_link', 'label' => 'Enlace CTA para Home', 'name' => 'home_cta_link', 'type' => 'link', 'instructions' => 'Si se deja vac\u00edo enlaza a la p\u00e1gina de detalle del inmueble.'),

            /* 5. Diseñador */
            marcan_acf_tab('field_marcan_tab_prop_designer', '5. Diseñador'),
            array('key' => 'field_marcan_show_designer', 'label' => 'Mostrar sección', 'name' => 'mostrar_disenador', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            marcan_acf_wysiwyg('field_marcan_interior_designer_name', 'Nombre del diseñador', 'disenador_interiores_nombre', 'basic'),
            marcan_acf_wysiwyg('field_marcan_interior_designer_role', 'Cargo del diseñador', 'disenador_interiores_cargo', 'basic'),
            array('key' => 'field_marcan_interior_designer_photo', 'label' => 'Foto del diseñador', 'name' => 'disenador_interiores_foto', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all', 'instructions' => marcan_acf_image_help('600x600 px', '220 KB')),

            /* 6. Unidades */
            marcan_acf_tab('field_marcan_tab_prop_units', '6. Unidades'),
            array('key' => 'field_marcan_show_units', 'label' => 'Mostrar sección', 'name' => 'mostrar_unidades', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            array('key' => 'field_marcan_units', 'label' => 'Unidades', 'name' => 'unidades', 'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Agregar unidad', 'sub_fields' => array(
                array('key' => 'field_marcan_unit_code', 'label' => 'Nombre', 'name' => 'codigo', 'type' => 'text'),
                array('key' => 'field_marcan_unit_floor', 'label' => 'Piso', 'name' => 'piso', 'type' => 'text'),
                array('key' => 'field_marcan_unit_bedrooms', 'label' => 'Dormitorios', 'name' => 'habitaciones', 'type' => 'text'),
                array('key' => 'field_marcan_unit_bathrooms', 'label' => 'Baños', 'name' => 'banos', 'type' => 'text'),
                array('key' => 'field_marcan_unit_area', 'label' => 'Area Total', 'name' => 'area_m2', 'type' => 'number', 'step' => '0.01'),
                array('key' => 'field_marcan_unit_area_unit', 'label' => 'Medida', 'name' => 'area_unidad', 'type' => 'select', 'choices' => array('m2' => 'm²', 'm lineal' => 'm lineal', 'ha' => 'ha'), 'default_value' => 'm2', 'return_format' => 'value'),
                array('key' => 'field_marcan_unit_price', 'label' => 'Precio Actual', 'name' => 'precio', 'type' => 'text'),
                array('key' => 'field_marcan_unit_status', 'label' => 'Tipología', 'name' => 'estado', 'type' => 'text', 'instructions' => 'Excel: Tipología en departamentos; Tipo de Unidad en oficinas.'),
                array('key' => 'field_marcan_unit_plan', 'label' => 'Plano', 'name' => 'plano', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'thumbnail', 'library' => 'all', 'instructions' => marcan_acf_image_help('934x1027 px o 1867x1027 px', '350 KB')),
                array('key' => 'field_marcan_unit_quote_pdf', 'label' => 'PDF cotización', 'name' => 'cotizacion_pdf', 'type' => 'file', 'return_format' => 'id', 'library' => 'all', 'mime_types' => 'pdf', 'instructions' => 'PDF específico para el botón Descargar cotización de esta unidad.'),
            )),

            /* 7. Tours Virtuales */
            marcan_acf_tab('field_marcan_tab_prop_tours', '7. Tours Virtuales'),
            array('key' => 'field_marcan_show_tours', 'label' => 'Mostrar sección', 'name' => 'mostrar_tours', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            array('key' => 'field_marcan_virtual_tours', 'label' => 'Recorridos virtuales', 'name' => 'recorridos_virtuales', 'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Agregar recorrido', 'sub_fields' => array(
                array('key' => 'field_marcan_virtual_tour_title', 'label' => 'Título', 'name' => 'titulo', 'type' => 'text'),
                array('key' => 'field_marcan_virtual_tour_group', 'label' => 'Grupo', 'name' => 'grupo', 'type' => 'text', 'instructions' => 'Ejemplo: Áreas comunes o Departamentos. Se muestra como encabezado cuando cambia el grupo.'),
                array('key' => 'field_marcan_virtual_tour_url', 'label' => 'URL externa', 'name' => 'url', 'type' => 'url'),
                array('key' => 'field_marcan_virtual_tour_active', 'label' => 'Activo', 'name' => 'activo', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            )),

            /* 8. Ubicación */
            marcan_acf_tab('field_marcan_tab_prop_map', '8. Ubicación'),
            array('key' => 'field_marcan_show_map', 'label' => 'Mostrar sección', 'name' => 'mostrar_ubicacion', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            array('key' => 'field_marcan_video', 'label' => 'Video', 'name' => 'video', 'type' => 'url'),
            array('key' => 'field_marcan_map_google_url', 'label' => 'Link Google Maps', 'name' => 'google_maps_url', 'type' => 'url', 'instructions' => 'URL directa para abrir la ubicación en Google Maps.'),
            array('key' => 'field_marcan_map_waze_url', 'label' => 'Link Waze', 'name' => 'waze_url', 'type' => 'url', 'instructions' => 'URL directa para abrir la ubicación en Waze.'),
            marcan_acf_wysiwyg('field_marcan_map_heading', 'Título de la sección', 'ubicacion_titulo', 'basic'),
            marcan_acf_wysiwyg('field_marcan_map_nearby_title', 'Título lugares cercanos', 'lugares_cercanos_titulo', 'basic'),
            array('key' => 'field_marcan_map_nearby', 'label' => 'Lugares cercanos', 'name' => 'lugares_cercanos', 'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Agregar categoría', 'instructions' => 'Cada fila es una categoría. Los items van uno por línea.', 'sub_fields' => array(
                array('key' => 'field_marcan_map_nearby_cat', 'label' => 'Categoría', 'name' => 'categoria', 'type' => 'text'),
                array('key' => 'field_marcan_map_nearby_items', 'label' => 'Items (uno por línea)', 'name' => 'items', 'type' => 'textarea', 'rows' => 4),
            )),
            marcan_acf_wysiwyg('field_marcan_map_description', 'Descripción ubicación', 'ubicacion_descripcion'),

            /* 9. Galería */
            marcan_acf_tab('field_marcan_tab_prop_gallery', '9. Galería'),
            array('key' => 'field_marcan_show_gallery', 'label' => 'Mostrar sección', 'name' => 'mostrar_galeria', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            array('key' => 'field_marcan_common_areas', 'label' => 'Áreas comunes (PC)', 'name' => 'areas_comunes', 'type' => 'gallery', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all', 'instructions' => marcan_acf_image_help('2609x1832 px', '500 KB por imagen')),
            array('key' => 'field_marcan_common_areas_mobile', 'label' => 'Áreas comunes (vertical)', 'name' => 'areas_comunes_mobile', 'type' => 'gallery', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all', 'instructions' => marcan_acf_image_help('975x1330 px', '350 KB por imagen') . ' Debe tener el mismo numero de imagenes que la galeria horizontal.'),
            array('key' => 'field_marcan_internal_areas', 'label' => 'Áreas internas (PC)', 'name' => 'areas_internas', 'type' => 'gallery', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all', 'instructions' => marcan_acf_image_help('2609x1832 px', '500 KB por imagen')),
            array('key' => 'field_marcan_internal_areas_mobile', 'label' => 'Áreas internas (vertical)', 'name' => 'areas_internas_mobile', 'type' => 'gallery', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all', 'instructions' => marcan_acf_image_help('975x1330 px', '350 KB por imagen') . ' Debe tener el mismo numero de imagenes que la galeria horizontal.'),

            /* 10. Arquitectura */
            marcan_acf_tab('field_marcan_tab_prop_architecture', '10. Arquitectura'),
            array('key' => 'field_marcan_show_architecture', 'label' => 'Mostrar sección', 'name' => 'mostrar_arquitectura', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            marcan_acf_wysiwyg('field_marcan_architecture_section_title', 'Título de la sección', 'arquitectura_titulo', 'basic'),
            marcan_acf_wysiwyg('field_marcan_architecture_section_text', 'Texto de la sección', 'arquitectura_texto'),
            array('key' => 'field_marcan_architecture_section_image', 'label' => 'Imagen principal', 'name' => 'arquitectura_imagen', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'large', 'library' => 'all', 'instructions' => marcan_acf_image_help('1627x1457 px', '450 KB')),
            marcan_acf_wysiwyg('field_marcan_architecture_studio_name', 'Nombre del estudio', 'arquitectura_estudio_nombre', 'basic'),
            marcan_acf_wysiwyg('field_marcan_architecture_studio_role', 'Descripción del estudio', 'arquitectura_estudio_cargo', 'basic'),
            array('key' => 'field_marcan_architecture_studio_photo', 'label' => 'Foto del estudio', 'name' => 'arquitectura_estudio_foto', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all', 'instructions' => marcan_acf_image_help('600x600 px', '220 KB')),
        ),
        'location' => array(
            array(
                array('param' => 'post_type', 'operator' => '==', 'value' => 'property'),
            ),
        ),
        'menu_order' => 0,
        'active' => true,
    ));

    acf_add_local_field_group(array(
        'key' => 'group_marcan_iconic_project',
        'title' => 'Proyecto icónico - Detalle',
        'fields' => array(
            /* 1. Hero */
            marcan_acf_tab('field_marcan_iconic_tab_hero', '1. Hero'),
            marcan_hero_image_repeater('field_marcan_iconic_hero', 'iconic_hero_imagenes', 'Hero - imagenes'),
            array('key' => 'field_marcan_iconic_lineal_image', 'label' => 'Imagen lineal / Canson', 'name' => 'iconic_lineal_image', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium_large', 'library' => 'all', 'instructions' => marcan_acf_image_help('1627x1457 px', '450 KB')),

            /* 2. Cabecera */
            marcan_acf_tab('field_marcan_iconic_tab_header', '2. Cabecera'),
            array('key' => 'field_marcan_iconic_district', 'label' => 'Distrito / ubicación corta', 'name' => 'iconic_district', 'type' => 'text'),
            array('key' => 'field_marcan_iconic_address', 'label' => 'Dirección', 'name' => 'iconic_address', 'type' => 'text'),
            array('key' => 'field_marcan_iconic_year', 'label' => 'Año', 'name' => 'iconic_year', 'type' => 'text'),
            array('key' => 'field_marcan_iconic_status', 'label' => 'Estado', 'name' => 'iconic_status', 'type' => 'text', 'default_value' => 'Entregado'),
            marcan_acf_wysiwyg('field_marcan_iconic_summary', 'Texto breve', 'iconic_summary'),

            /* 3. Imagen secundaria */
            marcan_acf_tab('field_marcan_iconic_tab_detail', '3. Imagen secundaria'),
            array('key' => 'field_marcan_iconic_detail_image', 'label' => 'Imagen secundaria', 'name' => 'iconic_detail_image', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'large', 'library' => 'all', 'instructions' => marcan_acf_image_help('3025x1552 px', '650 KB')),

            /* 4. Concepto */
            marcan_acf_tab('field_marcan_iconic_tab_concept', '4. Concepto'),
            array('key' => 'field_marcan_show_concept_iconic', 'label' => 'Mostrar seccion', 'name' => 'mostrar_concepto_iconico', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            array('key' => 'field_marcan_iconic_concept_title', 'label' => 'Título concepto', 'name' => 'iconic_concept_title', 'type' => 'text', 'default_value' => 'Concepto'),
            marcan_acf_wysiwyg('field_marcan_iconic_concept_text', 'Texto concepto', 'iconic_concept_text', 'full'),

            /* 5. Diseñador */
            marcan_acf_tab('field_marcan_iconic_tab_designer', '5. Diseñador'),
            array('key' => 'field_marcan_show_designer_iconic', 'label' => 'Mostrar sección', 'name' => 'mostrar_disenador_iconico', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            array('key' => 'field_marcan_iconic_designer_photo', 'label' => 'Foto', 'name' => 'iconic_designer_photo', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all', 'instructions' => marcan_acf_image_help('600x600 px', '220 KB')),
            array('key' => 'field_marcan_iconic_designer_name', 'label' => 'Nombre', 'name' => 'iconic_designer_name', 'type' => 'text'),
            array('key' => 'field_marcan_iconic_designer_role', 'label' => 'Cargo', 'name' => 'iconic_designer_role', 'type' => 'text'),

            /* 6. Fachada */
            marcan_acf_tab('field_marcan_iconic_tab_facade', '6. Fachada'),
            array('key' => 'field_marcan_show_facade', 'label' => 'Mostrar sección', 'name' => 'mostrar_fachada', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            array('key' => 'field_marcan_iconic_facade_image', 'label' => 'Imagen', 'name' => 'iconic_facade_image', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'large', 'library' => 'all', 'instructions' => marcan_acf_image_help('3024x1836 px', '650 KB')),
            array('key' => 'field_marcan_iconic_facade_title', 'label' => 'Título', 'name' => 'iconic_facade_title', 'type' => 'text'),
            marcan_acf_wysiwyg('field_marcan_iconic_facade_text', 'Texto', 'iconic_facade_text'),

            /* 7. Galería */
            marcan_acf_tab('field_marcan_iconic_tab_gallery', '7. Galería'),
            array('key' => 'field_marcan_show_gallery_iconic', 'label' => 'Mostrar sección', 'name' => 'mostrar_galeria_iconica', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            array('key' => 'field_marcan_iconic_gallery', 'label' => 'Galería áreas comunes', 'name' => 'iconic_gallery', 'type' => 'gallery', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all', 'instructions' => marcan_acf_image_help('2609x1832 px', '500 KB por imagen')),

            /* 8. Detalles */
            marcan_acf_tab('field_marcan_iconic_tab_details', '8. Detalles'),
            array('key' => 'field_marcan_show_details', 'label' => 'Mostrar sección', 'name' => 'mostrar_detalles', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            array('key' => 'field_marcan_iconic_details_title', 'label' => 'Título', 'name' => 'iconic_details_title', 'type' => 'text', 'default_value' => 'Detalles que marcan'),
            marcan_acf_wysiwyg('field_marcan_iconic_details_text', 'Texto', 'iconic_details_text'),

            /* 9. CTA */
            marcan_acf_tab('field_marcan_iconic_tab_cta', '9. CTA'),
            array('key' => 'field_marcan_show_cta_iconic', 'label' => 'Mostrar sección', 'name' => 'mostrar_cta_iconico', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            array('key' => 'field_marcan_iconic_cta_title', 'label' => 'Título', 'name' => 'iconic_cta_title', 'type' => 'text', 'default_value' => 'Creamos proyectos que marcan'),
            array('key' => 'field_marcan_iconic_cta_departments', 'label' => 'Enlace departamentos', 'name' => 'iconic_cta_departments', 'type' => 'link'),
            array('key' => 'field_marcan_iconic_cta_offices', 'label' => 'Enlace oficinas', 'name' => 'iconic_cta_offices', 'type' => 'link'),
        ),
        'location' => array(
            array(
                array('param' => 'post_type', 'operator' => '==', 'value' => 'iconic_project'),
            ),
        ),
        'menu_order' => 0,
        'active' => true,
    ));

    acf_add_local_field_group(array(
        'key' => 'group_marcan_context_whatsapp',
        'title' => 'WhatsApp flotante',
        'fields' => array(
            array(
                'key' => 'field_marcan_context_whatsapp_contact',
                'label' => 'WhatsApp específico',
                'name' => 'whatsapp_contacto',
                'type' => 'text',
                'default_value' => '51919490440',
                'instructions' => 'Opcional. Si se deja vacio usa el WhatsApp global. Formato internacional sin signos. Ejemplo: 51919490440',
            ),
        ),
        'location' => array(
            array(
                array('param' => 'post_type', 'operator' => '==', 'value' => 'page'),
            ),
            array(
                array('param' => 'post_type', 'operator' => '==', 'value' => 'property'),
            ),
            array(
                array('param' => 'post_type', 'operator' => '==', 'value' => 'iconic_project'),
            ),
        ),
        'menu_order' => 0,
        'position' => 'side',
        'style' => 'default',
        'active' => true,
    ));

}
add_action('acf/init', 'marcan_register_field_groups');
