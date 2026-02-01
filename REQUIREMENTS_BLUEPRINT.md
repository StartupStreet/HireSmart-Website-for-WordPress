# HireSmart Requirements & Workflow Blueprint

**Version:** 1.0.0  
**Last Updated:** February 2026  
**Document Type:** Comprehensive System Requirements & Architecture  
**Status:** Production Ready

---

## Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [System Architecture](#2-system-architecture)
3. [Core Requirements by Category](#3-core-requirements-by-category)
4. [Complete User Workflows](#4-complete-user-workflows)
5. [Technical Specifications](#5-technical-specifications)
6. [Domain & Deployment Strategy](#6-domain--deployment-strategy)
7. [Security & Access Control](#7-security--access-control)
8. [Feature Roadmap](#8-feature-roadmap)
9. [Appendix](#9-appendix)

---

## 1. Executive Summary

### 1.1 What is HireSmart?

HireSmart is a comprehensive AI-powered job portal and career builder platform that revolutionizes the hiring process through intelligent neural AI technology. Unlike traditional job boards that rely solely on keyword matching, HireSmart employs advanced AI algorithms to understand context, predict success rates, and facilitate meaningful connections between job seekers, employers, and recruitment agencies.

### 1.2 Key Differentiators

| Feature | Traditional Platforms | HireSmart |
|---------|----------------------|-----------|
| **Matching Technology** | Keyword-based search | Neural AI context-aware matching |
| **Matching Speed** | Manual review (days) | Instant AI-powered matching (95% faster) |
| **Success Prediction** | None | AI predicts candidate success rates |
| **Platform Unity** | Separate tools for each role | Unified platform for all stakeholders |
| **Profile Intelligence** | Static resumes | Dynamic AI-scored profiles (IQ/EQ/SQ) |
| **Transparency** | Limited insights | Real-time analytics and visibility |

### 1.3 Target Users

**Job Seekers**
- Professionals seeking career advancement
- Recent graduates entering the job market
- Career changers exploring new opportunities
- Freelancers looking for contract work

**Employers**
- Small to medium businesses (SMBs)
- Enterprise corporations
- Startups in growth phase
- Non-profit organizations

**Recruitment Agencies**
- Staffing firms managing multiple clients
- Executive search firms
- Industry-specific recruiters
- Placement agencies

### 1.4 Business Model

HireSmart operates on a freemium subscription model with three tiers:

- **Free Tier ($0/month)**: Basic features for individual job seekers
- **Startup Tier ($299/month)**: Advanced features for growing companies
- **Enterprise Tier ($999/month)**: White-label solution with dedicated support

Additional revenue streams include:
- AI Coins system for premium actions (job postings, profile boosts)
- Commission-based placements through agencies
- Referral bonuses for successful hires
- API access for enterprise integrations

### 1.5 Core Value Proposition

**For Job Seekers:**
- AI-powered job matching eliminates hours of searching
- Profile intelligence showcases skills beyond resume keywords
- Transparent visibility into employer organizations
- Career path recommendations based on AI analysis

**For Employers:**
- 95% faster candidate discovery and screening
- AI-ranked candidates reduce hiring time
- Success prediction minimizes bad hires
- Complete applicant tracking system included

**For Agencies:**
- Multi-client management in one platform
- Automated candidate matching across portfolios
- Commission tracking and revenue analytics
- Scalable solution from 1 to 1000+ placements

---

## 2. System Architecture

### 2.1 Unified Theme Approach Overview

HireSmart employs a unique two-domain architecture that separates public-facing content from authenticated application features:

```
┌─────────────────────────────────────────────────────────────────────────┐
│                          HireSmart Architecture                         │
└─────────────────────────────────────────────────────────────────────────┘

    Public Domain                           Application Domain
┌───────────────────┐                    ┌───────────────────┐
│  hiresmart.       │                    │  app-hiresmart.   │
│  startupstreet.in │                    │  startupstreet.in │
├───────────────────┤                    ├───────────────────┤
│ WordPress Theme   │                    │ WordPress Plugin  │
│ - Landing Page    │                    │ - Full App        │
│ - Marketing       │ ──── Login ────>  │ - Dashboards      │
│ - Public Jobs     │ <─── Logout ────   │ - Profiles        │
│ - SEO Content     │                    │ - Job Management  │
└───────────────────┘                    └───────────────────┘
         │                                        │
         └────────────┬───────────────────────────┘
                      │
                      ▼
            ┌──────────────────┐
            │ Shared Database  │
            │  - Users         │
            │  - Jobs          │
            │  - Applications  │
            │  - Profiles      │
            │  - Subscriptions │
            └──────────────────┘
```

### 2.2 Domain Responsibilities

#### 2.2.1 Public Domain (hiresmart.startupstreet.in)

**Purpose:** Marketing, SEO, and public job browsing

**Components:**
- WordPress installation with HireSmart Theme
- Landing page with features, pricing, testimonials
- Public job listings (first 5 visible without login)
- Authentication entry points (Login/Register links)
- Footer pages (About, Contact, Terms, Privacy)

**Functionality:**
- Static content delivery
- SEO optimization
- Public job search (limited preview)
- User acquisition funnel
- Social media integration

**Access:**
- Open to public (no authentication required)
- Guest users can browse first 5 jobs
- "Sign In" link redirects to app domain
- "Get Started" CTA redirects to registration

#### 2.2.2 Application Domain (app-hiresmart.startupstreet.in)

**Purpose:** Full application with authentication-required features

**Components:**
- WordPress installation with HireSmart Plugin
- User authentication system
- Role-based dashboards (3 types)
- Profile management
- Job posting and management
- Candidate directory
- Employer directory
- Billing and subscriptions
- Integration management

**Functionality:**
- User registration and login
- AJAX-powered dashboards
- Real-time notifications
- Payment processing
- AI profiling assessments
- Job application system
- Admin panels for each role

**Access:**
- Requires authentication
- Role-based access control
- Session maintained via shared cookies
- HTTPS required for security

### 2.3 Cross-Domain Session Management

#### 2.3.1 Cookie Domain Configuration

Both WordPress installations share authentication cookies by setting a common cookie domain:

```php
// wp-config.php (on BOTH domains)
define('COOKIE_DOMAIN', '.startupstreet.in');  // Note the leading dot
define('ADMIN_COOKIE_PATH', '/');
define('COOKIEPATH', '/');
define('SITECOOKIEPATH', '/');
```

**Why this works:**
- The leading dot (`.startupstreet.in`) makes cookies accessible to all subdomains
- Both `hiresmart.startupstreet.in` and `app-hiresmart.startupstreet.in` can read/write cookies
- WordPress auth cookies are automatically shared
- Single Sign-On (SSO) is achieved without additional systems

#### 2.3.2 Authentication Keys Synchronization

**Critical Requirement:** All 8 WordPress authentication keys MUST be identical on both domains:

```php
// wp-config.php (MUST BE IDENTICAL ON BOTH DOMAINS)
define('AUTH_KEY',         'your-unique-phrase-here-123');
define('SECURE_AUTH_KEY',  'your-unique-phrase-here-456');
define('LOGGED_IN_KEY',    'your-unique-phrase-here-789');
define('NONCE_KEY',        'your-unique-phrase-here-012');
define('AUTH_SALT',        'your-unique-phrase-here-345');
define('SECURE_AUTH_SALT', 'your-unique-phrase-here-678');
define('LOGGED_IN_SALT',   'your-unique-phrase-here-901');
define('NONCE_SALT',       'your-unique-phrase-here-234');
```

**Security Note:**
- Generate keys using: https://api.wordpress.org/secret-key/1.1/salt/
- Copy the SAME keys to both installations
- Never commit these keys to version control
- Rotate keys periodically (requires all users to re-login)

#### 2.3.3 Database Sharing Options

**Option A: Shared Database (Recommended)**

Both installations connect to the same MySQL database with different table prefixes:

```
Database: hiresmart_production

Landing Domain Tables:
- wp_main_users (WordPress core)
- wp_main_usermeta
- wp_main_posts
- wp_main_options
... (theme-specific tables)

App Domain Tables:
- wp_app_users (shared via users table sync)
- wp_app_usermeta
- wp_app_posts
- wp_app_options
... (app-specific tables)

Shared Plugin Tables:
- wp_hiresmart_profiles (used by both)
- wp_hiresmart_subscriptions (used by both)
- wp_hiresmart_payment_methods (used by both)
- wp_hiresmart_jobs (used by both)
- wp_hiresmart_applications (used by both)
```

**Configuration:**

```php
// Landing domain wp-config.php
$table_prefix = 'wp_main_';
define('DB_NAME', 'hiresmart_production');

// App domain wp-config.php
$table_prefix = 'wp_app_';
define('DB_NAME', 'hiresmart_production');
```

**Option B: Multisite Installation**

Use WordPress Multisite with subdomain mapping:

```php
// wp-config.php (single installation)
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', true);
define('DOMAIN_CURRENT_SITE', 'startupstreet.in');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);
```

### 2.4 System Flow Diagrams

#### 2.4.1 User Registration Flow

```
┌─────────────────────────────────────────────────────────────────────┐
│                     User Registration Journey                       │
└─────────────────────────────────────────────────────────────────────┘

Step 1: Discovery
    User lands on hiresmart.startupstreet.in
         │
         ▼
    Browses features, pricing, testimonials
         │
         ▼
    Clicks "Get Started" or "Sign In"

Step 2: Account Creation
    Redirects to: app-hiresmart.startupstreet.in/register
         │
         ▼
    ┌──────────────────────────────┐
    │  Registration Form           │
    │  ├─ Full Name               │
    │  ├─ Email Address           │
    │  ├─ Password                │
    │  ├─ Account Type            │
    │  │  ○ Job Seeker           │
    │  │  ○ Employer             │
    │  │  ○ Agency               │
    │  └─ Subscription Tier       │
    │     ○ Free ($0/mo)         │
    │     ○ Startup ($299/mo)    │
    │     ○ Enterprise ($999/mo) │
    └──────────────────────────────┘
         │
         ▼
    Submits form (AJAX)

Step 3: Account Processing
    ┌─────────────────────────┐
    │ WordPress User Created  │
    │ (wp_users table)        │
    └────────────┬────────────┘
                 │
                 ▼
    ┌─────────────────────────┐
    │ HireSmart Profile       │
    │ Created                 │
    │ (wp_hiresmart_profiles) │
    └────────────┬────────────┘
                 │
                 ▼
    ┌─────────────────────────┐
    │ Subscription Record     │
    │ Created                 │
    │ (wp_hiresmart_          │
    │  subscriptions)         │
    └────────────┬────────────┘
                 │
                 ▼
         Is subscription paid?
         /              \
       YES              NO
        │               │
        ▼               ▼
    Redirect to     Redirect to
    Billing Page    Dashboard
    (Add Payment)   (Free Tier)
        │               │
        ▼               │
    Payment Added       │
        │               │
        └───────┬───────┘
                │
                ▼
    User Dashboard (Role-Based)
    ├─ Job Seeker Dashboard
    ├─ Employer Dashboard
    └─ Agency Dashboard
```

#### 2.4.2 Login and Navigation Flow

```
┌─────────────────────────────────────────────────────────────────────┐
│                   Authentication & Navigation Flow                  │
└─────────────────────────────────────────────────────────────────────┘

Login Process:
    User visits app-hiresmart.startupstreet.in/login
         │
         ▼
    Enters credentials
    ├─ Email
    ├─ Password
    └─ [Remember Me]
         │
         ▼
    WordPress authenticates
         │
         ├─ Success ──────────> Set auth cookies
         │                       │
         │                       ▼
         │                  Redirect to dashboard
         │
         └─ Failure ──────────> Show error message
                                 │
                                 └──> Retry or reset password

Post-Login Navigation:
    Dashboard (app-hiresmart.startupstreet.in/dashboard)
         │
         ├─> Profile (/profile)
         │   └─ Edit personal info
         │   └─ View AI scores
         │   └─ Take assessment
         │
         ├─> Billing (/billing)
         │   └─ Manage subscription
         │   └─ Payment methods
         │   └─ View invoices
         │
         ├─> Integrations (/integrations)
         │   └─ Connect LinkedIn
         │   └─ Connect GitHub
         │   └─ Connect Behance
         │   └─ Add portfolio
         │
         ├─> Jobs (/jobs)
         │   └─ Browse all jobs (no limit)
         │   └─ Apply to jobs
         │   └─ View applications
         │
         ├─> Candidates (/candidates) [Employers/Agencies only]
         │   └─ Browse job seekers
         │   └─ View AI scores
         │   └─ Contact candidates
         │
         ├─> Employers & Agencies (/employers-agencies)
         │   └─ Browse hiring companies
         │   └─ View active jobs
         │   └─ Company profiles
         │
         └─> Logout
             └─ Clears session
             └─ Redirects to hiresmart.startupstreet.in

Cross-Domain Behavior:
    When logged in at app-hiresmart.startupstreet.in:
         │
         └─> Visit hiresmart.startupstreet.in
             │
             ├─ Header shows: "Dashboard" and "Logout"
             ├─ CTAs change to "Go to Dashboard"
             └─ Session maintained via shared cookies
```

#### 2.4.3 Job Posting and Application Flow

```
┌─────────────────────────────────────────────────────────────────────┐
│                   Job Lifecycle Management Flow                     │
└─────────────────────────────────────────────────────────────────────┘

Job Posting (Employer/Agency):
    Navigate to /post-job
         │
         ▼
    ┌──────────────────────────────┐
    │  Job Posting Form            │
    │  ├─ Job Title               │
    │  ├─ Description             │
    │  ├─ Requirements            │
    │  ├─ Location                │
    │  ├─ Salary Range            │
    │  ├─ Job Type                │
    │  ├─ Experience Level        │
    │  └─ Required Skills         │
    └────────────┬─────────────────┘
                 │
                 ▼
    Submit (costs 1 AI Coin)
         │
         ▼
    ┌──────────────────────────────┐
    │  Job Record Created          │
    │  - Status: Active            │
    │  - Expires: +14 days         │
    │  - Visible: Immediately      │
    │  - Coins deducted: 1         │
    └────────────┬─────────────────┘
                 │
                 ▼
    Job visible on /jobs page
    (To all users, including guests for first 5)

Job Discovery (Job Seeker):
    Browse /jobs page
         │
         ├─ Guest User:
         │  └─ Sees first 5 jobs
         │  └─ Remaining blurred
         │  └─ "Login to see more" gate
         │
         └─ Logged-in User:
            └─ Sees all jobs
            └─ Can use filters
            └─ Can search
         │
         ▼
    Click "View Details" or "Apply"
         │
         ▼
    ┌──────────────────────────────┐
    │  Job Detail Modal/Page       │
    │  - Full description          │
    │  - Company info              │
    │  - Requirements              │
    │  - Salary                    │
    │  - Application button        │
    └────────────┬─────────────────┘
                 │
                 ▼
    Click "Apply"
         │
         ▼
    ┌──────────────────────────────┐
    │  Application Form            │
    │  ├─ Cover Letter            │
    │  ├─ Resume Upload           │
    │  └─ Submit                  │
    └────────────┬─────────────────┘
                 │
                 ▼
    ┌──────────────────────────────┐
    │  Application Record Created  │
    │  - Job ID linked             │
    │  - Candidate ID linked       │
    │  - Status: Pending           │
    │  - Timestamp recorded        │
    └────────────┬─────────────────┘
                 │
                 ▼
    Notifications sent:
    ├─ Job Seeker: "Application submitted"
    └─ Employer: "New applicant received"

Job Management (Employer):
    Dashboard shows active jobs
         │
         ▼
    View job details
         │
         ├─ Applicants list
         │  └─ View profiles
         │  └─ AI scores
         │  └─ Accept/Reject
         │
         ├─ Job expires in 14 days
         │  └─ "Renew" button appears (costs 1 coin)
         │  └─ Extends for another 14 days
         │
         └─ Analytics
            └─ Views count
            └─ Applications count
            └─ Conversion rate
```


---

## 3. Core Requirements by Category

### 3.1 Authentication & User Management

#### 3.1.1 User Registration

**FR-AUTH-001: User Registration Form**
- **Priority:** P0 (Critical)
- **Status:** ✅ Implemented
- **Description:** Users must be able to create accounts with email and password

**Requirements:**
- Form fields:
  - Full name (required, min 2 characters)
  - Email address (required, valid format, unique)
  - Password (required, min 8 characters, must include uppercase, lowercase, number)
  - Password confirmation (must match)
  - Account type selection (radio buttons: Job Seeker, Employer, Agency)
  - Subscription tier selection (cards: Free, Startup, Enterprise)
  - Terms and conditions acceptance (checkbox, required)
- Client-side validation with inline error messages
- Server-side validation with WordPress sanitization
- AJAX submission for smooth UX
- Loading state during submission
- Success/error toast notifications

**FR-AUTH-002: Account Type Selection**
- **Priority:** P0 (Critical)
- **Status:** ✅ Implemented
- **Description:** Users must select their role during registration

**Account Types:**
1. **Job Seeker**
   - Default profile setup: AI scores, integrations
   - Dashboard: Applications, matches, interviews
   - Permissions: Apply to jobs, view companies

2. **Employer**
   - Default profile setup: Company info, industry
   - Dashboard: Active jobs, applicants, interviews
   - Permissions: Post jobs, view candidates, manage applications

3. **Agency**
   - Default profile setup: Agency info, clients
   - Dashboard: Clients, placements, candidates, revenue
   - Permissions: Post jobs for clients, manage talent pool, track commissions

**FR-AUTH-003: Subscription Tier Selection**
- **Priority:** P0 (Critical)
- **Status:** ✅ Implemented
- **Description:** Users must choose subscription plan during registration

**Subscription Tiers:**

| Feature | Free ($0/mo) | Startup ($299/mo) | Enterprise ($999/mo) |
|---------|--------------|-------------------|----------------------|
| **Job Applications** | 5 per month | Unlimited | Unlimited |
| **AI Matching** | Basic | Advanced | Premium |
| **Profile Visibility** | Standard | Enhanced | Priority |
| **Analytics** | Basic stats | Advanced dashboard | Custom reports |
| **Support** | Email (48h) | Priority email | Dedicated manager |
| **Job Postings** | N/A | 10 per month | Unlimited |
| **Candidate Search** | N/A | 50 views/month | Unlimited |
| **API Access** | No | No | Yes |
| **White Label** | No | No | Yes |
| **Custom Integration** | No | No | Yes |
| **AI Coins** | 0 | 50 per month | 200 per month |

**FR-AUTH-004: Social Login Integration**
- **Priority:** P1 (High)
- **Status:** 🔄 UI Ready, OAuth Pending
- **Description:** Users can register/login with social accounts

**Supported Providers:**
1. **Google OAuth 2.0**
   - Scopes: profile, email
   - Required setup: Google Cloud Console project
   - Redirect URL: `https://app-hiresmart.startupstreet.in/oauth/google/callback`

2. **LinkedIn OAuth 2.0**
   - Scopes: r_liteprofile, r_emailaddress
   - Required setup: LinkedIn Developer portal
   - Redirect URL: `https://app-hiresmart.startupstreet.in/oauth/linkedin/callback`

3. **GitHub OAuth 2.0**
   - Scopes: user:email, read:user
   - Required setup: GitHub OAuth Apps
   - Redirect URL: `https://app-hiresmart.startupstreet.in/oauth/github/callback`

**Social Login Flow:**
```
1. User clicks "Continue with Google/LinkedIn/GitHub"
2. Redirects to provider's OAuth consent screen
3. User authorizes application
4. Provider redirects back with auth code
5. Backend exchanges code for access token
6. Fetches user profile data
7. If existing user: Log in
8. If new user: Create account, prompt for account type & subscription
9. Redirect to dashboard or account setup
```

#### 3.1.2 User Login

**FR-AUTH-005: Login Form**
- **Priority:** P0 (Critical)
- **Status:** ✅ Implemented

**Requirements:**
- Email address field
- Password field (with show/hide toggle)
- "Remember me" checkbox (extends session to 14 days)
- "Forgot password?" link
- Social login buttons
- AJAX submission
- Loading state
- Error handling

**FR-AUTH-006: Session Management**
- **Priority:** P0 (Critical)
- **Status:** ✅ Implemented

**Session Behavior:**
- Default session: 2 days
- "Remember me" session: 14 days
- Cross-domain session via shared cookies
- Secure cookies (HTTPS only)
- HttpOnly cookies (prevent XSS)
- Session timeout handling

**FR-AUTH-007: Password Recovery**
- **Priority:** P1 (High)
- **Status:** ⏳ Pending

**Requirements:**
- Forgot password link on login page
- Email-based reset flow
- Temporary reset token (expires in 1 hour)
- Password reset form with validation
- Email notification on successful reset

#### 3.1.3 User Profile Management

**FR-AUTH-008: Profile Editing**
- **Priority:** P0 (Critical)
- **Status:** ✅ Implemented

**Editable Fields:**
- Full name
- Email address (requires verification if changed)
- Phone number
- Location (city, state, country)
- Bio/About (rich text)
- Profile photo upload
- Account type (display only, cannot change)
- Subscription tier (display only, change via billing)

**FR-AUTH-009: Account Deletion**
- **Priority:** P2 (Medium)
- **Status:** ⏳ Pending

**Requirements:**
- "Delete Account" option in profile settings
- Confirmation modal with warning
- Password verification required
- Data deletion or anonymization (GDPR)
- Email confirmation
- Cannot be undone

### 3.2 Landing Page Requirements

**FR-LAND-001: Hero Section**
- **Status:** ✅ Implemented

**Components:**
- Headline: "Transform Your Hiring with AI-Powered Intelligence"
- Subheadline: Value proposition
- Primary CTA: "Get Started Free"
- Secondary CTA: "Sign In"
- Hero image/animation
- Trust indicators (users count, companies, success rate)

**FR-LAND-002: Features Section**
- **Status:** ✅ Implemented

**Six Core Features:**
1. **Neural AI Matching**
   - Icon: Brain
   - Description: Context-aware matching beyond keywords
2. **Real-Time Insights**
   - Icon: Chart
   - Description: Analytics dashboard with live updates
3. **Success Prediction**
   - Icon: Target
   - Description: AI predicts candidate-job fit
4. **Complete ATS**
   - Icon: Briefcase
   - Description: End-to-end applicant tracking
5. **Smart Profiles**
   - Icon: User
   - Description: Dynamic profiles with AI scores
6. **Scalable Platform**
   - Icon: Growth
   - Description: From 1 to 10,000+ users

**FR-LAND-003: Use Cases Section**
- **Status:** ✅ Implemented

**Three User Types:**
- Job Seekers: Find perfect roles faster
- Employers: Hire smarter, not harder
- Agencies: Scale placement operations

**FR-LAND-004: Pricing Section**
- **Status:** ✅ Implemented

**Pricing Table:**
- Three tiers displayed as cards
- Feature comparison
- "Most Popular" badge on Startup tier
- Clear CTAs for each tier

**FR-LAND-005: Social Proof**
- **Status:** ⏳ Pending

**Components:**
- Customer testimonials (3-5)
- Company logos (trusted by)
- Statistics (jobs posted, placements made)
- Success stories

**FR-LAND-006: Footer**
- **Status:** ✅ Implemented

**Links:**
- About Us, Contact, Blog
- Terms of Service, Privacy Policy
- Features, Pricing, Support
- Social media links
- Copyright notice

### 3.3 Job Listings & Management

#### 3.3.1 Public Job Browsing

**FR-JOBS-001: Job Listings Page**
- **Priority:** P0 (Critical)
- **Status:** ✅ Implemented

**Guest Users (Not Logged In):**
- Can view first 5 job listings
- Remaining jobs are blurred
- "Login or Sign Up to View All X Jobs" gate displayed
- Can see:
  - Job title
  - Company name
  - Location
  - Salary range (if public)
  - Job type (full-time, part-time, etc.)
  - Posted date
  - Expires in X days

**Logged-In Users:**
- Can view ALL job listings
- No blur effect
- No access gate
- Can apply to jobs
- Can save jobs (future feature)

**FR-JOBS-002: Job Search & Filters**
- **Priority:** P1 (High)
- **Status:** ✅ Implemented

**Search:**
- Text search across:
  - Job title
  - Job description
  - Company name
  - Required skills

**Filters:**
- Job type: Full-time, Part-time, Contract, Freelance, Internship
- Location: Remote, specific cities
- Experience level: Entry, Mid, Senior, Lead, Executive
- Salary range: Min/Max sliders
- Date posted: Last 24h, Last 7 days, Last 30 days

**FR-JOBS-003: Job Detail View**
- **Priority:** P0 (Critical)
- **Status:** 🔄 Modal Ready, Full Page Pending

**Information Displayed:**
- Job title
- Company name and logo
- Location
- Job type and experience level
- Salary range
- Full job description
- Requirements list
- Required skills (tags)
- Posted date
- Expiry date
- Number of applicants
- Apply button
- Share button (social media)

**FR-JOBS-004: Job Application**
- **Priority:** P0 (Critical)
- **Status:** ✅ Implemented

**Requirements:**
- Requires login (job seekers only)
- Application form:
  - Cover letter (required, rich text, min 100 chars)
  - Resume upload (PDF/DOC, max 5MB)
  - Additional documents (optional)
  - Contact preferences
- Cannot apply twice to same job
- Confirmation email sent
- Employer notification sent

#### 3.3.2 Job Posting (Employers/Agencies)

**FR-JOBS-005: Post Job Form**
- **Priority:** P0 (Critical)
- **Status:** ✅ Implemented

**Access:** Employers and Agencies only

**Form Sections:**

**1. Job Information**
- Job title (required, max 100 chars)
- Job type (dropdown: full-time, part-time, contract, freelance, internship)
- Experience level (dropdown: entry, mid, senior, lead, executive)
- Location (text or "Remote")
- Salary range:
  - Minimum (number, optional)
  - Maximum (number, optional)
  - Currency (dropdown: USD, EUR, GBP, INR)
  - Display publicly (checkbox)

**2. Job Description**
- Description (required, rich text editor, min 200 chars)
- Requirements (rich text editor, min 100 chars)
- Required skills (tags, comma-separated)
- Nice-to-have skills (tags, optional)

**3. Commission & Referrals (Agencies Only)**
- Commission type (dropdown: percentage, fixed amount)
- Commission value (number)
- Referral bonus (number, optional)

**4. Posting Details**
- Duration: 14 days (2 weeks)
- Cost: 1 AI Coin
- Renewable: Every 2 weeks (costs 1 coin)
- Visibility: Immediate

**FR-JOBS-006: Job Management Dashboard**
- **Priority:** P0 (Critical)
- **Status:** ✅ Implemented

**Features:**
- List all posted jobs
- Status indicators (active, expired, filled)
- Quick stats per job:
  - Views count
  - Applications count
  - Days until expiry
- Actions:
  - Edit job
  - View applicants
  - Renew job (if expired or <3 days left)
  - Mark as filled
  - Delete job

**FR-JOBS-007: Job Expiration System**
- **Priority:** P0 (Critical)
- **Status:** ✅ Implemented

**Expiration Rules:**
- All jobs expire 14 days (2 weeks) after posting
- Expiry countdown displayed:
  - Normal (>3 days left): Blue badge "Expires in X days"
  - Urgent (≤3 days left): Red pulsing badge "Expires in X days"
- Auto-renewal option (future feature)
- Manual renewal button appears when <3 days left
- Renewal costs 1 AI Coin
- Renewal extends for another 14 days

**FR-JOBS-008: Applicant Tracking**
- **Priority:** P0 (Critical)
- **Status:** 🔄 Basic Implemented

**Features:**
- View all applications for a job
- Application details:
  - Candidate name and profile
  - AI scores (IQ, EQ, SQ)
  - Resume and cover letter
  - Application date
  - Current status
- Update application status:
  - Pending (default)
  - Reviewed
  - Interview Scheduled
  - Offer Extended
  - Accepted
  - Rejected
- Contact candidate button
- Notes field (private, visible only to employer)

### 3.4 Dashboard Application

#### 3.4.1 Job Seeker Dashboard

**FR-DASH-JS-001: Statistics Cards**
- **Status:** ✅ Implemented (Mock Data)

**Stats Displayed:**
- Applications Sent (count)
- Profile Views (count)
- Interviews Scheduled (count)
- Matches Found (count)

**FR-DASH-JS-002: Recent Activity Feed**
- **Status:** ✅ Implemented (Mock Data)

**Activity Types:**
- Application submitted
- New job match found
- Profile viewed by employer
- Interview scheduled
- Application status changed

**FR-DASH-JS-003: AI Profile Insights**
- **Status:** ✅ Implemented

**Components:**
- IQ Score (70-150 scale)
- EQ Score (30-100 scale)
- SQ Score (30-100 scale)
- Progress bars showing percentages
- "Take AI Assessment" button
- Profile completion percentage

**FR-DASH-JS-004: Recommended Jobs**
- **Status:** ⏳ Pending

**Features:**
- AI-matched jobs based on profile
- Match percentage displayed
- "Why this match?" explanation
- Quick apply button

#### 3.4.2 Employer Dashboard

**FR-DASH-EMP-001: Statistics Cards**
- **Status:** ✅ Implemented (Mock Data)

**Stats Displayed:**
- Active Jobs (count)
- Total Applicants (count)
- Interviews Scheduled (count)
- Positions Filled (count)

**FR-DASH-EMP-002: Recent Activity Feed**
- **Status:** ✅ Implemented (Mock Data)

**Activity Types:**
- New applicant received
- Interview scheduled
- Job posted
- Application reviewed
- Position filled

**FR-DASH-EMP-003: Quick Actions**
- **Status:** ✅ Implemented

**Actions:**
- Post New Job (button)
- View Applicants (button)
- Browse Candidates (button)
- View Analytics (future)

#### 3.4.3 Agency Dashboard

**FR-DASH-AGN-001: Statistics Cards**
- **Status:** ✅ Implemented (Mock Data)

**Stats Displayed:**
- Active Clients (count)
- Total Placements (count)
- Candidates Managed (count)
- Revenue Generated (currency)

**FR-DASH-AGN-002: Recent Activity Feed**
- **Status:** ✅ Implemented (Mock Data)

**Activity Types:**
- Candidate placed
- New client added
- Candidate added to pool
- Commission earned
- Client inquiry received

**FR-DASH-AGN-003: Quick Actions**
- **Status:** ✅ Implemented

**Actions:**
- Add Client (button)
- Manage Candidates (button)
- Post Job for Client (button)
- View Revenue Report (future)

### 3.5 AI Profiling System

**FR-AI-001: AI Assessment Tool**
- **Priority:** P0 (Critical)
- **Status:** ✅ Implemented

**Assessment Structure:**
- 6 questions total
- 2 questions per dimension (IQ, EQ, SQ)
- Slider input (1-10 scale) for each question
- Modal interface
- Real-time score calculation
- Results stored in database

**Questions:**

**IQ Assessment (Intelligence Quotient):**
1. Logical Reasoning (1-10)
   - "How well can you solve complex logic puzzles?"
2. Problem Solving (1-10)
   - "Rate your ability to break down complex problems"

**EQ Assessment (Emotional Quotient):**
3. Emotional Awareness (1-10)
   - "How well do you recognize your own emotions?"
4. Empathy (1-10)
   - "How easily can you understand others' perspectives?"

**SQ Assessment (Social Quotient):**
5. Communication Skills (1-10)
   - "How effective are you at expressing ideas clearly?"
6. Teamwork Ability (1-10)
   - "How well do you collaborate with others?"

**FR-AI-002: Score Calculation Algorithm**
- **Priority:** P0 (Critical)
- **Status:** ✅ Implemented

**Formulas:**
```
IQ Score = Base(100) + (logical_reasoning × 2) + (problem_solving × 2)
         = Range: 70-150 (where 100 is average)

EQ Score = Base(50) + (emotional_awareness × 5) + (empathy × 5)
         = Range: 30-100 (where 65 is average)

SQ Score = Base(50) + (communication × 5) + (teamwork × 5)
         = Range: 30-100 (where 65 is average)
```

**Score Interpretation:**

**IQ Ranges:**
- 70-85: Below Average
- 86-115: Average
- 116-130: Above Average
- 131-145: Superior
- 146-150: Very Superior

**EQ/SQ Ranges:**
- 30-45: Needs Development
- 46-60: Below Average
- 61-75: Average
- 76-90: Above Average
- 91-100: Excellent

**FR-AI-003: Profile Sync & Score Elevation**
- **Priority:** P1 (High)
- **Status:** ✅ Implemented

**Integration Levels:**

**Level 1: Resume Only (Baseline)**
- Scores calculated from manual assessment
- Base accuracy: 60%

**Level 2: Resume + LinkedIn (25% Sync)**
- Professional experience validated
- EQ score elevated by up to 10 points
- Accuracy: 70%

**Level 3: Resume + LinkedIn + GitHub (50% Sync)**
- Technical skills verified
- IQ score elevated by up to 10 points
- Accuracy: 80%

**Level 4: Resume + LinkedIn + GitHub + Behance (75% Sync)**
- Creative work assessed
- SQ score elevated by up to 10 points
- Accuracy: 90%

**Level 5: All Integrations + Portfolio (100% Sync)**
- Complete professional profile
- All scores optimized
- Maximum visibility to employers
- Accuracy: 95%

**FR-AI-004: Score Display**
- **Priority:** P0 (Critical)
- **Status:** ✅ Implemented

**Display Locations:**
- User profile page
- Dashboard (AI Profile Insights widget)
- Candidate cards (visible to employers)
- Job application details

**Visual Design:**
- Color-coded badges:
  - IQ: Blue (#dbeafe background, #1e40af text)
  - EQ: Pink (#fce7f3 background, #be185d text)
  - SQ: Green (#d1fae5 background, #065f46 text)
- Progress bars showing percentages
- Icons (brain for IQ, heart for EQ, users for SQ)

### 3.6 Candidate Directory

**FR-CAND-001: Browse Candidates Page**
- **Priority:** P0 (Critical)
- **Status:** ✅ Implemented
- **Access:** Employers and Agencies only

**Guest/Job Seeker Behavior:**
- Attempting to access shows error message
- Redirected to appropriate page or login

**Employer/Agency View:**
- Grid of candidate cards (3 columns on desktop)
- Search bar (searches name, skills)
- Filter options (future: by AI scores, location, experience)

**FR-CAND-002: Candidate Card Information**
- **Priority:** P0 (Critical)
- **Status:** ✅ Implemented

**Information Displayed:**
- Avatar (initials if no photo)
- Full name
- Email address
- AI Scores:
  - IQ Score (blue badge)
  - EQ Score (pink badge)
  - SQ Score (green badge)
- Profile Sync Status:
  - Connected platforms (LinkedIn, GitHub, Behance, Portfolio)
  - Sync percentage (0-100%)
  - Progress bar visual
- Social Profile Links:
  - LinkedIn (blue button)
  - GitHub (black button)
  - Portfolio (purple button)
- Member since date
- "Contact Candidate" button

**FR-CAND-003: Profile Sync Indicator**
- **Priority:** P1 (High)
- **Status:** ✅ Implemented

**Sync Status Display:**
- Each platform shows connection status:
  - ✓ Connected (green background)
  - Not Connected (red background)
- Progress bar shows overall completion (0-100%)
- Percentage text
- Color-coded progress fill (green gradient)

**FR-CAND-004: Contact Candidate**
- **Priority:** P1 (High)
- **Status:** ⏳ Pending

**Features:**
- Click "Contact" opens message modal
- Pre-filled subject with job title (if applicable)
- Message text area
- Send button
- Email sent to candidate
- Notification in candidate's dashboard
- Message tracking for employers

**FR-CAND-005: Access Control**
- **Priority:** P0 (Critical)
- **Status:** ✅ Implemented

**Access Rules:**
- Job Seekers: Cannot access
- Employers: Full access
- Agencies: Full access
- Guests: Cannot access (must login)

**First 5 Preview for Guests:**
- First 5 candidates visible
- Remaining blurred
- "Login or Sign Up to View All X Candidates" gate
- Encourages registration

### 3.7 Employer Directory

**FR-EMPL-001: Browse Employers Page**
- **Priority:** P0 (Critical)
- **Status:** ✅ Implemented
- **Access:** Public (all users)

**Purpose:**
- Transparency for job seekers
- Company discovery
- Shows platform activity
- Trust building

**FR-EMPL-002: Employer/Agency Card**
- **Priority:** P0 (Critical)
- **Status:** ✅ Implemented

**Information Displayed:**
- Company avatar (initials)
- Company name
- Type badge:
  - "Employer" (blue badge)
  - "Agency" (gold badge)
- Active jobs count (highlighted if >0)
- Member since date
- Subscription tier badge (gold gradient)
- Actions:
  - "View Jobs" button (if has active jobs)
  - "Contact" button

**FR-EMPL-003: Filter Tabs**
- **Priority:** P1 (High)
- **Status:** ✅ Implemented

**Filter Options:**
- All (shows count)
- Employers Only
- Agencies Only
- Active tab highlighted
- Count updates based on selection

**FR-EMPL-004: Search Functionality**
- **Priority:** P1 (High)
- **Status:** ✅ Implemented

**Search Capabilities:**
- Text search by company name
- Real-time filtering
- Case-insensitive
- Preserves current filter tab

**FR-EMPL-005: Access Control**
- **Priority:** P0 (Critical)
- **Status:** ✅ Implemented

**Access Rules:**
- Job Seekers: Full access
- Employers: Full access
- Agencies: Full access
- Guests: First 5 visible, rest blurred with gate

### 3.8 Profile Management

**FR-PROF-001: Personal Information Section**
- **Priority:** P0 (Critical)
- **Status:** ✅ Implemented

**Fields:**
- Full name (editable)
- Email address (editable, requires verification)
- Account type (display only)
- Member since date (display only)

**FR-PROF-002: AI Scores Section**
- **Priority:** P0 (Critical)
- **Status:** ✅ Implemented

**Display:**
- IQ Score with progress bar
- EQ Score with progress bar
- SQ Score with progress bar
- "Take AI Assessment" button
- Note: "Scores based on resume analysis & profile integrations"

**FR-PROF-003: Profile Integrations Section**
- **Priority:** P1 (High)
- **Status:** ✅ Implemented (Basic)

**Integrations:**
1. LinkedIn
2. GitHub
3. Behance
4. Canva
5. Portfolio Website

**Features:**
- URL input for each platform
- Connection status indicators
- Validation on URLs
- Save button
- Profile data sync (future)

**FR-PROF-004: Privacy Settings**
- **Priority:** P2 (Medium)
- **Status:** ⏳ Pending

**Options:**
- Profile visibility (public, employers only, private)
- Show email to employers (yes/no)
- Allow direct messages (yes/no)
- Job recommendations (yes/no)
- Email notifications (yes/no)

### 3.9 Billing & Subscriptions

**FR-BILL-001: Current Plan Display**
- **Priority:** P0 (Critical)
- **Status:** ✅ Implemented

**Information Shown:**
- Subscription tier name
- Monthly price
- Status (active, cancelled, past due)
- Next billing date
- "Change Plan" button
- "Cancel Subscription" button (for paid plans)

**FR-BILL-002: Payment Method Management**
- **Priority:** P0 (Critical)
- **Status:** ✅ Implemented (UI Only)

**Features:**
- List all saved payment methods
- Card information (last 4 digits, brand)
- Default payment method indicator
- Actions:
  - Set as default
  - Remove card
  - Add new payment method

**FR-BILL-003: Add Payment Method Modal**
- **Priority:** P0 (Critical)
- **Status:** 🔄 UI Ready, Stripe Integration Pending

**Requirements:**
- Modal interface
- Stripe Elements integration (production)
- Fields:
  - Card number
  - Expiry date (MM/YY)
  - CVC
  - Cardholder name
  - Billing ZIP code
- PCI DSS compliant
- Save button
- Cancel button

**FR-BILL-004: Billing History**
- **Priority:** P1 (High)
- **Status:** ⏳ Pending

**Table Columns:**
- Date
- Description
- Amount
- Status (paid, pending, failed)
- Invoice PDF download link

**FR-BILL-005: Plan Upgrade/Downgrade**
- **Priority:** P1 (High)
- **Status:** ⏳ Pending

**Features:**
- View available plans
- Compare features
- Upgrade: Immediate activation, prorated charge
- Downgrade: Takes effect next billing cycle
- Confirmation modal
- Email notification

### 3.10 Access Control & Permissions

**FR-ACCESS-001: Access Control Matrix**
- **Priority:** P0 (Critical)
- **Status:** ✅ Implemented

| Feature/Page | Guest | Job Seeker | Employer | Agency |
|--------------|-------|------------|----------|--------|
| **Landing Page** | ✅ Full | ✅ Full | ✅ Full | ✅ Full |
| **Registration** | ✅ Full | N/A | N/A | N/A |
| **Login** | ✅ Full | N/A | N/A | N/A |
| **Dashboard** | ❌ | ✅ Job Seeker | ✅ Employer | ✅ Agency |
| **Profile** | ❌ | ✅ View/Edit | ✅ View/Edit | ✅ View/Edit |
| **Billing** | ❌ | ✅ Own | ✅ Own | ✅ Own |
| **Integrations** | ❌ | ✅ Full | ✅ Full | ✅ Full |
| **Browse Jobs** | ✅ First 5 | ✅ All | ✅ All | ✅ All |
| **Post Job** | ❌ | ❌ | ✅ Full | ✅ Full |
| **Apply to Job** | ❌ | ✅ Full | ❌ | ❌ |
| **View Applicants** | ❌ | ❌ | ✅ Own Jobs | ✅ Own Jobs |
| **Browse Candidates** | ✅ First 5 | ❌ | ✅ All | ✅ All |
| **Browse Employers** | ✅ First 5 | ✅ All | ✅ All | ✅ All |
| **Contact Candidate** | ❌ | ❌ | ✅ Full | ✅ Full |
| **Manage Jobs** | ❌ | ❌ | ✅ Own | ✅ Own |
| **Renew Job** | ❌ | ❌ | ✅ Own | ✅ Own |

**FR-ACCESS-002: Guest User Limitations**
- **Priority:** P0 (Critical)
- **Status:** ✅ Implemented

**Limitations:**
- Can view first 5 items on:
  - Job listings
  - Candidate directory
  - Employer directory
- Remaining items blurred with CSS
- Access gate displayed with message
- CTAs to login or register
- Encourages conversion

**FR-ACCESS-003: Role Verification**
- **Priority:** P0 (Critical)
- **Status:** ✅ Implemented

**Verification Methods:**
```php
// Check if user is logged in
if (!is_user_logged_in()) {
    // Redirect to login or show error
}

// Check account type
$profile = get_user_profile($user_id);
if ($profile->account_type !== 'employer') {
    // Show access denied message
}

// Check subscription tier
if ($profile->subscription_tier === 'free' && requires_paid_feature()) {
    // Show upgrade prompt
}
```

### 3.11 AI Coins System

**FR-COINS-001: Coin Allocation**
- **Priority:** P1 (High)
- **Status:** 🔄 Basic Implemented

**Initial Allocation by Tier:**
- Free: 0 coins
- Startup: 50 coins per month
- Enterprise: 200 coins per month

**Additional Coins:**
- Purchase packs (future):
  - 10 coins: $50
  - 50 coins: $200
  - 100 coins: $350
  - 500 coins: $1,500

**FR-COINS-002: Coin Usage**
- **Priority:** P1 (High)
- **Status:** ✅ Implemented

**Actions That Cost Coins:**
| Action | Cost | User Type |
|--------|------|-----------|
| Post Job | 1 coin | Employer, Agency |
| Renew Job | 1 coin | Employer, Agency |
| Featured Job | 3 coins | Employer, Agency |
| Boost Profile | 2 coins | Job Seeker |
| Premium Job Application | 1 coin | Job Seeker |
| View Contact Info | 0.5 coins | Employer, Agency |

**FR-COINS-003: Coin Tracking**
- **Priority:** P1 (High)
- **Status:** ⏳ Pending

**Features:**
- Current balance displayed in dashboard
- Transaction history
- Low balance warnings
- Auto-purchase option (when balance < 5)
- Email notifications on transactions

**FR-COINS-004: Refund Policy**
- **Priority:** P2 (Medium)
- **Status:** ⏳ Pending

**Rules:**
- Job posting refunded if removed within 24 hours
- No refund for completed actions
- Disputed charges reviewed manually
- Refunds processed to original payment method


---

## 4. Complete User Workflows

### 4.1 Job Seeker Journey: Registration → Job Application

```
════════════════════════════════════════════════════════════════════════
                    COMPLETE JOB SEEKER WORKFLOW
════════════════════════════════════════════════════════════════════════

┌─ STEP 1: Discovery & Landing ────────────────────────────────────────┐
│                                                                       │
│  User searches Google: "AI-powered job portal"                       │
│            │                                                          │
│            ▼                                                          │
│  Lands on hiresmart.startupstreet.in                                │
│            │                                                          │
│            ▼                                                          │
│  Browses Landing Page:                                               │
│  ├─ Reads hero: "Transform Your Hiring with AI"                     │
│  ├─ Views features: Neural AI, Success Prediction, etc.             │
│  ├─ Checks pricing: Decides on Free tier                            │
│  └─ Clicks "Get Started Free" button                                 │
│                                                                       │
└───────────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─ STEP 2: Registration ───────────────────────────────────────────────┐
│                                                                       │
│  Redirects to: app-hiresmart.startupstreet.in/register              │
│            │                                                          │
│            ▼                                                          │
│  ┌──────────────────────────────────────┐                           │
│  │ Registration Form                    │                           │
│  ├──────────────────────────────────────┤                           │
│  │ Full Name: John Smith                │                           │
│  │ Email: john@email.com                │                           │
│  │ Password: ••••••••                   │                           │
│  │                                      │                           │
│  │ Select Account Type:                 │                           │
│  │ ● Job Seeker  ○ Employer  ○ Agency  │  ← Selects Job Seeker    │
│  │                                      │                           │
│  │ Choose Subscription:                 │                           │
│  │  ┌──────────────┐                   │                           │
│  │  │ FREE - $0/mo │ ✓ Selected        │  ← Chooses Free          │
│  │  │ 5 apps/month │                    │                           │
│  │  └──────────────┘                   │                           │
│  │                                      │                           │
│  │ ☑ I agree to Terms & Conditions      │                           │
│  │                                      │                           │
│  │  [Create Account]  [Sign In]        │                           │
│  └──────────────────────────────────────┘                           │
│            │                                                          │
│            ▼                                                          │
│  Submits form via AJAX                                               │
│            │                                                          │
│            ▼                                                          │
│  Backend Processing:                                                 │
│  ├─ Creates WordPress user                                           │
│  ├─ Creates HireSmart profile (account_type: job_seeker)           │
│  ├─ Creates subscription record (tier: free, status: active)        │
│  └─ Sends welcome email                                              │
│            │                                                          │
│            ▼                                                          │
│  Success! Redirects to: /dashboard                                   │
│           (Free tier → direct to dashboard)                          │
│           (Paid tier → /billing then dashboard)                      │
│                                                                       │
└───────────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─ STEP 3: First-Time Dashboard Experience ────────────────────────────┐
│                                                                       │
│  URL: app-hiresmart.startupstreet.in/dashboard                      │
│            │                                                          │
│            ▼                                                          │
│  ┌────────────────────────────────────────────────────┐             │
│  │         Job Seeker Dashboard                       │             │
│  ├────────────────────────────────────────────────────┤             │
│  │  Welcome, John! 👋                                 │             │
│  │                                                    │             │
│  │  ┌──────────┐ ┌──────────┐ ┌──────────┐          │             │
│  │  │Applications│ │  Profile │ │Interviews│          │             │
│  │  │    0      │ │  Views   │ │    0     │          │             │
│  │  └──────────┘ └──────────┘ └──────────┘          │             │
│  │                                                    │             │
│  │  Recent Activity:                                 │             │
│  │  ⚡ Account created - Start by taking AI assessment│             │
│  │                                                    │             │
│  │  AI Profile Insights:                             │             │
│  │  ⚠️ Complete your profile to get matched          │             │
│  │  [Take AI Assessment]                             │             │
│  └────────────────────────────────────────────────────┘             │
│            │                                                          │
│            ▼                                                          │
│  User clicks "Take AI Assessment"                                    │
│                                                                       │
└───────────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─ STEP 4: AI Assessment ──────────────────────────────────────────────┐
│                                                                       │
│  AI Assessment Modal Opens                                           │
│            │                                                          │
│            ▼                                                          │
│  ┌──────────────────────────────────────┐                           │
│  │      AI Profiling Assessment         │                           │
│  ├──────────────────────────────────────┤                           │
│  │                                      │                           │
│  │ 1. Logical Reasoning (IQ)           │                           │
│  │    [────────●──] 7/10                │  ← User slides to 7      │
│  │                                      │                           │
│  │ 2. Problem Solving (IQ)             │                           │
│  │    [──────────●] 8/10                │  ← User slides to 8      │
│  │                                      │                           │
│  │ 3. Emotional Awareness (EQ)         │                           │
│  │    [─────●─────] 6/10                │  ← User slides to 6      │
│  │                                      │                           │
│  │ 4. Empathy (EQ)                     │                           │
│  │    [───────●───] 7/10                │  ← User slides to 7      │
│  │                                      │                           │
│  │ 5. Communication Skills (SQ)        │                           │
│  │    [────────●──] 8/10                │  ← User slides to 8      │
│  │                                      │                           │
│  │ 6. Teamwork Ability (SQ)            │                           │
│  │    [────────●──] 7/10                │  ← User slides to 7      │
│  │                                      │                           │
│  │         [Submit Assessment]          │                           │
│  └──────────────────────────────────────┘                           │
│            │                                                          │
│            ▼                                                          │
│  Submits via AJAX → Scores calculated:                              │
│  ├─ IQ = 100 + (7×2) + (8×2) = 130                                  │
│  ├─ EQ = 50 + (6×5) + (7×5) = 115 → capped at 100                  │
│  └─ SQ = 50 + (8×5) + (7×5) = 125 → capped at 100                  │
│            │                                                          │
│            ▼                                                          │
│  Scores saved to database                                            │
│  Modal shows success message                                         │
│  Dashboard refreshes with new scores                                 │
│                                                                       │
└───────────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─ STEP 5: Profile Enhancement ────────────────────────────────────────┐
│                                                                       │
│  User navigates to: /profile                                         │
│            │                                                          │
│            ▼                                                          │
│  ┌────────────────────────────────────────┐                         │
│  │        Profile Management              │                         │
│  ├────────────────────────────────────────┤                         │
│  │ Personal Information:                  │                         │
│  │ Name: John Smith                       │                         │
│  │ Email: john@email.com                  │                         │
│  │                                        │                         │
│  │ AI Scores:                             │                         │
│  │ IQ: 130 [████████░░] 87%              │                         │
│  │ EQ: 100 [██████████] 100%             │                         │
│  │ SQ: 100 [██████████] 100%             │                         │
│  │                                        │                         │
│  │ ⓘ Scores based on resume & integrations│                         │
│  └────────────────────────────────────────┘                         │
│            │                                                          │
│            ▼                                                          │
│  User navigates to: /integrations                                    │
│            │                                                          │
│            ▼                                                          │
│  ┌────────────────────────────────────────┐                         │
│  │      Profile Integrations              │                         │
│  ├────────────────────────────────────────┤                         │
│  │ LinkedIn:                              │                         │
│  │ [https://linkedin.com/in/johnsmith]    │ ← Enters URL            │
│  │                                        │                         │
│  │ GitHub:                                │                         │
│  │ [https://github.com/johnsmith]         │ ← Enters URL            │
│  │                                        │                         │
│  │ Portfolio:                             │                         │
│  │ [https://johnsmith.com]                │ ← Enters URL            │
│  │                                        │                         │
│  │         [Save Integrations]            │                         │
│  └────────────────────────────────────────┘                         │
│            │                                                          │
│            ▼                                                          │
│  Integrations saved → Profile sync: 75% complete                     │
│  AI scores slightly elevated due to integrations                     │
│                                                                       │
└───────────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─ STEP 6: Browse Jobs ────────────────────────────────────────────────┐
│                                                                       │
│  User navigates to: /jobs                                            │
│            │                                                          │
│            ▼                                                          │
│  ┌────────────────────────────────────────────────────┐             │
│  │           Browse Jobs                              │             │
│  ├────────────────────────────────────────────────────┤             │
│  │ Search: [software engineer________] [Search]       │             │
│  │ Filter: [Full-time ▼] [Remote ▼] [Apply]          │             │
│  │                                                    │             │
│  │ ┌────────────────────────────────────┐            │             │
│  │ │ Senior Software Engineer           │            │             │
│  │ │ TechCorp Inc.                      │            │             │
│  │ │ 📍 Remote | 💼 Full-time           │            │             │
│  │ │ 💰 $120,000 - $150,000             │            │             │
│  │ │ Skills: React, Node.js, AWS        │            │             │
│  │ │ Posted 3 days ago | 12 applicants  │            │             │
│  │ │ [View Details] [Apply Now]         │ ← Clicks Apply           │
│  │ └────────────────────────────────────┘            │             │
│  │                                                    │             │
│  │ [More jobs listed...]                             │             │
│  └────────────────────────────────────────────────────┘             │
│                                                                       │
└───────────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─ STEP 7: Job Application ────────────────────────────────────────────┐
│                                                                       │
│  Application Modal Opens                                             │
│            │                                                          │
│            ▼                                                          │
│  ┌────────────────────────────────────────┐                         │
│  │    Apply to: Senior Software Engineer  │                         │
│  ├────────────────────────────────────────┤                         │
│  │                                        │                         │
│  │ Cover Letter:                          │                         │
│  │ ┌────────────────────────────────────┐ │                         │
│  │ │ I am excited to apply for this     │ │                         │
│  │ │ position because... [500 chars]    │ │  ← User writes letter  │
│  │ └────────────────────────────────────┘ │                         │
│  │                                        │                         │
│  │ Resume:                                │                         │
│  │ [📄 John_Smith_Resume.pdf] [Upload]   │  ← User uploads resume  │
│  │                                        │                         │
│  │ ⚡ Your AI Scores will be shared:      │                         │
│  │    IQ: 130 | EQ: 100 | SQ: 100        │                         │
│  │                                        │                         │
│  │ [Cancel] [Submit Application]          │                         │
│  └────────────────────────────────────────┘                         │
│            │                                                          │
│            ▼                                                          │
│  Submits via AJAX                                                    │
│            │                                                          │
│            ▼                                                          │
│  Backend Processing:                                                 │
│  ├─ Validates: not duplicate application                            │
│  ├─ Creates application record (status: pending)                    │
│  ├─ Increments job applications_count                               │
│  ├─ Sends email to job seeker (confirmation)                        │
│  └─ Sends email to employer (new applicant)                         │
│            │                                                          │
│            ▼                                                          │
│  Success! "Application submitted successfully"                       │
│  Dashboard updated: Applications Sent = 1                            │
│                                                                       │
└───────────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─ STEP 8: Track Application ──────────────────────────────────────────┐
│                                                                       │
│  User returns to dashboard                                           │
│            │                                                          │
│            ▼                                                          │
│  Dashboard now shows:                                                │
│  ┌──────────┐                                                        │
│  │Applications│                                                      │
│  │    1      │  ← Updated                                            │
│  └──────────┘                                                        │
│                                                                       │
│  Recent Activity:                                                    │
│  ✅ Applied to Senior Software Engineer at TechCorp Inc.            │
│                                                                       │
│  Later: Employer reviews application...                              │
│         Status changes: Pending → Interview Scheduled                │
│                                                                       │
│  Notification appears in dashboard:                                  │
│  🎉 Interview scheduled for Senior Software Engineer!               │
│     Date: June 15, 2026 at 10:00 AM                                 │
│                                                                       │
└───────────────────────────────────────────────────────────────────────┘

════════════════════════════════════════════════════════════════════════
                         WORKFLOW COMPLETE
════════════════════════════════════════════════════════════════════════
```

### 4.2 Employer Journey: Registration → Hiring Candidate

```
════════════════════════════════════════════════════════════════════════
                    COMPLETE EMPLOYER WORKFLOW
════════════════════════════════════════════════════════════════════════

┌─ STEP 1: Discovery & Registration ───────────────────────────────────┐
│                                                                       │
│  Company searches: "AI recruitment platform"                         │
│            │                                                          │
│            ▼                                                          │
│  Lands on hiresmart.startupstreet.in                                │
│            │                                                          │
│            ▼                                                          │
│  Reviews pricing: Chooses Startup ($299/mo)                          │
│            │                                                          │
│            ▼                                                          │
│  Clicks "Get Started" → Redirects to /register                      │
│            │                                                          │
│            ▼                                                          │
│  Registration Form:                                                  │
│  ├─ Name: Jane Doe                                                   │
│  ├─ Email: jane@techcorp.com                                         │
│  ├─ Password: ••••••••                                               │
│  ├─ Account Type: ● Employer  ○ Job Seeker  ○ Agency               │
│  └─ Subscription: ● Startup ($299/mo)  ○ Free  ○ Enterprise        │
│            │                                                          │
│            ▼                                                          │
│  Submits → Account created → Redirects to /billing                   │
│                                                                       │
└───────────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─ STEP 2: Payment Setup ──────────────────────────────────────────────┐
│                                                                       │
│  URL: app-hiresmart.startupstreet.in/billing                        │
│            │                                                          │
│            ▼                                                          │
│  ⚠️ Add payment method to activate subscription                      │
│            │                                                          │
│            ▼                                                          │
│  Clicks "Add Payment Method"                                         │
│            │                                                          │
│            ▼                                                          │
│  Payment Modal (Stripe Elements):                                    │
│  ┌────────────────────────────────┐                                 │
│  │ Card Number:                   │                                 │
│  │ [4242 4242 4242 4242]          │                                 │
│  │                                │                                 │
│  │ Expiry: [12/25]  CVC: [123]    │                                 │
│  │                                │                                 │
│  │ Name: Jane Doe                 │                                 │
│  │ ZIP: 94102                     │                                 │
│  │                                │                                 │
│  │ [Save Payment Method]          │                                 │
│  └────────────────────────────────┘                                 │
│            │                                                          │
│            ▼                                                          │
│  Payment method saved → Subscription activated                       │
│  Redirects to /dashboard                                             │
│                                                                       │
└───────────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─ STEP 3: Employer Dashboard ─────────────────────────────────────────┐
│                                                                       │
│  ┌────────────────────────────────────────────────────┐             │
│  │       Employer Dashboard                           │             │
│  ├────────────────────────────────────────────────────┤             │
│  │  ┌──────────┐ ┌──────────┐ ┌──────────┐          │             │
│  │  │  Active  │ │ Total    │ │Interviews│          │             │
│  │  │  Jobs: 0 │ │Applicants│ │    0     │          │             │
│  │  └──────────┘ └──────────┘ └──────────┘          │             │
│  │                                                    │             │
│  │  Quick Actions:                                   │             │
│  │  [Post New Job] [View Applicants]                │             │
│  │                                                    │             │
│  │  💡 Get started by posting your first job         │             │
│  │     You have 50 AI Coins (Startup tier)          │             │
│  └────────────────────────────────────────────────────┘             │
│            │                                                          │
│            ▼                                                          │
│  Clicks "Post New Job"                                               │
│                                                                       │
└───────────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─ STEP 4: Post Job ───────────────────────────────────────────────────┐
│                                                                       │
│  URL: app-hiresmart.startupstreet.in/post-job                       │
│            │                                                          │
│            ▼                                                          │
│  ┌──────────────────────────────────────────┐                       │
│  │         Post a New Job                   │                       │
│  ├──────────────────────────────────────────┤                       │
│  │ Job Title:                               │                       │
│  │ [Senior Software Engineer]               │                       │
│  │                                          │                       │
│  │ Job Type: [Full-time ▼]                 │                       │
│  │ Experience: [Senior ▼]                   │                       │
│  │ Location: [Remote]                       │                       │
│  │                                          │                       │
│  │ Salary Range:                            │                       │
│  │ Min: [$120,000] Max: [$150,000]         │                       │
│  │                                          │                       │
│  │ Description:                             │                       │
│  │ ┌──────────────────────────────────────┐ │                       │
│  │ │ We are seeking an experienced...    │ │                       │
│  │ │ [Rich text editor with 500 words]   │ │                       │
│  │ └──────────────────────────────────────┘ │                       │
│  │                                          │                       │
│  │ Requirements:                            │                       │
│  │ ┌──────────────────────────────────────┐ │                       │
│  │ │ • 5+ years of experience            │ │                       │
│  │ │ • Proficient in React, Node.js      │ │                       │
│  │ └──────────────────────────────────────┘ │                       │
│  │                                          │                       │
│  │ Required Skills:                         │                       │
│  │ [React, Node.js, AWS, Docker]           │                       │
│  │                                          │                       │
│  │ ℹ️ Posting Details:                      │                       │
│  │ • Duration: 14 days (renewable)         │                       │
│  │ • Cost: 1 AI Coin                       │                       │
│  │ • Visible: Immediately to all           │                       │
│  │                                          │                       │
│  │ [Cancel] [Post Job (1 coin)]            │                       │
│  └──────────────────────────────────────────┘                       │
│            │                                                          │
│            ▼                                                          │
│  Submits → 1 AI Coin deducted                                        │
│  Job created (status: active, expires: +14 days)                     │
│  Success message: "Job posted successfully!"                         │
│  Redirects to /jobs (employer view)                                  │
│                                                                       │
└───────────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─ STEP 5: Manage Posted Job ──────────────────────────────────────────┐
│                                                                       │
│  Employer Dashboard updated:                                         │
│  ┌──────────┐                                                        │
│  │  Active  │                                                        │
│  │  Jobs: 1 │  ← Updated                                             │
│  └──────────┘                                                        │
│                                                                       │
│  Job appears on /jobs page (visible to all users)                    │
│                                                                       │
│  Employer can view job analytics:                                    │
│  ├─ Views: 0 → 15 → 45 (grows over time)                           │
│  ├─ Applications: 0 → 3 → 12 (candidates apply)                    │
│  └─ Days until expiry: 14 → 13 → 12...                             │
│                                                                       │
└───────────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─ STEP 6: Browse Candidates ──────────────────────────────────────────┐
│                                                                       │
│  While waiting for applications, employer can proactively search     │
│            │                                                          │
│            ▼                                                          │
│  Navigates to: /candidates                                           │
│            │                                                          │
│            ▼                                                          │
│  ┌────────────────────────────────────────────────────┐             │
│  │          Browse Candidates                         │             │
│  ├────────────────────────────────────────────────────┤             │
│  │ Search: [software engineer______] [Search]         │             │
│  │                                                    │             │
│  │ ┌────────────────────────────────────┐            │             │
│  │ │ 👤 John Smith                      │            │             │
│  │ │ john@email.com                     │            │             │
│  │ │                                    │            │             │
│  │ │ AI Scores:                         │            │             │
│  │ │ [IQ: 130] [EQ: 100] [SQ: 100]     │            │             │
│  │ │                                    │            │             │
│  │ │ Profile Sync: 75% ████████░░       │            │             │
│  │ │ ✓ LinkedIn  ✓ GitHub  ✓ Portfolio │            │             │
│  │ │                                    │            │             │
│  │ │ [Contact Candidate]                │  ← Can reach out         │
│  │ └────────────────────────────────────┘            │             │
│  │                                                    │             │
│  │ [More candidates...]                              │             │
│  └────────────────────────────────────────────────────┘             │
│                                                                       │
└───────────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─ STEP 7: Review Applications ────────────────────────────────────────┐
│                                                                       │
│  Email notification: "You have 3 new applicants"                     │
│            │                                                          │
│            ▼                                                          │
│  Dashboard shows: Total Applicants: 3                                │
│            │                                                          │
│            ▼                                                          │
│  Clicks "View Applicants"                                            │
│            │                                                          │
│            ▼                                                          │
│  ┌────────────────────────────────────────────────────┐             │
│  │  Applications for: Senior Software Engineer        │             │
│  ├────────────────────────────────────────────────────┤             │
│  │                                                    │             │
│  │ ┌────────────────────────────────────┐            │             │
│  │ │ 👤 John Smith                      │            │             │
│  │ │ Applied 2 hours ago                │            │             │
│  │ │                                    │            │             │
│  │ │ AI Match: 92% ████████████         │            │             │
│  │ │ IQ: 130 | EQ: 100 | SQ: 100        │            │             │
│  │ │                                    │            │             │
│  │ │ Cover Letter:                      │            │             │
│  │ │ "I am excited to apply because..." │            │             │
│  │ │                                    │            │             │
│  │ │ 📄 Resume: John_Smith_Resume.pdf   │            │             │
│  │ │                                    │            │             │
│  │ │ Status: [Pending ▼]                │            │             │
│  │ │ ├─ Reviewed                        │            │             │
│  │ │ ├─ Interview Scheduled             │  ← Employer selects      │
│  │ │ ├─ Offer Extended                  │            │             │
│  │ │ └─ Rejected                        │            │             │
│  │ │                                    │            │             │
│  │ │ [Contact] [View Profile]           │            │             │
│  │ └────────────────────────────────────┘            │             │
│  │                                                    │             │
│  │ [2 more applicants...]                            │             │
│  └────────────────────────────────────────────────────┘             │
│            │                                                          │
│            ▼                                                          │
│  Employer changes status to "Interview Scheduled"                    │
│  Email sent to John: "Interview scheduled..."                        │
│  Notification appears in John's dashboard                            │
│                                                                       │
└───────────────────────────────────────────────────────────────────────┘

════════════════════════════════════════════════════════════════════════
                         WORKFLOW COMPLETE
════════════════════════════════════════════════════════════════════════
```

### 4.3 Social Login Flow (Google OAuth Example)

```
══════════════════════════════════════════════════════════════════
                   SOCIAL LOGIN WORKFLOW
══════════════════════════════════════════════════════════════════

┌─ Existing User Login ─────────────────────────────────────────┐
│                                                                │
│  User on: app-hiresmart.startupstreet.in/login               │
│            │                                                   │
│            ▼                                                   │
│  Clicks "Continue with Google"                                │
│            │                                                   │
│            ▼                                                   │
│  Redirects to Google OAuth consent screen:                    │
│  https://accounts.google.com/o/oauth2/v2/auth?               │
│    client_id=YOUR_CLIENT_ID                                   │
│    redirect_uri=https://app-hiresmart.../oauth/google/callback│
│    response_type=code                                         │
│    scope=profile+email                                        │
│            │                                                   │
│            ▼                                                   │
│  User sees:                                                    │
│  ┌───────────────────────────────────┐                       │
│  │  Sign in with Google              │                       │
│  ├───────────────────────────────────┤                       │
│  │  HireSmart wants to:              │                       │
│  │  ✓ See your email address         │                       │
│  │  ✓ See your personal info         │                       │
│  │                                   │                       │
│  │  [Cancel] [Continue]              │                       │
│  └───────────────────────────────────┘                       │
│            │                                                   │
│            ▼                                                   │
│  User clicks "Continue"                                       │
│            │                                                   │
│            ▼                                                   │
│  Google redirects back with auth code:                        │
│  https://app-hiresmart.../oauth/google/callback?code=ABC123  │
│            │                                                   │
│            ▼                                                   │
│  Backend exchanges code for access token                      │
│  ┌────────────────────────────────────┐                      │
│  │ POST https://oauth2.googleapis.com │                      │
│  │ /token                             │                      │
│  │ {                                  │                      │
│  │   "code": "ABC123",                │                      │
│  │   "client_id": "...",              │                      │
│  │   "client_secret": "...",          │                      │
│  │   "grant_type": "authorization_..."│                      │
│  │ }                                  │                      │
│  └────────────────────────────────────┘                      │
│            │                                                   │
│            ▼                                                   │
│  Receives access token                                        │
│            │                                                   │
│            ▼                                                   │
│  Fetches user profile:                                        │
│  ┌────────────────────────────────────┐                      │
│  │ GET https://www.googleapis.com     │                      │
│  │ /oauth2/v1/userinfo                │                      │
│  │ Authorization: Bearer {token}      │                      │
│  └────────────────────────────────────┘                      │
│            │                                                   │
│            ▼                                                   │
│  Receives:                                                    │
│  {                                                            │
│    "email": "john@gmail.com",                                │
│    "name": "John Smith",                                     │
│    "picture": "https://..."                                  │
│  }                                                            │
│            │                                                   │
│            ▼                                                   │
│  Check if user exists in database:                           │
│  SELECT * FROM wp_users WHERE user_email = 'john@gmail.com'  │
│            │                                                   │
│            ├─ User exists ──────────┐                         │
│            │                        │                         │
│            │                        ▼                         │
│            │                   Log user in                    │
│            │                   Set auth cookies               │
│            │                   Redirect to /dashboard         │
│            │                                                   │
│            └─ User does not exist ─> Go to New User Flow     │
│                                                                │
└────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─ New User Account Setup ──────────────────────────────────────┐
│                                                                │
│  Email not found in database                                  │
│            │                                                   │
│            ▼                                                   │
│  Create basic WordPress user:                                 │
│  ├─ Email: john@gmail.com                                     │
│  ├─ Name: John Smith                                          │
│  ├─ Username: john.smith (generated)                          │
│  └─ Password: random_secure_password (auto-generated)         │
│            │                                                   │
│            ▼                                                   │
│  Redirect to account setup page:                              │
│  /register?social=google&name=John+Smith&email=john@gmail.com │
│            │                                                   │
│            ▼                                                   │
│  ┌──────────────────────────────────────┐                    │
│  │  Complete Your Profile               │                    │
│  ├──────────────────────────────────────┤                    │
│  │  Welcome, John Smith! 👋             │                    │
│  │  Email: john@gmail.com               │                    │
│  │                                      │                    │
│  │  Choose your account type:           │                    │
│  │  ○ Job Seeker                        │                    │
│  │  ○ Employer                          │                    │
│  │  ○ Agency                            │                    │
│  │                                      │                    │
│  │  Select subscription:                │                    │
│  │  [Free] [Startup] [Enterprise]       │                    │
│  │                                      │                    │
│  │  [Complete Setup]                    │                    │
│  └──────────────────────────────────────┘                    │
│            │                                                   │
│            ▼                                                   │
│  User selects: Job Seeker + Free                             │
│            │                                                   │
│            ▼                                                   │
│  Creates HireSmart profile                                    │
│  Creates subscription record                                  │
│            │                                                   │
│            ▼                                                   │
│  Redirects to /dashboard                                      │
│  Account setup complete!                                      │
│                                                                │
└────────────────────────────────────────────────────────────────┘

══════════════════════════════════════════════════════════════════
                      WORKFLOW COMPLETE
══════════════════════════════════════════════════════════════════
```

### 4.4 Profile Sync & Score Elevation Flow

```
═══════════════════════════════════════════════════════════════════
            PROFILE SYNC & AI SCORE ELEVATION
═══════════════════════════════════════════════════════════════════

Initial State (Day 1):
┌─────────────────────────────────────┐
│ User: John Smith (Job Seeker)      │
├─────────────────────────────────────┤
│ Profile Sync: 0% ░░░░░░░░░░         │
│                                     │
│ AI Scores (Baseline):               │
│ IQ: 120 (from assessment only)      │
│ EQ: 85  (from assessment only)      │
│ SQ: 80  (from assessment only)      │
│                                     │
│ Integration Status:                 │
│ ❌ LinkedIn: Not Connected          │
│ ❌ GitHub: Not Connected            │
│ ❌ Behance: Not Connected           │
│ ❌ Portfolio: Not Connected         │
│                                     │
│ Visibility: Low (70/100)            │
│ ⚠️ Complete integrations to boost   │
│    visibility and score accuracy    │
└─────────────────────────────────────┘

Day 2: User Connects LinkedIn
┌─────────────────────────────────────┐
│ User navigates to /integrations     │
│ Enters: https://linkedin.com/in/... │
│ Clicks "Save"                       │
└─────────────────────────────────────┘
                │
                ▼
        Backend Processing:
        ┌────────────────────────────┐
        │ 1. Validate URL format     │
        │ 2. Store in database       │
        │ 3. Calculate sync %        │
        │    → 1/4 = 25%            │
        │ 4. Elevate scores:         │
        │    EQ: 85 → 90 (+5)       │
        │    (professional exp)      │
        └────────────────────────────┘
                │
                ▼
Updated State (Day 2):
┌─────────────────────────────────────┐
│ Profile Sync: 25% ███░░░░░░░        │
│                                     │
│ AI Scores (Elevated):               │
│ IQ: 120 (no change)                 │
│ EQ: 90  (+5 from LinkedIn) ↑        │
│ SQ: 80  (no change)                 │
│                                     │
│ Integration Status:                 │
│ ✅ LinkedIn: Connected              │
│ ❌ GitHub: Not Connected            │
│ ❌ Behance: Not Connected           │
│ ❌ Portfolio: Not Connected         │
│                                     │
│ Visibility: Medium (80/100)         │
└─────────────────────────────────────┘

Day 5: User Connects GitHub
┌─────────────────────────────────────┐
│ Enters: https://github.com/...     │
│ Clicks "Save"                       │
└─────────────────────────────────────┘
                │
                ▼
        Backend Processing:
        ┌────────────────────────────┐
        │ 1. Validate URL            │
        │ 2. Store in database       │
        │ 3. Calculate sync %        │
        │    → 2/4 = 50%            │
        │ 4. Elevate scores:         │
        │    IQ: 120 → 128 (+8)     │
        │    (technical skills)      │
        └────────────────────────────┘
                │
                ▼
Updated State (Day 5):
┌─────────────────────────────────────┐
│ Profile Sync: 50% ██████░░░░        │
│                                     │
│ AI Scores (Elevated):               │
│ IQ: 128 (+8 from GitHub) ↑          │
│ EQ: 90  (from LinkedIn)             │
│ SQ: 80  (no change)                 │
│                                     │
│ Integration Status:                 │
│ ✅ LinkedIn: Connected              │
│ ✅ GitHub: Connected                │
│ ❌ Behance: Not Connected           │
│ ❌ Portfolio: Not Connected         │
│                                     │
│ Visibility: Good (85/100)           │
└─────────────────────────────────────┘

Day 7: User Adds Behance Portfolio
┌─────────────────────────────────────┐
│ Enters: https://behance.net/...    │
│ Clicks "Save"                       │
└─────────────────────────────────────┘
                │
                ▼
        Backend Processing:
        ┌────────────────────────────┐
        │ 1. Validate URL            │
        │ 2. Store in database       │
        │ 3. Calculate sync %        │
        │    → 3/4 = 75%            │
        │ 4. Elevate scores:         │
        │    SQ: 80 → 88 (+8)       │
        │    (creative work)         │
        └────────────────────────────┘
                │
                ▼
Updated State (Day 7):
┌─────────────────────────────────────┐
│ Profile Sync: 75% █████████░        │
│                                     │
│ AI Scores (Elevated):               │
│ IQ: 128 (from GitHub)               │
│ EQ: 90  (from LinkedIn)             │
│ SQ: 88  (+8 from Behance) ↑         │
│                                     │
│ Integration Status:                 │
│ ✅ LinkedIn: Connected              │
│ ✅ GitHub: Connected                │
│ ✅ Behance: Connected               │
│ ❌ Portfolio: Not Connected         │
│                                     │
│ Visibility: Very Good (92/100)      │
└─────────────────────────────────────┘

Day 10: User Adds Personal Portfolio
┌─────────────────────────────────────┐
│ Enters: https://johnsmith.com      │
│ Clicks "Save"                       │
└─────────────────────────────────────┘
                │
                ▼
        Backend Processing:
        ┌────────────────────────────┐
        │ 1. Validate URL            │
        │ 2. Store in database       │
        │ 3. Calculate sync %        │
        │    → 4/4 = 100%           │
        │ 4. Bonus elevations:       │
        │    IQ: 128 → 130 (+2)     │
        │    EQ: 90 → 92 (+2)       │
        │    SQ: 88 → 90 (+2)       │
        │    (complete profile)      │
        └────────────────────────────┘
                │
                ▼
Final State (Day 10):
┌─────────────────────────────────────┐
│ Profile Sync: 100% ██████████ 🎉   │
│                                     │
│ AI Scores (Optimized):              │
│ IQ: 130 (fully validated) ★         │
│ EQ: 92  (fully validated) ★         │
│ SQ: 90  (fully validated) ★         │
│                                     │
│ Integration Status:                 │
│ ✅ LinkedIn: Connected              │
│ ✅ GitHub: Connected                │
│ ✅ Behance: Connected               │
│ ✅ Portfolio: Connected             │
│                                     │
│ Visibility: Excellent (100/100) ⭐  │
│ 🎉 Profile Complete!                │
│ ✨ Maximum employer visibility      │
└─────────────────────────────────────┘

Impact on Employer View:
┌─────────────────────────────────────┐
│ When employers browse candidates:   │
│                                     │
│ Before (0% sync):                   │
│ ├─ Appears on page 3               │
│ ├─ Basic info visible              │
│ └─ Low confidence scores           │
│                                     │
│ After (100% sync):                  │
│ ├─ Appears on page 1 ⭐            │
│ ├─ Full profile visible            │
│ ├─ Verified scores ✓               │
│ ├─ "Top Candidate" badge           │
│ └─ 3x more profile views           │
└─────────────────────────────────────┘

═══════════════════════════════════════════════════════════════════
                      WORKFLOW COMPLETE
═══════════════════════════════════════════════════════════════════
```


---

## 5. Technical Specifications

### 5.1 Technology Stack

#### 5.1.1 Backend Technologies

| Component | Technology | Version | Purpose |
|-----------|------------|---------|---------|
| **Core Platform** | WordPress | 5.0+ | CMS and application framework |
| **Programming Language** | PHP | 7.4+ | Server-side logic |
| **Database** | MySQL | 5.7+ or MariaDB 10.3+ | Data storage |
| **Session Management** | PHP Sessions + WordPress Cookies | - | User authentication |
| **AJAX Handler** | WordPress AJAX API | - | Asynchronous requests |
| **Template Engine** | PHP + WordPress Templates | - | HTML rendering |

#### 5.1.2 Frontend Technologies

| Component | Technology | Version | Purpose |
|-----------|------------|---------|---------|
| **Markup** | HTML5 | - | Structure |
| **Styling** | CSS3 | - | Presentation |
| **JavaScript** | Vanilla ES6+ | - | Interactivity |
| **Icons** | Font Awesome | 6.x | Icon library |
| **HTTP Client** | jQuery AJAX | 3.x (bundled with WP) | API calls |

#### 5.1.3 Third-Party Integrations

| Service | Purpose | Status |
|---------|---------|--------|
| **Stripe** | Payment processing | 🔄 UI Ready |
| **Google OAuth** | Social login | 🔄 UI Ready |
| **LinkedIn OAuth** | Social login | 🔄 UI Ready |
| **GitHub OAuth** | Social login | 🔄 UI Ready |
| **SendGrid/Mailgun** | Transactional emails | ⏳ Pending |
| **AWS S3** | File storage | ⏳ Pending |

### 5.2 WordPress Theme Structure

```
hiresmart-theme/
├── style.css                    # Theme metadata and styles
├── functions.php                # Theme setup and customizations
├── index.php                    # Landing page template
├── header.php                   # Header with navigation
├── footer.php                   # Footer template
├── page.php                     # Generic page template
├── single.php                   # Single post template (optional)
├── archive.php                  # Archive template (optional)
│
├── js/
│   └── main.js                  # Landing page interactions
│
├── css/
│   └── custom.css               # Additional styles (optional)
│
├── images/
│   ├── logo.svg                 # HireSmart logo
│   └── hero-image.svg           # Hero section graphics
│
├── inc/                         # Theme includes (optional)
│   ├── customizer.php           # WordPress Customizer settings
│   └── template-tags.php        # Custom template functions
│
└── README.md                    # Theme documentation
```

**Key Theme Files:**

**style.css (Theme Header)**
```css
/*
Theme Name: HireSmart
Theme URI: https://github.com/StartupStreet/HireSmart-Website-for-WordPress
Author: StartupStreet
Author URI: https://github.com/StartupStreet
Description: AI-powered job portal landing page theme
Version: 1.0.0
License: GPL v2 or later
Text Domain: hiresmart
*/
```

**functions.php (Theme Setup)**
```php
<?php
// Theme setup
function hiresmart_theme_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'hiresmart'),
        'footer' => __('Footer Menu', 'hiresmart'),
    ));
}
add_action('after_setup_theme', 'hiresmart_theme_setup');

// Enqueue scripts and styles
function hiresmart_scripts() {
    // Main stylesheet
    wp_enqueue_style('hiresmart-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // Font Awesome
    wp_enqueue_style('font-awesome', 
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', 
        array(), '6.4.0'
    );
    
    // Main JavaScript
    wp_enqueue_script('hiresmart-main', 
        get_template_directory_uri() . '/js/main.js', 
        array('jquery'), '1.0.0', true
    );
}
add_action('wp_enqueue_scripts', 'hiresmart_scripts');

// Modify login/register links based on authentication
function hiresmart_auth_links() {
    if (is_user_logged_in()) {
        return '<a href="https://app-hiresmart.startupstreet.in/dashboard" class="nav-link">Dashboard</a>
                <a href="' . wp_logout_url(home_url()) . '" class="cta-button">Logout</a>';
    } else {
        return '<a href="https://app-hiresmart.startupstreet.in/login" class="nav-link">Sign In</a>
                <a href="https://app-hiresmart.startupstreet.in/register" class="cta-button">Get Started</a>';
    }
}
?>
```

### 5.3 WordPress Plugin Structure

```
hiresmart-plugin/
├── hiresmart.php                # Main plugin file
│
├── includes/                    # Core PHP classes
│   ├── class-hiresmart-core.php          # Main functionality, shortcodes
│   ├── class-hiresmart-auth.php          # Authentication & registration
│   ├── class-hiresmart-user.php          # User management & profiles
│   ├── class-hiresmart-subscription.php  # Subscription tiers
│   ├── class-hiresmart-payment.php       # Payment processing
│   ├── class-hiresmart-dashboard.php     # Dashboard rendering
│   ├── class-hiresmart-ai-profiling.php  # AI assessment & scoring
│   └── class-hiresmart-jobs.php          # Job posting & management
│
├── templates/                   # Page templates
│   ├── register.php             # User registration form
│   ├── login.php                # User login form
│   ├── dashboard.php            # Dynamic dashboard router
│   ├── profile.php              # Profile management
│   ├── billing.php              # Billing & subscriptions
│   ├── integrations.php         # Profile integrations
│   ├── post-job.php             # Job posting form
│   ├── job-listings.php         # Browse jobs
│   ├── candidates.php           # Browse candidates
│   └── employers-agencies.php   # Browse employers
│
├── assets/                      # Frontend assets
│   ├── css/
│   │   ├── hiresmart.css        # Main plugin styles
│   │   └── dashboard.css        # Dashboard-specific styles
│   └── js/
│       └── hiresmart.js         # Plugin JavaScript & AJAX
│
├── languages/                   # Translations (i18n)
│   └── hiresmart.pot            # Translation template
│
└── README.md                    # Plugin documentation
```

**Main Plugin File (hiresmart.php)**

```php
<?php
/**
 * Plugin Name: HireSmart - AI-Powered Job Portal
 * Plugin URI: https://github.com/StartupStreet/HireSmart-Website-for-WordPress
 * Description: Complete AI-powered job portal with ATS
 * Version: 1.0.0
 * Author: StartupStreet
 * License: GPL v2 or later
 * Text Domain: hiresmart
 */

// Define plugin constants
define('HIRESMART_VERSION', '1.0.0');
define('HIRESMART_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('HIRESMART_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once HIRESMART_PLUGIN_DIR . 'includes/class-hiresmart-core.php';
require_once HIRESMART_PLUGIN_DIR . 'includes/class-hiresmart-auth.php';
// ... (other includes)

// Initialize plugin
function hiresmart_init() {
    $hiresmart = new HireSmart_Core();
    $hiresmart->init();
}
add_action('plugins_loaded', 'hiresmart_init');

// Activation hook
register_activation_hook(__FILE__, 'hiresmart_activate');
function hiresmart_activate() {
    // Create database tables
    // Create pages
    // Flush rewrite rules
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'hiresmart_deactivate');
function hiresmart_deactivate() {
    flush_rewrite_rules();
}
?>
```

### 5.4 Complete Database Schema

#### Table 1: wp_hiresmart_profiles

```sql
CREATE TABLE wp_hiresmart_profiles (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    user_id bigint(20) NOT NULL,
    account_type varchar(20) NOT NULL,          -- 'job_seeker', 'employer', 'agency'
    subscription_tier varchar(20) NOT NULL,      -- 'free', 'startup', 'enterprise'
    
    -- Profile URLs
    linkedin_url varchar(255) DEFAULT NULL,
    github_url varchar(255) DEFAULT NULL,
    behance_url varchar(255) DEFAULT NULL,
    canva_url varchar(255) DEFAULT NULL,
    portfolio_url varchar(255) DEFAULT NULL,
    
    -- AI Scores
    iq_score int(3) DEFAULT NULL,                -- Intelligence Quotient (70-150)
    eq_score int(3) DEFAULT NULL,                -- Emotional Quotient (30-100)
    sq_score int(3) DEFAULT NULL,                -- Social Quotient (30-100)
    
    -- Additional Data
    profile_data longtext DEFAULT NULL,          -- JSON: additional profile info
    
    -- Timestamps
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (id),
    UNIQUE KEY user_id (user_id),
    KEY account_type (account_type),
    KEY subscription_tier (subscription_tier)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Indexes Explanation:**
- `PRIMARY KEY (id)`: Unique identifier
- `UNIQUE KEY user_id`: One profile per WordPress user
- `KEY account_type`: Fast filtering by user type
- `KEY subscription_tier`: Fast filtering by subscription level

#### Table 2: wp_hiresmart_subscriptions

```sql
CREATE TABLE wp_hiresmart_subscriptions (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    user_id bigint(20) NOT NULL,
    subscription_tier varchar(20) NOT NULL,      -- 'free', 'startup', 'enterprise'
    status varchar(20) NOT NULL,                 -- 'pending', 'active', 'cancelled', 'past_due'
    
    -- Payment Information
    amount decimal(10,2) DEFAULT NULL,           -- Monthly amount in USD
    payment_method varchar(50) DEFAULT NULL,     -- 'card', 'bank_transfer', etc.
    stripe_subscription_id varchar(255) DEFAULT NULL,  -- Stripe subscription ID
    stripe_customer_id varchar(255) DEFAULT NULL,      -- Stripe customer ID
    
    -- Subscription Period
    start_date datetime DEFAULT NULL,
    end_date datetime DEFAULT NULL,
    next_billing_date datetime DEFAULT NULL,
    
    -- Timestamps
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (id),
    KEY user_id (user_id),
    KEY status (status),
    KEY subscription_tier (subscription_tier),
    KEY stripe_subscription_id (stripe_subscription_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Table 3: wp_hiresmart_payment_methods

```sql
CREATE TABLE wp_hiresmart_payment_methods (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    user_id bigint(20) NOT NULL,
    payment_type varchar(50) NOT NULL,           -- 'card', 'bank_account'
    
    -- Card Information (last 4 digits only for security)
    card_last4 varchar(4) DEFAULT NULL,
    card_brand varchar(20) DEFAULT NULL,         -- 'Visa', 'Mastercard', etc.
    card_exp_month int(2) DEFAULT NULL,
    card_exp_year int(4) DEFAULT NULL,
    
    -- Stripe IDs
    stripe_payment_method_id varchar(255) DEFAULT NULL,
    stripe_customer_id varchar(255) DEFAULT NULL,
    
    -- Flags
    is_default tinyint(1) DEFAULT 0,
    
    -- Timestamps
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (id),
    KEY user_id (user_id),
    KEY is_default (is_default),
    KEY stripe_payment_method_id (stripe_payment_method_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Table 4: wp_hiresmart_jobs

```sql
CREATE TABLE wp_hiresmart_jobs (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    employer_id bigint(20) NOT NULL,             -- wp_users.ID (employer or agency)
    
    -- Job Details
    title varchar(255) NOT NULL,
    description longtext NOT NULL,
    requirements longtext DEFAULT NULL,
    location varchar(255) DEFAULT NULL,
    salary_min decimal(10,2) DEFAULT NULL,
    salary_max decimal(10,2) DEFAULT NULL,
    salary_currency varchar(3) DEFAULT 'USD',
    salary_public tinyint(1) DEFAULT 1,           -- Show salary publicly
    
    -- Job Categories
    job_type varchar(50) DEFAULT NULL,            -- 'full-time', 'part-time', 'contract', etc.
    experience_level varchar(50) DEFAULT NULL,    -- 'entry', 'mid', 'senior', 'lead', 'executive'
    skills longtext DEFAULT NULL,                 -- Comma-separated required skills
    
    -- Commission (for agencies)
    commission_type varchar(50) DEFAULT NULL,     -- 'percentage', 'fixed'
    commission_value decimal(10,2) DEFAULT NULL,
    referral_bonus decimal(10,2) DEFAULT NULL,
    
    -- Job Status
    status varchar(20) DEFAULT 'active',          -- 'active', 'expired', 'filled', 'removed'
    coins_used int(11) DEFAULT 0,                 -- AI coins spent on this job
    
    -- Analytics
    views int(11) DEFAULT 0,
    applications_count int(11) DEFAULT 0,
    
    -- Timestamps
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    expires_at datetime DEFAULT NULL,             -- Job expiration (14 days from created_at)
    
    PRIMARY KEY (id),
    KEY employer_id (employer_id),
    KEY status (status),
    KEY job_type (job_type),
    KEY experience_level (experience_level),
    KEY expires_at (expires_at),
    FULLTEXT KEY title_description (title, description)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Table 5: wp_hiresmart_applications

```sql
CREATE TABLE wp_hiresmart_applications (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    job_id bigint(20) NOT NULL,
    candidate_id bigint(20) NOT NULL,            -- wp_users.ID (job seeker)
    
    -- Application Materials
    cover_letter longtext DEFAULT NULL,
    resume_url varchar(255) DEFAULT NULL,
    additional_documents longtext DEFAULT NULL,  -- JSON array of URLs
    
    -- Application Status
    status varchar(20) DEFAULT 'pending',         -- 'pending', 'reviewed', 'interview', 'offer', 'accepted', 'rejected'
    employer_notes longtext DEFAULT NULL,        -- Private notes by employer
    
    -- Timestamps
    applied_at datetime DEFAULT CURRENT_TIMESTAMP,
    updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    reviewed_at datetime DEFAULT NULL,
    
    PRIMARY KEY (id),
    KEY job_id (job_id),
    KEY candidate_id (candidate_id),
    KEY status (status),
    UNIQUE KEY unique_application (job_id, candidate_id)  -- Prevent duplicate applications
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Table 6: wp_hiresmart_coins (Future Enhancement)

```sql
CREATE TABLE wp_hiresmart_coins (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    user_id bigint(20) NOT NULL,
    
    -- Transaction Details
    transaction_type varchar(50) NOT NULL,       -- 'purchase', 'spend', 'refund', 'allocation'
    amount int(11) NOT NULL,                     -- Positive for additions, negative for deductions
    balance_after int(11) NOT NULL,              -- Balance after this transaction
    
    -- Related Records
    related_id bigint(20) DEFAULT NULL,          -- ID of job, purchase, etc.
    related_type varchar(50) DEFAULT NULL,       -- 'job', 'purchase', 'boost', etc.
    
    -- Description
    description varchar(255) DEFAULT NULL,
    
    -- Timestamps
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (id),
    KEY user_id (user_id),
    KEY transaction_type (transaction_type),
    KEY created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Table 7: wp_hiresmart_notifications (Future Enhancement)

```sql
CREATE TABLE wp_hiresmart_notifications (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    user_id bigint(20) NOT NULL,
    
    -- Notification Details
    notification_type varchar(50) NOT NULL,      -- 'application', 'interview', 'message', etc.
    title varchar(255) NOT NULL,
    message longtext NOT NULL,
    
    -- Related Records
    related_id bigint(20) DEFAULT NULL,
    related_type varchar(50) DEFAULT NULL,       -- 'job', 'application', 'user', etc.
    action_url varchar(255) DEFAULT NULL,        -- URL to navigate when clicked
    
    -- Status
    is_read tinyint(1) DEFAULT 0,
    read_at datetime DEFAULT NULL,
    
    -- Timestamps
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (id),
    KEY user_id (user_id),
    KEY is_read (is_read),
    KEY notification_type (notification_type),
    KEY created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 5.5 API Endpoints (AJAX)

All AJAX endpoints follow WordPress AJAX API conventions:

**Endpoint Format:**
```
POST /wp-admin/admin-ajax.php
```

**Parameters:**
```javascript
{
    action: 'hiresmart_{action_name}',
    nonce: wp_nonce_value,
    // ... other parameters
}
```

#### Authentication Endpoints

**1. Register User**
```javascript
Action: hiresmart_register
Parameters: {
    full_name: string,
    email: string,
    password: string,
    account_type: 'job_seeker' | 'employer' | 'agency',
    subscription_tier: 'free' | 'startup' | 'enterprise'
}
Response: {
    success: boolean,
    data: {
        user_id: number,
        redirect_url: string
    },
    message: string
}
```

**2. Login User**
```javascript
Action: hiresmart_login
Parameters: {
    email: string,
    password: string,
    remember_me: boolean
}
Response: {
    success: boolean,
    data: {
        user_id: number,
        redirect_url: string
    },
    message: string
}
```

**3. Logout User**
```javascript
Action: hiresmart_logout
Parameters: {}
Response: {
    success: boolean,
    data: {
        redirect_url: string
    }
}
```

#### Profile Endpoints

**4. Update Profile**
```javascript
Action: hiresmart_update_profile
Parameters: {
    full_name: string,
    email: string,
    phone: string,
    location: string,
    bio: string
}
Response: {
    success: boolean,
    message: string
}
```

**5. Submit AI Assessment**
```javascript
Action: hiresmart_submit_assessment
Parameters: {
    logical_reasoning: number (1-10),
    problem_solving: number (1-10),
    emotional_awareness: number (1-10),
    empathy: number (1-10),
    communication: number (1-10),
    teamwork: number (1-10)
}
Response: {
    success: boolean,
    data: {
        iq_score: number,
        eq_score: number,
        sq_score: number
    },
    message: string
}
```

**6. Save Integrations**
```javascript
Action: hiresmart_save_integrations
Parameters: {
    linkedin_url: string,
    github_url: string,
    behance_url: string,
    canva_url: string,
    portfolio_url: string
}
Response: {
    success: boolean,
    data: {
        sync_percentage: number
    },
    message: string
}
```

#### Job Endpoints

**7. Post Job**
```javascript
Action: hiresmart_post_job
Parameters: {
    title: string,
    description: string,
    requirements: string,
    location: string,
    salary_min: number,
    salary_max: number,
    job_type: string,
    experience_level: string,
    skills: string (comma-separated)
}
Response: {
    success: boolean,
    data: {
        job_id: number,
        expires_at: string (datetime)
    },
    message: string
}
```

**8. Apply to Job**
```javascript
Action: hiresmart_apply_job
Parameters: {
    job_id: number,
    cover_letter: string,
    resume_url: string
}
Response: {
    success: boolean,
    data: {
        application_id: number
    },
    message: string
}
```

**9. Renew Job**
```javascript
Action: hiresmart_renew_job
Parameters: {
    job_id: number
}
Response: {
    success: boolean,
    data: {
        new_expires_at: string (datetime),
        coins_remaining: number
    },
    message: string
}
```

#### Billing Endpoints

**10. Add Payment Method**
```javascript
Action: hiresmart_add_payment_method
Parameters: {
    stripe_payment_method_id: string,
    set_as_default: boolean
}
Response: {
    success: boolean,
    data: {
        payment_method_id: number
    },
    message: string
}
```

**11. Remove Payment Method**
```javascript
Action: hiresmart_remove_payment_method
Parameters: {
    payment_method_id: number
}
Response: {
    success: boolean,
    message: string
}
```

**12. Change Subscription**
```javascript
Action: hiresmart_change_subscription
Parameters: {
    new_tier: 'free' | 'startup' | 'enterprise'
}
Response: {
    success: boolean,
    data: {
        subscription_id: number,
        next_billing_date: string,
        amount: number
    },
    message: string
}
```

### 5.6 Cross-Domain Configuration

#### wp-config.php Settings

**Settings for BOTH domains (must be identical):**

```php
<?php
// === Authentication Keys ===
// CRITICAL: These MUST be identical on both domains
// Generate at: https://api.wordpress.org/secret-key/1.1/salt/
define('AUTH_KEY',         'put your unique phrase here');
define('SECURE_AUTH_KEY',  'put your unique phrase here');
define('LOGGED_IN_KEY',    'put your unique phrase here');
define('NONCE_KEY',        'put your unique phrase here');
define('AUTH_SALT',        'put your unique phrase here');
define('SECURE_AUTH_SALT', 'put your unique phrase here');
define('LOGGED_IN_SALT',   'put your unique phrase here');
define('NONCE_SALT',       'put your unique phrase here');

// === Cookie Domain for SSO ===
// The leading dot makes cookies accessible to all subdomains
define('COOKIE_DOMAIN', '.startupstreet.in');
define('ADMIN_COOKIE_PATH', '/');
define('COOKIEPATH', '/');
define('SITECOOKIEPATH', '/');

// === Database Configuration ===
// Option A: Shared Database (different prefixes)
// Landing domain:
$table_prefix = 'wp_main_';
define('DB_NAME', 'hiresmart_production');

// App domain:
$table_prefix = 'wp_app_';
define('DB_NAME', 'hiresmart_production');

// === Stripe Configuration ===
define('HIRESMART_STRIPE_PUBLIC_KEY', 'pk_live_your_key_here');
define('HIRESMART_STRIPE_SECRET_KEY', 'sk_live_your_key_here');

// === OAuth Configuration ===
define('HIRESMART_GOOGLE_CLIENT_ID', 'your-google-client-id');
define('HIRESMART_GOOGLE_CLIENT_SECRET', 'your-google-client-secret');
define('HIRESMART_LINKEDIN_CLIENT_ID', 'your-linkedin-client-id');
define('HIRESMART_LINKEDIN_CLIENT_SECRET', 'your-linkedin-client-secret');
define('HIRESMART_GITHUB_CLIENT_ID', 'your-github-client-id');
define('HIRESMART_GITHUB_CLIENT_SECRET', 'your-github-client-secret');

// === SSL/HTTPS Configuration ===
define('FORCE_SSL_ADMIN', true);
if (strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false) {
    $_SERVER['HTTPS'] = 'on';
}

// === CORS for Cross-Domain AJAX (if needed) ===
header('Access-Control-Allow-Origin: https://hiresmart.startupstreet.in');
header('Access-Control-Allow-Credentials: true');
?>
```

### 5.7 Security Measures

#### 5.7.1 Input Validation & Sanitization

**WordPress Sanitization Functions Used:**

```php
// Text fields
$title = sanitize_text_field($_POST['title']);

// Email addresses
$email = sanitize_email($_POST['email']);

// URLs
$url = esc_url_raw($_POST['url']);

// Rich text (allows safe HTML)
$description = wp_kses_post($_POST['description']);

// Integers
$user_id = intval($_POST['user_id']);

// Floating point numbers
$salary = floatval($_POST['salary']);

// Alphanumeric
$username = sanitize_user($_POST['username']);

// Filename
$filename = sanitize_file_name($_FILES['resume']['name']);

// SQL queries (prepared statements)
$wpdb->prepare(
    "SELECT * FROM wp_hiresmart_jobs WHERE id = %d AND employer_id = %d",
    $job_id,
    $user_id
);
```

#### 5.7.2 Output Escaping

```php
// Plain text
echo esc_html($user_name);

// HTML attributes
echo '<div class="' . esc_attr($class_name) . '">';

// URLs
echo '<a href="' . esc_url($profile_url) . '">';

// JavaScript
echo '<script>var userName = "' . esc_js($user_name) . '";</script>';

// Rich HTML content
echo wp_kses_post($job_description);

// Internationalized text
echo esc_html__('Welcome', 'hiresmart');
```

#### 5.7.3 CSRF Protection (Nonces)

```php
// Generate nonce
$nonce = wp_create_nonce('hiresmart_post_job');

// In form
<input type="hidden" name="nonce" value="<?php echo esc_attr($nonce); ?>">

// Verify nonce in AJAX handler
if (!wp_verify_nonce($_POST['nonce'], 'hiresmart_post_job')) {
    wp_send_json_error('Security check failed');
    wp_die();
}
```

#### 5.7.4 User Capability Checks

```php
// Check if user is logged in
if (!is_user_logged_in()) {
    wp_send_json_error('Please login to continue');
    wp_die();
}

// Check user capabilities
if (!current_user_can('edit_posts')) {
    wp_send_json_error('Insufficient permissions');
    wp_die();
}

// Check account type
$profile = get_user_profile(get_current_user_id());
if ($profile->account_type !== 'employer') {
    wp_send_json_error('This feature is for employers only');
    wp_die();
}
```

#### 5.7.5 SQL Injection Prevention

```php
global $wpdb;

// ALWAYS use prepared statements
$results = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}hiresmart_jobs 
        WHERE employer_id = %d 
        AND status = %s 
        AND created_at > %s",
        $employer_id,
        $status,
        $date
    )
);

// NEVER concatenate user input
// BAD: "SELECT * FROM jobs WHERE id = " . $_POST['id']
```

#### 5.7.6 XSS Prevention

```php
// Always escape output
<h1><?php echo esc_html($job_title); ?></h1>

<a href="<?php echo esc_url($job_url); ?>">
    <?php echo esc_html($job_title); ?>
</a>

<div class="<?php echo esc_attr($css_class); ?>">
    <?php echo wp_kses_post($job_description); ?>
</div>
```

#### 5.7.7 File Upload Security

```php
// Validate file type
$allowed_types = array('pdf', 'doc', 'docx');
$file_type = pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION);

if (!in_array(strtolower($file_type), $allowed_types)) {
    wp_send_json_error('Invalid file type');
    wp_die();
}

// Validate file size (5MB max)
$max_size = 5 * 1024 * 1024; // 5MB in bytes
if ($_FILES['resume']['size'] > $max_size) {
    wp_send_json_error('File too large');
    wp_die();
}

// Sanitize filename
$filename = sanitize_file_name($_FILES['resume']['name']);

// Use WordPress upload handler
$upload = wp_handle_upload($_FILES['resume'], array(
    'test_form' => false,
    'mimes' => array(
        'pdf' => 'application/pdf',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    )
));

if (isset($upload['error'])) {
    wp_send_json_error($upload['error']);
    wp_die();
}

$file_url = $upload['url'];
```

#### 5.7.8 Rate Limiting

```php
// Simple rate limiting using transients
function hiresmart_check_rate_limit($action, $user_id, $limit = 10, $period = 3600) {
    $transient_key = "hiresmart_rate_limit_{$action}_{$user_id}";
    $count = get_transient($transient_key);
    
    if ($count === false) {
        set_transient($transient_key, 1, $period);
        return true;
    }
    
    if ($count >= $limit) {
        return false; // Rate limit exceeded
    }
    
    set_transient($transient_key, $count + 1, $period);
    return true;
}

// Usage in AJAX handler
if (!hiresmart_check_rate_limit('post_job', get_current_user_id(), 5, 3600)) {
    wp_send_json_error('Too many job postings. Please try again later.');
    wp_die();
}
```

#### 5.7.9 Password Security

```php
// Password hashing (handled by WordPress)
$user_id = wp_create_user($username, $password, $email);

// Password validation
function hiresmart_validate_password($password) {
    $errors = array();
    
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long';
    }
    
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = 'Password must contain at least one uppercase letter';
    }
    
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = 'Password must contain at least one lowercase letter';
    }
    
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = 'Password must contain at least one number';
    }
    
    if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
        $errors[] = 'Password must contain at least one special character';
    }
    
    return empty($errors) ? true : $errors;
}
```


---

## 6. Domain & Deployment Strategy

### 6.1 DNS Configuration

**Required DNS Records:**

```
Type    Name            Value                           TTL    Priority
A       hiresmart       xxx.xxx.xxx.xxx (server IP)    300    -
A       app-hiresmart   xxx.xxx.xxx.xxx (server IP)    300    -
CNAME   www             hiresmart.startupstreet.in      300    -
TXT     @               v=spf1 include:_spf.google.com ~all    300    -
MX      @               mail.startupstreet.in           300    10
```

**DNS Provider Setup Steps:**
1. Log in to your DNS provider (Cloudflare, Route53, etc.)
2. Add A records for both subdomains pointing to server IP
3. Add SPF record for email deliverability
4. Add MX record if using custom email
5. Wait for propagation (5-30 minutes)

### 6.2 SSL Certificate Setup

**Option 1: Let's Encrypt (Free)**

```bash
# Install Certbot
sudo apt-get update
sudo apt-get install certbot python3-certbot-apache

# Generate certificates for both domains
sudo certbot --apache -d hiresmart.startupstreet.in -d app-hiresmart.startupstreet.in

# Auto-renewal (cron job)
sudo crontab -e
# Add this line:
0 3 * * * /usr/bin/certbot renew --quiet
```

**Option 2: Cloudflare SSL (Free)**
1. Add domains to Cloudflare
2. Change nameservers at registrar
3. Enable "Full (strict)" SSL mode
4. Auto-renewal handled by Cloudflare

**Option 3: Commercial SSL Certificate**
1. Purchase wildcard cert: *.startupstreet.in
2. Install on server
3. Configure Apache/Nginx to use cert
4. Set up auto-renewal

### 6.3 Server Requirements

**Minimum Requirements:**

| Component | Minimum | Recommended | Production |
|-----------|---------|-------------|------------|
| **CPU** | 1 core | 2 cores | 4 cores |
| **RAM** | 1GB | 2GB | 4GB+ |
| **Storage** | 10GB SSD | 20GB SSD | 50GB+ SSD |
| **Bandwidth** | 100GB/month | 500GB/month | 1TB+/month |
| **PHP** | 7.4 | 8.0 | 8.1+ |
| **MySQL** | 5.7 | 8.0 | 8.0+ |

**Recommended Hosting Providers:**
- DigitalOcean (Droplet): $12-24/month
- AWS EC2 (t3.small): $15-30/month
- Cloudways (Managed): $25-50/month
- WP Engine (Managed): $30-60/month
- Kinsta (Managed): $35-70/month

### 6.4 Deployment Options

#### Option 1: WordPress Multisite (Recommended)

**Pros:**
- Single WordPress installation
- Shared database and users
- Easier maintenance and updates
- Built-in subdomain support
- Lower resource usage

**Cons:**
- More complex initial setup
- Plugin compatibility issues (rare)
- All sites share same WordPress version

**Setup Steps:**

1. **Install WordPress** (single installation)
   ```bash
   cd /var/www/hiresmart-multisite
   wget https://wordpress.org/latest.tar.gz
   tar -xzf latest.tar.gz
   mv wordpress/* .
   ```

2. **Enable Multisite** in wp-config.php:
   ```php
   define('WP_ALLOW_MULTISITE', true);
   ```

3. **Configure Network** (after visiting Tools → Network Setup):
   ```php
   define('MULTISITE', true);
   define('SUBDOMAIN_INSTALL', true);
   define('DOMAIN_CURRENT_SITE', 'startupstreet.in');
   define('PATH_CURRENT_SITE', '/');
   define('SITE_ID_CURRENT_SITE', 1);
   define('BLOG_ID_CURRENT_SITE', 1);
   ```

4. **Update .htaccess**:
   ```apache
   RewriteEngine On
   RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
   RewriteBase /
   RewriteRule ^index\.php$ - [L]
   # add a trailing slash to /wp-admin
   RewriteRule ^([_0-9a-zA-Z-]+/)?wp-admin$ $1wp-admin/ [R=301,L]
   RewriteCond %{REQUEST_FILENAME} -f [OR]
   RewriteCond %{REQUEST_FILENAME} -d
   RewriteRule ^ - [L]
   RewriteRule ^([_0-9a-zA-Z-]+/)?(wp-(content|admin|includes).*) $2 [L]
   RewriteRule ^([_0-9a-zA-Z-]+/)?(.*\.php)$ $2 [L]
   RewriteRule . index.php [L]
   ```

5. **Create Subsites**:
   - Network Admin → Sites → Add New
   - Add "hiresmart" and "app-hiresmart"

6. **Activate Theme/Plugin**:
   - Network Admin → Themes → Enable HireSmart Theme
   - Network Admin → Plugins → Network Activate HireSmart Plugin

#### Option 2: Separate WordPress Installations

**Pros:**
- Complete isolation between sites
- Independent updates possible
- Simpler troubleshooting
- More flexibility per site

**Cons:**
- More server resources needed
- Duplicate WordPress files
- More complex cross-domain SSO
- Must sync updates manually

**Setup Steps:**

1. **Install WordPress Twice**:
   ```bash
   # Landing page installation
   /var/www/hiresmart/

   # App installation
   /var/www/app-hiresmart/
   ```

2. **Configure Database**:
   ```php
   // Landing: wp-config.php
   $table_prefix = 'wp_main_';
   define('DB_NAME', 'hiresmart_db');

   // App: wp-config.php
   $table_prefix = 'wp_app_';
   define('DB_NAME', 'hiresmart_db'); // Same database
   ```

3. **Sync Authentication Keys** (CRITICAL):
   - Copy all 8 auth keys from one wp-config.php to the other
   - Ensure COOKIE_DOMAIN is set to '.startupstreet.in' on both

4. **Configure Apache Virtual Hosts**:
   ```apache
   # /etc/apache2/sites-available/hiresmart.conf
   <VirtualHost *:80>
       ServerName hiresmart.startupstreet.in
       DocumentRoot /var/www/hiresmart
       <Directory /var/www/hiresmart>
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>

   # /etc/apache2/sites-available/app-hiresmart.conf
   <VirtualHost *:80>
       ServerName app-hiresmart.startupstreet.in
       DocumentRoot /var/www/app-hiresmart
       <Directory /var/www/app-hiresmart>
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

5. **Enable Sites**:
   ```bash
   sudo a2ensite hiresmart
   sudo a2ensite app-hiresmart
   sudo systemctl reload apache2
   ```

### 6.5 Performance Optimization

#### 6.5.1 Caching Strategy

**Object Caching (Redis/Memcached)**:
```bash
# Install Redis
sudo apt-get install redis-server php-redis

# Enable Redis in wp-config.php
define('WP_REDIS_HOST', '127.0.0.1');
define('WP_REDIS_PORT', 6379);
define('WP_CACHE_KEY_SALT', 'hiresmart_');
```

**Page Caching (W3 Total Cache / WP Super Cache)**:
- Install caching plugin
- Enable page caching
- Set cache expiration: 3600 seconds
- Exclude: /dashboard, /profile, /billing

**CDN Integration**:
- Cloudflare (free tier)
- AWS CloudFront
- StackPath
- KeyCDN

#### 6.5.2 Database Optimization

```sql
-- Index optimization
ALTER TABLE wp_hiresmart_jobs ADD INDEX idx_status_expires (status, expires_at);
ALTER TABLE wp_hiresmart_applications ADD INDEX idx_job_status (job_id, status);

-- Query optimization
EXPLAIN SELECT * FROM wp_hiresmart_jobs WHERE status = 'active' AND expires_at > NOW();

-- Database cleanup
DELETE FROM wp_postmeta WHERE meta_key = '_transient_%' AND meta_value < UNIX_TIMESTAMP();
OPTIMIZE TABLE wp_posts, wp_postmeta, wp_options;

-- Enable query cache
SET GLOBAL query_cache_size = 268435456;
SET GLOBAL query_cache_type = ON;
```

#### 6.5.3 PHP Optimization

**php.ini Settings**:
```ini
memory_limit = 256M
max_execution_time = 60
max_input_time = 60
upload_max_filesize = 10M
post_max_size = 12M
max_input_vars = 3000

; OPcache settings
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
```

#### 6.5.4 Image Optimization

- Use WebP format
- Lazy loading (native or plugin)
- Compress images before upload
- Use srcset for responsive images
- CDN for image delivery

### 6.6 Backup Strategy

**Automated Backups:**

```bash
#!/bin/bash
# /root/backup-hiresmart.sh

# Variables
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups/hiresmart"
DB_NAME="hiresmart_db"
DB_USER="username"
DB_PASS="password"

# Create backup directory
mkdir -p $BACKUP_DIR/$DATE

# Backup database
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/$DATE/database.sql.gz

# Backup files
tar -czf $BACKUP_DIR/$DATE/files.tar.gz /var/www/hiresmart /var/www/app-hiresmart

# Upload to S3 (optional)
aws s3 sync $BACKUP_DIR s3://hiresmart-backups/

# Delete backups older than 30 days
find $BACKUP_DIR -type d -mtime +30 -exec rm -rf {} +

echo "Backup completed: $DATE"
```

**Cron Schedule**:
```bash
# Daily backups at 2 AM
0 2 * * * /root/backup-hiresmart.sh >> /var/log/hiresmart-backup.log 2>&1

# Weekly full backups on Sunday at 1 AM
0 1 * * 0 /root/full-backup-hiresmart.sh >> /var/log/hiresmart-full-backup.log 2>&1
```

**Backup Tools:**
- UpdraftPlus (WordPress plugin)
- BackWPup
- Duplicator
- AWS Backup
- Acronis Cyber Backup

### 6.7 Monitoring & Logging

**Application Monitoring:**
- New Relic (APM)
- DataDog
- Sentry (error tracking)
- LogRocket (session replay)

**Server Monitoring:**
- Prometheus + Grafana
- Nagios
- Zabbix
- UptimeRobot (uptime monitoring)

**Log Files to Monitor:**
```bash
# WordPress debug log
/var/www/hiresmart/wp-content/debug.log

# PHP error log
/var/log/php/error.log

# Apache/Nginx access and error logs
/var/log/apache2/hiresmart_access.log
/var/log/apache2/hiresmart_error.log

# MySQL slow query log
/var/log/mysql/slow-query.log
```

**WordPress Debug Mode** (development only):
```php
// wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', true);
```

---

## 7. Security & Access Control

### 7.1 Access Control Matrix (Comprehensive)

| Resource / Action | Guest | Job Seeker | Employer | Agency | Admin |
|-------------------|-------|------------|----------|--------|-------|
| **Public Pages** |  |  |  |  |  |
| Landing Page | ✅ | ✅ | ✅ | ✅ | ✅ |
| About/Contact | ✅ | ✅ | ✅ | ✅ | ✅ |
| Terms/Privacy | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Authentication** |  |  |  |  |  |
| Registration | ✅ | N/A | N/A | N/A | N/A |
| Login | ✅ | ✅ | ✅ | ✅ | ✅ |
| Logout | N/A | ✅ | ✅ | ✅ | ✅ |
| Social Login | ✅ | ✅ | ✅ | ✅ | ✅ |
| Password Reset | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Job Browsing** |  |  |  |  |  |
| View Jobs (First 5) | ✅ | N/A | N/A | N/A | N/A |
| View Jobs (All) | ❌ | ✅ | ✅ | ✅ | ✅ |
| Search Jobs | ❌ | ✅ | ✅ | ✅ | ✅ |
| Filter Jobs | ❌ | ✅ | ✅ | ✅ | ✅ |
| View Job Details | ❌ | ✅ | ✅ | ✅ | ✅ |
| **Job Management** |  |  |  |  |  |
| Post Job | ❌ | ❌ | ✅ | ✅ | ✅ |
| Edit Own Job | ❌ | ❌ | ✅ | ✅ | ✅ |
| Delete Own Job | ❌ | ❌ | ✅ | ✅ | ✅ |
| Renew Job | ❌ | ❌ | ✅ | ✅ | ✅ |
| Mark Job as Filled | ❌ | ❌ | ✅ | ✅ | ✅ |
| View Job Analytics | ❌ | ❌ | ✅ (own) | ✅ (own) | ✅ (all) |
| **Applications** |  |  |  |  |  |
| Apply to Job | ❌ | ✅ | ❌ | ❌ | ❌ |
| View Own Applications | ❌ | ✅ | ❌ | ❌ | ✅ (all) |
| Withdraw Application | ❌ | ✅ | ❌ | ❌ | ❌ |
| View Job Applicants | ❌ | ❌ | ✅ (own jobs) | ✅ (own jobs) | ✅ (all) |
| Change Application Status | ❌ | ❌ | ✅ (own jobs) | ✅ (own jobs) | ✅ (all) |
| Download Resume | ❌ | ❌ | ✅ | ✅ | ✅ |
| **Candidate Directory** |  |  |  |  |  |
| View Candidates (First 5) | ✅ | ❌ | N/A | N/A | N/A |
| View Candidates (All) | ❌ | ❌ | ✅ | ✅ | ✅ |
| Search Candidates | ❌ | ❌ | ✅ | ✅ | ✅ |
| View AI Scores | ❌ | ❌ | ✅ | ✅ | ✅ |
| Contact Candidate | ❌ | ❌ | ✅ | ✅ | ✅ |
| **Employer Directory** |  |  |  |  |  |
| View Employers (First 5) | ✅ | N/A | N/A | N/A | N/A |
| View Employers (All) | ❌ | ✅ | ✅ | ✅ | ✅ |
| Search Employers | ❌ | ✅ | ✅ | ✅ | ✅ |
| View Company Profile | ❌ | ✅ | ✅ | ✅ | ✅ |
| Contact Company | ❌ | ✅ | ✅ | ✅ | ✅ |
| **Profile Management** |  |  |  |  |  |
| View Own Profile | ❌ | ✅ | ✅ | ✅ | ✅ |
| Edit Own Profile | ❌ | ✅ | ✅ | ✅ | ✅ |
| Take AI Assessment | ❌ | ✅ | ✅ | ✅ | ✅ |
| Add Integrations | ❌ | ✅ | ✅ | ✅ | ✅ |
| Upload Resume/Documents | ❌ | ✅ | ✅ | ✅ | ✅ |
| Delete Account | ❌ | ✅ | ✅ | ✅ | ❌ |
| **Billing & Subscriptions** |  |  |  |  |  |
| View Own Subscription | ❌ | ✅ | ✅ | ✅ | ✅ |
| Change Subscription | ❌ | ✅ | ✅ | ✅ | ✅ |
| Add Payment Method | ❌ | ✅ | ✅ | ✅ | ✅ |
| Remove Payment Method | ❌ | ✅ | ✅ | ✅ | ✅ |
| View Billing History | ❌ | ✅ | ✅ | ✅ | ✅ |
| Download Invoices | ❌ | ✅ | ✅ | ✅ | ✅ |
| **Dashboards** |  |  |  |  |  |
| Job Seeker Dashboard | ❌ | ✅ | ❌ | ❌ | ✅ (view) |
| Employer Dashboard | ❌ | ❌ | ✅ | ❌ | ✅ (view) |
| Agency Dashboard | ❌ | ❌ | ❌ | ✅ | ✅ (view) |
| Admin Dashboard | ❌ | ❌ | ❌ | ❌ | ✅ |
| **AI Coins** |  |  |  |  |  |
| View Coin Balance | ❌ | ✅ | ✅ | ✅ | ✅ |
| Purchase Coins | ❌ | ✅ | ✅ | ✅ | ✅ |
| View Transaction History | ❌ | ✅ | ✅ | ✅ | ✅ |
| Grant Coins (Manual) | ❌ | ❌ | ❌ | ❌ | ✅ |
| **Admin Functions** |  |  |  |  |  |
| View All Users | ❌ | ❌ | ❌ | ❌ | ✅ |
| Edit Any User | ❌ | ❌ | ❌ | ❌ | ✅ |
| Delete Users | ❌ | ❌ | ❌ | ❌ | ✅ |
| View All Jobs | ❌ | ❌ | ❌ | ❌ | ✅ |
| Edit/Delete Any Job | ❌ | ❌ | ❌ | ❌ | ✅ |
| View Platform Analytics | ❌ | ❌ | ❌ | ❌ | ✅ |
| Manage Subscriptions | ❌ | ❌ | ❌ | ❌ | ✅ |
| System Settings | ❌ | ❌ | ❌ | ❌ | ✅ |

### 7.2 Data Privacy & GDPR Compliance

#### 7.2.1 Data Collection Transparency

**What We Collect:**
- Personal information (name, email, phone)
- Professional data (resume, work history, skills)
- AI assessment responses
- Job application materials
- Payment information (via Stripe)
- Usage analytics (anonymized)
- Cookies and session data

**Purpose of Collection:**
- Account creation and authentication
- Job matching and recommendations
- AI profiling and scoring
- Payment processing
- Platform improvement
- Legal compliance

#### 7.2.2 User Rights (GDPR)

**Right to Access**:
- Users can download all their data
- JSON export of profile, applications, jobs

**Right to Rectification**:
- Users can edit their profile anytime
- Update AI scores by retaking assessment

**Right to Erasure** ("Right to be Forgotten"):
- Delete account option in settings
- Anonymizes data (doesn't delete completely for audit trail)
- Removes PII within 30 days

**Right to Data Portability**:
- Export data in JSON/CSV format
- Compatible with other platforms

**Right to Object**:
- Opt-out of email marketing
- Disable job recommendations
- Hide profile from employers

#### 7.2.3 GDPR Implementation

```php
// Data Export Function
function hiresmart_export_user_data($user_id) {
    global $wpdb;
    
    $data = array(
        'user' => get_userdata($user_id),
        'profile' => $wpdb->get_row("SELECT * FROM wp_hiresmart_profiles WHERE user_id = $user_id"),
        'applications' => $wpdb->get_results("SELECT * FROM wp_hiresmart_applications WHERE candidate_id = $user_id"),
        'jobs' => $wpdb->get_results("SELECT * FROM wp_hiresmart_jobs WHERE employer_id = $user_id"),
        'subscriptions' => $wpdb->get_results("SELECT * FROM wp_hiresmart_subscriptions WHERE user_id = $user_id")
    );
    
    // Remove sensitive fields
    unset($data['user']->user_pass);
    
    return json_encode($data, JSON_PRETTY_PRINT);
}

// Data Anonymization Function
function hiresmart_anonymize_user_data($user_id) {
    global $wpdb;
    
    // Update personal information
    wp_update_user(array(
        'ID' => $user_id,
        'user_email' => "deleted_" . $user_id . "@deleted.local",
        'user_login' => "deleted_user_" . $user_id,
        'display_name' => "Deleted User"
    ));
    
    // Anonymize profile
    $wpdb->update(
        $wpdb->prefix . 'hiresmart_profiles',
        array(
            'linkedin_url' => null,
            'github_url' => null,
            'behance_url' => null,
            'portfolio_url' => null,
            'profile_data' => null
        ),
        array('user_id' => $user_id)
    );
    
    // Keep applications and jobs for audit trail but anonymize PII
    $wpdb->update(
        $wpdb->prefix . 'hiresmart_applications',
        array(
            'cover_letter' => '[Deleted by user]',
            'resume_url' => null
        ),
        array('candidate_id' => $user_id)
    );
}
```

### 7.3 Security Best Practices Checklist

- ✅ SSL/HTTPS enforced on all pages
- ✅ Strong password policy (8+ chars, mixed case, numbers, symbols)
- ✅ WordPress core, themes, plugins kept updated
- ✅ Security plugins installed (Wordfence, iThemes Security)
- ✅ Two-factor authentication (2FA) for admins
- ✅ Limit login attempts (rate limiting)
- ✅ Regular security audits and vulnerability scans
- ✅ Database credentials not hardcoded in git
- ✅ File upload restrictions and validation
- ✅ CSRF protection (WordPress nonces)
- ✅ XSS prevention (output escaping)
- ✅ SQL injection prevention (prepared statements)
- ✅ User input sanitization
- ✅ Session hijacking prevention (secure cookies)
- ✅ Firewall rules (fail2ban, ModSecurity)
- ✅ Regular backups (daily database, weekly files)
- ✅ Disaster recovery plan documented
- ✅ Security incident response plan
- ✅ PCI DSS compliance for payments (via Stripe)
- ✅ GDPR compliance for EU users

---

## 8. Feature Roadmap

### Phase 1: MVP (COMPLETED) ✅

**Timeline:** Months 1-3 (Q1 2026)
**Status:** 100% Complete

**Features Delivered:**
- ✅ Landing page with features, pricing, use cases
- ✅ User registration and authentication
- ✅ Social login UI (OAuth integration ready)
- ✅ Role-based dashboards (Job Seeker, Employer, Agency)
- ✅ AI profiling system (IQ, EQ, SQ assessment)
- ✅ Job posting with 14-day expiration
- ✅ Job browsing with search and filters
- ✅ Job application system
- ✅ Candidate directory with AI scores
- ✅ Employer/agency directory
- ✅ Profile management
- ✅ Billing and subscription UI
- ✅ Profile integrations (LinkedIn, GitHub, etc.)
- ✅ Payment method management UI
- ✅ AI Coins system (basic)
- ✅ Access control and permissions
- ✅ Guest preview (first 5 items)
- ✅ Cross-domain session management
- ✅ Database schema (7 tables)
- ✅ Security best practices
- ✅ Comprehensive documentation

### Phase 2: Enhanced Features (IN PROGRESS) 🔄

**Timeline:** Months 4-6 (Q2 2026)
**Status:** 40% Complete

**Features:**

**2.1 OAuth Integration** (Priority: P0)
- [ ] Google OAuth complete integration
- [ ] LinkedIn OAuth complete integration
- [ ] GitHub OAuth complete integration
- [ ] Social profile data import
- [ ] Profile picture sync

**2.2 Stripe Payment Integration** (Priority: P0)
- [ ] Stripe Elements for card collection
- [ ] Subscription creation and management
- [ ] Webhook handlers for events
- [ ] Invoice generation
- [ ] Refund processing

**2.3 Email Notifications** (Priority: P1)
- [ ] Welcome email on registration
- [ ] Job application confirmation
- [ ] New applicant notification (employers)
- [ ] Interview scheduling emails
- [ ] Job expiration reminders
- [ ] Password reset emails
- [ ] Subscription renewal reminders
- [ ] Payment failure notifications

**2.4 Advanced Job Features** (Priority: P1)
- [ ] Job detail page (full page, not modal)
- [ ] Save/bookmark jobs
- [ ] Job recommendations based on AI matching
- [ ] Job alerts (email notifications for matches)
- [ ] Similar jobs section
- [ ] Company profile pages
- [ ] Job sharing (social media, email)

**2.5 Application Management** (Priority: P1)
- [ ] Employer dashboard for applications
- [ ] Application status workflow
- [ ] Interview scheduling interface
- [ ] Notes and ratings on applicants
- [ ] Bulk actions on applications
- [ ] Email templates for candidate communication
- [ ] Application tracking timeline

**2.6 Search Enhancements** (Priority: P2)
- [ ] Elasticsearch integration
- [ ] Advanced filters (salary, date, skills)
- [ ] Saved searches
- [ ] Search history
- [ ] Boolean search operators
- [ ] Autocomplete suggestions

### Phase 3: AI & Analytics (Q3 2026) 📊

**Timeline:** Months 7-9
**Status:** 0% Complete

**Features:**

**3.1 Advanced AI Matching** (Priority: P1)
- [ ] Machine learning model for candidate-job matching
- [ ] Success prediction algorithm
- [ ] Skills gap analysis
- [ ] Career path recommendations
- [ ] Interview question suggestions based on job
- [ ] Resume parsing and auto-population
- [ ] Job description optimization suggestions

**3.2 Analytics Dashboards** (Priority: P1)
- [ ] Job seeker: Application funnel, profile views, match quality
- [ ] Employer: Time-to-hire, cost-per-hire, candidate pipeline
- [ ] Agency: Placement rate, revenue tracking, client ROI
- [ ] Admin: Platform metrics, user growth, engagement

**3.3 Reporting** (Priority: P2)
- [ ] Custom report builder
- [ ] Scheduled reports (email delivery)
- [ ] Export reports (PDF, Excel, CSV)
- [ ] Data visualization (charts, graphs)
- [ ] Benchmarking against industry averages

**3.4 Profile Intelligence** (Priority: P2)
- [ ] LinkedIn data import and sync
- [ ] GitHub contribution analysis
- [ ] Behance portfolio scoring
- [ ] Skill endorsements
- [ ] Recommendations and testimonials
- [ ] Profile completeness score
- [ ] Profile SEO optimization

### Phase 4: Communication & Collaboration (Q4 2026) 💬

**Timeline:** Months 10-12
**Status:** 0% Complete

**Features:**

**4.1 Messaging System** (Priority: P1)
- [ ] In-app messaging (employer ↔ candidate)
- [ ] Message threads and organization
- [ ] File attachments in messages
- [ ] Message notifications
- [ ] Read receipts
- [ ] Block/report users
- [ ] Message templates

**4.2 Interview Scheduling** (Priority: P1)
- [ ] Calendar integration (Google Calendar, Outlook)
- [ ] Interview time slot selection
- [ ] Automated reminders
- [ ] Video interview integration (Zoom, Teams)
- [ ] Interview feedback forms
- [ ] Reschedule/cancel interface

**4.3 Collaboration Tools** (Priority: P2)
- [ ] Team accounts for employers
- [ ] Role-based permissions (hiring manager, recruiter, interviewer)
- [ ] Internal notes and comments
- [ ] Candidate evaluation scorecards
- [ ] Hiring pipeline stages
- [ ] Approval workflows

**4.4 Agency Features** (Priority: P2)
- [ ] Multi-client management
- [ ] Commission tracking
- [ ] Talent pool segmentation
- [ ] Client portal access
- [ ] Placement reports
- [ ] Revenue forecasting

### Phase 5: Marketplace & Monetization (2027) 💰

**Timeline:** Year 2
**Status:** 0% Complete

**Features:**

**5.1 Premium Features** (Priority: P1)
- [ ] Featured job listings (homepage placement)
- [ ] Urgent hiring badge (fast-track applications)
- [ ] Profile boost for job seekers
- [ ] Extended job posting duration (30, 60, 90 days)
- [ ] Unlimited applicants per job
- [ ] Priority customer support
- [ ] Custom branding for agencies

**5.2 Marketplace** (Priority: P2)
- [ ] Resume writing services
- [ ] Interview coaching
- [ ] Skills training courses
- [ ] Background check services
- [ ] Video interview platforms
- [ ] Assessment tools
- [ ] HR software integrations

**5.3 API & Integrations** (Priority: P1)
- [ ] REST API for third-party integrations
- [ ] Webhook system for real-time events
- [ ] ATS integrations (Greenhouse, Lever, etc.)
- [ ] HRIS integrations (BambooHR, Workday)
- [ ] Job board syndication (Indeed, Monster, etc.)
- [ ] Social media posting automation
- [ ] Analytics tool integrations (Google Analytics, Mixpanel)

**5.4 White Label Solution** (Priority: P2)
- [ ] Custom domain mapping
- [ ] Full branding customization
- [ ] Custom email templates
- [ ] Dedicated support
- [ ] Custom integrations
- [ ] SLA guarantees
- [ ] Advanced security features

### Phase 6: Mobile & Global Expansion (2027-2028) 🌍

**Timeline:** Year 2-3
**Status:** 0% Complete

**Features:**

**6.1 Mobile Applications** (Priority: P1)
- [ ] iOS app (native Swift)
- [ ] Android app (native Kotlin)
- [ ] Push notifications
- [ ] Offline mode
- [ ] Mobile-optimized workflows
- [ ] Biometric authentication
- [ ] App Store and Play Store optimization

**6.2 Multi-Language Support** (Priority: P2)
- [ ] Internationalization (i18n) framework
- [ ] Translation management system
- [ ] Language switcher
- [ ] RTL support (Arabic, Hebrew)
- [ ] Localized content
- [ ] Currency conversion
- [ ] Time zone handling

**6.3 Regional Expansion** (Priority: P2)
- [ ] EU GDPR compliance (full)
- [ ] California CCPA compliance
- [ ] Regional payment methods
- [ ] Local job boards integration
- [ ] Country-specific job categories
- [ ] Regional customer support

---

## 9. Appendix

### 9.1 Quick Reference

**Important URLs:**

| Resource | URL |
|----------|-----|
| **Production** |  |
| Landing Page | https://hiresmart.startupstreet.in |
| App Dashboard | https://app-hiresmart.startupstreet.in/dashboard |
| Registration | https://app-hiresmart.startupstreet.in/register |
| Login | https://app-hiresmart.startupstreet.in/login |
| **Documentation** |  |
| Main README | /README.md |
| Testing Guide | /TESTING_GUIDE.md |
| Deployment Guide | /DEPLOYMENT_GUIDE.md |
| Implementation Guide | /IMPLEMENTATION_GUIDE.md |
| **External Resources** |  |
| WordPress Docs | https://developer.wordpress.org/ |
| Stripe API Docs | https://stripe.com/docs/api |
| Google OAuth Guide | https://developers.google.com/identity/protocols/oauth2 |

**Key Configuration Files:**

| File | Location | Purpose |
|------|----------|---------|
| wp-config.php | Site root | WordPress configuration |
| .htaccess | Site root | Apache rewrite rules |
| style.css | Theme root | Theme metadata |
| functions.php | Theme root | Theme functionality |
| hiresmart.php | Plugin root | Plugin main file |

**Database Tables:**

| Table Name | Purpose |
|------------|---------|
| wp_hiresmart_profiles | User profiles and AI scores |
| wp_hiresmart_subscriptions | Subscription records |
| wp_hiresmart_payment_methods | Saved payment methods |
| wp_hiresmart_jobs | Job postings |
| wp_hiresmart_applications | Job applications |
| wp_hiresmart_coins | AI coins transactions (future) |
| wp_hiresmart_notifications | User notifications (future) |

### 9.2 Glossary

**Terms & Definitions:**

- **AI Coins**: Virtual currency used for premium actions (posting jobs, boosting profiles)
- **ATS**: Applicant Tracking System - software for managing recruitment
- **EQ**: Emotional Quotient - measure of emotional intelligence (30-100 scale)
- **IQ**: Intelligence Quotient - measure of cognitive abilities (70-150 scale)
- **Profile Sync**: Integration of external profiles (LinkedIn, GitHub, etc.) to enhance AI scores
- **SQ**: Social Quotient - measure of social skills and teamwork (30-100 scale)
- **SSO**: Single Sign-On - authentication method allowing access to multiple systems with one login
- **CSRF**: Cross-Site Request Forgery - security vulnerability prevented by nonces
- **XSS**: Cross-Site Scripting - security vulnerability prevented by output escaping
- **SQL Injection**: Database attack prevented by prepared statements
- **GDPR**: General Data Protection Regulation - EU data privacy law
- **PCI DSS**: Payment Card Industry Data Security Standard - security standard for credit card processing

### 9.3 Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | Feb 2026 | Initial release - MVP complete | StartupStreet |
| 0.9.0 | Jan 2026 | Beta release - Testing phase | StartupStreet |
| 0.5.0 | Dec 2025 | Alpha release - Core features | StartupStreet |

### 9.4 Support & Contact

**Technical Support:**
- Email: support@hiresmart.com
- Documentation: https://docs.hiresmart.com
- Community Forum: https://community.hiresmart.com

**Business Inquiries:**
- Email: hello@hiresmart.com
- Sales: sales@hiresmart.com
- Partnerships: partners@hiresmart.com

**Development:**
- GitHub: https://github.com/StartupStreet/HireSmart-Website-for-WordPress
- Issue Tracker: https://github.com/StartupStreet/HireSmart-Website-for-WordPress/issues
- Contributing: See CONTRIBUTING.md

### 9.5 License

**GPL v2 or later**

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

### 9.6 Acknowledgments

**Built With:**
- WordPress - Content Management System
- PHP - Server-side programming language
- MySQL - Database management system
- Font Awesome - Icon library
- Stripe - Payment processing
- jQuery - JavaScript library

**Special Thanks:**
- WordPress Community
- Open Source Contributors
- Beta Testers
- Early Adopters

---

## Document End

**Document Statistics:**
- Total Sections: 9
- Total Pages: ~60 (when printed)
- Word Count: ~15,000+
- Last Updated: February 2026
- Version: 1.0.0

**Next Steps:**
1. Review this document thoroughly
2. Share with stakeholders for feedback
3. Use as reference during development
4. Update as features evolve
5. Refer to during troubleshooting

**Related Documents:**
- README.md - Project overview
- TESTING_GUIDE.md - Testing procedures
- DEPLOYMENT_GUIDE.md - Deployment instructions
- CENTRALIZED_JOB_SYSTEM.md - Job system details
- JOB_EXPIRY_AND_ACCESS_CONTROL.md - Access control details

---

*This document serves as the comprehensive requirements and workflow blueprint for the HireSmart AI-Powered Job Portal. It should be treated as the single source of truth for understanding the complete system architecture, features, workflows, and technical specifications.*

**Maintained by:** StartupStreet Development Team  
**Questions?** Contact: support@hiresmart.com

