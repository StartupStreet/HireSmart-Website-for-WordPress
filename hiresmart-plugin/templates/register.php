<?php
/**
 * Registration Template
 */
?>
<div class="hiresmart-register-container">
    <div class="hiresmart-register-form">
        <h2>Create Your HireSmart Account</h2>
        
        <?php if (isset($_GET['social_setup'])): ?>
            <div class="notice">Complete your account setup to continue</div>
        <?php endif; ?>
        
        <form id="hiresmart-register-form" method="post">
            <div class="form-row">
                <div class="form-group">
                    <label for="first_name">First Name *</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>
                
                <div class="form-group">
                    <label for="last_name">Last Name *</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" name="password" required minlength="8">
                <small>Minimum 8 characters</small>
            </div>
            
            <div class="form-group">
                <label for="account_type">Account Type *</label>
                <select id="account_type" name="account_type" required>
                    <option value="">Select account type</option>
                    <option value="job_seeker">Job Seeker</option>
                    <option value="employer">Employer</option>
                    <option value="agency">Recruitment Agency</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Subscription Plan *</label>
                <div class="subscription-options">
                    <label class="subscription-option">
                        <input type="radio" name="subscription_tier" value="free" checked>
                        <div class="option-content">
                            <h4>Free</h4>
                            <p class="price">$0/month</p>
                            <ul>
                                <li>Basic features</li>
                                <li>Limited applications</li>
                            </ul>
                        </div>
                    </label>
                    
                    <label class="subscription-option">
                        <input type="radio" name="subscription_tier" value="startup">
                        <div class="option-content">
                            <h4>Startup</h4>
                            <p class="price">$299/month</p>
                            <ul>
                                <li>Advanced AI matching</li>
                                <li>Unlimited applications</li>
                                <li>Priority support</li>
                            </ul>
                        </div>
                    </label>
                    
                    <label class="subscription-option">
                        <input type="radio" name="subscription_tier" value="enterprise">
                        <div class="option-content">
                            <h4>Enterprise</h4>
                            <p class="price">$999/month</p>
                            <ul>
                                <li>All Startup features</li>
                                <li>White-label solution</li>
                                <li>API access</li>
                            </ul>
                        </div>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="terms" required>
                    I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                </label>
            </div>
            
            <button type="submit" class="btn-primary btn-large">Create Account</button>
            
            <div class="form-divider">
                <span>OR</span>
            </div>
            
            <div class="social-login-buttons">
                <button type="button" class="btn-social btn-google">
                    <img src="<?php echo HIRESMART_PLUGIN_URL; ?>assets/images/google.svg" alt="Google">
                    Continue with Google
                </button>
                <button type="button" class="btn-social btn-linkedin">
                    <img src="<?php echo HIRESMART_PLUGIN_URL; ?>assets/images/linkedin.svg" alt="LinkedIn">
                    Continue with LinkedIn
                </button>
                <button type="button" class="btn-social btn-github">
                    <img src="<?php echo HIRESMART_PLUGIN_URL; ?>assets/images/github.svg" alt="GitHub">
                    Continue with GitHub
                </button>
            </div>
        </form>
        
        <p class="form-footer">
            Already have an account? <a href="<?php echo site_url('/login'); ?>">Sign In</a>
        </p>
    </div>
    
    <div class="register-sidebar">
        <h3>Why Choose HireSmart?</h3>
        <ul>
            <li>✓ AI-Powered Job Matching</li>
            <li>✓ Smart ATS Integration</li>
            <li>✓ Real-Time Analytics</li>
            <li>✓ Secure & Compliant</li>
        </ul>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    $('#hiresmart-register-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        formData += '&action=hiresmart_register&nonce=' + hiresmart_ajax.nonce;
        
        $.ajax({
            url: hiresmart_ajax.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    window.location.href = response.redirect_url;
                } else {
                    alert(response.message);
                }
            }
        });
    });
});
</script>
