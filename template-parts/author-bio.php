<?php
/**
 * Author Bio Box Template Part
 * 
 * Displays author information on single posts
 * 
 * @package LegalPress
 * @since 2.5.0
 */

// Check if author bio is enabled
if (!get_theme_mod('legalpress_enable_author_bio', true)) {
    return;
}

$avatar_size = get_theme_mod('legalpress_author_avatar_size', 120);
$show_social = get_theme_mod('legalpress_author_show_social', true);
$show_posts = get_theme_mod('legalpress_author_show_posts', true);

$author_id = get_the_author_meta('ID');
$author_name = get_the_author();
$author_bio = get_the_author_meta('description');
$author_url = get_author_posts_url($author_id);
$author_website = get_the_author_meta('url');
$post_count = count_user_posts($author_id, 'post', true);

// Get author social links (from user meta)
$author_twitter = get_the_author_meta('twitter');
$author_facebook = get_the_author_meta('facebook');
$author_linkedin = get_the_author_meta('linkedin');
$author_instagram = get_the_author_meta('instagram');
?>

<aside class="author-bio" aria-label="<?php esc_attr_e('About the author', 'legalpress'); ?>">
    <div class="author-bio__inner">
        
        <!-- Author Avatar -->
        <div class="author-bio__avatar-wrapper">
            <a href="<?php echo esc_url($author_url); ?>" class="author-bio__avatar-link">
                <?php echo get_avatar($author_id, $avatar_size, '', $author_name, array('class' => 'author-bio__avatar')); ?>
            </a>
        </div>

        <!-- Author Info -->
        <div class="author-bio__content">
            
            <!-- Author Name -->
            <h3 class="author-bio__name">
                <a href="<?php echo esc_url($author_url); ?>"><?php echo esc_html($author_name); ?></a>
            </h3>

            <!-- Author Role/Title -->
            <?php 
            $author_role = get_the_author_meta('legalpress_author_role');
            if ($author_role): 
            ?>
            <span class="author-bio__role"><?php echo esc_html($author_role); ?></span>
            <?php endif; ?>

            <!-- Author Bio -->
            <?php if ($author_bio): ?>
            <p class="author-bio__description"><?php echo esc_html($author_bio); ?></p>
            <?php else: ?>
            <p class="author-bio__description author-bio__description--empty">
                <?php esc_html_e('This author has not added a bio yet.', 'legalpress'); ?>
            </p>
            <?php endif; ?>

            <!-- Author Meta -->
            <div class="author-bio__meta">
                
                <?php if ($show_posts && $post_count > 0): ?>
                <a href="<?php echo esc_url($author_url); ?>" class="author-bio__meta-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                    <span>
                        <?php 
                        printf(
                            esc_html(_n('%d Article', '%d Articles', $post_count, 'legalpress')),
                            $post_count
                        ); 
                        ?>
                    </span>
                </a>
                <?php endif; ?>

                <?php if ($author_website): ?>
                <a href="<?php echo esc_url($author_website); ?>" class="author-bio__meta-item" target="_blank" rel="noopener noreferrer">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="2" y1="12" x2="22" y2="12"/>
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                    </svg>
                    <span><?php esc_html_e('Website', 'legalpress'); ?></span>
                </a>
                <?php endif; ?>

            </div>

            <!-- Author Social Links -->
            <?php if ($show_social && ($author_twitter || $author_facebook || $author_linkedin || $author_instagram)): ?>
            <div class="author-bio__social">
                
                <?php if ($author_twitter): ?>
                <a href="<?php echo esc_url('https://twitter.com/' . $author_twitter); ?>" class="author-bio__social-link" target="_blank" rel="noopener noreferrer" aria-label="Twitter">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                </a>
                <?php endif; ?>

                <?php if ($author_facebook): ?>
                <a href="<?php echo esc_url($author_facebook); ?>" class="author-bio__social-link" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </a>
                <?php endif; ?>

                <?php if ($author_linkedin): ?>
                <a href="<?php echo esc_url($author_linkedin); ?>" class="author-bio__social-link" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                    </svg>
                </a>
                <?php endif; ?>

                <?php if ($author_instagram): ?>
                <a href="<?php echo esc_url('https://instagram.com/' . $author_instagram); ?>" class="author-bio__social-link" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                </a>
                <?php endif; ?>

            </div>
            <?php endif; ?>

            <!-- View All Posts Button -->
            <a href="<?php echo esc_url($author_url); ?>" class="author-bio__btn">
                <?php esc_html_e('View All Articles', 'legalpress'); ?>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="5" y1="12" x2="19" y2="12"/>
                    <polyline points="12 5 19 12 12 19"/>
                </svg>
            </a>

        </div>

    </div>
</aside>
