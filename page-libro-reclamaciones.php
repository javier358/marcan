<?php
/**
 * Libro de Reclamaciones — formulario virtual (formato Indecopi).
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

$defaults = function_exists('marcan_complaint_defaults') ? marcan_complaint_defaults() : array();
$business_name = marcan_get_option_text('complaint_business_name', $defaults['complaint_business_name'] ?? 'Marcan Ingenieros S.A.C.');
$business_ruc = marcan_get_option_text('complaint_business_ruc', $defaults['complaint_business_ruc'] ?? '');
$business_address = marcan_get_option_text('complaint_business_address', $defaults['complaint_business_address'] ?? '');
$today = date_i18n('d/m/Y');
?>
<main class="marcan-page-shell marcan-page-shell-complaint">
    <section class="marcan-privacy-hero">
        <div class="marcan-privacy-hero-inner">
            <a href="javascript:history.back()" class="marcan-privacy-back" aria-label="<?php esc_attr_e('Volver', 'marcan'); ?>">
                <img src="<?php echo esc_url(marcan_asset_uri('images/figma-tour-arrow-left-v2.svg')); ?>" alt="" aria-hidden="true">
            </a>
            <h1><?php the_title(); ?></h1>
        </div>
    </section>

    <section class="marcan-complaint-body">
        <div class="marcan-complaint-inner">
            <div class="marcan-complaint-provider">
                <p><strong><?php echo esc_html($business_name); ?></strong></p>
                <?php if ($business_ruc !== '') : ?><p><?php printf(esc_html__('RUC: %s', 'marcan'), esc_html($business_ruc)); ?></p><?php endif; ?>
                <?php if ($business_address !== '') : ?><p><?php echo esc_html($business_address); ?></p><?php endif; ?>
                <p class="marcan-complaint-date"><?php printf(esc_html__('Fecha: %s', 'marcan'), esc_html($today)); ?></p>
            </div>

            <p class="marcan-complaint-intro"><?php esc_html_e('Conforme al Código de Protección y Defensa del Consumidor, ponemos a tu disposición este Libro de Reclamaciones virtual. Completa los datos y recibirás una copia en tu correo. El proveedor dará respuesta en un plazo no mayor de quince (15) días hábiles.', 'marcan'); ?></p>

            <form class="marcan-complaint-form" id="marcan-complaint-form" novalidate>
                <fieldset class="marcan-complaint-fieldset">
                    <legend><?php esc_html_e('1. Identificación del consumidor reclamante', 'marcan'); ?></legend>
                    <div class="marcan-complaint-grid">
                        <label class="marcan-complaint-field marcan-complaint-field-wide">
                            <span><?php esc_html_e('Nombre completo', 'marcan'); ?> *</span>
                            <input type="text" name="consumidor_nombre" required>
                        </label>
                        <label class="marcan-complaint-field">
                            <span><?php esc_html_e('Tipo de documento', 'marcan'); ?></span>
                            <select name="consumidor_tipo_doc">
                                <option value="DNI">DNI</option>
                                <option value="CE"><?php esc_html_e('Carné de extranjería', 'marcan'); ?></option>
                                <option value="Pasaporte"><?php esc_html_e('Pasaporte', 'marcan'); ?></option>
                                <option value="RUC">RUC</option>
                            </select>
                        </label>
                        <label class="marcan-complaint-field">
                            <span><?php esc_html_e('N° de documento', 'marcan'); ?> *</span>
                            <input type="text" name="consumidor_documento" required>
                        </label>
                        <label class="marcan-complaint-field marcan-complaint-field-wide">
                            <span><?php esc_html_e('Domicilio', 'marcan'); ?> *</span>
                            <input type="text" name="consumidor_domicilio" required>
                        </label>
                        <label class="marcan-complaint-field">
                            <span><?php esc_html_e('Teléfono / celular', 'marcan'); ?> *</span>
                            <input type="text" name="consumidor_telefono" required>
                        </label>
                        <label class="marcan-complaint-field">
                            <span><?php esc_html_e('Correo electrónico', 'marcan'); ?> *</span>
                            <input type="email" name="consumidor_email" required>
                        </label>
                        <label class="marcan-complaint-check marcan-complaint-field-wide">
                            <input type="checkbox" name="consumidor_menor" value="1" id="marcan-complaint-menor">
                            <span><?php esc_html_e('El consumidor es menor de edad', 'marcan'); ?></span>
                        </label>
                        <label class="marcan-complaint-field marcan-complaint-field-wide marcan-complaint-apoderado" hidden>
                            <span><?php esc_html_e('Nombre del padre, madre o apoderado', 'marcan'); ?> *</span>
                            <input type="text" name="apoderado_nombre">
                        </label>
                    </div>
                </fieldset>

                <fieldset class="marcan-complaint-fieldset">
                    <legend><?php esc_html_e('2. Identificación del bien contratado', 'marcan'); ?></legend>
                    <div class="marcan-complaint-grid">
                        <div class="marcan-complaint-field marcan-complaint-field-wide">
                            <span class="marcan-complaint-label"><?php esc_html_e('Tipo', 'marcan'); ?></span>
                            <div class="marcan-complaint-radios">
                                <label><input type="radio" name="bien_tipo" value="Producto" checked> <?php esc_html_e('Producto', 'marcan'); ?></label>
                                <label><input type="radio" name="bien_tipo" value="Servicio"> <?php esc_html_e('Servicio', 'marcan'); ?></label>
                            </div>
                        </div>
                        <label class="marcan-complaint-field">
                            <span><?php esc_html_e('Monto reclamado (opcional)', 'marcan'); ?></span>
                            <input type="text" name="bien_monto" placeholder="S/">
                        </label>
                        <label class="marcan-complaint-field marcan-complaint-field-wide">
                            <span><?php esc_html_e('Descripción del bien o servicio', 'marcan'); ?> *</span>
                            <textarea name="bien_descripcion" rows="2" required></textarea>
                        </label>
                    </div>
                </fieldset>

                <fieldset class="marcan-complaint-fieldset">
                    <legend><?php esc_html_e('3. Detalle de la reclamación', 'marcan'); ?></legend>
                    <div class="marcan-complaint-grid">
                        <div class="marcan-complaint-field marcan-complaint-field-wide">
                            <span class="marcan-complaint-label"><?php esc_html_e('Tipo', 'marcan'); ?> *</span>
                            <div class="marcan-complaint-radios">
                                <label><input type="radio" name="reclamo_tipo" value="Reclamo" required> <?php esc_html_e('Reclamo', 'marcan'); ?></label>
                                <label><input type="radio" name="reclamo_tipo" value="Queja"> <?php esc_html_e('Queja', 'marcan'); ?></label>
                            </div>
                            <p class="marcan-complaint-hint"><?php esc_html_e('Reclamo: disconformidad con el producto o servicio. Queja: malestar respecto a la atención.', 'marcan'); ?></p>
                        </div>
                        <label class="marcan-complaint-field marcan-complaint-field-wide">
                            <span><?php esc_html_e('Detalle', 'marcan'); ?> *</span>
                            <textarea name="detalle" rows="4" required></textarea>
                        </label>
                        <label class="marcan-complaint-field marcan-complaint-field-wide">
                            <span><?php esc_html_e('Pedido del consumidor', 'marcan'); ?> *</span>
                            <textarea name="pedido" rows="3" required></textarea>
                        </label>
                    </div>
                </fieldset>

                <label class="marcan-complaint-check">
                    <input type="checkbox" name="acepta" value="1" required>
                    <span><?php esc_html_e('Declaro que los datos consignados son verídicos. Acepto el tratamiento de mis datos personales para la atención de esta hoja. La formulación del reclamo no impide acudir a otras vías de solución de controversias ni es requisito previo para una denuncia ante el Indecopi.', 'marcan'); ?></span>
                </label>

                <div class="marcan-complaint-actions">
                    <button type="submit" class="marcan-complaint-submit"><?php esc_html_e('Enviar hoja de reclamación', 'marcan'); ?></button>
                    <p class="marcan-complaint-feedback" role="status" aria-live="polite"></p>
                </div>
            </form>
        </div>
    </section>
</main>
<script>
(function () {
    var form = document.getElementById('marcan-complaint-form');
    if (!form) return;
    var ajaxUrl = <?php echo wp_json_encode(admin_url('admin-ajax.php')); ?>;
    var nonce = <?php echo wp_json_encode(wp_create_nonce('marcan_complaint_form')); ?>;
    var menor = document.getElementById('marcan-complaint-menor');
    var apoderado = form.querySelector('.marcan-complaint-apoderado');
    if (menor && apoderado) {
        menor.addEventListener('change', function () {
            apoderado.hidden = !menor.checked;
            var input = apoderado.querySelector('input');
            if (input) input.required = menor.checked;
        });
    }
    var feedback = form.querySelector('.marcan-complaint-feedback');
    var submit = form.querySelector('.marcan-complaint-submit');
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!form.checkValidity()) { form.reportValidity(); return; }
        feedback.className = 'marcan-complaint-feedback';
        feedback.textContent = '';
        submit.disabled = true;
        submit.textContent = <?php echo wp_json_encode(__('Enviando…', 'marcan')); ?>;
        var data = new FormData(form);
        data.append('action', 'marcan_complaint_submit');
        data.append('nonce', nonce);
        fetch(ajaxUrl, { method: 'POST', body: data, credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res && res.success) {
                    form.reset();
                    if (apoderado) apoderado.hidden = true;
                    feedback.className = 'marcan-complaint-feedback is-ok';
                    feedback.textContent = res.data.message;
                } else {
                    feedback.className = 'marcan-complaint-feedback is-error';
                    feedback.textContent = (res && res.data && res.data.message) ? res.data.message : <?php echo wp_json_encode(__('Ocurrió un error. Inténtalo nuevamente.', 'marcan')); ?>;
                }
            })
            .catch(function () {
                feedback.className = 'marcan-complaint-feedback is-error';
                feedback.textContent = <?php echo wp_json_encode(__('Ocurrió un error de conexión. Inténtalo nuevamente.', 'marcan')); ?>;
            })
            .finally(function () {
                submit.disabled = false;
                submit.textContent = <?php echo wp_json_encode(__('Enviar hoja de reclamación', 'marcan')); ?>;
            });
    });
})();
</script>
<?php
get_footer();
