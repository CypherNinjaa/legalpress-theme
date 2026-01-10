<?php
/**
 * Single Post Template
 * 
 * Displays individual articles with large readable typography,
 * author info, publish date, and optimized reading experience.
 * 
 * @package LegalPress
 * @since 2.0.0
 */

get_header();
?>

<?php while (have_posts()):
    the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>

        <!-- Post Header -->
        <header class="single-header">
            <div class="container">
                <?php
                // Category badge
                $category = legalpress_get_first_category();
                if ($category):
                    ?>
                    <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="single-category">
                        <?php echo esc_html($category->name); ?>
                    </a>
                <?php endif; ?>

                <h1 class="single-title"><?php the_title(); ?></h1>

                <div class="single-meta">
                    <div class="single-author">
                        <?php echo get_avatar(get_the_author_meta('ID'), 48, '', '', array('class' => 'single-author-avatar')); ?>
                        <div class="single-author-info">
                            <span class="single-author-name">
                                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                    <?php the_author(); ?>
                                </a>
                            </span>
                            <span class="single-date">
                                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                    <?php echo esc_html(get_the_date()); ?>
                                </time>
                            </span>
                        </div>
                    </div>
                    <span class="single-reading-time">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        <?php echo esc_html(legalpress_reading_time()); ?>
                    </span>
                </div>
            </div>
        </header>

        <!-- Featured Image -->
        <?php if (has_post_thumbnail()): ?>
            <figure class="single-featured-image">
                <?php the_post_thumbnail('legalpress-featured', array('class' => 'single-image')); ?>
                <?php if (get_the_post_thumbnail_caption()): ?>
                    <figcaption class="single-image-caption">
                        <?php the_post_thumbnail_caption(); ?>
                    </figcaption>
                <?php endif; ?>
            </figure>
        <?php endif; ?>

        <!-- Post Content -->
        <div class="single-content">
            <div class="container container-narrow">
                <?php
                the_content();

                // Pagination for multi-page posts
                wp_link_pages(array(
                    'before' => '<nav class="page-links" role="navigation"><span class="page-links-label">' . esc_html__('Pages:', 'legalpress') . '</span>',
                    'after' => '</nav>',
                    'link_before' => '<span class="page-links-number">',
                    'link_after' => '</span>',
                ));
                ?>
            </div>
        </div>

        <!-- Post Footer with Tags -->
        <?php
        $tags = get_the_tags();
        if ($tags):
            ?>
            <footer class="single-footer">
                <div class="container container-narrow">
                    <div class="post-tags">
                        <span class="post-tags-label">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" />
                                <line x1="7" y1="7" x2="7.01" y2="7" />
                            </svg>
                            <?php esc_html_e('Tags:', 'legalpress'); ?>
                        </span>
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
                <div class="container">
                    <div class="post-navigation-inner">
                        <?php if ($prev_post): ?>
                            <a href="<?php echo esc_url(get_permalink($prev_post)); ?>" class="post-nav-link post-nav-prev">
                                <span class="post-nav-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <line x1="19" y1="12" x2="5" y2="12" />
                                        <polyline points="12 19 5 12 12 5" />
                                    </svg>
                                    <?php esc_html_e('Previous', 'legalpress'); ?>
                                </span>
                                <span class="post-nav-title"><?php echo esc_html(get_the_title($prev_post)); ?></span>
                            </a>
                        <?php else: ?>
                            <span></span>
                        <?php endif; ?>

                        <?php if ($next_post): ?>
                            <a href="<?php echo esc_url(get_permalink($next_post)); ?>" class="post-nav-link post-nav-next">
                                <span class="post-nav-label">
                                    <?php esc_html_e('Next', 'legalpress'); ?>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <line x1="5" y1="12" x2="19" y2="12" />
                                        <polyline points="12 5 19 12 12 19" />
                                    </svg>
                                </span>
                                <span class="post-nav-title"><?php echo esc_html(get_the_title($next_post)); ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
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
            <section class="related-posts">
                <div class="container">
                    <h2 class="section-title">
                        <svg class="section-title-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                        </svg>
                        <?php esc_html_e('Related Articles', 'legalpress'); ?>
                    </h2>

                    <div class="posts-grid posts-grid-3">
                        <?php
                        while ($related_query->have_posts()):
                            $related_query->the_post();
                            get_template_part('template-parts/card', 'post');
                        endwhile;
                        ?>
                    </div>
                </div>
            </section>
            <?php
            wp_reset_postdata();
        endif;
        ?>

        <!-- Comments Section -->
        <?php
        if (comments_open() || get_comments_number()):
            ?>
            <section class="comments-section">
                <div class="container container-narrow">
                    <?php comments_template(); ?>
                </div>
            </section>
            <?php
        endif;
        ?>

    </article>

<?php endwhile; ?>

<?php get_footer(); ?>