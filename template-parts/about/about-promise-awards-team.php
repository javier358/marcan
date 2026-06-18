<?php
if (!defined('ABSPATH')) {
    exit;
}

$about = marcan_get_about_settings();
$has_promise = !empty($about['promise_title']) || !empty($about['promise_text']) || !empty($about['promise_image_desktop']) || !empty($about['promise_image_mobile']);
$has_awards = !empty($about['awards']);
$has_team = !empty($about['team_members']);
$has_facts = $has_promise || $has_awards || $has_team;
?>

<?php if ($has_facts) : ?>
    <section class="marcan-about-facts">
        <div class="marcan-about-facts-inner">
            <?php if ($has_promise) : ?>
                <div class="marcan-about-promise">
                    <?php if (!empty($about['promise_image_desktop']) || !empty($about['promise_image_mobile'])) : ?>
                        <div class="marcan-about-promise-image">
                            <?php if (!empty($about['promise_image_desktop'])) : ?>
                                <img class="marcan-about-promise-image-desktop" src="<?php echo esc_url($about['promise_image_desktop']); ?>" alt="">
                            <?php endif; ?>
                            <?php if (!empty($about['promise_image_mobile'])) : ?>
                                <img class="marcan-about-promise-image-mobile" src="<?php echo esc_url($about['promise_image_mobile']); ?>" alt="">
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($about['promise_title']) || !empty($about['promise_text'])) : ?>
                        <div class="marcan-about-promise-card">
                            <div class="marcan-about-promise-card-inner">
                                <?php if (!empty($about['promise_title'])) : ?>
                                    <h2<?php echo marcan_font_size_attrs($about['promise_title_font_size'] ?? array()); ?>><?php echo marcan_rich_inline($about['promise_title']); ?></h2>
                                <?php endif; ?>
                                <?php if (!empty($about['promise_text'])) : ?>
                                    <div<?php echo marcan_font_size_attrs($about['promise_text_font_size'] ?? array(), '', true); ?>><?php echo marcan_rich_block($about['promise_text']); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($has_awards) : ?>
                <div class="marcan-about-awards">
                    <?php if (!empty($about['awards_title'])) : ?>
                        <h2<?php echo marcan_font_size_attrs($about['awards_title_font_size'] ?? array(), 'marcan-about-section-title marcan-about-section-title--light'); ?>><?php echo marcan_rich_inline($about['awards_title']); ?></h2>
                    <?php endif; ?>
                    <div class="marcan-about-awards-grid">
                        <?php foreach ($about['awards'] as $award) : ?>
                            <article class="marcan-about-award">
                                <?php if (!empty($award['logo'])) : ?>
                                    <div class="marcan-about-award-logo">
                                        <img src="<?php echo esc_url(marcan_about_resolve_image($award['logo'])); ?>" alt="">
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($award['year']) || !empty($award['title']) || !empty($award['text']) || !empty($award['link_url'])) : ?>
                                    <div class="marcan-about-award-content">
                                        <?php if (!empty($award['year'])) : ?>
                                            <div<?php echo marcan_font_size_attrs(marcan_get_row_font_size($award, 'year'), 'marcan-about-award-year'); ?>><?php echo marcan_rich_inline($award['year']); ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($award['title'])) : ?>
                                            <h3<?php echo marcan_font_size_attrs(marcan_get_row_font_size($award, 'title')); ?>><?php echo marcan_rich_inline($award['title']); ?></h3>
                                        <?php endif; ?>
                                        <?php if (!empty($award['text'])) : ?>
                                            <div<?php echo marcan_font_size_attrs(marcan_get_row_font_size($award, 'text'), '', true); ?>><?php echo marcan_rich_block($award['text']); ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($award['link_url'])) : ?>
                                            <a<?php echo marcan_font_size_attrs(marcan_get_row_font_size($award, 'link_label')); ?> href="<?php echo esc_url($award['link_url']); ?>"><?php echo marcan_rich_inline(!empty($award['link_label']) ? $award['link_label'] : 'Ver proyecto'); ?></a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($has_team) : ?>
                <div class="marcan-about-team">
                    <?php if (!empty($about['team_title'])) : ?>
                        <h2<?php echo marcan_font_size_attrs($about['team_title_font_size'] ?? array(), 'marcan-about-section-title marcan-about-section-title--light'); ?>><?php echo marcan_rich_inline($about['team_title']); ?></h2>
                    <?php endif; ?>
                    <div class="marcan-about-team-track" data-about-slider="team">
                        <?php foreach ($about['team_members'] as $member) : ?>
                            <article class="marcan-about-team-card">
                                <?php if (!empty($member['image_desktop']) || !empty($member['image_mobile'])) : ?>
                                    <div class="marcan-about-team-photo">
                                        <?php if (!empty($member['image_desktop'])) : ?>
                                            <img class="marcan-about-team-photo-desktop" src="<?php echo esc_url(marcan_about_resolve_image($member['image_desktop'])); ?>" alt="">
                                        <?php endif; ?>
                                        <?php if (!empty($member['image_mobile']) || !empty($member['image_desktop'])) : ?>
                                            <img class="marcan-about-team-photo-mobile" src="<?php echo esc_url(marcan_about_resolve_image($member['image_mobile'] ?? $member['image_desktop'])); ?>" alt="">
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($member['name']) || !empty($member['role']) || !empty($member['linkedin'])) : ?>
                                    <div class="marcan-about-team-meta">
                                        <?php if (!empty($member['name'])) : ?>
                                            <h3<?php echo marcan_font_size_attrs(marcan_get_row_font_size($member, 'name')); ?>><?php echo marcan_rich_inline($member['name']); ?></h3>
                                        <?php endif; ?>
                                        <?php if (!empty($member['role'])) : ?>
                                            <p<?php echo marcan_font_size_attrs(marcan_get_row_font_size($member, 'role')); ?>><?php echo marcan_rich_inline($member['role']); ?></p>
                                        <?php endif; ?>
                                        <?php if (!empty($member['linkedin'])) : ?>
                                            <a href="<?php echo esc_url($member['linkedin']); ?>" target="_blank" rel="noreferrer">LinkedIn</a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>
