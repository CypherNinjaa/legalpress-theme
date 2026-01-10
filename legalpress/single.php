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

        <!-- Share Buttons -->
        <div class="share-buttons-section">
            <div class="container container-narrow">
                <div class="share-buttons">
                    <span class="share-buttons__label">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="18" cy="5" r="3"/>
                            <circle cx="6" cy="12" r="3"/>
                            <circle cx="18" cy="19" r="3"/>
                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/>
                            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
                        </svg>
                        <?php esc_html_e('Share:', 'legalpress'); ?>
                    </span>
                    
                    <div class="share-buttons__list">
                        <?php
                        $share_url = urlencode(get_permalink());
                        $share_title = urlencode(get_the_title());
                        $share_excerpt = urlencode(wp_trim_words(get_the_excerpt(), 20));
                        ?>
                        
                        <!-- Twitter/X -->
                        <a href="https://twitter.com/intent/tweet?url=<?php echo $share_url; ?>&text=<?php echo $share_title; ?>" 
                           class="share-btn share-btn--twitter" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           aria-label="<?php esc_attr_e('Share on Twitter', 'legalpress'); ?>">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                            <span><?php esc_html_e('Tweet', 'legalpress'); ?></span>
                        </a>
                        
                        <!-- Facebook -->
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" 
                           class="share-btn share-btn--facebook" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           aria-label="<?php esc_attr_e('Share on Facebook', 'legalpress'); ?>">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            <span><?php esc_html_e('Share', 'legalpress'); ?></span>
                        </a>
                        
                        <!-- LinkedIn -->
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $share_url; ?>&title=<?php echo $share_title; ?>&summary=<?php echo $share_excerpt; ?>" 
                           class="share-btn share-btn--linkedin" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           aria-label="<?php esc_attr_e('Share on LinkedIn', 'legalpress'); ?>">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                            <span><?php esc_html_e('LinkedIn', 'legalpress'); ?></span>
                        </a>
                        
                        <!-- WhatsApp -->
                        <a href="https://api.whatsapp.com/send?text=<?php echo $share_title; ?>%20<?php echo $share_url; ?>" 
                           class="share-btn share-btn--whatsapp" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           aria-label="<?php esc_attr_e('Share on WhatsApp', 'legalpress'); ?>">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            <span><?php esc_html_e('WhatsApp', 'legalpress'); ?></span>
                        </a>
                        
                        <!-- Telegram -->
                        <a href="https://t.me/share/url?url=<?php echo $share_url; ?>&text=<?php echo $share_title; ?>" 
                           class="share-btn share-btn--telegram" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           aria-label="<?php esc_attr_e('Share on Telegram', 'legalpress'); ?>">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                            </svg>
                            <span><?php esc_html_e('Telegram', 'legalpress'); ?></span>
                        </a>
                        
                        <!-- Email -->
                        <a href="mailto:?subject=<?php echo $share_title; ?>&body=<?php echo $share_excerpt; ?>%20<?php echo $share_url; ?>" 
                           class="share-btn share-btn--email"
                           aria-label="<?php esc_attr_e('Share via Email', 'legalpress'); ?>">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                            <span><?php esc_html_e('Email', 'legalpress'); ?></span>
                        </a>
                        
                        <!-- Copy Link -->
                        <button type="button" 
                                class="share-btn share-btn--copy" 
                                data-url="<?php echo esc_url(get_permalink()); ?>"
                                aria-label="<?php esc_attr_e('Copy link', 'legalpress'); ?>">
                            <svg class="icon-copy" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                            </svg>
                            <svg class="icon-check" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <span class="copy-text"><?php esc_html_e('Copy', 'legalpress'); ?></span>
                        </button>
                    </div>
                </div>
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