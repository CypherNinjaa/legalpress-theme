<?php
/**
 * Mobile Bottom Navigation
 * 
 * A beautiful fixed bottom navigation bar for mobile devices.
 * Completely independent and self-contained.
 * 
 * @package LegalPress
 * @since 2.5.0
 */

// Check if enabled
$bottom_nav_enabled = get_theme_mod('legalpress_mobile_bottom_nav', true);
if (!$bottom_nav_enabled) {
    return;
}

// Get customizer settings
$show_home = get_theme_mod('legalpress_bottom_nav_home', true);
$show_categories = get_theme_mod('legalpress_bottom_nav_categories', true);
$show_search = get_theme_mod('legalpress_bottom_nav_search', true);
$show_bookmarks = get_theme_mod('legalpress_bottom_nav_bookmarks', false);
$show_profile = get_theme_mod('legalpress_bottom_nav_profile', true);

// Get current page info for active state
$is_home = is_front_page() || is_home();
$is_search = is_search();
$is_archive = is_archive() || is_category();
?>

<!-- Mobile Bottom Navigation -->
<nav class="mobile-bottom-nav" id="mobile-bottom-nav" role="navigation" aria-label="<?php esc_attr_e('Mobile Navigation', 'legalpress'); ?>">
    <div class="mobile-bottom-nav__inner">
        
        <?php if ($show_home) : ?>
        <!-- Home -->
        <a href="<?php echo esc_url(home_url('/')); ?>" class="mobile-bottom-nav__item <?php echo $is_home ? 'is-active' : ''; ?>" data-nav="home">
            <span class="mobile-bottom-nav__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
            </span>
            <span class="mobile-bottom-nav__label"><?php esc_html_e('Home', 'legalpress'); ?></span>
        </a>
        <?php endif; ?>

        <?php if ($show_categories) : ?>
        <!-- Categories -->
        <button type="button" class="mobile-bottom-nav__item <?php echo $is_archive ? 'is-active' : ''; ?>" data-nav="categories" aria-expanded="false" aria-controls="mobile-categories-panel">
            <span class="mobile-bottom-nav__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
            </span>
            <span class="mobile-bottom-nav__label"><?php esc_html_e('Categories', 'legalpress'); ?></span>
        </button>
        <?php endif; ?>

        <?php if ($show_search) : ?>
        <!-- Search -->
        <button type="button" class="mobile-bottom-nav__item <?php echo $is_search ? 'is-active' : ''; ?>" data-nav="search" aria-expanded="false" aria-controls="mobile-search-panel">
            <span class="mobile-bottom-nav__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </span>
            <span class="mobile-bottom-nav__label"><?php esc_html_e('Search', 'legalpress'); ?></span>
        </button>
        <?php endif; ?>

        <?php if ($show_bookmarks) : ?>
        <!-- Bookmarks -->
        <button type="button" class="mobile-bottom-nav__item" data-nav="bookmarks" aria-expanded="false" aria-controls="mobile-bookmarks-panel">
            <span class="mobile-bottom-nav__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                </svg>
            </span>
            <span class="mobile-bottom-nav__label"><?php esc_html_e('Saved', 'legalpress'); ?></span>
            <span class="mobile-bottom-nav__badge" id="bookmarks-count" style="display: none;">0</span>
        </button>
        <?php endif; ?>

        <?php if ($show_profile) : ?>
        <!-- Profile / Menu -->
        <button type="button" class="mobile-bottom-nav__item" data-nav="menu" aria-expanded="false" aria-controls="mobile-menu-panel">
            <span class="mobile-bottom-nav__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </span>
            <span class="mobile-bottom-nav__label"><?php esc_html_e('Menu', 'legalpress'); ?></span>
        </button>
        <?php endif; ?>

    </div>
</nav>

<!-- Categories Panel -->
<?php if ($show_categories) : ?>
<div class="mobile-panel" id="mobile-categories-panel" aria-hidden="true">
    <div class="mobile-panel__overlay"></div>
    <div class="mobile-panel__content">
        <div class="mobile-panel__header">
            <h3 class="mobile-panel__title"><?php esc_html_e('Categories', 'legalpress'); ?></h3>
            <button type="button" class="mobile-panel__close" aria-label="<?php esc_attr_e('Close', 'legalpress'); ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="mobile-panel__body">
            <ul class="mobile-categories-list">
                <?php
                $categories = get_categories(array(
                    'orderby' => 'count',
                    'order' => 'DESC',
                    'hide_empty' => true,
                    'number' => 12,
                ));
                
                foreach ($categories as $category) :
                    $cat_color = legalpress_get_category_color($category->slug);
                ?>
                <li class="mobile-categories-list__item">
                    <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="mobile-categories-list__link">
                        <span class="mobile-categories-list__icon" style="background-color: <?php echo esc_attr($cat_color); ?>">
                            <?php echo esc_html(mb_substr($category->name, 0, 1)); ?>
                        </span>
                        <span class="mobile-categories-list__name"><?php echo esc_html($category->name); ?></span>
                        <span class="mobile-categories-list__count"><?php echo esc_html($category->count); ?></span>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Search Panel -->
<?php if ($show_search) : ?>
<div class="mobile-panel mobile-panel--search" id="mobile-search-panel" aria-hidden="true">
    <div class="mobile-panel__overlay"></div>
    <div class="mobile-panel__content">
        <div class="mobile-panel__header">
            <h3 class="mobile-panel__title"><?php esc_html_e('Search', 'legalpress'); ?></h3>
            <button type="button" class="mobile-panel__close" aria-label="<?php esc_attr_e('Close', 'legalpress'); ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="mobile-panel__body">
            <form role="search" method="get" class="mobile-search-form" action="<?php echo esc_url(home_url('/')); ?>">
                <div class="mobile-search-form__wrapper">
                    <input type="search" class="mobile-search-form__input" id="mobile-live-search" placeholder="<?php esc_attr_e('Search articles...', 'legalpress'); ?>" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" />
                    <span class="mobile-search-form__loader" style="display: none;">
                        <svg class="spinner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" stroke-dasharray="31.4 31.4" stroke-linecap="round"></circle>
                        </svg>
                    </span>
                    <button type="submit" class="mobile-search-form__submit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                </div>
            </form>
            
            <!-- Live Search Results -->
            <div class="mobile-search-results" id="mobile-search-results" style="display: none;">
                <div class="mobile-search-results__header">
                    <span class="mobile-search-results__count"></span>
                    <button type="button" class="mobile-search-results__clear"><?php esc_html_e('Clear', 'legalpress'); ?></button>
                </div>
                <div class="mobile-search-results__list"></div>
                <a href="#" class="mobile-search-results__viewall" style="display: none;">
                    <?php esc_html_e('View all results', 'legalpress'); ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
            </div>
            
            <!-- Popular Searches (shown when no search query) -->
            <div class="mobile-search-popular" id="mobile-search-popular">
                <h4 class="mobile-search-popular__title"><?php esc_html_e('Popular Topics', 'legalpress'); ?></h4>
                <div class="mobile-search-popular__tags">
                    <?php
                    $popular_tags = get_tags(array('orderby' => 'count', 'order' => 'DESC', 'number' => 8));
                    foreach ($popular_tags as $tag) :
                    ?>
                    <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="mobile-search-popular__tag">
                        <?php echo esc_html($tag->name); ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Recent Searches -->
            <div class="mobile-search-recent" id="mobile-search-recent" style="display: none;">
                <div class="mobile-search-recent__header">
                    <h4 class="mobile-search-recent__title"><?php esc_html_e('Recent Searches', 'legalpress'); ?></h4>
                    <button type="button" class="mobile-search-recent__clear"><?php esc_html_e('Clear all', 'legalpress'); ?></button>
                </div>
                <div class="mobile-search-recent__list"></div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Menu Panel -->
<?php if ($show_profile) : ?>
<div class="mobile-panel mobile-panel--menu" id="mobile-menu-panel" aria-hidden="true">
    <div class="mobile-panel__overlay"></div>
    <div class="mobile-panel__content">
        <div class="mobile-panel__header">
            <h3 class="mobile-panel__title"><?php esc_html_e('Menu', 'legalpress'); ?></h3>
            <button type="button" class="mobile-panel__close" aria-label="<?php esc_attr_e('Close', 'legalpress'); ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="mobile-panel__body">
            <?php
            if (has_nav_menu('primary')) {
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class' => 'mobile-menu-list',
                    'container' => false,
                    'depth' => 2,
                    'fallback_cb' => false,
                ));
            }
            ?>
            
            <!-- Quick Actions -->
            <div class="mobile-menu-actions">
                <?php if (is_user_logged_in()) : ?>
                <a href="<?php echo esc_url(admin_url()); ?>" class="mobile-menu-actions__item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20z"></path>
                        <path d="M12 6v6l4 2"></path>
                    </svg>
                    <?php esc_html_e('Dashboard', 'legalpress'); ?>
                </a>
                <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="mobile-menu-actions__item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <?php esc_html_e('Logout', 'legalpress'); ?>
                </a>
                <?php else : ?>
                <a href="<?php echo esc_url(wp_login_url()); ?>" class="mobile-menu-actions__item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                        <polyline points="10 17 15 12 10 7"></polyline>
                        <line x1="15" y1="12" x2="3" y2="12"></line>
                    </svg>
                    <?php esc_html_e('Login', 'legalpress'); ?>
                </a>
                <?php endif; ?>
            </div>
            
            <!-- Social Links -->
            <?php $social_links = legalpress_get_social_links(); ?>
            <?php if (!empty($social_links)) : ?>
            <div class="mobile-menu-social">
                <?php foreach ($social_links as $platform => $data) : ?>
                <a href="<?php echo esc_url($data['url']); ?>" class="mobile-menu-social__link mobile-menu-social__link--<?php echo esc_attr($platform); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($data['label']); ?>">
                    <?php echo legalpress_get_social_icon($platform); ?>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Bookmarks Panel -->
<?php if ($show_bookmarks) : ?>
<div class="mobile-panel mobile-panel--bookmarks" id="mobile-bookmarks-panel" aria-hidden="true">
    <div class="mobile-panel__overlay"></div>
    <div class="mobile-panel__content">
        <div class="mobile-panel__header">
            <h3 class="mobile-panel__title"><?php esc_html_e('Saved Articles', 'legalpress'); ?></h3>
            <button type="button" class="mobile-panel__close" aria-label="<?php esc_attr_e('Close', 'legalpress'); ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="mobile-panel__body">
            <div class="mobile-bookmarks-list" id="mobile-bookmarks-list">
                <div class="mobile-bookmarks-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <p><?php esc_html_e('No saved articles yet', 'legalpress'); ?></p>
                    <span><?php esc_html_e('Articles you save will appear here', 'legalpress'); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
