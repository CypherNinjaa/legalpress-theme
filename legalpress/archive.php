<?php
/**
 * Archive Template
 * 
 * Displays category, tag, author, and date archives.
 * Shows archive header with description and posts grid.
 * 
 * @package LegalPress
 * @since 1.0.0
 */

get_header();
?>

<!-- Archive Header -->
<header class="archive-header">
    <div class="container">
        <?php
        if (is_category()):
            ?>
            <h1 class="archive-header__title"><?php single_cat_title(); ?></h1>
            <?php if (category_description()): ?>
                <p class="archive-header__description"><?php echo wp_kses_post(category_description()); ?></p>
            <?php endif; ?>

        <?php elseif (is_tag()): ?>
            <h1 class="archive-header__title">
                <?php
                /* translators: %s: Tag name */
                printf(esc_html__('Tag: %s', 'legalpress'), single_tag_title('', false));
                ?>
            </h1>
            <?php if (tag_description()): ?>
                <p class="archive-header__description"><?php echo wp_kses_post(tag_description()); ?></p>
            <?php endif; ?>

        <?php elseif (is_author()): ?>
            <h1 class="archive-header__title">
                <?php
                /* translators: %s: Author name */
                printf(esc_html__('Author: %s', 'legalpress'), get_the_author());
                ?>
            </h1>
            <?php if (get_the_author_meta('description')): ?>
                <p class="archive-header__description"><?php echo wp_kses_post(get_the_author_meta('description')); ?></p>
            <?php endif; ?>

        <?php elseif (is_year()): ?>
            <h1 class="archive-header__title">
                <?php
                /* translators: %s: Year */
                printf(esc_html__('Year: %s', 'legalpress'), get_the_date('Y'));
                ?>
            </h1>

        <?php elseif (is_month()): ?>
            <h1 class="archive-header__title">
                <?php
                /* translators: %s: Month and year */
                printf(esc_html__('Month: %s', 'legalpress'), get_the_date('F Y'));
                ?>
            </h1>

        <?php elseif (is_day()): ?>
            <h1 class="archive-header__title">
                <?php
                /* translators: %s: Full date */
                printf(esc_html__('Date: %s', 'legalpress'), get_the_date());
                ?>
            </h1>

        <?php elseif (is_search()): ?>
            <h1 class="archive-header__title">
                <?php
                /* translators: %s: Search query */
                printf(esc_html__('Search Results for: %s', 'legalpress'), get_search_query());
                ?>
            </h1>

        <?php else: ?>
            <h1 class="archive-header__title"><?php esc_html_e('Archives', 'legalpress'); ?></h1>
        <?php endif; ?>

        <?php
        // Show post count
        global $wp_query;
        if ($wp_query->found_posts > 0):
            ?>
            <p class="archive-header__count"
                style="margin-top: var(--spacing-xs); color: var(--color-text-muted); font-size: 0.9375rem;">
                <?php
                printf(
                    /* translators: %d: Number of posts */
                    _n('%d article found', '%d articles found', $wp_query->found_posts, 'legalpress'),
                    $wp_query->found_posts
                );
                ?>
            </p>
        <?php endif; ?>
    </div>
</header>

<!-- Archive Posts -->
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
                <h2><?php esc_html_e('No Posts Found', 'legalpress'); ?></h2>
                <p><?php esc_html_e('No articles match your criteria. Try a different search or browse our categories.', 'legalpress'); ?>
                </p>

                <div style="margin-top: var(--spacing-lg);">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="error-404__button">
                        <?php esc_html_e('Back to Home', 'legalpress'); ?>
                    </a>
                </div>
            </div>

        <?php endif; ?>

    </div>
</section>

<?php get_footer(); ?>