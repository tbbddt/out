<?php
/**
 * Trending – Popular (7 Days). Prefers post_views_count meta if available
 * (e.g., Post Views Counter plugin), otherwise falls back to comment_count.
 *
 * @var array $args
 */

if (! defined('ABSPATH')) {
    exit;
}

$cat_id = isset($args['cat']) ? (int) $args['cat'] : 0;

// Try views-based ordering first.
$views_query = [
    'posts_per_page'      => 4,
    'ignore_sticky_posts' => true,
    'date_query'          => [
        [
            'after'     => '7 days ago',
            'inclusive' => true,
        ],
    ],
    'meta_key'            => 'post_views_count',
    'orderby'             => 'meta_value_num',
    'order'               => 'DESC',
];

if ($cat_id > 0) {
    $views_query['cat'] = $cat_id;
}

$q = new WP_Query($views_query);

// If no results (or views not tracked), fall back to comment_count over 7 days.
if (! $q->have_posts()) {
    wp_reset_postdata();
    $fallback = [
        'posts_per_page'      => 4,
        'ignore_sticky_posts' => true,
        'date_query'          => [
            [
                'after'     => '7 days ago',
                'inclusive' => true,
            ],
        ],
        'orderby'             => 'comment_count',
        'order'               => 'DESC',
    ];
    if ($cat_id > 0) {
        $fallback['cat'] = $cat_id;
    }
    $q = new WP_Query($fallback);
}

if (! $q->have_posts()) {
    wp_reset_postdata();
    return;
}
?>
<section class="spotlight-trending" aria-labelledby="popular-title">
  <div class="container--spotlight">
    <div class="widget-title">
      <h3 id="popular-title" class="title"><?php echo esc_html__('Popular (7 Hari)', 'gp-spotlight'); ?></h3>
      <?php if ($cat_id > 0) : ?>
        <a href="<?php echo esc_url(get_category_link($cat_id)); ?>"><?php echo esc_html__('View all', 'gp-spotlight'); ?></a>
      <?php endif; ?>
    </div>

    <div class="trending-grid">
      <?php while ($q->have_posts()) : $q->the_post(); ?>
        <article class="trending-card">
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
  </div>
</section>
<?php wp_reset_postdata(); ?>