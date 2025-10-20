<?php
/**
 * Entry meta helpers – compact and clean.
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Prints author avatar/name and date in a single line.
 */
function gp_spotlight_entry_meta(): void {
    echo '<div class="post-meta" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">';

    $author_id = get_the_author_meta('ID');
    $avatar    = get_avatar_url($author_id, ['size' => 44]);

    if ($avatar) {
        echo '<img src="' . esc_url($avatar) . '" alt="' . esc_attr(get_the_author()) . '" width="28" height="28" style="width:28px;height:28px;border-radius:9999px;object-fit:cover;" loading="lazy" />';
    }
    echo '<span>' . esc_html(get_the_author()) . '</span>';
    echo '<span aria-hidden="true">•</span>';
    echo '<time datetime="' . esc_attr(get_the_date(DATE_W3C)) . '">' . esc_html(get_the_date()) . '</time>';

    echo '</div>';
}