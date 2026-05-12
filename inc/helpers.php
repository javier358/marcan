<?php
/**
 * Shared theme helpers.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

function marcan_asset_uri(string $path): string
{
    return MARCAN_THEME_URI . 'assets/' . ltrim($path, '/');
}

function marcan_svg(string $name): string
{
    $path = MARCAN_THEME_PATH . 'assets/images/' . $name . '.svg';

    if (!file_exists($path)) {
        return '';
    }

    return (string) file_get_contents($path);
}

function marcan_primary_menu_fallback(): void
{
    ?>
    <ul class="marcan-menu-list">
        <li><a href="<?php echo esc_url(home_url('/quienes-somos/')); ?>"><?php esc_html_e('Quiénes somos', 'marcan'); ?></a></li>
        <li><a href="<?php echo esc_url(get_post_type_archive_link('property') ?: home_url('/propiedades/')); ?>"><?php esc_html_e('Departamentos', 'marcan'); ?></a></li>
        <li><a href="<?php echo esc_url(get_post_type_archive_link('project') ?: home_url('/oficinas/')); ?>"><?php esc_html_e('Oficinas', 'marcan'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/blog/')); ?>"><?php esc_html_e('Blog', 'marcan'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/contactanos/')); ?>"><?php esc_html_e('Contáctanos', 'marcan'); ?></a></li>
    </ul>
    <?php
}

function marcan_get_property_meta(int $post_id, string $key): string
{
    $field_map = array(
        'price'     => 'precio',
        'area'      => 'area',
        'bedrooms'  => 'dormitorios',
        'bathrooms' => 'banos',
        'parking'   => 'estacionamientos',
        'address'   => 'ubicacion',
    );

    $field_name = $field_map[$key] ?? $key;

    if (function_exists('get_field')) {
        $value = get_field($field_name, $post_id);

        if (is_scalar($value)) {
            return (string) $value;
        }
    }

    return (string) get_post_meta($post_id, $field_name, true);
}
