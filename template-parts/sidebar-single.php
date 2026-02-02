<?php
/**
 * Sidebar - Related & Trending News
 * 
 * Displays related and trending news widgets on single posts
 * 
 * @package LegalPress
 * @since 2.5.0
 */

// Check if sidebar is enabled
if (!get_theme_mod('legalpress_single_sidebar', true)) {
    return;
}

$show_related = get_theme_mod('legalpress_sidebar_related_news', true);
$show_trending = get_theme_mod('legalpress_sidebar_trending', true);
$related_count = get_theme_mod('legalpress_sidebar_related_count', 5);
$trending_days = get_theme_mod('legalpress_trending_days', 7);

// Get current post categories
$categories = wp_get_post_categories(get_the_ID());
$current_category = !empty($categories) ? get_category($categories[0]) : null;
?>

<aside class="single-sidebar" role="complementary">

    <?php if ($show_related && $current_category): ?>
    <!-- Related News Widget -->
    <div class="sidebar-widget sidebar-widget--related">
        <div class="sidebar-widget__header">
            <h3 class="sidebar-widget__title">
                <?php esc_html_e('Related News', 'legalpress'); ?>
                <a href="<?php echo esc_url(get_category_link($current_category->term_id)); ?>" class="sidebar-widget__link">›</a>
            </h3>
        </div>

        <div class="sidebar-widget__content">
            <?php
            $related_query = new WP_Query(array(
                'category__in'           => $categories,
                'post__not_in'           => array(get_the_ID()),
                'posts_per_page'         => $related_count,
                'no_found_rows'          => true,
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false,
            ));

            if ($related_query->have_posts()):
                while ($related_query->have_posts()): $related_query->the_post();
            ?>
                <article class="sidebar-post">
                    <a href="<?php the_permalink(); ?>" class="sidebar-post__link">
                        <?php if (has_post_thumbnail()): ?>
                        <div class="sidebar-post__image">
                            <?php the_post_thumbnail('thumbnail', array(
                                'class' => 'sidebar-post__img',
                                'loading' => 'lazy'
                            )); ?>
                        </div>
                        <?php endif; ?>
                        <div class="sidebar-post__content">
                            <h4 class="sidebar-post__title"><?php the_title(); ?></h4>
                            <span class="sidebar-post__date"><?php echo esc_html(get_the_date()); ?></span>
                        </div>
                    </a>
                </article>
            <?php
                endwhile;
                wp_reset_postdata();
            else:
            ?>
                <p class="sidebar-widget__empty"><?php esc_html_e('No related posts found.', 'legalpress'); ?></p>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($show_trending): ?>
    <!-- Trending News Widget -->
    <div class="sidebar-widget sidebar-widget--trending">
        <div class="sidebar-widget__header">
            <h3 class="sidebar-widget__title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                    <polyline points="17 6 23 6 23 12"/>
                </svg>
                <?php esc_html_e('Trending News', 'legalpress'); ?>
            </h3>
        </div>

        <div class="sidebar-widget__content">
            <?php
            // Get trending posts (most commented in last X days)
            $trending_query = new WP_Query(array(
                'posts_per_page'         => 5,
                'date_query'             => array(
                    array(
                        'after' => $trending_days . ' days ago',
                    ),
                ),
                'orderby'                => 'comment_count',
                'order'                  => 'DESC',
                'no_found_rows'          => true,
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false,
            ));

            // Fallback: If no trending posts, get recent posts
            if (!$trending_query->have_posts()) {
                $trending_query = new WP_Query(array(
                    'posts_per_page'         => 5,
                    'orderby'                => 'date',
                    'order'                  => 'DESC',
                    'no_found_rows'          => true,
                    'update_post_meta_cache' => false,
                    'update_post_term_cache' => false,
                ));
            }

            if ($trending_query->have_posts()):
                $counter = 1;
                while ($trending_query->have_posts()): $trending_query->the_post();
            ?>
                <article class="sidebar-post sidebar-post--numbered">
                    <span class="sidebar-post__number"><?php echo $counter; ?></span>
                    <a href="<?php the_permalink(); ?>" class="sidebar-post__link">
                        <div class="sidebar-post__content">
                            <h4 class="sidebar-post__title"><?php the_title(); ?></h4>
                            <span class="sidebar-post__date"><?php echo esc_html(get_the_date()); ?></span>
                        </div>
                    </a>
                </article>
            <?php
                $counter++;
                endwhile;
                wp_reset_postdata();
            else:
            ?>
                <p class="sidebar-widget__empty">
                    <?php 
                    printf(
                        esc_html__('No trending posts in the last %d days.', 'legalpress'),
                        $trending_days
                    ); 
                    ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php 
    // Dynamic sidebar for widgets
    if (is_active_sidebar('single-sidebar')): 
    ?>
    <div class="sidebar-widgets">
        <?php dynamic_sidebar('single-sidebar'); ?>
    </div>
    <?php endif; ?>

</aside>
