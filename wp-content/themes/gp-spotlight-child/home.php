<?php
/**
 * Posts page – simple grid with classic pagination (no JS).
 */

if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>
<div class="spotlight-content">
  <div class="container--spotlight">
    <div class="grid-two">
      <main>
        <div class="widget-title">
          <h3 class="title"><?php echo esc_html__('Terbaru', 'gp-spotlight'); ?></h3>
          <a href="<?php echo esc_url(get_post_type_archive_link('post')); ?>"><?php echo esc_html__('View all', 'gp-spotlight'); ?></a>
        </div>

        <div class="posts-grid">
          <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <?php get_template_part('template-parts/content', 'card'); ?>
          <?php endwhile; endif; ?>
        </div>

        <nav class="pagination" aria-label="<?php esc_attr_e('Posts navigation', 'gp-spotlight'); ?>" style="margin-top:16px;">
          <?php
          the_posts_pagination([
              'mid_size'  => 1,
              'prev_text' => esc_html__('Previous', 'gp-spotlight'),
              'next_text' => esc_html__('Next', 'gp-spotlight'),
          ]);
          ?>
        </nav>
      </main>

      <aside>
        <?php if (is_active_sidebar('sidebar-1')) { dynamic_sidebar('sidebar-1'); } ?>
      </aside>
    </div>
  </div>
</div>
<?php
get_footer();