<?php
/**
 * Global floating WhatsApp button.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

$context_id = is_singular() ? get_queried_object_id() : 0;
$context_title = $context_id ? get_the_title($context_id) : get_bloginfo('name');
$message = $context_id
    ? sprintf(__('Hola, quiero informacion sobre %1$s: %2$s', 'marcan'), $context_title, get_permalink($context_id))
    : sprintf(__('Hola, quiero informacion desde %s', 'marcan'), home_url('/'));
$whatsapp_url = marcan_get_context_whatsapp_url((int) $context_id, $message);
?>

<a class="marcan-property-archive-whatsapp marcan-global-whatsapp" href="<?php echo esc_url($whatsapp_url); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e('Contactar por WhatsApp', 'marcan'); ?>">
    <span aria-hidden="true"></span>
</a>
