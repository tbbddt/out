<?php
/**
 * Comments – default WP with avatars, no extra JS.
 */

if (! defined('ABSPATH')) {
    exit;
}

if (post_password_required()) {
    return;
}
?>
<section id="comments" class="comments-area" style="max-width:880px;margin:16px auto;">
  <?php if (have_comments()) : ?>
    <div class="widget-title">
      <h3 class="title">
        <?php
        printf(
            esc_html(_nx('%1$s Comment', '%1$s Comments', get_comments_number(), 'comments title', 'gp-spotlight')),
            number_format_i18n(get_comments_number())
        );
        ?>
      </h3>
    </div>

    <ol class="comment-list">
      <?php
      wp_list_comments([
          'style'       => 'ol',
          'short_ping'  => true,
          'avatar_size' => 44,
      ]);
      ?>
    </ol>

    <?php the_comments_pagination([
        'prev_text' => esc_html__('Previous', 'gp-spotlight'),
        'next_text' => esc_html__('Next', 'gp-spotlight'),
    ]); ?>
  <?php endif; ?>

  <?php
  if (! comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) :
      echo '<p class="no-comments">' . esc_html__('Comments are closed.', 'gp-spotlight') . '</p>';
  endif;

  comment_form();
  ?>
</section>