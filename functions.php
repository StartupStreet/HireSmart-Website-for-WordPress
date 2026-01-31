<?php
/**
 * Theme Functions and Definitions
 * 
 * @package HireSmart
 * @version 1.0.0
 */

// Theme setup
function hiresmart_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');
    
    // Let WordPress manage the document title
    add_theme_support('title-tag');
    
    // Enable support for Post Thumbnails
    add_theme_support('post-thumbnails');
    
    // Add support for custom logo
    add_theme_support('custom-logo');
    
    // Add support for HTML5 markup
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
}
add_action('after_setup_theme', 'hiresmart_setup');

// Enqueue scripts and styles
function hiresmart_scripts() {
    wp_enqueue_style('hiresmart-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // Enqueue custom JavaScript
    wp_enqueue_script('hiresmart-script', get_template_directory_uri() . '/js/main.js', array(), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'hiresmart_scripts');

// Remove admin bar for front-end
show_admin_bar(false);
