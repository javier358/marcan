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
