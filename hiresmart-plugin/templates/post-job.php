<?php
/**
 * Post Job Template
 * 
 * Template for posting new jobs
 */

if (!defined('ABSPATH')) {
    exit;
}

$user_manager = new HireSmart_User();
$profile = $user_manager->get_profile(get_current_user_id());
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="hiresmart-post-job-container">
    <div class="post-job-header">
        <h1><i class="fas fa-briefcase"></i> Post a New Job</h1>
        <p class="subtitle">Fill in the details below to attract the best candidates</p>
    </div>
    
    <form id="hiresmart-post-job-form" class="hiresmart-form">
        <?php wp_nonce_field('hiresmart_nonce', 'hiresmart_nonce'); ?>
        
        <div class="form-section">
            <h2><i class="fas fa-info-circle"></i> Job Information</h2>
            
            <div class="form-group">
                <label for="job_title"><i class="fas fa-heading"></i> Job Title *</label>
                <input type="text" id="job_title" name="title" required placeholder="e.g., Senior Software Engineer">
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="job_type"><i class="fas fa-clock"></i> Job Type *</label>
                    <select id="job_type" name="job_type" required>
                        <option value="">Select job type</option>
                        <option value="full-time">Full-time</option>
                        <option value="part-time">Part-time</option>
                        <option value="contract">Contract</option>
                        <option value="freelance">Freelance</option>
                        <option value="internship">Internship</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="experience_level"><i class="fas fa-layer-group"></i> Experience Level *</label>
                    <select id="experience_level" name="experience_level" required>
                        <option value="">Select experience level</option>
                        <option value="entry">Entry Level</option>
                        <option value="mid">Mid Level</option>
                        <option value="senior">Senior Level</option>
                        <option value="lead">Lead/Principal</option>
                        <option value="executive">Executive</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="location"><i class="fas fa-map-marker-alt"></i> Location *</label>
                <input type="text" id="location" name="location" required placeholder="e.g., San Francisco, CA or Remote">
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="salary_min"><i class="fas fa-dollar-sign"></i> Minimum Salary</label>
                    <input type="number" id="salary_min" name="salary_min" placeholder="50000" step="1000">
                </div>
                
                <div class="form-group">
                    <label for="salary_max"><i class="fas fa-dollar-sign"></i> Maximum Salary</label>
                    <input type="number" id="salary_max" name="salary_max" placeholder="100000" step="1000">
                </div>
            </div>
        </div>
        
        <div class="form-section">
            <h2><i class="fas fa-file-alt"></i> Job Description</h2>
            
            <div class="form-group">
                <label for="description"><i class="fas fa-align-left"></i> Description *</label>
                <textarea id="description" name="description" required rows="8" placeholder="Describe the role, responsibilities, and what makes this position exciting..."></textarea>
                <small>Provide a compelling description of the role and your company</small>
            </div>
            
            <div class="form-group">
                <label for="requirements"><i class="fas fa-tasks"></i> Requirements *</label>
                <textarea id="requirements" name="requirements" required rows="6" placeholder="• Bachelor's degree in Computer Science&#10;• 3+ years of experience with...&#10;• Strong knowledge of..."></textarea>
                <small>List the key requirements and qualifications</small>
            </div>
            
            <div class="form-group">
                <label for="skills"><i class="fas fa-code"></i> Required Skills *</label>
                <input type="text" id="skills" name="skills" required placeholder="e.g., Python, JavaScript, React, Node.js">
                <small>Separate skills with commas</small>
            </div>
        </div>
        
        <div class="form-section">
            <h2><i class="fas fa-coins"></i> Posting Details</h2>
            
            <div class="posting-info">
                <div class="info-card">
                    <i class="fas fa-calendar-alt"></i>
                    <div>
                        <strong>Posting Duration</strong>
                        <p>Your job will be active for 30 days</p>
                    </div>
                </div>
                
                <div class="info-card">
                    <i class="fas fa-coins"></i>
                    <div>
                        <strong>AI Coins Required</strong>
                        <p>1 AI Coin per job posting</p>
                    </div>
                </div>
                
                <div class="info-card">
                    <i class="fas fa-eye"></i>
                    <div>
                        <strong>Visibility</strong>
                        <p>Visible to all job seekers immediately</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="button" class="btn-secondary" onclick="window.history.back()">
                <i class="fas fa-arrow-left"></i> Cancel
            </button>
            <button type="submit" class="btn-primary">
                <i class="fas fa-paper-plane"></i> Post Job
            </button>
        </div>
    </form>
</div>

<style>
.hiresmart-post-job-container {
    max-width: 900px;
    margin: 40px auto;
    padding: 0 20px;
}

.post-job-header {
    text-align: center;
    margin-bottom: 40px;
}

.post-job-header h1 {
    font-size: 32px;
    color: #1f2937;
    margin-bottom: 10px;
}

.post-job-header .subtitle {
    font-size: 16px;
    color: #6b7280;
}

.hiresmart-form {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    padding: 40px;
}

.form-section {
    margin-bottom: 40px;
    padding-bottom: 40px;
    border-bottom: 2px solid #f3f4f6;
}

.form-section:last-of-type {
    border-bottom: none;
}

.form-section h2 {
    font-size: 24px;
    color: #1f2937;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-section h2 i {
    color: #2563eb;
}

.form-group {
    margin-bottom: 24px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #1f2937;
    font-size: 14px;
}

.form-group label i {
    margin-right: 6px;
    color: #6b7280;
}

.form-group input[type="text"],
.form-group input[type="number"],
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    font-size: 16px;
    transition: border-color 0.3s;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #2563eb;
}

.form-group small {
    display: block;
    margin-top: 6px;
    font-size: 13px;
    color: #6b7280;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.posting-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.info-card {
    display: flex;
    align-items: start;
    gap: 15px;
    padding: 20px;
    background: #f9fafb;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.info-card i {
    font-size: 28px;
    color: #2563eb;
    margin-top: 4px;
}

.info-card strong {
    display: block;
    color: #1f2937;
    margin-bottom: 4px;
}

.info-card p {
    color: #6b7280;
    font-size: 14px;
    margin: 0;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    margin-top: 30px;
}

.btn-primary,
.btn-secondary {
    padding: 14px 30px;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    border: none;
    font-size: 16px;
}

.btn-primary {
    background: #2563eb;
    color: white;
}

.btn-primary:hover {
    background: #1e40af;
}

.btn-secondary {
    background: white;
    color: #2563eb;
    border: 2px solid #2563eb;
}

.btn-secondary:hover {
    background: #2563eb;
    color: white;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .hiresmart-form {
        padding: 24px;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    $('#hiresmart-post-job-form').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $submitBtn = $form.find('button[type="submit"]');
        const originalText = $submitBtn.html();
        
        // Disable button and show loading
        $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Posting...');
        
        $.ajax({
            url: hiresmart_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'hiresmart_post_job',
                nonce: hiresmart_ajax.nonce,
                title: $('input[name="title"]').val(),
                description: $('textarea[name="description"]').val(),
                requirements: $('textarea[name="requirements"]').val(),
                location: $('input[name="location"]').val(),
                salary_min: $('input[name="salary_min"]').val(),
                salary_max: $('input[name="salary_max"]').val(),
                job_type: $('select[name="job_type"]').val(),
                experience_level: $('select[name="experience_level"]').val(),
                skills: $('input[name="skills"]').val()
            },
            success: function(response) {
                if (response.success) {
                    alert('✓ ' + response.data.message);
                    window.location.href = '<?php echo site_url('/jobs'); ?>';
                } else {
                    alert('Error: ' + response.data.message);
                    $submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
                $submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script>
