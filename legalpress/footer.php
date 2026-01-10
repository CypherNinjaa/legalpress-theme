<?php
/**
 * Footer Template
 * 
 * Displays the site footer with about section, navigation, and copyright.
 * This file is loaded via get_footer() in other templates.
 * 
 * @package LegalPress
 * @since 1.0.0
 */
?>

</main><!-- #main-content -->

<footer class="site-footer" role="contentinfo">
    <div class="container">
        <div class="footer__inner">

            <!-- Footer About Section -->
            <div class="footer__section footer__about">
                <h4 class="footer__title"><?php esc_html_e('About Us', 'legalpress'); ?></h4>
                <p><?php echo esc_html(legalpress_get_footer_about()); ?></p>

                <?php if (has_custom_logo()): ?>
                    <div class="footer__logo" style="margin-top: 1rem; opacity: 0.8;">
                        <?php the_custom_logo(); ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Footer Quick Links -->
            <div class="footer__section footer__nav">
                <h4 class="footer__title"><?php esc_html_e('Quick Links', 'legalpress'); ?></h4>

                <?php
                if (has_nav_menu('footer')) {
                    wp_nav_menu(array(
                        'theme_location' => 'footer',
                        'container' => false,
                        'depth' => 1,
                        'fallback_cb' => false,
                    ));
                } else {
                    // Fallback links
                    ?>
                    <ul>
                        <li><a
                                href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'legalpress'); ?></a>
                        </li>
                        <?php
                        // Show top categories
                        $categories = get_categories(array(
                            'orderby' => 'count',
                            'order' => 'DESC',
                            'number' => 4,
                            'hide_empty' => true,
                        ));

                        foreach ($categories as $category) {
                            printf(
                                '<li><a href="%s">%s</a></li>',
                                esc_url(get_category_link($category->term_id)),
                                esc_html($category->name)
                            );
                        }
                        ?>
                    </ul>
                    <?php
                }
                ?>
            </div>

            <!-- Footer Categories -->
            <div class="footer__section footer__nav">
                <h4 class="footer__title"><?php esc_html_e('Categories', 'legalpress'); ?></h4>
                <ul>
                    <?php
                    $all_categories = get_categories(array(
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'number' => 6,
                        'hide_empty' => true,
                    ));

                    if (!empty($all_categories)) {
                        foreach ($all_categories as $cat) {
                            printf(
                                '<li><a href="%s">%s</a></li>',
                                esc_url(get_category_link($cat->term_id)),
                                esc_html($cat->name)
                            );
                        }
                    } else {
                        echo '<li>' . esc_html__('No categories', 'legalpress') . '</li>';
                    }
                    ?>
                </ul>
            </div>

        </div>
    </div>

    <!-- Footer Bottom / Copyright -->
    <div class="footer__bottom">
        <div class="container">
            <p class="footer__copyright">
                <?php echo legalpress_get_copyright(); ?>
            </p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</body>

</html>