<?php
/**
 * Global contact form handling.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

function marcan_register_contact_submission_type(): void
{
    register_post_type('contact_submission', array(
        'labels' => array(
            'name'          => __('Envios del formulario', 'marcan'),
            'singular_name' => __('Envio del formulario', 'marcan'),
            'menu_name'     => __('Envios formulario', 'marcan'),
            'edit_item'     => __('Ver envío', 'marcan'),
        ),
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'exclude_from_search' => true,
        'has_archive'         => false,
        'rewrite'             => false,
        'menu_icon'           => 'dashicons-email-alt2',
        'supports'            => array('title'),
        'capability_type'     => 'post',
        'show_in_rest'        => false,
    ));
}
add_action('init', 'marcan_register_contact_submission_type');

function marcan_contact_form_settings_menu(): void
{
    add_submenu_page(
        'edit.php?post_type=contact_submission',
        __('Ajustes formulario', 'marcan'),
        __('Ajustes formulario', 'marcan'),
        'manage_options',
        'marcan-contact-form-settings',
        'marcan_render_contact_form_settings_page'
    );
}
add_action('admin_menu', 'marcan_contact_form_settings_menu');

function marcan_save_contact_form_settings(): void
{
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('No tienes permisos para editar estos ajustes.', 'marcan'));
    }

    check_admin_referer('marcan_contact_form_settings');

    $text_fields = array(
        'contact_form_recipients'  => 'sanitize_textarea_field',
        'contact_form_subject'     => 'sanitize_text_field',
        'contact_modal_title'      => 'sanitize_text_field',
        'contact_sidebar_title'    => 'sanitize_text_field',
        'contact_sidebar_hours'    => 'sanitize_text_field',
        'contact_modal_address'    => 'sanitize_text_field',
        'contact_privacy_text'     => 'sanitize_text_field',
        'contact_marketing_text'   => 'sanitize_text_field',
        'contact_phone_lines'      => 'sanitize_textarea_field',
        'contact_recaptcha_site_key' => 'sanitize_text_field',
        'contact_recaptcha_secret'   => 'sanitize_text_field',
    );

    foreach ($text_fields as $field => $sanitizer) {
        $value = $sanitizer(wp_unslash($_POST[$field] ?? ''));
        update_option($field, $value);
    }

    wp_safe_redirect(add_query_arg(array(
        'post_type' => 'contact_submission',
        'page' => 'marcan-contact-form-settings',
        'updated' => '1',
    ), admin_url('edit.php')));
    exit;
}
add_action('admin_post_marcan_save_contact_form_settings', 'marcan_save_contact_form_settings');

function marcan_render_contact_form_settings_page(): void
{
    $fields = array(
        array('name' => 'contact_form_recipients', 'label' => 'Destinatarios del correo', 'type' => 'textarea', 'rows' => 3, 'desc' => 'Correos que reciben cada envío del formulario. Uno por línea o separados por coma.'),
        array('name' => 'contact_form_subject', 'label' => 'Asunto del correo', 'type' => 'text', 'desc' => 'Se prefijará automáticamente con el nombre del proyecto.'),
        array('heading' => 'Modal de contacto — barra lateral'),
        array('name' => 'contact_sidebar_title', 'label' => 'Título de la columna derecha', 'type' => 'text'),
        array('name' => 'contact_phone_lines', 'label' => 'Teléfonos', 'type' => 'textarea', 'rows' => 2, 'desc' => 'Una línea por cada número.'),
        array('name' => 'contact_sidebar_hours', 'label' => 'Horario de atención', 'type' => 'text', 'desc' => 'Ej: Lun a Vier 9:00am - 6:00pm'),
        array('name' => 'contact_modal_address', 'label' => 'Dirección', 'type' => 'text', 'desc' => 'Ej: Av. Santa Cruz 830, of. 402 - Miraflores'),
        array('heading' => 'Modal de contacto — formulario'),
        array('name' => 'contact_modal_title', 'label' => 'Título principal', 'type' => 'text'),
        array('name' => 'contact_privacy_text', 'label' => 'Texto del checkbox de privacidad', 'type' => 'textarea', 'rows' => 2, 'desc' => 'La frase "Políticas de privacidad" se convierte en enlace automáticamente.'),
        array('name' => 'contact_marketing_text', 'label' => 'Texto del checkbox de publicidad', 'type' => 'textarea', 'rows' => 2),
        array('heading' => 'Anti-spam — Google reCAPTCHA v3'),
        array('name' => 'contact_recaptcha_site_key', 'label' => 'Clave del sitio (site key)', 'type' => 'text', 'desc' => 'Crea las claves en google.com/recaptcha/admin (tipo reCAPTCHA v3). Si se deja vacío, el formulario funciona sin reCAPTCHA.'),
        array('name' => 'contact_recaptcha_secret', 'label' => 'Clave secreta (secret key)', 'type' => 'text', 'desc' => 'Se usa solo en el servidor para verificar cada envío.'),
    );

    $defaults = array(
        'contact_form_recipients'  => '',
        'contact_form_subject'     => 'Nuevo contacto desde la web Marcan',
        'contact_modal_title'      => 'Conversemos',
        'contact_sidebar_title'    => 'Contáctanos ahora',
        'contact_sidebar_hours'    => 'Lun a Vier 9:00am - 6:00pm',
        'contact_modal_address'    => 'Av. 28 de Julio 1150, Miraflores, Lima, Perú',
        'contact_phone_lines'      => "Contact Center: 919 490 440\nOficinas: (01) 711 9400",
        'contact_privacy_text'     => 'He leído y acepto las Políticas de privacidad y otorgo mi consentimiento para el envío de información.',
        'contact_marketing_text'   => 'Otorgo mi consentimiento para el envío de publicidad y/o anuncios comerciales y/o invitaciones a eventos.',
        'contact_recaptcha_site_key' => '',
        'contact_recaptcha_secret'   => '',
    );

    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Ajustes del formulario de contacto', 'marcan'); ?></h1>
        <p style="font-size:14px;color:#666;max-width:700px"><?php esc_html_e('Configura los textos, destinatarios y contenido que se muestra en el modal de contacto de toda la web.', 'marcan'); ?></p>
        <?php if (isset($_GET['updated'])) : ?>
            <div class="notice notice-success is-dismissible"><p><?php esc_html_e('Ajustes guardados correctamente.', 'marcan'); ?></p></div>
        <?php endif; ?>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="marcan_save_contact_form_settings">
            <?php wp_nonce_field('marcan_contact_form_settings'); ?>
            <table class="form-table" role="presentation">
                <?php foreach ($fields as $field) :
                    if (isset($field['heading'])) : ?>
                        <tr><th colspan="2"><h2 style="margin:0;padding-top:20px;border-top:1px solid #ccc;font-size:15px"><?php echo esc_html($field['heading']); ?></h2></th></tr>
                        <?php continue;
                    endif;
                    $name  = $field['name'];
                    $label = $field['label'];
                    $type  = $field['type'];
                    $desc  = $field['desc'] ?? '';
                    $rows  = $field['rows'] ?? 4;
                    $value = marcan_get_option_text($name, $defaults[$name] ?? '');
                ?>
                    <tr>
                        <th scope="row"><label for="<?php echo esc_attr($name); ?>"><?php echo esc_html($label); ?></label></th>
                        <td>
                            <?php if ($type === 'textarea') : ?>
                                <textarea class="large-text" id="<?php echo esc_attr($name); ?>" name="<?php echo esc_attr($name); ?>" rows="<?php echo (int) $rows; ?>"><?php echo esc_textarea($value); ?></textarea>
                            <?php else : ?>
                                <input class="large-text" id="<?php echo esc_attr($name); ?>" name="<?php echo esc_attr($name); ?>" type="text" value="<?php echo esc_attr($value); ?>">
                            <?php endif; ?>
                            <?php if ($desc !== '') : ?>
                                <p class="description"><?php echo esc_html($desc); ?></p>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?php submit_button(__('Guardar ajustes', 'marcan')); ?>
        </form>
    </div>
    <?php
}

function marcan_parse_contact_recipients(): array
{
    $raw_recipients = marcan_get_option_text('contact_form_recipients', marcan_get_option_text('footer_email', get_option('admin_email')));
    $parts = preg_split('/[\s,;]+/', $raw_recipients);
    $recipients = array();

    foreach ((array) $parts as $part) {
        $email = sanitize_email($part);
        if ($email !== '' && is_email($email)) {
            $recipients[] = $email;
        }
    }

    if (empty($recipients)) {
        $fallback = sanitize_email((string) get_option('admin_email'));
        if ($fallback !== '' && is_email($fallback)) {
            $recipients[] = $fallback;
        }
    }

    return array_values(array_unique($recipients));
}

function marcan_clean_phone_for_whatsapp(string $phone): string
{
    $phone = preg_replace('/[^0-9+]/', '', $phone);

    if ($phone === '') {
        return '';
    }

    if (strlen($phone) === 9 && $phone[0] !== '9') {
        $phone = '51' . $phone;
    }

    if (strlen($phone) === 10 && $phone[0] === '9') {
        $phone = '51' . $phone;
    }

    if (strpos($phone, '51') === 0 && strlen($phone) === 11) {
        $phone = '51' . substr($phone, 2);
    }

    $phone = ltrim($phone, '+');

    return $phone;
}

function marcan_verify_recaptcha(string $token): bool
{
    $secret = marcan_get_option_text('contact_recaptcha_secret', '');
    if ($secret === '') {
        return true;
    }

    if ($token === '') {
        return false;
    }

    $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', array(
        'timeout' => 10,
        'body'    => array(
            'secret'   => $secret,
            'response' => $token,
            'remoteip' => sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'] ?? '')),
        ),
    ));

    if (is_wp_error($response)) {
        // Si Google no responde, no bloquear a usuarios reales.
        return true;
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);
    if (empty($body['success'])) {
        return false;
    }

    if (isset($body['action']) && $body['action'] !== 'marcan_contact') {
        return false;
    }

    if (isset($body['score']) && (float) $body['score'] < 0.5) {
        return false;
    }

    return true;
}

function marcan_submit_contact_form(): void
{
    check_ajax_referer('marcan_contact_form', 'nonce');

    $recaptcha_token = sanitize_text_field(wp_unslash($_POST['recaptcha_token'] ?? ''));
    if (!marcan_verify_recaptcha($recaptcha_token)) {
        wp_send_json_error(array(
            'message' => __('No pudimos verificar que eres una persona. Recarga la página e inténtalo nuevamente.', 'marcan'),
        ), 403);
    }

    $name = sanitize_text_field(wp_unslash($_POST['nombre'] ?? ''));
    $last_name = sanitize_text_field(wp_unslash($_POST['apellido'] ?? ''));
    $email = sanitize_email(wp_unslash($_POST['email'] ?? ''));
    $phone = sanitize_text_field(wp_unslash($_POST['telefono'] ?? ''));
    $privacy = !empty($_POST['privacidad']);
    $advertising = !empty($_POST['publicidad']);
    $source_url = esc_url_raw(wp_unslash($_POST['source_url'] ?? ''));
    $source_title = sanitize_text_field(wp_unslash($_POST['source_title'] ?? ''));
    $source_context = sanitize_text_field(wp_unslash($_POST['source_context'] ?? ''));
    $source_property = sanitize_text_field(wp_unslash($_POST['source_property'] ?? ''));
    $source_unit = sanitize_text_field(wp_unslash($_POST['source_unit'] ?? ''));

    if ($name === '' || $last_name === '' || $email === '' || $phone === '' || !$privacy || !is_email($email)) {
        wp_send_json_error(array(
            'message' => __('Completa los campos obligatorios para enviar el formulario.', 'marcan'),
        ), 400);
    }

    $submission_id = wp_insert_post(array(
        'post_type'   => 'contact_submission',
        'post_status' => 'publish',
        'post_title'  => sprintf('%s %s - %s', $name, $last_name, current_time('Y-m-d H:i')),
    ), true);

    if (is_wp_error($submission_id)) {
        wp_send_json_error(array(
            'message' => __('No se pudo guardar el envío. Inténtalo nuevamente.', 'marcan'),
        ), 500);
    }

    $fields = array(
        'nombre'        => $name,
        'apellido'      => $last_name,
        'email'         => $email,
        'telefono'      => $phone,
        'privacidad'    => $privacy ? 'Si' : 'No',
        'publicidad'    => $advertising ? 'Si' : 'No',
        'origen_url'    => $source_url,
        'origen_titulo' => $source_title,
        'origen_contexto' => $source_context,
        'origen_proyecto' => $source_property,
        'origen_unidad'   => $source_unit,
        'ip'            => sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'] ?? '')),
        'user_agent'    => sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'] ?? '')),
    );

    foreach ($fields as $key => $value) {
        update_post_meta($submission_id, $key, $value);
    }

    $recipients = marcan_parse_contact_recipients();
    $subject = marcan_get_option_text('contact_form_subject', 'Nuevo contacto desde la web Marcan');
    if ($source_property !== '') {
        $subject = '[' . $source_property . '] ' . $subject;
    }
    if ($source_unit !== '') {
        $subject .= ' - ' . $source_unit;
    }

    $logo_url = get_template_directory_uri() . '/assets/images/marcan-logo-desktop.svg';
    $admin_url = admin_url('post.php?post=' . $submission_id . '&action=edit');

    $whatsapp_target = marcan_clean_phone_for_whatsapp($phone);

    $html = marcan_build_contact_email_html(array(
        'name'            => $name,
        'last_name'       => $last_name,
        'email'           => $email,
        'phone'           => $phone,
        'phone_whatsapp'  => $whatsapp_target,
        'privacy'         => $privacy,
        'advertising'     => $advertising,
        'source_url'      => $source_url,
        'source_title'    => $source_title,
        'source_context'  => $source_context,
        'source_property' => $source_property,
        'source_unit'     => $source_unit,
        'date'            => current_time('Y-m-d H:i'),
        'logo_url'        => $logo_url,
    ));

    $plain = sprintf(
        "Nuevo envío del formulario Marcan\n\nNombre: %s %s\nEmail: %s\nTeléfono: %s\nAcepta privacidad: %s\nAcepta publicidad: %s\nOrigen: %s\nProyecto: %s\nUnidad: %s\nURL: %s\n\nVer en WordPress: %s",
        $name, $last_name, $email, $phone,
        $fields['privacidad'], $fields['publicidad'],
        $source_context, $source_property, $source_unit,
        $source_url, $admin_url
    );

    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'Reply-To: ' . $name . ' ' . $last_name . ' <' . $email . '>',
        'Cc: javier20185984@gmail.com',
    );
    $mail_sent = !empty($recipients) ? wp_mail($recipients, $subject, $html, $headers) : false;
    update_post_meta($submission_id, 'correo_enviado', $mail_sent ? 'Si' : 'No');
    update_post_meta($submission_id, 'correo_destinatarios', implode(', ', $recipients));

    wp_send_json_success(array(
        'message' => __('Gracias. Hemos recibido tus datos y te contactaremos pronto.', 'marcan'),
        'submission_id' => $submission_id,
    ));
}

function marcan_build_contact_email_html(array $data): string
{
    $rows = array(
        'Nombre'     => $data['name'] . ' ' . $data['last_name'],
        'Email'      => $data['email'],
        'Teléfono'   => $data['phone'],
        'Privacidad' => $data['privacy'] ? 'Aceptada' : 'No aceptada',
        'Publicidad' => $data['advertising'] ? 'Aceptada' : 'No aceptada',
    );

    $source = '';
    if ($data['source_context'] !== '') {
        $source .= '<tr><td style="padding:8px 12px;border-bottom:1px solid #e0e0e0;font-weight:600;color:#4f4f4f;width:140px">Origen</td><td style="padding:8px 12px;border-bottom:1px solid #e0e0e0;color:#333">' . esc_html($data['source_context']) . '</td></tr>';
    }
    if ($data['source_property'] !== '') {
        $source .= '<tr><td style="padding:8px 12px;border-bottom:1px solid #e0e0e0;font-weight:600;color:#4f4f4f;width:140px">Proyecto</td><td style="padding:8px 12px;border-bottom:1px solid #e0e0e0;color:#333">' . esc_html($data['source_property']) . '</td></tr>';
    }
    if ($data['source_unit'] !== '') {
        $source .= '<tr><td style="padding:8px 12px;border-bottom:1px solid #e0e0e0;font-weight:600;color:#4f4f4f;width:140px">Tipología / Unidad</td><td style="padding:8px 12px;border-bottom:1px solid #e0e0e0;color:#333">' . esc_html($data['source_unit']) . '</td></tr>';
    }
    if ($data['source_url'] !== '') {
        $source .= '<tr><td style="padding:8px 12px;border-bottom:1px solid #e0e0e0;font-weight:600;color:#4f4f4f;width:140px">Link</td><td style="padding:8px 12px;border-bottom:1px solid #e0e0e0"><a href="' . esc_url($data['source_url']) . '" style="color:#0057B8;text-decoration:none">' . esc_html($data['source_url']) . '</a></td></tr>';
    }

    $rows_html = '';
    foreach ($rows as $label => $value) {
        $rows_html .= '<tr><td style="padding:8px 12px;border-bottom:1px solid #e0e0e0;font-weight:600;color:#4f4f4f;width:140px">' . esc_html($label) . '</td><td style="padding:8px 12px;border-bottom:1px solid #e0e0e0;color:#333">' . esc_html($value) . '</td></tr>';
    }

    $logo = $data['logo_url'] !== '' ? '<img src="' . esc_url($data['logo_url']) . '" alt="Marcan" height="36" style="height:36px;width:auto;display:block">' : 'Marcan';

    $whatsapp_num = $data['phone_whatsapp'] ?? '';
    $whatsapp_button = '';
    if ($whatsapp_num !== '') {
        $wa_msg = rawurlencode('Hola ' . $data['name'] . ', gracias por escribirnos desde marcan.com.pe. ¿En qué podemos ayudarte?');
        $wa_link = 'https://wa.me/' . $whatsapp_num . '?text=' . $wa_msg;
        $whatsapp_button = '<a href="' . esc_url($wa_link) . '" style="display:inline-block;background:#25D366;color:#ffffff;font-size:15px;font-weight:600;padding:12px 28px;text-decoration:none;border-radius:4px">Contactar por WhatsApp</a>';
    }

    return '<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head><body style="margin:0;padding:0;background:#f3f2f1;font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,Arial,sans-serif"><table width="100%" cellpadding="0" cellspacing="0" style="background:#f3f2f1;padding:30px 0"><tr><td align="center"><table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:4px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.06)"><tr><td style="background:#ffcb05;padding:24px 30px">' . $logo . '</td></tr><tr><td style="padding:24px 30px"><h1 style="font-size:22px;font-weight:600;color:#4f4f4f;margin:0 0 16px">Nuevo contacto desde la web</h1><p style="font-size:14px;color:#666;margin:0 0 24px">Recibido el ' . esc_html($data['date']) . '</p><table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e0e0e0;border-radius:3px;margin-bottom:24px">' . $rows_html . $source . '</table>' . $whatsapp_button . '</td></tr><tr><td style="background:#f9f9f9;padding:16px 30px;border-top:1px solid #e0e0e0"><p style="font-size:12px;color:#999;margin:0">Marcan &middot; Av. Santa Cruz 830 Of. 402, Miraflores &middot; ventas@marcan.com.pe &middot; 919 490 440</p></td></tr></table></td></tr></table></body></html>';
}
add_action('wp_ajax_marcan_contact_submit', 'marcan_submit_contact_form');
add_action('wp_ajax_nopriv_marcan_contact_submit', 'marcan_submit_contact_form');

function marcan_contact_submission_columns(array $columns): array
{
    return array(
        'cb' => $columns['cb'] ?? '',
        'title' => __('Envio', 'marcan'),
        'email' => __('Email', 'marcan'),
        'telefono' => __('Teléfono', 'marcan'),
        'origen' => __('Origen', 'marcan'),
        'correo' => __('Correo', 'marcan'),
        'date' => $columns['date'] ?? __('Fecha', 'marcan'),
    );
}
add_filter('manage_contact_submission_posts_columns', 'marcan_contact_submission_columns');

function marcan_contact_submission_column(string $column, int $post_id): void
{
    if ($column === 'email') {
        echo esc_html((string) get_post_meta($post_id, 'email', true));
    } elseif ($column === 'telefono') {
        echo esc_html((string) get_post_meta($post_id, 'telefono', true));
    } elseif ($column === 'origen') {
        $url = (string) get_post_meta($post_id, 'origen_url', true);
        $title = (string) get_post_meta($post_id, 'origen_titulo', true);
        echo $url !== '' ? '<a href="' . esc_url($url) . '" target="_blank" rel="noopener">' . esc_html($title !== '' ? $title : $url) . '</a>' : esc_html($title);
    } elseif ($column === 'correo') {
        echo esc_html((string) get_post_meta($post_id, 'correo_enviado', true));
    }
}
add_action('manage_contact_submission_posts_custom_column', 'marcan_contact_submission_column', 10, 2);

function marcan_contact_submission_metaboxes(): void
{
    add_meta_box(
        'marcan_contact_submission_details',
        __('Datos del envío', 'marcan'),
        'marcan_contact_submission_details_metabox',
        'contact_submission',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes_contact_submission', 'marcan_contact_submission_metaboxes');

function marcan_contact_submission_details_metabox(WP_Post $post): void
{
    $rows = array(
        __('Nombre', 'marcan') => trim((string) get_post_meta($post->ID, 'nombre', true) . ' ' . (string) get_post_meta($post->ID, 'apellido', true)),
        __('Email', 'marcan') => (string) get_post_meta($post->ID, 'email', true),
        __('Teléfono', 'marcan') => (string) get_post_meta($post->ID, 'telefono', true),
        __('Acepto privacidad', 'marcan') => (string) get_post_meta($post->ID, 'privacidad', true),
        __('Acepto publicidad', 'marcan') => (string) get_post_meta($post->ID, 'publicidad', true),
        __('Origen', 'marcan') => (string) get_post_meta($post->ID, 'origen_titulo', true),
        __('URL origen', 'marcan') => (string) get_post_meta($post->ID, 'origen_url', true),
        __('Correo enviado', 'marcan') => (string) get_post_meta($post->ID, 'correo_enviado', true),
        __('Destinatarios', 'marcan') => (string) get_post_meta($post->ID, 'correo_destinatarios', true),
    );
    ?>
    <table class="widefat striped">
        <tbody>
            <?php foreach ($rows as $label => $value) : ?>
                <tr>
                    <th style="width: 180px;"><?php echo esc_html($label); ?></th>
                    <td><?php echo esc_html($value); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
}
