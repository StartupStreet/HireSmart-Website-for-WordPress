<?php
/**
 * HireSmart Payment Class
 * 
 * Handles payment processing and payment methods
 */

class HireSmart_Payment {
    
    public function add_payment_method($user_id, $data) {
        global $wpdb;
        $table = $wpdb->prefix . 'hiresmart_payment_methods';
        
        // If this is the first payment method, make it default
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table WHERE user_id = %d",
            $user_id
        ));
        
        $is_default = ($existing == 0) ? 1 : 0;
        
        $wpdb->insert($table, array(
            'user_id' => $user_id,
            'payment_type' => sanitize_text_field($data['payment_type']),
            'card_last4' => sanitize_text_field($data['card_last4'] ?? ''),
            'card_brand' => sanitize_text_field($data['card_brand'] ?? ''),
            'stripe_payment_method_id' => sanitize_text_field($data['stripe_payment_method_id'] ?? ''),
            'is_default' => $is_default
        ));
        
        return $wpdb->insert_id;
    }
    
    public function get_payment_methods($user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'hiresmart_payment_methods';
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table WHERE user_id = %d ORDER BY is_default DESC, created_at DESC",
            $user_id
        ));
    }
    
    public function set_default_payment_method($user_id, $payment_method_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'hiresmart_payment_methods';
        
        // Remove default from all methods
        $wpdb->update(
            $table,
            array('is_default' => 0),
            array('user_id' => $user_id)
        );
        
        // Set new default
        return $wpdb->update(
            $table,
            array('is_default' => 1),
            array('id' => $payment_method_id, 'user_id' => $user_id)
        );
    }
    
    public function remove_payment_method($user_id, $payment_method_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'hiresmart_payment_methods';
        
        return $wpdb->delete(
            $table,
            array('id' => $payment_method_id, 'user_id' => $user_id)
        );
    }
    
    public function process_payment($user_id, $amount, $payment_method_id) {
        // This is a placeholder for actual payment processing
        // In production, integrate with Stripe, PayPal, etc.
        
        // For demo purposes, we'll simulate a successful payment
        $payment_data = array(
            'user_id' => $user_id,
            'amount' => $amount,
            'payment_method_id' => $payment_method_id,
            'status' => 'completed',
            'transaction_id' => 'demo_' . uniqid(),
            'timestamp' => current_time('mysql')
        );
        
        // Store payment record (you would add a payments table in production)
        do_action('hiresmart_payment_processed', $payment_data);
        
        return array(
            'success' => true,
            'message' => 'Payment processed successfully',
            'transaction_id' => $payment_data['transaction_id']
        );
    }
    
    public function get_stripe_publishable_key() {
        // This should be stored in wp-config.php or plugin settings
        return get_option('hiresmart_stripe_publishable_key', '');
    }
}
