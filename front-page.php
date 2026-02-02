<?php
/**
 * Front Page Template - Premium Version
 * 
 * Beautiful homepage with animations, skeleton loading,
 * hero section, latest news grid, and category sections.
 * 
 * @package LegalPress
 * @since 1.0.0
 */

get_header();

// Track displayed post IDs to avoid duplicates
$displayed_posts = array();
?>

<!-- Skeleton Loading for Hero (shown during page load) -->
<?php if (get_theme_mod('legalpress_hero_enable', true)) : ?>
<div class="skeleton-hero-wrapper" data-skeleton="#hero-section" style="display: none;">
    <div class="skeleton-hero">
        <div class="skeleton-hero__content">
            <div class="skeleton skeleton-hero__category"></div>
            <div class="skeleton skeleton-hero__title"></div>
            <div class="skeleton skeleton-hero__title-2"></div>
            <div class="skeleton-hero__meta">
                <div class="skeleton skeleton-hero__meta-item"></div>
                <div class="skeleton skeleton-hero__meta-item"></div>
                <div class="skeleton skeleton-hero__meta-item"></div>
            </div>
        </div>
    </div>
</div>

<!-- Hero Section with Featured Article -->
<section id="hero-section" class="hero" data-animate="fade-in">
    <?php
    // Get hero post based on admin settings
    $hero_source = get_theme_mod('legalpress_hero_source', 'latest');
    $hero_post_id = get_theme_mod('legalpress_hero_post_id', '');
    
    // Build query based on source selection
    $hero_args = array(
        'posts_per_page' => 1,
        'post_status' => 'publish',
        'no_found_rows' => true,
        'update_post_meta_cache' => false,
    );
    
    switch ($hero_source) {
        case 'sticky':
            // Get sticky posts first
            $sticky_posts = get_option('sticky_posts');
            if (!empty($sticky_posts)) {
                $hero_args['post__in'] = $sticky_posts;
                $hero_args['ignore_sticky_posts'] = 1;
            } else {
                // Fallback to latest if no sticky posts
                $hero_args['orderby'] = 'date';
                $hero_args['order'] = 'DESC';
            }
            break;
            
        case 'specific':
            // Get specific post selected by admin
            if (!empty($hero_post_id)) {
                $hero_args['p'] = absint($hero_post_id);
            } else {
                // Fallback to latest if no post selected
                $hero_args['orderby'] = 'date';
                $hero_args['order'] = 'DESC';
            }
            break;
            
        case 'random':
            $hero_args['orderby'] = 'rand';
            break;
            
        case 'latest':
        default:
            $hero_args['orderby'] = 'date';
            $hero_args['order'] = 'DESC';
            break;
    }
    
    $featured_query = new WP_Query($hero_args);

    if ($featured_query->have_posts()):
        while ($featured_query->have_posts()):
            $featured_query->the_post();
            $displayed_posts[] = get_the_ID();
            $category = legalpress_get_first_category();
            ?>

            <!-- Hero Background -->
            <div class="hero__background">
                <?php if (has_post_thumbnail()): ?>
                    <?php the_post_thumbnail('legalpress-featured', array(
                        'class' => 'hero__background-image',
                        'loading' => 'eager'
                    )); ?>
                <?php else: ?>
                    <div class="hero__background-image" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
                    </div>
                <?php endif; ?>
            </div>

            <!-- Hero Overlay -->
            <div class="hero__overlay"></div>

            <!-- Hero Pattern -->
            <div class="hero__pattern"></div>

            <!-- Hero Decoration -->
            <div class="hero__decoration"></div>

            <!-- Hero Content -->
            <div class="container">
                <div class="hero__content reveal">
                    <?php if ($category): ?>
                        <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>"
                            class="hero__category animate-pop-in">
                            <svg class="hero__category-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                            </svg>
                            <?php echo esc_html($category->name); ?>
                        </a>
                    <?php endif; ?>

                    <h1 class="hero__title">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h1>

                    <?php if (has_excerpt()): ?>
                        <p class="hero__excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
                    <?php endif; ?>

                    <div class="hero__meta">
                        <div class="hero__meta-item">
                            <?php echo get_avatar(get_the_author_meta('ID'), 40, '', '', array('class' => 'hero__author-avatar')); ?>
                            <span><?php the_author(); ?></span>
                        </div>
                        <div class="hero__meta-item">
                            <svg class="hero__meta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>
                            <span><?php echo esc_html(get_the_date()); ?></span>
                        </div>
                        <div class="hero__meta-item">
                            <svg class="hero__meta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                            <span><?php echo esc_html(legalpress_reading_time()); ?></span>
                        </div>
                    </div>

                    <a href="<?php the_permalink(); ?>" class="hero__cta btn-primary">
                        Read Article
                        <svg class="hero__cta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12" />
                            <polyline points="12 5 19 12 12 19" />
                        </svg>
                    </a>
                </div>
            </div>

            <?php
        endwhile;
        wp_reset_postdata();
    else:
        // No featured posts - show default hero
        ?>
        <div class="hero__background" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);"></div>
        <div class="hero__overlay"></div>
        <div class="hero__pattern"></div>

        <div class="container">
            <div class="hero__content reveal">
                <span class="hero__category animate-pop-in">Welcome</span>
                <h1 class="hero__title gradient-text-hero">Welcome to LegalPress</h1>
                <p class="hero__excerpt">Your trusted source for legal news, judgments, and expert analysis. Stay informed
                    with the latest developments in law and justice.</p>
            </div>
        </div>
    <?php endif; ?>
</section>
<?php endif; ?>

<!-- Latest News Section with Sidebar -->
<?php 
$latest_news_enabled = get_theme_mod('legalpress_latest_news_enable', true);
$latest_news_title = get_theme_mod('legalpress_latest_news_title', __('Latest News', 'legalpress'));
$latest_news_count = get_theme_mod('legalpress_latest_news_count', 6);
$has_homepage_sidebar = get_theme_mod('legalpress_monthly_recap_enable', true) || get_theme_mod('legalpress_opinion_enable', true);

if ($latest_news_enabled) : 
?>
<section class="section section--latest-news">
    <div class="container">
        <div class="homepage-layout <?php echo $has_homepage_sidebar ? 'has-sidebar' : ''; ?>">
            <div class="homepage-main">
                <!-- Section Header -->
                <div class="section-header reveal">
                    <h2 class="section-title">
                        <svg class="section-title__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z" />
                            <polyline points="13 2 13 9 20 9" />
                        </svg>
                        <?php echo esc_html($latest_news_title); ?>
                    </h2>
                    <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="section-link">
                        <?php esc_html_e('View All Articles', 'legalpress'); ?>
                        <svg class="section-link__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12" />
                            <polyline points="12 5 19 12 12 19" />
                        </svg>
                    </a>
                </div>

                <!-- Posts Grid with Skeleton Loading -->
                <div class="posts-grid stagger-children">
            <?php
            $latest_query = legalpress_get_latest_posts($latest_news_count, $displayed_posts);

            if ($latest_query->have_posts()):
                $post_count = 0;
                while ($latest_query->have_posts()):
                    $latest_query->the_post();
                    $displayed_posts[] = get_the_ID();
                    $post_count++;
                    $is_featured = ($post_count === 1);
                    ?>

                    <article class="post-card <?php echo $is_featured ? 'post-card-featured' : ''; ?> reveal hover-lift"
                        data-animate="fade-in-up" data-href="<?php the_permalink(); ?>">
                        <!-- Full card clickable overlay -->
                        <a href="<?php the_permalink(); ?>" class="post-card-link-overlay" aria-label="<?php echo esc_attr(get_the_title()); ?>"></a>
                        
                        <div class="post-card-image hover-zoom">
                            <?php if (has_post_thumbnail()): ?>
                                <?php the_post_thumbnail('legalpress-card', array(
                                    'class' => 'post-card-img',
                                    'loading' => 'lazy'
                                )); ?>
                            <?php else: ?>
                                <div class="post-card-placeholder">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1" opacity="0.3">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" />
                                    </svg>
                                </div>
                            <?php endif; ?>

                            <div class="post-card-image-overlay"></div>

                            <?php
                            $category = legalpress_get_first_category();
                            if ($category):
                                $cat_class = 'category-' . esc_attr($category->slug);
                                ?>
                                <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>"
                                    class="post-card-category <?php echo esc_attr($cat_class); ?>">
                                    <?php echo esc_html($category->name); ?>
                                </a>
                            <?php endif; ?>

                            <span class="post-card-read-time">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <circle cx="12" cy="12" r="10" />
                                    <polyline points="12 6 12 12 16 14" />
                                </svg>
                                <?php echo esc_html(legalpress_reading_time()); ?>
                            </span>
                        </div>

                        <div class="post-card-content">
                            <h3 class="post-card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>

                            <?php if ($is_featured && has_excerpt()): ?>
                                <p class="post-card-excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 25)); ?></p>
                            <?php endif; ?>

                            <div class="post-card-footer">
                                <div class="post-card-author">
                                    <?php echo get_avatar(get_the_author_meta('ID'), 28, '', '', array('class' => 'post-card-author-avatar')); ?>
                                    <span><?php the_author(); ?></span>
                                </div>
                                <div class="post-card-date">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                        <line x1="16" y1="2" x2="16" y2="6" />
                                        <line x1="8" y1="2" x2="8" y2="6" />
                                        <line x1="3" y1="10" x2="21" y2="10" />
                                    </svg>
                                    <span><?php echo esc_html(get_the_date('M j')); ?></span>
                                </div>
                            </div>
                        </div>
                    </article>

                    <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
                </div>
            </div><!-- /.homepage-main -->

            <?php if ($has_homepage_sidebar) : ?>
            <div class="homepage-sidebar-wrapper">
                <?php get_template_part('template-parts/sidebar', 'homepage'); ?>
            </div>
            <?php endif; ?>

        </div><!-- /.homepage-layout -->
    </div>
</section>
<?php endif; ?>

<!-- Category Sections (Admin Configurable) -->
<?php
// Get dynamic category sections from Customizer
$category_sections = legalpress_get_homepage_categories();

// Reset displayed posts for category sections to allow more flexibility
// Each section will show its category posts regardless of previous sections
$section_displayed = array();

foreach ($category_sections as $index => $cat_section):
    $category = $cat_section['category'];
    $section_index = $cat_section['index'];
    $post_count = isset($cat_section['count']) ? $cat_section['count'] : 6;
    
    // Build query args
    $query_args = array(
        'posts_per_page' => $post_count,
        'no_found_rows' => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
        'post_status' => 'publish',
    );
    
    // If category is set, filter by it (use category ID for more reliable querying)
    if ($category && isset($category->term_id)) {
        $query_args['cat'] = $category->term_id;
    } elseif (!empty($cat_section['slug'])) {
        // Fallback to slug if category object not available
        $query_args['category_name'] = $cat_section['slug'];
    }

    $cat_query = new WP_Query($query_args);

    // Skip section only if no posts found at all
    if (!$cat_query->have_posts())
        continue;
    ?>

    <section class="section section--category homepage-section-<?php echo esc_attr($section_index); ?> section--<?php echo esc_attr($cat_section['slug'] ?: 'default'); ?>"
        style="--section-color: <?php echo esc_attr($cat_section['color']); ?>">
        <div class="container">

            <!-- Section Header -->
            <div class="section-header reveal" data-animate="fade-in-up">
                <h2 class="section-title">
                    <span class="section-title__icon" style="color: <?php echo esc_attr($cat_section['color']); ?>">
                        <?php echo $cat_section['icon']; ?>
                    </span>
                    <?php echo esc_html($cat_section['title']); ?>
                </h2>
                <?php if ($category) : ?>
                <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="section-link" style="color: <?php echo esc_attr($cat_section['color']); ?>">
                    <?php esc_html_e('View All', 'legalpress'); ?>
                    <svg class="section-link__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="5" y1="12" x2="19" y2="12" />
                        <polyline points="12 5 19 12 12 19" />
                    </svg>
                </a>
                <?php endif; ?>
            </div>

            <!-- Category Posts Grid -->
            <div class="posts-grid posts-grid-<?php echo min($post_count, 4); ?> stagger-children">
                <?php
                $first_post = true;
                while ($cat_query->have_posts()):
                    $cat_query->the_post();
                    $displayed_posts[] = get_the_ID();
                    $is_featured = $first_post && $post_count <= 4;
                    ?>
                    <article class="post-card <?php echo $is_featured ? 'post-card--section-featured' : ''; ?> reveal hover-lift" data-animate="fade-in-up" data-href="<?php the_permalink(); ?>">
                        <!-- Full card clickable overlay -->
                        <a href="<?php the_permalink(); ?>" class="post-card-link-overlay" aria-label="<?php echo esc_attr(get_the_title()); ?>"></a>
                        
                        <div class="post-card-image hover-zoom">
                            <?php if (has_post_thumbnail()): ?>
                                <?php the_post_thumbnail('legalpress-card', array(
                                    'class' => 'post-card-img',
                                    'loading' => 'lazy'
                                )); ?>
                            <?php else: ?>
                                <div class="post-card-placeholder"
                                    style="background: linear-gradient(135deg, <?php echo esc_attr($cat_section['color']); ?>22, <?php echo esc_attr($cat_section['color']); ?>44);">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                                        stroke="<?php echo esc_attr($cat_section['color']); ?>" stroke-width="1" opacity="0.5">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" />
                                    </svg>
                                </div>
                            <?php endif; ?>

                            <div class="post-card-image-overlay"></div>
                            
                            <?php
                            // Show category badge if not filtered by category
                            if (!$category) :
                                $post_category = legalpress_get_first_category();
                                if ($post_category):
                                ?>
                                <a href="<?php echo esc_url(get_category_link($post_category->term_id)); ?>" 
                                   class="post-card-category category-<?php echo esc_attr($post_category->slug); ?>">
                                    <?php echo esc_html($post_category->name); ?>
                                </a>
                                <?php endif;
                            endif;
                            ?>

                            <span class="post-card-read-time">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <circle cx="12" cy="12" r="10" />
                                    <polyline points="12 6 12 12 16 14" />
                                </svg>
                                <?php echo esc_html(legalpress_reading_time()); ?>
                            </span>
                        </div>

                        <div class="post-card-content">
                            <h3 class="post-card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            
                            <?php if ($is_featured && has_excerpt()): ?>
                                <p class="post-card-excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?></p>
                            <?php endif; ?>

                            <div class="post-card-footer">
                                <div class="post-card-author">
                                    <?php echo get_avatar(get_the_author_meta('ID'), 28, '', '', array('class' => 'post-card-author-avatar')); ?>
                                    <span><?php the_author(); ?></span>
                                </div>
                                <div class="post-card-date">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                        <line x1="16" y1="2" x2="16" y2="6"/>
                                        <line x1="8" y1="2" x2="8" y2="6"/>
                                        <line x1="3" y1="10" x2="21" y2="10"/>
                                    </svg>
                                    <span><?php echo esc_html(get_the_date('M j')); ?></span>
                                </div>
                            </div>
                        </div>
                    </article>
                    <?php 
                    $first_post = false;
                endwhile; 
                ?>
            </div>
        </div>
    </section>

    <?php
    wp_reset_postdata();
endforeach;
?>

<!-- Newsletter Section -->
<?php if (get_theme_mod('legalpress_show_newsletter', true)):
    $newsletter_title = get_theme_mod('legalpress_newsletter_title', 'Stay Updated');
    $newsletter_text = get_theme_mod('legalpress_newsletter_text', 'Get the latest legal news and analysis delivered to your inbox weekly. Join thousands of legal professionals who trust LegalPress.');
    $newsletter_action = get_theme_mod('legalpress_newsletter_action', '');
    ?>
    <section class="section section--newsletter reveal" data-animate="fade-in-up">
        <div class="container">
            <div class="newsletter-box glass-card">
                <div class="newsletter-box__content">
                    <h2 class="newsletter-box__title gradient-text"><?php echo esc_html($newsletter_title); ?></h2>
                    <p class="newsletter-box__text"><?php echo esc_html($newsletter_text); ?></p>

                    <form class="newsletter-form"
                        action="<?php echo esc_url($newsletter_action ? $newsletter_action : '#'); ?>" method="post">
                        <div class="newsletter-form__group">
                            <input type="email" name="email"
                                placeholder="<?php esc_attr_e('Enter your email address', 'legalpress'); ?>"
                                class="newsletter-form__input" required>
                            <button type="submit" class="newsletter-form__btn btn btn-primary">
                                <?php esc_html_e('Subscribe', 'legalpress'); ?>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <line x1="22" y1="2" x2="11" y2="13" />
                                    <polygon points="22 2 15 22 11 13 2 9 22 2" />
                                </svg>
                            </button>
                        </div>
                        <p class="newsletter-form__note">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                            </svg>
                            <?php esc_html_e('We respect your privacy. Unsubscribe at any time.', 'legalpress'); ?>
                        </p>
                    </form>
                </div>

                <div class="newsletter-box__decoration">
                    <svg viewBox="0 0 200 200" fill="none">
                        <circle cx="100" cy="100" r="80" stroke="currentColor" stroke-width="0.5" opacity="0.1" />
                        <circle cx="100" cy="100" r="60" stroke="currentColor" stroke-width="0.5" opacity="0.15" />
                        <circle cx="100" cy="100" r="40" stroke="currentColor" stroke-width="0.5" opacity="0.2" />
                    </svg>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php get_footer(); ?>