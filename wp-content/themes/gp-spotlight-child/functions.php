<?php
/**
 * GeneratePress Child – Spotlight Minimal
 *
 * @package gp-spotlight
 */

if (! defined('ABSPATH')) {
    exit;
}

define('GP_SPOTLIGHT_VER', '1.2.0');

/**
 * Setup theme supports and sidebars.
 */
function gp_spotlight_setup(): void {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', [
        'height'      => 96,
        'width'       => 320,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support('responsive-embeds');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);

    // Image sizes tuned to layout; core adds intrinsic sizes to prevent CLS.
    add_image_size('spotlight-landscape', 1200, 675, true);
    add_image_size('spotlight-card', 800, 450, true);

    // Sidebars
    register_sidebar([
        'name'          => esc_html__('Sidebar', 'gp-spotlight'),
        'id'            => 'sidebar-1',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<div class="widget-title"><h3 class="title">',
        'after_title'   => '</h3></div>',
    ]);

    foreach (['Footer 1' => 'footer-1', 'Footer 2' => 'footer-2', 'Footer 3' => 'footer-3'] as $label => $id) {
        register_sidebar([
            'name'          => esc_html__($label, 'gp-spotlight'),
            'id'            => $id,
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<div class="widget-title"><h3 class="title">',
            'after_title'   => '</h3></div>',
        ]);
    }
}
add_action('after_setup_theme', 'gp_spotlight_setup');

/**
 * Enqueue styles; no front-end JS for minimal footprint.
 */
function gp_spotlight_enqueue(): void {
    $parent = wp_get_theme(get_template());
    wp_enqueue_style('generatepress', get_template_directory_uri() . '/style.css', [], $parent->get('Version'));
    wp_enqueue_style('gp-spotlight', get_stylesheet_uri(), ['generatepress'], GP_SPOTLIGHT_VER);
}
add_action('wp_enqueue_scripts', 'gp_spotlight_enqueue', 20);

/**
 * Customizer: Featured and Trending categories (optional).
 */
require_once get_stylesheet_directory() . '/inc/customizer.php';

/**
 * Insert Featured (Terkini) and Trending sections after header on homepage.
 */
function gp_spotlight_home_sections(): void {
    if (! (is_front_page() || is_home())) {
        return;
    }
    $featured_cat = absint(get_theme_mod('gp_spotlight_featured_cat', 0));
    $trending_cat = absint(get_theme_mod('gp_spotlight_trending_cat', 0));

    get_template_part('template-parts/section', 'featured', ['cat' => $featured_cat]);
    get_template_part('template-parts/section', 'trending', ['cat' => $trending_cat]);
}
add_action('generate_after_header', 'gp_spotlight_home_sections', 12);

/**
 * Append Related posts after single content.
 */
function gp_spotlight_related_after_content(string $content): string {
    if (! is_single() || ! in_the_loop() || ! is_main_query()) {
        return $content;
    }
    ob_start();
    get_template_part('template-parts/section', 'related');
    $related = (string) ob_get_clean();
    return $content . $related;
}
add_filter('the_content', 'gp_spotlight_related_after_content', 20);

/**
 * Add a simple "Contact" page template (no JS, nonce + honeypot).
 */
require_once get_stylesheet_directory() . '/page-contact.php';

/**
 * Entry meta helpers.
 */
require_once get_stylesheet_directory() . '/template-parts/entry-meta.php';

/**
 * RankMath breadcrumbs (optional). Output before single content if available.
 */
function gp_spotlight_breadcrumbs(): void {
    if (is_single() && function_exists('rank_math_the_breadcrumbs')) {
        echo '<div class="container--spotlight" style="margin-top:16px;"><nav aria-label="Breadcrumbs">';
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        rank_math_the_breadcrumbs();
        echo '</nav></div>';
    }
}
add_action('generate_before_content', 'gp_spotlight_breadcrumbs', 8);

/**
 * SECURITY: No schema output here; RankMath handles all SEO markup.
 */