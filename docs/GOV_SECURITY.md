# GOV_SECURITY — Bảo Mật WordPress

> **Dự án:** Website XANH - Design & Build
> **Phiên bản:** 1.0 | **Ngày tạo:** 2026-03-12

---

## 1. WordPress Core Hardening

### wp-config.php
```php
// === Security Constants ===
define('DISALLOW_FILE_EDIT', true);           // Tắt editor trong admin
define('DISALLOW_FILE_MODS', false);          // Cho phép update plugins (true = tắt)
define('WP_AUTO_UPDATE_CORE', 'minor');       // Chỉ auto-update minor/security
define('FORCE_SSL_ADMIN', true);             // Force HTTPS cho admin

// === Auth Keys & Salts ===
// Tạo tại: https://api.wordpress.org/secret-key/1.1/salt/
define('AUTH_KEY',         'unique-phrase');
define('SECURE_AUTH_KEY',  'unique-phrase');
// ... (đầy đủ 8 keys)

// === Database Table Prefix ===
$table_prefix = 'xanh_';                     // KHÔNG dùng 'wp_' mặc định

// === Debug (chỉ dev) ===
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);
```

### File Permissions
| Path | Permission | Ghi chú |
|---|---|---|
| `wp-config.php` | `440` hoặc `400` | Chỉ đọc |
| `wp-content/` | `755` | |
| `wp-content/uploads/` | `755` | Writable cho media |
| `wp-content/themes/` | `755` | |
| `wp-content/plugins/` | `755` | |
| `.htaccess` | `644` | |
| All `.php` files | `644` | |
| All directories | `755` | |

---

## 2. .htaccess Hardening

```apache
# === Bảo vệ wp-config.php ===
<files wp-config.php>
  order allow,deny
  deny from all
</files>

# === Chặn directory browsing ===
Options -Indexes

# === Chặn access file nhạy cảm ===
<FilesMatch "^(\.htaccess|\.htpasswd|wp-config\.php|readme\.html|license\.txt)$">
  Order Allow,Deny
  Deny from all
</FilesMatch>

# === Bảo vệ wp-includes ===
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteBase /
  RewriteRule ^wp-admin/includes/ - [F,L]
  RewriteRule !^wp-includes/ - [S=3]
  RewriteRule ^wp-includes/[^/]+\.php$ - [F,L]
  RewriteRule ^wp-includes/js/tinymce/langs/.+\.php - [F,L]
  RewriteRule ^wp-includes/theme-compat/ - [F,L]
</IfModule>

# === Disable XML-RPC ===
<Files xmlrpc.php>
  Order Allow,Deny
  Deny from all
</Files>

# === Security Headers ===
<IfModule mod_headers.c>
  Header set X-Content-Type-Options "nosniff"
  Header set X-Frame-Options "SAMEORIGIN"
  Header set X-XSS-Protection "1; mode=block"
  Header set Referrer-Policy "strict-origin-when-cross-origin"
  Header set Permissions-Policy "camera=(), microphone=(), geolocation=()"
  Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
</IfModule>

# === Chặn PHP execution trong uploads ===
<Directory "wp-content/uploads">
  <Files "*.php">
    Order Deny,Allow
    Deny from all
  </Files>
</Directory>
```

---

## 3. Login Protection

| Measure | Implementation |
|---|---|
| **Limit login attempts** | Plugin hoặc LiteSpeed WAF (5 attempts → block 15 min) |
| **Custom login URL** | Đổi `/wp-login.php` → `/xanh-login/` (WPS Hide Login hoặc .htaccess) |
| **Strong passwords** | Enforce strong password policy |
| **2FA** | Tùy chọn (Google Authenticator) — khuyến nghị cho admin |
| **Admin username** | KHÔNG dùng `admin`, dùng username riêng |
| **CAPTCHA** | reCAPTCHA v3 trên login form |

---

## 4. REST API Security

```php
// Chỉ cho phép REST API cho authenticated users (trừ public endpoints)
add_filter('rest_authentication_errors', function($result) {
    if (!is_user_logged_in()) {
        // Cho phép một số endpoints public cần thiết
        $allowed = ['/wp/v2/posts', '/wp/v2/xanh_project', '/fluent_forms'];
        $request_uri = $_SERVER['REQUEST_URI'];
        
        foreach ($allowed as $endpoint) {
            if (strpos($request_uri, $endpoint) !== false) {
                return $result;
            }
        }
        
        return new WP_Error('rest_not_logged_in', 'Not authorized.', ['status' => 401]);
    }
    return $result;
});

// Ẩn user enumeration qua REST API
add_filter('rest_endpoints', function($endpoints) {
    if (isset($endpoints['/wp/v2/users'])) {
        unset($endpoints['/wp/v2/users']);
    }
    return $endpoints;
});
```

---

## 5. Form Security (Fluent Form)

| Measure | Implementation |
|---|---|
| **Honeypot** | ✅ Built-in (Fluent Form) |
| **reCAPTCHA v3** | ✅ Invisible reCAPTCHA |
| **Rate limiting** | Max 3 submissions / IP / hour |
| **Input sanitization** | `sanitize_text_field()`, `absint()` |
| **File upload** | ❌ Không cho upload file qua form (tránh mã độc) |
| **Nonce verification** | ✅ Auto (Fluent Form) |

---

## 6. Database Security

| Measure | Implementation |
|---|---|
| **Table prefix** | `xanh_` (không dùng `wp_` mặc định) |
| **DB user** | Separate user, chỉ cấp quyền cần thiết |
| **Prepared statements** | Luôn dùng `$wpdb->prepare()` |
| **Clean revisions** | LiteSpeed Cache auto-clean |
| **Backup encryption** | Optional — encrypt backups chứa customer data |

---

## 7. Plugin/Theme Update Policy

| Action | Frequency | Responsibility |
|---|---|---|
| WordPress core (minor) | Auto-update | System |
| WordPress core (major) | Manual review → staging → production | Dev |
| Plugin updates | Weekly check → staging test | Dev |
| Theme updates | Sau khi test staging | Dev |
| Backup trước update | Bắt buộc | Dev |

### Update Process
```
1. Backup full (files + DB)
2. Update trên staging
3. Test 30 phút (forms, filter, pages)
4. Nếu OK → Update production
5. Test production 15 phút
6. Rollback nếu có lỗi (restore backup)
```

---

## 8. Content Security

| Measure | Implementation |
|---|---|
| **Comment spam** | Tắt comments (nếu không cần) hoặc Akismet |
| **User role** | Chỉ 2 roles: Administrator + Editor. KHÔNG tạo Subscriber public |
| **File uploads** | Chỉ cho phép: JPG, PNG, WebP, PDF. Block PHP, EXE |
| **Hotlinking** | Chặn hotlink ảnh từ domain khác (`.htaccess`) |

---

## 9. Monitoring & Incident Response

| Tool | Mục đích |
|---|---|
| **Wordfence** (Free) hoặc **LiteSpeed WAF** | Firewall, malware scan |
| **UptimeRobot** | Giám sát uptime |
| **Activity log** | Ghi nhận mọi thay đổi admin |

### Khi bị tấn công
1. Đổi tất cả mật khẩu (WP admin, FTP, DB, hosting)
2. Scan malware (Wordfence)
3. Kiểm tra file thay đổi gần đây
4. Restore từ backup sạch gần nhất
5. Update tất cả plugins/themes/core
6. Kiểm tra user accounts lạ

---

## Security Checklist

- [ ] `wp-config.php` có security constants
- [ ] Table prefix ≠ `wp_`
- [ ] `.htaccess` hardening applied
- [ ] XML-RPC disabled
- [ ] Login URL changed
- [ ] Login attempts limited
- [ ] REST API restricted
- [ ] Security headers set
- [ ] SSL + HSTS enabled
- [ ] PHP execution blocked in uploads
- [ ] Strong admin password
- [ ] File permissions correct
- [ ] Backup strategy active
- [ ] Update policy documented

---

## Tài Liệu Liên Quan

- `CORE_ARCHITECTURE.md` — wp-config settings
- `ARCH_INTEGRATIONS.md` — SSL, HTTPS
- `FEATURE_LEAD_CAPTURE.md` — Form security
- `OPS_DEPLOYMENT.md` — Deployment security
