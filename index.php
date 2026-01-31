<?php
/**
 * HireSmart Landing Page Template
 * 
 * @package HireSmart
 * @version 1.0.0
 */

get_header(); ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <h1>Welcome to <span>HireSmart</span></h1>
            <p class="tagline">AI-Powered Job Portal & Career Builder with Advanced ATS</p>
            <p class="description">
                Bridge the gap between job seekers, employers, and agencies with neural AI-powered insights. 
                Save time, make smarter hiring decisions, and accelerate your career growth.
            </p>
            <div class="hero-buttons">
                <?php if (is_user_logged_in()): ?>
                    <a href="<?php echo site_url('/dashboard'); ?>" class="btn-primary">Go to Dashboard</a>
                    <a href="<?php echo site_url('/profile'); ?>" class="btn-secondary">View Profile</a>
                <?php else: ?>
                    <a href="<?php echo site_url('/register'); ?>" class="btn-primary">Get Started Free</a>
                    <a href="<?php echo site_url('/login'); ?>" class="btn-secondary">Sign In</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="features-section">
    <div class="container">
        <h2 class="section-title">Powerful AI Features</h2>
        <p class="section-subtitle">
            Leverage cutting-edge neural AI technology to revolutionize your hiring and job search experience
        </p>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🧠</div>
                <h3>Neural AI Matching</h3>
                <p>
                    Advanced machine learning algorithms analyze skills, experience, and culture fit 
                    to connect the right candidates with the right opportunities in real-time.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">⚡</div>
                <h3>Smart ATS Integration</h3>
                <p>
                    Comprehensive Applicant Tracking System with intelligent automation, 
                    resume parsing, and candidate management to streamline your hiring workflow.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Real-Time Analytics</h3>
                <p>
                    Get actionable insights with advanced analytics dashboard tracking 
                    applications, engagement, and hiring success rates with predictive intelligence.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">🎯</div>
                <h3>Targeted Job Recommendations</h3>
                <p>
                    AI-powered job suggestions based on skills, preferences, career goals, 
                    and market trends to help candidates find their perfect role faster.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">🤝</div>
                <h3>Agency Collaboration</h3>
                <p>
                    Seamless integration for recruitment agencies to manage multiple clients, 
                    candidates, and job postings from a unified intelligent platform.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">🔒</div>
                <h3>Secure & Compliant</h3>
                <p>
                    Enterprise-grade security with GDPR compliance, encrypted data storage, 
                    and role-based access control to protect sensitive information.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Use Cases Section -->
<section id="use-cases" class="usecases-section">
    <div class="container">
        <h2 class="section-title">Built for Everyone</h2>
        <p class="section-subtitle">
            Whether you're seeking a job, hiring talent, or running an agency, HireSmart has you covered
        </p>
        
        <div class="usecases-grid">
            <div class="usecase-card">
                <h3>🎓 For Job Seekers</h3>
                <p class="persona">Career Professionals & Fresh Graduates</p>
                <p>
                    Find your dream job faster with AI-powered recommendations tailored to your 
                    skills and career aspirations. Get real-time notifications and insights.
                </p>
                <ul>
                    <li>Personalized job matching based on skills and preferences</li>
                    <li>AI resume optimization and feedback</li>
                    <li>Application tracking and status updates</li>
                    <li>Career path recommendations and insights</li>
                    <li>Interview preparation resources</li>
                </ul>
            </div>
            
            <div class="usecase-card">
                <h3>🏢 For Employers</h3>
                <p class="persona">Companies & Hiring Managers</p>
                <p>
                    Reduce time-to-hire and find the best candidates with intelligent screening, 
                    automated workflows, and data-driven hiring decisions.
                </p>
                <ul>
                    <li>AI-powered candidate screening and ranking</li>
                    <li>Automated interview scheduling</li>
                    <li>Collaborative hiring tools for teams</li>
                    <li>Custom branded career pages</li>
                    <li>Comprehensive analytics and reporting</li>
                </ul>
            </div>
            
            <div class="usecase-card">
                <h3>🎯 For Agencies</h3>
                <p class="persona">Recruitment Agencies & Headhunters</p>
                <p>
                    Manage multiple clients and candidates efficiently with advanced tools 
                    designed specifically for recruitment professionals.
                </p>
                <ul>
                    <li>Multi-client management dashboard</li>
                    <li>Candidate database and talent pool management</li>
                    <li>Commission tracking and invoicing</li>
                    <li>White-label solutions available</li>
                    <li>Priority support and dedicated account manager</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Differentiators Section -->
<section id="differentiators" class="differentiators-section">
    <div class="container">
        <h2 class="section-title">Why Choose HireSmart?</h2>
        <p class="section-subtitle">
            What makes us different from traditional ATS and job portals
        </p>
        
        <div class="diff-grid">
            <div class="diff-card">
                <span class="diff-badge">AI-First</span>
                <h3>Neural AI Technology</h3>
                <p>
                    Unlike traditional keyword-based systems, our neural AI understands context, 
                    skills, and potential, making smarter connections between candidates and roles.
                </p>
            </div>
            
            <div class="diff-card">
                <span class="diff-badge">Time-Saving</span>
                <h3>95% Faster Matching</h3>
                <p>
                    Reduce manual screening time from hours to minutes. Our AI processes thousands 
                    of profiles instantly to find the best matches.
                </p>
            </div>
            
            <div class="diff-card">
                <span class="diff-badge">All-in-One</span>
                <h3>Unified Platform</h3>
                <p>
                    Job seekers, employers, and agencies all work seamlessly on one platform, 
                    eliminating fragmentation and improving collaboration.
                </p>
            </div>
            
            <div class="diff-card">
                <span class="diff-badge">Predictive</span>
                <h3>Success Prediction</h3>
                <p>
                    Our AI predicts candidate success rates and retention probability, 
                    helping you make data-driven hiring decisions with confidence.
                </p>
            </div>
            
            <div class="diff-card">
                <span class="diff-badge">Transparent</span>
                <h3>Real-Time Insights</h3>
                <p>
                    Full visibility into the hiring process with detailed analytics, 
                    progress tracking, and actionable recommendations at every stage.
                </p>
            </div>
            
            <div class="diff-card">
                <span class="diff-badge">Scalable</span>
                <h3>Grows With You</h3>
                <p>
                    From startups to enterprises, our platform scales effortlessly to meet 
                    your needs without compromising on performance or features.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Section -->
<section id="pricing" class="pricing-section">
    <div class="container">
        <h2 class="section-title">Simple, Transparent Pricing</h2>
        <p class="section-subtitle">
            Choose the plan that fits your needs. No hidden fees, cancel anytime.
        </p>
        
        <div class="pricing-grid">
            <div class="pricing-card">
                <h3>Job Seeker</h3>
                <div class="price">Free<span></span></div>
                <ul>
                    <li>Unlimited job applications</li>
                    <li>AI-powered job recommendations</li>
                    <li>Resume builder and optimization</li>
                    <li>Application tracking</li>
                    <li>Email notifications</li>
                    <li>Basic analytics</li>
                </ul>
                <a href="#" class="btn">Get Started</a>
            </div>
            
            <div class="pricing-card featured">
                <span class="featured-badge">Most Popular</span>
                <h3>Employer Pro</h3>
                <div class="price">$299<span>/month</span></div>
                <ul>
                    <li>Up to 10 active job postings</li>
                    <li>AI candidate screening & ranking</li>
                    <li>Advanced ATS features</li>
                    <li>Team collaboration tools</li>
                    <li>Custom branded career page</li>
                    <li>Priority support</li>
                    <li>Advanced analytics dashboard</li>
                </ul>
                <a href="#" class="btn">Start Free Trial</a>
            </div>
            
            <div class="pricing-card">
                <h3>Agency Enterprise</h3>
                <div class="price">$999<span>/month</span></div>
                <ul>
                    <li>Unlimited job postings</li>
                    <li>Multi-client management</li>
                    <li>Advanced talent pool management</li>
                    <li>White-label options</li>
                    <li>Commission tracking</li>
                    <li>Dedicated account manager</li>
                    <li>Custom integrations</li>
                    <li>API access</li>
                </ul>
                <a href="#" class="btn">Contact Sales</a>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <h2>Ready to Transform Your Hiring Process?</h2>
        <p>Join thousands of companies and job seekers who are already using HireSmart</p>
        <div class="hero-buttons">
            <a href="#" class="btn-primary">Start Free Trial</a>
            <a href="#" class="btn-secondary">Schedule Demo</a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
