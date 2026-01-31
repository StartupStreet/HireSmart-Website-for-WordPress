<?php
/**
 * HireSmart User Class
 * 
 * Handles user profile management and data
 */

class HireSmart_User {
    
    public function create_profile($user_id, $data) {
        global $wpdb;
        $table = $wpdb->prefix . 'hiresmart_profiles';
        
        $wpdb->insert($table, array(
            'user_id' => $user_id,
            'account_type' => $data['account_type'],
            'subscription_tier' => $data['subscription_tier'],
            'profile_data' => json_encode(array())
        ));
        
        return $wpdb->insert_id;
    }
    
    public function get_profile($user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'hiresmart_profiles';
        
        $profile = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE user_id = %d",
            $user_id
        ));
        
        if ($profile && $profile->profile_data) {
            $profile->profile_data = json_decode($profile->profile_data, true);
        }
        
        return $profile;
    }
    
    public function update_profile($user_id, $data) {
        global $wpdb;
        $table = $wpdb->prefix . 'hiresmart_profiles';
        
        $update_data = array();
        
        // Update integration URLs
        if (isset($data['linkedin_url'])) {
            $update_data['linkedin_url'] = esc_url_raw($data['linkedin_url']);
        }
        if (isset($data['github_url'])) {
            $update_data['github_url'] = esc_url_raw($data['github_url']);
        }
        if (isset($data['behance_url'])) {
            $update_data['behance_url'] = esc_url_raw($data['behance_url']);
        }
        if (isset($data['canva_url'])) {
            $update_data['canva_url'] = esc_url_raw($data['canva_url']);
        }
        if (isset($data['portfolio_url'])) {
            $update_data['portfolio_url'] = esc_url_raw($data['portfolio_url']);
        }
        
        // Update profile data
        if (isset($data['profile_data'])) {
            $update_data['profile_data'] = json_encode($data['profile_data']);
        }
        
        if (!empty($update_data)) {
            $wpdb->update(
                $table,
                $update_data,
                array('user_id' => $user_id)
            );
        }
        
        return array(
            'success' => true,
            'message' => 'Profile updated successfully'
        );
    }
    
    public function get_dashboard_data($user_id) {
        $profile = $this->get_profile($user_id);
        
        // Generate mock data based on account type
        $data = array(
            'profile' => $profile,
            'stats' => $this->generate_mock_stats($profile->account_type),
            'recent_activity' => $this->generate_mock_activity($profile->account_type)
        );
        
        return $data;
    }
    
    private function generate_mock_stats($account_type) {
        switch ($account_type) {
            case 'job_seeker':
                return array(
                    'applications_sent' => rand(5, 25),
                    'profile_views' => rand(50, 200),
                    'interviews_scheduled' => rand(1, 5),
                    'matches_found' => rand(10, 40)
                );
            
            case 'employer':
                return array(
                    'active_jobs' => rand(2, 10),
                    'total_applicants' => rand(50, 300),
                    'interviews_scheduled' => rand(5, 25),
                    'positions_filled' => rand(1, 8)
                );
            
            case 'agency':
                return array(
                    'active_clients' => rand(3, 15),
                    'total_placements' => rand(10, 50),
                    'candidates_managed' => rand(100, 500),
                    'revenue_generated' => '$' . number_format(rand(10000, 100000), 2)
                );
            
            default:
                return array();
        }
    }
    
    private function generate_mock_activity($account_type) {
        $activities = array();
        
        switch ($account_type) {
            case 'job_seeker':
                $activities = array(
                    array('type' => 'application', 'title' => 'Applied to Senior Developer at TechCorp', 'time' => '2 hours ago'),
                    array('type' => 'match', 'title' => 'New job match: Full Stack Engineer', 'time' => '5 hours ago'),
                    array('type' => 'view', 'title' => 'Your profile was viewed by StartupInc', 'time' => '1 day ago'),
                );
                break;
            
            case 'employer':
                $activities = array(
                    array('type' => 'applicant', 'title' => 'New applicant for Software Engineer position', 'time' => '1 hour ago'),
                    array('type' => 'interview', 'title' => 'Interview scheduled with John Doe', 'time' => '3 hours ago'),
                    array('type' => 'posting', 'title' => 'Job posting "Senior Designer" went live', 'time' => '1 day ago'),
                );
                break;
            
            case 'agency':
                $activities = array(
                    array('type' => 'placement', 'title' => 'Candidate placed at Client ABC', 'time' => '2 hours ago'),
                    array('type' => 'client', 'title' => 'New client inquiry received', 'time' => '4 hours ago'),
                    array('type' => 'candidate', 'title' => '5 new candidates added to talent pool', 'time' => '1 day ago'),
                );
                break;
        }
        
        return $activities;
    }
}
