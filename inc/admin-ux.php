<?php
/**
 * Ajustes de experiencia de edicion en el admin.
 *
 * Oculta el editor de contenido nativo de WordPress en las paginas cuyo contenido
 * se gestiona 100% con campos SCF, para que el cliente no se confunda y vea primero
 * las pestanas editables. Los CPT (property, iconic_project) ya no declaran 'editor'
 * en supports, asi que aqui solo se tratan las PAGINAS por plantilla.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Plantillas de pagina 100% SCF donde el editor nativo sobra.
 */
function marcan_scf_only_page_templates(): array
{
    return array(
        'page-quienes-somos.php',
        'page-departamentos.php',
        'page-oficinas.php',
        'page-politicas-privacidad.php',
        'page-terminos-condiciones.php',
    );
}

/**
 * Quita el soporte de 'editor' solo cuando se edita una pagina SCF-only o la
 * pagina configurada como portada (front page). Se ejecuta por request en la
 * pantalla de edicion, asi que no afecta a otras paginas.
 */
function marcan_maybe_hide_page_editor(): void
{
    global $pagenow;

    if (!in_array($pagenow, array('post.php', 'post-new.php'), true)) {
        return;
    }

    $post_id = 0;
    if (isset($_GET['post'])) {
        $post_id = (int) $_GET['post'];
    } elseif (isset($_POST['post_ID'])) {
        $post_id = (int) $_POST['post_ID'];
    }

    if (!$post_id || get_post_type($post_id) !== 'page') {
        return;
    }

    $template = (string) get_page_template_slug($post_id);
    $front_id = (int) get_option('page_on_front');

    $is_scf_only = in_array($template, marcan_scf_only_page_templates(), true)
        || ($front_id && $post_id === $front_id);

    if ($is_scf_only) {
        remove_post_type_support('page', 'editor');
    }
}
add_action('admin_init', 'marcan_maybe_hide_page_editor');

/**
 * Tipos de contenido donde el equipo MARCAN debe poder editar en paralelo.
 */
function marcan_parallel_edit_post_types(): array
{
    return array('page', 'post', 'property', 'iconic_project');
}

/**
 * Detecta el post editado en pantallas clásicas, Quick Edit y heartbeat.
 */
function marcan_current_admin_edit_post_id(): int
{
    foreach (array('post', 'post_ID', 'post_id') as $key) {
        if (isset($_REQUEST[$key]) && is_numeric($_REQUEST[$key])) {
            return (int) $_REQUEST[$key];
        }
    }

    $heartbeat_data = isset($_POST['data']) && is_array($_POST['data']) ? wp_unslash($_POST['data']) : array();
    $refresh_lock = is_array($heartbeat_data) && isset($heartbeat_data['wp-refresh-post-lock']) && is_array($heartbeat_data['wp-refresh-post-lock'])
        ? $heartbeat_data['wp-refresh-post-lock']
        : array();

    if (isset($refresh_lock['post_id']) && is_numeric($refresh_lock['post_id'])) {
        return (int) $refresh_lock['post_id'];
    }

    return 0;
}

/**
 * WordPress bloquea una pagina/post cuando otro usuario la tiene abierta.
 * Para el flujo editorial de MARCAN, varios usuarios pueden entrar al mismo
 * contenido administrable sin que aparezca el candado ni el modal de takeover.
 */
function marcan_disable_editor_lock_for_content(int $window): int
{
    if (!is_admin()) {
        return $window;
    }

    $post_id = marcan_current_admin_edit_post_id();
    if (!$post_id) {
        $post_type = isset($_REQUEST['post_type']) ? sanitize_key(wp_unslash($_REQUEST['post_type'])) : '';
        return in_array($post_type, marcan_parallel_edit_post_types(), true) ? 0 : $window;
    }

    return in_array(get_post_type($post_id), marcan_parallel_edit_post_types(), true) ? 0 : $window;
}
add_filter('wp_check_post_lock_window', 'marcan_disable_editor_lock_for_content');
