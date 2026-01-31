# Quick Start: Preview & Deploy HireSmart

## How to Preview the Platform

### Option 1: Instant Preview (No Setup Required) ⚡

The fastest way to see the landing page:

```bash
# Navigate to the repository
cd /path/to/HireSmart-Website-for-WordPress

# Open preview.html in your browser
# Mac:
open preview.html

# Linux:
xdg-open preview.html

# Windows:
start preview.html
```

**What you'll see:** Complete landing page with all sections (features, pricing, use cases)

### Option 2: Local Web Server (Better Experience) 🌐

For a more realistic preview with proper URLs:

```bash
# Using Python (comes pre-installed on Mac/Linux)
python3 -m http.server 8000

# Then open: http://localhost:8000/preview.html
```

### Option 3: Full WordPress Test (Complete Experience) 🚀

For testing authentication and dashboards, follow the [PREVIEW_GUIDE.md](./PREVIEW_GUIDE.md)

---

## How to Deploy to Your Domains

You want:
- **hiresmart.startupstreet.in** = Landing page (before login)
- **app-hiresmart.startupstreet.in** = Dashboard (after login)

### Quick Deploy Checklist

#### 1. DNS Setup (5 minutes)

Add these DNS records in your domain provider:

```
Type    Name              Value (Your Server IP)    TTL
A       hiresmart         123.456.789.101          300
A       app-hiresmart     123.456.789.101          300
```

Wait 5-15 minutes for DNS propagation. Verify with:
```bash
ping hiresmart.startupstreet.in
ping app-hiresmart.startupstreet.in
```

#### 2. Server Setup (Choose One Method)

**Method A: WordPress Multisite (Recommended - Easier)**

Best for shared sessions and single database:

1. Install WordPress at your main domain
2. Enable multisite with subdomains
3. Create two sites: `hiresmart` and `app-hiresmart`
4. Deploy theme and plugin
5. Configure cross-domain cookies

📖 **Full Guide**: [DEPLOYMENT_GUIDE.md - Option 1](./DEPLOYMENT_GUIDE.md#option-1-wordpress-multisite-setup-recommended)

**Method B: Separate WordPress Installations**

Best for complete isolation:

1. Install WordPress twice (different folders)
2. Configure Apache/Nginx virtual hosts
3. Deploy files to each installation
4. Share authentication keys
5. Configure cross-domain cookies

📖 **Full Guide**: [DEPLOYMENT_GUIDE.md - Option 2](./DEPLOYMENT_GUIDE.md#option-2-separate-wordpress-installations-alternative)

#### 3. File Deployment (10 minutes)

**Landing Page (hiresmart.startupstreet.in):**
```bash
# Copy theme files
cp -r /path/to/repo/* /var/www/hiresmart/wp-content/themes/hiresmart/

# Copy plugin
cp -r /path/to/repo/hiresmart-plugin /var/www/hiresmart/wp-content/plugins/
```

**Dashboard (app-hiresmart.startupstreet.in):**
```bash
# Copy plugin only (it creates all pages automatically)
cp -r /path/to/repo/hiresmart-plugin /var/www/app-hiresmart/wp-content/plugins/
```

#### 4. WordPress Configuration (15 minutes)

**Both domains need same authentication keys in wp-config.php:**

```php
// Use wp-config-sample.php as template
// Key requirement: SAME keys on both domains for session sharing

define('COOKIE_DOMAIN', '.startupstreet.in');  // Note the leading dot!

// Copy these EXACT values to both wp-config.php files:
define('AUTH_KEY', 'your-unique-phrase-here');
define('SECURE_AUTH_KEY', 'your-unique-phrase-here');
// ... (use same for all 8 keys)
```

Generate keys at: https://api.wordpress.org/secret-key/1.1/salt/

#### 5. SSL Certificates (10 minutes)

Install SSL with Certbot (free):

```bash
# Install Certbot
sudo apt-get update
sudo apt-get install certbot python3-certbot-apache
# OR for Nginx: python3-certbot-nginx

# Get certificates for both domains
sudo certbot --apache -d hiresmart.startupstreet.in -d app-hiresmart.startupstreet.in
# OR for Nginx:
sudo certbot --nginx -d hiresmart.startupstreet.in -d app-hiresmart.startupstreet.in
```

#### 6. Activate & Configure (10 minutes)

**On hiresmart.startupstreet.in:**
1. Login to WordPress admin
2. Activate "HireSmart" theme
3. Activate "HireSmart" plugin
4. Landing page should display

**On app-hiresmart.startupstreet.in:**
1. Login to WordPress admin
2. Activate "HireSmart" plugin (creates pages automatically)
3. Go to Settings → Reading
4. Set Homepage to "Dashboard" page
5. Save

#### 7. Test Complete Flow (5 minutes)

1. Visit https://hiresmart.startupstreet.in
   - ✅ Landing page displays
   - ✅ Click "Get Started"
   
2. Should redirect to https://app-hiresmart.startupstreet.in/register
   - ✅ Registration form displays
   - ✅ Fill and submit
   
3. Should redirect to dashboard
   - ✅ Dashboard displays based on account type
   - ✅ User is logged in

4. Navigate back to https://hiresmart.startupstreet.in
   - ✅ Header shows "Dashboard" and "Logout"
   - ✅ Session maintained across domains

---

## Configuration Files Provided

Ready-to-use configuration templates:

1. **wp-config-sample.php** - WordPress configuration with:
   - Cross-domain cookie settings
   - Security keys (use same on both domains)
   - Stripe API keys placeholders
   - OAuth credentials placeholders
   - Performance settings

2. **apache-vhosts.conf** - Apache virtual host configuration
   - Separate configs for both domains
   - SSL ready
   - Security headers
   - WordPress permalink support

3. **nginx-vhosts.conf** - Nginx server block configuration
   - Separate configs for both domains
   - SSL ready
   - PHP-FPM configuration
   - Security headers

---

## Troubleshooting

### Q: Landing page doesn't show?
**A:** Check theme is activated at hiresmart.startupstreet.in

### Q: Registration form doesn't appear?
**A:** Check plugin is activated at app-hiresmart.startupstreet.in

### Q: Login doesn't work across domains?
**A:** Verify COOKIE_DOMAIN is set to `.startupstreet.in` (with leading dot) and same auth keys on both domains

### Q: Dashboard pages missing?
**A:** Deactivate and reactivate plugin on app-hiresmart.startupstreet.in

### Q: SSL not working?
**A:** Run certbot again: `sudo certbot --apache -d hiresmart.startupstreet.in -d app-hiresmart.startupstreet.in`

---

## Need More Help?

📖 **Detailed Guides:**
- [PREVIEW_GUIDE.md](./PREVIEW_GUIDE.md) - Local testing instructions
- [DEPLOYMENT_GUIDE.md](./DEPLOYMENT_GUIDE.md) - Complete deployment walkthrough
- [IMPLEMENTATION_GUIDE.md](./IMPLEMENTATION_GUIDE.md) - Technical details

💬 **Support:**
- Check [README.md](./README.md) for project overview
- Review [PROJECT_SUMMARY.md](./PROJECT_SUMMARY.md) for feature list
- Check WordPress logs: `/wp-content/debug.log`

---

## Summary: Your Complete Setup

```
✅ Preview locally: Open preview.html in browser
✅ Deploy landing page to: hiresmart.startupstreet.in
✅ Deploy dashboard app to: app-hiresmart.startupstreet.in
✅ Configure cross-domain sessions with shared cookies
✅ Install SSL certificates on both domains
✅ Test complete user flow from landing → register → dashboard
```

**Time Estimate:** 1-2 hours for complete deployment

**Recommended Path:** Multisite setup for easier management

🚀 **Ready to launch!**
