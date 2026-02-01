<?php
/**
 * Plugin Name: HireSmart - AI-Powered Job Portal
 * Plugin URI: https://github.com/StartupStreet/HireSmart-Website-for-WordPress
 * Description: Complete AI-powered job portal with ATS, user authentication, dashboards, payment integration, and profile management
 * Version: 1.0.0
 * Author: StartupStreet
 * Author URI: https://github.com/StartupStreet
 * License: GPL v2 or later
 * Text Domain: hiresmart
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('HIRESMART_VERSION', '1.0.0');
define('HIRESMART_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('HIRESMART_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once HIRESMART_PLUGIN_DIR . 'includes/class-hiresmart-core.php';
require_once HIRESMART_PLUGIN_DIR . 'includes/class-hiresmart-auth.php';
require_once HIRESMART_PLUGIN_DIR . 'includes/class-hiresmart-user.php';
require_once HIRESMART_PLUGIN_DIR . 'includes/class-hiresmart-subscription.php';
require_once HIRESMART_PLUGIN_DIR . 'includes/class-hiresmart-payment.php';
require_once HIRESMART_PLUGIN_DIR . 'includes/class-hiresmart-dashboard.php';
require_once HIRESMART_PLUGIN_DIR . 'includes/class-hiresmart-ai-profiling.php';
require_once HIRESMART_PLUGIN_DIR . 'includes/class-hiresmart-jobs.php';

// Initialize the plugin
function hiresmart_init() {
    $hiresmart = new HireSmart_Core();
    $hiresmart->init();
}
add_action('plugins_loaded', 'hiresmart_init');

// Activation hook
register_activation_hook(__FILE__, 'hiresmart_activate');
function hiresmart_activate() {
    // Create custom database tables
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    
    // User profiles table
    $table_profiles = $wpdb->prefix . 'hiresmart_profiles';
    $sql_profiles = "CREATE TABLE IF NOT EXISTS $table_profiles (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        account_type varchar(20) NOT NULL,
        subscription_tier varchar(20) NOT NULL,
        linkedin_url varchar(255),
        github_url varchar(255),
        behance_url varchar(255),
        canva_url varchar(255),
        portfolio_url varchar(255),
        iq_score int(3),
        eq_score int(3),
        sq_score int(3),
        profile_data longtext,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY user_id (user_id)
    ) $charset_collate;";
    
    // Subscriptions table
    $table_subscriptions = $wpdb->prefix . 'hiresmart_subscriptions';
    $sql_subscriptions = "CREATE TABLE IF NOT EXISTS $table_subscriptions (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        subscription_tier varchar(20) NOT NULL,
        status varchar(20) NOT NULL,
        amount decimal(10,2),
        payment_method varchar(50),
        stripe_subscription_id varchar(255),
        start_date datetime,
        end_date datetime,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY user_id (user_id)
    ) $charset_collate;";
    
    // Payment methods table
    $table_payments = $wpdb->prefix . 'hiresmart_payment_methods';
    $sql_payments = "CREATE TABLE IF NOT EXISTS $table_payments (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        payment_type varchar(50) NOT NULL,
        card_last4 varchar(4),
        card_brand varchar(20),
        stripe_payment_method_id varchar(255),
        is_default tinyint(1) DEFAULT 0,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY user_id (user_id)
    ) $charset_collate;";
    
    // Jobs table
    $table_jobs = $wpdb->prefix . 'hiresmart_jobs';
    $sql_jobs = "CREATE TABLE IF NOT EXISTS $table_jobs (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        employer_id bigint(20) NOT NULL,
        title varchar(255) NOT NULL,
        description longtext NOT NULL,
        requirements longtext,
        location varchar(255),
        salary_min decimal(10,2),
        salary_max decimal(10,2),
        job_type varchar(50),
        experience_level varchar(50),
        skills longtext,
        status varchar(20) DEFAULT 'active',
        coins_used int(11) DEFAULT 0,
        views int(11) DEFAULT 0,
        applications_count int(11) DEFAULT 0,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        expires_at datetime,
        PRIMARY KEY (id),
        KEY employer_id (employer_id),
        KEY status (status)
    ) $charset_collate;";
    
    // Job applications table
    $table_applications = $wpdb->prefix . 'hiresmart_applications';
    $sql_applications = "CREATE TABLE IF NOT EXISTS $table_applications (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        job_id bigint(20) NOT NULL,
        candidate_id bigint(20) NOT NULL,
        cover_letter longtext,
        resume_url varchar(255),
        status varchar(20) DEFAULT 'pending',
        applied_at datetime DEFAULT CURRENT_TIMESTAMP,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY job_id (job_id),
        KEY candidate_id (candidate_id),
        KEY status (status)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql_profiles);
    dbDelta($sql_subscriptions);
    dbDelta($sql_payments);
    dbDelta($sql_jobs);
    dbDelta($sql_applications);
    
    // Create pages
    hiresmart_create_pages();
    
    // Flush rewrite rules
    flush_rewrite_rules();
}

// Create necessary pages
function hiresmart_create_pages() {
    $pages = array(
        'dashboard' => array(
            'title' => 'Dashboard',
            'content' => '[hiresmart_dashboard]'
        ),
        'profile' => array(
            'title' => 'Profile',
            'content' => '[hiresmart_profile]'
        ),
        'billing' => array(
            'title' => 'Billing',
            'content' => '[hiresmart_billing]'
        ),
        'integrations' => array(
            'title' => 'Integrations',
            'content' => '[hiresmart_integrations]'
        ),
        'login' => array(
            'title' => 'Login',
            'content' => '[hiresmart_login]'
        ),
        'register' => array(
            'title' => 'Create Account',
            'content' => '[hiresmart_register]'
        ),
        'post-job' => array(
            'title' => 'Post a Job',
            'content' => '[hiresmart_post_job]'
        ),
        'jobs' => array(
            'title' => 'Browse Jobs',
            'content' => '[hiresmart_job_listings]'
        ),
        'candidates' => array(
            'title' => 'Browse Candidates',
            'content' => '[hiresmart_candidates]'
        ),
        'employers-agencies' => array(
            'title' => 'Employers & Agencies',
            'content' => '[hiresmart_employers_agencies]'
        )
    );
    
    foreach ($pages as $slug => $page_data) {
        $page = get_page_by_path($slug);
        if (!$page) {
            wp_insert_post(array(
                'post_title' => $page_data['title'],
                'post_content' => $page_data['content'],
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_name' => $slug
            ));
        }
    }
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'hiresmart_deactivate');
function hiresmart_deactivate() {
    flush_rewrite_rules();
}
