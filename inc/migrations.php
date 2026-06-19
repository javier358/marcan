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
    $version = '2026-06-19-phase-3-global-copy';
    if (get_option('marcan_global_editable_copy_seed_version') === $version) {
        return;
    }

    marcan_seed_option_once('ui_property_btn_quote_project', 'Cotizar proyecto');
    marcan_seed_option_once('ui_property_btn_quote_office', 'Cotizar oficina');
    marcan_seed_option_once('ui_property_map_google', 'Ver en Google Maps');
    marcan_seed_option_once('ui_property_map_waze', 'Ver en Waze');
    marcan_seed_option_once('ui_property_about_label', 'Sobre el proyecto');

    update_option('marcan_global_editable_copy_seed_version', $version, false);
}
add_action('init', 'marcan_seed_global_editable_copy', 31);
