<?php
/**
 * Similar Posts Template Part
 * 
 * Displays similar posts on single post pages
 * 
 * @package LegalPress
 * @since 2.5.0
 */

// Check if similar posts is enabled
if (!get_theme_mod('legalpress_enable_similar_posts', true)) {
    return;
}

$posts_count = get_theme_mod('legalpress_similar_posts_count', 3);
$section_title = get_theme_mod('legalpress_similar_posts_title', 'Similar Posts');

// Get categories of current post
$categories = wp_get_post_categories(get_the_ID());

if (empty($categories)) {
    return;
}

// Query similar posts
$similar_query = new WP_Query(array(
    'category__in'           => $categories,
    'post__not_in'           => array(get_the_ID()),
    'posts_per_page'         => $posts_count,
    'orderby'                => 'rand',
    'no_found_rows'          => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
));

if (!$similar_query->have_posts()) {
    return;
}
?>

<section class="similar-posts">
    <div class="container">
        
        <!-- Section Header -->
        <div class="similar-posts__header">
            <h2 class="similar-posts__title">
                <svg class="similar-posts__icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                </svg>
                <?php echo esc_html($section_title); ?>
            </h2>
        </div>

        <!-- Posts Grid -->
        <div class="similar-posts__grid">
            <?php while ($similar_query->have_posts()): $similar_query->the_post(); ?>
            
            <article class="similar-post-card" data-href="<?php the_permalink(); ?>">
                <a href="<?php the_permalink(); ?>" class="similar-post-card__link-overlay" aria-label="<?php echo esc_attr(get_the_title()); ?>"></a>
                
                <!-- Thumbnail -->
                <div class="similar-post-card__image">
                    <?php if (has_post_thumbnail()): ?>
                        <?php the_post_thumbnail('legalpress-card', array(
                            'class' => 'similar-post-card__img',
                            'loading' => 'lazy'
                        )); ?>
                    <?php else: ?>
                        <div class="similar-post-card__placeholder">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" opacity="0.3">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Content -->
                <div class="similar-post-card__content">
                    
                    <!-- Title -->
                    <h3 class="similar-post-card__title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>

                    <!-- Meta -->
                    <div class="similar-post-card__meta">
                        <div class="similar-post-card__author">
                            <?php echo get_avatar(get_the_author_meta('ID'), 28, '', '', array('class' => 'similar-post-card__avatar')); ?>
                            <span class="similar-post-card__author-name">
                                <?php esc_html_e('By', 'legalpress'); ?> 
                                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                    <?php the_author(); ?>
                                </a>
                            </span>
                        </div>
                        <span class="similar-post-card__date">
                            <?php echo esc_html(get_the_date()); ?>
                        </span>
                    </div>

                </div>
            </article>

            <?php endwhile; ?>
        </div>

    </div>
</section>

<?php
wp_reset_postdata();
?>
