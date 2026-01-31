# HireSmart WordPress Plugin

Complete AI-powered job portal plugin with authentication, dashboards, payment integration, and AI profiling.

## Features

### Authentication System
- User registration with account type selection (Job Seeker, Employer, Agency)
- Subscription tier selection (Free, Startup, Enterprise)
- Login/logout functionality
- Social login integration (Google, LinkedIn, GitHub) - ready for OAuth

### User Profiles
- Customizable user profiles
- AI profiling with IQ, EQ, and SQ scores
- Profile integration with professional networks
- Support for LinkedIn, GitHub, Behance, Canva, and portfolio URLs

### Dashboards
- Role-specific dashboards for each account type:
  - **Job Seekers**: Applications, profile views, interviews, matches
  - **Employers**: Active jobs, applicants, interviews, positions filled
  - **Agencies**: Clients, placements, candidates, revenue
- Real-time mock data visualization
- Recent activity tracking

### Billing & Subscriptions
- Multiple subscription tiers with pricing
- Payment method management
- Billing history
- Stripe integration ready

### AI Profiling System
- Intelligence Quotient (IQ) assessment
- Emotional Quotient (EQ) assessment
- Social Quotient (SQ) assessment
- Automated score calculation
- Profile sync and updates

## Installation

1. Upload the `hiresmart-plugin` folder to `/wp-content/plugins/`
2. Activate the plugin through the WordPress admin panel
3. The plugin will automatically create necessary pages:
   - /dashboard
   - /profile
   - /billing
   - /integrations
   - /login
   - /register

## Database Tables

The plugin creates the following custom tables:

- `wp_hiresmart_profiles` - User profile data and AI scores
- `wp_hiresmart_subscriptions` - Subscription information
- `wp_hiresmart_payment_methods` - Payment method storage

## Shortcodes

- `[hiresmart_dashboard]` - Display user dashboard
- `[hiresmart_profile]` - Display user profile management
- `[hiresmart_billing]` - Display billing and subscription management
- `[hiresmart_integrations]` - Display profile integrations
- `[hiresmart_login]` - Display login form
- `[hiresmart_register]` - Display registration form

## File Structure

```
hiresmart-plugin/
├── hiresmart.php              # Main plugin file
├── includes/
│   ├── class-hiresmart-core.php         # Core functionality
│   ├── class-hiresmart-auth.php         # Authentication
│   ├── class-hiresmart-user.php         # User management
│   ├── class-hiresmart-subscription.php # Subscriptions
│   ├── class-hiresmart-payment.php      # Payment processing
│   ├── class-hiresmart-dashboard.php    # Dashboard rendering
│   └── class-hiresmart-ai-profiling.php # AI assessment
├── templates/
│   ├── register.php          # Registration form
│   ├── login.php            # Login form
│   ├── dashboard.php        # Dashboard template
│   ├── profile.php          # Profile management
│   ├── billing.php          # Billing & subscriptions
│   └── integrations.php     # Profile integrations
└── assets/
    ├── css/
    │   ├── hiresmart.css    # Main styles
    │   └── dashboard.css    # Dashboard styles
    └── js/
        └── hiresmart.js     # Main JavaScript
```

## Integration Points

### Social Login
The plugin includes placeholders for social login integration. To enable:
1. Register OAuth apps with Google, LinkedIn, and GitHub
2. Add OAuth credentials to WordPress settings
3. Implement OAuth flow in the social login handlers

### Payment Processing
The plugin includes Stripe integration placeholders. To enable:
1. Install Stripe PHP SDK
2. Add Stripe API keys to WordPress settings
3. Implement Stripe payment processing in `class-hiresmart-payment.php`

### AI Profiling
Current implementation uses simplified algorithms. For production:
1. Integrate with ML models (TensorFlow, scikit-learn)
2. Implement comprehensive assessment questionnaires
3. Add advanced analytics and insights

## Configuration

Add these constants to `wp-config.php` for production:

```php
define('HIRESMART_STRIPE_PUBLIC_KEY', 'pk_live_...');
define('HIRESMART_STRIPE_SECRET_KEY', 'sk_live_...');
define('HIRESMART_GOOGLE_CLIENT_ID', 'your-google-client-id');
define('HIRESMART_LINKEDIN_CLIENT_ID', 'your-linkedin-client-id');
define('HIRESMART_GITHUB_CLIENT_ID', 'your-github-client-id');
```

## AJAX Endpoints

- `hiresmart_register` - Handle user registration
- `hiresmart_login` - Handle user login
- `hiresmart_update_profile` - Update user profile
- `hiresmart_ai_assessment` - Process AI assessment
- `hiresmart_set_default_payment` - Set default payment method
- `hiresmart_remove_payment` - Remove payment method

## Security

- All AJAX calls use WordPress nonces
- SQL queries use prepared statements
- Input sanitization and validation
- User capability checks

## License

GPL v2 or later
