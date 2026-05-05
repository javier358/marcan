<?php
/**
 * Single property template.
 *
 * @package Marcan
 */

get_header();

while (have_posts()) :
    the_post();
    $price = marcan_get_property_meta(get_the_ID(), 'price');
    $area = marcan_get_property_meta(get_the_ID(), 'area');
    $bedrooms = marcan_get_property_meta(get_the_ID(), 'bedrooms');
    $bathrooms = marcan_get_property_meta(get_the_ID(), 'bathrooms');
    $parking = marcan_get_property_meta(get_the_ID(), 'parking');
    $address = marcan_get_property_meta(get_the_ID(), 'address');
    $cta_label = marcan_get_property_meta(get_the_ID(), 'cta_label') ?: __('Consultar', 'marcan');
    $cta_url = marcan_get_property_meta(get_the_ID(), 'cta_url') ?: '#';
    ?>
    <article <?php post_class('property-detail'); ?>>
        <div class="property-detail-media">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('full'); ?>
            <?php endif; ?>
        </div>
        <div class="property-detail-body">
            <p class="eyebrow"><?php esc_html_e('Propiedad', 'marcan'); ?></p>
            <h1><?php the_title(); ?></h1>
            <?php if ($address) : ?>
                <p class="property-address"><?php echo esc_html($address); ?></p>
            <?php endif; ?>
            <div class="property-facts">
                <?php if ($price) : ?><span><?php echo esc_html($price); ?></span><?php endif; ?>
                <?php if ($area) : ?><span><?php echo esc_html($area); ?></span><?php endif; ?>
                <?php if ($bedrooms) : ?><span><?php echo esc_html($bedrooms); ?> dorm.</span><?php endif; ?>
                <?php if ($bathrooms) : ?><span><?php echo esc_html($bathrooms); ?> baños</span><?php endif; ?>
                <?php if ($parking) : ?><span><?php echo esc_html($parking); ?> estac.</span><?php endif; ?>
            </div>
            <div class="entry-content">
                <?php the_content(); ?>
            </div>
            <a class="button-primary" href="<?php echo esc_url($cta_url); ?>"><?php echo esc_html($cta_label); ?></a>
        </div>
    </article>
    <?php
endwhile;

get_footer();
