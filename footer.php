<?php
/**
 * Footer template.
 *
 * @package Marcan
 */

?>
</main>
<footer class="site-footer">
    <a class="footer-logo" href="<?php echo esc_url(home_url('/')); ?>">marcan</a>
    <div class="footer-menu">
        <?php
        wp_nav_menu(array(
            'theme_location' => 'footer',
            'container'      => false,
            'fallback_cb'    => false,
            'menu_class'     => 'footer-nav',
        ));
        ?>
    </div>
    <p><?php echo esc_html(date_i18n('Y')); ?> Marcan</p>
</footer>
<?php wp_footer(); ?>
</body>
</html>
