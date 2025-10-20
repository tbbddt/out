<?php
/**
 * Single post – clean layout, relies on RankMath for SEO/Schema.
 */

if (! defined('ABSPATH')) {
    exit;
}

get_header();

if (have_posts()) :
    while (have_posts()) : the_post(); ?>
        <div class="spotlight-content">
          <div class="container--spotlight">
            <article <?php post_class('post'); ?> style="max-width:880px;margin:0 auto;">
              <header style="margin-top:16px;">
                <h1 class="post-title" style="font-size:34px;line-height:1.25;"><?php the_title(); ?></h1>
                <div style="margin-top:8px;"><?php gp_spotlight_entry_meta(); ?></div>
              </header>

              <div class="entry-content" style="margin-top:16px;">
                <?php
                if (has_post_thumbnail()) {
                    echo '<a class="post-thumb" href="' . esc_url(get_permalink()) . '">';
                    the_post_thumbnail('spotlight-landscape', ['loading' => 'lazy', 'alt' => esc_attr(get_the_title())]);
                    echo '</a>';
                }
                the_content();

                wp_link_pages([
                    'before' => '<nav class="post-pages">' . esc_html__('Pages:', 'gp-spotlight'),
                    'after'  => '</nav>',
                ]);
                ?>
              </div>

              <footer style="margin-top:16px;">
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                  <?php
                  $tags = get_the_tags();
                  if ($tags) {
                      foreach ($tags as $tag) {
                          echo '<a href="' . esc_url(get_tag_link($tag)) . '" style="text-decoration:none;border:1px solid var(--border);border-radius:8px;padding:6px 10px;color:var(--text);">#' . esc_html($tag->name) . '</a>';
                      }
                  }
                  ?>
                </div>
              </footer>

              <?php comments_template(); ?>
            </article>
          </div>
        </div>
    <?php endwhile;
endif;

get_footer();