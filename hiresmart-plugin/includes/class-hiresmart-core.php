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
        add_shortcode('hiresmart_post_job', array($this, 'render_post_job'));
        add_shortcode('hiresmart_job_listings', array($this, 'render_job_listings'));
        add_shortcode('hiresmart_candidates', array($this, 'render_candidates'));
        add_shortcode('hiresmart_employers_agencies', array($this, 'render_employers_agencies'));
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        
        // Handle redirects before headers are sent
        add_action('template_redirect', array($this, 'handle_redirects'));
        
        // AJAX actions
        add_action('wp_ajax_hiresmart_register', array($this, 'ajax_register'));
        add_action('wp_ajax_nopriv_hiresmart_register', array($this, 'ajax_register'));
        add_action('wp_ajax_hiresmart_login', array($this, 'ajax_login'));
        add_action('wp_ajax_nopriv_hiresmart_login', array($this, 'ajax_login'));
        add_action('wp_ajax_hiresmart_update_profile', array($this, 'ajax_update_profile'));
        add_action('wp_ajax_hiresmart_ai_assessment', array($this, 'ajax_ai_assessment'));
        add_action('wp_ajax_hiresmart_post_job', array($this, 'ajax_post_job'));
        add_action('wp_ajax_hiresmart_apply_job', array($this, 'ajax_apply_job'));
        add_action('wp_ajax_hiresmart_get_job_details', array($this, 'ajax_get_job_details'));
        add_action('wp_ajax_nopriv_hiresmart_get_job_details', array($this, 'ajax_get_job_details'));
        add_action('wp_ajax_hiresmart_get_employer_profile', array($this, 'ajax_get_employer_profile'));
        add_action('wp_ajax_nopriv_hiresmart_get_employer_profile', array($this, 'ajax_get_employer_profile'));
        
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
    
    public function handle_redirects() {
        // Check if user is logged in and trying to access login or register pages
        if (is_user_logged_in()) {
            if (is_page('login') || is_page('register')) {
                wp_redirect(site_url('/dashboard'));
                exit;
            }
        }
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
        // Redirect is handled in handle_redirects() hook
        ob_start();
        include HIRESMART_PLUGIN_DIR . 'templates/login.php';
        return ob_get_clean();
    }
    
    public function render_register() {
        // Redirect is handled in handle_redirects() hook
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
    
    // New render methods for job-related pages
    public function render_post_job() {
        if (!is_user_logged_in()) {
            return '<p>Please <a href="' . site_url('/login') . '">login</a> to post a job.</p>';
        }
        
        $user_manager = new HireSmart_User();
        $profile = $user_manager->get_profile(get_current_user_id());
        
        if (!$profile || !in_array($profile->account_type, ['employer', 'agency'])) {
            return '<p>Only employers and agencies can post jobs. <a href="' . site_url('/register') . '">Upgrade your account</a>.</p>';
        }
        
        ob_start();
        include HIRESMART_PLUGIN_DIR . 'templates/post-job.php';
        return ob_get_clean();
    }
    
    public function render_job_listings() {
        ob_start();
        include HIRESMART_PLUGIN_DIR . 'templates/job-listings.php';
        return ob_get_clean();
    }
    
    public function render_candidates() {
        if (!is_user_logged_in()) {
            return '<p>Please <a href="' . site_url('/login') . '">login</a> to view candidates.</p>';
        }
        
        $user_manager = new HireSmart_User();
        $profile = $user_manager->get_profile(get_current_user_id());
        
        if (!$profile || !in_array($profile->account_type, ['employer', 'agency'])) {
            return '<p>Only employers and agencies can view the candidate database.</p>';
        }
        
        ob_start();
        include HIRESMART_PLUGIN_DIR . 'templates/candidates.php';
        return ob_get_clean();
    }
    
    public function render_employers_agencies() {
        ob_start();
        include HIRESMART_PLUGIN_DIR . 'templates/employers-agencies.php';
        return ob_get_clean();
    }
    
    // AJAX handler for posting jobs
    public function ajax_post_job() {
        check_ajax_referer('hiresmart_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'You must be logged in'));
        }
        
        $jobs_manager = new HireSmart_Jobs();
        $data = array(
            'employer_id' => get_current_user_id(),
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'requirements' => $_POST['requirements'],
            'location' => $_POST['location'],
            'salary_min' => $_POST['salary_min'],
            'salary_max' => $_POST['salary_max'],
            'job_type' => $_POST['job_type'],
            'experience_level' => $_POST['experience_level'],
            'skills' => $_POST['skills'],
            'commission_type' => isset($_POST['commission_type']) ? $_POST['commission_type'] : null,
            'commission_value' => isset($_POST['commission_value']) ? $_POST['commission_value'] : null,
            'referral_bonus' => isset($_POST['referral_bonus']) ? $_POST['referral_bonus'] : null,
            'coins_used' => 1
        );
        
        $result = $jobs_manager->create_job($data);
        
        if ($result['success']) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }
    
    // AJAX handler for applying to jobs
    public function ajax_apply_job() {
        check_ajax_referer('hiresmart_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'You must be logged in'));
        }
        
        $jobs_manager = new HireSmart_Jobs();
        $data = array(
            'job_id' => $_POST['job_id'],
            'candidate_id' => get_current_user_id(),
            'cover_letter' => $_POST['cover_letter'],
            'resume_url' => $_POST['resume_url']
        );
        
        $result = $jobs_manager->apply_for_job($data);
        
        if ($result['success']) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }
    
    // AJAX handler for getting job details
    public function ajax_get_job_details() {
        check_ajax_referer('hiresmart_nonce', 'nonce');
        
        if (empty($_POST['job_id'])) {
            wp_send_json_error(array('message' => 'Job ID is required'));
        }
        
        $jobs_manager = new HireSmart_Jobs();
        $job = $jobs_manager->get_job(intval($_POST['job_id']));
        
        if ($job) {
            wp_send_json_success(array('job' => $job));
        } else {
            wp_send_json_error(array('message' => 'Job not found'));
        }
    }
    
    // AJAX handler for getting employer profile
    public function ajax_get_employer_profile() {
        check_ajax_referer('hiresmart_nonce', 'nonce');
        
        if (empty($_POST['employer_id'])) {
            wp_send_json_error(array('message' => 'Employer ID is required'));
        }
        
        $jobs_manager = new HireSmart_Jobs();
        $profile = $jobs_manager->get_employer_profile(intval($_POST['employer_id']));
        
        if ($profile) {
            wp_send_json_success(array('profile' => $profile));
        } else {
            wp_send_json_error(array('message' => 'Profile not found'));
        }
    }
}
