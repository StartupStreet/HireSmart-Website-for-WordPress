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
$is_logged_in = is_user_logged_in();
$show_limit = !$is_logged_in ? 5 : count($jobs);
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
            <?php foreach ($jobs as $index => $job): 
                $is_blurred = !$is_logged_in && $index >= $show_limit;
                $card_class = $is_blurred ? 'job-card blurred-card' : 'job-card';
            ?>
                <div class="<?php echo $card_class; ?>" data-job-id="<?php echo $job->id; ?>">
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
                    
                    <div class="job-footer-meta">
                        <div class="job-posted">
                            Posted <?php echo human_time_diff(strtotime($job->created_at), current_time('timestamp')); ?> ago
                        </div>
                        
                        <?php 
                        $days_until_expiry = ceil((strtotime($job->expires_at) - time()) / 86400);
                        $expiry_class = $days_until_expiry <= 3 ? 'expiry-urgent' : 'expiry-normal';
                        ?>
                        <div class="job-expiry <?php echo $expiry_class; ?>">
                            <i class="fas fa-clock"></i>
                            Expires in <?php echo $days_until_expiry; ?> day<?php echo $days_until_expiry != 1 ? 's' : ''; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <?php if (!$is_logged_in && count($jobs) > 5): ?>
                <div class="access-gate">
                    <div class="gate-content">
                        <i class="fas fa-lock"></i>
                        <h3>Want to see more jobs?</h3>
                        <p>Login or subscribe to view all <?php echo count($jobs); ?> available job openings</p>
                        <div class="gate-actions">
                            <a href="<?php echo wp_login_url(); ?>" class="btn-login">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                            <a href="<?php echo site_url('/register'); ?>" class="btn-signup">
                                <i class="fas fa-user-plus"></i> Sign Up Free
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
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
}

.job-footer-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 12px;
}

.job-expiry {
    font-size: 12px;
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}

.job-expiry.expiry-normal {
    background: #dbeafe;
    color: #1e40af;
}

.job-expiry.expiry-urgent {
    background: #fee2e2;
    color: #dc2626;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.blurred-card {
    filter: blur(5px);
    pointer-events: none;
    opacity: 0.5;
}

.access-gate {
    background: white;
    border-radius: 12px;
    padding: 60px 40px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    text-align: center;
    margin-top: -50px;
    position: relative;
    z-index: 10;
}

.gate-content i {
    font-size: 48px;
    color: #2563eb;
    margin-bottom: 20px;
}

.gate-content h3 {
    font-size: 28px;
    color: #1f2937;
    margin-bottom: 12px;
}

.gate-content p {
    font-size: 16px;
    color: #6b7280;
    margin-bottom: 24px;
}

.gate-actions {
    display: flex;
    justify-content: center;
    gap: 12px;
}

.btn-login,
.btn-signup {
    padding: 12px 24px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
}

.btn-login {
    background: white;
    color: #2563eb;
    border: 2px solid #2563eb;
}

.btn-login:hover {
    background: #2563eb;
    color: white;
}

.btn-signup {
    background: #2563eb;
    color: white;
}

.btn-signup:hover {
    background: #1e40af;
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

/* Job Modal Styles */
.job-modal {
    display: none;
    position: fixed;
    z-index: 10000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.6);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.job-modal.show {
    display: block;
    opacity: 1;
}

.job-modal-content {
    background-color: white;
    margin: 3% auto;
    padding: 0;
    border-radius: 12px;
    width: 90%;
    max-width: 900px;
    max-height: 85vh;
    overflow-y: auto;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    animation: modalSlideDown 0.3s ease;
}

@keyframes modalSlideDown {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modal-close {
    position: absolute;
    right: 20px;
    top: 20px;
    font-size: 32px;
    font-weight: bold;
    color: #6b7280;
    background: none;
    border: none;
    cursor: pointer;
    transition: color 0.3s;
    z-index: 1;
}

.modal-close:hover {
    color: #1f2937;
}

.modal-header {
    padding: 30px;
    border-bottom: 2px solid #f3f4f6;
    display: flex;
    justify-content: space-between;
    align-items: start;
    gap: 20px;
}

.modal-header h2 {
    font-size: 28px;
    color: #1f2937;
    margin: 0 0 10px 0;
}

.modal-company {
    font-size: 16px;
    color: #6b7280;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    margin: 0;
}

.modal-company:hover {
    color: #2563eb;
}

.btn-view-profile {
    padding: 10px 20px;
    background: #2563eb;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    white-space: nowrap;
    transition: background 0.3s;
}

.btn-view-profile:hover {
    background: #1e40af;
}

.modal-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    padding: 20px 30px;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}

.modal-salary {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    padding: 12px 20px;
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0 30px;
    margin-top: 20px;
    border-radius: 8px;
}

.modal-body {
    padding: 30px;
}

.job-detail-section {
    margin-bottom: 30px;
}

.job-detail-section:last-child {
    margin-bottom: 0;
}

.job-detail-section h3 {
    font-size: 20px;
    color: #1f2937;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.job-detail-section h3 i {
    color: #2563eb;
}

.job-detail-content {
    color: #4b5563;
    line-height: 1.8;
}

.employer-social {
    display: flex;
    gap: 12px;
    margin-top: 12px;
}

.social-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f3f4f6;
    color: #2563eb;
    font-size: 18px;
    transition: all 0.3s;
    text-decoration: none;
}

.social-link:hover {
    background: #2563eb;
    color: white;
    transform: translateY(-2px);
}

.modal-footer {
    padding: 20px 30px;
    border-top: 2px solid #f3f4f6;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    background: #f9fafb;
    border-radius: 0 0 12px 12px;
}

@media (max-width: 768px) {
    .job-modal-content {
        width: 95%;
        margin: 5% auto;
        max-height: 90vh;
    }
    
    .modal-header {
        flex-direction: column;
        padding: 20px;
    }
    
    .modal-header h2 {
        font-size: 22px;
    }
    
    .modal-meta {
        flex-direction: column;
        gap: 8px;
    }
    
    .modal-body {
        padding: 20px;
    }
    
    .modal-footer {
        flex-direction: column;
    }
    
    .btn-view-profile,
    .modal-footer button {
        width: 100%;
    }
}
</style>

<script>
function viewJob(jobId) {
    // Fetch job details via AJAX
    jQuery.ajax({
        url: hiresmart_ajax.ajax_url,
        type: 'POST',
        data: {
            action: 'hiresmart_get_job_details',
            nonce: hiresmart_ajax.nonce,
            job_id: jobId
        },
        success: function(response) {
            if (response.success) {
                showJobModal(response.data.job);
            } else {
                alert('Error: ' + response.data.message);
            }
        },
        error: function() {
            alert('Failed to load job details');
        }
    });
}

function showJobModal(job) {
    // Calculate days until expiry
    const expiryDate = new Date(job.expires_at);
    const today = new Date();
    const daysUntilExpiry = Math.ceil((expiryDate - today) / (1000 * 60 * 60 * 24));
    const expiryClass = daysUntilExpiry <= 3 ? 'expiry-urgent' : 'expiry-normal';
    
    // Build skills HTML
    let skillsHTML = '';
    if (job.skills) {
        const skills = job.skills.split(',');
        skillsHTML = skills.map(skill => `<span class="skill-tag">${skill.trim()}</span>`).join('');
    }
    
    // Build salary HTML
    let salaryHTML = '';
    if (job.salary_min || job.salary_max) {
        if (job.salary_min && job.salary_max) {
            salaryHTML = `$${parseInt(job.salary_min).toLocaleString()} - $${parseInt(job.salary_max).toLocaleString()}`;
        } else if (job.salary_min) {
            salaryHTML = `$${parseInt(job.salary_min).toLocaleString()}+`;
        } else {
            salaryHTML = `Up to $${parseInt(job.salary_max).toLocaleString()}`;
        }
    }
    
    // Build commission/referral HTML
    let commissionHTML = '';
    if (job.commission_type && job.commission_value) {
        const commissionText = job.commission_type === 'percentage' ? 
            `${job.commission_value}%` : `$${parseInt(job.commission_value).toLocaleString()}`;
        commissionHTML = `
            <div class="job-detail-section">
                <h3><i class="fas fa-percentage"></i> Commission</h3>
                <p><strong>${commissionText}</strong> commission for successful placement</p>
            </div>
        `;
    }
    
    let referralHTML = '';
    if (job.referral_bonus && job.referral_bonus > 0) {
        referralHTML = `
            <div class="job-detail-section">
                <h3><i class="fas fa-gift"></i> Referral Bonus</h3>
                <p><strong>$${parseInt(job.referral_bonus).toLocaleString()}</strong> for successful referrals</p>
            </div>
        `;
    }
    
    // Build employer profile links
    let socialLinks = '';
    if (job.linkedin_url) socialLinks += `<a href="${job.linkedin_url}" target="_blank" class="social-link"><i class="fab fa-linkedin"></i></a>`;
    if (job.github_url) socialLinks += `<a href="${job.github_url}" target="_blank" class="social-link"><i class="fab fa-github"></i></a>`;
    if (job.behance_url) socialLinks += `<a href="${job.behance_url}" target="_blank" class="social-link"><i class="fab fa-behance"></i></a>`;
    if (job.portfolio_url) socialLinks += `<a href="${job.portfolio_url}" target="_blank" class="social-link"><i class="fas fa-globe"></i></a>`;
    
    const modalHTML = `
        <div id="jobModal" class="job-modal">
            <div class="job-modal-content">
                <button class="modal-close" onclick="closeJobModal()">&times;</button>
                
                <div class="modal-header">
                    <div>
                        <h2>${job.title}</h2>
                        <p class="modal-company" ondblclick="openEmployerProfile(${job.employer_id})" title="Double-click to open profile in new tab">
                            <i class="fas fa-building"></i> ${job.employer_name}
                            ${job.account_type === 'agency' ? '<span class="badge agency-badge">Agency</span>' : ''}
                        </p>
                    </div>
                    <button class="btn-view-profile" onclick="openEmployerProfile(${job.employer_id})">
                        <i class="fas fa-user"></i> View Complete Profile
                    </button>
                </div>
                
                <div class="modal-meta">
                    <span class="meta-item"><i class="fas fa-map-marker-alt"></i> ${job.location}</span>
                    <span class="meta-item"><i class="fas fa-clock"></i> ${job.job_type}</span>
                    <span class="meta-item"><i class="fas fa-layer-group"></i> ${job.experience_level}</span>
                    <span class="meta-item job-expiry ${expiryClass}">
                        <i class="fas fa-clock"></i> Expires in ${daysUntilExpiry} day${daysUntilExpiry !== 1 ? 's' : ''}
                    </span>
                </div>
                
                ${salaryHTML ? `<div class="modal-salary"><i class="fas fa-dollar-sign"></i> ${salaryHTML}</div>` : ''}
                
                <div class="modal-body">
                    <div class="job-detail-section">
                        <h3><i class="fas fa-info-circle"></i> Job Description</h3>
                        <div class="job-detail-content">${job.description}</div>
                    </div>
                    
                    <div class="job-detail-section">
                        <h3><i class="fas fa-tasks"></i> Requirements</h3>
                        <div class="job-detail-content">${job.requirements}</div>
                    </div>
                    
                    ${skillsHTML ? `
                        <div class="job-detail-section">
                            <h3><i class="fas fa-code"></i> Required Skills</h3>
                            <div class="job-skills">${skillsHTML}</div>
                        </div>
                    ` : ''}
                    
                    ${commissionHTML}
                    ${referralHTML}
                    
                    <div class="job-detail-section">
                        <h3><i class="fas fa-building"></i> About the Employer</h3>
                        <p><strong>${job.employer_name}</strong> ${job.account_type === 'agency' ? '(Recruiting Agency)' : '(Direct Employer)'}</p>
                        ${job.employer_email ? `<p><i class="fas fa-envelope"></i> ${job.employer_email}</p>` : ''}
                        ${socialLinks ? `<div class="employer-social">${socialLinks}</div>` : ''}
                    </div>
                    
                    <div class="job-detail-section">
                        <h3><i class="fas fa-chart-line"></i> Job Statistics</h3>
                        <div class="job-stats">
                            <span><i class="fas fa-eye"></i> ${job.views} views</span>
                            <span><i class="fas fa-users"></i> ${job.applications_count} applicants</span>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button class="btn-secondary" onclick="closeJobModal()">Close</button>
                    <?php if (is_user_logged_in()): 
                        $user_manager = new HireSmart_User();
                        $profile = $user_manager->get_profile(get_current_user_id());
                        if ($profile && $profile->account_type === 'job_seeker'):
                    ?>
                        <button class="btn-apply" onclick="applyFromModal(${job.id})">
                            <i class="fas fa-paper-plane"></i> Apply Now
                        </button>
                    <?php endif; endif; ?>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('jobModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to page
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Show modal with animation
    setTimeout(() => {
        document.getElementById('jobModal').classList.add('show');
    }, 10);
}

function closeJobModal() {
    const modal = document.getElementById('jobModal');
    if (modal) {
        modal.classList.remove('show');
        setTimeout(() => modal.remove(), 300);
    }
}

function openEmployerProfile(employerId) {
    // Open employer profile in new tab
    window.open('<?php echo site_url("/employer-profile/"); ?>' + employerId, '_blank');
}

function applyFromModal(jobId) {
    closeJobModal();
    applyJob(jobId);
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('jobModal');
    if (modal && event.target === modal) {
        closeJobModal();
    }
});

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
