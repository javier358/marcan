<?php
/**
 * Modal de contacto universal (disponible en toda la web).
 * Se abre con cualquier botón/enlace marcado con data-open-contact-modal
 * o enlaces de contacto (/contactanos/ o #contacto).
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

$contact_phone_lines = marcan_get_option_text('contact_phone_lines', marcan_get_option_text('footer_phone_lines', "Contact Center: 919 490 440\nOficinas: (01) 711 9400"));
$contact_email_raw = marcan_get_option_text('footer_email', 'ventas@marcan.com.pe');
if (preg_match('/[A-Z0-9._%+\-]+@[A-Z0-9.\-]+\.[A-Z]{2,}/i', $contact_email_raw, $email_match)) {
    $contact_email_raw = $email_match[0];
}
$contact_address = marcan_get_option_text('contact_modal_address', 'Av. 28 de Julio 1150, Miraflores, Lima, Perú');
$contact_hours = marcan_get_option_text('contact_sidebar_hours', 'Lun a Vier 9:00am - 6:00pm');
$contact_modal_title = marcan_get_option_text('contact_modal_title', 'Conversemos');
$privacy_page_url = get_privacy_policy_url();
if ($privacy_page_url === '') {
    $privacy_page_url = home_url('/politicas-de-privacidad/');
}
$privacy_default = 'He leído y acepto las Políticas de privacidad y otorgo mi consentimiento para el envío de información.';
$contact_privacy_text = marcan_get_option_text('contact_privacy_text', $privacy_default);
if (stripos($contact_privacy_text, 'Políticas de privacidad') !== false) {
    $contact_privacy_text = str_ireplace(
        'Políticas de privacidad',
        '<a href="' . esc_url($privacy_page_url) . '" target="_blank" rel="noopener">Políticas de privacidad</a>',
        $contact_privacy_text
    );
}
$contact_marketing_text = marcan_get_option_text('contact_marketing_text', 'Otorgo mi consentimiento para el envío de publicidad y/o anuncios comerciales y/o invitaciones a eventos.');
$contact_sidebar_title = marcan_get_option_text('contact_sidebar_title', 'Contáctanos ahora');

// Construir contenido de barra lateral sin que el cliente toque HTML
$contact_sidebar_parts = array();
if (trim($contact_phone_lines) !== '') {
    $contact_sidebar_parts[] = '<strong>' . esc_html($contact_phone_lines) . '</strong>';
}
if (trim($contact_email_raw) !== '') {
    $contact_sidebar_parts[] = '<strong>Correo:</strong><br>' . esc_html($contact_email_raw);
}
if (trim($contact_address) !== '') {
    $maps_url = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($contact_address);
    $contact_sidebar_parts[] = '<strong>Oficina Central:</strong><br>' . esc_html($contact_address) . '<br><a href="' . esc_url($maps_url) . '" target="_blank" rel="noopener">Ver ubicación</a>';
}
if (trim($contact_hours) !== '') {
    $contact_sidebar_parts[] = '<strong>Horario:</strong><br>' . esc_html($contact_hours);
}
$contact_sidebar_copy = implode('<br><br>', $contact_sidebar_parts);
$contact_thanks_variant = 'general';
$thank_you_image_id = 0;
if (is_singular('property')) {
    $property_id = get_queried_object_id();
    $property_kind = marcan_get_property_kind((int) $property_id);
    $contact_thanks_variant = $property_kind === 'oficina' ? 'office' : 'department';
    $thank_you_image_id = marcan_get_property_image_id($property_id, 'detalle_hero_desktop', 'home_desktop_image');
}
if (!$thank_you_image_id && has_post_thumbnail()) {
    $thank_you_image_id = (int) get_post_thumbnail_id();
}
$thank_you_image = $thank_you_image_id ? wp_get_attachment_image_url($thank_you_image_id, 'full') : '';
?>
<dialog class="marcan-property-contact-modal marcan-property-contact-modal-<?php echo esc_attr($contact_thanks_variant); ?>" data-contact-modal data-contact-thanks-variant="<?php echo esc_attr($contact_thanks_variant); ?>" aria-label="<?php esc_attr_e('Contáctanos', 'marcan'); ?>">
    <div class="marcan-property-contact-modal-inner">
        <form class="marcan-property-contact-modal-form-area" data-contact-form novalidate>
            <h2><?php echo marcan_rich_inline($contact_modal_title); ?></h2>
            <div class="marcan-property-contact-modal-fields">
                <div class="marcan-property-contact-field">
                    <label><?php esc_html_e('Nombre*', 'marcan'); ?></label>
                    <input type="text" name="nombre" placeholder="<?php esc_attr_e('Ingresa tu nombre', 'marcan'); ?>" required>
                </div>
                <div class="marcan-property-contact-field">
                    <label><?php esc_html_e('Apellido*', 'marcan'); ?></label>
                    <input type="text" name="apellido" placeholder="<?php esc_attr_e('Ingresa tu apellido', 'marcan'); ?>" required>
                </div>
                <div class="marcan-property-contact-field">
                    <label><?php esc_html_e('Email*', 'marcan'); ?></label>
                    <input type="email" name="email" placeholder="nombre@correo.com" required>
                </div>
                <div class="marcan-property-contact-field">
                    <label><?php esc_html_e('Teléfono/Celular*', 'marcan'); ?></label>
                    <input type="tel" name="telefono" placeholder="999 999 999" required>
                </div>
            </div>
            <div class="marcan-property-contact-modal-checks">
                <label class="marcan-property-contact-check">
                    <input type="checkbox" name="privacidad" required>
                    <span><?php echo wp_kses_post($contact_privacy_text); ?></span>
                </label>
                <label class="marcan-property-contact-check">
                    <input type="checkbox" name="publicidad">
                    <span><?php echo wp_kses_post($contact_marketing_text); ?></span>
                </label>
            </div>
            <input type="hidden" name="source_url" value="">
            <input type="hidden" name="source_title" value="">
            <input type="hidden" name="source_context" value="">
            <input type="hidden" name="source_property" value="">
            <input type="hidden" name="source_unit" value="">
            <p class="marcan-property-contact-modal-message" data-contact-form-message hidden></p>
            <button class="marcan-property-contact-modal-submit" type="submit">
                <span data-contact-submit-label><?php esc_html_e('Contactar', 'marcan'); ?></span>
                <span class="marcan-property-contact-modal-submit-icon" aria-hidden="true"></span>
            </button>
            <div class="marcan-property-contact-modal-sending" data-contact-form-sending aria-hidden="true">
                <div class="marcan-property-contact-modal-sending-mark"></div>
                <p><?php esc_html_e('Enviando solicitud', 'marcan'); ?></p>
            </div>
        </form>
        <aside class="marcan-property-contact-modal-sidebar">
            <button class="marcan-property-contact-modal-close" type="button" data-contact-modal-close aria-label="<?php esc_attr_e('Cerrar', 'marcan'); ?>"></button>
            <div class="marcan-property-contact-modal-sidebar-content">
                <p class="marcan-property-contact-modal-sidebar-heading"><?php echo marcan_rich_inline($contact_sidebar_title); ?></p>
                <div class="marcan-property-contact-modal-sidebar-info">
                    <?php echo marcan_rich_block($contact_sidebar_copy); ?>
                </div>
            </div>
        </aside>
        <div class="marcan-property-contact-modal-thanks" data-contact-thanks aria-hidden="true">
            <?php if ($thank_you_image !== '' && $contact_thanks_variant !== 'general') : ?>
                <img src="<?php echo esc_url($thank_you_image); ?>" alt="" aria-hidden="true">
            <?php endif; ?>
            <div class="marcan-property-contact-modal-thanks-yellow">
                <span class="marcan-property-contact-modal-thanks-arrow" aria-hidden="true"></span>
                <?php if ($contact_thanks_variant === 'general') : ?>
                    <p><?php esc_html_e('Gracias, un asesor se pondrá en contacto contigo', 'marcan'); ?></p>
                <?php else : ?>
                    <p><?php esc_html_e('Gracias,', 'marcan'); ?><br><?php esc_html_e('tu información', 'marcan'); ?><br><?php esc_html_e('ha sido enviada', 'marcan'); ?></p>
                <?php endif; ?>
            </div>
            <button class="marcan-property-contact-modal-thanks-close" type="button" data-contact-thanks-close aria-label="<?php esc_attr_e('Cerrar', 'marcan'); ?>"></button>
        </div>
    </div>
</dialog>

