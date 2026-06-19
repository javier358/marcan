<?php
/**
 * One-time content migrations.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

function marcan_meta_key_exists(int $post_id, string $key): bool
{
    return metadata_exists('post', $post_id, $key);
}

function marcan_seed_meta_once(int $post_id, string $key, string $value): void
{
    if ($value === '' || marcan_meta_key_exists($post_id, $key)) {
        return;
    }

    update_post_meta($post_id, $key, wp_slash($value));
}

function marcan_seed_property_editable_copy(): void
{
    $version = '2026-06-19-phase-3-editable-copy';
    if (get_option('marcan_property_editable_copy_seed_version') === $version) {
        return;
    }

    $query = new WP_Query(array(
        'post_type' => 'property',
        'post_status' => 'any',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'no_found_rows' => true,
    ));

    foreach ($query->posts as $post_id) {
        $post_id = (int) $post_id;
        $kind = marcan_get_property_kind($post_id);
        $is_office = $kind === 'oficina';
        $title = wp_strip_all_tags(marcan_get_property_field($post_id, 'titulo_comercial', get_the_title($post_id)));
        $price = marcan_get_property_field($post_id, 'precio', $is_office ? 'S/ 352,500' : 'S/ 846,000');

        marcan_seed_meta_once($post_id, 'ubicacion_titulo', 'Ubicacion perfecta cerca a todo');
        marcan_seed_meta_once($post_id, 'lugares_cercanos_titulo', 'Lugares de interes cercanos');
        marcan_seed_meta_once($post_id, 'relacionados_intro_texto', $is_office ? 'Revisa las oficinas que tenemos para ti' : 'Revisa las opciones que tenemos para ti');
        marcan_seed_meta_once($post_id, 'unidades_titulo_intro', 'Revisa las opciones que tenemos en');

        if ($title !== '' && $price !== '') {
            marcan_seed_meta_once($post_id, 'unidades_titulo_detalle', sprintf('%s desde %s', $title, $price));
        }

        marcan_seed_meta_once($post_id, 'frase_proyecto', 'Time: Aramburu se creo con el enfoque y balance de la naturaleza y el mar, vibran los detalles en cada espacio');
        marcan_seed_meta_once($post_id, 'autor_frase', 'Manuel de Rivero 51-1 Arquitectos');
    }

    update_option('marcan_property_editable_copy_seed_version', $version, false);
}
add_action('init', 'marcan_seed_property_editable_copy', 30);

function marcan_seed_option_once(string $field_name, string $value): void
{
    $option_name = 'options_' . $field_name;
    if ($value === '' || get_option($option_name, null) !== null) {
        return;
    }

    update_option($option_name, wp_slash($value), false);
}

function marcan_seed_global_editable_copy(): void
{
    $version = '2026-06-19-phase-5-card-labels';
    if (get_option('marcan_global_editable_copy_seed_version') === $version) {
        return;
    }

    marcan_seed_option_once('ui_property_btn_quote_project', 'Cotizar proyecto');
    marcan_seed_option_once('ui_property_btn_quote_office', 'Cotizar oficina');
    marcan_seed_option_once('ui_property_btn_brochure', 'Descargar brochure');
    marcan_seed_option_once('ui_property_btn_download_quote', 'Descargar cotizacion');
    marcan_seed_option_once('ui_property_btn_contact', 'Contactanos');
    marcan_seed_option_once('ui_property_btn_share', 'Compartir');
    marcan_seed_option_once('ui_property_map_google', 'Ver en Google Maps');
    marcan_seed_option_once('ui_property_map_waze', 'Ver en Waze');
    marcan_seed_option_once('ui_property_tours_title', 'Recorridos virtuales');
    marcan_seed_option_once('ui_property_about_label', 'Sobre el proyecto');
    marcan_seed_option_once('ui_property_related_dept', 'Otros departamentos en venta');
    marcan_seed_option_once('ui_property_related_office', 'Otras oficinas en venta');
    marcan_seed_option_once('ui_card_cta_more', 'Ver mas');
    marcan_seed_option_once('ui_card_cta_office', 'Ver oficina');
    marcan_seed_option_once('ui_card_cta_department', 'Ver departamento');
    marcan_seed_option_once('ui_card_price_label', 'Desde:');
    marcan_seed_option_once('ui_card_brochure', 'Descargar brochure');

    update_option('marcan_global_editable_copy_seed_version', $version, false);
}
add_action('init', 'marcan_seed_global_editable_copy', 31);

function marcan_seed_listing_reasons_once(int $page_id, array $reasons): void
{
    if (empty($reasons) || marcan_meta_key_exists($page_id, 'listing_reasons')) {
        return;
    }

    update_post_meta($page_id, 'listing_reasons', count($reasons));
    foreach (array_values($reasons) as $index => $reason) {
        update_post_meta($page_id, sprintf('listing_reasons_%d_number', $index), wp_slash((string) ($reason['number'] ?? '')));
        update_post_meta($page_id, sprintf('listing_reasons_%d_text', $index), wp_slash((string) ($reason['text'] ?? '')));
    }
}

function marcan_seed_listing_editable_copy(): void
{
    $version = '2026-06-19-phase-5-listing-copy';
    if (get_option('marcan_listing_editable_copy_seed_version') === $version) {
        return;
    }

    $departamentos = get_page_by_path('departamentos');
    if ($departamentos instanceof WP_Post) {
        $dept_id = (int) $departamentos->ID;
        marcan_seed_meta_once($dept_id, 'listing_title', 'Departamentos en venta');
        marcan_seed_meta_once($dept_id, 'listing_intro', 'Encuentra departamentos pensados para vivir mejor, con arquitectura funcional y ubicaciones conectadas a la ciudad.');
    }

    $oficinas = get_page_by_path('oficinas');
    if ($oficinas instanceof WP_Post) {
        $office_id = (int) $oficinas->ID;
        marcan_seed_meta_once($office_id, 'listing_title', 'Oficinas en venta');
        marcan_seed_meta_once($office_id, 'listing_intro', 'Espacios de trabajo pensados para invertir y crecer, en ubicaciones con alto potencial urbano.');
        marcan_seed_meta_once($office_id, 'listing_reasons_title', 'Por que invertir en oficinas?');
        marcan_seed_listing_reasons_once($office_id, array(
            array('number' => '1', 'text' => 'Los contratos empresariales ofrecen ingresos estables, predecibles y seguros a largo plazo.'),
            array('number' => '2', 'text' => 'Los espacios bien ubicados aumentan su valor de forma sostenida con el tiempo.'),
            array('number' => '3', 'text' => 'El trabajo hibrido impulsa la busqueda de oficinas modernas, flexibles y funcionales.'),
            array('number' => '4', 'text' => 'Invertir en oficinas permite equilibrar tu portafolio, reduce riesgos y fortalece tu patrimonio.'),
        ));
    }

    update_option('marcan_listing_editable_copy_seed_version', $version, false);
}
add_action('init', 'marcan_seed_listing_editable_copy', 32);

function marcan_seed_property_single_rest_copy(): void
{
    $version = '2026-06-19-phase-5-single-rest-copy';
    if (get_option('marcan_property_single_rest_copy_seed_version') === $version) {
        return;
    }

    $query = new WP_Query(array(
        'post_type' => 'property',
        'post_status' => 'any',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'no_found_rows' => true,
    ));

    $quote_label = marcan_get_option_text('ui_property_about_label', 'Sobre el proyecto');

    foreach ($query->posts as $post_id) {
        marcan_seed_meta_once((int) $post_id, 'quote_label', $quote_label);
    }

    update_option('marcan_property_single_rest_copy_seed_version', $version, false);
}
add_action('init', 'marcan_seed_property_single_rest_copy', 33);

function marcan_seed_property_listing_card_copy(): void
{
    $version = '2026-06-19-phase-5-card-copy';
    if (get_option('marcan_property_listing_card_copy_seed_version') === $version) {
        return;
    }

    $query = new WP_Query(array(
        'post_type' => 'property',
        'post_status' => 'any',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'no_found_rows' => true,
    ));

    foreach ($query->posts as $post_id) {
        $post_id = (int) $post_id;
        $title = marcan_get_property_field($post_id, 'concepto_titulo', wp_strip_all_tags(get_the_title($post_id)));
        $text = marcan_get_property_field($post_id, 'descripcion_corta', wp_strip_all_tags(get_the_excerpt($post_id)));

        marcan_seed_meta_once($post_id, 'listado_intro_titulo', $title);
        marcan_seed_meta_once($post_id, 'listado_intro_texto', $text);
    }

    update_option('marcan_property_listing_card_copy_seed_version', $version, false);
}
add_action('init', 'marcan_seed_property_listing_card_copy', 33);
