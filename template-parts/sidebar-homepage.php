<?php
/**
 * Homepage Sidebar Template
 * 
 * Displays Monthly Recap and Opinion widgets
 * Inspired by LawChakra's sidebar design.
 * 
 * @package LegalPress
 * @since 2.5.0
 */

// Get settings
$monthly_recap_enabled = get_theme_mod('legalpress_monthly_recap_enable', true);
$monthly_recap_title = get_theme_mod('legalpress_monthly_recap_title', __('Monthly Recap', 'legalpress'));
$monthly_recap_count = get_theme_mod('legalpress_monthly_recap_count', 4);

$opinion_enabled = get_theme_mod('legalpress_opinion_enable', true);
$opinion_title = get_theme_mod('legalpress_opinion_title', __('Opinion', 'legalpress'));
$opinion_category = get_theme_mod('legalpress_opinion_category', 'opinion');
$opinion_count = get_theme_mod('legalpress_opinion_count', 4);

// If neither is enabled, don't show sidebar
if (!$monthly_recap_enabled && !$opinion_enabled) {
    return;
}
?>

<aside class="homepage-sidebar">
    
    <?php 
    // =========================================================================
    // OPINION WIDGET
    // =========================================================================
    if ($opinion_enabled) :
        $opinion_query = legalpress_get_opinion_posts($opinion_count, $opinion_category);
        
        if ($opinion_query->have_posts()) :
            // Get the category for the link
            $opinion_cat = get_category_by_slug($opinion_category);
    ?>
    <div class="sidebar-widget sidebar-widget--opinion reveal" data-animate="fade-in-up">
        <div class="sidebar-widget__header">
            <h3 class="sidebar-widget__title">
                <?php echo esc_html($opinion_title); ?>
                <?php if ($opinion_cat) : ?>
                <a href="<?php echo esc_url(get_category_link($opinion_cat->term_id)); ?>" class="sidebar-widget__link">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </a>
                <?php endif; ?>
            </h3>
        </div>
        
        <div class="sidebar-widget__content">
            <?php while ($opinion_query->have_posts()) : $opinion_query->the_post(); ?>
            <article class="sidebar-post sidebar-post--opinion">
                <div class="sidebar-post__image">
                    <a href="<?php the_permalink(); ?>">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('thumbnail', array('class' => 'sidebar-post__img')); ?>
                        <?php else : ?>
                            <div class="sidebar-post__placeholder">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <polyline points="21 15 16 10 5 21"/>
                                </svg>
                            </div>
                        <?php endif; ?>
                    </a>
                </div>
                <div class="sidebar-post__content">
                    <h4 class="sidebar-post__title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h4>
                </div>
            </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
    <?php 
        endif;
    endif; 
    ?>

    <?php 
    // =========================================================================
    // MONTHLY RECAP WIDGET
    // =========================================================================
    if ($monthly_recap_enabled) :
        $monthly_recap_query = legalpress_get_monthly_recap_posts($monthly_recap_count);
        $current_month = legalpress_get_current_month_name();
        
        // If no posts this month, get recent posts as fallback
        $has_monthly_posts = $monthly_recap_query->have_posts();
        if (!$has_monthly_posts) {
            $monthly_recap_query = new WP_Query(array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => $monthly_recap_count,
                'orderby' => 'date',
                'order' => 'DESC',
                'ignore_sticky_posts' => true,
            ));
        }
        
        if ($monthly_recap_query->have_posts()) :
    ?>
    <div class="sidebar-widget sidebar-widget--monthly-recap reveal" data-animate="fade-in-up">
        <div class="sidebar-widget__header">
            <h3 class="sidebar-widget__title">
                <?php echo esc_html($monthly_recap_title); ?>
                <span class="sidebar-widget__link" style="cursor: default;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </span>
            </h3>
        </div>
        
        <div class="sidebar-widget__content">
            <?php while ($monthly_recap_query->have_posts()) : $monthly_recap_query->the_post(); ?>
            <article class="sidebar-post sidebar-post--recap">
                <div class="sidebar-post__image">
                    <a href="<?php the_permalink(); ?>">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('thumbnail', array('class' => 'sidebar-post__img')); ?>
                        <?php else : ?>
                            <div class="sidebar-post__placeholder">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <polyline points="21 15 16 10 5 21"/>
                                </svg>
                            </div>
                        <?php endif; ?>
                    </a>
                </div>
                <div class="sidebar-post__content">
                    <h4 class="sidebar-post__title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h4>
                    <span class="sidebar-post__date">
                        <?php echo esc_html(get_the_date('M j, Y')); ?>
                    </span>
                </div>
            </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        
        <div class="sidebar-widget__footer">
            <span class="sidebar-widget__month-label">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                <?php if ($has_monthly_posts) : ?>
                    <?php echo esc_html($current_month); ?>
                <?php else : ?>
                    <?php esc_html_e('Recent Posts', 'legalpress'); ?>
                <?php endif; ?>
            </span>
        </div>
    </div>
    <?php 
        endif;
    endif; 
    ?>

</aside>
