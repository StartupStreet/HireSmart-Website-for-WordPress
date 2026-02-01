<?php
/**
 * Job Listings Template
 * 
 * Display all active job postings
 */

if (!defined('ABSPATH')) {
    exit;
}

$jobs_manager = new HireSmart_Jobs();
$jobs = $jobs_manager->get_all_jobs();
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="hiresmart-jobs-container">
    <div class="jobs-header">
        <div class="header-content">
            <h1><i class="fas fa-briefcase"></i> Browse Jobs</h1>
            <p class="subtitle">Explore thousands of opportunities from top companies</p>
        </div>
        
        <?php if (is_user_logged_in()): 
            $user_manager = new HireSmart_User();
            $profile = $user_manager->get_profile(get_current_user_id());
            if ($profile && in_array($profile->account_type, ['employer', 'agency'])):
        ?>
            <a href="<?php echo site_url('/post-job'); ?>" class="btn-primary">
                <i class="fas fa-plus"></i> Post a Job
            </a>
        <?php endif; endif; ?>
    </div>
    
    <div class="jobs-filters">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="job-search" placeholder="Search jobs by title, skills, or company...">
        </div>
        
        <div class="filter-row">
            <select id="filter-job-type">
                <option value="">All Job Types</option>
                <option value="full-time">Full-time</option>
                <option value="part-time">Part-time</option>
                <option value="contract">Contract</option>
                <option value="freelance">Freelance</option>
                <option value="internship">Internship</option>
            </select>
            
            <input type="text" id="filter-location" placeholder="Location...">
            
            <button class="btn-secondary" onclick="filterJobs()">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>
    </div>
    
    <div class="jobs-grid">
        <?php if (!empty($jobs)): ?>
            <?php foreach ($jobs as $job): ?>
                <div class="job-card" data-job-id="<?php echo $job->id; ?>">
                    <div class="job-header">
                        <div class="job-info">
                            <h3><?php echo esc_html($job->title); ?></h3>
                            <p class="company-name">
                                <i class="fas fa-building"></i> 
                                <?php echo esc_html($job->employer_name); ?>
                                <?php if ($job->account_type === 'agency'): ?>
                                    <span class="badge agency-badge">Agency</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    
                    <div class="job-meta">
                        <span class="meta-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo esc_html($job->location); ?>
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-clock"></i>
                            <?php echo esc_html($job->job_type); ?>
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-layer-group"></i>
                            <?php echo esc_html($job->experience_level); ?>
                        </span>
                    </div>
                    
                    <?php if ($job->salary_min || $job->salary_max): ?>
                        <div class="job-salary">
                            <i class="fas fa-dollar-sign"></i>
                            <?php 
                            if ($job->salary_min && $job->salary_max) {
                                echo '$' . number_format($job->salary_min) . ' - $' . number_format($job->salary_max);
                            } elseif ($job->salary_min) {
                                echo '$' . number_format($job->salary_min) . '+';
                            } else {
                                echo 'Up to $' . number_format($job->salary_max);
                            }
                            ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="job-description">
                        <?php echo wp_trim_words(wp_strip_all_tags($job->description), 30); ?>
                    </div>
                    
                    <?php if ($job->skills): ?>
                        <div class="job-skills">
                            <?php 
                            $skills = explode(',', $job->skills);
                            foreach (array_slice($skills, 0, 5) as $skill): 
                            ?>
                                <span class="skill-tag"><?php echo esc_html(trim($skill)); ?></span>
                            <?php endforeach; ?>
                            <?php if (count($skills) > 5): ?>
                                <span class="skill-tag more">+<?php echo count($skills) - 5; ?> more</span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="job-footer">
                        <div class="job-stats">
                            <span><i class="fas fa-eye"></i> <?php echo $job->views; ?> views</span>
                            <span><i class="fas fa-users"></i> <?php echo $job->applications_count; ?> applicants</span>
                        </div>
                        
                        <div class="job-actions">
                            <button class="btn-view-job" onclick="viewJob(<?php echo $job->id; ?>)">
                                <i class="fas fa-info-circle"></i> View Details
                            </button>
                            <?php if (is_user_logged_in()): 
                                $user_manager = new HireSmart_User();
                                $profile = $user_manager->get_profile(get_current_user_id());
                                if ($profile && $profile->account_type === 'job_seeker'):
                            ?>
                                <button class="btn-apply" onclick="applyJob(<?php echo $job->id; ?>)">
                                    <i class="fas fa-paper-plane"></i> Apply
                                </button>
                            <?php endif; endif; ?>
                        </div>
                    </div>
                    
                    <div class="job-posted">
                        Posted <?php echo human_time_diff(strtotime($job->created_at), current_time('timestamp')); ?> ago
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-jobs">
                <i class="fas fa-briefcase"></i>
                <h3>No Jobs Available</h3>
                <p>Check back soon for new opportunities!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.hiresmart-jobs-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.jobs-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 40px;
}

.jobs-header h1 {
    font-size: 32px;
    color: #1f2937;
    margin-bottom: 10px;
}

.jobs-header .subtitle {
    font-size: 16px;
    color: #6b7280;
}

.jobs-filters {
    background: white;
    padding: 24px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.search-box {
    position: relative;
    margin-bottom: 16px;
}

.search-box i {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #6b7280;
}

.search-box input {
    width: 100%;
    padding: 12px 12px 12px 45px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    font-size: 16px;
}

.filter-row {
    display: flex;
    gap: 12px;
}

.filter-row select,
.filter-row input {
    flex: 1;
    padding: 10px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
}

.jobs-grid {
    display: grid;
    gap: 24px;
}

.job-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
    position: relative;
}

.job-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.job-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 16px;
}

.job-header h3 {
    font-size: 22px;
    color: #1f2937;
    margin-bottom: 8px;
}

.company-name {
    color: #6b7280;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
}

.agency-badge {
    background: #fbbf24;
    color: white;
}

.job-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 16px;
}

.meta-item {
    color: #6b7280;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.job-salary {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    padding: 8px 12px;
    border-radius: 6px;
    display: inline-block;
    margin-bottom: 16px;
    font-weight: 600;
}

.job-description {
    color: #4b5563;
    line-height: 1.6;
    margin-bottom: 16px;
}

.job-skills {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 16px;
}

.skill-tag {
    background: #eff6ff;
    color: #2563eb;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}

.skill-tag.more {
    background: #f3f4f6;
    color: #6b7280;
}

.job-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 16px;
    border-top: 1px solid #e5e7eb;
}

.job-stats {
    display: flex;
    gap: 16px;
    font-size: 14px;
    color: #6b7280;
}

.job-actions {
    display: flex;
    gap: 8px;
}

.btn-view-job,
.btn-apply {
    padding: 8px 16px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-view-job {
    background: white;
    color: #2563eb;
    border: 2px solid #2563eb;
}

.btn-view-job:hover {
    background: #2563eb;
    color: white;
}

.btn-apply {
    background: #2563eb;
    color: white;
}

.btn-apply:hover {
    background: #1e40af;
}

.job-posted {
    font-size: 12px;
    color: #9ca3af;
    margin-top: 12px;
}

.btn-primary {
    background: #2563eb;
    color: white;
    padding: 12px 24px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary:hover {
    background: #1e40af;
}

.btn-secondary {
    background: white;
    color: #2563eb;
    padding: 10px 20px;
    border: 2px solid #2563eb;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
}

.no-jobs {
    text-align: center;
    padding: 60px 20px;
}

.no-jobs i {
    font-size: 64px;
    color: #e5e7eb;
    margin-bottom: 20px;
}

.no-jobs h3 {
    font-size: 24px;
    color: #1f2937;
    margin-bottom: 10px;
}

@media (max-width: 768px) {
    .jobs-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .filter-row {
        flex-direction: column;
    }
    
    .job-footer {
        flex-direction: column;
        gap: 12px;
        align-items: flex-start;
    }
}
</style>

<script>
function viewJob(jobId) {
    // For now, just show job details in an alert
    // In production, you would navigate to a job detail page
    alert('View job details (Job ID: ' + jobId + ')\n\nIn production, this would navigate to a detailed job page.');
}

function applyJob(jobId) {
    if (confirm('Apply for this job?')) {
        const coverLetter = prompt('Enter a brief cover letter (optional):');
        
        jQuery.ajax({
            url: hiresmart_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'hiresmart_apply_job',
                nonce: hiresmart_ajax.nonce,
                job_id: jobId,
                cover_letter: coverLetter || '',
                resume_url: ''
            },
            success: function(response) {
                if (response.success) {
                    alert('✓ ' + response.data.message);
                    location.reload();
                } else {
                    alert('Error: ' + response.data.message);
                }
            }
        });
    }
}

function filterJobs() {
    // Implement filtering logic
    alert('Filtering functionality - In production, this would filter the jobs based on selected criteria');
}
</script>
