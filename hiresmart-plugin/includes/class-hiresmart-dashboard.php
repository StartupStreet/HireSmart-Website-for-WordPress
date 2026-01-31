<?php
/**
 * HireSmart Dashboard Class
 * 
 * Handles dashboard functionality and data display
 */

class HireSmart_Dashboard {
    
    public function get_dashboard_content($user_id) {
        $user_manager = new HireSmart_User();
        $profile = $user_manager->get_profile($user_id);
        
        if (!$profile) {
            return '<p>Profile not found. Please complete your registration.</p>';
        }
        
        $dashboard_data = $user_manager->get_dashboard_data($user_id);
        
        // Render dashboard based on account type
        switch ($profile->account_type) {
            case 'job_seeker':
                return $this->render_job_seeker_dashboard($dashboard_data);
            
            case 'employer':
                return $this->render_employer_dashboard($dashboard_data);
            
            case 'agency':
                return $this->render_agency_dashboard($dashboard_data);
            
            default:
                return '<p>Unknown account type</p>';
        }
    }
    
    private function render_job_seeker_dashboard($data) {
        ob_start();
        ?>
        <div class="hiresmart-dashboard job-seeker-dashboard">
            <h1>Job Seeker Dashboard</h1>
            
            <div class="dashboard-stats">
                <div class="stat-card">
                    <h3>Applications Sent</h3>
                    <div class="stat-value"><?php echo $data['stats']['applications_sent']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Profile Views</h3>
                    <div class="stat-value"><?php echo $data['stats']['profile_views']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Interviews</h3>
                    <div class="stat-value"><?php echo $data['stats']['interviews_scheduled']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Matches Found</h3>
                    <div class="stat-value"><?php echo $data['stats']['matches_found']; ?></div>
                </div>
            </div>
            
            <div class="dashboard-section">
                <h2>Recent Activity</h2>
                <div class="activity-list">
                    <?php foreach ($data['recent_activity'] as $activity): ?>
                        <div class="activity-item">
                            <span class="activity-type"><?php echo esc_html($activity['type']); ?></span>
                            <span class="activity-title"><?php echo esc_html($activity['title']); ?></span>
                            <span class="activity-time"><?php echo esc_html($activity['time']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="dashboard-section">
                <h2>AI Profile Insights</h2>
                <div class="ai-scores">
                    <div class="score-card">
                        <h4>IQ Score</h4>
                        <div class="score-value"><?php echo $data['profile']->iq_score ?? 'Not assessed'; ?></div>
                    </div>
                    <div class="score-card">
                        <h4>EQ Score</h4>
                        <div class="score-value"><?php echo $data['profile']->eq_score ?? 'Not assessed'; ?></div>
                    </div>
                    <div class="score-card">
                        <h4>SQ Score</h4>
                        <div class="score-value"><?php echo $data['profile']->sq_score ?? 'Not assessed'; ?></div>
                    </div>
                </div>
                <button class="btn-primary" onclick="startAIAssessment()">Take AI Assessment</button>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    private function render_employer_dashboard($data) {
        ob_start();
        ?>
        <div class="hiresmart-dashboard employer-dashboard">
            <h1>Employer Dashboard</h1>
            
            <div class="dashboard-stats">
                <div class="stat-card">
                    <h3>Active Jobs</h3>
                    <div class="stat-value"><?php echo $data['stats']['active_jobs']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Total Applicants</h3>
                    <div class="stat-value"><?php echo $data['stats']['total_applicants']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Interviews</h3>
                    <div class="stat-value"><?php echo $data['stats']['interviews_scheduled']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Positions Filled</h3>
                    <div class="stat-value"><?php echo $data['stats']['positions_filled']; ?></div>
                </div>
            </div>
            
            <div class="dashboard-section">
                <h2>Recent Activity</h2>
                <div class="activity-list">
                    <?php foreach ($data['recent_activity'] as $activity): ?>
                        <div class="activity-item">
                            <span class="activity-type"><?php echo esc_html($activity['type']); ?></span>
                            <span class="activity-title"><?php echo esc_html($activity['title']); ?></span>
                            <span class="activity-time"><?php echo esc_html($activity['time']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="dashboard-actions">
                <button class="btn-primary">Post New Job</button>
                <button class="btn-secondary">View Applicants</button>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    private function render_agency_dashboard($data) {
        ob_start();
        ?>
        <div class="hiresmart-dashboard agency-dashboard">
            <h1>Agency Dashboard</h1>
            
            <div class="dashboard-stats">
                <div class="stat-card">
                    <h3>Active Clients</h3>
                    <div class="stat-value"><?php echo $data['stats']['active_clients']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Total Placements</h3>
                    <div class="stat-value"><?php echo $data['stats']['total_placements']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Candidates</h3>
                    <div class="stat-value"><?php echo $data['stats']['candidates_managed']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Revenue</h3>
                    <div class="stat-value"><?php echo $data['stats']['revenue_generated']; ?></div>
                </div>
            </div>
            
            <div class="dashboard-section">
                <h2>Recent Activity</h2>
                <div class="activity-list">
                    <?php foreach ($data['recent_activity'] as $activity): ?>
                        <div class="activity-item">
                            <span class="activity-type"><?php echo esc_html($activity['type']); ?></span>
                            <span class="activity-title"><?php echo esc_html($activity['title']); ?></span>
                            <span class="activity-time"><?php echo esc_html($activity['time']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="dashboard-actions">
                <button class="btn-primary">Add Client</button>
                <button class="btn-secondary">Manage Candidates</button>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}
