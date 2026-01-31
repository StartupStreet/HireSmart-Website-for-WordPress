# HireSmart Local Preview & Testing Guide

## Quick Start: Preview Without WordPress

The easiest way to preview the HireSmart landing page without setting up WordPress is to use the standalone HTML preview file.

### Option 1: Direct HTML Preview (Fastest)

1. **Navigate to the repository folder**
   ```bash
   cd /path/to/HireSmart-Website-for-WordPress
   ```

2. **Open preview.html in your browser**
   ```bash
   # On Mac
   open preview.html
   
   # On Linux
   xdg-open preview.html
   
   # On Windows
   start preview.html
   ```

3. **Or use a local web server** (recommended for full functionality):
   ```bash
   # Using Python
   python3 -m http.server 8000
   
   # Using PHP
   php -S localhost:8000
   
   # Using Node.js (http-server)
   npx http-server -p 8000
   ```
   
   Then visit: http://localhost:8000/preview.html

**What you'll see:**
- Complete landing page
- All sections: Hero, Features, Use Cases, Differentiators, Pricing
- Responsive design
- Interactive elements
- ⚠️ Note: Forms won't submit (no backend), but you can see the UI

---

## Option 2: Local WordPress Installation

For testing the complete application with authentication and dashboards.

### Prerequisites

- PHP 7.4+
- MySQL 5.7+
- Apache/Nginx web server
- Or use Local by Flywheel, XAMPP, MAMP, etc.

### Step 1: Install Local WordPress Environment

**Using Local by Flywheel (Recommended):**

1. **Download Local**: https://localwp.com/
2. **Install and open Local**
3. **Create new site**:
   - Site name: `hiresmart-local`
   - PHP version: 7.4+
   - Web server: Apache or Nginx
   - Database: MySQL 5.7+

4. **Start the site** and note the URL (e.g., http://hiresmart-local.local)

**Using XAMPP:**

1. Install XAMPP: https://www.apachefriends.org/
2. Start Apache and MySQL
3. Create database:
   ```sql
   CREATE DATABASE hiresmart_local;
   ```
4. Download WordPress: https://wordpress.org/download/
5. Extract to `xampp/htdocs/hiresmart`
6. Configure wp-config.php

### Step 2: Install HireSmart Theme

1. **Copy theme files**:
   ```bash
   # Navigate to WordPress themes directory
   cd /path/to/wordpress/wp-content/themes/
   
   # Create hiresmart directory
   mkdir hiresmart
   
   # Copy theme files
   cp /path/to/repository/style.css hiresmart/
   cp /path/to/repository/index.php hiresmart/
   cp /path/to/repository/header.php hiresmart/
   cp /path/to/repository/footer.php hiresmart/
   cp /path/to/repository/functions.php hiresmart/
   cp -r /path/to/repository/js hiresmart/
   ```

2. **Activate theme**:
   - Login to WordPress admin: http://your-site.local/wp-admin
   - Go to Appearance → Themes
   - Activate "HireSmart"

3. **View landing page**: Visit http://your-site.local

### Step 3: Install HireSmart Plugin

1. **Copy plugin files**:
   ```bash
   # Navigate to WordPress plugins directory
   cd /path/to/wordpress/wp-content/plugins/
   
   # Copy entire plugin folder
   cp -r /path/to/repository/hiresmart-plugin ./
   ```

2. **Activate plugin**:
   - Go to Plugins → Installed Plugins
   - Activate "HireSmart - AI-Powered Job Portal"
   - Plugin will automatically create necessary pages and database tables

3. **Verify pages created**:
   - Pages → All Pages
   - You should see: Dashboard, Profile, Billing, Integrations, Login, Register

### Step 4: Test Complete Flow

1. **View Landing Page**:
   - Visit: http://your-site.local
   - Click through all sections
   - Verify responsive design (resize browser)

2. **Test Registration**:
   - Click "Get Started" or visit http://your-site.local/register
   - Fill out form:
     - Name, email, password
     - Select account type (Job Seeker, Employer, or Agency)
     - Choose subscription tier (Free, Startup, or Enterprise)
   - Submit form
   - Should redirect to dashboard (Free) or billing (Paid)

3. **Test Login**:
   - Visit: http://your-site.local/login
   - Enter credentials
   - Should redirect to dashboard

4. **Test Dashboard**:
   - Should see role-specific dashboard (Job Seeker, Employer, or Agency)
   - Check stats cards display correctly
   - Verify recent activity shows
   - Test AI scores section

5. **Test Profile**:
   - Visit: http://your-site.local/profile
   - View personal information
   - Check AI scores display
   - Click "Take AI Assessment" button

6. **Test Billing**:
   - Visit: http://your-site.local/billing
   - View current subscription
   - Check payment methods section
   - View billing history

7. **Test Integrations**:
   - Visit: http://your-site.local/integrations
   - Add LinkedIn, GitHub, Behance, Canva, or portfolio URLs
   - Save and verify connection status

8. **Test Logout**:
   - Click "Logout"
   - Should redirect to landing page
   - Header should now show "Login" and "Sign Up"

---

## Option 3: Docker Setup (Advanced)

For a completely isolated testing environment.

### Create docker-compose.yml

```yaml
version: '3'

services:
  wordpress:
    image: wordpress:latest
    ports:
      - "8080:80"
    environment:
      WORDPRESS_DB_HOST: db:3306
      WORDPRESS_DB_NAME: hiresmart
      WORDPRESS_DB_USER: wordpress
      WORDPRESS_DB_PASSWORD: wordpress
    volumes:
      - ./:/var/www/html/wp-content/themes/hiresmart
      - ./hiresmart-plugin:/var/www/html/wp-content/plugins/hiresmart-plugin

  db:
    image: mysql:5.7
    environment:
      MYSQL_DATABASE: hiresmart
      MYSQL_USER: wordpress
      MYSQL_PASSWORD: wordpress
      MYSQL_ROOT_PASSWORD: wordpress
```

### Run Docker

```bash
# Start containers
docker-compose up -d

# Visit WordPress
# http://localhost:8080

# Stop containers
docker-compose down
```

---

## Browser Testing Checklist

### Desktop Testing
- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Edge (latest)

### Mobile Testing
- [ ] Chrome Mobile (Android)
- [ ] Safari Mobile (iOS)
- [ ] Responsive design at 768px, 480px, 320px

### Functionality Testing
- [ ] Landing page loads
- [ ] All sections visible
- [ ] Navigation works
- [ ] Forms display correctly
- [ ] Registration flow works
- [ ] Login works
- [ ] Dashboard displays
- [ ] AI assessment works
- [ ] Profile updates save
- [ ] Integrations save
- [ ] Logout works

---

## Development Tools

### Browser DevTools

**Check Console for Errors**:
1. Open browser DevTools (F12)
2. Go to Console tab
3. Look for JavaScript errors (red text)
4. Fix any issues

**Check Network Requests**:
1. Go to Network tab
2. Reload page
3. Check for failed requests (red status codes)
4. Verify AJAX calls work

**Test Responsive Design**:
1. Open DevTools
2. Click device toolbar icon
3. Test different screen sizes
4. Verify layout adapts correctly

### Debugging PHP

Add to wp-config.php:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors', 0);
```

Check logs at: `wp-content/debug.log`

### Testing AJAX Calls

Open browser console and test:
```javascript
// Test registration (won't actually work without form data)
jQuery.ajax({
    url: hiresmart_ajax.ajax_url,
    type: 'POST',
    data: {
        action: 'hiresmart_register',
        nonce: hiresmart_ajax.nonce,
        email: 'test@example.com',
        password: 'password123',
        account_type: 'job_seeker',
        subscription_tier: 'free'
    },
    success: function(response) {
        console.log('Response:', response);
    }
});
```

---

## Common Issues & Solutions

### Issue: Theme doesn't appear in WordPress

**Solution**:
- Verify `style.css` has theme header comment
- Check file permissions (755 for folders, 644 for files)
- Refresh themes page in WordPress admin

### Issue: Plugin pages not created

**Solution**:
- Deactivate and reactivate plugin
- Or manually create pages with shortcodes:
  - `[hiresmart_dashboard]`
  - `[hiresmart_profile]`
  - `[hiresmart_billing]`
  - `[hiresmart_integrations]`
  - `[hiresmart_login]`
  - `[hiresmart_register]`

### Issue: AJAX not working

**Solution**:
- Check jQuery is loaded
- Verify `hiresmart_ajax` object exists
- Check browser console for errors
- Ensure nonces are valid

### Issue: Database tables not created

**Solution**:
```php
// Run this in WordPress admin → Tools → PHP
global $wpdb;
$charset_collate = $wpdb->get_charset_collate();

$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}hiresmart_profiles (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    user_id bigint(20) NOT NULL,
    account_type varchar(20) NOT NULL,
    subscription_tier varchar(20) NOT NULL,
    PRIMARY KEY (id)
) $charset_collate;";

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);
```

### Issue: Styles not loading

**Solution**:
- Check file paths in `functions.php`
- Verify CSS files exist
- Clear browser cache (Ctrl+Shift+R)
- Check WordPress → Settings → Permalinks (click Save)

---

## Performance Testing

### Load Time Testing
```bash
# Using curl
curl -w "@curl-format.txt" -o /dev/null -s http://your-site.local

# curl-format.txt content:
time_namelookup:  %{time_namelookup}\n
time_connect:  %{time_connect}\n
time_starttransfer:  %{time_starttransfer}\n
time_total:  %{time_total}\n
```

### Database Query Testing

Install Query Monitor plugin:
```bash
wp plugin install query-monitor --activate
```

Check for:
- Slow queries (> 0.05s)
- Duplicate queries
- High query count per page

---

## Next Steps After Preview

Once you've tested locally and everything works:

1. **Review the [DEPLOYMENT_GUIDE.md](./DEPLOYMENT_GUIDE.md)** for production setup
2. **Configure your domains** (hiresmart.startupstreet.in, app-hiresmart.startupstreet.in)
3. **Set up SSL certificates**
4. **Deploy to production server**
5. **Test in production environment**
6. **Monitor and maintain**

---

## Support Resources

- **WordPress Codex**: https://codex.wordpress.org/
- **WordPress Support Forums**: https://wordpress.org/support/
- **WP-CLI Documentation**: https://wp-cli.org/
- **Plugin Documentation**: [hiresmart-plugin/README.md](./hiresmart-plugin/README.md)

For project-specific issues:
- Check [README.md](./README.md)
- Review [IMPLEMENTATION_GUIDE.md](./IMPLEMENTATION_GUIDE.md)
- See [PROJECT_SUMMARY.md](./PROJECT_SUMMARY.md)
