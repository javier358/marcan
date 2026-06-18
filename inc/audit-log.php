<?php
/**
 * Registro de auditoría (qué usuario hizo qué cambio).
 *
 * Logger liviano propio del tema (se despliega con el código, sin plugins).
 * Registra ediciones de contenido, subidas/borrados de medios, papelera y logins,
 * con usuario, acción, objeto y fecha. Solo lo ve el administrador desde
 * "Registro de cambios". Los gestores de contenido NO pueden verlo ni gestionarlo.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

define('MARCAN_AUDIT_OPTION', 'marcan_audit_log');
define('MARCAN_AUDIT_MAX', 1000);

/**
 * Añade una entrada al registro (anillo capado a MARCAN_AUDIT_MAX).
 */
function marcan_audit_add(string $action, string $object = '', int $object_id = 0): void
{
    $user = wp_get_current_user();
    if (!$user || !$user->ID) {
        return;
    }

    $log = get_option(MARCAN_AUDIT_OPTION, array());
    if (!is_array($log)) {
        $log = array();
    }

    $log[] = array(
        'time'    => current_time('mysql'),
        'user_id' => (int) $user->ID,
        'user'    => $user->user_login,
        'action'  => $action,
        'object'  => $object,
        'oid'     => $object_id,
    );

    if (count($log) > MARCAN_AUDIT_MAX) {
        $log = array_slice($log, -MARCAN_AUDIT_MAX);
    }

    update_option(MARCAN_AUDIT_OPTION, $log, false);
}

/**
 * Tipos de post que NO se registran (ruido interno).
 */
function marcan_audit_ignored_types(): array
{
    return array('revision', 'nav_menu_item', 'customize_changeset', 'oembed_cache', 'user_request', 'wp_global_styles', 'acf-field', 'acf-field-group');
}

/**
 * Edición/creación/publicación de contenido.
 */
function marcan_audit_save_post(int $post_id, $post, bool $update): void
{
    if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
        return;
    }
    if (!is_object($post) || in_array($post->post_type, marcan_audit_ignored_types(), true)) {
        return;
    }
    if ($post->post_status === 'auto-draft') {
        return;
    }

    $action = $update ? 'Actualizó' : 'Creó';
    $label = sprintf('%s "%s"', $post->post_type, get_the_title($post_id) ?: ('#' . $post_id));
    marcan_audit_add($action, $label, $post_id);
}
add_action('save_post', 'marcan_audit_save_post', 99, 3);

/**
 * Guardado de campos SCF/ACF (registra el objeto editado).
 */
function marcan_audit_acf_save($post_id): void
{
    // ACF llama con 'options' para la página global, o un ID numérico para posts.
    if ($post_id === 'options' || (is_string($post_id) && strpos($post_id, 'option') === 0)) {
        marcan_audit_add('Guardó opciones globales', 'MARCAN Global', 0);
    }
}
add_action('acf/save_post', 'marcan_audit_acf_save', 99);

function marcan_audit_trash(int $post_id): void
{
    $type = get_post_type($post_id);
    if (in_array((string) $type, marcan_audit_ignored_types(), true)) {
        return;
    }
    marcan_audit_add('Envió a la papelera', sprintf('%s "%s"', $type, get_the_title($post_id) ?: ('#' . $post_id)), $post_id);
}
add_action('wp_trash_post', 'marcan_audit_trash');

function marcan_audit_delete(int $post_id): void
{
    $type = get_post_type($post_id);
    if (in_array((string) $type, marcan_audit_ignored_types(), true)) {
        return;
    }
    marcan_audit_add('Eliminó definitivamente', sprintf('%s "%s"', $type, get_the_title($post_id) ?: ('#' . $post_id)), $post_id);
}
add_action('before_delete_post', 'marcan_audit_delete');

function marcan_audit_attachment(int $post_id): void
{
    marcan_audit_add('Subió un archivo', get_the_title($post_id) ?: ('#' . $post_id), $post_id);
}
add_action('add_attachment', 'marcan_audit_attachment');

function marcan_audit_login(string $user_login): void
{
    $log = get_option(MARCAN_AUDIT_OPTION, array());
    if (!is_array($log)) {
        $log = array();
    }
    $log[] = array(
        'time'    => current_time('mysql'),
        'user_id' => 0,
        'user'    => $user_login,
        'action'  => 'Inició sesión',
        'object'  => '',
        'oid'     => 0,
    );
    if (count($log) > MARCAN_AUDIT_MAX) {
        $log = array_slice($log, -MARCAN_AUDIT_MAX);
    }
    update_option(MARCAN_AUDIT_OPTION, $log, false);
}
add_action('wp_login', 'marcan_audit_login');

/**
 * Página de admin "Registro de cambios" (solo administradores).
 */
function marcan_audit_menu(): void
{
    add_menu_page(
        'Registro de cambios',
        'Registro de cambios',
        'manage_options',
        'marcan-audit-log',
        'marcan_audit_render_page',
        'dashicons-list-view',
        81
    );
}
add_action('admin_menu', 'marcan_audit_menu');

function marcan_audit_render_page(): void
{
    if (!current_user_can('manage_options')) {
        return;
    }

    $log = get_option(MARCAN_AUDIT_OPTION, array());
    if (!is_array($log)) {
        $log = array();
    }
    $log = array_reverse($log); // más reciente primero

    echo '<div class="wrap"><h1>Registro de cambios</h1>';
    echo '<p>Acciones registradas de los usuarios (máximo ' . esc_html((string) MARCAN_AUDIT_MAX) . ' entradas).</p>';

    if (empty($log)) {
        echo '<p>Aún no hay actividad registrada.</p></div>';
        return;
    }

    echo '<table class="widefat striped"><thead><tr>';
    echo '<th>Fecha</th><th>Usuario</th><th>Acción</th><th>Objeto</th>';
    echo '</tr></thead><tbody>';

    foreach ($log as $entry) {
        $time   = isset($entry['time']) ? (string) $entry['time'] : '';
        $user   = isset($entry['user']) ? (string) $entry['user'] : '';
        $action = isset($entry['action']) ? (string) $entry['action'] : '';
        $object = isset($entry['object']) ? (string) $entry['object'] : '';
        $oid    = isset($entry['oid']) ? (int) $entry['oid'] : 0;
        $object_cell = esc_html($object);
        if ($oid > 0 && get_post($oid)) {
            $object_cell = '<a href="' . esc_url(get_edit_post_link($oid)) . '">' . esc_html($object) . '</a>';
        }

        echo '<tr>';
        echo '<td>' . esc_html($time) . '</td>';
        echo '<td>' . esc_html($user) . '</td>';
        echo '<td>' . esc_html($action) . '</td>';
        echo '<td>' . $object_cell . '</td>';
        echo '</tr>';
    }

    echo '</tbody></table></div>';
}
