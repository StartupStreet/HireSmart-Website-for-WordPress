# HireSmart Complete Application Documentation

## Overview

This document describes the complete HireSmart application implementation, including the WordPress theme and plugin for the AI-powered job portal.

## System Architecture

### Components

1. **WordPress Theme** (`/`)
   - Landing page with features, pricing, use cases
   - Responsive design
   - Modern UI with smooth animations

2. **WordPress Plugin** (`/hiresmart-plugin/`)
   - Complete authentication system
   - Role-based dashboards
   - AI profiling
   - Subscription management
   - Payment integration
   - Profile integrations

## User Flow

### 1. Registration Flow

```
Landing Page → Register Page
↓
User fills in:
- Personal information (name, email, password)
- Account type selection (Job Seeker, Employer, Agency)
- Subscription tier (Free, Startup $299/mo, Enterprise $999/mo)
- Accept terms and conditions
↓
Submit Registration
↓
If Free: → Dashboard
If Paid: → Billing Page (add payment method) → Dashboard
```

### 2. Social Login Flow

```
Landing Page/Login Page → Click Social Login (Google/LinkedIn/GitHub)
↓
OAuth Authentication
↓
If New User:
  → Account Setup Page (select type & subscription)
  → Payment (if needed)
  → Dashboard
If Existing User:
  → Dashboard
```

### 3. Dashboard Experience

#### Job Seeker Dashboard
- **Stats Cards**: Applications sent, profile views, interviews, matches
- **Recent Activity**: Applications, matches, profile views
- **AI Scores**: IQ, EQ, SQ assessment results
- **Actions**: Take AI assessment, apply to jobs

#### Employer Dashboard
- **Stats Cards**: Active jobs, applicants, interviews, positions filled
- **Recent Activity**: New applicants, interviews, job postings
- **Actions**: Post new job, view applicants

#### Agency Dashboard
- **Stats Cards**: Active clients, placements, candidates, revenue
- **Recent Activity**: Placements, client inquiries, candidates
- **Actions**: Add client, manage candidates

## Features by Section

### Authentication System

**Registration** (`/register`)
- Full name, email, password
- Account type selection
- Subscription tier selection with inline pricing
- Terms acceptance
- Social login buttons

**Login** (`/login`)
- Email and password
- Remember me checkbox
- Forgot password link
- Social login buttons

**Social Login**
- Google OAuth integration ready
- LinkedIn OAuth integration ready
- GitHub OAuth integration ready

### User Profile Management

**Profile Page** (`/profile`)
- Personal information
- Account type (display only)
- AI assessment scores with progress bars
- Take AI assessment button

**AI Profiling**
- IQ Assessment (Intelligence Quotient): 70-150 scale
- EQ Assessment (Emotional Quotient): 30-100 scale
- SQ Assessment (Social Quotient): 30-100 scale
- Automated scoring algorithm
- Visual score display

### Billing & Subscriptions

**Billing Page** (`/billing`)
- Current subscription plan display
- Plan upgrade/downgrade
- Payment methods management
- Billing history table
- Invoice downloads

**Subscription Tiers**
1. **Free** - $0/month
   - Basic job matching
   - Limited applications
   - Profile creation
   - Email notifications

2. **Startup** - $299/month
   - Advanced AI matching
   - Unlimited applications
   - Priority support
   - Analytics dashboard
   - Custom branding

3. **Enterprise** - $999/month
   - All Startup features
   - White-label solution
   - API access
   - Dedicated account manager
   - Custom integrations
   - Advanced reporting

### Integrations

**Integrations Page** (`/integrations`)
- LinkedIn profile URL
- GitHub account
- Behance portfolio
- Canva designs
- Personal portfolio website
- Connection status indicators

## Database Schema

### wp_hiresmart_profiles
```sql
- id (bigint, PK)
- user_id (bigint, FK)
- account_type (varchar: job_seeker, employer, agency)
- subscription_tier (varchar: free, startup, enterprise)
- linkedin_url (varchar)
- github_url (varchar)
- behance_url (varchar)
- canva_url (varchar)
- portfolio_url (varchar)
- iq_score (int)
- eq_score (int)
- sq_score (int)
- profile_data (longtext, JSON)
- created_at (datetime)
- updated_at (datetime)
```

### wp_hiresmart_subscriptions
```sql
- id (bigint, PK)
- user_id (bigint, FK)
- subscription_tier (varchar)
- status (varchar: pending, active, cancelled)
- amount (decimal)
- payment_method (varchar)
- stripe_subscription_id (varchar)
- start_date (datetime)
- end_date (datetime)
- created_at (datetime)
```

### wp_hiresmart_payment_methods
```sql
- id (bigint, PK)
- user_id (bigint, FK)
- payment_type (varchar)
- card_last4 (varchar)
- card_brand (varchar)
- stripe_payment_method_id (varchar)
- is_default (tinyint)
- created_at (datetime)
```

## API Endpoints (AJAX)

All AJAX requests use WordPress nonces for security.

### Authentication
- `hiresmart_register` - Create new user account
- `hiresmart_login` - Authenticate user

### Profile Management
- `hiresmart_update_profile` - Update user profile and integrations
- `hiresmart_ai_assessment` - Submit and calculate AI scores

### Billing
- `hiresmart_set_default_payment` - Set default payment method
- `hiresmart_remove_payment` - Remove payment method

## Integration Guides

### Stripe Payment Integration

1. Install Stripe PHP SDK:
```bash
composer require stripe/stripe-php
```

2. Add keys to `wp-config.php`:
```php
define('HIRESMART_STRIPE_PUBLIC_KEY', 'pk_live_...');
define('HIRESMART_STRIPE_SECRET_KEY', 'sk_live_...');
```

3. Update `class-hiresmart-payment.php` with Stripe API calls

### Social Login Integration

#### Google OAuth
1. Create project in Google Cloud Console
2. Enable Google+ API
3. Create OAuth 2.0 credentials
4. Add redirect URI: `https://yoursite.com/wp-admin/admin-ajax.php?action=google_oauth_callback`
5. Add client ID to `wp-config.php`

#### LinkedIn OAuth
1. Create app in LinkedIn Developer Portal
2. Request OAuth 2.0 permissions
3. Add redirect URI
4. Add client ID and secret to `wp-config.php`

#### GitHub OAuth
1. Register OAuth app in GitHub settings
2. Add authorization callback URL
3. Add client ID and secret to `wp-config.php`

### AI Model Integration

For production-ready AI profiling:

1. **IQ Assessment**: Integrate with psychometric testing APIs or develop custom ML models
2. **EQ Assessment**: Use emotional intelligence frameworks and assessment tools
3. **SQ Assessment**: Implement social skills evaluation algorithms

Example integration with custom ML:
```php
// In class-hiresmart-ai-profiling.php
private function calculate_iq($data) {
    $ml_api = new MLService();
    $result = $ml_api->assess_intelligence([
        'responses' => $data['assessment_responses'],
        'user_profile' => $data['user_profile']
    ]);
    return $result['iq_score'];
}
```

## Security Considerations

1. **Nonce Verification**: All AJAX requests verify WordPress nonces
2. **SQL Injection Protection**: All queries use `$wpdb->prepare()`
3. **XSS Protection**: All output uses `esc_html()`, `esc_attr()`, `esc_url()`
4. **CSRF Protection**: WordPress nonces on all forms
5. **Capability Checks**: User authentication required for protected pages
6. **Password Security**: WordPress handles password hashing

## Deployment Checklist

### Pre-Launch
- [ ] Configure Stripe API keys
- [ ] Set up OAuth apps for social login
- [ ] Configure email templates
- [ ] Set up SSL certificate
- [ ] Test registration flow
- [ ] Test payment processing
- [ ] Test AI assessment
- [ ] Verify social login
- [ ] Check responsive design
- [ ] Test on multiple browsers

### Launch
- [ ] Activate plugin on production
- [ ] Verify database tables created
- [ ] Check all pages created correctly
- [ ] Test complete user journey
- [ ] Monitor error logs
- [ ] Set up analytics tracking

### Post-Launch
- [ ] Monitor user registrations
- [ ] Track subscription conversions
- [ ] Analyze dashboard usage
- [ ] Collect user feedback
- [ ] Optimize AI algorithms
- [ ] Improve based on analytics

## Customization

### Adding New Account Types

1. Update subscription tier options in `class-hiresmart-subscription.php`
2. Add new dashboard template in `class-hiresmart-dashboard.php`
3. Update registration form in `templates/register.php`
4. Add corresponding mock data generator

### Modifying Subscription Tiers

1. Edit `$tiers` array in `class-hiresmart-subscription.php`
2. Update pricing display in registration form
3. Adjust features list for each tier
4. Update billing page to reflect changes

### Customizing Dashboard

1. Edit dashboard templates in `includes/class-hiresmart-dashboard.php`
2. Modify stat generators in `class-hiresmart-user.php`
3. Update CSS in `assets/css/dashboard.css`
4. Add new widgets or sections as needed

## Support & Maintenance

### Common Issues

**Plugin not activating**
- Check PHP version (7.4+)
- Verify file permissions
- Check error logs

**Pages not created**
- Manually create pages with shortcodes
- Check permalink structure
- Flush rewrite rules

**AJAX not working**
- Verify jQuery is loaded
- Check browser console for errors
- Verify nonce generation

### Debugging

Enable WordPress debugging:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

Check logs at `/wp-content/debug.log`

## Future Enhancements

1. **Job Posting System**: Full ATS with job listings and applications
2. **Messaging System**: Direct messaging between users
3. **Video Interviews**: Integrated video call functionality
4. **Resume Parser**: AI-powered resume analysis
5. **Skill Assessment**: Technical skills testing
6. **Mobile App**: Native iOS and Android apps
7. **Analytics Dashboard**: Advanced reporting and insights
8. **Email Campaigns**: Automated email marketing
9. **API Access**: RESTful API for integrations
10. **Multi-language Support**: Internationalization

## License

GPL v2 or later

## Credits

Developed by StartupStreet
