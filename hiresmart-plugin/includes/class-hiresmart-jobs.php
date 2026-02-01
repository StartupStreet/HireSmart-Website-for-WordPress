<?php
/**
 * HireSmart Jobs Class
 * 
 * Handles job posting, listing, and management
 */

class HireSmart_Jobs {
    
    public function __construct() {
        // Constructor
    }
    
    /**
     * Create a new job posting
     */
    public function create_job($data) {
        global $wpdb;
        $table = $wpdb->prefix . 'hiresmart_jobs';
        
        // Validate employer
        $user_manager = new HireSmart_User();
        $profile = $user_manager->get_profile($data['employer_id']);
        
        if (!$profile || !in_array($profile->account_type, ['employer', 'agency'])) {
            return array('success' => false, 'message' => 'Only employers and agencies can post jobs');
        }
        
        // Calculate expiration (30 days from now)
        $expires_at = date('Y-m-d H:i:s', strtotime('+30 days'));
        
        $insert_data = array(
            'employer_id' => $data['employer_id'],
            'title' => sanitize_text_field($data['title']),
            'description' => wp_kses_post($data['description']),
            'requirements' => wp_kses_post($data['requirements']),
            'location' => sanitize_text_field($data['location']),
            'salary_min' => floatval($data['salary_min']),
            'salary_max' => floatval($data['salary_max']),
            'job_type' => sanitize_text_field($data['job_type']),
            'experience_level' => sanitize_text_field($data['experience_level']),
            'skills' => sanitize_text_field($data['skills']),
            'status' => 'active',
            'coins_used' => intval($data['coins_used']) ?: 1,
            'expires_at' => $expires_at
        );
        
        $result = $wpdb->insert($table, $insert_data);
        
        if ($result) {
            return array(
                'success' => true,
                'message' => 'Job posted successfully!',
                'job_id' => $wpdb->insert_id
            );
        }
        
        return array('success' => false, 'message' => 'Failed to post job');
    }
    
    /**
     * Get all active jobs
     */
    public function get_all_jobs($args = array()) {
        global $wpdb;
        $table = $wpdb->prefix . 'hiresmart_jobs';
        
        $defaults = array(
            'status' => 'active',
            'limit' => 50,
            'offset' => 0,
            'orderby' => 'created_at',
            'order' => 'DESC'
        );
        
        $args = wp_parse_args($args, $defaults);
        
        $query = "SELECT j.*, u.display_name as employer_name, p.account_type 
                  FROM $table j
                  LEFT JOIN {$wpdb->users} u ON j.employer_id = u.ID
                  LEFT JOIN {$wpdb->prefix}hiresmart_profiles p ON j.employer_id = p.user_id
                  WHERE j.status = %s";
        
        if (!empty($args['job_type'])) {
            $query .= $wpdb->prepare(" AND j.job_type = %s", $args['job_type']);
        }
        
        if (!empty($args['location'])) {
            $query .= $wpdb->prepare(" AND j.location LIKE %s", '%' . $args['location'] . '%');
        }
        
        if (!empty($args['search'])) {
            $query .= $wpdb->prepare(" AND (j.title LIKE %s OR j.description LIKE %s OR j.skills LIKE %s)", 
                '%' . $args['search'] . '%', 
                '%' . $args['search'] . '%',
                '%' . $args['search'] . '%'
            );
        }
        
        $query .= " ORDER BY {$args['orderby']} {$args['order']} LIMIT %d OFFSET %d";
        
        $jobs = $wpdb->get_results(
            $wpdb->prepare($query, $args['status'], $args['limit'], $args['offset'])
        );
        
        return $jobs;
    }
    
    /**
     * Get job by ID
     */
    public function get_job($job_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'hiresmart_jobs';
        
        $job = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT j.*, u.display_name as employer_name, p.account_type 
                 FROM $table j
                 LEFT JOIN {$wpdb->users} u ON j.employer_id = u.ID
                 LEFT JOIN {$wpdb->prefix}hiresmart_profiles p ON j.employer_id = p.user_id
                 WHERE j.id = %d",
                $job_id
            )
        );
        
        if ($job) {
            // Increment view count
            $wpdb->query(
                $wpdb->prepare("UPDATE $table SET views = views + 1 WHERE id = %d", $job_id)
            );
        }
        
        return $job;
    }
    
    /**
     * Get jobs by employer
     */
    public function get_employer_jobs($employer_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'hiresmart_jobs';
        
        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table WHERE employer_id = %d ORDER BY created_at DESC",
                $employer_id
            )
        );
    }
    
    /**
     * Apply for a job
     */
    public function apply_for_job($data) {
        global $wpdb;
        $table = $wpdb->prefix . 'hiresmart_applications';
        
        // Check if already applied
        $existing = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT id FROM $table WHERE job_id = %d AND candidate_id = %d",
                $data['job_id'],
                $data['candidate_id']
            )
        );
        
        if ($existing) {
            return array('success' => false, 'message' => 'You have already applied for this job');
        }
        
        $insert_data = array(
            'job_id' => intval($data['job_id']),
            'candidate_id' => intval($data['candidate_id']),
            'cover_letter' => wp_kses_post($data['cover_letter']),
            'resume_url' => esc_url_raw($data['resume_url']),
            'status' => 'pending'
        );
        
        $result = $wpdb->insert($table, $insert_data);
        
        if ($result) {
            // Increment applications count
            $jobs_table = $wpdb->prefix . 'hiresmart_jobs';
            $wpdb->query(
                $wpdb->prepare(
                    "UPDATE $jobs_table SET applications_count = applications_count + 1 WHERE id = %d",
                    $data['job_id']
                )
            );
            
            return array('success' => true, 'message' => 'Application submitted successfully!');
        }
        
        return array('success' => false, 'message' => 'Failed to submit application');
    }
    
    /**
     * Get applications for a job
     */
    public function get_job_applications($job_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'hiresmart_applications';
        
        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT a.*, u.display_name as candidate_name, u.user_email as candidate_email,
                        p.iq_score, p.eq_score, p.sq_score
                 FROM $table a
                 LEFT JOIN {$wpdb->users} u ON a.candidate_id = u.ID
                 LEFT JOIN {$wpdb->prefix}hiresmart_profiles p ON a.candidate_id = p.user_id
                 WHERE a.job_id = %d
                 ORDER BY a.applied_at DESC",
                $job_id
            )
        );
    }
    
    /**
     * Get all candidates
     */
    public function get_all_candidates($args = array()) {
        global $wpdb;
        $profiles_table = $wpdb->prefix . 'hiresmart_profiles';
        
        $defaults = array(
            'limit' => 50,
            'offset' => 0
        );
        
        $args = wp_parse_args($args, $defaults);
        
        $query = "SELECT p.*, u.display_name, u.user_email, u.user_registered
                  FROM $profiles_table p
                  LEFT JOIN {$wpdb->users} u ON p.user_id = u.ID
                  WHERE p.account_type = 'job_seeker'";
        
        if (!empty($args['search'])) {
            $query .= $wpdb->prepare(" AND u.display_name LIKE %s", '%' . $args['search'] . '%');
        }
        
        $query .= " ORDER BY p.created_at DESC LIMIT %d OFFSET %d";
        
        return $wpdb->get_results(
            $wpdb->prepare($query, $args['limit'], $args['offset'])
        );
    }
    
    /**
     * Get all employers and agencies
     */
    public function get_employers_agencies($args = array()) {
        global $wpdb;
        $profiles_table = $wpdb->prefix . 'hiresmart_profiles';
        
        $defaults = array(
            'limit' => 50,
            'offset' => 0,
            'account_type' => null // null for both, 'employer' or 'agency' to filter
        );
        
        $args = wp_parse_args($args, $defaults);
        
        $query = "SELECT p.*, u.display_name, u.user_email, u.user_registered,
                         (SELECT COUNT(*) FROM {$wpdb->prefix}hiresmart_jobs WHERE employer_id = p.user_id AND status = 'active') as active_jobs
                  FROM $profiles_table p
                  LEFT JOIN {$wpdb->users} u ON p.user_id = u.ID
                  WHERE p.account_type IN ('employer', 'agency')";
        
        if ($args['account_type']) {
            $query .= $wpdb->prepare(" AND p.account_type = %s", $args['account_type']);
        }
        
        if (!empty($args['search'])) {
            $query .= $wpdb->prepare(" AND u.display_name LIKE %s", '%' . $args['search'] . '%');
        }
        
        $query .= " ORDER BY p.created_at DESC LIMIT %d OFFSET %d";
        
        return $wpdb->get_results(
            $wpdb->prepare($query, $args['limit'], $args['offset'])
        );
    }
}
