<?php
/**
 * Related posts – same categories, exclude current.
 */

if (! defined('ABSPATH')) {
    exit;
}

if (! is_single()) {
    return;
}

$cats = wp_get_post_categories(get_the_ID());
if (empty($cats)) {
    return;
}

$q = new WP_Query([
    'posts_per_page'      => 3,
    'category__in'        => $cats,
    'post__not_in'        => [get_the_ID()],
    'ignore_sticky_posts' => true,
]);

if (! $q->have_posts()) {
    wp_reset_postdata();
    return;
}
?>
<section class="spotlight-related" style="margin-top:24px;" aria-labelledby="related-title">
  <div class="widget-title">
    <h3 id="related-title" class="title"><?php echo esc_html__('You might like', 'gp-spotlight'); ?></h3>
    <a href="<?php echo esc_url(get_post_type_archive_link('post')); ?>"><?php echo esc_html__('View all', 'gp-spotlight'); ?></a>
  </div>
  <div class="related-grid">
    <?php while ($q->have_posts()) : $q->the_post(); ?>
      <article class="post-card">
        <a class="post-thumb" href="<?php the_permalink(); ?>">
          <?php
          if (has_post_thumbnail()) {
              the_post_thumbnail('spotlight-card', ['loading' => 'lazy', 'alt' => esc_attr(get_the_title())]);
          } else {
              echo '<img src="' . esc_url('https://via.placeholder.com/800x450') . '" alt="" loading="lazy" />';
          }
          ?>
        </a>
        <div class="post-body">
          <h3 class="post-title" style="font-size:18px;margin:4px 0;">
            <a href="<?php the_permalink(); ?>" style="text-decoration:none;color:var(--text);"><?php the_title(); ?></a>
          </h3>
          <div class="post-meta">
            <time datetime="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>"><?php echo esc_html(get_the_date()); ?></time>
          </div>
        </div>
      </article>
    <?php endwhile; ?>
  </div>
</section>
<?php wp_reset_postdata(); ?>