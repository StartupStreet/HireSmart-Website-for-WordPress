<?php
/**
 * Profile Template
 */

$user_manager = new HireSmart_User();
$user_id = get_current_user_id();
$user = wp_get_current_user();
$profile = $user_manager->get_profile($user_id);
?>

<div class="hiresmart-profile-container">
    <h1>My Profile</h1>
    
    <form id="hiresmart-profile-form" class="profile-form">
        <div class="profile-section">
            <h2>Personal Information</h2>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" value="<?php echo esc_attr($user->first_name); ?>">
                </div>
                
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" value="<?php echo esc_attr($user->last_name); ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" value="<?php echo esc_attr($user->user_email); ?>" disabled>
            </div>
            
            <div class="form-group">
                <label for="account_type">Account Type</label>
                <input type="text" value="<?php echo esc_attr(ucwords(str_replace('_', ' ', $profile->account_type))); ?>" disabled>
            </div>
        </div>
        
        <div class="profile-section">
            <h2>AI Profile Scores</h2>
            
            <div class="score-cards">
                <div class="score-card">
                    <h3>IQ Score</h3>
                    <div class="score-display">
                        <span class="score-value"><?php echo $profile->iq_score ?? 'N/A'; ?></span>
                        <div class="score-bar">
                            <div class="score-fill" style="width: <?php echo ($profile->iq_score ?? 0); ?>%"></div>
                        </div>
                    </div>
                </div>
                
                <div class="score-card">
                    <h3>EQ Score</h3>
                    <div class="score-display">
                        <span class="score-value"><?php echo $profile->eq_score ?? 'N/A'; ?></span>
                        <div class="score-bar">
                            <div class="score-fill" style="width: <?php echo ($profile->eq_score ?? 0); ?>%"></div>
                        </div>
                    </div>
                </div>
                
                <div class="score-card">
                    <h3>SQ Score</h3>
                    <div class="score-display">
                        <span class="score-value"><?php echo $profile->sq_score ?? 'N/A'; ?></span>
                        <div class="score-bar">
                            <div class="score-fill" style="width: <?php echo ($profile->sq_score ?? 0); ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <button type="button" class="btn-secondary" onclick="openAIAssessment()">Take AI Assessment</button>
        </div>
        
        <button type="submit" class="btn-primary">Save Changes</button>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    $('#hiresmart-profile-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        formData += '&action=hiresmart_update_profile&nonce=' + hiresmart_ajax.nonce;
        
        $.ajax({
            url: hiresmart_ajax.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    alert('Profile updated successfully!');
                } else {
                    alert(response.message);
                }
            }
        });
    });
});

function openAIAssessment() {
    // Create modal HTML
    var modalHTML = `
        <div id="ai-assessment-modal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 9999;">
            <div style="background: white; padding: 40px; border-radius: 12px; max-width: 600px; max-height: 90vh; overflow-y: auto;">
                <h2 style="margin-top: 0;">AI Profile Assessment</h2>
                <p>Answer these questions to calculate your IQ, EQ, and SQ scores.</p>
                
                <form id="ai-assessment-form">
                    <div class="form-group">
                        <label><strong>Logical Reasoning (1-10)</strong></label>
                        <p style="font-size: 14px; color: #6b7280;">How would you rate your logical problem-solving abilities?</p>
                        <input type="range" name="logical_reasoning" min="1" max="10" value="5" oninput="this.nextElementSibling.textContent = this.value" style="width: 100%;">
                        <span>5</span>
                    </div>
                    
                    <div class="form-group">
                        <label><strong>Problem Solving (1-10)</strong></label>
                        <p style="font-size: 14px; color: #6b7280;">How effective are you at finding solutions to complex problems?</p>
                        <input type="range" name="problem_solving" min="1" max="10" value="5" oninput="this.nextElementSibling.textContent = this.value" style="width: 100%;">
                        <span>5</span>
                    </div>
                    
                    <div class="form-group">
                        <label><strong>Emotional Awareness (1-10)</strong></label>
                        <p style="font-size: 14px; color: #6b7280;">How well do you understand and manage your own emotions?</p>
                        <input type="range" name="emotional_awareness" min="1" max="10" value="5" oninput="this.nextElementSibling.textContent = this.value" style="width: 100%;">
                        <span>5</span>
                    </div>
                    
                    <div class="form-group">
                        <label><strong>Empathy (1-10)</strong></label>
                        <p style="font-size: 14px; color: #6b7280;">How well do you understand and relate to others' feelings?</p>
                        <input type="range" name="empathy" min="1" max="10" value="5" oninput="this.nextElementSibling.textContent = this.value" style="width: 100%;">
                        <span>5</span>
                    </div>
                    
                    <div class="form-group">
                        <label><strong>Communication Skills (1-10)</strong></label>
                        <p style="font-size: 14px; color: #6b7280;">How effective are you at communicating with others?</p>
                        <input type="range" name="communication" min="1" max="10" value="5" oninput="this.nextElementSibling.textContent = this.value" style="width: 100%;">
                        <span>5</span>
                    </div>
                    
                    <div class="form-group">
                        <label><strong>Teamwork Ability (1-10)</strong></label>
                        <p style="font-size: 14px; color: #6b7280;">How well do you work in team environments?</p>
                        <input type="range" name="teamwork" min="1" max="10" value="5" oninput="this.nextElementSibling.textContent = this.value" style="width: 100%;">
                        <span>5</span>
                    </div>
                    
                    <div style="display: flex; gap: 12px; margin-top: 24px;">
                        <button type="submit" class="btn-primary" style="flex: 1;">Submit Assessment</button>
                        <button type="button" class="btn-secondary" onclick="closeAIAssessment()" style="flex: 1;">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    `;
    
    $('body').append(modalHTML);
    
    $('#ai-assessment-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        formData += '&action=hiresmart_ai_assessment&nonce=' + hiresmart_ajax.nonce;
        
        $.ajax({
            url: hiresmart_ajax.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    alert('Assessment Complete!\n\nIQ Score: ' + response.scores.iq + 
                          '\nEQ Score: ' + response.scores.eq + 
                          '\nSQ Score: ' + response.scores.sq +
                          '\n\nYour scores have been saved to your profile.');
                    closeAIAssessment();
                    location.reload();
                } else {
                    alert(response.message || 'Error submitting assessment');
                }
            }
        });
    });
}

function closeAIAssessment() {
    $('#ai-assessment-modal').remove();
}
</script>
