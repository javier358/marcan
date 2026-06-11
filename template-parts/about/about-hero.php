<?php
if (!defined('ABSPATH')) {
    exit;
}

$about = marcan_get_about_settings();
$has_hero = !empty($about['hero_intro']) || !empty($about['hero_image_desktop']) || !empty($about['hero_image_mobile']);
?>

<?php if ($has_hero) : ?>
    <section class="marcan-about-hero">
        <?php if (!empty($about['hero_intro'])) : ?>
            <div class="marcan-about-hero-copy">
                <div><?php echo wp_kses_post(wpautop((string) $about['hero_intro'])); ?></div>
            </div>
        <?php endif; ?>
        <?php if (!empty($about['hero_image_desktop'])) : ?>
            <div class="marcan-about-hero-image marcan-about-hero-image-desktop">
                <img src="<?php echo esc_url($about['hero_image_desktop']); ?>" alt="">
            </div>
        <?php endif; ?>
        <?php if (!empty($about['hero_image_mobile'])) : ?>
            <div class="marcan-about-hero-image marcan-about-hero-image-mobile">
                <img src="<?php echo esc_url($about['hero_image_mobile']); ?>" alt="">
            </div>
        <?php endif; ?>
    </section>
<?php endif; ?>
