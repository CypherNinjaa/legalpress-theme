<?php
/**
 * Breadcrumb Template Part
 * 
 * Displays breadcrumb navigation
 * 
 * @package LegalPress
 * @since 2.5.0
 */

// Check if breadcrumbs are enabled
if (!get_theme_mod('legalpress_enable_breadcrumbs', true)) {
    return;
}

// Check context-specific settings
if (is_single() && !get_theme_mod('legalpress_breadcrumb_single', true)) {
    return;
}
if ((is_category() || is_archive()) && !get_theme_mod('legalpress_breadcrumb_archive', true)) {
    return;
}

// Don't show on homepage
if (is_front_page() || is_home()) {
    return;
}

$home_text = get_theme_mod('legalpress_breadcrumb_home', 'Home');
$separator = get_theme_mod('legalpress_breadcrumb_separator', '›');
?>

<nav class="breadcrumb" aria-label="<?php esc_attr_e('Breadcrumb', 'legalpress'); ?>" itemscope itemtype="https://schema.org/BreadcrumbList">
    <div class="container">
        <ol class="breadcrumb__list">
            
            <!-- Home -->
            <li class="breadcrumb__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="breadcrumb__link" itemprop="item">
                    <svg class="breadcrumb__home-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    <span itemprop="name"><?php echo esc_html($home_text); ?></span>
                </a>
                <meta itemprop="position" content="1" />
                <span class="breadcrumb__separator" aria-hidden="true"><?php echo esc_html($separator); ?></span>
            </li>

            <?php
            $position = 2;

            // Single Post
            if (is_single()) {
                // Get primary category
                $categories = get_the_category();
                if (!empty($categories)) {
                    $category = $categories[0];
                    
                    // Check for parent category
                    if ($category->parent) {
                        $parent = get_category($category->parent);
                        ?>
                        <li class="breadcrumb__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                            <a href="<?php echo esc_url(get_category_link($parent->term_id)); ?>" class="breadcrumb__link" itemprop="item">
                                <span itemprop="name"><?php echo esc_html($parent->name); ?></span>
                            </a>
                            <meta itemprop="position" content="<?php echo $position; ?>" />
                            <span class="breadcrumb__separator" aria-hidden="true"><?php echo esc_html($separator); ?></span>
                        </li>
                        <?php
                        $position++;
                    }
                    ?>
                    <li class="breadcrumb__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="breadcrumb__link" itemprop="item">
                            <span itemprop="name"><?php echo esc_html($category->name); ?></span>
                        </a>
                        <meta itemprop="position" content="<?php echo $position; ?>" />
                        <span class="breadcrumb__separator" aria-hidden="true"><?php echo esc_html($separator); ?></span>
                    </li>
                    <?php
                    $position++;
                }
                ?>
                <li class="breadcrumb__item breadcrumb__item--current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <span class="breadcrumb__current" itemprop="name"><?php echo esc_html(wp_trim_words(get_the_title(), 8, '...')); ?></span>
                    <meta itemprop="position" content="<?php echo $position; ?>" />
                </li>
                <?php
            }

            // Category Archive
            elseif (is_category()) {
                $category = get_queried_object();
                
                // Check for parent category
                if ($category->parent) {
                    $parent = get_category($category->parent);
                    ?>
                    <li class="breadcrumb__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <a href="<?php echo esc_url(get_category_link($parent->term_id)); ?>" class="breadcrumb__link" itemprop="item">
                            <span itemprop="name"><?php echo esc_html($parent->name); ?></span>
                        </a>
                        <meta itemprop="position" content="<?php echo $position; ?>" />
                        <span class="breadcrumb__separator" aria-hidden="true"><?php echo esc_html($separator); ?></span>
                    </li>
                    <?php
                    $position++;
                }
                ?>
                <li class="breadcrumb__item breadcrumb__item--current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <span class="breadcrumb__current" itemprop="name"><?php echo esc_html($category->name); ?></span>
                    <meta itemprop="position" content="<?php echo $position; ?>" />
                </li>
                <?php
            }

            // Tag Archive
            elseif (is_tag()) {
                ?>
                <li class="breadcrumb__item breadcrumb__item--current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <span class="breadcrumb__current" itemprop="name"><?php single_tag_title(); ?></span>
                    <meta itemprop="position" content="<?php echo $position; ?>" />
                </li>
                <?php
            }

            // Author Archive
            elseif (is_author()) {
                ?>
                <li class="breadcrumb__item breadcrumb__item--current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <span class="breadcrumb__current" itemprop="name"><?php the_author(); ?></span>
                    <meta itemprop="position" content="<?php echo $position; ?>" />
                </li>
                <?php
            }

            // Search Results
            elseif (is_search()) {
                ?>
                <li class="breadcrumb__item breadcrumb__item--current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <span class="breadcrumb__current" itemprop="name"><?php printf(esc_html__('Search: %s', 'legalpress'), get_search_query()); ?></span>
                    <meta itemprop="position" content="<?php echo $position; ?>" />
                </li>
                <?php
            }

            // Date Archives
            elseif (is_date()) {
                if (is_year()) {
                    ?>
                    <li class="breadcrumb__item breadcrumb__item--current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <span class="breadcrumb__current" itemprop="name"><?php echo get_the_date('Y'); ?></span>
                        <meta itemprop="position" content="<?php echo $position; ?>" />
                    </li>
                    <?php
                } elseif (is_month()) {
                    ?>
                    <li class="breadcrumb__item breadcrumb__item--current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <span class="breadcrumb__current" itemprop="name"><?php echo get_the_date('F Y'); ?></span>
                        <meta itemprop="position" content="<?php echo $position; ?>" />
                    </li>
                    <?php
                } elseif (is_day()) {
                    ?>
                    <li class="breadcrumb__item breadcrumb__item--current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <span class="breadcrumb__current" itemprop="name"><?php echo get_the_date(); ?></span>
                        <meta itemprop="position" content="<?php echo $position; ?>" />
                    </li>
                    <?php
                }
            }

            // Page
            elseif (is_page()) {
                global $post;
                
                // Parent pages
                if ($post->post_parent) {
                    $ancestors = get_post_ancestors($post->ID);
                    $ancestors = array_reverse($ancestors);
                    
                    foreach ($ancestors as $ancestor) {
                        ?>
                        <li class="breadcrumb__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                            <a href="<?php echo esc_url(get_permalink($ancestor)); ?>" class="breadcrumb__link" itemprop="item">
                                <span itemprop="name"><?php echo esc_html(get_the_title($ancestor)); ?></span>
                            </a>
                            <meta itemprop="position" content="<?php echo $position; ?>" />
                            <span class="breadcrumb__separator" aria-hidden="true"><?php echo esc_html($separator); ?></span>
                        </li>
                        <?php
                        $position++;
                    }
                }
                ?>
                <li class="breadcrumb__item breadcrumb__item--current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <span class="breadcrumb__current" itemprop="name"><?php the_title(); ?></span>
                    <meta itemprop="position" content="<?php echo $position; ?>" />
                </li>
                <?php
            }

            // 404 Page
            elseif (is_404()) {
                ?>
                <li class="breadcrumb__item breadcrumb__item--current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <span class="breadcrumb__current" itemprop="name"><?php esc_html_e('Page Not Found', 'legalpress'); ?></span>
                    <meta itemprop="position" content="<?php echo $position; ?>" />
                </li>
                <?php
            }
            ?>

        </ol>
    </div>
</nav>
