<?php
/**
 * Billing Template
 */

$user_id = get_current_user_id();
$subscription_manager = new HireSmart_Subscription();
$payment_manager = new HireSmart_Payment();

$subscription = $subscription_manager->get_subscription($user_id);
$payment_methods = $payment_manager->get_payment_methods($user_id);
$tier_info = $subscription_manager->get_tier($subscription->subscription_tier);
?>

<div class="hiresmart-billing-container">
    <h1>Billing & Subscription</h1>
    
    <?php if (isset($_GET['payment_required'])): ?>
        <div class="notice notice-info">
            <p>Please add a payment method to activate your subscription.</p>
        </div>
    <?php endif; ?>
    
    <div class="billing-section">
        <h2>Current Plan</h2>
        <div class="plan-card">
            <h3><?php echo esc_html($tier_info['name']); ?> Plan</h3>
            <div class="plan-price">$<?php echo number_format($tier_info['price'], 0); ?><span>/month</span></div>
            <p>Status: <strong><?php echo esc_html(ucfirst($subscription->status)); ?></strong></p>
            
            <?php if ($subscription->end_date): ?>
                <p>Renews: <?php echo date('F j, Y', strtotime($subscription->end_date)); ?></p>
            <?php endif; ?>
            
            <button class="btn-secondary">Change Plan</button>
        </div>
    </div>
    
    <div class="billing-section">
        <h2>Payment Methods</h2>
        
        <?php if (empty($payment_methods)): ?>
            <p>No payment methods on file.</p>
        <?php else: ?>
            <div class="payment-methods-list">
                <?php foreach ($payment_methods as $method): ?>
                    <div class="payment-method-card">
                        <div class="method-info">
                            <strong><?php echo esc_html(ucfirst($method->card_brand)); ?></strong>
                            ending in <?php echo esc_html($method->card_last4); ?>
                            <?php if ($method->is_default): ?>
                                <span class="badge">Default</span>
                            <?php endif; ?>
                        </div>
                        <div class="method-actions">
                            <?php if (!$method->is_default): ?>
                                <button class="btn-link set-default" data-id="<?php echo $method->id; ?>">Set as Default</button>
                            <?php endif; ?>
                            <button class="btn-link text-danger remove-method" data-id="<?php echo $method->id; ?>">Remove</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <button class="btn-primary" onclick="openAddPaymentModal()">Add Payment Method</button>
    </div>
    
    <div class="billing-section">
        <h2>Billing History</h2>
        <table class="billing-history-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Invoice</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Jan 1, 2026</td>
                    <td><?php echo esc_html($tier_info['name']); ?> Subscription</td>
                    <td>$<?php echo number_format($tier_info['price'], 2); ?></td>
                    <td><span class="badge badge-success">Paid</span></td>
                    <td><a href="#">Download</a></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
function openAddPaymentModal() {
    var modalHTML = `
        <div id="payment-modal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 9999;">
            <div style="background: white; padding: 40px; border-radius: 12px; max-width: 500px; width: 90%;">
                <h2 style="margin-top: 0;">Add Payment Method</h2>
                <p>Enter your card details to add a payment method.</p>
                
                <form id="payment-form">
                    <div class="form-group">
                        <label>Cardholder Name</label>
                        <input type="text" name="cardholder_name" placeholder="John Doe" required style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px;">
                    </div>
                    
                    <div class="form-group">
                        <label>Card Number</label>
                        <input type="text" name="card_number" placeholder="4242 4242 4242 4242" maxlength="19" required style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px;">
                    </div>
                    
                    <div style="display: flex; gap: 12px;">
                        <div class="form-group" style="flex: 1;">
                            <label>Expiry Date</label>
                            <input type="text" name="expiry" placeholder="MM/YY" maxlength="5" required style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px;">
                        </div>
                        
                        <div class="form-group" style="flex: 1;">
                            <label>CVC</label>
                            <input type="text" name="cvc" placeholder="123" maxlength="3" required style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px;">
                        </div>
                    </div>
                    
                    <div style="font-size: 12px; color: #6b7280; padding: 12px; background: #f9fafb; border-radius: 6px; margin: 16px 0;">
                        🔒 Your payment information is secure and encrypted. We use Stripe for payment processing.
                    </div>
                    
                    <div style="display: flex; gap: 12px; margin-top: 24px;">
                        <button type="submit" class="btn-primary" style="flex: 1;">Add Card</button>
                        <button type="button" class="btn-secondary" onclick="closePaymentModal()" style="flex: 1;">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    `;
    
    jQuery('body').append(modalHTML);
    
    jQuery('#payment-form').on('submit', function(e) {
        e.preventDefault();
        
        // Simulate payment processing
        alert('Payment method would be added via Stripe API.\n\nIn production:\n1. Tokenize card with Stripe.js\n2. Send token to server\n3. Create payment method\n4. Save to database\n5. Update UI');
        
        closePaymentModal();
        // location.reload();
    });
}

function closePaymentModal() {
    jQuery('#payment-modal').remove();
}
</script>
