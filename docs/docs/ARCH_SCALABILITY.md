# ARCH_SCALABILITY — Khả Năng Mở Rộng & Tích Hợp

> **Dự án:** Website XANH - Design & Build
> **Phiên bản:** 1.0 | **Ngày tạo:** 2026-03-12
> **Mục tiêu:** Đảm bảo codebase có thể mở rộng cho Phase 2+ mà không cần refactor lại

---

## 1. Extensibility Architecture

### Hook System (WordPress Way)

XANH custom theme sử dụng hooks để mở rộng mà KHÔNG sửa code gốc:

```php
// === CUSTOM ACTIONS (do_action) ===

// Theme lifecycle hooks
do_action('xanh_theme_setup');              // Sau khi theme setup
do_action('xanh_enqueue_scripts');          // Trước khi đóng enqueue

// Content hooks
do_action('xanh_before_hero', $page_id);    // Trước hero section
do_action('xanh_after_hero', $page_id);     // Sau hero section
do_action('xanh_before_content');           // Trước main content
do_action('xanh_after_content');            // Sau main content

// Project hooks
do_action('xanh_before_project_card', $post_id);
do_action('xanh_after_project_card', $post_id);
do_action('xanh_before_project_gallery', $post_id);
do_action('xanh_project_sidebar', $post_id);   // Phase 2: related, CTA

// Form hooks
do_action('xanh_before_form', $form_id);
do_action('xanh_after_form_submit', $form_id, $data);

// Footer hooks
do_action('xanh_footer_before');
do_action('xanh_footer_after');
```

```php
// === CUSTOM FILTERS (apply_filters) ===

$price = apply_filters('xanh_estimator_price', $price, $area, $package);
$columns = apply_filters('xanh_portfolio_columns', 3);
$per_page = apply_filters('xanh_portfolio_per_page', 9);
$categories = apply_filters('xanh_blog_categories', $categories);
$cta_text = apply_filters('xanh_default_cta_text', 'Đặt Lịch Tư Vấn Riêng');
$nav_items = apply_filters('xanh_nav_items', $items);
```

### Tại sao quan trọng?
- Phase 2 plugin (Client Portal) có thể **hook** vào theme mà không sửa template
- Custom integrations (CRM, analytics) dùng `xanh_after_form_submit`
- Pricing thay đổi → `xanh_estimator_price` filter, không sửa calculator

---

## 2. Adding New Pages

### Checklist thêm trang mới:

```
1. Tạo docs/PAGE_{NEW_PAGE}.md           ← Đặc tả page
2. Tạo page-{slug}.php                   ← Template
3. Tạo template-parts/hero/hero-{slug}.php ← Hero section
4. Tạo template-parts/sections/...        ← Page-specific sections
5. Thêm ACF field group (nếu cần)        ← inc/acf-fields.php
6. Cập nhật nav menu                      ← WordPress admin
7. Cập nhật sitemap                       ← Auto (nếu dùng plugin)
8. Cập nhật CORE_AI_CONTEXT.md            ← File map
```

### Template Pattern:
```php
<?php get_header(); ?>

<main id="main-content" class="page-{slug}">
  <?php do_action('xanh_before_hero', get_the_ID()); ?>
  <?php get_template_part('template-parts/hero', '{slug}'); ?>
  <?php do_action('xanh_after_hero', get_the_ID()); ?>

  <?php do_action('xanh_before_content'); ?>
  <!-- Page-specific sections -->
  <?php get_template_part('template-parts/sections', 'section-name'); ?>
  <?php do_action('xanh_after_content'); ?>

  <?php get_template_part('template-parts/sections', 'cta-global'); ?>
</main>

<?php get_footer(); ?>
```

---

## 3. Adding New Components

### Checklist thêm component mới:

```
1. Thêm spec vào docs/ARCH_UI_PATTERNS.md    ← HTML structure + interaction
2. Tạo template-parts/components/{name}.php   ← PHP template
3. Thêm styles vào assets/css/components.css  ← BEM classes
4. Thêm JS (nếu interactive) vào module tương ứng
5. Sử dụng component tokens (Layer 3):
   - var(--card-radius), var(--card-shadow), var(--transition-base)
6. Test responsive: mobile → tablet → desktop
7. Test accessibility: keyboard nav, screen reader
8. Cập nhật component matrix trong ARCH_UI_PATTERNS.md
```

### Component Token Compliance:
```css
/* PHẢI dùng semantic tokens — KHÔNG hardcode */
.new-component {
  background: var(--card-bg);
  border-radius: var(--card-radius);
  box-shadow: var(--card-shadow);
  padding: var(--card-padding);
  transition: var(--card-transition);
}
.new-component:hover {
  box-shadow: var(--card-shadow-hover);
  transform: translateY(-4px);
}
```

---

## 4. Adding New Custom Post Types

### Checklist thêm CPT mới:

```
1. Đăng ký CPT trong inc/cpt-registration.php
2. Tạo ACF field group cho CPT
3. Tạo templates:
   - single-xanh_{cpt}.php    ← Detail page
   - archive-xanh_{cpt}.php   ← List page (nếu has_archive)
   - template-parts/content-{cpt}-card.php ← Card component
4. Cập nhật inc/custom-functions.php   ← WP_Query helpers
5. Cập nhật docs/CORE_DATA_MODEL.md   ← Schema + relationships
6. Cập nhật CORE_AI_CONTEXT.md        ← File map
7. Flush permalinks (Settings → Permalinks → Save)
```

### CPT Registration Pattern:
```php
function xanh_register_{cpt}() {
    register_post_type('xanh_{cpt}', [
        'labels'       => [...],
        'public'       => true/false,
        'has_archive'  => true/false,
        'supports'     => ['title', 'thumbnail', ...],
        'menu_icon'    => 'dashicons-{icon}',
        'rewrite'      => ['slug' => '{vi-slug}'],
        'show_in_rest' => true, // ★ REST API ready
    ]);
}
add_action('init', 'xanh_register_{cpt}');
```

---

## 5. Phase 2 Roadmap

### 5.1 Client Portal (`xanh-client-portal` plugin)

| Feature | Mô tả | Hook vào |
|---|---|---|
| Custom login page | Branded login UI | `login_enqueue_scripts` |
| Client dashboard | Xem tiến độ thi công | Custom page template |
| Nhật ký thi công | Ảnh hàng ngày + ghi chú | CPT `xanh_diary` |
| Biên bản nghiệm thu | Upload/ký online | CPT `xanh_acceptance` |
| Email notification | Khi có cập nhật mới | `xanh_after_diary_update` |

**Architecture:**
```
xanh-client-portal/
├── xanh-client-portal.php
├── includes/
│   ├── class-portal-setup.php      # CPTs, roles, capabilities
│   ├── class-portal-dashboard.php  # Dashboard template logic
│   ├── class-portal-api.php        # REST API endpoints
│   └── class-portal-notify.php     # Email notifications
├── templates/
│   ├── login.php
│   ├── dashboard.php
│   └── diary-entry.php
└── assets/
    ├── css/portal.css
    └── js/portal.js
```

### 5.2 Multi-language (Phase 2)

| Approach | Plugin | Impact |
|---|---|---|
| **Option A:** WPML | WPML Multilingual | Full translation, URL: `/en/`, ACF compatible |
| **Option B:** Polylang | Polylang Pro | Lighter, free option, ACF compatible |

**Preparation (làm từ Phase 1):**
- Textdomain `xanh` trên tất cả strings: `__('text', 'xanh')`, `esc_html_e('text', 'xanh')`
- Vietnamese slugs: giữ `/du-an/`, English: `/projects/`
- Schema markup: `"inLanguage": "vi"`

### 5.3 360°/VR Tour (Phase 2)

| Feature | Technology | Integration |
|---|---|---|
| 360° photos | Matterport / Kuula embed | `<iframe>` in project detail |
| Virtual tour | Matterport SDK | Custom shortcode `[xanh_vr_tour]` |

**Preparation:**
- ACF field `project_vr_url` (URL) — sẵn sàng cho Phase 2
- Template-part `template-parts/components/vr-tour.php` — chỉ render nếu field có giá trị

---

## 6. REST API Readiness

### Current Endpoints (Phase 1)

```
GET /wp-json/wp/v2/xanh_project      ← Public (show_in_rest: true)
GET /wp-json/wp/v2/project_type       ← Taxonomy
GET /wp-json/wp/v2/project_status     ← Taxonomy
```

### Custom Endpoints (khi cần)

```php
// Pattern cho custom REST endpoint
add_action('rest_api_init', function() {
    register_rest_route('xanh/v1', '/estimator', [
        'methods'             => 'POST',
        'callback'            => 'xanh_api_estimate',
        'permission_callback' => '__return_true', // Public
        'args'                => [
            'area'    => ['required' => true, 'type' => 'number', 'minimum' => 10],
            'floors'  => ['required' => true, 'type' => 'integer', 'minimum' => 1],
            'package' => ['required' => true, 'enum' => ['basic', 'standard', 'premium']],
        ],
    ]);
});
```

### Phase 2 API Endpoints (planned)

```
POST /xanh/v1/estimator              ← Tính dự toán (public)
GET  /xanh/v1/portal/dashboard       ← Client dashboard data (auth)
GET  /xanh/v1/portal/diary/{id}      ← Nhật ký thi công (auth)
POST /xanh/v1/portal/acceptance      ← Biên bản nghiệm thu (auth)
```

---

## 7. Third-Party Integration Strategy

### Current (Phase 1)

| Service | Method | File |
|---|---|---|
| Google Analytics 4 | `<script async>` | `header.php` |
| Facebook Pixel | `<script async>` | `header.php` |
| Zalo OA Widget | Lazy JS (3s delay) | `footer.php` |
| Google Maps | `<iframe>` embed | Contact page |
| Fluent Form SMTP | Plugin config | Admin |

### Future-Ready Patterns

```php
// ★ Centralized integration manager
// inc/integrations.php

function xanh_load_integrations() {
    // Chỉ load tracking sau cookie consent
    if (xanh_has_cookie_consent()) {
        do_action('xanh_load_analytics');
    }

    // Third-party widgets lazy load
    add_action('wp_footer', 'xanh_lazy_load_widgets');
}

function xanh_lazy_load_widgets() {
    // Zalo: load after 3s
    // Chatbot (Phase 2): load after scroll
    // Hotjar (Phase 2): load on interaction
}
```

---

## 8. Database Migration Strategy

### Khi thêm custom tables (Phase 2):

```php
// Plugin activation hook
function xanh_portal_activate() {
    global $wpdb;
    $charset = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}xanh_diary (
        id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        project_id BIGINT(20) UNSIGNED NOT NULL,
        client_id BIGINT(20) UNSIGNED NOT NULL,
        content TEXT,
        images TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_project (project_id),
        INDEX idx_client (client_id)
    ) $charset;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);

    update_option('xanh_portal_db_version', '1.0');
}
register_activation_hook(__FILE__, 'xanh_portal_activate');
```

---

## Tài Liệu Liên Quan

- `CORE_ARCHITECTURE.md` — Theme file structure, hook architecture
- `CORE_DATA_MODEL.md` — CPT schema, WP_Query patterns
- `PLUGIN_CUSTOM_DEV.md` — Plugin development details
- `TRACK_BLUEPRINT.md` — Sprint roadmap, Phase 2 timeline
