<?php
/**
 * Libro de Reclamaciones virtual (formato Indecopi, D.S. 011-2011-PCM).
 *
 * Reusa el patrón del formulario de contacto: CPT de registro, página de
 * ajustes para los destinatarios, handler AJAX, correo HTML y columnas admin.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * CPT que almacena cada hoja de reclamación recibida.
 */
function marcan_register_complaint_type(): void
{
    register_post_type('complaint_submission', array(
        'labels' => array(
            'name'          => __('Libro de Reclamaciones', 'marcan'),
            'singular_name' => __('Hoja de reclamación', 'marcan'),
            'menu_name'     => __('Reclamaciones', 'marcan'),
            'edit_item'     => __('Ver reclamación', 'marcan'),
        ),
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'exclude_from_search' => true,
        'has_archive'         => false,
        'rewrite'             => false,
        'menu_icon'           => 'dashicons-book-alt',
        'supports'            => array('title'),
        'capability_type'     => 'post',
        'show_in_rest'        => false,
    ));
}
add_action('init', 'marcan_register_complaint_type');

/**
 * Submenú de ajustes (destinatarios del correo).
 */
function marcan_complaint_settings_menu(): void
{
    add_submenu_page(
        'edit.php?post_type=complaint_submission',
        __('Ajustes del libro', 'marcan'),
        __('Ajustes del libro', 'marcan'),
        'manage_options',
        'marcan-complaint-settings',
        'marcan_render_complaint_settings_page'
    );
}
add_action('admin_menu', 'marcan_complaint_settings_menu');

function marcan_save_complaint_settings(): void
{
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('No tienes permisos para editar estos ajustes.', 'marcan'));
    }

    check_admin_referer('marcan_complaint_settings');

    $text_fields = array(
        'complaint_form_recipients' => 'sanitize_textarea_field',
        'complaint_form_subject'    => 'sanitize_text_field',
        'complaint_business_name'   => 'sanitize_text_field',
        'complaint_business_ruc'    => 'sanitize_text_field',
        'complaint_business_address' => 'sanitize_text_field',
    );

    foreach ($text_fields as $field => $sanitizer) {
        $value = $sanitizer(wp_unslash($_POST[$field] ?? ''));
        update_option($field, $value);
    }

    wp_safe_redirect(add_query_arg(array(
        'post_type' => 'complaint_submission',
        'page'      => 'marcan-complaint-settings',
        'updated'   => '1',
    ), admin_url('edit.php')));
    exit;
}
add_action('admin_post_marcan_save_complaint_settings', 'marcan_save_complaint_settings');

/**
 * Valores por defecto compartidos por los ajustes y el template.
 *
 * @return array<string, string>
 */
function marcan_complaint_defaults(): array
{
    return array(
        'complaint_form_recipients'  => "sac@marcan.com.pe\njarias@marcan.com.pe\nfsoldevilla@marcan.com.pe",
        'complaint_form_subject'     => 'Nueva hoja de reclamación - Libro de Reclamaciones',
        'complaint_business_name'    => 'Marcan Ingenieros S.A.C.',
        'complaint_business_ruc'     => '',
        'complaint_business_address' => 'Av. Santa Cruz 830 Of. 402, Miraflores, Lima, Perú',
    );
}

function marcan_render_complaint_settings_page(): void
{
    $defaults = marcan_complaint_defaults();
    $fields = array(
        array('name' => 'complaint_form_recipients', 'label' => 'Destinatarios del correo', 'type' => 'textarea', 'rows' => 3, 'desc' => 'Correos que reciben cada hoja de reclamación. Uno por línea o separados por coma.'),
        array('name' => 'complaint_form_subject', 'label' => 'Asunto del correo', 'type' => 'text'),
        array('heading' => 'Datos del proveedor (aparecen en la hoja)'),
        array('name' => 'complaint_business_name', 'label' => 'Razón social', 'type' => 'text'),
        array('name' => 'complaint_business_ruc', 'label' => 'RUC', 'type' => 'text'),
        array('name' => 'complaint_business_address', 'label' => 'Domicilio / establecimiento', 'type' => 'text'),
    );

    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Ajustes del Libro de Reclamaciones', 'marcan'); ?></h1>
        <p style="font-size:14px;color:#666;max-width:700px"><?php esc_html_e('Configura los destinatarios y los datos del proveedor que se muestran en el Libro de Reclamaciones virtual.', 'marcan'); ?></p>
        <?php if (isset($_GET['updated'])) : ?>
            <div class="notice notice-success is-dismissible"><p><?php esc_html_e('Ajustes guardados correctamente.', 'marcan'); ?></p></div>
        <?php endif; ?>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="marcan_save_complaint_settings">
            <?php wp_nonce_field('marcan_complaint_settings'); ?>
            <table class="form-table" role="presentation">
                <?php foreach ($fields as $field) :
                    if (isset($field['heading'])) : ?>
                        <tr><th colspan="2"><h2 style="margin:0;padding-top:20px;border-top:1px solid #ccc;font-size:15px"><?php echo esc_html($field['heading']); ?></h2></th></tr>
                        <?php continue;
                    endif;
                    $name  = $field['name'];
                    $desc  = $field['desc'] ?? '';
                    $rows  = $field['rows'] ?? 4;
                    $value = marcan_get_option_text($name, $defaults[$name] ?? '');
                ?>
                    <tr>
                        <th scope="row"><label for="<?php echo esc_attr($name); ?>"><?php echo esc_html($field['label']); ?></label></th>
                        <td>
                            <?php if (($field['type'] ?? 'text') === 'textarea') : ?>
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

/**
 * Lista de destinatarios validados (cae a los del contacto / admin si está vacío).
 *
 * @return string[]
 */
function marcan_parse_complaint_recipients(): array
{
    $defaults = marcan_complaint_defaults();
    $raw = marcan_get_option_text('complaint_form_recipients', $defaults['complaint_form_recipients']);
    $parts = preg_split('/[\s,;]+/', $raw);
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

/**
 * Genera el correlativo de la hoja de reclamación: 0001-2026.
 */
function marcan_next_complaint_number(): string
{
    $year = (int) current_time('Y');
    $key = 'marcan_complaint_counter_' . $year;
    $next = (int) get_option($key, 0) + 1;
    update_option($key, $next);

    return sprintf('%04d-%d', $next, $year);
}

/**
 * Recibe y procesa la hoja de reclamación enviada por AJAX.
 */
function marcan_submit_complaint(): void
{
    check_ajax_referer('marcan_complaint_form', 'nonce');

    $data = array(
        'consumidor_nombre'    => sanitize_text_field(wp_unslash($_POST['consumidor_nombre'] ?? '')),
        'consumidor_tipo_doc'  => sanitize_text_field(wp_unslash($_POST['consumidor_tipo_doc'] ?? '')),
        'consumidor_documento' => sanitize_text_field(wp_unslash($_POST['consumidor_documento'] ?? '')),
        'consumidor_domicilio' => sanitize_text_field(wp_unslash($_POST['consumidor_domicilio'] ?? '')),
        'consumidor_telefono'  => sanitize_text_field(wp_unslash($_POST['consumidor_telefono'] ?? '')),
        'consumidor_email'     => sanitize_email(wp_unslash($_POST['consumidor_email'] ?? '')),
        'consumidor_menor'     => !empty($_POST['consumidor_menor']) ? 'Si' : 'No',
        'apoderado_nombre'     => sanitize_text_field(wp_unslash($_POST['apoderado_nombre'] ?? '')),
        'bien_tipo'            => sanitize_text_field(wp_unslash($_POST['bien_tipo'] ?? '')),
        'bien_monto'           => sanitize_text_field(wp_unslash($_POST['bien_monto'] ?? '')),
        'bien_descripcion'     => sanitize_textarea_field(wp_unslash($_POST['bien_descripcion'] ?? '')),
        'reclamo_tipo'         => sanitize_text_field(wp_unslash($_POST['reclamo_tipo'] ?? '')),
        'detalle'              => sanitize_textarea_field(wp_unslash($_POST['detalle'] ?? '')),
        'pedido'               => sanitize_textarea_field(wp_unslash($_POST['pedido'] ?? '')),
        'acepta'               => !empty($_POST['acepta']) ? 'Si' : 'No',
    );

    $required = array('consumidor_nombre', 'consumidor_documento', 'consumidor_domicilio', 'consumidor_telefono', 'consumidor_email', 'bien_descripcion', 'detalle', 'pedido');
    foreach ($required as $key) {
        if ($data[$key] === '') {
            wp_send_json_error(array('message' => __('Completa todos los campos obligatorios.', 'marcan')), 400);
        }
    }
    if (!is_email($data['consumidor_email'])) {
        wp_send_json_error(array('message' => __('Ingresa un correo electrónico válido.', 'marcan')), 400);
    }
    if (!in_array($data['reclamo_tipo'], array('Reclamo', 'Queja'), true)) {
        wp_send_json_error(array('message' => __('Indica si es un reclamo o una queja.', 'marcan')), 400);
    }
    if ($data['acepta'] !== 'Si') {
        wp_send_json_error(array('message' => __('Debes aceptar los términos para enviar la hoja.', 'marcan')), 400);
    }
    if ($data['consumidor_menor'] === 'Si' && $data['apoderado_nombre'] === '') {
        wp_send_json_error(array('message' => __('Para menores de edad, indica el nombre del padre, madre o apoderado.', 'marcan')), 400);
    }

    $codigo = marcan_next_complaint_number();

    $submission_id = wp_insert_post(array(
        'post_type'   => 'complaint_submission',
        'post_status' => 'publish',
        'post_title'  => sprintf('Hoja %s - %s (%s)', $codigo, $data['consumidor_nombre'], $data['reclamo_tipo']),
    ), true);

    if (is_wp_error($submission_id)) {
        wp_send_json_error(array('message' => __('No se pudo registrar la hoja. Inténtalo nuevamente.', 'marcan')), 500);
    }

    $data['codigo'] = $codigo;
    $data['fecha'] = current_time('Y-m-d H:i');
    $data['ip'] = sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'] ?? ''));

    foreach ($data as $key => $value) {
        update_post_meta($submission_id, $key, $value);
    }

    $recipients = marcan_parse_complaint_recipients();
    $defaults = marcan_complaint_defaults();
    $subject = sprintf('[%s] %s', $codigo, marcan_get_option_text('complaint_form_subject', $defaults['complaint_form_subject']));
    $logo_url = get_template_directory_uri() . '/assets/images/marcan-logo-desktop.svg';

    $html = marcan_build_complaint_email_html($data, $logo_url);

    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'Reply-To: ' . $data['consumidor_nombre'] . ' <' . $data['consumidor_email'] . '>',
    );
    $mail_sent = !empty($recipients) ? wp_mail($recipients, $subject, $html, $headers) : false;

    // Acuse de recibo al consumidor (copia de su hoja).
    if (is_email($data['consumidor_email'])) {
        wp_mail(
            $data['consumidor_email'],
            sprintf(__('Hemos recibido tu hoja de reclamación %s', 'marcan'), $codigo),
            $html,
            array('Content-Type: text/html; charset=UTF-8')
        );
    }

    update_post_meta($submission_id, 'correo_enviado', $mail_sent ? 'Si' : 'No');
    update_post_meta($submission_id, 'correo_destinatarios', implode(', ', $recipients));

    wp_send_json_success(array(
        'message' => sprintf(__('Tu hoja de reclamación %s fue registrada. Te responderemos en un plazo máximo de 15 días hábiles.', 'marcan'), $codigo),
        'codigo'  => $codigo,
    ));
}
add_action('wp_ajax_marcan_complaint_submit', 'marcan_submit_complaint');
add_action('wp_ajax_nopriv_marcan_complaint_submit', 'marcan_submit_complaint');

/**
 * Construye el correo HTML de la hoja de reclamación.
 *
 * @param array<string, string> $d
 */
function marcan_build_complaint_email_html(array $d, string $logo_url): string
{
    $row = static function (string $label, string $value): string {
        if (trim($value) === '') {
            return '';
        }
        return '<tr><td style="padding:8px 12px;border-bottom:1px solid #e0e0e0;font-weight:600;color:#4f4f4f;width:200px;vertical-align:top">' . esc_html($label) . '</td><td style="padding:8px 12px;border-bottom:1px solid #e0e0e0;color:#333">' . nl2br(esc_html($value)) . '</td></tr>';
    };

    $consumidor = $row('Nombre', $d['consumidor_nombre'])
        . $row('Documento', trim($d['consumidor_tipo_doc'] . ' ' . $d['consumidor_documento']))
        . $row('Domicilio', $d['consumidor_domicilio'])
        . $row('Teléfono', $d['consumidor_telefono'])
        . $row('Email', $d['consumidor_email'])
        . ($d['consumidor_menor'] === 'Si' ? $row('Apoderado (menor de edad)', $d['apoderado_nombre']) : '');

    $bien = $row('Tipo', $d['bien_tipo'])
        . $row('Monto reclamado', $d['bien_monto'])
        . $row('Descripción', $d['bien_descripcion']);

    $detalle = $row('Tipo', $d['reclamo_tipo'])
        . $row('Detalle', $d['detalle'])
        . $row('Pedido del consumidor', $d['pedido']);

    $logo = $logo_url !== '' ? '<img src="' . esc_url($logo_url) . '" alt="Marcan" height="36" style="height:36px;width:auto;display:block">' : 'Marcan';

    $section = static function (string $title, string $rows): string {
        return '<h2 style="font-size:15px;font-weight:700;color:#4f4f4f;margin:24px 0 8px">' . esc_html($title) . '</h2><table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e0e0e0;border-radius:3px">' . $rows . '</table>';
    };

    return '<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head><body style="margin:0;padding:0;background:#f3f2f1;font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,Arial,sans-serif"><table width="100%" cellpadding="0" cellspacing="0" style="background:#f3f2f1;padding:30px 0"><tr><td align="center"><table width="640" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:4px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.06)"><tr><td style="background:#ffcb05;padding:24px 30px">' . $logo . '</td></tr><tr><td style="padding:24px 30px"><h1 style="font-size:22px;font-weight:600;color:#4f4f4f;margin:0 0 4px">Libro de Reclamaciones</h1><p style="font-size:14px;color:#666;margin:0 0 4px">Hoja N° ' . esc_html($d['codigo']) . '</p><p style="font-size:14px;color:#666;margin:0 0 8px">Recibida el ' . esc_html($d['fecha']) . '</p>'
        . $section('1. Identificación del consumidor', $consumidor)
        . $section('2. Identificación del bien contratado', $bien)
        . $section('3. Detalle de la reclamación', $detalle)
        . '<p style="font-size:12px;color:#999;margin:24px 0 0">El proveedor debe dar respuesta en un plazo no mayor de quince (15) días hábiles, conforme al Código de Protección y Defensa del Consumidor.</p></td></tr><tr><td style="background:#f9f9f9;padding:16px 30px;border-top:1px solid #e0e0e0"><p style="font-size:12px;color:#999;margin:0">Marcan &middot; Av. Santa Cruz 830 Of. 402, Miraflores &middot; ventas@marcan.com.pe</p></td></tr></table></td></tr></table></body></html>';
}

/**
 * Columnas del listado en el admin.
 *
 * @param array<string, string> $columns
 * @return array<string, string>
 */
function marcan_complaint_columns(array $columns): array
{
    return array(
        'cb'       => $columns['cb'] ?? '',
        'title'    => __('Hoja', 'marcan'),
        'tipo'     => __('Tipo', 'marcan'),
        'documento' => __('Documento', 'marcan'),
        'email'    => __('Email', 'marcan'),
        'correo'   => __('Correo', 'marcan'),
        'date'     => $columns['date'] ?? __('Fecha', 'marcan'),
    );
}
add_filter('manage_complaint_submission_posts_columns', 'marcan_complaint_columns');

function marcan_complaint_column(string $column, int $post_id): void
{
    if ($column === 'tipo') {
        echo esc_html((string) get_post_meta($post_id, 'reclamo_tipo', true));
    } elseif ($column === 'documento') {
        echo esc_html(trim((string) get_post_meta($post_id, 'consumidor_tipo_doc', true) . ' ' . (string) get_post_meta($post_id, 'consumidor_documento', true)));
    } elseif ($column === 'email') {
        echo esc_html((string) get_post_meta($post_id, 'consumidor_email', true));
    } elseif ($column === 'correo') {
        echo esc_html((string) get_post_meta($post_id, 'correo_enviado', true));
    }
}
add_action('manage_complaint_submission_posts_custom_column', 'marcan_complaint_column', 10, 2);

function marcan_complaint_metaboxes(): void
{
    add_meta_box(
        'marcan_complaint_details',
        __('Datos de la hoja de reclamación', 'marcan'),
        'marcan_complaint_details_metabox',
        'complaint_submission',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes_complaint_submission', 'marcan_complaint_metaboxes');

function marcan_complaint_details_metabox(WP_Post $post): void
{
    $labels = array(
        'codigo'               => __('Código de hoja', 'marcan'),
        'fecha'                => __('Fecha', 'marcan'),
        'reclamo_tipo'         => __('Tipo (Reclamo/Queja)', 'marcan'),
        'consumidor_nombre'    => __('Consumidor', 'marcan'),
        'consumidor_tipo_doc'  => __('Tipo de documento', 'marcan'),
        'consumidor_documento' => __('N° de documento', 'marcan'),
        'consumidor_domicilio' => __('Domicilio', 'marcan'),
        'consumidor_telefono'  => __('Teléfono', 'marcan'),
        'consumidor_email'     => __('Email', 'marcan'),
        'consumidor_menor'     => __('Menor de edad', 'marcan'),
        'apoderado_nombre'     => __('Apoderado', 'marcan'),
        'bien_tipo'            => __('Bien: tipo', 'marcan'),
        'bien_monto'           => __('Bien: monto', 'marcan'),
        'bien_descripcion'     => __('Bien: descripción', 'marcan'),
        'detalle'              => __('Detalle', 'marcan'),
        'pedido'               => __('Pedido del consumidor', 'marcan'),
        'correo_enviado'       => __('Correo enviado', 'marcan'),
        'correo_destinatarios' => __('Destinatarios', 'marcan'),
    );
    ?>
    <table class="widefat striped">
        <tbody>
            <?php foreach ($labels as $key => $label) :
                $value = (string) get_post_meta($post->ID, $key, true);
                if ($value === '') {
                    continue;
                }
            ?>
                <tr>
                    <th style="width: 200px;"><?php echo esc_html($label); ?></th>
                    <td><?php echo nl2br(esc_html($value)); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
}
