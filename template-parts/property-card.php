<?php
/**
 * Property card partial.
 *
 * @package Marcan
 */

$price = marcan_get_property_meta(get_the_ID(), 'price');
$area = marcan_get_property_meta(get_the_ID(), 'area');
$address = marcan_get_property_meta(get_the_ID(), 'address');
$post_id = get_the_ID();
?>
<article <?php post_class('property-card'); ?> data-reveal>
    <a href="<?php the_permalink(); ?>" class="property-card-link">
        <div class="property-card-media">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('large'); ?>
            <?php else : ?>
                <div class="property-placeholder">marcan</div>
            <?php endif; ?>
        </div>
        <div class="property-card-body">
            <p<?php echo marcan_font_size_attrs(marcan_get_field_font_size('ubicacion', $post_id)); ?>><?php echo esc_html($address ?: __('Disponible', 'marcan')); ?></p>
            <h2><?php the_title(); ?></h2>
            <div>
                <?php if ($price) : ?><span<?php echo marcan_font_size_attrs(marcan_get_field_font_size('precio', $post_id)); ?>><?php echo esc_html($price); ?></span><?php endif; ?>
                <?php if ($area) : ?><span><?php echo esc_html($area); ?></span><?php endif; ?>
            </div>
        </div>
    </a>
</article>
