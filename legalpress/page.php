<?php
/**
 * Page Template
 * 
 * Template for displaying static pages.
 * Clean, distraction-free layout for content pages.
 * 
 * @package LegalPress
 * @since 1.0.0
 */

get_header();
?>

<?php while (have_posts()):
    the_post(); ?>

    <article id="page-<?php the_ID(); ?>" <?php post_class('page-content'); ?>>
        <div class="container">
            <div class="page-content__inner">

                <header class="page-content__header">
                    <h1 class="page-content__title"><?php the_title(); ?></h1>
                </header>

                <?php if (has_post_thumbnail()): ?>
                    <figure class="page-content__featured-image"
                        style="margin-bottom: var(--spacing-lg); border-radius: 8px; overflow: hidden;">
                        <?php the_post_thumbnail('legalpress-featured'); ?>
                    </figure>
                <?php endif; ?>

                <div class="page-content__body">
                    <?php
                    the_content();

                    // Pagination for multi-page content
                    wp_link_pages(array(
                        'before' => '<nav class="page-links"><span class="page-links__label">' . esc_html__('Pages:', 'legalpress') . '</span>',
                        'after' => '</nav>',
                        'link_before' => '<span class="page-links__number">',
                        'link_after' => '</span>',
                    ));
                    ?>
                </div>

                <?php
                // Display comments if enabled for pages
                if (comments_open() || get_comments_number()):
                    comments_template();
                endif;
                ?>

            </div>
        </div>
    </article>

<?php endwhile; ?>

<?php get_footer(); ?>