# HireSmart - AI-Powered Job Portal

## 🚀 Quick Start

**Want to preview or deploy?**
- 📖 [QUICKSTART.md](./QUICKSTART.md) - Fast preview & deployment steps
- 🧪 [PREVIEW_GUIDE.md](./PREVIEW_GUIDE.md) - Local testing instructions
- 🌐 [DEPLOYMENT_GUIDE.md](./DEPLOYMENT_GUIDE.md) - Production deployment for two-domain setup

**Deploy to:**
- Landing page: `hiresmart.startupstreet.in`
- Dashboard app: `app-hiresmart.startupstreet.in`

---

## Project Overview

HireSmart is a comprehensive AI-powered job portal and career builder with an advanced Applicant Tracking System (ATS). The platform connects job seekers, employers, and recruitment agencies through intelligent neural AI technology.

This repository contains both the WordPress theme (landing page) and the complete WordPress plugin (full application).

## 🎯 Complete Application Features

### 🔐 Authentication & User Management
- User registration with account type selection (Job Seeker, Employer, Agency)
- Subscription tier selection (Free, Startup $299/mo, Enterprise $999/mo)
- Login/logout with session management
- Social login integration ready (Google, LinkedIn, GitHub)
- Profile creation and management

### 📊 Role-Based Dashboards

#### For Job Seekers
- Applications tracking
- Profile views analytics
- Interview scheduling
- AI-powered job matches
- Career path recommendations
- AI profiling scores (IQ, EQ, SQ)

#### For Employers
- Active job postings management
- Applicant tracking and screening
- Interview scheduling
- Positions filled analytics
- Team collaboration tools
- AI candidate ranking

#### For Recruitment Agencies
- Multi-client management
- Talent pool management
- Placement tracking
- Revenue analytics
- Commission tracking
- Dedicated account management

### 🧠 AI Profiling System
- **IQ Assessment**: Intelligence quotient scoring (70-150 scale)
- **EQ Assessment**: Emotional quotient scoring (30-100 scale)
- **SQ Assessment**: Social quotient scoring (30-100 scale)
- Automated score calculation
- Visual progress indicators
- Profile enhancement recommendations

### 💳 Subscription & Billing
- **Free Tier**: $0/month
  - Basic features
  - Limited applications
  - Profile creation
  
- **Startup Tier**: $299/month
  - Advanced AI matching
  - Unlimited applications
  - Priority support
  - Analytics dashboard
  
- **Enterprise Tier**: $999/month
  - White-label solution
  - API access
  - Dedicated account manager
  - Custom integrations

### 🔗 Profile Integrations
- LinkedIn profile sync
- GitHub repository showcase
- Behance portfolio
- Canva designs
- Custom portfolio URLs
- Automatic profile data import

### 💰 Payment Management
- Payment method storage
- Stripe integration ready
- Billing history
- Invoice generation
- Subscription management

## 📁 Repository Structure

```
HireSmart-Website-for-WordPress/
├── Theme Files (Landing Page)
│   ├── style.css           # Theme stylesheet
│   ├── index.php           # Landing page template
│   ├── header.php          # Header with navigation
│   ├── footer.php          # Footer template
│   ├── functions.php       # Theme functions
│   ├── js/main.js          # Landing page JavaScript
│   └── preview.html        # Standalone preview
│
├── hiresmart-plugin/       # Complete Application Plugin
│   ├── hiresmart.php       # Main plugin file
│   ├── includes/           # Core classes
│   │   ├── class-hiresmart-core.php
│   │   ├── class-hiresmart-auth.php
│   │   ├── class-hiresmart-user.php
│   │   ├── class-hiresmart-subscription.php
│   │   ├── class-hiresmart-payment.php
│   │   ├── class-hiresmart-dashboard.php
│   │   └── class-hiresmart-ai-profiling.php
│   ├── templates/          # Page templates
│   │   ├── register.php
│   │   ├── login.php
│   │   ├── dashboard.php
│   │   ├── profile.php
│   │   ├── billing.php
│   │   └── integrations.php
│   ├── assets/             # CSS and JavaScript
│   │   ├── css/
│   │   │   ├── hiresmart.css
│   │   │   └── dashboard.css
│   │   └── js/
│   │       └── hiresmart.js
│   └── README.md           # Plugin documentation
│
└── IMPLEMENTATION_GUIDE.md # Complete setup guide
```

## 🚀 Installation

### Theme Installation

1. Clone this repository
2. Copy theme files to `wp-content/themes/hiresmart/`
3. Activate theme in WordPress admin
4. Landing page will display as homepage

### Plugin Installation

1. Copy `hiresmart-plugin/` to `wp-content/plugins/`
2. Activate plugin in WordPress admin
3. Plugin creates necessary pages automatically:
   - `/dashboard` - User dashboard
   - `/profile` - Profile management
   - `/billing` - Subscription & billing
   - `/integrations` - Profile integrations
   - `/login` - Sign in page
   - `/register` - Account creation

### Database Tables

Plugin automatically creates:
- `wp_hiresmart_profiles` - User profiles and AI scores
- `wp_hiresmart_subscriptions` - Subscription data
- `wp_hiresmart_payment_methods` - Payment information

## 🎨 Landing Page Features

- Modern, professional design
- Hero section with call-to-action
- Features showcase (6 AI capabilities)
- Use cases for all user types
- Competitive differentiators
- Transparent pricing table
- Responsive mobile design
- Smooth scroll animations

## 💻 Technology Stack

- **Backend**: PHP 7.4+, WordPress 5.0+
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Database**: MySQL 5.7+
- **Payment**: Stripe integration ready
- **OAuth**: Google, LinkedIn, GitHub ready
- **AI**: Pluggable ML model support

## 🔧 Configuration

Add to `wp-config.php` for production:

```php
// Stripe Payment Integration
define('HIRESMART_STRIPE_PUBLIC_KEY', 'pk_live_...');
define('HIRESMART_STRIPE_SECRET_KEY', 'sk_live_...');

// Social Login OAuth
define('HIRESMART_GOOGLE_CLIENT_ID', 'your-google-client-id');
define('HIRESMART_LINKEDIN_CLIENT_ID', 'your-linkedin-client-id');
define('HIRESMART_GITHUB_CLIENT_ID', 'your-github-client-id');
```

## 🎯 User Journey

### New User Registration
1. Visit landing page
2. Click "Get Started"
3. Fill registration form:
   - Personal information
   - Select account type
   - Choose subscription tier
   - Accept terms
4. If paid plan → Add payment method
5. Access dashboard

### Social Login
1. Click social login button
2. OAuth authentication
3. If new user → Complete account setup
4. Access dashboard

### Dashboard Experience
1. View personalized stats
2. Access recent activity
3. Take AI assessment
4. Manage profile
5. Configure integrations
6. Handle billing

## 📊 What Makes HireSmart Different

1. **Neural AI Technology**: Context-aware matching beyond keywords
2. **95% Faster Matching**: Instant candidate-job pairing
3. **Unified Platform**: All stakeholders in one place
4. **Success Prediction**: AI predicts candidate success rates
5. **Real-Time Insights**: Complete transparency with analytics
6. **Scalable**: From startups to enterprises

## 🔒 Security

- WordPress nonce verification on all forms
- SQL injection protection (prepared statements)
- XSS protection (output escaping)
- CSRF protection
- User capability checks
- Secure password hashing

## 📈 Future Enhancements

- Full ATS with job posting system
- Direct messaging between users
- Video interview integration
- AI-powered resume parser
- Technical skill assessments
- Mobile applications (iOS/Android)
- Advanced analytics dashboard
- Email marketing automation
- RESTful API
- Multi-language support

## 📖 Documentation

- [Plugin README](hiresmart-plugin/README.md) - Plugin-specific documentation
- [Implementation Guide](IMPLEMENTATION_GUIDE.md) - Complete setup and customization guide
- [Theme README](THEME_README.md) - Theme-specific information

## 🧪 Testing

```bash
# Validate PHP syntax
find . -name "*.php" -exec php -l {} \;

# Check WordPress coding standards (requires PHP_CodeSniffer)
phpcs --standard=WordPress hiresmart-plugin/

# Test in browser
# 1. Activate theme and plugin
# 2. Test registration flow
# 3. Test login
# 4. Verify dashboard loads
# 5. Test profile updates
# 6. Check billing page
```

## 🤝 Contributing

This is a production-ready implementation. For customization:

1. Fork the repository
2. Create feature branch
3. Make your changes
4. Test thoroughly
5. Submit pull request

## 📝 License

GPL v2 or later

## 👥 Author

StartupStreet

## 📞 Contact

For more information: https://github.com/StartupStreet/HireSmart-Website-for-WordPress

---

**Ready to Transform Hiring?** Install HireSmart today and experience the future of AI-powered recruitment!
