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
    define('LEGALPRESS_VERSION', '1.0.0');
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
 * @since 1.0.0
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

    // Main stylesheet
    wp_enqueue_style('legalpress-style', get_stylesheet_uri(), array('legalpress-google-fonts'), LEGALPRESS_VERSION);

    // Animation styles
    wp_enqueue_style('legalpress-animations', LEGALPRESS_URI . '/assets/css/animations.css', array('legalpress-style'), LEGALPRESS_VERSION);

    // Premium styles
    wp_enqueue_style('legalpress-premium', LEGALPRESS_URI . '/assets/css/premium.css', array('legalpress-animations'), LEGALPRESS_VERSION);

    // Component styles
    wp_enqueue_style('legalpress-components', LEGALPRESS_URI . '/assets/css/components.css', array('legalpress-premium'), LEGALPRESS_VERSION);

    // Main JavaScript
    wp_enqueue_script('legalpress-main', LEGALPRESS_URI . '/assets/js/main.js', array(), LEGALPRESS_VERSION, true);

    // Advanced JavaScript (animations, skeleton loading, etc.)
    wp_enqueue_script('legalpress-advanced', LEGALPRESS_URI . '/assets/js/advanced.js', array('legalpress-main'), LEGALPRESS_VERSION, true);

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
            '<a href="%s" class="post-card__category %s">%s</a>',
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
