<?php
/**
 * Integrations Template
 */

$user_manager = new HireSmart_User();
$user_id = get_current_user_id();
$profile = $user_manager->get_profile($user_id);
?>

<div class="hiresmart-integrations-container">
    <h1>Integrations</h1>
    
    <p class="section-description">Connect your professional profiles and portfolio to enhance your HireSmart experience.</p>
    
    <form id="hiresmart-integrations-form" class="integrations-form">
        <div class="integration-section">
            <h2>Professional Networks</h2>
            
            <div class="integration-card">
                <div class="integration-icon">
                    <img src="<?php echo HIRESMART_PLUGIN_URL; ?>assets/images/linkedin.svg" alt="LinkedIn">
                </div>
                <div class="integration-info">
                    <h3>LinkedIn</h3>
                    <p>Connect your LinkedIn profile to import experience and connections</p>
                    <div class="form-group">
                        <input type="url" name="linkedin_url" placeholder="https://linkedin.com/in/username" value="<?php echo esc_attr($profile->linkedin_url); ?>">
                    </div>
                    <?php if ($profile->linkedin_url): ?>
                        <span class="status-badge connected">Connected</span>
                    <?php else: ?>
                        <span class="status-badge">Not Connected</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="integration-card">
                <div class="integration-icon">
                    <img src="<?php echo HIRESMART_PLUGIN_URL; ?>assets/images/github.svg" alt="GitHub">
                </div>
                <div class="integration-info">
                    <h3>GitHub</h3>
                    <p>Showcase your code repositories and contributions</p>
                    <div class="form-group">
                        <input type="url" name="github_url" placeholder="https://github.com/username" value="<?php echo esc_attr($profile->github_url); ?>">
                    </div>
                    <?php if ($profile->github_url): ?>
                        <span class="status-badge connected">Connected</span>
                    <?php else: ?>
                        <span class="status-badge">Not Connected</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="integration-section">
            <h2>Portfolio & Design</h2>
            
            <div class="integration-card">
                <div class="integration-icon">
                    <img src="<?php echo HIRESMART_PLUGIN_URL; ?>assets/images/behance.svg" alt="Behance">
                </div>
                <div class="integration-info">
                    <h3>Behance</h3>
                    <p>Display your creative portfolio and projects</p>
                    <div class="form-group">
                        <input type="url" name="behance_url" placeholder="https://behance.net/username" value="<?php echo esc_attr($profile->behance_url); ?>">
                    </div>
                    <?php if ($profile->behance_url): ?>
                        <span class="status-badge connected">Connected</span>
                    <?php else: ?>
                        <span class="status-badge">Not Connected</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="integration-card">
                <div class="integration-icon">
                    <img src="<?php echo HIRESMART_PLUGIN_URL; ?>assets/images/canva.svg" alt="Canva">
                </div>
                <div class="integration-info">
                    <h3>Canva</h3>
                    <p>Link your Canva designs and templates</p>
                    <div class="form-group">
                        <input type="url" name="canva_url" placeholder="https://canva.com/username" value="<?php echo esc_attr($profile->canva_url); ?>">
                    </div>
                    <?php if ($profile->canva_url): ?>
                        <span class="status-badge connected">Connected</span>
                    <?php else: ?>
                        <span class="status-badge">Not Connected</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="integration-section">
            <h2>Personal Portfolio</h2>
            
            <div class="integration-card">
                <div class="integration-icon">
                    🌐
                </div>
                <div class="integration-info">
                    <h3>Portfolio Website</h3>
                    <p>Add your personal portfolio or website URL</p>
                    <div class="form-group">
                        <input type="url" name="portfolio_url" placeholder="https://yourwebsite.com" value="<?php echo esc_attr($profile->portfolio_url); ?>">
                    </div>
                    <?php if ($profile->portfolio_url): ?>
                        <span class="status-badge connected">Connected</span>
                    <?php else: ?>
                        <span class="status-badge">Not Connected</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <button type="submit" class="btn-primary btn-large">Save Integrations</button>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    $('#hiresmart-integrations-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        formData += '&action=hiresmart_update_profile&nonce=' + hiresmart_ajax.nonce;
        
        $.ajax({
            url: hiresmart_ajax.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    alert('Integrations updated successfully!');
                    location.reload();
                } else {
                    alert(response.message);
                }
            }
        });
    });
});
</script>
