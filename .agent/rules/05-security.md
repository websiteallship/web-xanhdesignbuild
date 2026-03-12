---
description: Security rules for WordPress. Apply when writing PHP, handling user input, or configuring server.
globs: wp-content/**/*.php
---

# Security Rules

## Input/Output
- **ALWAYS sanitize input:** `sanitize_text_field()`, `absint()`, `sanitize_email()`
- **ALWAYS escape output:** `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses_post()`
- **ALWAYS use prepared statements:** `$wpdb->prepare()`
- **NEVER trust user input** — including AJAX requests, form data, URL params

## AJAX Security
```php
// ALWAYS verify nonce in AJAX handlers
check_ajax_referer('xanh_{action}_nonce', 'nonce');
// ALWAYS send nonce via wp_localize_script
wp_localize_script('xanh-filter', 'xanhAjax', [
    'url' => admin_url('admin-ajax.php'),
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
- `DISALLOW_FILE_EDIT: true` — No editor in admin
- `FORCE_SSL_ADMIN: true` — HTTPS for admin
- Table prefix: `xanh_` (NOT `wp_`)
- XML-RPC: Disabled via .htaccess
- REST API: Restricted (only public CPT endpoints)
- Directory browsing: Disabled (`Options -Indexes`)
- PHP execution in uploads: Blocked

## Security Headers (.htaccess)
```
X-Content-Type-Options: nosniff
X-Frame-Options: SAMEORIGIN
X-XSS-Protection: 1; mode=block
Referrer-Policy: strict-origin-when-cross-origin
Strict-Transport-Security: max-age=31536000
```

## Plugin Updates
- Auto-update: Minor/security WordPress core
- Major updates: Test on staging first, backup before
- Weekly plugin update check

Full reference: `docs/GOV_SECURITY.md`
