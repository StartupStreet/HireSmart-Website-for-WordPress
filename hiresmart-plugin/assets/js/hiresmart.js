/**
 * HireSmart Plugin JavaScript
 */

(function($) {
    'use strict';
    
    // Social login handlers
    $('.btn-google, .btn-linkedin, .btn-github').on('click', function() {
        var provider = $(this).hasClass('btn-google') ? 'google' : 
                      $(this).hasClass('btn-linkedin') ? 'linkedin' : 'github';
        
        alert('Social login with ' + provider + ' would be initiated here.\nIn production, integrate with OAuth providers.');
    });
    
    // AI Assessment
    window.startAIAssessment = function() {
        // In production, open a modal with assessment questions
        var proceed = confirm('Start AI Assessment?\n\nThis will assess your IQ, EQ, and SQ through a series of questions.');
        
        if (proceed) {
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
                    if (response.success) {
                        alert('Assessment Complete!\n\nIQ: ' + response.scores.iq + 
                              '\nEQ: ' + response.scores.eq + 
                              '\nSQ: ' + response.scores.sq);
                        location.reload();
                    }
                }
            });
        }
    };
    
    // Payment method handlers
    $('.set-default').on('click', function() {
        var methodId = $(this).data('id');
        
        $.ajax({
            url: hiresmart_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'hiresmart_set_default_payment',
                nonce: hiresmart_ajax.nonce,
                method_id: methodId
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                }
            }
        });
    });
    
    $('.remove-method').on('click', function() {
        if (!confirm('Remove this payment method?')) {
            return;
        }
        
        var methodId = $(this).data('id');
        
        $.ajax({
            url: hiresmart_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'hiresmart_remove_payment',
                nonce: hiresmart_ajax.nonce,
                method_id: methodId
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                }
            }
        });
    });
    
    // Form validation
    $('form').on('submit', function(e) {
        var form = $(this);
        var requiredFields = form.find('[required]');
        var valid = true;
        
        requiredFields.each(function() {
            if (!$(this).val()) {
                valid = false;
                $(this).css('border-color', '#dc2626');
            } else {
                $(this).css('border-color', '#e5e7eb');
            }
        });
        
        if (!valid) {
            e.preventDefault();
            alert('Please fill in all required fields');
        }
    });
    
    // Subscription tier selection
    $('.select-tier').on('click', function() {
        var tier = $(this).data('tier');
        $('input[name="subscription_tier"][value="' + tier + '"]').prop('checked', true).trigger('change');
    });
    
})(jQuery);
