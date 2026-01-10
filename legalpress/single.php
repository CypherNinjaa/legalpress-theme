<?php
/**
 * Single Post Template
 * 
 * Displays individual articles with large readable typography,
 * author info, publish date, and optimized reading experience.
 * 
 * @package LegalPress
 * @since 1.0.0
 */

get_header();
?>

<?php while (have_posts()):
    the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
        <div class="container">

            <!-- Post Header -->
            <header class="single-post__header">
                <?php
                // Category badge
                $category = legalpress_get_first_category();
                if ($category):
                    ?>
                    <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="single-post__category">
                        <?php echo esc_html($category->name); ?>
                    </a>
                <?php endif; ?>

                <h1 class="single-post__title"><?php the_title(); ?></h1>

                <div class="single-post__meta">
                    <span class="single-post__author">
                        <?php esc_html_e('By', 'legalpress'); ?>
                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                            <?php the_author(); ?>
                        </a>
                    </span>
                    <span>&bull;</span>
                    <span class="single-post__date">
                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                            <?php echo esc_html(get_the_date()); ?>
                        </time>
                    </span>
                    <span>&bull;</span>
                    <span class="single-post__reading-time">
                        <?php echo esc_html(legalpress_reading_time()); ?>
                    </span>
                </div>
            </header>

            <!-- Featured Image -->
            <?php if (has_post_thumbnail()): ?>
                <figure class="single-post__featured-image">
                    <?php the_post_thumbnail('legalpress-featured'); ?>
                    <?php if (get_the_post_thumbnail_caption()): ?>
                        <figcaption class="wp-caption-text">
                            <?php the_post_thumbnail_caption(); ?>
                        </figcaption>
                    <?php endif; ?>
                </figure>
            <?php endif; ?>

            <!-- Post Content -->
            <div class="single-post__content">
                <?php
                the_content();

                // Pagination for multi-page posts
                wp_link_pages(array(
                    'before' => '<nav class="page-links" role="navigation"><span class="page-links__label">' . esc_html__('Pages:', 'legalpress') . '</span>',
                    'after' => '</nav>',
                    'link_before' => '<span class="page-links__number">',
                    'link_after' => '</span>',
                ));
                ?>
            </div>

            <!-- Post Footer with Tags -->
            <?php
            $tags = get_the_tags();
            if ($tags):
                ?>
                <footer class="single-post__footer">
                    <div class="post-tags">
                        <span class="post-tags__label"><?php esc_html_e('Tags:', 'legalpress'); ?></span>
                        <?php
                        foreach ($tags as $tag) {
                            printf(
                                '<a href="%s" class="post-tag" rel="tag">%s</a>',
                                esc_url(get_tag_link($tag->term_id)),
                                esc_html($tag->name)
                            );
                        }
                        ?>
                    </div>
                </footer>
            <?php endif; ?>

            <!-- Post Navigation (Previous/Next) -->
            <?php
            $prev_post = get_previous_post();
            $next_post = get_next_post();

            if ($prev_post || $next_post):
                ?>
                <nav class="post-navigation" aria-label="<?php esc_attr_e('Post navigation', 'legalpress'); ?>">
                    <?php if ($prev_post): ?>
                        <a href="<?php echo esc_url(get_permalink($prev_post)); ?>"
                            class="post-navigation__link post-navigation__link--prev">
                            <span class="post-navigation__label"><?php esc_html_e('← Previous', 'legalpress'); ?></span>
                            <span class="post-navigation__title"><?php echo esc_html(get_the_title($prev_post)); ?></span>
                        </a>
                    <?php else: ?>
                        <span></span>
                    <?php endif; ?>

                    <?php if ($next_post): ?>
                        <a href="<?php echo esc_url(get_permalink($next_post)); ?>"
                            class="post-navigation__link post-navigation__link--next">
                            <span class="post-navigation__label"><?php esc_html_e('Next →', 'legalpress'); ?></span>
                            <span class="post-navigation__title"><?php echo esc_html(get_the_title($next_post)); ?></span>
                        </a>
                    <?php endif; ?>
                </nav>
            <?php endif; ?>

            <!-- Related Posts -->
            <?php
            $related_query = new WP_Query(array(
                'category__in' => wp_get_post_categories(get_the_ID()),
                'post__not_in' => array(get_the_ID()),
                'posts_per_page' => 3,
                'no_found_rows' => true,
            ));

            if ($related_query->have_posts()):
                ?>
                <section class="related-posts" style="max-width: var(--content-width); margin: var(--spacing-xxl) auto 0;">
                    <h2 class="section__title" style="margin-bottom: var(--spacing-lg);">
                        <?php esc_html_e('Related Articles', 'legalpress'); ?>
                    </h2>

                    <div class="posts-grid" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));">
                        <?php
                        while ($related_query->have_posts()):
                            $related_query->the_post();
                            get_template_part('template-parts/card', 'post');
                        endwhile;
                        ?>
                    </div>
                </section>
                <?php
                wp_reset_postdata();
            endif;
            ?>

            <!-- Comments Section -->
            <?php
            if (comments_open() || get_comments_number()):
                comments_template();
            endif;
            ?>

        </div>
    </article>

<?php endwhile; ?>

<?php get_footer(); ?>