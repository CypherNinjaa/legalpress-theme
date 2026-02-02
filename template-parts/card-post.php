<?php
/**
 * Post Card Template Part - Enhanced Version
 * 
 * Beautiful animated post card with hover effects.
 * Used in grids on homepage, archives, and related posts.
 * 
 * @package LegalPress
 * @since 2.0.0
 */

// Card variation - can be passed via $args
$card_class = isset($args['class']) ? $args['class'] : '';
$show_excerpt = isset($args['show_excerpt']) ? $args['show_excerpt'] : true;
$animation_delay = isset($args['delay']) ? $args['delay'] : 0;

// Get category
$category = legalpress_get_first_category();
$cat_slug = $category ? sanitize_html_class($category->slug) : 'default';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post-card reveal ' . esc_attr($card_class)); ?>
    data-animate="fade-in-up" data-href="<?php the_permalink(); ?>"
    style="<?php echo $animation_delay ? 'animation-delay: ' . esc_attr($animation_delay) . 'ms;' : ''; ?>">

    <!-- Full card clickable overlay -->
    <a href="<?php the_permalink(); ?>" class="post-card-link-overlay" aria-label="<?php echo esc_attr(get_the_title()); ?>"></a>

    <!-- Card Image -->
    <div class="post-card-image">
        <a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(get_the_title()); ?>">
            <?php if (has_post_thumbnail()): ?>
                <?php the_post_thumbnail('legalpress-card', array(
                    'loading' => 'lazy'
                )); ?>
            <?php else: ?>
                <!-- Placeholder when no image -->
                <div class="post-card-placeholder">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"
                        opacity="0.3">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                        <circle cx="8.5" cy="8.5" r="1.5" />
                        <polyline points="21 15 16 10 5 21" />
                    </svg>
                </div>
            <?php endif; ?>
        </a>

        <div class="post-card-image-overlay"></div>

        <?php if ($category): ?>
            <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>"
                class="post-card-category category-<?php echo esc_attr($cat_slug); ?>">
                <?php echo esc_html($category->name); ?>
            </a>
        <?php endif; ?>

        <span class="post-card-read-time">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10" />
                <polyline points="12 6 12 12 16 14" />
            </svg>
            <?php echo esc_html(legalpress_reading_time()); ?>
        </span>
    </div>

    <!-- Card Content -->
    <div class="post-card-content">
        <h3 class="post-card-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <?php if ($show_excerpt && has_excerpt()): ?>
            <p class="post-card-excerpt">
                <?php echo esc_html(wp_trim_words(get_the_excerpt(), 18)); ?>
            </p>
        <?php endif; ?>

        <div class="post-card-footer">
            <div class="post-card-author">
                <?php echo get_avatar(get_the_author_meta('ID'), 28); ?>
                <span><?php the_author(); ?></span>
            </div>
            <div class="post-card-date">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                    <line x1="16" y1="2" x2="16" y2="6" />
                    <line x1="8" y1="2" x2="8" y2="6" />
                    <line x1="3" y1="10" x2="21" y2="10" />
                </svg>
                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                    <?php echo esc_html(get_the_date('M j')); ?>
                </time>
            </div>
        </div>
    </div>

</article>