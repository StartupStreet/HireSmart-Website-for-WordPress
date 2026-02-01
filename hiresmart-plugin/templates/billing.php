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
                <p style="color: #dc2626; background: #fef2f2; padding: 12px; border-radius: 6px; margin-bottom: 16px; font-size: 14px;">
                    ⚠️ <strong>Demo UI Only:</strong> This form is for demonstration purposes. In production, use Stripe Elements for PCI-compliant payment collection.
                </p>
                <p>This UI demonstrates the payment collection flow. Production implementation requires Stripe.js integration.</p>
                
                <form id="payment-form">
                    <div class="form-group">
                        <label>Cardholder Name</label>
                        <input type="text" name="cardholder_name" placeholder="John Doe" required style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px;">
                    </div>
                    
                    <div class="form-group">
                        <label>Card Number (Demo - Not Collected)</label>
                        <input type="text" name="card_number" placeholder="Use Stripe Elements in production" disabled style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px; background: #f9fafb;">
                        <small style="color: #6b7280; font-size: 12px;">Production: Stripe Elements iframe handles card input</small>
                    </div>
                    
                    <div style="display: flex; gap: 12px;">
                        <div class="form-group" style="flex: 1;">
                            <label>Expiry Date</label>
                            <input type="text" placeholder="MM/YY" disabled style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px; background: #f9fafb;">
                        </div>
                        
                        <div class="form-group" style="flex: 1;">
                            <label>CVC</label>
                            <input type="text" placeholder="123" disabled style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px; background: #f9fafb;">
                        </div>
                    </div>
                    
                    <div style="font-size: 12px; color: #1f2937; padding: 12px; background: #dbeafe; border-radius: 6px; margin: 16px 0; border-left: 4px solid #2563eb;">
                        <strong>Production Implementation:</strong><br>
                        1. Load Stripe.js library<br>
                        2. Create Stripe Elements for card input<br>
                        3. Tokenize card on client side<br>
                        4. Send token (not card data) to server<br>
                        5. Create PaymentMethod via Stripe API<br>
                        6. Store payment method ID only<br><br>
                        Card data never touches your server (PCI DSS compliant).
                    </div>
                    
                    <div style="display: flex; gap: 12px; margin-top: 24px;">
                        <button type="submit" class="btn-primary" style="flex: 1;">Simulate Add Card</button>
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
        alert('✅ Demo Mode\n\nPayment method UI demonstrated.\n\nProduction Flow:\n1. Tokenize with Stripe Elements\n2. Send token to server\n3. Create Stripe PaymentMethod\n4. Attach to customer\n5. Save payment method ID\n6. Update UI\n\nSee IMPLEMENTATION_GUIDE.md for Stripe setup.');
        
        closePaymentModal();
        // location.reload();
    });
}

function closePaymentModal() {
    jQuery('#payment-modal').remove();
}
</script>
