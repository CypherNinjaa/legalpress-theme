<?php
/**
 * 404 Error Page Template
 * 
 * Displays when a page is not found.
 * Clean design with helpful navigation options.
 * 
 * @package LegalPress
 * @since 1.0.0
 */

get_header();
?>

<section class="error-404">
    <div class="container">
        <div class="error-404__inner">

            <div class="error-404__code">404</div>

            <h1 class="error-404__title">
                <?php esc_html_e('Page Not Found', 'legalpress'); ?>
            </h1>

            <p class="error-404__text">
                <?php esc_html_e('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'legalpress'); ?>
            </p>

            <a href="<?php echo esc_url(home_url('/')); ?>" class="error-404__button">
                <?php esc_html_e('Back to Homepage', 'legalpress'); ?>
            </a>

            <!-- Search Form -->
            <div style="margin-top: var(--spacing-xl);">
                <p style="margin-bottom: var(--spacing-sm); color: var(--color-text-light);">
                    <?php esc_html_e('Or try searching:', 'legalpress'); ?>
                </p>
                <?php get_search_form(); ?>
            </div>

        </div>
    </div>
</section>

<?php get_footer(); ?>