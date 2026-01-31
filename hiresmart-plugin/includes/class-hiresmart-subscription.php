<?php
/**
 * HireSmart Subscription Class
 * 
 * Manages subscription tiers and billing
 */

class HireSmart_Subscription {
    
    private $tiers = array(
        'free' => array(
            'name' => 'Free',
            'price' => 0,
            'features' => array(
                'Basic job matching',
                'Limited applications',
                'Profile creation',
                'Email notifications'
            )
        ),
        'startup' => array(
            'name' => 'Startup',
            'price' => 299,
            'features' => array(
                'Advanced AI matching',
                'Unlimited applications',
                'Priority support',
                'Analytics dashboard',
                'Custom branding'
            )
        ),
        'enterprise' => array(
            'name' => 'Enterprise',
            'price' => 999,
            'features' => array(
                'All Startup features',
                'White-label solution',
                'API access',
                'Dedicated account manager',
                'Custom integrations',
                'Advanced reporting'
            )
        )
    );
    
    public function get_tiers() {
        return $this->tiers;
    }
    
    public function get_tier($tier_name) {
        return $this->tiers[$tier_name] ?? null;
    }
    
    public function create_subscription($user_id, $tier) {
        global $wpdb;
        $table = $wpdb->prefix . 'hiresmart_subscriptions';
        
        $tier_data = $this->get_tier($tier);
        $status = ($tier === 'free') ? 'active' : 'pending';
        
        $wpdb->insert($table, array(
            'user_id' => $user_id,
            'subscription_tier' => $tier,
            'status' => $status,
            'amount' => $tier_data['price'],
            'start_date' => current_time('mysql'),
            'end_date' => date('Y-m-d H:i:s', strtotime('+1 year'))
        ));
        
        return $wpdb->insert_id;
    }
    
    public function get_subscription($user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'hiresmart_subscriptions';
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE user_id = %d ORDER BY created_at DESC LIMIT 1",
            $user_id
        ));
    }
    
    public function update_subscription_status($subscription_id, $status, $payment_method_id = null) {
        global $wpdb;
        $table = $wpdb->prefix . 'hiresmart_subscriptions';
        
        $update_data = array('status' => $status);
        
        if ($payment_method_id) {
            $update_data['stripe_subscription_id'] = $payment_method_id;
        }
        
        return $wpdb->update(
            $table,
            $update_data,
            array('id' => $subscription_id)
        );
    }
    
    public function cancel_subscription($user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'hiresmart_subscriptions';
        
        return $wpdb->update(
            $table,
            array('status' => 'cancelled'),
            array('user_id' => $user_id)
        );
    }
    
    public function get_pricing_table() {
        $tiers = $this->get_tiers();
        $html = '<div class="pricing-tiers">';
        
        foreach ($tiers as $key => $tier) {
            $html .= '<div class="pricing-tier pricing-tier-' . $key . '">';
            $html .= '<h3>' . $tier['name'] . '</h3>';
            $html .= '<div class="price">$' . number_format($tier['price'], 0) . '<span>/month</span></div>';
            $html .= '<ul>';
            foreach ($tier['features'] as $feature) {
                $html .= '<li>' . $feature . '</li>';
            }
            $html .= '</ul>';
            $html .= '<button class="select-tier" data-tier="' . $key . '">Select Plan</button>';
            $html .= '</div>';
        }
        
        $html .= '</div>';
        return $html;
    }
}
