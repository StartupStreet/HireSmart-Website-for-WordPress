# HireSmart Complete Feature Testing Guide

## Overview
This document provides a comprehensive testing guide for all HireSmart features to ensure everything works correctly in production.

## Prerequisites
- WordPress 5.0+ installed
- PHP 7.4+ with MySQL 5.7+
- HireSmart theme activated
- HireSmart plugin activated

## 1. Landing Page Testing

### Test 1.1: Navigation Links
**Steps:**
1. Open landing page
2. Verify navigation menu shows: Features, Use Cases, Why Us, Pricing, Sign In
3. Verify "Get Started" button visible in header
4. Click "Sign In" → Should navigate to `/login` page
5. Click "Get Started" → Should navigate to `/register` page

**Expected Result:** All links work correctly

### Test 1.2: Hero Section CTAs
**Steps:**
1. On landing page, find hero section
2. Verify two buttons: "Get Started Free" and "Sign In"
3. Click "Get Started Free" → Should navigate to `/register`
4. Click "Sign In" → Should navigate to `/login`

**Expected Result:** Both CTAs navigate correctly

## 2. Registration Testing

### Test 2.1: Basic Registration (Free Plan)
**Steps:**
1. Navigate to `/register`
2. Fill in:
   - First Name: "John"
   - Last Name: "Doe"
   - Email: "john.doe@example.com"
   - Password: "TestPass123"
   - Account Type: "Job Seeker"
   - Subscription: "Free" (default selected)
   - Check "Terms" checkbox
3. Click "Create Account"

**Expected Result:**
- Form submits via AJAX
- Success message displayed
- User logged in automatically
- Redirected to `/dashboard`

### Test 2.2: Paid Plan Registration
**Steps:**
1. Navigate to `/register`
2. Fill in all fields
3. Select "Startup" ($299/month) subscription
4. Submit form

**Expected Result:**
- Form submits successfully
- Redirected to `/billing?payment_required=1`
- Billing page shows notice to add payment method

### Test 2.3: Form Validation
**Steps:**
1. Navigate to `/register`
2. Click "Create Account" without filling fields
3. Verify error messages appear
4. Fill one field at a time and verify errors clear

**Expected Result:**
- Red borders on empty required fields
- Error messages appear inline
- Toast notification shows "Please fill in all required fields"

### Test 2.4: Social Registration
**Steps:**
1. Navigate to `/register`
2. Click "Continue with Google"
3. Verify informational alert appears

**Expected Result:**
- Alert explains OAuth would be initiated
- Loading overlay appears briefly
- Toast notification shows status

## 3. Login Testing

### Test 3.1: Email/Password Login
**Steps:**
1. Navigate to `/login`
2. Enter email and password
3. Optionally check "Remember me"
4. Click "Sign In"

**Expected Result:**
- AJAX submission
- Success message
- Redirect to `/dashboard`

### Test 3.2: Social Login
**Steps:**
1. Navigate to `/login`
2. Click one of: Google, LinkedIn, GitHub
3. Verify informational message

**Expected Result:**
- Alert shows OAuth process
- In production, would open OAuth popup

### Test 3.3: Login Validation
**Steps:**
1. Navigate to `/login`
2. Try submitting empty form
3. Verify validation works

**Expected Result:**
- Form validation prevents empty submission
- Error messages show

## 4. Dashboard Testing

### Test 4.1: Job Seeker Dashboard
**Steps:**
1. Login as Job Seeker
2. Navigate to `/dashboard`
3. Verify sections displayed:
   - Stats cards (4 cards)
   - Recent Activity list
   - AI Profile Insights with scores
   - "Take AI Assessment" button

**Expected Result:**
- Dashboard renders correctly
- Mock data displayed
- All sections visible

### Test 4.2: Employer Dashboard
**Steps:**
1. Login as Employer account
2. Navigate to `/dashboard`
3. Verify sections:
   - Active Jobs, Applicants, Interviews, Positions stats
   - Recent Activity
   - Action buttons: "Post New Job", "View Applicants"

**Expected Result:**
- Employer-specific dashboard shows
- Different stats than Job Seeker
- Action buttons present

### Test 4.3: Agency Dashboard
**Steps:**
1. Login as Agency account
2. Navigate to `/dashboard`
3. Verify sections:
   - Clients, Placements, Candidates, Revenue stats
   - Recent Activity
   - Action buttons: "Add Client", "Manage Candidates"

**Expected Result:**
- Agency-specific dashboard displays
- Revenue shown in dollars
- Appropriate action buttons

## 5. AI Assessment Testing

### Test 5.1: Assessment Modal
**Steps:**
1. On dashboard, click "Take AI Assessment"
2. Confirm dialog
3. Verify modal appears with 6 questions:
   - Logical Reasoning
   - Problem Solving
   - Emotional Awareness
   - Empathy
   - Communication Skills
   - Teamwork Ability
4. Adjust sliders (1-10 range)
5. Click "Submit Assessment"

**Expected Result:**
- Modal displays correctly
- All 6 range inputs work
- Values update as sliders move
- Submit calculates scores
- Success message shows IQ, EQ, SQ scores
- Modal closes
- Page reloads with updated scores

### Test 5.2: Score Calculation
**Steps:**
1. Complete assessment with known values
2. Verify score calculation:
   - IQ = 100 + (logical × 2) + (problem_solving × 2)
   - EQ = 50 + (emotional × 5) + (empathy × 5)
   - SQ = 50 + (communication × 5) + (teamwork × 5)

**Expected Result:**
- Scores calculated correctly
- Stored in database
- Displayed on profile and dashboard

## 6. Profile Testing

### Test 6.1: View Profile
**Steps:**
1. Navigate to `/profile`
2. Verify sections:
   - Personal Information (name, email, account type)
   - AI Profile Scores (IQ, EQ, SQ with bars)
   - "Save Changes" button

**Expected Result:**
- All fields populated with user data
- Scores display correctly
- Progress bars show visual representation

### Test 6.2: Update Profile
**Steps:**
1. On profile page, modify first/last name
2. Click "Save Changes"
3. Verify toast notification
4. Verify changes saved

**Expected Result:**
- AJAX submission
- Success toast appears
- Profile updated in database

### Test 6.3: AI Assessment from Profile
**Steps:**
1. On profile page, click "Take AI Assessment"
2. Complete assessment
3. Verify scores update on profile page

**Expected Result:**
- Modal opens
- Assessment completes
- Scores update and display

## 7. Integrations Testing

### Test 7.1: Add Integration URLs
**Steps:**
1. Navigate to `/integrations`
2. Fill in URLs for:
   - LinkedIn: `https://linkedin.com/in/username`
   - GitHub: `https://github.com/username`
   - Behance: `https://behance.net/username`
   - Canva: `https://canva.com/username`
   - Portfolio: `https://yoursite.com`
3. Click "Save Integrations"

**Expected Result:**
- AJAX submission
- Success toast
- Page reloads
- Status badges show "Connected"

### Test 7.2: Connection Status
**Steps:**
1. On integrations page, verify badges:
   - "Connected" badge for filled URLs (green)
   - "Not Connected" for empty (gray)
2. Add URL to empty integration
3. Save and verify badge changes

**Expected Result:**
- Status badges update correctly
- Visual indication of connection status

## 8. Billing & Subscription Testing

### Test 8.1: View Current Plan
**Steps:**
1. Navigate to `/billing`
2. Verify current plan card shows:
   - Plan name
   - Price
   - Status
   - Renewal date (if applicable)

**Expected Result:**
- Plan details displayed correctly
- Status shows (active/pending/etc.)

### Test 8.2: Add Payment Method
**Steps:**
1. On billing page, click "Add Payment Method"
2. Verify modal opens
3. Fill in card details:
   - Cardholder Name
   - Card Number: 4242 4242 4242 4242
   - Expiry: 12/25
   - CVC: 123
4. Click "Add Card"

**Expected Result:**
- Modal displays
- Form validation works
- Security message shown
- Alert explains Stripe integration
- In production, would tokenize and save

### Test 8.3: Payment Method Management
**Steps:**
1. With payment methods saved
2. Click "Set as Default" on non-default card
3. Verify loading overlay
4. Verify toast notification

**Expected Result:**
- AJAX request sent
- Loading shown
- Toast confirms action
- Page reloads with updated default

### Test 8.4: Remove Payment Method
**Steps:**
1. Click "Remove" on payment method
2. Confirm dialog
3. Verify loading and toast

**Expected Result:**
- Confirmation required
- Loading overlay
- Success toast
- Method removed from list

## 9. Security Testing

### Test 9.1: Unauthenticated Access
**Steps:**
1. Logout
2. Try accessing:
   - `/dashboard`
   - `/profile`
   - `/billing`
   - `/integrations`

**Expected Result:**
- All pages show "Please login" message
- Redirect to login page

### Test 9.2: AJAX Nonce Verification
**Steps:**
1. Open browser console
2. Try AJAX request without nonce
3. Verify rejected

**Expected Result:**
- Request blocked
- Error message returned

### Test 9.3: SQL Injection Protection
**Steps:**
1. In forms, try: `' OR '1'='1`
2. Verify sanitized

**Expected Result:**
- Input sanitized
- No SQL injection possible
- Using prepared statements

## 10. Responsive Design Testing

### Test 10.1: Mobile View
**Steps:**
1. Resize browser to mobile width (375px)
2. Test all pages:
   - Landing page
   - Registration
   - Login
   - Dashboard
   - Profile
   - Billing
   - Integrations

**Expected Result:**
- All pages responsive
- Forms stack vertically
- Navigation adapts
- Stat cards stack
- Readable and usable

### Test 10.2: Tablet View
**Steps:**
1. Resize to tablet width (768px)
2. Test all pages

**Expected Result:**
- Layout adapts appropriately
- 2-column layouts where appropriate
- Touch-friendly buttons

## 11. Browser Compatibility Testing

### Test 11.1: Cross-Browser
**Test in:**
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

**Expected Result:**
- All features work in all browsers
- Consistent appearance
- No JavaScript errors

## 12. Performance Testing

### Test 12.1: Page Load Speed
**Steps:**
1. Use Lighthouse/PageSpeed Insights
2. Test landing page
3. Test dashboard

**Expected Result:**
- Landing page loads < 2 seconds
- Dashboard loads < 3 seconds
- Good performance scores

### Test 12.2: AJAX Response Time
**Steps:**
1. Monitor network tab
2. Submit forms
3. Check response times

**Expected Result:**
- Form submissions < 500ms
- AJAX requests efficient
- No unnecessary requests

## 13. Integration Points (Requires Configuration)

### Test 13.1: Stripe Payment Integration
**Prerequisites:**
- Stripe API keys configured in `wp-config.php`

**Steps:**
1. Add payment method
2. Verify Stripe tokenization
3. Test actual payment

**Expected Result:**
- Card tokenized via Stripe.js
- Payment method saved
- Charge processed (for paid plans)

### Test 13.2: OAuth Social Login
**Prerequisites:**
- OAuth apps configured (Google, LinkedIn, GitHub)
- Client IDs/secrets in `wp-config.php`

**Steps:**
1. Click social login button
2. Complete OAuth flow
3. Verify account created/logged in

**Expected Result:**
- OAuth popup opens
- User authenticates
- Account created/login successful
- Redirect to dashboard

## Troubleshooting Common Issues

### Issue: Pages showing 404
**Solution:** Flush rewrite rules
```bash
wp rewrite flush
```
Or: Settings > Permalinks > Save Changes in WP Admin

### Issue: AJAX not working
**Solution:** Verify:
1. jQuery loaded
2. `hiresmart_ajax` object available
3. Check console for errors
4. Verify nonce generation

### Issue: Styles not loading
**Solution:**
1. Clear browser cache
2. Verify CSS files exist in `assets/css/`
3. Check file permissions
4. Enqueue scripts properly

### Issue: Database errors
**Solution:**
1. Verify tables created (check activation)
2. Check database user permissions
3. Review error logs

## Production Checklist

Before deploying to production:

- [ ] All test cases pass
- [ ] Configure Stripe API keys
- [ ] Set up OAuth applications
- [ ] Enable SSL certificate
- [ ] Set WordPress to production mode
- [ ] Test email sending
- [ ] Configure backup system
- [ ] Set up monitoring
- [ ] Load test with multiple users
- [ ] Security scan completed
- [ ] Performance optimized
- [ ] Documentation updated
- [ ] User training materials ready

## Reporting Issues

When reporting issues, include:
1. Test case number
2. Steps to reproduce
3. Expected vs actual result
4. Screenshots/videos
5. Browser/device info
6. Error messages from console

## Conclusion

This testing guide ensures all HireSmart features work correctly. Follow systematically for best results. Update this document as new features are added.

---

**Document Version:** 1.0  
**Last Updated:** January 31, 2026  
**Maintained by:** StartupStreet Team
