<?php
/**
 * Blog page template.
 *
 * @package Marcan
 */

if (!defined('ABSPATH')) {
    exit;
}

$blog_query = new WP_Query(array(
    'post_type'      => 'post',
    'posts_per_page' => 6,
    'post_status'    => 'publish',
));
$posts = $blog_query->posts;
$featured = $posts[0] ?? null;
$main = $posts[1] ?? null;
$sidebar = array_slice($posts, 2, 3);
$blog_content = marcan_get_blog_page_content();
wp_reset_postdata();

get_header();
?>
<main class="marcan-blog">
    <?php if ($featured) : ?>
        <section class="marcan-blog-hero">
            <div class="marcan-blog-hero-bg">
                <?php if (has_post_thumbnail($featured->ID)) : ?>
                    <img src="<?php echo esc_url(get_the_post_thumbnail_url($featured->ID, 'full')); ?>" alt="" class="marcan-blog-hero-image">
                <?php endif; ?>
            </div>
            <div class="marcan-blog-hero-panel">
                <span<?php echo marcan_font_size_attrs($blog_content['featured_label_font_size'] ?? array(), 'marcan-blog-hero-new'); ?>><?php echo esc_html($blog_content['featured_label']); ?></span>
                <h1 class="marcan-blog-hero-title">
                    <a href="<?php echo esc_url(get_permalink($featured->ID)); ?>"><?php echo esc_html(get_the_title($featured->ID)); ?></a>
                </h1>
                <div class="marcan-blog-hero-card">
                    <div class="marcan-blog-hero-card-inner">
                        <time datetime="<?php echo esc_attr(get_the_date('c', $featured->ID)); ?>"><?php echo esc_html(get_the_date('F j, Y', $featured->ID)); ?></time>
                        <p><?php echo esc_html(wp_trim_words(get_the_excerpt($featured->ID), 30)); ?></p>
                        <a href="<?php echo esc_url(get_permalink($featured->ID)); ?>" class="marcan-blog-read-btn"><?php echo esc_html(marcan_get_option_text('ui_blog_read', 'Leer publicación')); ?></a>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <section class="marcan-blog-content">
        <div class="marcan-blog-content-labels">
            <span<?php echo marcan_font_size_attrs($blog_content['important_label_font_size'] ?? array()); ?>><?php echo esc_html($blog_content['important_label']); ?></span>
            <span<?php echo marcan_font_size_attrs($blog_content['all_label_font_size'] ?? array()); ?>><?php echo esc_html($blog_content['all_label']); ?></span>
        </div>
        <div class="marcan-blog-content-grid">
            <article class="marcan-blog-main">
                <?php if ($main) : ?>
                    <?php if (has_post_thumbnail($main->ID)) : ?>
                        <a href="<?php echo esc_url(get_permalink($main->ID)); ?>" class="marcan-blog-main-image">
                            <img src="<?php echo esc_url(get_the_post_thumbnail_url($main->ID, 'large')); ?>" alt="<?php echo esc_attr(get_the_title($main->ID)); ?>">
                        </a>
                    <?php endif; ?>
                    <time datetime="<?php echo esc_attr(get_the_date('c', $main->ID)); ?>"><?php echo esc_html(get_the_date('M j, Y', $main->ID)); ?></time>
                    <div class="marcan-blog-main-text">
                        <h2><a href="<?php echo esc_url(get_permalink($main->ID)); ?>"><?php echo esc_html(get_the_title($main->ID)); ?></a></h2>
                        <p><?php echo esc_html(wp_trim_words(get_the_excerpt($main->ID), 30)); ?></p>
                    </div>
                <?php endif; ?>
            </article>
            <div class="marcan-blog-divider" aria-hidden="true"></div>
            <div class="marcan-blog-sidebar">
                <?php foreach ($sidebar as $side_post) : ?>
                    <article class="marcan-blog-sidebar-card">
                        <time datetime="<?php echo esc_attr(get_the_date('c', $side_post->ID)); ?>"><?php echo esc_html(get_the_date('M j, Y', $side_post->ID)); ?></time>
                        <h3><a href="<?php echo esc_url(get_permalink($side_post->ID)); ?>"><?php echo esc_html(get_the_title($side_post->ID)); ?></a></h3>
                    </article>
                <?php endforeach; ?>
                <a href="<?php echo esc_url(marcan_page_url('blog')); ?>" class="marcan-blog-more-btn"><?php echo esc_html(marcan_get_option_text('ui_blog_more', 'Ver más')); ?></a>
            </div>
        </div>
    </section>

    <section class="marcan-blog-about">
        <div class="marcan-blog-about-grid">
            <?php foreach (array(
                array('image_id' => $blog_content['about_image_id'], 'label' => $blog_content['about_label'], 'title' => $blog_content['about_title'], 'text' => $blog_content['about_text'], 'label_font_size' => $blog_content['about_label_font_size'] ?? array(), 'title_font_size' => $blog_content['about_title_font_size'] ?? array(), 'text_font_size' => $blog_content['about_text_font_size'] ?? array()),
                array('image_id' => $blog_content['vision_image_id'], 'label' => $blog_content['vision_label'], 'title' => $blog_content['vision_title'], 'text' => $blog_content['vision_text'], 'label_font_size' => $blog_content['vision_label_font_size'] ?? array(), 'title_font_size' => $blog_content['vision_title_font_size'] ?? array(), 'text_font_size' => $blog_content['vision_text_font_size'] ?? array()),
            ) as $about_block) : ?>
                <div class="marcan-blog-about-block">
                    <div class="marcan-blog-about-photo">
                        <?php if ($about_block['image_id']) : ?>
                            <?php echo wp_get_attachment_image((int) $about_block['image_id'], 'large', false, array('alt' => '')); ?>
                        <?php endif; ?>
                    </div>
                    <div class="marcan-blog-about-copy">
                        <p<?php echo marcan_font_size_attrs($about_block['label_font_size'] ?? array(), 'marcan-blog-about-label'); ?>><?php echo esc_html($about_block['label']); ?></p>
                        <h2<?php echo marcan_font_size_attrs($about_block['title_font_size'] ?? array()); ?>><?php echo esc_html($about_block['title']); ?></h2>
                        <p<?php echo marcan_font_size_attrs($about_block['text_font_size'] ?? array()); ?>><?php echo esc_html($about_block['text']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="marcan-blog-about-stats">
            <?php foreach ($blog_content['stats'] as $stat) : ?>
                <div class="marcan-blog-about-stat">
                    <span<?php echo marcan_font_size_attrs(marcan_get_row_font_size($stat, 'number'), 'marcan-blog-about-stat-number'); ?>><?php echo esc_html((string) ($stat['number'] ?? '')); ?></span>
                    <span<?php echo marcan_font_size_attrs(marcan_get_row_font_size($stat, 'label'), 'marcan-blog-about-stat-label'); ?>><?php echo esc_html((string) ($stat['label'] ?? '')); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="marcan-blog-cta">
        <div class="marcan-blog-cta-inner">
            <h2<?php echo marcan_font_size_attrs($blog_content['cta_title_font_size'] ?? array()); ?>><?php echo esc_html($blog_content['cta_title']); ?></h2>
            <div class="marcan-blog-cta-actions">
                <a href="<?php echo esc_url(marcan_page_url('departamentos')); ?>"<?php echo marcan_font_size_attrs($blog_content['cta_departments_label_font_size'] ?? array(), 'marcan-blog-cta-btn marcan-blog-cta-btn-yellow'); ?>><?php echo esc_html($blog_content['cta_departments_label']); ?></a>
                <a href="<?php echo esc_url(marcan_page_url('oficinas')); ?>"<?php echo marcan_font_size_attrs($blog_content['cta_offices_label_font_size'] ?? array(), 'marcan-blog-cta-btn marcan-blog-cta-btn-white'); ?>><?php echo esc_html($blog_content['cta_offices_label']); ?></a>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>
