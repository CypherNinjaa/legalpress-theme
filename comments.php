<?php
/**
 * Comments Template
 * 
 * Displays comments and comment form for posts.
 * 
 * @package LegalPress
 * @since 1.0.0
 */

// Don't load if password protected
if (post_password_required()) {
    return;
}
?>

<section id="comments" class="comments-area">

    <?php if (have_comments()): ?>

        <h2 class="comments-title">
            <?php
            $comment_count = get_comments_number();
            printf(
                /* translators: 1: Comment count, 2: Post title */
                _n(
                    '%1$s Comment on &ldquo;%2$s&rdquo;',
                    '%1$s Comments on &ldquo;%2$s&rdquo;',
                    $comment_count,
                    'legalpress'
                ),
                number_format_i18n($comment_count),
                get_the_title()
            );
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'style' => 'ol',
                'short_ping' => true,
                'avatar_size' => 48,
                'callback' => 'legalpress_comment_callback',
            ));
            ?>
        </ol>

        <?php
        // Comment pagination
        the_comments_pagination(array(
            'prev_text' => '&larr; ' . esc_html__('Older Comments', 'legalpress'),
            'next_text' => esc_html__('Newer Comments', 'legalpress') . ' &rarr;',
        ));
        ?>

        <?php if (!comments_open()): ?>
            <p class="no-comments" style="margin-top: var(--spacing-lg); color: var(--color-text-muted); font-style: italic;">
                <?php esc_html_e('Comments are closed.', 'legalpress'); ?>
            </p>
        <?php endif; ?>

    <?php endif; ?>

    <?php
    // Comment form
    comment_form(array(
        'title_reply' => esc_html__('Leave a Comment', 'legalpress'),
        'title_reply_to' => esc_html__('Reply to %s', 'legalpress'),
        'cancel_reply_link' => esc_html__('Cancel Reply', 'legalpress'),
        'label_submit' => esc_html__('Submit Comment', 'legalpress'),
        'comment_notes_before' => '<p class="comment-notes" style="margin-bottom: var(--spacing-md); color: var(--color-text-light); font-size: 0.9375rem;">' .
            esc_html__('Your email address will not be published. Required fields are marked *', 'legalpress') .
            '</p>',
        'comment_field' => '<p class="comment-form-comment"><label for="comment">' .
            esc_html__('Comment *', 'legalpress') .
            '</label><textarea id="comment" name="comment" required></textarea></p>',
    ));
    ?>

</section>