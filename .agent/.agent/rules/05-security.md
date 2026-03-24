---
description: Security rules for WordPress theme. Apply when writing PHP, handling user input, or configuring server.
globs: wp-content/themes/xanhdesignbuild/**/*.php
---

# Security Rules

## Input/Output
- **ALWAYS sanitize input:** `sanitize_text_field()`, `absint()`, `sanitize_email()`
- **ALWAYS escape output:** `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses_post()`
- **ALWAYS use prepared statements:** `$wpdb->prepare()`
- **NEVER trust user input** — including AJAX requests, form data, URL params

### ACF-Specific Escaping
```php
// Text fields — ALWAYS escape
echo esc_html(get_field('hero_title'));

// URLs — ALWAYS esc_url
echo '<a href="' . esc_url(get_field('cta_link')) . '">';

// Rich text (WYSIWYG) — wp_kses_post allows safe HTML
echo wp_kses_post(get_field('rich_content'));

// Image — use wp_get_attachment_image (auto-escapes)
$img = get_field('hero_image');
if ($img) {
    echo wp_get_attachment_image($img['ID'], 'xanh-hero');
}

// ❌ NEVER output raw ACF values
echo get_field('title');           // ❌ XSS risk
echo $item['description'];        // ❌ XSS risk
```

## AJAX Security
```php
// ALWAYS verify nonce in AJAX handlers
check_ajax_referer('xanh_{action}_nonce', 'nonce');
// ALWAYS send nonce via wp_localize_script
wp_localize_script('xanh-filter', 'xanhAjax', [
    'url'   => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('xanh_filter_nonce'),
]);
```

## Form Security
- Honeypot field: ✅ (Fluent Form built-in)
- reCAPTCHA v3: ✅ (invisible)
- Rate limiting: Max 3 submissions / IP / hour
- File uploads: ❌ DISABLED via forms (prevent malware)
- Phone validation: 10 digits, starts with 0

## WordPress Hardening
```php
// wp-config.php
define('DISALLOW_FILE_EDIT', true);    // No editor in admin
define('FORCE_SSL_ADMIN', true);       // HTTPS for admin
define('WP_POST_REVISIONS', 5);        // Limit revisions
define('AUTOSAVE_INTERVAL', 120);      // 2 minutes
```
- Table prefix: `xanh_` (NOT `wp_`)
- XML-RPC: Disabled (`add_filter('xmlrpc_enabled', '__return_false')`)
- REST API: Restricted (only public CPT endpoints)
- Directory browsing: Disabled (`Options -Indexes`)
- PHP execution in uploads: Blocked

## Security Headers (.htaccess)
```apache
<IfModule mod_headers.c>
    # Prevent MIME type sniffing
    Header set X-Content-Type-Options "nosniff"
    # Prevent clickjacking
    Header set X-Frame-Options "SAMEORIGIN"
    # XSS Protection
    Header set X-XSS-Protection "1; mode=block"
    # Referrer Policy
    Header set Referrer-Policy "strict-origin-when-cross-origin"
    # HSTS (HTTPS only)
    Header set Strict-Transport-Security "max-age=31536000; includeSubDomains"
    # Permissions Policy
    Header set Permissions-Policy "camera=(), microphone=(), geolocation=(self)"
    # Content Security Policy (loosen for CDN scripts)
    Header set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' cdn.jsdelivr.net unpkg.com sp.zalo.me www.google.com; style-src 'self' 'unsafe-inline' cdn.jsdelivr.net fonts.googleapis.com; img-src 'self' data: https:; font-src 'self' fonts.gstatic.com; connect-src 'self'; frame-src www.youtube.com www.google.com;"
</IfModule>
```

## Plugin Updates
- Auto-update: Minor/security WordPress core
- Major updates: Test on staging first, backup before
- Weekly plugin update check

## Related Rules
- `16-error-prevention.md` — PHP null safety, JS DOM guards, zero errors policy
- `17-wp-optimization.md` — WP bloat removal, XML-RPC disable

Full reference: `docs/GOV_SECURITY.md`
