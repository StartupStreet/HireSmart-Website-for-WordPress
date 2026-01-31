# HireSmart Deployment Guide

## Two-Domain Setup: Landing Page + Dashboard Application

This guide explains how to deploy HireSmart with a two-domain architecture:
- **hiresmart.startupstreet.in** - Public landing page (before login)
- **app-hiresmart.startupstreet.in** - Dashboard application (after login)

---

## Architecture Overview

```
hiresmart.startupstreet.in
├── WordPress Installation
├── HireSmart Theme (Landing Page)
└── HireSmart Plugin (Authentication only)

app-hiresmart.startupstreet.in
├── WordPress Installation (shared DB)
├── HireSmart Plugin (Full Application)
└── Dashboard Pages
```

---

## Option 1: WordPress Multisite Setup (Recommended)

This is the cleanest approach using WordPress Multisite with subdomain mapping.

### Step 1: Enable WordPress Multisite

1. **Backup your current WordPress installation**

2. **Edit wp-config.php** (before the "That's all" line):
```php
/* Multisite */
define('WP_ALLOW_MULTISITE', true);
```

3. **Access Network Setup**:
   - Go to WordPress Admin → Tools → Network Setup
   - Choose "Sub-domains"
   - Follow the instructions

4. **Update wp-config.php** with the generated code:
```php
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', true);
define('DOMAIN_CURRENT_SITE', 'startupstreet.in');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);
```

5. **Update .htaccess** with the generated code

### Step 2: Create Subsites

1. **Go to Network Admin → Sites → Add New**

2. **Create Landing Page Site**:
   - Site Address: `hiresmart`
   - Site Title: `HireSmart - AI Job Portal`
   - Admin Email: your-email@domain.com

3. **Create App Site**:
   - Site Address: `app-hiresmart`
   - Site Title: `HireSmart Dashboard`
   - Admin Email: your-email@domain.com

### Step 3: Configure DNS

Add these DNS records to your domain:

```
Type    Name          Value                    TTL
A       hiresmart     your-server-ip          300
A       app-hiresmart your-server-ip          300
CNAME   *.hiresmart   hiresmart.startupstreet.in  300
```

### Step 4: Install SSL Certificates

```bash
# Using Certbot (Let's Encrypt)
sudo certbot --apache -d hiresmart.startupstreet.in -d app-hiresmart.startupstreet.in
```

### Step 5: Deploy Theme and Plugin

**On hiresmart.startupstreet.in:**
1. Switch to this site in Network Admin
2. Go to Themes → Upload
3. Upload and activate HireSmart theme
4. Go to Plugins → Network Activate → HireSmart plugin
5. Verify landing page displays correctly

**On app-hiresmart.startupstreet.in:**
1. Switch to this site in Network Admin
2. Activate a minimal theme (or same HireSmart theme)
3. HireSmart plugin is already network-activated
4. Set homepage to display Dashboard page

### Step 6: Configure Cross-Domain Authentication

Edit `wp-config.php`:
```php
// Cookie domain for single sign-on
define('COOKIE_DOMAIN', '.startupstreet.in');
define('ADMIN_COOKIE_PATH', '/');
define('COOKIEPATH', '/');
define('SITECOOKIEPATH', '/');

// Allow cross-domain AJAX
define('ALLOW_UNFILTERED_UPLOADS', true);
```

---

## Option 2: Separate WordPress Installations (Alternative)

Use this if multisite is not preferred.

### Installation Structure

```
/var/www/
├── hiresmart/              # Landing page
│   ├── wp-content/
│   │   ├── themes/
│   │   │   └── hiresmart/
│   │   └── plugins/
│   │       └── hiresmart-plugin/
│   └── ...
└── app-hiresmart/          # Dashboard app
    ├── wp-content/
    │   ├── themes/
    │   │   └── hiresmart/
    │   └── plugins/
    │       └── hiresmart-plugin/
    └── ...
```

### Step 1: Install WordPress Twice

```bash
# Landing page installation
cd /var/www/hiresmart
wget https://wordpress.org/latest.tar.gz
tar -xzf latest.tar.gz
mv wordpress/* .
rm -rf wordpress latest.tar.gz

# Dashboard installation
cd /var/www/app-hiresmart
wget https://wordpress.org/latest.tar.gz
tar -xzf latest.tar.gz
mv wordpress/* .
rm -rf wordpress latest.tar.gz
```

### Step 2: Configure Database

**Option A: Shared Database (Recommended)**
Use the same database with different table prefixes:

Landing page `wp-config.php`:
```php
$table_prefix = 'wp_main_';
define('DB_NAME', 'hiresmart_db');
```

Dashboard `wp-config.php`:
```php
$table_prefix = 'wp_app_';
define('DB_NAME', 'hiresmart_db');
```

**Option B: Separate Databases**
Create two databases and configure separately.

### Step 3: Configure Apache Virtual Hosts

Create `/etc/apache2/sites-available/hiresmart.conf`:
```apache
<VirtualHost *:80>
    ServerName hiresmart.startupstreet.in
    DocumentRoot /var/www/hiresmart
    
    <Directory /var/www/hiresmart>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/hiresmart_error.log
    CustomLog ${APACHE_LOG_DIR}/hiresmart_access.log combined
</VirtualHost>
```

Create `/etc/apache2/sites-available/app-hiresmart.conf`:
```apache
<VirtualHost *:80>
    ServerName app-hiresmart.startupstreet.in
    DocumentRoot /var/www/app-hiresmart
    
    <Directory /var/www/app-hiresmart>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/app-hiresmart_error.log
    CustomLog ${APACHE_LOG_DIR}/app-hiresmart_access.log combined
</VirtualHost>
```

Enable sites:
```bash
sudo a2ensite hiresmart
sudo a2ensite app-hiresmart
sudo systemctl reload apache2
```

### Step 4: Install SSL

```bash
sudo certbot --apache -d hiresmart.startupstreet.in
sudo certbot --apache -d app-hiresmart.startupstreet.in
```

### Step 5: Deploy Files

**Landing Page (hiresmart.startupstreet.in):**
```bash
# Upload theme
cp -r hiresmart-theme/* /var/www/hiresmart/wp-content/themes/hiresmart/

# Upload plugin
cp -r hiresmart-plugin/* /var/www/hiresmart/wp-content/plugins/hiresmart-plugin/

# Set permissions
sudo chown -R www-data:www-data /var/www/hiresmart
```

**Dashboard (app-hiresmart.startupstreet.in):**
```bash
# Upload plugin
cp -r hiresmart-plugin/* /var/www/app-hiresmart/wp-content/plugins/hiresmart-plugin/

# Set permissions
sudo chown -R www-data:www-data /var/www/app-hiresmart
```

### Step 6: Configure Cross-Domain Sessions

Add to both `wp-config.php` files:
```php
// Shared authentication
define('AUTH_KEY',         'your-unique-phrase-here');
define('SECURE_AUTH_KEY',  'your-unique-phrase-here');
define('LOGGED_IN_KEY',    'your-unique-phrase-here');
define('NONCE_KEY',        'your-unique-phrase-here');
define('AUTH_SALT',        'your-unique-phrase-here');
define('SECURE_AUTH_SALT', 'your-unique-phrase-here');
define('LOGGED_IN_SALT',   'your-unique-phrase-here');
define('NONCE_SALT',       'your-unique-phrase-here');

// Cookie domain for SSO
define('COOKIE_DOMAIN', '.startupstreet.in');
```

---

## Post-Deployment Configuration

### 1. Configure Landing Page (hiresmart.startupstreet.in)

1. **Activate HireSmart Theme**:
   - WordPress Admin → Appearance → Themes
   - Activate "HireSmart"

2. **Activate Plugin**:
   - Plugins → Activate "HireSmart - AI-Powered Job Portal"

3. **Set Homepage**:
   - Settings → Reading
   - Set "Your homepage displays" to "A static page"
   - Homepage: Select the home page
   - Or leave as "Your latest posts" if using index.php

4. **Configure Navigation**:
   - The theme automatically adds Login/Register links
   - These should redirect to app-hiresmart.startupstreet.in

5. **Update Plugin Settings**:
   Edit `hiresmart-plugin/includes/class-hiresmart-core.php`:
   ```php
   // Change redirect URLs to app subdomain
   public function render_login() {
       if (is_user_logged_in()) {
           wp_redirect('https://app-hiresmart.startupstreet.in/dashboard');
           exit;
       }
       // ... rest of code
   }
   ```

### 2. Configure Dashboard (app-hiresmart.startupstreet.in)

1. **Activate Plugin**:
   - Plugins → Activate "HireSmart - AI-Powered Job Portal"
   - This creates all necessary pages automatically

2. **Set Homepage to Dashboard**:
   - Settings → Reading
   - Homepage: Select "Dashboard" page

3. **Hide Landing Page Content**:
   - You may want to redirect root to /dashboard
   - Add to functions.php:
   ```php
   function hiresmart_app_redirect() {
       if (is_front_page() && !is_user_logged_in()) {
           wp_redirect('https://hiresmart.startupstreet.in');
           exit;
       }
   }
   add_action('template_redirect', 'hiresmart_app_redirect');
   ```

### 3. Update Cross-Domain Links

Edit theme header.php on landing page:
```php
<?php if (is_user_logged_in()): ?>
    <a href="https://app-hiresmart.startupstreet.in/dashboard" class="cta-button">Dashboard</a>
<?php else: ?>
    <a href="https://app-hiresmart.startupstreet.in/register" class="cta-button">Get Started</a>
<?php endif; ?>
```

Edit plugin templates to use app subdomain:
```php
// In templates/register.php and login.php
$redirect_url = 'https://app-hiresmart.startupstreet.in/dashboard';
```

---

## Testing the Deployment

### 1. Test Landing Page
- Visit https://hiresmart.startupstreet.in
- Verify landing page loads
- Check all sections display correctly
- Click "Get Started" → Should go to app-hiresmart.startupstreet.in/register

### 2. Test Registration
- Visit https://app-hiresmart.startupstreet.in/register
- Fill out registration form
- Select account type and subscription
- Verify redirect to dashboard or billing

### 3. Test Login
- Visit https://app-hiresmart.startupstreet.in/login
- Enter credentials
- Verify redirect to dashboard
- Check user session persists

### 4. Test Dashboard
- Verify correct dashboard loads based on account type
- Check all stats display
- Test navigation between pages
- Verify logout works and redirects to landing page

### 5. Test Cross-Domain Session
- Login at app-hiresmart.startupstreet.in
- Navigate to hiresmart.startupstreet.in
- Header should show "Dashboard" and "Logout" links
- Session should be maintained

---

## Troubleshooting

### Issue: Cross-domain cookies not working

**Solution**: Ensure cookie domain is set correctly:
```php
define('COOKIE_DOMAIN', '.startupstreet.in'); // Note the leading dot
```

And ensure both domains use HTTPS (SSL required for secure cookies).

### Issue: Redirects causing loops

**Solution**: Check redirect logic and ensure:
- Landing page doesn't redirect logged-in users unnecessarily
- App domain properly handles authentication state
- Logout redirects back to landing page

### Issue: Assets not loading

**Solution**: Update asset URLs in plugin:
```php
// Use relative URLs or full URLs
wp_enqueue_style('hiresmart-main', 
    HIRESMART_PLUGIN_URL . 'assets/css/hiresmart.css', 
    array(), 
    HIRESMART_VERSION
);
```

### Issue: Database connection errors

**Solution**: Verify database credentials in wp-config.php:
```php
define('DB_NAME', 'hiresmart_db');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_HOST', 'localhost');
```

---

## Security Checklist

- [ ] SSL certificates installed on both domains
- [ ] HTTPS enforced (redirect HTTP to HTTPS)
- [ ] Cookie domain set to `.startupstreet.in`
- [ ] Secure authentication keys set in wp-config.php
- [ ] File permissions set correctly (755 for directories, 644 for files)
- [ ] Database credentials secured
- [ ] WordPress and plugins updated to latest versions
- [ ] Security plugins installed (Wordfence, iThemes Security)
- [ ] Firewall rules configured
- [ ] Regular backups configured

---

## Maintenance

### Regular Updates
```bash
# Update WordPress core
wp core update

# Update plugins
wp plugin update --all

# Update themes
wp theme update --all
```

### Backup Strategy
```bash
# Database backup
mysqldump -u username -p hiresmart_db > backup_$(date +%Y%m%d).sql

# Files backup
tar -czf hiresmart_files_$(date +%Y%m%d).tar.gz /var/www/hiresmart
tar -czf app_hiresmart_files_$(date +%Y%m%d).tar.gz /var/www/app-hiresmart
```

### Monitoring
- Set up uptime monitoring (UptimeRobot, Pingdom)
- Monitor SSL certificate expiration
- Monitor disk space and server resources
- Set up error logging and alerting

---

## Next Steps

1. Complete WordPress installation
2. Configure DNS records
3. Install SSL certificates
4. Deploy theme and plugin files
5. Configure cross-domain settings
6. Test complete user flow
7. Set up monitoring and backups
8. Launch! 🚀

For additional support, refer to:
- [README.md](../README.md) - Project overview
- [IMPLEMENTATION_GUIDE.md](../IMPLEMENTATION_GUIDE.md) - Development guide
- [WordPress Multisite Documentation](https://wordpress.org/support/article/create-a-network/)
