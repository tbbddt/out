<?php
/**
 * Minimal header with logo, primary menu, and vanilla GET search form.
 */

if (! defined('ABSPATH')) {
    exit;
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="site-outer">
  <header class="spotlight-header" role="banner">
    <div class="container--spotlight">
      <div class="spotlight-header__inner">
        <div class="spotlight-logo">
          <?php
          if (has_custom_logo()) {
              the_custom_logo();
          } else {
              $fallback = get_stylesheet_directory_uri() . '/assets/images/logo.svg';
              if (@fopen($fallback, 'r')) {
                  echo '<a href="' . esc_url(home_url('/')) . '"><img src="' . esc_url($fallback) . '" alt="' . esc_attr(get_bloginfo('name')) . '" height="32" loading="eager"></a>';
              } else {
                  echo '<a href="' . esc_url(home_url('/')) . '">' . esc_html(get_bloginfo('name')) . '</a>';
              }
          }
          ?>
        </div>

        <nav class="main-navigation" aria-label="<?php esc_attr_e('Primary', 'gp-spotlight'); ?>">
          <?php
          wp_nav_menu([
              'theme_location' => 'primary',
              'container'      => false,
              'menu_class'     => 'menu',
              'fallback_cb'    => '__return_empty_string',
              'depth'          => 2,
          ]);
          ?>
        </nav>

        <form class="header-search" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
          <label class="screen-reader-text" for="q"><?php echo esc_html_x('Search for:', 'label', 'gp-spotlight'); ?></label>
          <input id="q" type="search" name="s" placeholder="<?php echo esc_attr__('Search...', 'gp-spotlight'); ?>" value="<?php echo esc_attr(get_search_query()); ?>" />
          <button type="submit"><?php echo esc_html__('Search', 'gp-spotlight'); ?></button>
        </form>
      </div>
    </div>
  </header>