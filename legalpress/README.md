# LegalPress - Premium WordPress Theme

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![WordPress](https://img.shields.io/badge/WordPress-6.0%2B-green.svg)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-purple.svg)
![License](https://img.shields.io/badge/license-GPL--2.0%2B-red.svg)

A stunning, modern WordPress theme designed for legal news, judgments, and editorial content. Built with beautiful animations, skeleton loading, and optimized performance.

## ✨ Features

### 🎨 Design

- **Modern & Professional** - Clean, elegant design perfect for legal/news sites
- **Beautiful Animations** - Scroll-triggered animations, hover effects, and smooth transitions
- **Skeleton Loading** - Beautiful loading states for improved perceived performance
- **Glassmorphism Effects** - Modern glass-effect cards and overlays
- **Dark Mode Ready** - Built-in dark mode support
- **Fully Responsive** - Optimized for desktop, tablet, and mobile

### ⚡ Performance

- **Optimized Queries** - Uses `no_found_rows` and caching for faster queries
- **Deferred Scripts** - JavaScript loaded with defer for faster page rendering
- **Lazy Loading** - Images load on-demand with fade effects
- **Preconnect Hints** - Faster font loading with resource hints
- **Minimal Dependencies** - No jQuery required, pure vanilla JavaScript

### 🔒 Security

- **Escaped Output** - All output properly escaped with esc_html, esc_attr, esc_url
- **Sanitized Input** - Input validation with absint, sanitize_text_field
- **Security Headers** - X-Frame-Options, X-XSS-Protection, Referrer-Policy
- **Direct Access Prevention** - All PHP files protected
- **WordPress Hardening** - Version hidden, head cleanup, login errors masked

### 🎯 SEO Ready

- **Schema.org Markup** - Article structured data for rich snippets
- **Open Graph Tags** - Social sharing optimization
- **Semantic HTML5** - Proper heading hierarchy and landmarks
- **Fast Loading** - Performance optimizations improve SEO rankings

## 📁 File Structure

```
legalpress/
├── assets/
│   ├── css/
│   │   ├── animations.css    # Animation keyframes & utilities
│   │   ├── components.css    # UI components (newsletter, cards, etc.)
│   │   └── premium.css       # Enhanced premium styles
│   └── js/
│       ├── main.js           # Core JavaScript functionality
│       └── advanced.js       # Animations, skeleton loading, effects
├── inc/
│   └── demo-content.php      # Demo content generator
├── template-parts/
│   ├── card-post.php         # Post card component
│   └── content.php           # Content template
├── 404.php                   # Error page
├── archive.php               # Archive template
├── comments.php              # Comments template
├── footer.php                # Footer template
├── front-page.php            # Homepage template
├── functions.php             # Theme functions
├── header.php                # Header template
├── index.php                 # Main template
├── page.php                  # Page template
├── search.php                # Search results
├── searchform.php            # Search form
├── sidebar.php               # Sidebar template
├── single.php                # Single post template
└── style.css                 # Main stylesheet
```

## 🚀 Quick Start

### Installation

1. Download the theme
2. Upload to `/wp-content/themes/legalpress/`
3. Activate in **Appearance → Themes**
4. Install demo content (optional)

### Demo Content

The theme includes a demo content generator to help you get started quickly:

1. Go to **Appearance → Demo Content**
2. Click **Install Demo Content**
3. This will create:
   - 4 Categories (Law, Judgments, Editorial, News)
   - 12 Sample posts with featured images
   - 3 Pages (About, Contact, Privacy Policy)
   - Navigation menus

## 🎭 Animations

The theme includes various beautiful animations:

### Scroll Animations

- `data-animate="fade-in-up"` - Fade in from below
- `data-animate="fade-in-down"` - Fade in from above
- `data-animate="fade-in-left"` - Fade in from left
- `data-animate="fade-in-right"` - Fade in from right
- `data-animate="scale-in"` - Scale in effect
- `data-animate="pop-in"` - Pop in with bounce

### CSS Classes

- `.reveal` - Basic reveal on scroll
- `.hover-lift` - Lift card on hover
- `.hover-zoom` - Zoom image on hover
- `.hover-shine` - Shine effect on hover
- `.hover-glow` - Glow border effect
- `.gradient-text` - Animated gradient text
- `.animate-float` - Floating animation
- `.animate-pulse` - Pulse animation

### Stagger Animations

Add `stagger-children` class to parent element to animate children sequentially.

## 🦴 Skeleton Loading

Built-in skeleton loading components:

```html
<!-- Skeleton Card -->
<div class="skeleton-card">
	<div class="skeleton skeleton-card__image"></div>
	<div class="skeleton-card__content">
		<div class="skeleton skeleton-card__category"></div>
		<div class="skeleton skeleton-card__title"></div>
		<div class="skeleton skeleton-card__text"></div>
	</div>
</div>
```

## 📱 Responsive Breakpoints

- **Desktop**: 1200px+
- **Laptop**: 1024px - 1199px
- **Tablet**: 768px - 1023px
- **Mobile**: 320px - 767px

## ⚙️ Customization

### Theme Colors (CSS Variables)

```css
:root {
	--color-primary: #1a1a2e;
	--color-secondary: #16213e;
	--color-accent: #c9a227;
	--color-text: #2d2d2d;
	--color-bg: #ffffff;
}
```

### Category Colors

Categories have automatic color coding:

- **Law**: Purple (#4f46e5)
- **Judgments**: Green (#059669)
- **Editorial**: Red (#dc2626)
- **News**: Blue (#0284c7)

## 🔧 Developer Notes

### JavaScript Modules

The theme exposes utilities for custom development:

```javascript
// Available via window.LegalPress
LegalPress.SkeletonLoader.createSkeleton("card");
LegalPress.debounce(fn, 200);
LegalPress.throttle(fn, 100);
```

### Hooks Available

```php
// Filter reading time
add_filter('legalpress_reading_time_wpm', function() {
    return 250; // words per minute
});
```

## 🌐 Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Opera (latest)

## 📄 License

GPL-2.0-or-later

## 🤝 Credits

- **Fonts**: [Google Fonts](https://fonts.google.com/) - Inter & Merriweather
- **Icons**: Custom SVG icons
- **Images**: [Unsplash](https://unsplash.com/) (demo content only)

---

Made with ❤️ for the legal community
