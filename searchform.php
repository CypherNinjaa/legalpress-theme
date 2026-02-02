<?php
/**
 * Search Form Template
 * 
 * Custom search form with clean styling.
 * 
 * @package LegalPress
 * @since 1.0.0
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label class="screen-reader-text" for="search-field-<?php echo esc_attr(wp_unique_id()); ?>">
        <?php esc_html_e('Search for:', 'legalpress'); ?>
    </label>

    <div style="display: flex; gap: 0.5rem;">
        <input type="search" id="search-field-<?php echo esc_attr(wp_unique_id()); ?>" class="search-form__input"
            placeholder="<?php esc_attr_e('Search articles...', 'legalpress'); ?>"
            value="<?php echo get_search_query(); ?>" name="s"
            style="flex: 1; padding: 0.75rem 1rem; border: 1px solid var(--color-border, #e0e0e0); border-radius: 5px; font-size: 1rem; font-family: inherit;" />

        <button type="submit" class="search-form__submit"
            style="padding: 0.75rem 1.5rem; background: var(--color-primary, #1a1a2e); color: #fff; border: none; border-radius: 5px; font-weight: 600; cursor: pointer; transition: background 0.2s ease;"
            onmouseover="this.style.background='var(--color-accent, #c9a227)'; this.style.color='var(--color-primary, #1a1a2e)';"
            onmouseout="this.style.background='var(--color-primary, #1a1a2e)'; this.style.color='#fff';">
            <?php esc_html_e('Search', 'legalpress'); ?>
        </button>
    </div>
</form>