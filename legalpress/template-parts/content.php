<?php
/**
 * Post Content Template Part
 * 
 * Displays post content within the loop.
 * Can be used for different content formats.
 * 
 * @package LegalPress
 * @since 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('entry'); ?>>

    <header class="entry__header">
        <?php
        if (is_singular()):
            the_title('<h1 class="entry__title">', '</h1>');
        else:
            the_title('<h2 class="entry__title"><a href="' . esc_url(get_permalink()) . '">', '</a></h2>');
        endif;
        ?>

        <div class="entry__meta">
            <span class="entry__date">
                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                    <?php echo esc_html(get_the_date()); ?>
                </time>
            </span>
            <span class="entry__author">
                <?php esc_html_e('by', 'legalpress'); ?>
                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                    <?php the_author(); ?>
                </a>
            </span>
        </div>
    </header>

    <?php if (has_post_thumbnail() && !is_singular()): ?>
        <figure class="entry__thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('legalpress-card'); ?>
            </a>
        </figure>
    <?php endif; ?>

    <div class="entry__content">
        <?php
        if (is_singular()):
            the_content();
        else:
            the_excerpt();
            ?>
            <a href="<?php the_permalink(); ?>" class="entry__read-more">
                <?php esc_html_e('Read More', 'legalpress'); ?> →
            </a>
        <?php endif; ?>
    </div>

</article>