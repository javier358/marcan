<?php
/**
 * Politicas de privacidad page template.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

$privacy_sections = array(
    array(
        'title' => 'Política de Privacidad',
        'subtitle' => 'Información sobre el tratamiento de datos personales en los canales digitales de Marcan.',
    ),
    array(
        'intro' => true,
        'paragraphs' => array(
            'Inmobiliaria y Constructora Marcan S.A. reconoce la seguridad de los datos personales proporcionados a través de su portal web motivo por el cual brinda a la información el mayor interés y cuidado.',
            'En este sentido, a través de esta política brinda las condiciones y medidas de seguridad con el propósito de salvaguardar el derecho de privacidad y confidencialidad de los datos personales conferidos, de acuerdo con lo establecido en la Ley N° 29733 y su Reglamento el Decreto Supremo N° 003-2013-JUS.',
        ),
    ),
    array(
        'title' => 'Identificación, domicilio y titular de los datos personales',
        'paragraphs' => array(
            'Inmobiliaria y Constructora Marcan S.A. debidamente identificada con RUC N° 20133793195 y con domicilio en Avenida Santa Cruz N° 820, oficina 402, distrito de Miraflores.',
            'El banco de datos donde se registra la información recopilada por el sitio web es denominado banco de datos personales: "usuarios de la página web" y "quejas y reclamos".',
        ),
    ),
    array(
        'title' => 'Finalidad de la recopilación de los datos personales',
        'paragraphs' => array(
            'Recopilar la información de los usuarios web para gestionar las relaciones comerciales y de calidad.',
        ),
    ),
    array(
        'title' => 'Datos personales obligatorios',
        'paragraphs' => array(
            'Para llevar a cabo las finalidades descritas en el numeral anterior, se les solicitará a los usuarios web cuando menos los siguientes datos: Nombre, Apellido Paterno, Apellido Materno, E-mail, Teléfono Fijo, Teléfono Celular, Proyecto inmobiliario y unidad o unidades inmobiliarias de su interés o en el cual haya adquirido una propiedad, Dirección (en caso de no tener relación con la empresa).',
        ),
    ),
    array(
        'title' => 'Consecuencias de no proporcionar los datos personales requeridos',
        'paragraphs' => array(
            'En caso no se nos proporcione los datos personales considerados como obligatorios, no se podrá establecer una relación comercial con usted, motivo por el cual nos veremos impedidos de cumplir con los ofrecimientos establecidos en nuestra plataforma virtual.',
        ),
    ),
    array(
        'title' => 'Plazo de conservación de los datos personales',
        'paragraphs' => array(
            'Los datos personales conferidos serán conservados el tiempo necesario para cumplir con las finalidades para la cuales fueron recopilados o hasta que el titular revoque su consentimiento.',
        ),
    ),
    array(
        'title' => 'Cómo utilizamos los datos personales recopilados',
        'paragraphs' => array(
            'Utilizamos la información recopilada a través de nuestro portal web para distintos propósitos, entre los cuales tenemos:',
        ),
        'list' => array(
            'Entregar información a los usuarios sobre las consultas hechas a través del formulario.',
            'Preparar y entregar al titular de los datos personales publicidad de nuestros proyectos, promociones o comunicados.',
            'Realizar encuestas sobre proyectos o servicios.',
            'Envío de invitaciones a actividades convocadas por Inmobiliaria y Constructora Marcan S.A.',
            'Actualizar y consultar datos de contacto y otra información relevante.',
            'Evaluar la capacidad de endeudamiento, comportamiento de pago y de consumo, patrimonio, gustos y preferencias de consumo del titular de los datos personales.',
            'Generar modelos predictivos y alimentarlos con los datos personales.',
            'Elaborar estadísticas y/o estudios de comportamiento, gustos y/o tendencias.',
            'Las demás finalidades que se informen de manera previa y expresa a los titulares de datos personales previo a su tratamiento.',
        ),
    ),
    array(
        'title' => 'Flujo transfronterizo de la información',
        'paragraphs' => array(
            'Inmobiliaria y Constructora Marcan S.A. realiza la comunicación de datos personales a nivel internacional, remitiendo información a los Estados Unidos de América a la empresa Inmotion Hosting, Inc., con la finalidad de utilizar el servicio de web hosting a efectos de permitir el flujo de archivos y datos que conforman la página web, así como de los datos que se recopilan a través de ella.',
        ),
    ),
    array(
        'title' => 'Ejercicio de derechos ARCO',
        'paragraphs' => array(
            'Los usuarios web podrán restringir la recopilación o el uso de la información personal proporcionada a través de este formulario. Así mismo, podrán rectificar su información en caso que los datos sean incompletos o inexactos y/o cancelarlos cuando ya no se requieren para la finalidad conferida.',
            'Para hacer ejercicio de sus derechos ARCO escríbanos a <a href="mailto:ventas@marcan.com.pe">ventas@marcan.com.pe</a>.',
        ),
    ),
    array(
        'title' => 'Seguridad y confidencialidad',
        'paragraphs' => array(
            'Inmobiliaria y Constructora Marcan S.A. cuenta con las medidas técnicas, organizativas y legales necesarias a fin de garantizar la seguridad y confidencialidad de los datos personales. Los datos serán tratados teniendo en consideración los principios de legalidad, consentimiento, proporcionalidad, calidad, finalidad, disposición de recurso, seguridad y nivel de protección adecuado, protegiendo de esta manera los datos conferidos a cabalidad.',
        ),
    ),
    array(
        'title' => 'Vigencia y modificación de la política de privacidad',
        'paragraphs' => array(
            'Inmobiliaria y Constructora Marcan S.A. podrá modificar en cualquier momento la política de protección de datos personales. Cualquier cambio circunstancial será oportunamente comunicado antes de su implementación en su respectivo portal web <a href="https://marcan.com.pe">www.marcan.com.pe</a>.',
        ),
    ),
    array(
        'title' => 'Consultas',
        'paragraphs' => array(
            'En caso de tener consultas respecto al alcance de la presente política no dude en comunicarse con nosotros a <a href="mailto:ventas@marcan.com.pe">ventas@marcan.com.pe</a>.',
        ),
    ),
    array(
        'title' => 'Integridad del documento',
        'paragraphs' => array(
            'Los anexos a la política de privacidad conforman parte integrante del presente documento; y, rigen de acuerdo a lo estipulado en cuanto le sea aplicable.',
        ),
    ),
);

$last_updated = 'Última actualización: ' . get_the_modified_date('', get_queried_object_id());
?>
<main class="marcan-page-shell marcan-page-shell-privacy">
    <section class="marcan-privacy-hero">
        <div class="marcan-privacy-hero-inner">
            <a href="javascript:history.back()" class="marcan-privacy-back" aria-label="<?php esc_attr_e('Volver', 'marcan'); ?>">
                <img src="<?php echo esc_url(marcan_asset_uri('images/figma-tour-arrow-left-v2.svg')); ?>" alt="" aria-hidden="true">
            </a>
            <h1><?php the_title(); ?></h1>
        </div>
    </section>

    <section class="marcan-privacy-body">
        <div class="marcan-privacy-prose">
            <?php
            $custom_content = trim(get_post_field('post_content', get_queried_object_id()));
            if ($custom_content !== '') :
                echo apply_filters('the_content', $custom_content);
            else :
                foreach ($privacy_sections as $idx => $section) :
                    if (isset($section['subtitle'])) continue;
                    if (isset($section['intro']) && $section['intro']) : ?>
                        <div class="marcan-privacy-intro">
                            <?php foreach ($section['paragraphs'] as $p) : ?>
                                <p><?php echo esc_html($p); ?></p>
                            <?php endforeach; ?>
                        </div>
                        <?php continue;
                    endif; ?>
                    <div class="marcan-privacy-section">
                        <?php if (isset($section['title'])) : ?>
                            <h2><?php echo esc_html($section['title']); ?></h2>
                        <?php endif; ?>
                        <?php if (isset($section['paragraphs'])) :
                            foreach ($section['paragraphs'] as $p) : ?>
                                <p><?php echo marcan_rich_inline($p); ?></p>
                            <?php endforeach;
                        endif; ?>
                        <?php if (isset($section['list'])) : ?>
                            <ul>
                                <?php foreach ($section['list'] as $item) : ?>
                                    <li><?php echo esc_html($item); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                <?php endforeach;
            endif; ?>
        </div>
    </section>

    <section class="marcan-privacy-contact">
        <div class="marcan-privacy-contact-card">
            <h2><?php esc_html_e('¿Tienes consultas?', 'marcan'); ?></h2>
            <p><?php esc_html_e('Si deseas ejercer tus derechos ARCO o tienes dudas sobre nuestra política de privacidad, contáctanos.', 'marcan'); ?></p>
            <div class="marcan-privacy-contact-links">
                <a href="mailto:ventas@marcan.com.pe" class="marcan-privacy-contact-btn">ventas@marcan.com.pe</a>
                <button class="marcan-privacy-contact-btn marcan-privacy-contact-btn-dark" type="button" data-open-contact-modal><?php esc_html_e('Formulario de contacto', 'marcan'); ?></button>
            </div>
        </div>
    </section>
</main>
<?php
get_footer();
