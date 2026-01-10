<?php
/**
 * Header Template
 * 
 * Displays the site header with logo, navigation, and mobile menu.
 * This file is loaded via get_header() in other templates.
 * 
 * @package LegalPress
 * @since 2.0.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <a class="skip-link" href="#main-content">
        <?php esc_html_e('Skip to content', 'legalpress'); ?>
    </a>

    <header class="site-header" role="banner">
        <div class="container">
            <div class="header-inner">

                <!-- Site Branding -->
                <div class="site-branding">
                    <?php if (has_custom_logo()): ?>
                        <div class="site-logo">
                            <?php the_custom_logo(); ?>
                        </div>
                    <?php else: ?>
                        <h1 class="site-title">
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                <?php bloginfo('name'); ?>
                            </a>
                        </h1>
                    <?php endif; ?>
                </div>

                <!-- Primary Navigation (Desktop) -->
                <nav class="main-navigation" role="navigation"
                    aria-label="<?php esc_attr_e('Primary Menu', 'legalpress'); ?>">
                    <?php
                    if (has_nav_menu('primary')) {
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'menu_class' => 'menu',
                            'container' => false,
                            'depth' => 2,
                            'fallback_cb' => false,
                        ));
                    } else {
                        // Fallback menu for when no menu is assigned
                        ?>
                        <ul class="menu">
                            <li class="menu-item"><a
                                    href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'legalpress'); ?></a>
                            </li>
                            <?php
                            // Show categories as menu items
                            $categories = get_categories(array(
                                'orderby' => 'count',
                                'order' => 'DESC',
                                'number' => 5,
                                'hide_empty' => true,
                            ));

                            foreach ($categories as $category) {
                                printf(
                                    '<li class="menu-item"><a href="%s">%s</a></li>',
                                    esc_url(get_category_link($category->term_id)),
                                    esc_html($category->name)
                                );
                            }
                            ?>
                        </ul>
                        <?php
                    }
                    ?>
                </nav>

                <!-- Header Actions -->
                <div class="header-actions">
                    <!-- Theme Toggle -->
                    <button class="theme-toggle" aria-label="<?php esc_attr_e('Toggle dark mode', 'legalpress'); ?>">
                        <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="5" />
                            <line x1="12" y1="1" x2="12" y2="3" />
                            <line x1="12" y1="21" x2="12" y2="23" />
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" />
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" />
                            <line x1="1" y1="12" x2="3" y2="12" />
                            <line x1="21" y1="12" x2="23" y2="12" />
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" />
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" />
                        </svg>
                        <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
                        </svg>
                    </button>

                    <!-- Mobile Menu Toggle Button -->
                    <button class="mobile-menu-toggle" aria-controls="mobile-navigation" aria-expanded="false"
                        aria-label="<?php esc_attr_e('Toggle Menu', 'legalpress'); ?>">
                        <span class="hamburger">
                            <span class="hamburger-line"></span>
                            <span class="hamburger-line"></span>
                            <span class="hamburger-line"></span>
                        </span>
                    </button>
                </div>

            </div>
        </div>
    </header>

    <!-- Mobile Navigation -->
    <nav class="mobile-navigation" id="mobile-navigation" role="navigation"
        aria-label="<?php esc_attr_e('Mobile Menu', 'legalpress'); ?>">
        <div class="mobile-nav-inner">
            <div class="mobile-menu">
                <?php
                if (has_nav_menu('primary')) {
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_class' => 'menu',
                        'container' => false,
                        'depth' => 2,
                        'fallback_cb' => false,
                    ));
                } else {
                    // Same fallback menu for mobile
                    ?>
                    <ul class="menu">
                        <li class="menu-item"><a
                                href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'legalpress'); ?></a>
                        </li>
                        <?php
                        $categories = get_categories(array(
                            'orderby' => 'count',
                            'order' => 'DESC',
                            'number' => 5,
                            'hide_empty' => true,
                        ));

                        foreach ($categories as $category) {
                            printf(
                                '<li class="menu-item"><a href="%s">%s</a></li>',
                                esc_url(get_category_link($category->term_id)),
                                esc_html($category->name)
                            );
                        }
                        ?>
                    </ul>
                    <?php
                }
                ?>
            </div>
        </div>
    </nav>

    <main id="main-content" class="site-main" role="main">