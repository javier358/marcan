<?php
/**
 * Theme support and menus.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

function marcan_setup(): void
{
    load_theme_textdomain('marcan', MARCAN_THEME_PATH . 'languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));
    add_theme_support('custom-logo', array(
        'height'      => 22,
        'width'       => 110,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    register_nav_menus(array(
        'primary'         => __('Menú principal', 'marcan'),
        'footer_projects' => __('Footer proyectos', 'marcan'),
        'footer_company'  => __('Footer empresa', 'marcan'),
    ));
}
add_action('after_setup_theme', 'marcan_setup');

/**
 * Páginas requeridas por el tema y su template asociado.
 *
 * @return array<string, array{title:string, template:string}>
 */
function marcan_required_pages(): array
{
    return array(
        'inicio' => array('title' => 'Inicio', 'template' => ''),
        'quienes-somos' => array('title' => 'Quiénes somos', 'template' => 'page-quienes-somos.php'),
        'departamentos' => array('title' => 'Departamentos', 'template' => 'page-departamentos.php'),
        'oficinas' => array('title' => 'Oficinas', 'template' => 'page-oficinas.php'),
        'blog' => array('title' => 'Blog', 'template' => 'page-blog.php'),
        'politicas-de-privacidad' => array('title' => 'Políticas de privacidad', 'template' => 'page-politicas-privacidad.php'),
        'libro-de-reclamaciones' => array('title' => 'Libro de Reclamaciones', 'template' => 'page-libro-reclamaciones.php'),
    );
}

function marcan_redirect_iconic_projects_index(): void
{
    if (is_admin()) {
        return;
    }

    $path = trim((string) wp_parse_url((string) ($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH), '/');

    if ($path === 'proyectos-iconicos') {
        wp_safe_redirect(home_url('/quienes-somos/'), 301);
        exit;
    }
}
add_action('template_redirect', 'marcan_redirect_iconic_projects_index', 1);

/**
 * Crea las páginas del tema si no existen y fija "Inicio" como portada.
 * Se ejecuta una sola vez (flag en options) salvo que falte alguna página.
 */
function marcan_ensure_required_pages(): void
{
    if (!is_admin() || !current_user_can('manage_options')) {
        return;
    }

    $created_ids = array();

    foreach (marcan_required_pages() as $slug => $config) {
        $existing = get_page_by_path($slug);

        if ($existing instanceof WP_Post) {
            $created_ids[$slug] = (int) $existing->ID;
            if ($config['template'] !== '' && get_page_template_slug($existing->ID) !== $config['template']) {
                update_post_meta($existing->ID, '_wp_page_template', $config['template']);
            }
            continue;
        }

        $page_id = wp_insert_post(array(
            'post_title'  => $config['title'],
            'post_name'   => $slug,
            'post_status' => 'publish',
            'post_type'   => 'page',
        ));

        if ($page_id && !is_wp_error($page_id)) {
            if ($config['template'] !== '') {
                update_post_meta($page_id, '_wp_page_template', $config['template']);
            }
            $created_ids[$slug] = (int) $page_id;
        }
    }

    if (!empty($created_ids['inicio'])) {
        $front_id = $created_ids['inicio'];
        if ((int) get_option('page_on_front') !== $front_id || get_option('show_on_front') !== 'page') {
            update_option('show_on_front', 'page');
            update_option('page_on_front', $front_id);
        }
    }

    update_option('marcan_pages_setup_done', '1');
}
add_action('admin_init', 'marcan_ensure_required_pages');
