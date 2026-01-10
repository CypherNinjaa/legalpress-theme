# Local Testing Guide for LegalPress Theme

## Quick Setup Options

### Option 1: Local by Flywheel (Recommended - Easiest)

1. **Download Local**: https://localwp.com/
2. **Install and create a new site**
3. **Copy theme folder**:
   - Navigate to: `[Your Local Site]/app/public/wp-content/themes/`
   - Copy the entire `legalpress` folder there
4. **Activate in WordPress Admin** → Appearance → Themes

### Option 2: XAMPP / WAMP / MAMP

1. **Install XAMPP**: https://www.apachefriends.org/
2. **Start Apache & MySQL**
3. **Download WordPress**: https://wordpress.org/download/
4. **Extract to**: `C:\xampp\htdocs\wordpress\`
5. **Create database**:
   - Go to http://localhost/phpmyadmin
   - Create new database: `wordpress`
6. **Install WordPress**: http://localhost/wordpress
7. **Copy theme**:
   ```
   C:\xampp\htdocs\wordpress\wp-content\themes\legalpress\
   ```

### Option 3: wp-env (Docker-based - For Developers)

```bash
# Install Node.js first, then:
npm install -g @wordpress/env

# In your theme directory:
cd "d:\wordpress theme\legalpress"
wp-env start
```

Access at: http://localhost:8888

### Option 4: WordPress Playground (No Install Required!)

1. Go to: https://playground.wordpress.net/
2. Upload theme as ZIP
3. Test immediately in browser

---

## After Installation - Test Checklist

### 1. Create Test Content

```
✅ Create 3 categories: Law, Judgments, Editorial
✅ Create 10+ sample posts with featured images
✅ Mark 1 post as "Sticky" (for hero section)
✅ Create About and Contact pages
```

### 2. Configure Menus

```
WordPress Admin → Appearance → Menus
✅ Create Primary Menu (assign to "Primary Menu" location)
✅ Create Footer Menu (assign to "Footer Menu" location)
```

### 3. Configure Settings

```
WordPress Admin → Settings → Reading
✅ Set "Your homepage displays" to "Your latest posts"
   OR create a static front page

WordPress Admin → Appearance → Customize → Footer Settings
✅ Add footer about text
✅ Add copyright text
```

### 4. Test All Pages

```
✅ Homepage (front-page.php)
✅ Single Post (single.php)
✅ Category Archive (archive.php)
✅ Search Results (search.php)
✅ 404 Page (type non-existent URL)
✅ Static Page (page.php)
```

### 5. Test Responsive Design

```
✅ Desktop (1200px+)
✅ Tablet (768px - 1024px)
✅ Mobile (320px - 767px)
```

---

## Developer Tools

### VS Code Extensions for WordPress

```json
{
	"recommendations": [
		"bmewburn.vscode-intelephense-client",
		"wordpresstoolbox.wordpress-toolbox",
		"claudiosanches.wpcs-whitelist"
	]
}
```

### Install WordPress Stubs (Removes IDE Warnings)

```bash
# In theme folder
composer require --dev php-stubs/wordpress-stubs
```

Or just use the `.vscode/settings.json` file I created.

---

## Theme Customization

### Add Categories First!

The homepage shows posts from these category slugs:

- `law`
- `judgments`
- `editorial`

Create them in **Posts → Categories** before testing.

### Upload Logo

**Appearance → Customize → Site Identity → Logo**

### Featured Posts

To feature a post in the hero section:

1. Edit any post
2. In the right sidebar, check "Stick to the top of the blog"

---

## Troubleshooting

### White Screen / Error?

1. Enable debug mode in `wp-config.php`:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

### Styles Not Loading?

1. Check browser console for 404 errors
2. Verify file paths are correct
3. Clear browser cache

### Images Not Showing?

1. Regenerate thumbnails: Install "Regenerate Thumbnails" plugin
2. Check if uploads folder is writable

---

## Performance Testing

### GTmetrix

https://gtmetrix.com/

### PageSpeed Insights

https://pagespeed.web.dev/

### Query Monitor Plugin

https://wordpress.org/plugins/query-monitor/

---

## Security Checklist

This theme includes:

- ✅ Escaped output (esc_html, esc_attr, esc_url)
- ✅ Sanitized input (absint, sanitize_text_field)
- ✅ Nonce verification for AJAX
- ✅ Direct file access prevention
- ✅ Security headers (X-Frame-Options, etc.)
- ✅ WordPress version hidden
- ✅ Removed unnecessary head links

For production, also:

- Use SSL certificate
- Keep WordPress updated
- Use security plugin (Wordfence/Sucuri)
- Regular backups
