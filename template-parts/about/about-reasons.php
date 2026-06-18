<?php
if (!defined('ABSPATH')) {
    exit;
}

$about = marcan_get_about_settings();
$has_reasons = !empty($about['reasons']);
?>

<?php if ($has_reasons) : ?>
    <section class="marcan-about-reasons">
        <div class="marcan-about-section-inner marcan-about-reasons-inner">
            <?php if (!empty($about['reasons_title'])) : ?>
                <h2<?php echo marcan_font_size_attrs($about['reasons_title_font_size'] ?? array(), 'marcan-about-section-title'); ?>><?php echo marcan_rich_inline($about['reasons_title']); ?></h2>
            <?php endif; ?>
            <div class="marcan-about-reasons-grid">
                <?php foreach ($about['reasons'] as $reason) : ?>
                    <article class="marcan-about-reason">
                        <?php if (!empty($reason['number'])) : ?>
                            <div<?php echo marcan_font_size_attrs(marcan_get_row_font_size($reason, 'number'), 'marcan-about-reason-number'); ?>><?php echo marcan_rich_inline($reason['number']); ?></div>
                        <?php endif; ?>
                        <?php if (!empty($reason['text'])) : ?>
                            <div<?php echo marcan_font_size_attrs(marcan_get_row_font_size($reason, 'text'), 'marcan-about-reason-text', true); ?>><?php echo marcan_rich_block($reason['text']); ?></div>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>
