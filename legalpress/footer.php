<?php
/**
 * Footer Template
 * 
 * Displays the site footer with about section, navigation, and copyright.
 * This file is loaded via get_footer() in other templates.
 * 
 * @package LegalPress
 * @since 2.0.0
 */
?>

</main><!-- #main-content -->

<footer class="site-footer" role="contentinfo">
    <div class="container">
        <div class="footer-inner">

            <!-- Footer About Section -->
            <div class="footer-section footer-about">
                <h4 class="footer-title"><?php esc_html_e('About Us', 'legalpress'); ?></h4>
                <p class="footer-text"><?php echo esc_html(legalpress_get_footer_about()); ?></p>

                <?php if (has_custom_logo()): ?>
                    <div class="footer-logo">
                        <?php the_custom_logo(); ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Footer Quick Links -->
            <div class="footer-section footer-nav">
                <h4 class="footer-title"><?php esc_html_e('Quick Links', 'legalpress'); ?></h4>

                <?php
                if (has_nav_menu('footer')) {
                    wp_nav_menu(array(
                        'theme_location' => 'footer',
                        'menu_class' => 'footer-menu',
                        'container' => false,
                        'depth' => 1,
                        'fallback_cb' => false,
                    ));
                } else {
                    // Fallback links
                    ?>
                    <ul class="footer-menu">
                        <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'legalpress'); ?></a>
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
            <div class="footer-section footer-nav">
                <h4 class="footer-title"><?php esc_html_e('Categories', 'legalpress'); ?></h4>
                <ul class="footer-menu">
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

            <!-- Footer Social Links -->
            <div class="footer-section footer-social">
                <h4 class="footer-title"><?php esc_html_e('Connect', 'legalpress'); ?></h4>
                <div class="social-links">
                    <a href="#" class="social-link" aria-label="Twitter">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path
                                d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z" />
                        </svg>
                    </a>
                    <a href="#" class="social-link" aria-label="LinkedIn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z" />
                            <rect x="2" y="9" width="4" height="12" />
                            <circle cx="4" cy="4" r="2" />
                        </svg>
                    </a>
                    <a href="#" class="social-link" aria-label="RSS">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M4 11a9 9 0 0 1 9 9" />
                            <path d="M4 4a16 16 0 0 1 16 16" />
                            <circle cx="5" cy="19" r="1" />
                        </svg>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer Bottom / Copyright -->
    <div class="footer-bottom">
        <div class="container">
            <p class="footer-copyright">
                <?php echo legalpress_get_copyright(); ?>
            </p>
            <p class="footer-credit">
                <?php esc_html_e('Powered by WordPress', 'legalpress'); ?>
            </p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</body>

</html>