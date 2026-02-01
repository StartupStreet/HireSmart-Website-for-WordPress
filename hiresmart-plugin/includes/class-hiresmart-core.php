<?php
/**
 * HireSmart Core Class
 * 
 * Main plugin functionality and initialization
 */

class HireSmart_Core {
    
    public function __construct() {
        // Constructor
    }
    
    public function init() {
        // Register shortcodes
        add_shortcode('hiresmart_dashboard', array($this, 'render_dashboard'));
        add_shortcode('hiresmart_profile', array($this, 'render_profile'));
        add_shortcode('hiresmart_billing', array($this, 'render_billing'));
        add_shortcode('hiresmart_integrations', array($this, 'render_integrations'));
        add_shortcode('hiresmart_login', array($this, 'render_login'));
        add_shortcode('hiresmart_register', array($this, 'render_register'));
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        
        // AJAX actions
        add_action('wp_ajax_hiresmart_register', array($this, 'ajax_register'));
        add_action('wp_ajax_nopriv_hiresmart_register', array($this, 'ajax_register'));
        add_action('wp_ajax_hiresmart_login', array($this, 'ajax_login'));
        add_action('wp_ajax_nopriv_hiresmart_login', array($this, 'ajax_login'));
        add_action('wp_ajax_hiresmart_update_profile', array($this, 'ajax_update_profile'));
        add_action('wp_ajax_hiresmart_ai_assessment', array($this, 'ajax_ai_assessment'));
        
        // Add menu items
        add_action('wp_nav_menu_items', array($this, 'add_menu_items'), 10, 2);
    }
    
    public function enqueue_assets() {
        // Enqueue CSS
        wp_enqueue_style('hiresmart-main', HIRESMART_PLUGIN_URL . 'assets/css/hiresmart.css', array(), HIRESMART_VERSION);
        wp_enqueue_style('hiresmart-dashboard', HIRESMART_PLUGIN_URL . 'assets/css/dashboard.css', array(), HIRESMART_VERSION);
        
        // Enqueue JS
        wp_enqueue_script('hiresmart-main', HIRESMART_PLUGIN_URL . 'assets/js/hiresmart.js', array('jquery'), HIRESMART_VERSION, true);
        
        // Localize script
        wp_localize_script('hiresmart-main', 'hiresmart_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('hiresmart_nonce')
        ));
    }
    
    public function render_dashboard() {
        if (!is_user_logged_in()) {
            return '<p>Please <a href="' . site_url('/login') . '">login</a> to access your dashboard.</p>';
        }
        
        ob_start();
        include HIRESMART_PLUGIN_DIR . 'templates/dashboard.php';
        return ob_get_clean();
    }
    
    public function render_profile() {
        if (!is_user_logged_in()) {
            return '<p>Please <a href="' . site_url('/login') . '">login</a> to access your profile.</p>';
        }
        
        ob_start();
        include HIRESMART_PLUGIN_DIR . 'templates/profile.php';
        return ob_get_clean();
    }
    
    public function render_billing() {
        if (!is_user_logged_in()) {
            return '<p>Please <a href="' . site_url('/login') . '">login</a> to access billing.</p>';
        }
        
        ob_start();
        include HIRESMART_PLUGIN_DIR . 'templates/billing.php';
        return ob_get_clean();
    }
    
    public function render_integrations() {
        if (!is_user_logged_in()) {
            return '<p>Please <a href="' . site_url('/login') . '">login</a> to access integrations.</p>';
        }
        
        ob_start();
        include HIRESMART_PLUGIN_DIR . 'templates/integrations.php';
        return ob_get_clean();
    }
    
    public function render_login() {
        if (is_user_logged_in()) {
            wp_redirect(site_url('/dashboard'));
            exit;
        }
        
        ob_start();
        include HIRESMART_PLUGIN_DIR . 'templates/login.php';
        return ob_get_clean();
    }
    
    public function render_register() {
        if (is_user_logged_in()) {
            wp_redirect(site_url('/dashboard'));
            exit;
        }
        
        ob_start();
        include HIRESMART_PLUGIN_DIR . 'templates/register.php';
        return ob_get_clean();
    }
    
    public function ajax_register() {
        check_ajax_referer('hiresmart_nonce', 'nonce');
        
        $auth = new HireSmart_Auth();
        $result = $auth->register_user($_POST);
        
        wp_send_json($result);
    }
    
    public function ajax_login() {
        check_ajax_referer('hiresmart_nonce', 'nonce');
        
        $auth = new HireSmart_Auth();
        $result = $auth->login_user($_POST);
        
        wp_send_json($result);
    }
    
    public function ajax_update_profile() {
        check_ajax_referer('hiresmart_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'Not logged in'));
        }
        
        $user = new HireSmart_User();
        $result = $user->update_profile(get_current_user_id(), $_POST);
        
        wp_send_json($result);
    }
    
    public function ajax_ai_assessment() {
        check_ajax_referer('hiresmart_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'Not logged in'));
        }
        
        $ai = new HireSmart_AI_Profiling();
        $result = $ai->calculate_scores(get_current_user_id(), $_POST);
        
        wp_send_json($result);
    }
    
    public function add_menu_items($items, $args) {
        if (is_user_logged_in()) {
            $items .= '<li><a href="' . site_url('/dashboard') . '">Dashboard</a></li>';
            $items .= '<li><a href="' . wp_logout_url(home_url()) . '">Logout</a></li>';
        } else {
            $items .= '<li><a href="' . site_url('/login') . '">Login</a></li>';
            $items .= '<li><a href="' . site_url('/register') . '">Sign Up</a></li>';
        }
        return $items;
    }
}
