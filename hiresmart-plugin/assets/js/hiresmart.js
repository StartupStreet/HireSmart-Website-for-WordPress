/**
 * HireSmart Plugin JavaScript
 */

(function($) {
    'use strict';
    
    // Toast notification system
    function showToast(message, type = 'success') {
        var bgColor = type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6';
        var toast = $('<div>')
            .css({
                position: 'fixed',
                top: '20px',
                right: '20px',
                background: bgColor,
                color: 'white',
                padding: '16px 24px',
                borderRadius: '8px',
                boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
                zIndex: 10000,
                maxWidth: '400px',
                animation: 'slideIn 0.3s ease'
            })
            .text(message);
        
        $('body').append(toast);
        
        setTimeout(function() {
            toast.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }
    
    // Loading overlay
    function showLoading(message = 'Processing...') {
        var loadingHTML = `
            <div id="loading-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 9999;">
                <div style="background: white; padding: 30px; border-radius: 12px; text-align: center;">
                    <div style="border: 4px solid #f3f4f6; border-top: 4px solid #2563eb; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto;"></div>
                    <p style="margin: 16px 0 0 0; color: #1f2937;">${message}</p>
                </div>
            </div>
        `;
        
        if (!$('#loading-overlay').length) {
            $('body').append(loadingHTML);
        }
    }
    
    function hideLoading() {
        $('#loading-overlay').remove();
    }
    
    // Social login handlers
    $('.btn-google, .btn-linkedin, .btn-github').on('click', function() {
        var provider = $(this).hasClass('btn-google') ? 'Google' : 
                      $(this).hasClass('btn-linkedin') ? 'LinkedIn' : 'GitHub';
        
        showToast('Initiating ' + provider + ' OAuth...', 'info');
        
        // In production, open OAuth window
        showLoading('Connecting to ' + provider + '...');
        
        setTimeout(function() {
            hideLoading();
            showToast('Social login with ' + provider + ' would be initiated here. In production, integrate with OAuth providers.', 'info');
        }, 1500);
    });
    
    // AI Assessment
    window.startAIAssessment = function() {
        var proceed = confirm('Start AI Assessment?\n\nThis will assess your IQ, EQ, and SQ through a series of questions.');
        
        if (proceed) {
            showLoading('Preparing assessment...');
            
            // Mock assessment
            var mockData = {
                logical_reasoning: Math.floor(Math.random() * 10) + 1,
                problem_solving: Math.floor(Math.random() * 10) + 1,
                emotional_awareness: Math.floor(Math.random() * 10) + 1,
                empathy: Math.floor(Math.random() * 10) + 1,
                communication: Math.floor(Math.random() * 10) + 1,
                teamwork: Math.floor(Math.random() * 10) + 1
            };
            
            $.ajax({
                url: hiresmart_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'hiresmart_ai_assessment',
                    nonce: hiresmart_ajax.nonce,
                    ...mockData
                },
                success: function(response) {
                    hideLoading();
                    if (response.success) {
                        showToast('Assessment completed successfully!', 'success');
                        alert('Assessment Complete!\n\nIQ: ' + response.scores.iq + 
                              '\nEQ: ' + response.scores.eq + 
                              '\nSQ: ' + response.scores.sq);
                        location.reload();
                    } else {
                        showToast('Assessment failed. Please try again.', 'error');
                    }
                },
                error: function() {
                    hideLoading();
                    showToast('Network error. Please try again.', 'error');
                }
            });
        }
    };
    
    // Payment method handlers
    $(document).on('click', '.set-default', function() {
        var methodId = $(this).data('id');
        
        showLoading('Updating payment method...');
        
        $.ajax({
            url: hiresmart_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'hiresmart_set_default_payment',
                nonce: hiresmart_ajax.nonce,
                method_id: methodId
            },
            success: function(response) {
                hideLoading();
                if (response.success) {
                    showToast('Default payment method updated!', 'success');
                    location.reload();
                } else {
                    showToast('Failed to update payment method.', 'error');
                }
            },
            error: function() {
                hideLoading();
                showToast('Network error. Please try again.', 'error');
            }
        });
    });
    
    $(document).on('click', '.remove-method', function() {
        if (!confirm('Remove this payment method?')) {
            return;
        }
        
        var methodId = $(this).data('id');
        
        showLoading('Removing payment method...');
        
        $.ajax({
            url: hiresmart_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'hiresmart_remove_payment',
                nonce: hiresmart_ajax.nonce,
                method_id: methodId
            },
            success: function(response) {
                hideLoading();
                if (response.success) {
                    showToast('Payment method removed!', 'success');
                    location.reload();
                } else {
                    showToast('Failed to remove payment method.', 'error');
                }
            },
            error: function() {
                hideLoading();
                showToast('Network error. Please try again.', 'error');
            }
        });
    });
    
    // Form validation with better feedback
    $('form').on('submit', function(e) {
        var form = $(this);
        var requiredFields = form.find('[required]');
        var valid = true;
        
        requiredFields.each(function() {
            if (!$(this).val()) {
                valid = false;
                $(this).css('border-color', '#dc2626');
                
                // Add error message
                if (!$(this).next('.error-message').length) {
                    $(this).after('<span class="error-message" style="color: #dc2626; font-size: 14px; margin-top: 4px; display: block;">This field is required</span>');
                }
            } else {
                $(this).css('border-color', '#e5e7eb');
                $(this).next('.error-message').remove();
            }
        });
        
        if (!valid) {
            e.preventDefault();
            showToast('Please fill in all required fields', 'error');
        }
    });
    
    // Remove error messages on input
    $('input[required], select[required]').on('input change', function() {
        if ($(this).val()) {
            $(this).css('border-color', '#e5e7eb');
            $(this).next('.error-message').remove();
        }
    });
    
    // Subscription tier selection
    $('.select-tier').on('click', function() {
        var tier = $(this).data('tier');
        $('input[name="subscription_tier"][value="' + tier + '"]').prop('checked', true).trigger('change');
    });
    
    // Add CSS animation for toast
    if (!$('#toast-animation').length) {
        $('head').append(`
            <style id="toast-animation">
                @keyframes slideIn {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                @keyframes spin {
                    to {
                        transform: rotate(360deg);
                    }
                }
            </style>
        `);
    }
    
})(jQuery);
