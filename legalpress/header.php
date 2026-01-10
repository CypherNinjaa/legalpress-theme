<?php
/**
 * Header Template
 * 
 * Displays the site header with logo, navigation, and mobile menu.
 * This file is loaded via get_header() in other templates.
 * 
 * @package LegalPress
 * @since 1.0.0
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

    <a class="screen-reader-text" href="#main-content">
        <?php esc_html_e('Skip to content', 'legalpress'); ?>
    </a>

    <header class="site-header" role="banner">
        <div class="container">
            <div class="header__inner">

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
                <nav class="primary-nav" role="navigation"
                    aria-label="<?php esc_attr_e('Primary Menu', 'legalpress'); ?>">
                    <?php
                    if (has_nav_menu('primary')) {
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'menu_class' => 'nav-menu',
                            'container' => false,
                            'depth' => 2,
                            'fallback_cb' => false,
                        ));
                    } else {
                        // Fallback menu for when no menu is assigned
                        ?>
                        <ul class="nav-menu">
                            <li><a
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
                                    '<li><a href="%s">%s</a></li>',
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

                <!-- Mobile Menu Toggle Button -->
                <button class="menu-toggle" aria-controls="mobile-nav" aria-expanded="false"
                    aria-label="<?php esc_attr_e('Toggle Menu', 'legalpress'); ?>">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

            </div>
        </div>

        <!-- Mobile Navigation -->
        <nav class="mobile-nav" id="mobile-nav" role="navigation"
            aria-label="<?php esc_attr_e('Mobile Menu', 'legalpress'); ?>">
            <div class="container">
                <?php
                if (has_nav_menu('primary')) {
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_class' => 'nav-menu',
                        'container' => false,
                        'depth' => 2,
                        'fallback_cb' => false,
                    ));
                } else {
                    // Same fallback menu for mobile
                    ?>
                    <ul class="nav-menu">
                        <li><a
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
                                '<li><a href="%s">%s</a></li>',
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
        </nav>
    </header>

    <main id="main-content" class="site-main" role="main">