<?php
/**
 * Rol personalizado para gestores de contenido MARCAN.
 *
 * Permite editar todo el contenido del sitio (paginas, posts, CPTs y la pagina
 * de opciones "MARCAN Global") SIN poder gestionar plugins, ajustes de WordPress,
 * el Customizer, los menus, los widgets ni los archivos del tema. Pensado para que
 * el cliente edite sin riesgo de romper el diseno.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Subir esta version cada vez que cambien las capacidades del rol, para que el
 * registro idempotente vuelva a crear el rol con los caps nuevos.
 */
define('MARCAN_ROLE_VERSION', '1');
define('MARCAN_CONTENT_ROLE', 'marcan_content_manager');
define('MARCAN_CONTENT_CAP', 'manage_marcan_content');

/**
 * Capacidades del rol. true = otorgada, false = denegada explicitamente.
 */
function marcan_content_manager_caps(): array
{
    return array(
        // Lectura y medios.
        'read'                   => true,
        'upload_files'           => true,

        // Entradas (blog) y CPTs (property / iconic_project usan caps de 'post').
        'edit_posts'             => true,
        'edit_others_posts'      => true,
        'edit_published_posts'   => true,
        'edit_private_posts'     => true,
        'publish_posts'          => true,
        'read_private_posts'     => true,
        'delete_posts'           => true,
        'delete_others_posts'    => true,
        'delete_published_posts' => true,
        'delete_private_posts'   => true,

        // Paginas: editar y publicar, pero NO borrar publicadas ni ajenas (anti-rotura).
        'edit_pages'             => true,
        'edit_others_pages'      => true,
        'edit_published_pages'   => true,
        'edit_private_pages'     => true,
        'publish_pages'          => true,
        'read_private_pages'     => true,
        'delete_pages'           => true,  // solo borradores propios sin publicar
        'delete_private_pages'   => true,
        'delete_published_pages' => false, // no borrar paginas publicadas
        'delete_others_pages'    => false, // no borrar paginas de otros

        // Taxonomias y comentarios.
        'manage_categories'      => true,
        'moderate_comments'      => true,

        // Acceso a "MARCAN Global" sin abrir el Customizer.
        MARCAN_CONTENT_CAP       => true,

        // --- Denegado explicitamente: nada que rompa el sitio o el diseno ---
        'activate_plugins'       => false,
        'install_plugins'        => false,
        'update_plugins'         => false,
        'delete_plugins'         => false,
        'edit_plugins'           => false,
        'install_themes'         => false,
        'update_themes'          => false,
        'delete_themes'          => false,
        'switch_themes'          => false,
        'edit_themes'            => false,
        'edit_theme_options'     => false, // quita Customizer, Menus y Widgets
        'customize'              => false,
        'manage_options'         => false,
        'update_core'            => false,
        'export'                 => false,
        'import'                 => false,
        'edit_users'             => false,
        'create_users'           => false,
        'delete_users'           => false,
        'list_users'             => false,
        'promote_users'          => false,
        'remove_users'           => false,
        'edit_files'             => false,
        'unfiltered_html'        => false, // no pegar <script>/HTML que descuadre
        'unfiltered_upload'      => false,
    );
}

/**
 * Crea/actualiza el rol de forma idempotente tras un flag de version.
 * add_role() es no-op si el rol existe, por eso se elimina y recrea al subir version.
 */
function marcan_register_roles(): void
{
    if (get_option('marcan_role_version') === MARCAN_ROLE_VERSION) {
        return;
    }

    remove_role(MARCAN_CONTENT_ROLE);
    add_role(MARCAN_CONTENT_ROLE, 'Gestor de contenido MARCAN', marcan_content_manager_caps());

    // El administrador no tiene caps custom de forma implicita: hay que otorgarla
    // o se bloquearia a si mismo fuera de "MARCAN Global".
    $admin = get_role('administrator');
    if ($admin) {
        $admin->add_cap(MARCAN_CONTENT_CAP);
    }

    update_option('marcan_role_version', MARCAN_ROLE_VERSION);
}
add_action('after_switch_theme', 'marcan_register_roles');
add_action('admin_init', 'marcan_register_roles');

/**
 * True solo para el gestor de contenido restringido (nunca para administradores).
 */
function marcan_is_restricted_manager(): bool
{
    return is_user_logged_in()
        && current_user_can(MARCAN_CONTENT_CAP)
        && !current_user_can('manage_options');
}

/**
 * Oculta del menu admin las secciones peligrosas para el rol restringido.
 */
function marcan_restrict_admin_menus(): void
{
    if (!marcan_is_restricted_manager()) {
        return;
    }

    remove_menu_page('plugins.php');
    remove_menu_page('tools.php');
    remove_menu_page('options-general.php');
    remove_menu_page('themes.php'); // Apariencia: temas, customizer, menus, widgets, editor

    remove_submenu_page('themes.php', 'customize.php');
    remove_submenu_page('themes.php', 'nav-menus.php');
    remove_submenu_page('themes.php', 'widgets.php');
    remove_submenu_page('themes.php', 'theme-editor.php');
    remove_submenu_page('themes.php', 'site-editor.php');
}
add_action('admin_menu', 'marcan_restrict_admin_menus', 999);

/**
 * Barrera real: bloquea el acceso por URL directa aunque el menu este oculto.
 * Ocultar el menu es solo cosmetico.
 */
function marcan_block_restricted_admin_pages(): void
{
    if (!marcan_is_restricted_manager()) {
        return;
    }

    global $pagenow;

    $blocked = array(
        'plugins.php', 'plugin-install.php', 'plugin-editor.php',
        'themes.php', 'theme-editor.php', 'customize.php',
        'nav-menus.php', 'widgets.php', 'site-editor.php',
        'tools.php', 'import.php', 'export.php', 'site-health.php',
        'users.php', 'user-new.php',
    );

    $is_options = is_string($pagenow) && strpos($pagenow, 'options-') === 0;

    if (in_array($pagenow, $blocked, true) || $is_options) {
        wp_die(
            esc_html__('No tienes permisos para acceder a esta seccion.', 'marcan'),
            esc_html__('Acceso restringido', 'marcan'),
            array('response' => 403, 'back_link' => true)
        );
    }
}
add_action('admin_init', 'marcan_block_restricted_admin_pages');
