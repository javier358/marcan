<?php
/**
 * Terminos y Condiciones page template.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

$terms_sections = array(
    array(
        'title' => 'Términos y Condiciones',
        'subtitle' => 'Condiciones generales de uso del sitio web de Marcan.',
    ),
    array(
        'intro' => true,
        'paragraphs' => array(
            'El presente documento establece los Términos y Condiciones de uso del sitio web www.marcan.com.pe (en adelante, "el Sitio Web"), propiedad de Inmobiliaria y Constructora Marcan S.A., identificada con RUC N° 20133793195, con domicilio en Avenida Santa Cruz N° 820, oficina 402, distrito de Miraflores, Lima, Perú.',
            'El acceso y uso del Sitio Web implica la aceptación plena y sin reservas de los presentes Términos y Condiciones. Si no está de acuerdo con los mismos, le solicitamos abstenerse de utilizar el Sitio Web.',
        ),
    ),
    array(
        'title' => 'Uso del Sitio Web',
        'paragraphs' => array(
            'El usuario se compromete a utilizar el Sitio Web de conformidad con la ley, la moral, las buenas costumbres y el orden público, así como con lo dispuesto en los presentes Términos y Condiciones.',
            'Queda prohibido el uso del Sitio Web con fines ilícitos, lesivos de derechos de terceros o que puedan dañar, inutilizar, sobrecargar o deteriorar el Sitio Web o impedir su normal utilización.',
        ),
    ),
    array(
        'title' => 'Propiedad intelectual',
        'paragraphs' => array(
            'Todos los contenidos del Sitio Web —incluyendo textos, fotografías, gráficos, imágenes, iconos, logotipos, marcas, diseños, software y demás elementos— son propiedad exclusiva de Inmobiliaria y Constructora Marcan S.A. o de terceros que han autorizado su uso, y están protegidos por la legislación peruana sobre propiedad intelectual.',
            'Queda prohibida la reproducción, distribución, comunicación pública, transformación o cualquier otra forma de explotación de los contenidos del Sitio Web sin la autorización previa y por escrito de Inmobiliaria y Constructora Marcan S.A.',
        ),
    ),
    array(
        'title' => 'Información sobre los proyectos inmobiliarios',
        'paragraphs' => array(
            'La información contenida en el Sitio Web respecto de los proyectos inmobiliarios —incluyendo precios, metrajes, planos, características, fechas de entrega y disponibilidad— es de carácter referencial y puede estar sujeta a modificaciones sin previo aviso.',
            'Las imágenes, renders, recorridos virtuales y demás representaciones visuales de los proyectos son de carácter ilustrativo y pueden diferir del producto final entregado.',
            'Los precios publicados no incluyen IGV ni gastos notariales o registrales, salvo indicación expresa en contrario.',
        ),
    ),
    array(
        'title' => 'Exclusión de garantías y responsabilidad',
        'paragraphs' => array(
            'Inmobiliaria y Constructora Marcan S.A. no garantiza la disponibilidad, continuidad o infalibilidad del funcionamiento del Sitio Web, ni la ausencia de virus u otros elementos dañinos en el mismo.',
            'Inmobiliaria y Constructora Marcan S.A. no será responsable por los daños y perjuicios de cualquier naturaleza que pudieran derivarse de la falta de disponibilidad o continuidad del funcionamiento del Sitio Web, de los fallos en el acceso a sus distintas páginas, ni de la transmisión de virus o elementos dañinos.',
        ),
    ),
    array(
        'title' => 'Enlaces a terceros',
        'paragraphs' => array(
            'El Sitio Web puede contener enlaces a sitios web de terceros. Estos enlaces se proporcionan únicamente para conveniencia del usuario. Inmobiliaria y Constructora Marcan S.A. no controla ni asume responsabilidad alguna por el contenido, las políticas de privacidad o las prácticas de dichos sitios web de terceros.',
        ),
    ),
    array(
        'title' => 'Modificaciones',
        'paragraphs' => array(
            'Inmobiliaria y Constructora Marcan S.A. se reserva el derecho de modificar en cualquier momento los presentes Términos y Condiciones. Las modificaciones serán publicadas en el Sitio Web y entrarán en vigor desde el momento de su publicación. Se recomienda al usuario revisar periódicamente los Términos y Condiciones.',
        ),
    ),
    array(
        'title' => 'Legislación aplicable y jurisdicción',
        'paragraphs' => array(
            'Los presentes Términos y Condiciones se rigen por la legislación peruana. Para cualquier controversia derivada del uso del Sitio Web, las partes se someten a la jurisdicción de los jueces y tribunales del distrito de Miraflores, Lima, Perú, renunciando a cualquier otro fuero que pudiera corresponderles.',
        ),
    ),
    array(
        'title' => 'Contacto',
        'paragraphs' => array(
            'Para cualquier consulta sobre los presentes Términos y Condiciones, puede contactarnos a través del formulario de contacto disponible en el Sitio Web o escribiendo a <a href="mailto:ventas@marcan.com.pe">ventas@marcan.com.pe</a>.',
        ),
    ),
);
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
                foreach ($terms_sections as $idx => $section) :
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
            <p><?php esc_html_e('Si deseas obtener más información sobre nuestros términos y condiciones, contáctanos.', 'marcan'); ?></p>
            <div class="marcan-privacy-contact-links">
                <a href="mailto:ventas@marcan.com.pe" class="marcan-privacy-contact-btn">ventas@marcan.com.pe</a>
                <button class="marcan-privacy-contact-btn marcan-privacy-contact-btn-dark" type="button" data-open-contact-modal><?php esc_html_e('Formulario de contacto', 'marcan'); ?></button>
            </div>
        </div>
    </section>
</main>
<?php
get_footer();
