<?php
/**
 * Login Template
 */
?>
<div class="hiresmart-login-container">
    <div class="hiresmart-login-form">
        <h2>Sign In to HireSmart</h2>
        
        <form id="hiresmart-login-form" method="post">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-row">
                <label>
                    <input type="checkbox" name="remember" value="1">
                    Remember me
                </label>
                
                <a href="#" class="forgot-password">Forgot password?</a>
            </div>
            
            <button type="submit" class="btn-primary btn-large">Sign In</button>
            
            <div class="form-divider">
                <span>OR</span>
            </div>
            
            <div class="social-login-buttons">
                <button type="button" class="btn-social btn-google">
                    Continue with Google
                </button>
                <button type="button" class="btn-social btn-linkedin">
                    Continue with LinkedIn
                </button>
                <button type="button" class="btn-social btn-github">
                    Continue with GitHub
                </button>
            </div>
        </form>
        
        <p class="form-footer">
            Don't have an account? <a href="<?php echo site_url('/register'); ?>">Create Account</a>
        </p>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    $('#hiresmart-login-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        formData += '&action=hiresmart_login&nonce=' + hiresmart_ajax.nonce;
        
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
