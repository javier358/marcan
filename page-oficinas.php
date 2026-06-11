<?php
if (!defined('ABSPATH')) {
    exit;
}

get_header();
get_template_part('template-parts/properties/property-listing-page', null, array('kind' => 'oficina'));
get_footer();
