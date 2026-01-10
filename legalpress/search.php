<?php
/**
 * Search Results Template
 * 
 * Displays search results in a grid layout.
 * 
 * @package LegalPress
 * @since 1.0.0
 */

get_header();
?>

<!-- Search Header -->
<header class="archive-header">
    <div class="container">
        <h1 class="archive-header__title">
            <?php
            /* translators: %s: Search query */
            printf(esc_html__('Search Results for: "%s"', 'legalpress'), get_search_query());
            ?>
        </h1>

        <?php
        global $wp_query;
        if ($wp_query->found_posts > 0):
            ?>
            <p class="archive-header__description">
                <?php
                printf(
                    /* translators: %d: Number of results */
                    _n('%d result found', '%d results found', $wp_query->found_posts, 'legalpress'),
                    $wp_query->found_posts
                );
                ?>
            </p>
        <?php endif; ?>
    </div>
</header>

<!-- Search Results -->
<section class="section">
    <div class="container">

        <?php if (have_posts()): ?>

            <div class="posts-grid">
                <?php
                while (have_posts()):
                    the_post();
                    get_template_part('template-parts/card', 'post');
                endwhile;
                ?>
            </div>

            <?php legalpress_pagination(); ?>

        <?php else: ?>

            <div class="no-posts" style="text-align: center; padding: var(--spacing-xxl) 0;">
                <h2><?php esc_html_e('No Results Found', 'legalpress'); ?></h2>
                <p><?php esc_html_e('Sorry, no posts matched your search. Please try again with different keywords.', 'legalpress'); ?>
                </p>

                <div style="margin-top: var(--spacing-lg); max-width: 400px; margin-left: auto; margin-right: auto;">
                    <?php get_search_form(); ?>
                </div>

                <div style="margin-top: var(--spacing-xl);">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="error-404__button">
                        <?php esc_html_e('Back to Home', 'legalpress'); ?>
                    </a>
                </div>
            </div>

        <?php endif; ?>

    </div>
</section>

<?php get_footer(); ?>