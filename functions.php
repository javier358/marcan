<?php
/**
 * Theme bootstrap.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

define('MARCAN_THEME_VERSION', '0.2.0');
define('MARCAN_THEME_PATH', trailingslashit(get_template_directory()));
define('MARCAN_THEME_URI', trailingslashit(get_template_directory_uri()));

$marcan_includes = array(
    'inc/helpers.php',
    'inc/setup.php',
    'inc/enqueue.php',
    'inc/cpt.php',
    'inc/contact.php',
    'inc/reclamaciones.php',
    'inc/about.php',
    'inc/iconic-projects.php',
    'inc/blog.php',
    'inc/acf.php',
);

foreach ($marcan_includes as $marcan_include) {
    require_once MARCAN_THEME_PATH . $marcan_include;
}
