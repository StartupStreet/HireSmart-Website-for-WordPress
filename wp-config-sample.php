<?php
/**
 * HireSmart Configuration for Two-Domain Setup
 * 
 * Add this to your wp-config.php file
 * Instructions: https://github.com/StartupStreet/HireSmart-Website-for-WordPress/blob/main/DEPLOYMENT_GUIDE.md
 */

// ===== MULTISITE CONFIGURATION =====
// Uncomment if using WordPress Multisite

/*
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', true);
define('DOMAIN_CURRENT_SITE', 'startupstreet.in');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);
*/

// ===== CROSS-DOMAIN SESSION SHARING =====
// Required for both multisite and separate installations

// Cookie domain (note the leading dot for subdomain sharing)
define('COOKIE_DOMAIN', '.startupstreet.in');
define('ADMIN_COOKIE_PATH', '/');
define('COOKIEPATH', '/');
define('SITECOOKIEPATH', '/');

// ===== DOMAIN CONFIGURATION =====
// Define your domains for easy reference in code

define('HIRESMART_LANDING_DOMAIN', 'https://hiresmart.startupstreet.in');
define('HIRESMART_APP_DOMAIN', 'https://app-hiresmart.startupstreet.in');

// ===== SECURITY KEYS =====
// Generate unique keys at: https://api.wordpress.org/secret-key/1.1/salt/
// IMPORTANT: Use the same keys on both domains for session sharing

/*
define('AUTH_KEY',         'put your unique phrase here');
define('SECURE_AUTH_KEY',  'put your unique phrase here');
define('LOGGED_IN_KEY',    'put your unique phrase here');
define('NONCE_KEY',        'put your unique phrase here');
define('AUTH_SALT',        'put your unique phrase here');
define('SECURE_AUTH_SALT', 'put your unique phrase here');
define('LOGGED_IN_SALT',   'put your unique phrase here');
define('NONCE_SALT',       'put your unique phrase here');
*/

// ===== SSL/HTTPS CONFIGURATION =====
// Force SSL for admin and logins

define('FORCE_SSL_ADMIN', true);
define('FORCE_SSL_LOGIN', true);

// If behind a proxy/load balancer
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}

// ===== CORS CONFIGURATION =====
// Allow cross-domain AJAX requests between your domains

if (!defined('CORS_ORIGIN')) {
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        $allowed_origins = [
            'https://hiresmart.startupstreet.in',
            'https://app-hiresmart.startupstreet.in'
        ];
        
        if (in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
            header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization, X-WP-Nonce');
        }
    }
}

// ===== DATABASE CONFIGURATION =====
// Configure your database settings

// For shared database (recommended)
/*
define('DB_NAME', 'hiresmart_db');
define('DB_USER', 'your_database_user');
define('DB_PASSWORD', 'your_database_password');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');
*/

// Different table prefixes for landing vs app (if using shared DB)
// Landing page: $table_prefix = 'wp_main_';
// App domain:   $table_prefix = 'wp_app_';

// ===== STRIPE PAYMENT CONFIGURATION =====
// Add your Stripe API keys

// Test keys (for development)
define('HIRESMART_STRIPE_TEST_PUBLIC_KEY', 'pk_test_your_key_here');
define('HIRESMART_STRIPE_TEST_SECRET_KEY', 'sk_test_your_key_here');

// Live keys (for production)
// define('HIRESMART_STRIPE_PUBLIC_KEY', 'pk_live_your_key_here');
// define('HIRESMART_STRIPE_SECRET_KEY', 'sk_live_your_key_here');

// ===== SOCIAL LOGIN OAUTH CONFIGURATION =====
// Add your OAuth app credentials

// Google OAuth
define('HIRESMART_GOOGLE_CLIENT_ID', 'your-google-client-id.apps.googleusercontent.com');
define('HIRESMART_GOOGLE_CLIENT_SECRET', 'your-google-client-secret');
define('HIRESMART_GOOGLE_REDIRECT_URI', 'https://app-hiresmart.startupstreet.in/oauth/google/callback');

// LinkedIn OAuth
define('HIRESMART_LINKEDIN_CLIENT_ID', 'your-linkedin-client-id');
define('HIRESMART_LINKEDIN_CLIENT_SECRET', 'your-linkedin-client-secret');
define('HIRESMART_LINKEDIN_REDIRECT_URI', 'https://app-hiresmart.startupstreet.in/oauth/linkedin/callback');

// GitHub OAuth
define('HIRESMART_GITHUB_CLIENT_ID', 'your-github-client-id');
define('HIRESMART_GITHUB_CLIENT_SECRET', 'your-github-client-secret');
define('HIRESMART_GITHUB_REDIRECT_URI', 'https://app-hiresmart.startupstreet.in/oauth/github/callback');

// ===== DEBUG CONFIGURATION =====
// Enable for development, disable for production

// Development
/*
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors', 0);
define('SCRIPT_DEBUG', true);
define('SAVEQUERIES', true);
*/

// Production (disable all debugging)
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);

// ===== PERFORMANCE OPTIMIZATION =====

// Increase memory limit
define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '512M');

// Disable file editing from admin
define('DISALLOW_FILE_EDIT', true);

// Enable caching
define('WP_CACHE', true);

// ===== AUTO-UPDATE CONFIGURATION =====

// Enable auto-updates for plugins and themes
define('WP_AUTO_UPDATE_CORE', true);
add_filter('auto_update_plugin', '__return_true');
add_filter('auto_update_theme', '__return_true');

// ===== ADDITIONAL SECURITY =====

// Disable XML-RPC (if not needed)
add_filter('xmlrpc_enabled', '__return_false');

// Limit login attempts (requires plugin)
// Install: Limit Login Attempts Reloaded

// ===== THAT'S ALL, STOP EDITING! =====
// The rest of the wp-config.php file should remain unchanged

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
