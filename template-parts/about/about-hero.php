<?php
if (!defined('ABSPATH')) {
    exit;
}

$about = marcan_get_about_settings();
$hero_images = is_array($about['hero_images'] ?? null) ? $about['hero_images'] : array();
$hero_picture = marcan_render_hero_picture($hero_images, '', array(
    'picture_class' => 'marcan-about-hero-image',
    'eager' => true,
));
$has_hero = !empty($about['hero_intro']) || $hero_picture !== '';
?>

<?php if ($has_hero) : ?>
    <section class="marcan-about-hero">
        <?php if (!empty($about['hero_intro'])) : ?>
            <div class="marcan-about-hero-copy">
                <div><?php echo wp_kses_post(wpautop((string) $about['hero_intro'])); ?></div>
            </div>
        <?php endif; ?>
        <?php echo $hero_picture; ?>
    </section>
<?php endif; ?>
