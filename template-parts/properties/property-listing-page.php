<?php
if (!defined('ABSPATH')) {
    exit;
}

$kind = isset($args['kind']) ? (string) $args['kind'] : 'departamento';
$is_office = $kind === 'oficina';
$query = marcan_get_properties_by_kind($kind);
$first_post = $query->have_posts() ? $query->posts[0] : null;

$page_id = get_queried_object_id();
$get_listing_field = static function (string $field, string $fallback = '') use ($page_id): string {
    if ($page_id && function_exists('get_field')) {
        $value = get_field($field, $page_id);
        if (is_scalar($value) && (string) $value !== '') {
            return (string) $value;
        }
    }
    return $fallback;
};

$default_title = $is_office ? __('Oficinas en venta', 'marcan') : __('Departamentos en venta', 'marcan');
$default_intro = $is_office
    ? __('Espacios de trabajo pensados para invertir y crecer, en ubicaciones con alto potencial urbano.', 'marcan')
    : __('Encuentra departamentos pensados para vivir mejor, con arquitectura funcional y ubicaciones conectadas a la ciudad.', 'marcan');
$default_search_copy = $is_office
    ? __('Revisa las opciones que tenemos para ti desde S/455,222', 'marcan')
    : __('Revisa las opciones que tenemos para ti desde S/355,222', 'marcan');

$title = $get_listing_field('listing_title', $default_title);
$intro = $get_listing_field('listing_intro', $default_intro);
$search_title = $get_listing_field('listing_search_title', __('Encuentra lo que estas buscando', 'marcan'));
$search_copy = $get_listing_field('listing_search_copy', $default_search_copy);

$hero_image_id = 0;
if ($page_id && function_exists('get_field')) {
    $hero_image_id = (int) get_field('listing_hero_image', $page_id);
}
if (!$hero_image_id && $first_post) {
    $hero_image_id = marcan_get_property_image_id((int) $first_post->ID, 'listado_hero_imagen', 'home_desktop_image');
}
$hero_image_url = '';
if (!$hero_image_id && !$is_office) {
    $hero_image_url = get_theme_file_uri('assets/images/marcan-departamentos-hero-figma.png');
}
?>

<main class="marcan-property-archive marcan-property-archive-<?php echo esc_attr($kind); ?>">
    <section class="marcan-property-archive-hero">
        <div class="marcan-property-archive-hero-media">
            <?php if ($hero_image_url !== '') : ?>
                <img src="<?php echo esc_url($hero_image_url); ?>" alt="" class="marcan-property-archive-hero-image">
            <?php elseif ($hero_image_id) : ?>
                <?php echo wp_get_attachment_image($hero_image_id, 'full', false, array('alt' => '', 'class' => 'marcan-property-archive-hero-image')); ?>
            <?php endif; ?>
        </div>
        <div class="marcan-property-archive-hero-content">
        <div class="marcan-property-archive-copy">
            <h1><?php echo marcan_rich_inline($title); ?></h1>
            <div><?php echo marcan_rich_block($intro); ?></div>
        </div>
        <?php if ($is_office) : ?>
            <?php
            $reasons_title = $get_listing_field('listing_reasons_title', __('¿Por qué invertir en oficinas?', 'marcan'));
            $reasons = ($page_id && function_exists('get_field')) ? get_field('listing_reasons', $page_id) : array();
            if (!is_array($reasons) || empty($reasons)) {
                $reasons = array(
                    array('number' => '1', 'text' => __('Los contratos empresariales ofrecen ingresos estables, predecibles y seguros a largo plazo.', 'marcan')),
                    array('number' => '2', 'text' => __('Los espacios bien ubicados aumentan su valor de forma sostenida con el tiempo.', 'marcan')),
                    array('number' => '3', 'text' => __('El trabajo híbrido impulsa la búsqueda de oficinas modernas, flexibles y funcionales.', 'marcan')),
                    array('number' => '4', 'text' => __('Invertir en oficinas permite equilibrar tu portafolio, reduce riesgos y fortalece tu patrimonio.', 'marcan')),
                );
            }
            ?>
            <div class="marcan-property-archive-reasons">
                <h2><?php echo marcan_rich_inline($reasons_title); ?></h2>
                <ol>
                    <?php foreach ($reasons as $reason) : ?>
                        <li><span><?php echo marcan_rich_inline((string) ($reason['number'] ?? '')); ?></span><div><?php echo marcan_rich_block((string) ($reason['text'] ?? '')); ?></div></li>
                    <?php endforeach; ?>
                </ol>
            </div>
        <?php endif; ?>
        <div class="marcan-property-archive-search-copy">
            <h2><?php echo marcan_rich_inline($search_title); ?></h2>
            <div><?php echo marcan_rich_block($search_copy); ?></div>
        </div>
        </div>
    </section>

    <section class="marcan-property-archive-list">
        <?php if ($query->have_posts()) : ?>
            <?php $index = 0; ?>
            <?php while ($query->have_posts()) : ?>
                <?php
                $query->the_post();
                $layout = $is_office ? 'info-left' : 'media-left';
                if (!$is_office && $index % 2 === 1) {
                    $layout = 'media-left';
                }
                get_template_part('template-parts/properties/property-card-listing', null, array('post_id' => get_the_ID(), 'layout' => $layout));
                $index++;
                ?>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <div class="marcan-property-empty">
                <h2><?php esc_html_e('Aun no hay inmuebles publicados.', 'marcan'); ?></h2>
                <p><?php esc_html_e('Agrega departamentos u oficinas desde el administrador para alimentar esta pagina y el home.', 'marcan'); ?></p>
            </div>
        <?php endif; ?>
    </section>
</main>
