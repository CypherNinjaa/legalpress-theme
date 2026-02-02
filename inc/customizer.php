<?php
/**
 * LegalPress Theme Customizer
 * 
 * All theme options controllable by admin
 * 
 * @package LegalPress
 * @since 2.5.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register LawChakra Feature Customizer Settings
 */
function legalpress_lawchakra_customize_register($wp_customize) {
    
    // =========================================================================
    // TOP BAR SECTION
    // =========================================================================
    $wp_customize->add_section('legalpress_topbar_section', array(
        'title'       => __('Top Bar Settings', 'legalpress'),
        'description' => __('Configure the top utility bar with date, social icons, and more.', 'legalpress'),
        'priority'    => 25,
    ));

    // Enable Top Bar
    $wp_customize->add_setting('legalpress_enable_topbar', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('legalpress_enable_topbar', array(
        'label'   => __('Enable Top Bar', 'legalpress'),
        'section' => 'legalpress_topbar_section',
        'type'    => 'checkbox',
    ));

    // Show Live Date/Time
    $wp_customize->add_setting('legalpress_topbar_show_datetime', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('legalpress_topbar_show_datetime', array(
        'label'   => __('Show Live Date & Time', 'legalpress'),
        'section' => 'legalpress_topbar_section',
        'type'    => 'checkbox',
    ));

    // Date Format
    $wp_customize->add_setting('legalpress_topbar_date_format', array(
        'default'           => 'D. M jS, Y',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('legalpress_topbar_date_format', array(
        'label'       => __('Date Format', 'legalpress'),
        'description' => __('PHP date format. Default: D. M jS, Y (e.g., Mon. Feb 2nd, 2026)', 'legalpress'),
        'section'     => 'legalpress_topbar_section',
        'type'        => 'text',
    ));

    // Show Social Icons
    $wp_customize->add_setting('legalpress_topbar_show_social', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('legalpress_topbar_show_social', array(
        'label'   => __('Show Social Media Icons', 'legalpress'),
        'section' => 'legalpress_topbar_section',
        'type'    => 'checkbox',
    ));

    // YouTube URL
    $wp_customize->add_setting('legalpress_social_youtube', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('legalpress_social_youtube', array(
        'label'   => __('YouTube Channel URL', 'legalpress'),
        'section' => 'legalpress_topbar_section',
        'type'    => 'url',
    ));

    // LinkedIn URL
    $wp_customize->add_setting('legalpress_social_linkedin', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('legalpress_social_linkedin', array(
        'label'   => __('LinkedIn URL', 'legalpress'),
        'section' => 'legalpress_topbar_section',
        'type'    => 'url',
    ));

    // Twitter/X URL
    $wp_customize->add_setting('legalpress_social_twitter', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('legalpress_social_twitter', array(
        'label'   => __('Twitter/X URL', 'legalpress'),
        'section' => 'legalpress_topbar_section',
        'type'    => 'url',
    ));

    // Facebook URL
    $wp_customize->add_setting('legalpress_social_facebook', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('legalpress_social_facebook', array(
        'label'   => __('Facebook URL', 'legalpress'),
        'section' => 'legalpress_topbar_section',
        'type'    => 'url',
    ));

    // Instagram URL
    $wp_customize->add_setting('legalpress_social_instagram', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('legalpress_social_instagram', array(
        'label'   => __('Instagram URL', 'legalpress'),
        'section' => 'legalpress_topbar_section',
        'type'    => 'url',
    ));

    // Telegram URL
    $wp_customize->add_setting('legalpress_social_telegram', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('legalpress_social_telegram', array(
        'label'   => __('Telegram URL', 'legalpress'),
        'section' => 'legalpress_topbar_section',
        'type'    => 'url',
    ));

    // WhatsApp URL
    $wp_customize->add_setting('legalpress_social_whatsapp', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('legalpress_social_whatsapp', array(
        'label'   => __('WhatsApp URL/Number', 'legalpress'),
        'section' => 'legalpress_topbar_section',
        'type'    => 'url',
    ));

    // Top Bar Background Color
    $wp_customize->add_setting('legalpress_topbar_bg_color', array(
        'default'           => '#1e293b',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'legalpress_topbar_bg_color', array(
        'label'   => __('Top Bar Background Color', 'legalpress'),
        'section' => 'legalpress_topbar_section',
    )));

    // Top Bar Text Color
    $wp_customize->add_setting('legalpress_topbar_text_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'legalpress_topbar_text_color', array(
        'label'   => __('Top Bar Text Color', 'legalpress'),
        'section' => 'legalpress_topbar_section',
    )));

    // =========================================================================
    // NEWS TICKER SECTION
    // =========================================================================
    $wp_customize->add_section('legalpress_ticker_section', array(
        'title'       => __('News Ticker Settings', 'legalpress'),
        'description' => __('Configure the breaking news ticker/carousel.', 'legalpress'),
        'priority'    => 26,
    ));

    // Enable News Ticker
    $wp_customize->add_setting('legalpress_enable_ticker', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('legalpress_enable_ticker', array(
        'label'   => __('Enable News Ticker', 'legalpress'),
        'section' => 'legalpress_ticker_section',
        'type'    => 'checkbox',
    ));

    // Ticker Label
    $wp_customize->add_setting('legalpress_ticker_label', array(
        'default'           => 'Top Stories',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('legalpress_ticker_label', array(
        'label'   => __('Ticker Label Text', 'legalpress'),
        'section' => 'legalpress_ticker_section',
        'type'    => 'text',
    ));

    // Number of Posts in Ticker
    $wp_customize->add_setting('legalpress_ticker_count', array(
        'default'           => 10,
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('legalpress_ticker_count', array(
        'label'   => __('Number of Posts to Show', 'legalpress'),
        'section' => 'legalpress_ticker_section',
        'type'    => 'number',
        'input_attrs' => array(
            'min'  => 3,
            'max'  => 20,
            'step' => 1,
        ),
    ));

    // Ticker Speed
    $wp_customize->add_setting('legalpress_ticker_speed', array(
        'default'           => 30,
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('legalpress_ticker_speed', array(
        'label'       => __('Scroll Speed (seconds)', 'legalpress'),
        'description' => __('Time to complete one scroll cycle.', 'legalpress'),
        'section'     => 'legalpress_ticker_section',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 10,
            'max'  => 120,
            'step' => 5,
        ),
    ));

    // Ticker Category Filter
    $wp_customize->add_setting('legalpress_ticker_category', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('legalpress_ticker_category', array(
        'label'       => __('Filter by Category (slug)', 'legalpress'),
        'description' => __('Leave empty to show all categories. Enter category slug to filter.', 'legalpress'),
        'section'     => 'legalpress_ticker_section',
        'type'        => 'text',
    ));

    // Ticker Background Color
    $wp_customize->add_setting('legalpress_ticker_bg_color', array(
        'default'           => '#0f172a',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'legalpress_ticker_bg_color', array(
        'label'   => __('Ticker Background Color', 'legalpress'),
        'section' => 'legalpress_ticker_section',
    )));

    // Ticker Label Background Color
    $wp_customize->add_setting('legalpress_ticker_label_bg', array(
        'default'           => '#d4a84b',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'legalpress_ticker_label_bg', array(
        'label'   => __('Ticker Label Background Color', 'legalpress'),
        'section' => 'legalpress_ticker_section',
    )));

    // =========================================================================
    // BREADCRUMB SECTION
    // =========================================================================
    $wp_customize->add_section('legalpress_breadcrumb_section', array(
        'title'       => __('Breadcrumb Settings', 'legalpress'),
        'description' => __('Configure breadcrumb navigation.', 'legalpress'),
        'priority'    => 27,
    ));

    // Enable Breadcrumbs
    $wp_customize->add_setting('legalpress_enable_breadcrumbs', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('legalpress_enable_breadcrumbs', array(
        'label'   => __('Enable Breadcrumbs', 'legalpress'),
        'section' => 'legalpress_breadcrumb_section',
        'type'    => 'checkbox',
    ));

    // Breadcrumb Home Text
    $wp_customize->add_setting('legalpress_breadcrumb_home', array(
        'default'           => 'Home',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('legalpress_breadcrumb_home', array(
        'label'   => __('Home Link Text', 'legalpress'),
        'section' => 'legalpress_breadcrumb_section',
        'type'    => 'text',
    ));

    // Breadcrumb Separator
    $wp_customize->add_setting('legalpress_breadcrumb_separator', array(
        'default'           => '›',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('legalpress_breadcrumb_separator', array(
        'label'   => __('Separator Character', 'legalpress'),
        'section' => 'legalpress_breadcrumb_section',
        'type'    => 'text',
    ));

    // Show on Single Posts
    $wp_customize->add_setting('legalpress_breadcrumb_single', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('legalpress_breadcrumb_single', array(
        'label'   => __('Show on Single Posts', 'legalpress'),
        'section' => 'legalpress_breadcrumb_section',
        'type'    => 'checkbox',
    ));

    // Show on Category Archives
    $wp_customize->add_setting('legalpress_breadcrumb_archive', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('legalpress_breadcrumb_archive', array(
        'label'   => __('Show on Category/Archive Pages', 'legalpress'),
        'section' => 'legalpress_breadcrumb_section',
        'type'    => 'checkbox',
    ));

    // =========================================================================
    // AUTHOR BIO SECTION
    // =========================================================================
    $wp_customize->add_section('legalpress_author_section', array(
        'title'       => __('Author Bio Settings', 'legalpress'),
        'description' => __('Configure author bio box on single posts.', 'legalpress'),
        'priority'    => 28,
    ));

    // Enable Author Bio
    $wp_customize->add_setting('legalpress_enable_author_bio', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('legalpress_enable_author_bio', array(
        'label'   => __('Enable Author Bio Box', 'legalpress'),
        'section' => 'legalpress_author_section',
        'type'    => 'checkbox',
    ));

    // Author Avatar Size
    $wp_customize->add_setting('legalpress_author_avatar_size', array(
        'default'           => 120,
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('legalpress_author_avatar_size', array(
        'label'   => __('Author Avatar Size (px)', 'legalpress'),
        'section' => 'legalpress_author_section',
        'type'    => 'number',
        'input_attrs' => array(
            'min'  => 50,
            'max'  => 200,
            'step' => 10,
        ),
    ));

    // Show Author Social Links
    $wp_customize->add_setting('legalpress_author_show_social', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('legalpress_author_show_social', array(
        'label'   => __('Show Author Social Links', 'legalpress'),
        'section' => 'legalpress_author_section',
        'type'    => 'checkbox',
    ));

    // Show Author Post Count
    $wp_customize->add_setting('legalpress_author_show_posts', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('legalpress_author_show_posts', array(
        'label'   => __('Show Author Post Count', 'legalpress'),
        'section' => 'legalpress_author_section',
        'type'    => 'checkbox',
    ));

    // =========================================================================
    // FLOATING SHARE SIDEBAR
    // =========================================================================
    $wp_customize->add_setting('legalpress_floating_share_enable', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('legalpress_floating_share_enable', array(
        'label'       => __('Enable Floating Share Buttons', 'legalpress'),
        'description' => __('Show floating social share sidebar on single posts (desktop only).', 'legalpress'),
        'section'     => 'legalpress_author_section',
        'type'        => 'checkbox',
    ));

    // =========================================================================
    // HOMEPAGE SECTIONS
    // =========================================================================
    $wp_customize->add_section('legalpress_homepage_section', array(
        'title'       => __('Homepage Content', 'legalpress'),
        'description' => __('Configure homepage hero, latest news, and category sections. To create new categories, go to Posts → Categories in the WordPress admin.', 'legalpress'),
        'priority'    => 29,
    ));

    // ─── HERO SECTION SETTINGS ───
    $wp_customize->add_setting('legalpress_hero_separator', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'legalpress_hero_separator', array(
        'label'   => __('━━━ Hero Section Settings ━━━', 'legalpress'),
        'section' => 'legalpress_homepage_section',
        'type'    => 'hidden',
    )));

    // Enable Hero Section
    $wp_customize->add_setting('legalpress_hero_enable', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('legalpress_hero_enable', array(
        'label'   => __('Enable Hero Section', 'legalpress'),
        'description' => __('Show the large featured post hero section at the top of homepage.', 'legalpress'),
        'section' => 'legalpress_homepage_section',
        'type'    => 'checkbox',
    ));

    // Hero Post Source
    $wp_customize->add_setting('legalpress_hero_source', array(
        'default'           => 'latest',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('legalpress_hero_source', array(
        'label'   => __('Hero Post Source', 'legalpress'),
        'description' => __('Choose which post appears in the hero section.', 'legalpress'),
        'section' => 'legalpress_homepage_section',
        'type'    => 'select',
        'choices' => array(
            'latest'   => __('Latest Post (Newest)', 'legalpress'),
            'sticky'   => __('Sticky Post (Pinned)', 'legalpress'),
            'random'   => __('Random Post', 'legalpress'),
            'specific' => __('Specific Post (Select below)', 'legalpress'),
        ),
    ));

    // Get posts for dropdown
    $recent_posts = get_posts(array(
        'numberposts' => 50,
        'post_status' => 'publish',
    ));
    $post_choices = array('' => __('-- Select a Post --', 'legalpress'));
    foreach ($recent_posts as $post) {
        $post_choices[$post->ID] = $post->post_title;
    }

    // Hero Specific Post Selection
    $wp_customize->add_setting('legalpress_hero_post_id', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('legalpress_hero_post_id', array(
        'label'       => __('Select Specific Post for Hero', 'legalpress'),
        'description' => __('Only used when "Specific Post" is selected above.', 'legalpress'),
        'section'     => 'legalpress_homepage_section',
        'type'        => 'select',
        'choices'     => $post_choices,
    ));

    // ─── LATEST NEWS SECTION SETTINGS ───
    $wp_customize->add_setting('legalpress_latest_separator', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'legalpress_latest_separator', array(
        'label'   => __('━━━ Latest News Settings ━━━', 'legalpress'),
        'section' => 'legalpress_homepage_section',
        'type'    => 'hidden',
    )));

    // Enable Latest News Section
    $wp_customize->add_setting('legalpress_latest_news_enable', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('legalpress_latest_news_enable', array(
        'label'   => __('Enable Latest News Section', 'legalpress'),
        'section' => 'legalpress_homepage_section',
        'type'    => 'checkbox',
    ));

    // Latest News Title
    $wp_customize->add_setting('legalpress_latest_news_title', array(
        'default'           => 'Latest News',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('legalpress_latest_news_title', array(
        'label'   => __('Latest News Section Title', 'legalpress'),
        'section' => 'legalpress_homepage_section',
        'type'    => 'text',
    ));

    // Latest News Post Count
    $wp_customize->add_setting('legalpress_latest_news_count', array(
        'default'           => 6,
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('legalpress_latest_news_count', array(
        'label'   => __('Latest News Posts Count', 'legalpress'),
        'section' => 'legalpress_homepage_section',
        'type'    => 'number',
        'input_attrs' => array(
            'min'  => 3,
            'max'  => 12,
            'step' => 1,
        ),
    ));

    // Separator
    $wp_customize->add_setting('legalpress_sections_separator', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'legalpress_sections_separator', array(
        'label'       => __('─── Category Sections ───', 'legalpress'),
        'description' => __('Configure up to 6 category sections below:', 'legalpress'),
        'section'     => 'legalpress_homepage_section',
        'type'        => 'hidden',
    )));

    // Get all categories for dropdown selection
    $all_categories = get_categories(array('hide_empty' => false, 'orderby' => 'count', 'order' => 'DESC'));
    $category_choices = array('' => __('-- Select Category --', 'legalpress'));
    $default_category_slugs = array(); // Store first 4 category slugs for defaults
    
    foreach ($all_categories as $index => $cat) {
        $category_choices[$cat->slug] = $cat->name . ' (' . $cat->count . ' posts)';
        // Store first 4 categories for defaults (excluding 'uncategorized')
        if (count($default_category_slugs) < 4 && $cat->slug !== 'uncategorized') {
            $default_category_slugs[] = $cat->slug;
        }
    }

    // Default colors for each section
    $default_section_colors = array(
        1 => '#1e3a5f', // Navy Blue
        2 => '#059669', // Green
        3 => '#7c3aed', // Purple
        4 => '#dc2626', // Red
        5 => '#d97706', // Orange
        6 => '#0891b2', // Cyan
    );

    // Category Section Loop
    for ($i = 1; $i <= 6; $i++) {
        // Get default category slug for this section
        $default_cat = isset($default_category_slugs[$i - 1]) ? $default_category_slugs[$i - 1] : '';
        
        // Enable Section
        $wp_customize->add_setting("legalpress_section_{$i}_enable", array(
            'default'           => ($i <= 4),
            'sanitize_callback' => 'wp_validate_boolean',
        ));
        $wp_customize->add_control("legalpress_section_{$i}_enable", array(
            'label'   => sprintf(__('━━━ Enable Section %d ━━━', 'legalpress'), $i),
            'section' => 'legalpress_homepage_section',
            'type'    => 'checkbox',
        ));

        // Section Title
        $wp_customize->add_setting("legalpress_section_{$i}_title", array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("legalpress_section_{$i}_title", array(
            'label'       => sprintf(__('Section %d Title', 'legalpress'), $i),
            'description' => __('Leave empty to use category name', 'legalpress'),
            'section'     => 'legalpress_homepage_section',
            'type'        => 'text',
        ));

        // Section Category - DROPDOWN with dynamic default
        $wp_customize->add_setting("legalpress_section_{$i}_category", array(
            'default'           => $default_cat,
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("legalpress_section_{$i}_category", array(
            'label'       => sprintf(__('Section %d Category', 'legalpress'), $i),
            'description' => __('Select category to display', 'legalpress'),
            'section'     => 'legalpress_homepage_section',
            'type'        => 'select',
            'choices'     => $category_choices,
        ));

        // Number of Posts
        $wp_customize->add_setting("legalpress_section_{$i}_count", array(
            'default'           => 6,
            'sanitize_callback' => 'absint',
        ));
        $wp_customize->add_control("legalpress_section_{$i}_count", array(
            'label'   => sprintf(__('Section %d Posts Count', 'legalpress'), $i),
            'section' => 'legalpress_homepage_section',
            'type'    => 'number',
            'input_attrs' => array(
                'min'  => 3,
                'max'  => 12,
                'step' => 1,
            ),
        ));

        // Section Color - with dynamic default
        $wp_customize->add_setting("legalpress_section_{$i}_color", array(
            'default'           => $default_section_colors[$i] ?? '#1e3a5f',
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, "legalpress_section_{$i}_color", array(
            'label'   => sprintf(__('Section %d Accent Color', 'legalpress'), $i),
            'section' => 'legalpress_homepage_section',
        )));
    }

    // =========================================================================
    // SIMILAR POSTS SECTION
    // =========================================================================
    $wp_customize->add_section('legalpress_similar_posts_section', array(
        'title'       => __('Similar Posts Settings', 'legalpress'),
        'description' => __('Configure similar/related posts display.', 'legalpress'),
        'priority'    => 30,
    ));

    // Enable Similar Posts
    $wp_customize->add_setting('legalpress_enable_similar_posts', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('legalpress_enable_similar_posts', array(
        'label'   => __('Enable Similar Posts Section', 'legalpress'),
        'section' => 'legalpress_similar_posts_section',
        'type'    => 'checkbox',
    ));

    // Similar Posts Count
    $wp_customize->add_setting('legalpress_similar_posts_count', array(
        'default'           => 3,
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('legalpress_similar_posts_count', array(
        'label'   => __('Number of Similar Posts', 'legalpress'),
        'section' => 'legalpress_similar_posts_section',
        'type'    => 'number',
        'input_attrs' => array(
            'min'  => 2,
            'max'  => 6,
            'step' => 1,
        ),
    ));

    // Similar Posts Title
    $wp_customize->add_setting('legalpress_similar_posts_title', array(
        'default'           => 'Similar Posts',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('legalpress_similar_posts_title', array(
        'label'   => __('Section Title', 'legalpress'),
        'section' => 'legalpress_similar_posts_section',
        'type'    => 'text',
    ));

    // =========================================================================
    // SIDEBAR WIDGETS SECTION
    // =========================================================================
    $wp_customize->add_section('legalpress_sidebar_section', array(
        'title'       => __('Sidebar Settings', 'legalpress'),
        'description' => __('Configure sidebar widgets and layout.', 'legalpress'),
        'priority'    => 31,
    ));

    // Enable Sidebar on Single Posts
    $wp_customize->add_setting('legalpress_single_sidebar', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('legalpress_single_sidebar', array(
        'label'   => __('Enable Sidebar on Single Posts', 'legalpress'),
        'section' => 'legalpress_sidebar_section',
        'type'    => 'checkbox',
    ));

    // Enable Related News Widget
    $wp_customize->add_setting('legalpress_sidebar_related_news', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('legalpress_sidebar_related_news', array(
        'label'   => __('Show Related News Widget', 'legalpress'),
        'section' => 'legalpress_sidebar_section',
        'type'    => 'checkbox',
    ));

    // Related News Count
    $wp_customize->add_setting('legalpress_sidebar_related_count', array(
        'default'           => 5,
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('legalpress_sidebar_related_count', array(
        'label'   => __('Related News Posts Count', 'legalpress'),
        'section' => 'legalpress_sidebar_section',
        'type'    => 'number',
        'input_attrs' => array(
            'min'  => 3,
            'max'  => 10,
            'step' => 1,
        ),
    ));

    // Enable Trending News Widget
    $wp_customize->add_setting('legalpress_sidebar_trending', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('legalpress_sidebar_trending', array(
        'label'   => __('Show Trending News Widget', 'legalpress'),
        'section' => 'legalpress_sidebar_section',
        'type'    => 'checkbox',
    ));

    // Trending Days
    $wp_customize->add_setting('legalpress_trending_days', array(
        'default'           => 7,
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('legalpress_trending_days', array(
        'label'       => __('Trending Period (days)', 'legalpress'),
        'description' => __('Show posts from the last X days.', 'legalpress'),
        'section'     => 'legalpress_sidebar_section',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 30,
            'step' => 1,
        ),
    ));

    // =========================================================================
    // HOMEPAGE SIDEBAR WIDGETS (Monthly Recap & Opinion)
    // =========================================================================
    $wp_customize->add_section('legalpress_homepage_sidebar_section', array(
        'title'       => __('Homepage Sidebar', 'legalpress'),
        'description' => __('Configure Monthly Recap and Opinion widgets for homepage sidebar.', 'legalpress'),
        'priority'    => 32,
    ));

    // ----- Monthly Recap Settings -----
    $wp_customize->add_setting('legalpress_monthly_recap_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'legalpress_monthly_recap_heading', array(
        'label'       => __('── Monthly Recap Widget ──', 'legalpress'),
        'description' => __('Shows the most viral posts from this month.', 'legalpress'),
        'section'     => 'legalpress_homepage_sidebar_section',
        'type'        => 'hidden',
    )));

    // Enable Monthly Recap
    $wp_customize->add_setting('legalpress_monthly_recap_enable', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('legalpress_monthly_recap_enable', array(
        'label'   => __('Enable Monthly Recap Widget', 'legalpress'),
        'section' => 'legalpress_homepage_sidebar_section',
        'type'    => 'checkbox',
    ));

    // Monthly Recap Title
    $wp_customize->add_setting('legalpress_monthly_recap_title', array(
        'default'           => 'Monthly Recap',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('legalpress_monthly_recap_title', array(
        'label'   => __('Monthly Recap Title', 'legalpress'),
        'section' => 'legalpress_homepage_sidebar_section',
        'type'    => 'text',
    ));

    // Monthly Recap Posts Count
    $wp_customize->add_setting('legalpress_monthly_recap_count', array(
        'default'           => 4,
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('legalpress_monthly_recap_count', array(
        'label'   => __('Number of Posts', 'legalpress'),
        'section' => 'legalpress_homepage_sidebar_section',
        'type'    => 'number',
        'input_attrs' => array(
            'min'  => 2,
            'max'  => 10,
            'step' => 1,
        ),
    ));

    // ----- Opinion Settings -----
    $wp_customize->add_setting('legalpress_opinion_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'legalpress_opinion_heading', array(
        'label'       => __('── Opinion Widget ──', 'legalpress'),
        'description' => __('Shows posts from a specific category.', 'legalpress'),
        'section'     => 'legalpress_homepage_sidebar_section',
        'type'        => 'hidden',
    )));

    // Enable Opinion
    $wp_customize->add_setting('legalpress_opinion_enable', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('legalpress_opinion_enable', array(
        'label'   => __('Enable Opinion Widget', 'legalpress'),
        'section' => 'legalpress_homepage_sidebar_section',
        'type'    => 'checkbox',
    ));

    // Opinion Title
    $wp_customize->add_setting('legalpress_opinion_title', array(
        'default'           => 'Opinion',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('legalpress_opinion_title', array(
        'label'   => __('Opinion Widget Title', 'legalpress'),
        'section' => 'legalpress_homepage_sidebar_section',
        'type'    => 'text',
    ));

    // Opinion Category
    $wp_customize->add_setting('legalpress_opinion_category', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'legalpress_opinion_category', array(
        'label'       => __('Opinion Category', 'legalpress'),
        'description' => __('Select the category to display.', 'legalpress'),
        'section'     => 'legalpress_homepage_sidebar_section',
        'type'        => 'select',
        'choices'     => legalpress_get_category_choices(),
    )));

    // Opinion Posts Count
    $wp_customize->add_setting('legalpress_opinion_count', array(
        'default'           => 4,
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('legalpress_opinion_count', array(
        'label'   => __('Number of Posts', 'legalpress'),
        'section' => 'legalpress_homepage_sidebar_section',
        'type'    => 'number',
        'input_attrs' => array(
            'min'  => 2,
            'max'  => 10,
            'step' => 1,
        ),
    ));

    // =========================================================================
    // MOBILE BOTTOM NAVIGATION
    // =========================================================================
    $wp_customize->add_section('legalpress_mobile_nav_section', array(
        'title'       => __('Mobile Bottom Navigation', 'legalpress'),
        'description' => __('Configure the mobile bottom navigation bar.', 'legalpress'),
        'priority'    => 35,
    ));

    // Enable Mobile Bottom Nav
    $wp_customize->add_setting('legalpress_mobile_bottom_nav', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('legalpress_mobile_bottom_nav', array(
        'label'       => __('Enable Mobile Bottom Navigation', 'legalpress'),
        'description' => __('Show a fixed navigation bar at the bottom on mobile devices.', 'legalpress'),
        'section'     => 'legalpress_mobile_nav_section',
        'type'        => 'checkbox',
    ));

    // Show Home Button
    $wp_customize->add_setting('legalpress_bottom_nav_home', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('legalpress_bottom_nav_home', array(
        'label'   => __('Show Home Button', 'legalpress'),
        'section' => 'legalpress_mobile_nav_section',
        'type'    => 'checkbox',
    ));

    // Show Categories Button
    $wp_customize->add_setting('legalpress_bottom_nav_categories', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('legalpress_bottom_nav_categories', array(
        'label'   => __('Show Categories Button', 'legalpress'),
        'section' => 'legalpress_mobile_nav_section',
        'type'    => 'checkbox',
    ));

    // Show Search Button
    $wp_customize->add_setting('legalpress_bottom_nav_search', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('legalpress_bottom_nav_search', array(
        'label'   => __('Show Search Button (Center)', 'legalpress'),
        'section' => 'legalpress_mobile_nav_section',
        'type'    => 'checkbox',
    ));

    // Show Bookmarks Button
    $wp_customize->add_setting('legalpress_bottom_nav_bookmarks', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('legalpress_bottom_nav_bookmarks', array(
        'label'       => __('Show Saved/Bookmarks Button', 'legalpress'),
        'description' => __('Allows users to save articles locally.', 'legalpress'),
        'section'     => 'legalpress_mobile_nav_section',
        'type'        => 'checkbox',
    ));

    // Show Menu Button
    $wp_customize->add_setting('legalpress_bottom_nav_profile', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('legalpress_bottom_nav_profile', array(
        'label'   => __('Show Menu Button', 'legalpress'),
        'section' => 'legalpress_mobile_nav_section',
        'type'    => 'checkbox',
    ));
}
add_action('customize_register', 'legalpress_lawchakra_customize_register');

/**
 * Output Customizer CSS
 */
function legalpress_customizer_css() {
    $topbar_bg = get_theme_mod('legalpress_topbar_bg_color', '#1e293b');
    $topbar_text = get_theme_mod('legalpress_topbar_text_color', '#ffffff');
    $ticker_bg = get_theme_mod('legalpress_ticker_bg_color', '#0f172a');
    $ticker_label_bg = get_theme_mod('legalpress_ticker_label_bg', '#d4a84b');
    $ticker_speed = get_theme_mod('legalpress_ticker_speed', 30);
    
    ?>
    <style type="text/css" id="legalpress-customizer-css">
        .top-bar {
            background-color: <?php echo esc_attr($topbar_bg); ?>;
            color: <?php echo esc_attr($topbar_text); ?>;
        }
        .top-bar a {
            color: <?php echo esc_attr($topbar_text); ?>;
        }
        .news-ticker {
            background-color: <?php echo esc_attr($ticker_bg); ?>;
        }
        .news-ticker__label {
            background-color: <?php echo esc_attr($ticker_label_bg); ?>;
        }
        .news-ticker__track {
            animation-duration: <?php echo esc_attr($ticker_speed); ?>s;
        }
        <?php
        // Output section colors
        for ($i = 1; $i <= 6; $i++) {
            $color = get_theme_mod("legalpress_section_{$i}_color", '#1e3a5f');
            echo ".homepage-section-{$i} .section-title__icon { color: " . esc_attr($color) . "; }";
            echo ".homepage-section-{$i} .section-link { color: " . esc_attr($color) . "; }";
        }
        ?>
    </style>
    <?php
}
add_action('wp_head', 'legalpress_customizer_css');
