<?php
/**
 * Featured (Terkini) – recent posts 1 large + up to 4 small.
 *
 * @var array $args
 */

if (! defined('ABSPATH')) {
    exit;
}

$cat_id = isset($args['cat']) ? (int) $args['cat'] : 0;

$q_args = [
    'posts_per_page'      => 5,
    'ignore_sticky_posts' => false,
];
if ($cat_id > 0) {
    $q_args['cat'] = $cat_id;
}
$q = new WP_Query($q_args);
if (! $q->have_posts()) {
    wp_reset_postdata();
    return;
}
?>
<section class="spotlight-featured" aria-labelledby="terkini-title">
  <div class="container--spotlight">
    <div class="widget-title">
      <h3 id="terkini-title" class="title"><?php echo esc_html__('Terkini', 'gp-spotlight'); ?></h3>
      <?php if ($cat_id > 0) : ?>
        <a href="<?php echo esc_url(get_category_link($cat_id)); ?>"><?php echo esc_html__('View all', 'gp-spotlight'); ?></a>
      <?php endif; ?>
    </div>

    <div class="featured-grid">
      <?php
      $index = 0;
      $small_open = false;
      while ($q->have_posts()) :
          $q->the_post();
          $index++;
          if ($index === 1) : ?>
            <article class="featured-card">
              <a class="post-thumb" href="<?php the_permalink(); ?>">
                <?php
                if (has_post_thumbnail()) {
                    the_post_thumbnail('spotlight-landscape', ['loading' => 'lazy', 'alt' => esc_attr(get_the_title())]);
                } else {
                    echo '<img src="' . esc_url('https://via.placeholder.com/1200x675') . '" alt="" loading="lazy" />';
                }
                ?>
              </a>
              <div class="post-body">
                <?php gp_spotlight_entry_meta(); ?>
                <h2 class="post-title" style="font-size:26px;margin:6px 0;">
                  <a href="<?php the_permalink(); ?>" style="text-decoration:none;color:var(--text);"><?php the_title(); ?></a>
                </h2>
              </div>
            </article>
          <?php else :
              if ($index === 2) {
                  echo '<div class="featured-right">';
                  $small_open = true;
              } ?>
              <article class="featured-sm">
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
          <?php
          endif;
      endwhile;
      if ($small_open) {
          echo '</div>';
      }
      ?>
    </div>
  </div>
</section>
<?php wp_reset_postdata(); ?>