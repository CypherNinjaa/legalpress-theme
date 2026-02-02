<?php
/**
 * LegalPress Theme Functions
 * 
 * This file handles all theme setup, script/style enqueuing,
 * menu registration, widget areas, and custom functionality.
 * 
 * @package LegalPress
 * @since 1.0.0
 */

// Prevent direct access to this file - SECURITY
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

/**
 * Define theme constants with existence check - SECURITY
 */
if (!defined('LEGALPRESS_VERSION')) {
    define('LEGALPRESS_VERSION', '2.5.0');
}
if (!defined('LEGALPRESS_DIR')) {
    define('LEGALPRESS_DIR', get_template_directory());
}
if (!defined('LEGALPRESS_URI')) {
    define('LEGALPRESS_URI', get_template_directory_uri());
}

/**
 * Theme Setup
 *
 * @since 1.0.0
 * @return void
 */
function legalpress_setup()
{
    load_theme_textdomain('legalpress', LEGALPRESS_DIR . '/languages');
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(800, 500, true);
    add_image_size('legalpress-featured', 1200, 675, true);
    add_image_size('legalpress-card', 400, 250, true);
    add_image_size('legalpress-sidebar', 150, 150, true);

    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'legalpress'),
        'footer' => esc_html__('Footer Menu', 'legalpress'),
    ));

    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    add_theme_support('custom-logo', array(
        'height' => 100,
        'width' => 300,
        'flex-width' => true,
        'flex-height' => true,
    ));

    add_theme_support('editor-styles');
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    add_theme_support('custom-background', array('default-color' => 'ffffff'));
}
add_action('after_setup_theme', 'legalpress_setup');

/**
 * Set content width
 *
 * @since 1.0.0
 * @return void
 */
function legalpress_content_width()
{
    $GLOBALS['content_width'] = apply_filters('legalpress_content_width', 720);
}
add_action('after_setup_theme', 'legalpress_content_width', 0);

/**
 * Enqueue Scripts and Styles - PERFORMANCE OPTIMIZED
 *
 * @since 2.0.0
 * @return void
 */
function legalpress_scripts()
{
    // Google Fonts with preconnect for performance
    wp_enqueue_style(
        'legalpress-google-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Merriweather:wght@400;700;900&display=swap',
        array(),
        null
    );

    // Main stylesheet (base, typography, reset, utilities)
    wp_enqueue_style('legalpress-style', get_stylesheet_uri(), array('legalpress-google-fonts'), LEGALPRESS_VERSION);

    // Header & Navigation styles
    wp_enqueue_style('legalpress-header', LEGALPRESS_URI . '/assets/css/header.css', array('legalpress-style'), LEGALPRESS_VERSION);

    // Hero section styles
    wp_enqueue_style('legalpress-hero', LEGALPRESS_URI . '/assets/css/hero.css', array('legalpress-header'), LEGALPRESS_VERSION);

    // Post cards styles
    wp_enqueue_style('legalpress-cards', LEGALPRESS_URI . '/assets/css/cards.css', array('legalpress-hero'), LEGALPRESS_VERSION);

    // Single post & content styles
    wp_enqueue_style('legalpress-single', LEGALPRESS_URI . '/assets/css/single.css', array('legalpress-cards'), LEGALPRESS_VERSION);

    // Footer & Sidebar styles
    wp_enqueue_style('legalpress-footer', LEGALPRESS_URI . '/assets/css/footer.css', array('legalpress-single'), LEGALPRESS_VERSION);

    // Animation styles
    wp_enqueue_style('legalpress-animations', LEGALPRESS_URI . '/assets/css/animations.css', array('legalpress-footer'), LEGALPRESS_VERSION);

    // Skeleton loading styles
    wp_enqueue_style('legalpress-skeleton', LEGALPRESS_URI . '/assets/css/skeleton.css', array('legalpress-animations'), LEGALPRESS_VERSION);

    // Responsive styles (load last)
    wp_enqueue_style('legalpress-responsive', LEGALPRESS_URI . '/assets/css/responsive.css', array('legalpress-skeleton'), LEGALPRESS_VERSION);

    // LawChakra Components (Top Bar, Ticker, Breadcrumb, Author Bio, Similar Posts)
    wp_enqueue_style('legalpress-lawchakra', LEGALPRESS_URI . '/assets/css/lawchakra-components.css', array('legalpress-responsive'), LEGALPRESS_VERSION);

    // Main JavaScript
    wp_enqueue_script('legalpress-main', LEGALPRESS_URI . '/assets/js/main.js', array(), LEGALPRESS_VERSION, true);

    // LawChakra Components JavaScript (Live Time, Ticker Controls)
    wp_enqueue_script('legalpress-lawchakra', LEGALPRESS_URI . '/assets/js/lawchakra-components.js', array('legalpress-main'), LEGALPRESS_VERSION, true);

    wp_localize_script('legalpress-main', 'legalpressData', array(
        'ajaxUrl' => esc_url(admin_url('admin-ajax.php')),
        'nonce' => wp_create_nonce('legalpress_nonce'),
        'homeUrl' => esc_url(home_url('/')),
        'themeUrl' => esc_url(LEGALPRESS_URI),
    ));

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'legalpress_scripts');

/**
 * Include Demo Content Generator
 */
require_once LEGALPRESS_DIR . '/inc/demo-content.php';

/**
 * Include Customizer Settings for LawChakra Features
 */
require_once LEGALPRESS_DIR . '/inc/customizer.php';

/**
 * Register Widget Areas
 *
 * @since 1.0.0
 * @return void
 */
function legalpress_widgets_init()
{
    register_sidebar(array(
        'name' => esc_html__('Sidebar', 'legalpress'),
        'id' => 'sidebar-1',
        'description' => esc_html__('Add widgets here.', 'legalpress'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3 class="widget__title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Single Post Sidebar', 'legalpress'),
        'id' => 'single-sidebar',
        'description' => esc_html__('Sidebar for single post pages with Related & Trending posts.', 'legalpress'),
        'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="sidebar-widget__title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer 1', 'legalpress'),
        'id' => 'footer-1',
        'description' => esc_html__('Footer column 1.', 'legalpress'),
        'before_widget' => '<div id="%1$s" class="footer__widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="footer__title">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer 2', 'legalpress'),
        'id' => 'footer-2',
        'description' => esc_html__('Footer column 2.', 'legalpress'),
        'before_widget' => '<div id="%1$s" class="footer__widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="footer__title">',
        'after_title' => '</h4>',
    ));
}
add_action('widgets_init', 'legalpress_widgets_init');

/**
 * Custom Excerpt Length
 *
 * @param int $length Default excerpt length.
 * @return int Modified excerpt length.
 */
function legalpress_excerpt_length($length)
{
    return is_admin() ? $length : 25;
}
add_filter('excerpt_length', 'legalpress_excerpt_length');

/**
 * Custom Excerpt More
 *
 * @param string $more Default more string.
 * @return string Modified more string.
 */
function legalpress_excerpt_more($more)
{
    return is_admin() ? $more : '&hellip;';
}
add_filter('excerpt_more', 'legalpress_excerpt_more');

/**
 * Get Category Choices for Customizer Dropdowns
 * Returns array of category slug => name for use in select controls
 *
 * @since 2.5.0
 * @return array
 */
function legalpress_get_category_choices() {
    $choices = array('' => __('— Select Category —', 'legalpress'));
    
    $categories = get_categories(array(
        'orderby' => 'name',
        'order' => 'ASC',
        'hide_empty' => false,
        'exclude' => array(get_cat_ID('uncategorized')),
    ));
    
    foreach ($categories as $category) {
        $choices[$category->slug] = $category->name;
    }
    
    return $choices;
}

/**
 * Get First Category - SECURITY: Sanitized
 *
 * @param int|null $post_id Post ID.
 * @return WP_Term|false Category object or false.
 */
function legalpress_get_first_category($post_id = null)
{
    $post_id = $post_id ? absint($post_id) : get_the_ID();
    if (!$post_id) {
        return false;
    }
    $categories = get_the_category($post_id);
    return (!empty($categories) && !is_wp_error($categories)) ? $categories[0] : false;
}

/**
 * Display Post Category Badge - SECURITY: Escaped
 *
 * @param string $class Additional CSS classes.
 * @return void
 */
function legalpress_category_badge($class = '')
{
    $category = legalpress_get_first_category();
    if ($category) {
        printf(
            '<a href="%s" class="post-card-category %s">%s</a>',
            esc_url(get_category_link($category->term_id)),
            esc_attr(sanitize_html_class($class)),
            esc_html($category->name)
        );
    }
}

/**
 * Get Reading Time - PERFORMANCE: Uses cache
 *
 * @param int|null $post_id Post ID.
 * @return string Formatted reading time.
 */
function legalpress_reading_time($post_id = null)
{
    $post_id = $post_id ? absint($post_id) : get_the_ID();
    if (!$post_id) {
        return esc_html__('1 min read', 'legalpress');
    }

    $cache_key = 'legalpress_rt_' . $post_id;
    $cached = wp_cache_get($cache_key, 'legalpress');
    if (false !== $cached) {
        return $cached;
    }

    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(wp_strip_all_tags($content));
    $reading_time = max(1, (int) ceil($word_count / 200));

    $result = sprintf(
        /* translators: %d: number of minutes */
        _n('%d min read', '%d min read', $reading_time, 'legalpress'),
        $reading_time
    );

    wp_cache_set($cache_key, $result, 'legalpress', HOUR_IN_SECONDS);
    return $result;
}

/**
 * Get Posts by Category - PERFORMANCE: Optimized query
 *
 * @param string $category_slug Category slug.
 * @param int    $count         Number of posts.
 * @return WP_Query
 */
function legalpress_get_category_posts($category_slug, $count = 4)
{
    return new WP_Query(array(
        'category_name' => sanitize_title($category_slug),
        'posts_per_page' => min(absint($count), 20),
        'post_status' => 'publish',
        'no_found_rows' => true,
        'update_post_meta_cache' => false,
    ));
}

/**
 * Get Random Featured Post for Hero Section
 * Returns a random published post each time page loads
 *
 * @return WP_Query
 */
function legalpress_get_random_featured_post()
{
    $args = array(
        'posts_per_page' => 1,
        'post_status' => 'publish',
        'orderby' => 'rand',
        'no_found_rows' => true,
        'update_post_meta_cache' => false,
        'ignore_sticky_posts' => 1,
    );

    return new WP_Query($args);
}

/**
 * Get Featured Posts - PERFORMANCE: Optimized
 *
 * @param int $count Number of posts.
 * @return WP_Query
 */
function legalpress_get_featured_posts($count = 1)
{
    $count = min(absint($count), 10);
    $sticky_posts = get_option('sticky_posts');

    $args = array(
        'posts_per_page' => $count,
        'post_status' => 'publish',
        'no_found_rows' => true,
        'update_post_meta_cache' => false,
    );

    if (!empty($sticky_posts) && is_array($sticky_posts)) {
        $args['post__in'] = array_map('absint', $sticky_posts);
        $args['ignore_sticky_posts'] = 1;
    }

    return new WP_Query($args);
}

/**
 * Get Latest Posts - PERFORMANCE: Optimized
 *
 * @param int   $count   Number of posts.
 * @param array $exclude Post IDs to exclude.
 * @return WP_Query
 */
function legalpress_get_latest_posts($count = 6, $exclude = array())
{
    $args = array(
        'posts_per_page' => min(absint($count), 20),
        'post_status' => 'publish',
        'ignore_sticky_posts' => 1,
        'no_found_rows' => true,
        'update_post_meta_cache' => false,
    );

    if (!empty($exclude) && is_array($exclude)) {
        $args['post__not_in'] = array_map('absint', $exclude);
    }

    return new WP_Query($args);
}

/**
 * Nav Menu CSS Class
 *
 * @param array    $classes CSS classes.
 * @param WP_Post  $item    Menu item.
 * @param stdClass $args    Menu arguments.
 * @param int      $depth   Menu depth.
 * @return array Modified classes.
 */
function legalpress_nav_menu_css_class($classes, $item, $args, $depth)
{
    if (isset($args->theme_location) && 'primary' === $args->theme_location) {
        if ($item->current || $item->current_item_ancestor) {
            $classes[] = 'current-menu-item';
        }
    }
    return $classes;
}
add_filter('nav_menu_css_class', 'legalpress_nav_menu_css_class', 10, 4);

/**
 * Add Defer to Scripts - PERFORMANCE
 *
 * @param string $tag    Script tag.
 * @param string $handle Script handle.
 * @param string $src    Script source.
 * @return string Modified script tag.
 */
function legalpress_script_loader_tag($tag, $handle, $src)
{
    if ('legalpress-main' === $handle && strpos($tag, 'defer') === false) {
        return str_replace(' src', ' defer src', $tag);
    }
    return $tag;
}
add_filter('script_loader_tag', 'legalpress_script_loader_tag', 10, 3);

/**
 * Resource Hints for Performance
 *
 * @param array  $urls          URLs.
 * @param string $relation_type Relation type.
 * @return array Modified URLs.
 */
function legalpress_resource_hints($urls, $relation_type)
{
    if ('preconnect' === $relation_type) {
        $urls[] = array('href' => 'https://fonts.googleapis.com');
        $urls[] = array('href' => 'https://fonts.gstatic.com', 'crossorigin' => 'anonymous');
    }
    return $urls;
}
add_filter('wp_resource_hints', 'legalpress_resource_hints', 10, 2);

/**
 * Custom Body Classes
 *
 * @param array $classes Body classes.
 * @return array Modified body classes.
 */
function legalpress_body_classes($classes)
{
    if (is_singular('post')) {
        $classes[] = 'single-post-view';
    }
    if (is_active_sidebar('sidebar-1') && !is_page() && !is_singular('post')) {
        $classes[] = 'has-sidebar';
    }
    if (is_front_page()) {
        $classes[] = 'is-homepage';
    }
    return $classes;
}
add_filter('body_class', 'legalpress_body_classes');

/**
 * Custom Post Classes
 *
 * @param array $classes Post classes.
 * @return array Modified post classes.
 */
function legalpress_post_classes($classes)
{
    $classes[] = 'entry';
    return $classes;
}
add_filter('post_class', 'legalpress_post_classes');

/**
 * Disable WordPress Emojis - PERFORMANCE
 *
 * @return void
 */
function legalpress_disable_emojis()
{
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}
add_action('init', 'legalpress_disable_emojis');

/**
 * Remove jQuery Migrate - PERFORMANCE
 *
 * @param WP_Scripts $scripts Scripts object.
 * @return void
 */
function legalpress_remove_jquery_migrate($scripts)
{
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $script = $scripts->registered['jquery'];
        if ($script->deps) {
            $script->deps = array_diff($script->deps, array('jquery-migrate'));
        }
    }
}
add_action('wp_default_scripts', 'legalpress_remove_jquery_migrate');

/**
 * Schema.org Markup for Articles - SEO
 *
 * @return void
 */
function legalpress_article_schema()
{
    if (!is_singular('post')) {
        return;
    }

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => esc_html(get_the_title()),
        'datePublished' => esc_attr(get_the_date('c')),
        'dateModified' => esc_attr(get_the_modified_date('c')),
        'author' => array('@type' => 'Person', 'name' => esc_html(get_the_author())),
        'publisher' => array('@type' => 'Organization', 'name' => esc_html(get_bloginfo('name'))),
        'mainEntityOfPage' => array('@type' => 'WebPage', '@id' => esc_url(get_permalink())),
    );

    if (has_post_thumbnail()) {
        $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
        if ($image) {
            $schema['image'] = array(
                '@type' => 'ImageObject',
                'url' => esc_url($image[0]),
                'width' => absint($image[1]),
                'height' => absint($image[2]),
            );
        }
    }

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'legalpress_article_schema');

/**
 * Open Graph Meta Tags - SEO
 *
 * @return void
 */
function legalpress_open_graph_meta()
{
    if (is_singular('post')) {
        echo '<meta property="og:type" content="article" />' . "\n";
        echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '" />' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";

        if (has_post_thumbnail()) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'legalpress-featured');
            if ($image) {
                echo '<meta property="og:image" content="' . esc_url($image[0]) . '" />' . "\n";
            }
        }

        if (has_excerpt()) {
            echo '<meta property="og:description" content="' . esc_attr(wp_strip_all_tags(get_the_excerpt())) . '" />' . "\n";
        }
    } else {
        echo '<meta property="og:type" content="website" />' . "\n";
        echo '<meta property="og:title" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url(home_url('/')) . '" />' . "\n";
        echo '<meta property="og:description" content="' . esc_attr(get_bloginfo('description')) . '" />' . "\n";
    }
}
add_action('wp_head', 'legalpress_open_graph_meta', 5);

/**
 * Pagination Function - SECURITY: Escaped
 *
 * @return void
 */
function legalpress_pagination()
{
    global $wp_query;
    if ($wp_query->max_num_pages <= 1) {
        return;
    }

    $paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;

    echo '<nav class="pagination" role="navigation" aria-label="' . esc_attr__('Posts navigation', 'legalpress') . '">';
    echo paginate_links(array(
        'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
        'format' => '?paged=%#%',
        'current' => $paged,
        'total' => absint($wp_query->max_num_pages),
        'prev_text' => '&larr; ' . esc_html__('Previous', 'legalpress'),
        'next_text' => esc_html__('Next', 'legalpress') . ' &rarr;',
    ));
    echo '</nav>';
}

/**
 * Template Part Helper
 *
 * @param string      $slug Template slug.
 * @param string|null $name Template name.
 * @param array       $args Arguments.
 * @return void
 */
function legalpress_template_part($slug, $name = null, $args = array())
{
    get_template_part('template-parts/' . sanitize_file_name($slug), $name ? sanitize_file_name($name) : null, $args);
}

/**
 * Custom Comment Callback
 *
 * @param WP_Comment $comment Comment object.
 * @param array      $args    Arguments.
 * @param int        $depth   Comment depth.
 * @return void
 */
function legalpress_comment_callback($comment, $args, $depth)
{
    $tag = ('div' === $args['style']) ? 'div' : 'li';
    ?>
    <<?php echo esc_attr($tag); ?> id="comment-
        <?php comment_ID(); ?>"
        <?php comment_class(empty($args['has_children']) ? '' : 'parent'); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <header class="comment-meta">
                <div class="comment-author vcard">
                    <?php echo get_avatar($comment, 48); ?>
                    <b class="fn">
                        <?php echo get_comment_author_link(); ?>
                    </b>
                </div>
                <div class="comment-metadata">
                    <time datetime="<?php echo esc_attr(get_comment_time('c')); ?>">
                        <?php echo esc_html(get_comment_date()) . ' ' . esc_html__('at', 'legalpress') . ' ' . esc_html(get_comment_time()); ?>
                    </time>
                </div>
            </header>
            <div class="comment-content">
                <?php comment_text(); ?>
            </div>
            <?php if ('0' === $comment->comment_approved): ?>
                <em class="comment-awaiting-moderation">
                    <?php esc_html_e('Your comment is awaiting moderation.', 'legalpress'); ?>
                </em>
            <?php endif; ?>
            <div class="comment-reply">
                <?php comment_reply_link(array_merge($args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
            </div>
        </article>
        <?php
}

/**
 * Theme Customizer Settings
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 * @return void
 */
function legalpress_customize_register($wp_customize)
{
    // ========================================
    // HEADER SETTINGS SECTION
    // ========================================
    $wp_customize->add_section('legalpress_header', array(
        'title' => esc_html__('Header Settings', 'legalpress'),
        'priority' => 105,
    ));

    // Show/Hide Dark Mode Toggle
    $wp_customize->add_setting('legalpress_show_theme_toggle', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('legalpress_show_theme_toggle', array(
        'label' => esc_html__('Show Dark/Light Mode Toggle', 'legalpress'),
        'description' => esc_html__('Display the theme toggle button in the header', 'legalpress'),
        'section' => 'legalpress_header',
        'type' => 'checkbox',
    ));

    // Show/Hide Search Button
    $wp_customize->add_setting('legalpress_show_search', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('legalpress_show_search', array(
        'label' => esc_html__('Show Search Button', 'legalpress'),
        'description' => esc_html__('Display the search button in the header', 'legalpress'),
        'section' => 'legalpress_header',
        'type' => 'checkbox',
    ));

    // ========================================
    // SOCIAL LINKS SECTION
    // ========================================
    $wp_customize->add_section('legalpress_social', array(
        'title' => esc_html__('Social Links', 'legalpress'),
        'priority' => 110,
    ));

    // Twitter/X URL
    $wp_customize->add_setting('legalpress_twitter_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('legalpress_twitter_url', array(
        'label' => esc_html__('Twitter/X URL', 'legalpress'),
        'section' => 'legalpress_social',
        'type' => 'url',
    ));

    // Facebook URL
    $wp_customize->add_setting('legalpress_facebook_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('legalpress_facebook_url', array(
        'label' => esc_html__('Facebook URL', 'legalpress'),
        'section' => 'legalpress_social',
        'type' => 'url',
    ));

    // LinkedIn URL
    $wp_customize->add_setting('legalpress_linkedin_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('legalpress_linkedin_url', array(
        'label' => esc_html__('LinkedIn URL', 'legalpress'),
        'section' => 'legalpress_social',
        'type' => 'url',
    ));

    // Instagram URL
    $wp_customize->add_setting('legalpress_instagram_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('legalpress_instagram_url', array(
        'label' => esc_html__('Instagram URL', 'legalpress'),
        'section' => 'legalpress_social',
        'type' => 'url',
    ));

    // YouTube URL
    $wp_customize->add_setting('legalpress_youtube_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('legalpress_youtube_url', array(
        'label' => esc_html__('YouTube URL', 'legalpress'),
        'section' => 'legalpress_social',
        'type' => 'url',
    ));

    // ========================================
    // NEWSLETTER SETTINGS (Moved to Homepage Content in inc/customizer.php)
    // Note: Category sections are now configured in Homepage Content
    // ========================================
    
    // Newsletter settings added to homepage content section
    $wp_customize->add_setting('legalpress_newsletter_title', array(
        'default' => 'Stay Updated',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('legalpress_newsletter_title', array(
        'label' => esc_html__('Newsletter Title', 'legalpress'),
        'section' => 'legalpress_homepage_section',
        'type' => 'text',
    ));

    // Newsletter Section Text
    $wp_customize->add_setting('legalpress_newsletter_text', array(
        'default' => 'Get the latest legal news and analysis delivered to your inbox weekly. Join thousands of legal professionals who trust LegalPress.',
        'sanitize_callback' => 'wp_kses_post',
    ));
    $wp_customize->add_control('legalpress_newsletter_text', array(
        'label' => esc_html__('Newsletter Description', 'legalpress'),
        'section' => 'legalpress_homepage_section',
        'type' => 'textarea',
    ));

    // Newsletter Form Action URL (for MailChimp, etc.)
    $wp_customize->add_setting('legalpress_newsletter_action', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('legalpress_newsletter_action', array(
        'label' => esc_html__('Newsletter Form URL', 'legalpress'),
        'description' => esc_html__('Enter your email service form action URL (MailChimp, ConvertKit, etc.)', 'legalpress'),
        'section' => 'legalpress_homepage_section',
        'type' => 'url',
    ));

    // Show/Hide Newsletter Section
    $wp_customize->add_setting('legalpress_show_newsletter', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('legalpress_show_newsletter', array(
        'label' => esc_html__('Show Newsletter Section', 'legalpress'),
        'section' => 'legalpress_homepage_section',
        'type' => 'checkbox',
    ));

    // ========================================
    // FOOTER SETTINGS SECTION
    // ========================================
    $wp_customize->add_section('legalpress_footer', array(
        'title' => esc_html__('Footer Settings', 'legalpress'),
        'priority' => 120,
    ));

    $wp_customize->add_setting('legalpress_footer_about', array(
        'default' => '',
        'sanitize_callback' => 'wp_kses_post',
    ));
    $wp_customize->add_control('legalpress_footer_about', array(
        'label' => esc_html__('About Text', 'legalpress'),
        'section' => 'legalpress_footer',
        'type' => 'textarea',
    ));

    $wp_customize->add_setting('legalpress_copyright', array(
        'default' => '',
        'sanitize_callback' => 'wp_kses_post',
    ));
    $wp_customize->add_control('legalpress_copyright', array(
        'label' => esc_html__('Copyright Text', 'legalpress'),
        'section' => 'legalpress_footer',
        'type' => 'text',
    ));
}
add_action('customize_register', 'legalpress_customize_register');

/**
 * Get Social Links for Footer
 *
 * @return array Array of social links
 */
function legalpress_get_social_links()
{
    $social_links = array();

    $platforms = array(
        'twitter' => array(
            'url' => get_theme_mod('legalpress_twitter_url'),
            'label' => 'Twitter',
            'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/></svg>',
        ),
        'facebook' => array(
            'url' => get_theme_mod('legalpress_facebook_url'),
            'label' => 'Facebook',
            'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>',
        ),
        'linkedin' => array(
            'url' => get_theme_mod('legalpress_linkedin_url'),
            'label' => 'LinkedIn',
            'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>',
        ),
        'instagram' => array(
            'url' => get_theme_mod('legalpress_instagram_url'),
            'label' => 'Instagram',
            'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>',
        ),
        'youtube' => array(
            'url' => get_theme_mod('legalpress_youtube_url'),
            'label' => 'YouTube',
            'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"/></svg>',
        ),
    );

    foreach ($platforms as $key => $platform) {
        if (!empty($platform['url'])) {
            $social_links[$key] = $platform;
        }
    }

    // Always show RSS feed
    $social_links['rss'] = array(
        'url' => get_bloginfo('rss2_url'),
        'label' => 'RSS Feed',
        'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 11a9 9 0 0 1 9 9"/><path d="M4 4a16 16 0 0 1 16 16"/><circle cx="5" cy="19" r="1"/></svg>',
    );

    return $social_links;
}

/**
 * Get Homepage Category Sections
 *
 * @return array Array of category sections
 */
function legalpress_get_homepage_categories()
{
    $sections = array();
    
    // Default icons for each section
    $default_icons = array(
        1 => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18"/><path d="M9 8h1"/><path d="M9 12h1"/><path d="M9 16h1"/><path d="M14 8h1"/><path d="M14 12h1"/><path d="M14 16h1"/><path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/></svg>', // Supreme Court - Building
        2 => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m16.24 7.76-2.12 6.36-6.36 2.12 2.12-6.36 6.36-2.12z"/></svg>', // High Court - Compass
        3 => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>', // Other Courts - Shield
        4 => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>', // Legal Updates - Document
        5 => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>', // Opinion - Chat
        6 => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>', // Monthly Recap - Calendar
    );

    // Default colors for each section
    $default_colors = array(
        1 => '#1e3a5f', // Navy Blue - Supreme Court
        2 => '#059669', // Green - High Court
        3 => '#7c3aed', // Purple - Other Courts
        4 => '#dc2626', // Red - Legal Updates
        5 => '#d97706', // Orange - Opinion
        6 => '#0891b2', // Cyan - Monthly Recap
    );

    // Get top categories for defaults (excluding 'uncategorized')
    $top_cats = get_categories(array(
        'orderby' => 'count',
        'order' => 'DESC',
        'number' => 6,
        'hide_empty' => false,
        'exclude' => array(get_cat_ID('uncategorized')),
    ));
    
    $default_category_slugs = array();
    foreach ($top_cats as $cat) {
        $default_category_slugs[] = $cat->slug;
    }

    // Loop through 6 possible sections
    for ($i = 1; $i <= 6; $i++) {
        $enabled = get_theme_mod("legalpress_section_{$i}_enable", ($i <= 4));
        
        if (!$enabled) {
            continue;
        }

        // Get default category slug for this section
        $default_cat_slug = isset($default_category_slugs[$i - 1]) ? $default_category_slugs[$i - 1] : '';
        
        $cat_slug = get_theme_mod("legalpress_section_{$i}_category", $default_cat_slug);
        $custom_title = get_theme_mod("legalpress_section_{$i}_title", '');
        $post_count = get_theme_mod("legalpress_section_{$i}_count", 6);
        $color = get_theme_mod("legalpress_section_{$i}_color", $default_colors[$i] ?? '#1e3a5f');
        $icon = get_theme_mod("legalpress_section_{$i}_icon", '');

        // Try to get category by slug
        $category = null;
        if (!empty($cat_slug)) {
            $category = get_category_by_slug($cat_slug);
        }

        // Use custom title or category name, or fallback to Section number
        $title = !empty($custom_title) ? $custom_title : ($category ? $category->name : sprintf(__('Section %d', 'legalpress'), $i));

        $sections[] = array(
            'index' => $i,
            'slug' => $cat_slug,
            'title' => $title,
            'icon' => !empty($icon) ? $icon : ($default_icons[$i] ?? $default_icons[1]),
            'color' => $color,
            'category' => $category,
            'count' => $post_count,
        );
    }

    return $sections;
}

/**
 * Get Copyright Text
 *
 * @return string Copyright text.
 */
function legalpress_get_copyright()
{
    $custom = get_theme_mod('legalpress_copyright');
    if ($custom) {
        return wp_kses_post($custom);
    }
    return sprintf(
        /* translators: 1: Year, 2: Site name */
        esc_html__('© %1$s %2$s. All rights reserved.', 'legalpress'),
        esc_html(gmdate('Y')),
        esc_html(get_bloginfo('name'))
    );
}

/**
 * Get Footer About Text
 *
 * @return string Footer about text.
 */
function legalpress_get_footer_about()
{
    $custom = get_theme_mod('legalpress_footer_about');
    return $custom ? wp_kses_post($custom) : esc_html(get_bloginfo('description'));
}

/**
 * Security Headers
 *
 * @return void
 */
function legalpress_security_headers()
{
    if (!is_admin()) {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
    }
}
add_action('send_headers', 'legalpress_security_headers');

/**
 * Remove WordPress Version - SECURITY
 *
 * @return string Empty string.
 */
add_filter('the_generator', '__return_empty_string');

/**
 * Cleanup Head - SECURITY & PERFORMANCE
 *
 * @return void
 */
function legalpress_cleanup_head()
{
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
}
add_action('init', 'legalpress_cleanup_head');

/**
 * ==========================================================================
 * AJAX LIVE SEARCH
 * ==========================================================================
 */

/**
 * Enqueue search script data
 */
function legalpress_search_scripts()
{
    wp_localize_script('legalpress-main', 'legalpressSearch', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('legalpress_search_nonce'),
        'maxLength' => 100,
        'minChars' => 3,
    ));
}
add_action('wp_enqueue_scripts', 'legalpress_search_scripts', 20);

/**
 * AJAX handler for live search suggestions
 */
function legalpress_ajax_search()
{
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'legalpress_search_nonce')) {
        wp_send_json_error('Invalid security token');
        return;
    }

    // Get and sanitize search query
    $search_query = isset($_POST['query']) ? sanitize_text_field($_POST['query']) : '';
    
    // Enforce max length (100 characters)
    $search_query = substr($search_query, 0, 100);
    
    // Minimum 3 characters required
    if (strlen($search_query) < 3) {
        wp_send_json_error('Query too short');
        return;
    }

    // Rate limiting using transient (5 searches per 10 seconds per IP)
    $ip = sanitize_text_field($_SERVER['REMOTE_ADDR']);
    $rate_key = 'lp_search_rate_' . md5($ip);
    $rate_count = get_transient($rate_key);
    
    if ($rate_count === false) {
        set_transient($rate_key, 1, 10);
    } elseif ($rate_count >= 5) {
        wp_send_json_error('Rate limit exceeded. Please wait.');
        return;
    } else {
        set_transient($rate_key, $rate_count + 1, 10);
    }

    // Perform search
    $args = array(
        's' => $search_query,
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 6,
        'orderby' => 'relevance',
        'no_found_rows' => true, // Performance optimization
    );

    $query = new WP_Query($args);
    $results = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            $category = legalpress_get_first_category();
            
            $results[] = array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'url' => get_permalink(),
                'excerpt' => wp_trim_words(get_the_excerpt(), 12),
                'date' => get_the_date('M j, Y'),
                'category' => $category ? $category->name : '',
                'category_url' => $category ? get_category_link($category->term_id) : '',
                'thumbnail' => has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') : '',
            );
        }
        wp_reset_postdata();
    }

    wp_send_json_success(array(
        'results' => $results,
        'total' => $query->found_posts,
        'query' => $search_query,
    ));
}
add_action('wp_ajax_legalpress_search', 'legalpress_ajax_search');
add_action('wp_ajax_nopriv_legalpress_search', 'legalpress_ajax_search');

/**
 * Add Social Media Fields to User Profile
 * 
 * @since 2.5.0
 * @param array $contactmethods Contact methods array.
 * @return array Modified contact methods.
 */
function legalpress_user_social_fields($contactmethods) {
    $contactmethods['twitter'] = esc_html__('Twitter/X URL', 'legalpress');
    $contactmethods['facebook'] = esc_html__('Facebook URL', 'legalpress');
    $contactmethods['linkedin'] = esc_html__('LinkedIn URL', 'legalpress');
    $contactmethods['instagram'] = esc_html__('Instagram URL', 'legalpress');
    $contactmethods['youtube'] = esc_html__('YouTube URL', 'legalpress');
    return $contactmethods;
}
add_filter('user_contactmethods', 'legalpress_user_social_fields');

/**
 * Get Author Social Links
 *
 * @since 2.5.0
 * @param int $user_id User ID.
 * @return array Social links array.
 */
function legalpress_get_author_social_links($user_id = null) {
    if (!$user_id) {
        $user_id = get_the_author_meta('ID');
    }
    
    $social = array();
    $networks = array('twitter', 'facebook', 'linkedin', 'instagram', 'youtube');
    
    foreach ($networks as $network) {
        $url = get_the_author_meta($network, $user_id);
        if ($url) {
            $social[$network] = esc_url($url);
        }
    }
    
    return $social;
}

/**
 * Get Similar Posts by Category
 *
 * @since 2.5.0
 * @param int $post_id Current post ID.
 * @param int $count Number of posts to get.
 * @return WP_Query
 */
function legalpress_get_similar_posts($post_id = null, $count = 3) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $categories = wp_get_post_categories($post_id, array('fields' => 'ids'));
    
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => absint($count),
        'post__not_in' => array($post_id),
        'no_found_rows' => true,
        'update_post_meta_cache' => false,
    );
    
    if (!empty($categories)) {
        $args['category__in'] = $categories;
    }
    
    return new WP_Query($args);
}

/**
 * Get Related Posts for Sidebar (by current category)
 *
 * @since 2.5.0
 * @param int $count Number of posts.
 * @return WP_Query
 */
function legalpress_get_related_sidebar_posts($count = 5) {
    $post_id = get_the_ID();
    $categories = wp_get_post_categories($post_id, array('fields' => 'ids'));
    
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => absint($count),
        'post__not_in' => array($post_id),
        'no_found_rows' => true,
    );
    
    if (!empty($categories)) {
        $args['category__in'] = $categories;
    }
    
    return new WP_Query($args);
}

/**
 * Get Trending Posts (by comment count or views)
 *
 * @since 2.5.0
 * @param int $count Number of posts.
 * @param int $days Posts from last X days.
 * @return WP_Query
 */
function legalpress_get_trending_posts($count = 5, $days = 30) {
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => absint($count),
        'orderby' => 'comment_count',
        'order' => 'DESC',
        'no_found_rows' => true,
        'date_query' => array(
            array(
                'after' => $days . ' days ago',
            ),
        ),
    );
    
    $query = new WP_Query($args);
    
    // If no posts found in date range, get most commented posts of all time
    if (!$query->have_posts()) {
        unset($args['date_query']);
        $query = new WP_Query($args);
    }
    
    return $query;
}

/**
 * Get Breaking News / Latest Posts for Ticker
 *
 * @since 2.5.0
 * @param int $count Number of posts.
 * @param string $category Optional category slug.
 * @return WP_Query
 */
function legalpress_get_ticker_posts($count = 10, $category = '') {
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => absint($count),
        'orderby' => 'date',
        'order' => 'DESC',
        'no_found_rows' => true,
    );
    
    if (!empty($category)) {
        $args['category_name'] = sanitize_title($category);
    }
    
    return new WP_Query($args);
}

// =============================================================================
// POST VIEWS TRACKING
// =============================================================================

/**
 * Track post views
 * Uses post meta to store view count
 *
 * @since 2.5.0
 * @param int $post_id Post ID
 */
function legalpress_track_post_views($post_id = null) {
    if (!is_single()) return;
    
    if (empty($post_id)) {
        $post_id = get_the_ID();
    }
    
    // Don't track for logged in admins
    if (current_user_can('manage_options')) return;
    
    // Check if already counted in this session
    $viewed_key = 'legalpress_viewed_' . $post_id;
    if (isset($_COOKIE[$viewed_key])) return;
    
    $count = get_post_meta($post_id, 'legalpress_post_views', true);
    $count = empty($count) ? 1 : $count + 1;
    update_post_meta($post_id, 'legalpress_post_views', $count);
    
    // Set cookie to prevent multiple counts (30 min expiry)
    setcookie($viewed_key, '1', time() + 1800, '/');
}
add_action('wp_head', 'legalpress_track_post_views');

/**
 * Get post view count
 *
 * @since 2.5.0
 * @param int $post_id Post ID
 * @return int View count
 */
function legalpress_get_post_views($post_id = null) {
    if (empty($post_id)) {
        $post_id = get_the_ID();
    }
    
    $count = get_post_meta($post_id, 'legalpress_post_views', true);
    return empty($count) ? 0 : absint($count);
}

/**
 * Get Monthly Recap Posts
 * Returns the most viral posts from the current month
 *
 * @since 2.5.0
 * @param int $count Number of posts
 * @return WP_Query
 */
function legalpress_get_monthly_recap_posts($count = 5) {
    $current_month = date('n');
    $current_year = date('Y');
    
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => absint($count),
        'meta_key' => 'legalpress_post_views',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'no_found_rows' => true,
        'date_query' => array(
            array(
                'year' => $current_year,
                'month' => $current_month,
            ),
        ),
    );
    
    $query = new WP_Query($args);
    
    // If no posts with views in current month, get most commented
    if (!$query->have_posts()) {
        $args['orderby'] = 'comment_count';
        unset($args['meta_key']);
        $query = new WP_Query($args);
    }
    
    // If still no posts, get latest from current month
    if (!$query->have_posts()) {
        $args['orderby'] = 'date';
        $query = new WP_Query($args);
    }
    
    // If still no posts (new month), get last month's viral posts
    if (!$query->have_posts()) {
        $last_month = $current_month - 1;
        $last_year = $current_year;
        if ($last_month < 1) {
            $last_month = 12;
            $last_year--;
        }
        
        $args['meta_key'] = 'legalpress_post_views';
        $args['orderby'] = 'meta_value_num';
        $args['date_query'] = array(
            array(
                'year' => $last_year,
                'month' => $last_month,
            ),
        );
        $query = new WP_Query($args);
    }
    
    return $query;
}

/**
 * Get Opinion Posts
 * Returns posts from the Opinion category
 *
 * @since 2.5.0
 * @param int $count Number of posts
 * @param string $category_slug Category slug to fetch
 * @return WP_Query
 */
function legalpress_get_opinion_posts($count = 5, $category_slug = 'opinion') {
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => absint($count),
        'orderby' => 'date',
        'order' => 'DESC',
        'no_found_rows' => true,
        'category_name' => sanitize_title($category_slug),
    );
    
    return new WP_Query($args);
}

/**
 * Get the current month name
 *
 * @since 2.5.0
 * @return string Month name
 */
function legalpress_get_current_month_name() {
    return date_i18n('F Y');
}
