# PLUGIN_AI_SECURITY — Bảo Mật Plugin

> **Plugin:** XANH AI Content Generator
> **Tuân thủ:** `GOV_CODING_STANDARDS.md` §4 | `GOV_SECURITY.md` | `backend-security-coder` skill
> **Cập nhật:** 2026-03-20

---

## 1. API Key Encryption

### Quản lý key an toàn — KHÔNG lưu plaintext trong wp_options

```php
/**
 * Encrypt API key trước khi lưu.
 * Dùng AES-256-CBC với wp_salt() làm key.
 */
function xanh_ai_encrypt_key( string $plaintext ): string {
    $key    = hash( 'sha256', wp_salt( 'auth' ), true );
    $iv     = openssl_random_pseudo_bytes( 16 );
    $cipher = openssl_encrypt( $plaintext, 'AES-256-CBC', $key, 0, $iv );

    return base64_encode( $iv . $cipher );
}

function xanh_ai_decrypt_key( string $encrypted ): string {
    if ( empty( $encrypted ) ) {
        return '';
    }

    $key  = hash( 'sha256', wp_salt( 'auth' ), true );
    $data = base64_decode( $encrypted );
    $iv   = substr( $data, 0, 16 );
    $cipher = substr( $data, 16 );

    return openssl_decrypt( $cipher, 'AES-256-CBC', $key, 0, $iv );
}
```

### Lưu ý
- API key chỉ hiển thị dạng masked: `sk-...xxxx`
- Không bao giờ log API key
- Không gửi API key qua AJAX response

---

## 2. CSRF Protection — Nonce

### Mọi form và AJAX đều phải có nonce

```php
// Form — render nonce field
wp_nonce_field( 'xanh_ai_settings', 'xanh_ai_nonce' );

// Form — verify
if ( ! wp_verify_nonce( $_POST['xanh_ai_nonce'] ?? '', 'xanh_ai_settings' ) ) {
    wp_die( 'Security check failed.' );
}

// AJAX — render nonce in localized script
wp_localize_script( 'xanh-ai-admin', 'xanhAI', [
    'url'   => admin_url( 'admin-ajax.php' ),
    'nonce' => wp_create_nonce( 'xanh_ai_ajax' ),
] );

// AJAX — verify
function xanh_ai_ajax_generate(): void {
    check_ajax_referer( 'xanh_ai_ajax', 'nonce' );
    // ... handler code
}
```

---

## 3. Capability Checks

### Permission Matrix

| Action | Required Capability | Roles mặc định |
|---|---|---|
| View generator | `edit_posts` | Editor, Admin |
| Generate content | `edit_posts` | Editor, Admin |
| Save draft | `edit_posts` | Editor, Admin |
| Batch generate | `edit_posts` | Editor, Admin |
| View calendar | `edit_posts` | Editor, Admin |
| View history | `edit_posts` | Editor, Admin |
| Change settings | `manage_options` | Admin only |
| View analytics | `manage_options` | Admin only |

### Implementation
```php
function xanh_ai_render_generator_page(): void {
    if ( ! current_user_can( 'edit_posts' ) ) {
        wp_die( 'Bạn không có quyền truy cập trang này.' );
    }
    include XANH_AI_DIR . 'admin/views/generator-page.php';
}
```

---

## 4. Input Sanitization

### Sanitize mọi user input trước khi xử lý

| Input | Sanitizer |
|---|---|
| Topic text | `sanitize_text_field()` |
| Keywords | `sanitize_text_field()` |
| Notes textarea | `sanitize_textarea_field()` |
| Angle ID | Whitelist check against registered angles |
| Tone | Whitelist: `['warm-luxury', 'expert', 'friendly']` |
| Length | Whitelist: `['standard', 'long', 'guide']` |
| Author ID | `absint()` + `get_userdata()` verify |
| Temperature | `floatval()` + range check 0.0-1.0 |
| API key | `sanitize_text_field()` + encrypt |

```php
// ✅ Pattern: sanitize → validate → process
function xanh_ai_ajax_generate(): void {
    check_ajax_referer( 'xanh_ai_ajax', 'nonce' );

    if ( ! current_user_can( 'edit_posts' ) ) {
        wp_send_json_error( [ 'message' => 'Không có quyền.' ], 403 );
    }

    $topic   = sanitize_text_field( wp_unslash( $_POST['topic'] ?? '' ) );
    $keyword = sanitize_text_field( wp_unslash( $_POST['keyword'] ?? '' ) );
    $angle   = sanitize_text_field( wp_unslash( $_POST['angle'] ?? '' ) );

    // Validate required fields
    if ( empty( $topic ) || empty( $keyword ) || empty( $angle ) ) {
        wp_send_json_error( [ 'message' => 'Vui lòng điền đầy đủ thông tin.' ] );
    }

    // Validate angle against whitelist
    if ( ! Xanh_AI_Angles::exists( $angle ) ) {
        wp_send_json_error( [ 'message' => 'Góc viết không hợp lệ.' ] );
    }

    // Process...
}
```

---

## 5. Output Escaping

### Escape mọi output trước khi render HTML

| Context | Escaper |
|---|---|
| Text trong HTML | `esc_html()` |
| Attributes | `esc_attr()` |
| URLs | `esc_url()` |
| JavaScript | `esc_js()` |
| HTML content (AI) | `wp_kses_post()` |
| JSON in HTML | `wp_json_encode()` + `JSON_HEX_TAG` |

```php
// ✅ Preview content: allow HTML but sanitize
echo wp_kses_post( $generated_content );

// ✅ Title in attribute
printf( '<input value="%s" />', esc_attr( $title ) );

// ✅ URL
printf( '<a href="%s">Link</a>', esc_url( $url ) );
```

---

## 6. Rate Limiting

### API Abuse Prevention

```php
/**
 * Rate limit: 1 request mỗi 30 giây per user.
 * Ngăn chặn AJAX spam gọi Gemini API liên tục.
 */
function xanh_ai_check_rate_limit(): bool {
    $user_id = get_current_user_id();
    $key     = "xanh_ai_rate_{$user_id}";

    if ( get_transient( $key ) ) {
        return false; // Rate limited
    }

    set_transient( $key, true, 30 ); // 30 seconds cooldown
    return true;
}

// Trong AJAX handler:
if ( ! xanh_ai_check_rate_limit() ) {
    wp_send_json_error( [
        'message' => 'Vui lòng đợi 30 giây trước khi tạo tiếp.',
        'code'    => 'rate_limited',
    ], 429 );
}
```

---

## 7. Error Handling — Không Leak Thông Tin

### Rules
- KHÔNG expose raw API errors cho user
- KHÔNG log API key hoặc full request body
- User-friendly messages tiếng Việt
- Log errors to WP error log (nếu WP_DEBUG = true)

```php
// ✅ User-friendly error
wp_send_json_error( [ 'message' => 'Lỗi kết nối API. Vui lòng thử lại.' ] );

// ✅ Internal log (only if WP_DEBUG)
if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
    error_log( '[XANH AI] API Error: ' . $response->get_error_message() );
}

// ❌ KHÔNG BAO GIỜ
wp_send_json_error( [ 'message' => $raw_api_error ] ); // Leak API details
error_log( 'API Key: ' . $api_key );                   // Leak credentials
```

---

## 8. SQL Safety

### Cho custom table (history log)

```php
// ✅ Dùng $wpdb->prepare() cho mọi query
global $wpdb;
$table = $wpdb->prefix . 'xanh_ai_history';

$wpdb->insert( $table, [
    'post_id'     => $post_id,
    'angle'       => $angle,
    'topic'       => $topic,
    'tokens_used' => $tokens,
    'score'       => $score,
    'status'      => 'success',
    'created_at'  => current_time( 'mysql' ),
], [ '%d', '%s', '%s', '%d', '%d', '%s', '%s' ] );

// ✅ Prepared statement cho SELECT
$results = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT * FROM {$table} WHERE status = %s ORDER BY created_at DESC LIMIT %d",
        'success',
        50
    )
);

// ❌ KHÔNG BAO GIỜ
$wpdb->query( "SELECT * FROM {$table} WHERE topic = '{$topic}'" ); // SQL injection
```

---

## 9. File Upload Security

### Featured image upload validation

```php
function xanh_ai_validate_image_upload( string $mime, int $size ): bool {
    $allowed_mimes = [ 'image/png', 'image/jpeg', 'image/webp' ];

    if ( ! in_array( $mime, $allowed_mimes, true ) ) {
        return false;
    }

    // Max 5MB
    if ( $size > 5 * MB_IN_BYTES ) {
        return false;
    }

    return true;
}
```

---

## 10. Uninstall Cleanup

### `uninstall.php`

```php
<?php
// Prevent direct access
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Remove all plugin options
$options = [
    'xanh_ai_gemini_key',
    'xanh_ai_text_model',
    'xanh_ai_image_model',
    'xanh_ai_image_aspect',
    'xanh_ai_image_size',
    'xanh_ai_auto_image',
    'xanh_ai_default_author',
    'xanh_ai_temperature',
    'xanh_ai_schedule_frequency',
    'xanh_ai_schedule_time',
];

foreach ( $options as $option ) {
    delete_option( $option );
}

// Remove custom table
global $wpdb;
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}xanh_ai_history" );

// Remove post meta
delete_post_meta_by_key( '_xanh_ai_generated' );
delete_post_meta_by_key( '_xanh_ai_angle' );
delete_post_meta_by_key( '_xanh_ai_score' );
delete_post_meta_by_key( '_xanh_ai_tokens' );

// Clear transients
$wpdb->query(
    "DELETE FROM {$wpdb->options} WHERE option_name LIKE '%xanh_ai_%'"
);
```

---

## Security Checklist

Trước mỗi release, kiểm tra:

- [ ] Mọi AJAX handler có `check_ajax_referer()`
- [ ] Mọi admin page có `current_user_can()` check
- [ ] Mọi `$_POST`/`$_GET` đều sanitized
- [ ] Mọi output đều escaped
- [ ] API key encrypted, không plaintext
- [ ] `$wpdb->prepare()` cho mọi custom SQL
- [ ] Rate limiting hoạt động
- [ ] Error messages không leak thông tin
- [ ] Uninstall cleanup hoàn chỉnh
- [ ] No `eval()`, `exec()`, `innerHTML`

---

## Tài Liệu Liên Quan

- `GOV_CODING_STANDARDS.md` §4 — PHP security patterns
- `GOV_SECURITY.md` — Security standards
- `PLUGIN_CUSTOM_DEV.md` §5 — Plugin development standards
