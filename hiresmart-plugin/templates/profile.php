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
    // Open AI assessment modal
    alert('AI Assessment will be opened in a modal');
}
</script>
