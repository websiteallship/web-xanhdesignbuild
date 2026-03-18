---
description: Kiểm tra bảo mật toàn diện cho WordPress theme. Dùng trước khi launch production hoặc sau Phase C.
---

# Security Audit — XANH Theme

## Skills cần đọc trước
- `@007` — Security audit, hardening, OWASP checks, code review
- `@backend-security-coder` — Input validation, authentication, API security
- `@frontend-security-coder` — XSS prevention, output sanitization, client-side security
- `@wordpress-penetration-testing` — WP vulnerability scanning, enumeration

## Rules BẮT BUỘC
- `05-security.md` — **Đọc toàn bộ** — Escape, AJAX, form, hardening, headers
- `16-error-prevention.md` — Null safety, escape checklist, zero errors policy

---

## Bước 1: PHP Output Escaping (rule `05`)

Scan toàn bộ theme cho output chưa escape:

// turbo
```bash
$theme = "wp-content/themes/xanhdesignbuild"
Write-Host "=== Potential unescaped echo/output ==="
# Tìm echo không có esc_ hoặc wp_kses
Select-String -Path "$theme/*.php","$theme/inc/*.php","$theme/template-parts/**/*.php" -Pattern 'echo\s+\$|echo\s+get_field' -Recurse | ForEach-Object { "$($_.Filename):$($_.LineNumber) $($_.Line.Trim())" }
```

### Checklist Escaping
- [ ] Text: `esc_html()` cho tất cả text output
- [ ] Attributes: `esc_attr()` cho HTML attributes
- [ ] URLs: `esc_url()` cho tất cả href, src
- [ ] Rich HTML (ACF WYSIWYG): `wp_kses_post()`
- [ ] Integers: `absint()`
- [ ] JSON in data-*: `esc_attr(wp_json_encode($config))`

```php
// ✅ Patterns đúng
echo esc_html(get_field('hero_title'));
echo '<a href="' . esc_url(get_field('cta_link')) . '">';
echo wp_kses_post(get_field('rich_content'));

// ❌ PHẢI FIX — XSS risk
echo get_field('title');
echo $item['description'];
```

---

## Bước 2: ACF Null Safety (rule `16`)

// turbo
```bash
$theme = "wp-content/themes/xanhdesignbuild"
Write-Host "=== ACF get_field without null check ==="
Select-String -Path "$theme/*.php","$theme/inc/*.php","$theme/template-parts/**/*.php" -Pattern "get_field\(" -Recurse | ForEach-Object { "$($_.Filename):$($_.LineNumber) $($_.Line.Trim())" }
```

### Checklist Null Safety
- [ ] Tất cả `get_field()` có null-check trước khi dùng
- [ ] Default fallback: `get_field('x') ?: 'default'`
- [ ] Repeater: `if ($items) : foreach...`
- [ ] Image: `if ($image && isset($image['ID']))`
- [ ] KHÔNG truy cập array key trên `null/false`

```php
// ✅ Đúng
$title = get_field('hero_title');
if ($title) { echo '<h1>' . esc_html($title) . '</h1>'; }

$image = get_field('hero_image');
if ($image && isset($image['ID'])) {
    echo wp_get_attachment_image($image['ID'], 'xanh-hero');
}

// ❌ Fatal crash nếu field trống
echo $image['url'];
```

---

## Bước 3: AJAX Security (rule `05`)

### Kiểm tra AJAX handlers
// turbo
```bash
$theme = "wp-content/themes/xanhdesignbuild"
Write-Host "=== AJAX handlers ==="
Select-String -Path "$theme/inc/*.php" -Pattern "wp_ajax_" -Recurse
Write-Host "=== Nonce verification ==="
Select-String -Path "$theme/inc/*.php" -Pattern "check_ajax_referer|wp_verify_nonce" -Recurse
Write-Host "=== wp_localize_script with nonce ==="
Select-String -Path "$theme/inc/*.php" -Pattern "wp_create_nonce" -Recurse
```

### Checklist AJAX
- [ ] Mỗi AJAX handler có `check_ajax_referer('xanh_{action}_nonce', 'nonce')`
- [ ] Nonce được gửi qua `wp_localize_script()`:
  ```php
  wp_localize_script('xanh-filter', 'xanhAjax', [
      'url'   => admin_url('admin-ajax.php'),
      'nonce' => wp_create_nonce('xanh_filter_nonce'),
  ]);
  ```
- [ ] Sanitize ALL input: `sanitize_text_field()`, `absint()`
- [ ] Capability check nếu cần: `current_user_can('edit_posts')`
- [ ] `wp_ajax_nopriv_` chỉ cho actions public cần thiết
- [ ] Response dùng `wp_send_json_success()` / `wp_send_json_error()`

---

## Bước 4: WP_Query Security (rule `16`)

// turbo
```bash
$theme = "wp-content/themes/xanhdesignbuild"
Write-Host "=== WP_Query instances ==="
Select-String -Path "$theme/*.php","$theme/template-parts/**/*.php" -Pattern "new WP_Query|query_posts" -Recurse | ForEach-Object { "$($_.Filename):$($_.LineNumber) $($_.Line.Trim())" }
Write-Host "=== wp_reset_postdata ==="
Select-String -Path "$theme/*.php","$theme/template-parts/**/*.php" -Pattern "wp_reset_postdata" -Recurse
```

### Checklist
- [ ] `wp_reset_postdata()` sau MỖI `WP_Query` custom
- [ ] KHÔNG dùng `query_posts()` (banned — breaks main loop)
- [ ] User input vào `WP_Query` phải sanitize:
  ```php
  $paged = absint(get_query_var('paged')) ?: 1;
  $type = sanitize_text_field($_GET['type'] ?? '');
  ```

---

## Bước 5: WordPress Hardening (rule `05`)

### wp-config.php
// turbo
```bash
Write-Host "=== wp-config.php security settings ==="
Select-String -Path "wp-config.php" -Pattern "DISALLOW_FILE_EDIT|FORCE_SSL_ADMIN|WP_POST_REVISIONS|AUTOSAVE_INTERVAL|WP_DEBUG" | ForEach-Object { $_.Line.Trim() }
```

### Checklist wp-config.php
- [ ] `define('DISALLOW_FILE_EDIT', true)` — No editor in admin
- [ ] `define('FORCE_SSL_ADMIN', true)` — HTTPS for admin
- [ ] `define('WP_POST_REVISIONS', 5)` — Limit revisions
- [ ] `define('AUTOSAVE_INTERVAL', 120)` — 2 minutes
- [ ] Production: `WP_DEBUG = false`, `WP_DEBUG_LOG = false`

### Checklist Theme Hardening
- [ ] Table prefix: `xanh_` (KHÔNG `wp_`)
- [ ] XML-RPC disabled: `add_filter('xmlrpc_enabled', '__return_false')`
- [ ] REST API restricted (chỉ public CPT endpoints)
- [ ] WP version hidden: `remove_action('wp_head', 'wp_generator')`
- [ ] `defined('ABSPATH') || exit;` ở đầu MỌI `inc/` files
- [ ] No `eval()`, `base64_decode()`, `system()`, `exec()`

---

## Bước 6: Form Security (rule `05`)

### Checklist Fluent Form
- [ ] Honeypot field: enabled (built-in)
- [ ] reCAPTCHA v3: enabled (invisible)
- [ ] Rate limiting: max 3 submissions / IP / hour
- [ ] File uploads: DISABLED (prevent malware)
- [ ] Phone validation: 10 digits, starts with 0
- [ ] Email validation: proper format check

### Checklist Custom Forms (nếu có)
- [ ] `wp_nonce_field()` trong form
- [ ] `wp_verify_nonce()` khi xử lý
- [ ] `sanitize_text_field()` tất cả input
- [ ] `sanitize_email()` cho email
- [ ] CSRF protection

---

## Bước 7: Security Headers (.htaccess) (rule `05`)

// turbo
```bash
Write-Host "=== Security Headers in .htaccess ==="
if (Test-Path ".htaccess") {
    Select-String -Path ".htaccess" -Pattern "X-Content-Type|X-Frame-Options|X-XSS-Protection|Referrer-Policy|Strict-Transport|Permissions-Policy|Content-Security-Policy" | ForEach-Object { $_.Line.Trim() }
} else {
    Write-Host "⚠️ .htaccess not found"
}
```

### Checklist Headers
- [ ] `X-Content-Type-Options: nosniff` — Prevent MIME sniffing
- [ ] `X-Frame-Options: SAMEORIGIN` — Prevent clickjacking
- [ ] `X-XSS-Protection: 1; mode=block` — XSS filter
- [ ] `Referrer-Policy: strict-origin-when-cross-origin`
- [ ] `Strict-Transport-Security: max-age=31536000` — HSTS
- [ ] `Permissions-Policy: camera=(), microphone=(), geolocation=(self)`
- [ ] `Content-Security-Policy` — Cho phép CDN scripts (jsDelivr, Google, Zalo)

### CSP chuẩn cho XANH:
```apache
Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' cdn.jsdelivr.net unpkg.com sp.zalo.me www.google.com; style-src 'self' 'unsafe-inline' cdn.jsdelivr.net fonts.googleapis.com; img-src 'self' data: https:; font-src 'self' fonts.gstatic.com; connect-src 'self'; frame-src www.youtube.com www.google.com;"
```

---

## Bước 8: Directory & File Security

### Checklist
- [ ] Directory browsing disabled: `Options -Indexes` trong `.htaccess`
- [ ] PHP execution blocked trong uploads:
  ```apache
  # wp-content/uploads/.htaccess
  <FilesMatch "\.php$">
      Order Deny,Allow
      Deny from all
  </FilesMatch>
  ```
- [ ] `wp-config.php` không accessible từ web
- [ ] Debug log không accessible: `WP_DEBUG_DISPLAY = false`
- [ ] `readme.html`, `license.txt` đã xóa (leaks WP version)

---

## Bước 9: Plugin Security

// turbo
```bash
Write-Host "=== Installed Plugins ==="
Get-ChildItem "wp-content/plugins" -Directory | ForEach-Object { $_.Name }
```

### Checklist
- [ ] Tất cả plugins up-to-date
- [ ] Không có plugin inactive/unused → xóa
- [ ] ACF Pro: bản mới nhất
- [ ] Fluent Form: bản mới nhất
- [ ] Auto-update: minor/security WP core
- [ ] Major updates: test staging trước, backup trước

---

## Bước 10: Frontend Security

### JavaScript
- [ ] Không có `document.write()`
- [ ] Không có `innerHTML` với user data (dùng `textContent`)
- [ ] `wp_localize_script` cho server data (KHÔNG inline `<script>` với PHP vars)
- [ ] Không store sensitive data trong localStorage/sessionStorage
- [ ] Third-party scripts (Zalo, Analytics) loaded lazy + trusted sources

### HTML
- [ ] Không leak internal paths/errors trên frontend
- [ ] Không hiện WP version, plugin versions
- [ ] Login page: `/wp-login.php` có rate limiting (plugin hoặc server)

---

## Bước 11: Tổng Hợp & Báo Cáo

Sau khi audit, tổng hợp:

```markdown
# 🔒 Security Audit Report — XANH Theme
**Ngày:** [DATE]

## Tổng quan
| Hạng mục | Passed | Failed | N/A |
|---|---|---|---|
| Output Escaping | x | x | - |
| Null Safety | x | x | - |
| AJAX Security | x | x | - |
| WP Hardening | x | x | - |
| Form Security | x | x | - |
| Security Headers | x | x | - |
| File Security | x | x | - |
| Plugin Security | x | x | - |
| Frontend Security | x | x | - |

## ❌ Issues Found
1. [File:Line] — [Description] — [Severity: High/Medium/Low]

## ✅ Recommendations
1. [Action item]
```

---

## Tài Liệu Tham Chiếu

| File | Nội dung |
|---|---|
| `.agent/rules/05-security.md` | Input/output, AJAX, form, hardening, headers |
| `.agent/rules/16-error-prevention.md` | PHP null safety, escape checklist |
| `docs/GOV_SECURITY.md` | Full security reference |
| `docs/implement/THEME_CONVENTIONS.md` §5 | Security checklist |
