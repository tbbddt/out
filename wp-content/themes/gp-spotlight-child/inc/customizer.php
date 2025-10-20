<?php
/**
 * Customizer settings for Featured/Trending categories
 *
 * @package gp-spotlight
 */

if (! defined('ABSPATH')) {
    exit;
}

function gp_spotlight_customizer(WP_Customize_Manager $wp_customize): void {
    $wp_customize->add_panel('gp_spotlight_panel', [
        'title'    => __('Spotlight', 'gp-spotlight'),
        'priority' => 160,
    ]);

    // Featured (Terkini)
    $wp_customize->add_section('gp_spotlight_featured', [
        'title' => __('Featured (Terkini)', 'gp-spotlight'),
        'panel' => 'gp_spotlight_panel',
    ]);

    $wp_customize->add_setting('gp_spotlight_featured_cat', [
        'default'           => 0,
        'sanitize_callback' => 'absint',
    ]);

    $wp_customize->add_control(
        'gp_spotlight_featured_cat',
        [
            'label'   => __('Featured Category (optional, leave 0 for all recent)', 'gp-spotlight'),
            'type'    => 'select',
            'section' => 'gp_spotlight_featured',
            'choices' => gp_spotlight_category_choices(),
        ]
    );

    // Trending (7 Days)
    $wp_customize->add_section('gp_spotlight_trending', [
        'title' => __('Trending (7 Days)', 'gp-spotlight'),
        'panel' => 'gp_spotlight_panel',
    ]);

    $wp_customize->add_setting('gp_spotlight_trending_cat', [
        'default'           => 0,
        'sanitize_callback' => 'absint',
    ]);

    $wp_customize->add_control(
        'gp_spotlight_trending_cat',
        [
            'label'   => __('Trending Category (optional)', 'gp-spotlight'),
            'type'    => 'select',
            'section' => 'gp_spotlight_trending',
            'choices' => gp_spotlight_category_choices(),
        ]
    );
}
add_action('customize_register', 'gp_spotlight_customizer');

/**
 * Category choices helper.
 *
 * @return array<string,string>
 */
function gp_spotlight_category_choices(): array {
    $choices = [0 => __('— All —', 'gp-spotlight')];
    $terms = get_terms([
        'taxonomy'   => 'category',
        'hide_empty' => false,
    ]);
    if (is_wp_error($terms)) {
        return $choices;
    }
    foreach ($terms as $term) {
        $choices[(string) $term->term_id] = $term->name;
    }
    return $choices;
}