<?php
/**
 * Minimal footer with three widget columns and a simple footer menu.
 */

if (! defined('ABSPATH')) {
    exit;
}
?>
  <footer class="site-footer--spotlight" role="contentinfo">
    <div class="container--spotlight footer-inner">
      <div class="footer-widgets">
        <div><?php if (is_active_sidebar('footer-1')) { dynamic_sidebar('footer-1'); } ?></div>
        <div><?php if (is_active_sidebar('footer-2')) { dynamic_sidebar('footer-2'); } ?></div>
        <div><?php if (is_active_sidebar('footer-3')) { dynamic_sidebar('footer-3'); } ?></div>
      </div>

      <div class="footer-bar">
        <div>
          <?php
          echo wp_kses_post(
              sprintf(
                  '© %1$s %2$s · %3$s',
                  esc_html(get_bloginfo('name')),
                  esc_html(date_i18n('Y')),
                  sprintf(
                      __('Published with %s', 'gp-spotlight'),
                      '<a href="https://wordpress.org/" rel="noopener">WordPress</a>'
                  )
              )
          );
          ?>
        </div>
        <div class="footer-menu">
          <?php
          // Footer menu if assigned
          $menu = wp_nav_menu([
              'theme_location' => 'footer',
              'container'      => false,
              'menu_class'     => 'menu',
              'fallback_cb'    => '__return_empty_string',
              'depth'          => 1,
              'echo'           => false,
          ]);
          if ($menu) {
              // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
              echo $menu;
          } else {
              // Fallback links including Contact if page exists
              echo '<ul class="menu">';
              $contact = get_page_by_path('contact');
              if ($contact) {
                  echo '<li><a href="' . esc_url(get_permalink($contact)) . '">' . esc_html__('Contact', 'gp-spotlight') . '</a></li>';
              }
              echo '<li><a href="' . esc_url(home_url('/privacy-policy')) . '">' . esc_html__('Privacy Policy', 'gp-spotlight') . '</a></li>';
              echo '<li><a href="' . esc_url(home_url('/terms')) . '">' . esc_html__('Terms of Use', 'gp-spotlight') . '</a></li>';
              echo '</ul>';
          }
          ?>
        </div>
      </div>
    </div>
  </footer>
</div><!-- .site-outer -->

<?php wp_footer(); ?>
</body>
</html>