<?php
/**
 * News Ticker Template Part
 * 
 * Displays breaking news ticker/carousel
 * 
 * @package LegalPress
 * @since 2.5.0
 */

// Check if ticker is enabled
if (!get_theme_mod('legalpress_enable_ticker', true)) {
    return;
}

$ticker_label = get_theme_mod('legalpress_ticker_label', 'Top Stories');
$ticker_count = get_theme_mod('legalpress_ticker_count', 10);
$ticker_category = get_theme_mod('legalpress_ticker_category', '');

// Build query args
$ticker_args = array(
    'posts_per_page'         => $ticker_count,
    'post_status'            => 'publish',
    'ignore_sticky_posts'    => true,
    'no_found_rows'          => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
);

// Filter by category if set
if (!empty($ticker_category)) {
    $ticker_args['category_name'] = $ticker_category;
}

$ticker_query = new WP_Query($ticker_args);

if (!$ticker_query->have_posts()) {
    return;
}
?>

<div class="news-ticker" role="marquee" aria-label="<?php esc_attr_e('Breaking News', 'legalpress'); ?>">
    <div class="container-fluid">
        <div class="news-ticker__inner">
            
            <!-- Ticker Label -->
            <div class="news-ticker__label">
                <svg class="news-ticker__label-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                </svg>
                <span><?php echo esc_html($ticker_label); ?></span>
            </div>

            <!-- Ticker Content -->
            <div class="news-ticker__content">
                <div class="news-ticker__track">
                    <?php while ($ticker_query->have_posts()): $ticker_query->the_post(); ?>
                        <a href="<?php the_permalink(); ?>" class="news-ticker__item">
                            <span class="news-ticker__title"><?php the_title(); ?></span>
                            <span class="news-ticker__separator">•</span>
                        </a>
                    <?php endwhile; ?>
                    
                    <!-- Duplicate for seamless loop -->
                    <?php $ticker_query->rewind_posts(); ?>
                    <?php while ($ticker_query->have_posts()): $ticker_query->the_post(); ?>
                        <a href="<?php the_permalink(); ?>" class="news-ticker__item">
                            <span class="news-ticker__title"><?php the_title(); ?></span>
                            <span class="news-ticker__separator">•</span>
                        </a>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Ticker Controls -->
            <div class="news-ticker__controls">
                <button type="button" class="news-ticker__btn news-ticker__btn--pause" aria-label="<?php esc_attr_e('Pause ticker', 'legalpress'); ?>">
                    <svg class="icon-pause" width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="6" y="4" width="4" height="16"/>
                        <rect x="14" y="4" width="4" height="16"/>
                    </svg>
                    <svg class="icon-play" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" style="display:none;">
                        <polygon points="5 3 19 12 5 21 5 3"/>
                    </svg>
                </button>
            </div>

        </div>
    </div>
</div>

<?php
wp_reset_postdata();
?>
