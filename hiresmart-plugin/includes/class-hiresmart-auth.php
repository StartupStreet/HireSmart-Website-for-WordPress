<?php
/**
 * HireSmart Authentication Class
 * 
 * Handles user registration, login, and social authentication
 */

class HireSmart_Auth {
    
    public function register_user($data) {
        // Validate required fields
        if (empty($data['email']) || empty($data['password']) || empty($data['account_type']) || empty($data['subscription_tier'])) {
            return array(
                'success' => false,
                'message' => 'All fields are required'
            );
        }
        
        // Validate email
        if (!is_email($data['email'])) {
            return array(
                'success' => false,
                'message' => 'Invalid email address'
            );
        }
        
        // Check if email exists
        if (email_exists($data['email'])) {
            return array(
                'success' => false,
                'message' => 'Email already registered'
            );
        }
        
        // Create WordPress user
        $user_id = wp_create_user(
            sanitize_email($data['email']),
            $data['password'],
            sanitize_email($data['email'])
        );
        
        if (is_wp_error($user_id)) {
            return array(
                'success' => false,
                'message' => $user_id->get_error_message()
            );
        }
        
        // Update user meta
        wp_update_user(array(
            'ID' => $user_id,
            'first_name' => sanitize_text_field($data['first_name'] ?? ''),
            'last_name' => sanitize_text_field($data['last_name'] ?? ''),
            'display_name' => sanitize_text_field($data['first_name'] ?? '') . ' ' . sanitize_text_field($data['last_name'] ?? '')
        ));
        
        // Create profile
        $user = new HireSmart_User();
        $user->create_profile($user_id, array(
            'account_type' => sanitize_text_field($data['account_type']),
            'subscription_tier' => sanitize_text_field($data['subscription_tier'])
        ));
        
        // Create subscription
        $subscription = new HireSmart_Subscription();
        $subscription_data = $subscription->create_subscription($user_id, $data['subscription_tier']);
        
        // Auto login
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);
        
        // Determine redirect URL
        $redirect_url = site_url('/dashboard');
        if ($data['subscription_tier'] !== 'free') {
            $redirect_url = site_url('/billing?payment_required=1');
        }
        
        return array(
            'success' => true,
            'message' => 'Registration successful',
            'redirect_url' => $redirect_url
        );
    }
    
    public function login_user($data) {
        // Validate fields
        if (empty($data['email']) || empty($data['password'])) {
            return array(
                'success' => false,
                'message' => 'Email and password are required'
            );
        }
        
        // Attempt login
        $creds = array(
            'user_login' => sanitize_email($data['email']),
            'user_password' => $data['password'],
            'remember' => isset($data['remember']) ? true : false
        );
        
        $user = wp_signon($creds, false);
        
        if (is_wp_error($user)) {
            return array(
                'success' => false,
                'message' => 'Invalid email or password'
            );
        }
        
        return array(
            'success' => true,
            'message' => 'Login successful',
            'redirect_url' => site_url('/dashboard')
        );
    }
    
    public function social_login($provider, $user_data) {
        // Check if user exists by email
        $user = get_user_by('email', $user_data['email']);
        
        if (!$user) {
            // Create new user
            $user_id = wp_create_user(
                $user_data['email'],
                wp_generate_password(),
                $user_data['email']
            );
            
            if (is_wp_error($user_id)) {
                return array(
                    'success' => false,
                    'message' => 'Could not create user'
                );
            }
            
            // Update user profile with social data
            wp_update_user(array(
                'ID' => $user_id,
                'first_name' => $user_data['first_name'] ?? '',
                'last_name' => $user_data['last_name'] ?? ''
            ));
            
            // Save social profile data
            update_user_meta($user_id, 'social_provider', $provider);
            update_user_meta($user_id, 'social_profile_url', $user_data['profile_url'] ?? '');
            
            // Redirect to account setup
            return array(
                'success' => true,
                'needs_setup' => true,
                'user_id' => $user_id,
                'redirect_url' => site_url('/register?social_setup=1&user_id=' . $user_id)
            );
        } else {
            // Login existing user
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID);
            
            return array(
                'success' => true,
                'redirect_url' => site_url('/dashboard')
            );
        }
    }
}
