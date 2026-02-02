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
                    <?php
                    $social_links = legalpress_get_social_links();
                    foreach ($social_links as $platform => $link):
                        ?>
                        <a href="<?php echo esc_url($link['url']); ?>" class="social-link"
                            aria-label="<?php echo esc_attr($link['label']); ?>" <?php echo ($platform !== 'rss') ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                            <?php echo $link['icon']; // SVG icons are safe ?>
                        </a>
                    <?php endforeach; ?>
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

<!-- Scroll to Top Button -->
<button id="scroll-to-top" class="scroll-to-top" aria-label="<?php esc_attr_e('Scroll to top', 'legalpress'); ?>" title="<?php esc_attr_e('Back to top', 'legalpress'); ?>">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="18 15 12 9 6 15"></polyline>
    </svg>
</button>

<?php wp_footer(); ?>

</body>

</html>