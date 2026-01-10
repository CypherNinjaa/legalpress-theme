<?php
/**
 * Main Index Template
 * 
 * The main template file used as a fallback for all pages.
 * Displays posts in a grid layout with pagination.
 * 
 * @package LegalPress
 * @since 1.0.0
 */

get_header();
?>

<section class="section">
    <div class="container">

        <?php if (is_home() && !is_front_page()): ?>
            <header class="archive-header" style="text-align: left; background: none; padding: 0 0 var(--spacing-lg);">
                <h1 class="archive-header__title"><?php single_post_title(); ?></h1>
            </header>
        <?php endif; ?>

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
                <p><?php esc_html_e('It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'legalpress'); ?>
                </p>
                <?php get_search_form(); ?>
            </div>

        <?php endif; ?>

    </div>
</section>

<?php get_footer(); ?>