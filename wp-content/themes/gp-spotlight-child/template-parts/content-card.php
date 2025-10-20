<?php
/**
 * Card used in archives/home grid.
 */

if (! defined('ABSPATH')) {
    exit;
}
?>
<article <?php post_class('post-card'); ?>>
  <a class="post-thumb" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(get_the_title()); ?>">
    <?php
    if (has_post_thumbnail()) {
        the_post_thumbnail('spotlight-card', ['loading' => 'lazy', 'alt' => esc_attr(get_the_title())]);
    } else {
        echo '<img src="' . esc_url('https://via.placeholder.com/800x450') . '" alt="" loading="lazy" />';
    }
    ?>
  </a>
  <div class="post-body">
    <h2 class="post-title" style="font-size:22px;margin:4px 0;">
      <a href="<?php the_permalink(); ?>" style="text-decoration:none;color:var(--text);"><?php the_title(); ?></a>
    </h2>
    <div class="post-meta">
      <time datetime="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>"><?php echo esc_html(get_the_date()); ?></time>
    </div>
  </div>
</article>