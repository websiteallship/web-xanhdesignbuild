# CORE_ARCHITECTURE — Kiến Trúc Hệ Thống

> **Dự án:** Website XANH - Design & Build
> **Phiên bản:** 1.1 | **Cập nhật:** 2026-03-12

---

## 1. Tổng Quan Stack

```
┌──────────────────────────────────────────┐
│               BROWSER                     │
│  HTML/CSS/JS + Custom Theme Frontend      │
├──────────────────────────────────────────┤
│             WORDPRESS CORE                │
│  ┌──────────┐  ┌───────────────────────┐ │
│  │  Custom   │  │    Plugin Ecosystem   │ │
│  │  Theme    │  │  ACF Pro | Fluent Form│ │
│  │  (xanh-  │  │  LiteSpeed | Smush    │ │
│  │  theme)   │  │  Classic Editor       │ │
│  └──────────┘  │  Custom Plugins        │ │
│                 └───────────────────────┘ │
├──────────────────────────────────────────┤
│          MySQL Database                   │
│   Posts | CPTs | ACF Fields | Options     │
├──────────────────────────────────────────┤
│       Hosting (LiteSpeed Server)          │
│   SSL | CDN | Object Cache               │
└──────────────────────────────────────────┘
```

---

## 2. Custom Theme Structure

```
wp-content/themes/xanh-theme/
├── style.css                    # Theme header + base styles
├── functions.php                # Theme setup, enqueue, CPT registration
├── index.php                    # Fallback template
├── front-page.php               # Trang Chủ (HomePage)
├── page-about.php               # Trang Giới Thiệu
├── page-green-solution.php      # Trang Giải Pháp Xanh
├── page-contact.php             # Trang Liên Hệ
├── page-estimator.php           # Trang Dự Toán
│
├── archive-xanh_project.php     # Portfolio Grid
├── single-xanh_project.php      # Chi tiết dự án
├── archive.php                  # Blog listing
├── single.php                   # Chi tiết bài viết
├── search.php                   # Trang tìm kiếm
├── 404.php                      # Trang 404
│
├── header.php                   # Global header + navigation
├── footer.php                   # Global footer
├── sidebar.php                  # Blog sidebar
│
├── template-parts/
│   ├── hero/
│   │   ├── hero-home.php
│   │   ├── hero-page.php
│   │   └── hero-archive.php
│   ├── content/
│   │   ├── content-project-card.php
│   │   ├── content-blog-card.php
│   │   ├── content-testimonial.php
│   │   ├── content-team-member.php
│   │   └── content-related-post.php     # ★ Blog related articles
│   ├── sections/
│   │   ├── section-4xanh.php            # Triết lý 4 Xanh (reusable)
│   │   ├── section-process-steps.php     # Quy trình 6 bước
│   │   ├── section-counter.php           # Animated Counter
│   │   ├── section-partners.php          # Partner Logos
│   │   ├── section-cta.php               # CTA block
│   │   └── section-faq.php               # FAQ Accordion
│   ├── components/
│   │   ├── before-after-slider.php
│   │   ├── material-board.php
│   │   ├── lightbox-gallery.php
│   │   ├── video-popup.php
│   │   ├── floating-cta-mobile.php
│   │   ├── breadcrumb.php
│   │   ├── cookie-consent.php
│   │   ├── preloader.php
│   │   ├── back-to-top.php
│   │   ├── reading-progress-bar.php     # ★ Blog reading progress
│   │   ├── social-share.php             # ★ FB, Zalo, Copy link
│   │   ├── search-autocomplete.php      # ★ Blog search dropdown
│   │   ├── skeleton-loading.php         # ★ Filter loading state
│   │   ├── table-of-contents.php        # ★ Blog auto-generated ToC
│   │   ├── card-flip.php                # ★ 4 Xanh philosophy cards
│   │   └── tooltip.php                  # ★ Material Board, Trust Box
│   └── forms/
│       ├── form-estimator.php
│       └── form-lead-capture.php
│
├── inc/
│   ├── theme-setup.php           # add_theme_support, menus, sidebars
│   ├── enqueue.php               # CSS/JS enqueue (conditional loading)
│   ├── cpt-registration.php      # Custom Post Types
│   ├── acf-fields.php            # ACF field group registration
│   ├── custom-functions.php      # Helper functions (data retrieval)
│   ├── ajax-handlers.php         # AJAX filtering, load more
│   ├── shortcodes.php            # Custom shortcodes
│   ├── template-tags.php         # ★ Reusable template functions
│   └── walker-nav.php            # ★ Custom nav walker (nếu cần)
│
├── package.json                 # npm deps (Tailwind CLI)
├── tailwind.config.js           # Tailwind configuration (colors, fonts, screens)
│
├── assets/
│   ├── css/
│   │   ├── input.css             # Tailwind directives (@tailwind base/components/utilities)
│   │   ├── output.css            # ★ CLI-generated (DO NOT EDIT) — purged Tailwind
│   │   ├── variables.css         # XANH brand tokens (CSS custom properties)
│   │   └── components.css        # Custom component styles (where Tailwind isn't enough)
│   ├── js/
│   │   ├── main.js               # App init, Lenis, GSAP global, scroll reveal
│   │   ├── animations.js         # GSAP timelines, counters, card-flip
│   │   ├── slider.js             # Swiper init: Before/After, partners, materials
│   │   ├── gallery.js            # GLightbox init: lightbox, video popup
│   │   ├── filter.js             # AJAX filtering + skeleton loading
│   │   ├── forms.js              # Form validation, UX, progress indicator
│   │   └── search.js             # Autocomplete blog search
│   │   # ★ NO vendor/ folder — all vendor JS via CDN (jsDelivr)
│   ├── icons/                    # ★ Lucide SVG icons (chỉ copy icons cần dùng)
│   │   ├── house.svg
│   │   ├── phone.svg
│   │   ├── mail.svg
│   │   ├── leaf.svg              # 4 Xanh icons
│   │   └── ...
│   ├── fonts/
│   │   ├── FoundersGrotesk/      # Medium + Bold (.otf)
│   │   └── Inter/                # Variable font (.ttf)
│   └── images/
│       ├── icons/                # Custom brand SVG icons (4 Xanh, UI)
│       └── placeholders/         # Dev placeholders
│
└── languages/                    # i18n (nếu cần)
```

---

## 3. WordPress Configuration

### wp-config.php (Production)
```php
// Performance
define('WP_CACHE', true);
define('COMPRESS_CSS', true);
define('COMPRESS_SCRIPTS', true);

// Security
define('DISALLOW_FILE_EDIT', true);
define('WP_AUTO_UPDATE_CORE', 'minor');

// Debug (chỉ dùng khi dev)
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);
```

### Permalink Structure
```
/%postname%/
```

### Custom Post Type Slugs
- Portfolio: `/du-an/`
- Blog: `/tin-tuc/`

---

## 4. Asset Pipeline

### CSS Architecture (Tailwind + CSS Variables)
```
input.css          → Tailwind directives (@tailwind base/components/utilities)
  ↓ [CLI build: npx @tailwindcss/cli -i input.css -o output.css --minify]
output.css         → Purged utility CSS (production)
  ↓
variables.css      → XANH brand tokens (CSS custom properties)
  ↓
components.css     → Custom component styles (complex animations, multi-state)
```

### JS Loading Strategy (CDN + Custom)

| Script | Source | Loading | Condition | Size (gzip) |
|---|---|---|---|---|
| Alpine.js | CDN (jsDelivr) | `defer` head | Tất cả trang | ~15KB |
| GSAP | CDN (jsDelivr) | `defer` footer | Tất cả trang | ~15KB |
| ScrollTrigger | CDN (jsDelivr) | `defer` footer | Tất cả trang | ~8KB |
| Lenis | CDN (jsDelivr) | `defer` footer | Tất cả trang | ~4KB |
| Lucide | CDN (unpkg) | `defer` footer | Tất cả trang | ~0KB* |
| `main.js` | Local | `defer` footer | Tất cả trang | ~3KB |
| `animations.js` | Local | `defer` footer | Tất cả trang | ~2KB |
| Swiper | CDN (jsDelivr) | `defer` footer | Home, Portfolio detail | ~15KB |
| `slider.js` | Local | `defer` footer | = Swiper condition | ~1KB |
| GLightbox | CDN (jsDelivr) | `defer` footer | Portfolio detail | ~8KB |
| `gallery.js` | Local | `defer` footer | = GLightbox condition | ~1KB |
| `filter.js` | Local | `defer` footer | Portfolio, Blog | ~2KB |
| `forms.js` | Local | `defer` footer | Contact, Home | ~2KB |
| `search.js` | Local | `defer` footer | Blog | ~1KB |

> *Lucide: 0KB nếu dùng inline SVG (preferred), hoặc ~8KB nếu dùng CDN createIcons()

### Font Loading
```css
/* Chỉ load 2 weights thực sự cần */
@font-face {
  font-family: 'FoundersGrotesk';
  src: url('../fonts/FoundersGrotesk/FoundersGroteskMedium.otf') format('opentype');
  font-weight: 500;
  font-display: swap;
}
@font-face {
  font-family: 'FoundersGrotesk';
  src: url('../fonts/FoundersGrotesk/FoundersGroteskBold.otf') format('opentype');
  font-weight: 700;
  font-display: swap;
}
```

---

## 5. Template Hierarchy — WordPress Resolution

```
Yêu cầu URL                    → WordPress chọn template
─────────────────────────────────────────────────────────
/                               → front-page.php → index.php
/gioi-thieu/                    → page-about.php → page.php → index.php
/giai-phap-xanh/                → page-green-solution.php → page.php
/lien-he/                       → page-contact.php → page.php
/du-toan/                       → page-estimator.php → page.php
/du-an/                         → archive-xanh_project.php → archive.php
/du-an/biet-thu-anh-hoang/      → single-xanh_project.php → single.php
/tin-tuc/                       → home.php → archive.php → index.php
/tin-tuc/tieu-de-bai-viet/      → single.php → index.php
/?s=keyword                     → search.php → index.php
/not-found                      → 404.php → index.php
```

### Template Loading Flow
```
WordPress Core
  └── Resolve template file
      └── get_header() ──────────────► header.php
      └── [Page template content]
          ├── get_template_part('template-parts/hero', 'home')
          ├── get_template_part('template-parts/sections', 'counter')
          └── get_template_part('template-parts/components', 'before-after-slider')
      └── get_sidebar() ─────────────► sidebar.php (chỉ Blog)
      └── get_footer() ──────────────► footer.php
```

---

## 6. Data Flow Layer

### ACF → Template Flow
```
┌──────────────────────────┐
│     ACF Admin UI         │ ← Editor nhập data qua giao diện
│  (Groups → Fields)       │
└──────────┬───────────────┘
           ↓ wp_postmeta / wp_options
┌──────────────────────────┐
│     MySQL Database       │ ← ACF lưu vào meta/options
│  wp_postmeta (per-post)  │
│  wp_options (global)     │
└──────────┬───────────────┘
           ↓ get_field() / get_option()
┌──────────────────────────┐
│  inc/custom-functions.php │ ← Helper functions xử lý logic
│  (data retrieval layer)  │
└──────────┬───────────────┘
           ↓ xanh_get_*() functions
┌──────────────────────────┐
│   template-parts/*.php   │ ← Render HTML
│   (presentation layer)   │
└──────────────────────────┘
```

### Helper Functions Pattern (inc/custom-functions.php)
```php
/**
 * Lấy dữ liệu dự án đã format
 * Tách logic khỏi template → dễ test, dễ thay đổi data source
 */
function xanh_get_project_stats($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    return [
        'location'     => get_field('project_location', $post_id),
        'area'         => get_field('project_area', $post_id),
        'floors'       => get_field('project_floors', $post_id),
        'duration'     => get_field('project_duration', $post_id),
        'budget'       => get_field('project_budget', $post_id),
        'match_3d'     => get_field('project_3d_match', $post_id),
        'cost_overrun' => get_field('project_cost_overrun', $post_id),
    ];
}

function xanh_get_site_info($key) {
    // Lấy từ ACF Options Page
    return get_field($key, 'option');
}

function xanh_get_featured_projects($count = 3) {
    $ids = get_field('featured_projects', get_option('page_on_front'));
    if (!$ids) return [];
    return array_slice($ids, 0, $count);
}
```

### Template Usage
```php
<!-- template-parts/sections/section-counter.php -->
<?php
$stats = xanh_get_project_stats();
?>
<section class="counter-bar" id="counter-bar">
    <div class="counter-bar__item" data-counter="<?= esc_attr($stats['area']); ?>">
        <span class="counter-bar__number">0</span>
        <span class="counter-bar__label">m² đã thi công</span>
    </div>
    <!-- ... -->
</section>
```

---

## 7. Hook Architecture

### Theme Setup Hooks (inc/theme-setup.php)

```php
/* ═══════════════════════════════════════
   HOOK REGISTRY — Thứ tự thực thi
   ═══════════════════════════════════════ */

// Priority 1: Theme features
add_action('after_setup_theme', 'xanh_theme_setup');       // Menus, thumbnails, HTML5

// Priority 2: Register CPTs + Taxonomies
add_action('init', 'xanh_register_post_types');            // xanh_project, xanh_testimonial
add_action('init', 'xanh_register_taxonomies');            // project_type, project_status

// Priority 3: Enqueue assets (conditional)
add_action('wp_enqueue_scripts', 'xanh_enqueue_styles');   // CSS always
add_action('wp_enqueue_scripts', 'xanh_enqueue_scripts');  // JS conditional

// Priority 4: AJAX handlers
add_action('wp_ajax_xanh_filter_projects', 'xanh_ajax_filter_projects');
add_action('wp_ajax_nopriv_xanh_filter_projects', 'xanh_ajax_filter_projects');
add_action('wp_ajax_xanh_load_more', 'xanh_ajax_load_more');
add_action('wp_ajax_nopriv_xanh_load_more', 'xanh_ajax_load_more');
add_action('wp_ajax_xanh_search_posts', 'xanh_ajax_search_posts');
add_action('wp_ajax_nopriv_xanh_search_posts', 'xanh_ajax_search_posts');

// Priority 5: ACF Options Pages
add_action('acf/init', 'xanh_register_options_pages');

// Priority 6: Schema markup
add_action('wp_head', 'xanh_schema_markup');                // JSON-LD structured data
add_action('wp_head', 'xanh_meta_tags');                    // OG, Twitter cards
```

### Custom Hooks (Extensibility)
```php
// Cho phép plugins/child-themes hook vào
do_action('xanh_before_hero', $page_type);         // Trước hero section
do_action('xanh_after_hero', $page_type);           // Sau hero section
do_action('xanh_before_footer_cta');                // Trước CTA cuối trang
do_action('xanh_project_stats', $project_id);       // Sau stats bar
apply_filters('xanh_estimator_result', $result);    // Filter kết quả dự toán
apply_filters('xanh_counter_items', $items);        // Filter counter data
```

---

## 8. AJAX Architecture

### Endpoints

| Action | Function | Nonce | Trang |
|---|---|---|---|
| `xanh_filter_projects` | Filter Portfolio theo taxonomy | `xanh_filter_nonce` | Portfolio Grid |
| `xanh_load_more` | Load thêm posts (pagination) | `xanh_loadmore_nonce` | Portfolio, Blog |
| `xanh_search_posts` | Autocomplete search | `xanh_search_nonce` | Blog |
| `xanh_calculate_estimate` | Tính dự toán | `xanh_estimator_nonce` | Home, Estimator |

### AJAX Flow Pattern
```
[Browser JS]                        [WordPress PHP]
─────────────                       ─────────────────
User click filter                   
  → filter.js                       
    → fetch(admin_url + action)     → wp_ajax_xanh_filter_projects()
                                       → verify nonce
                                       → sanitize inputs
                                       → WP_Query with tax_query
                                       → Loop: get_template_part()
                                       → ob_get_clean()
    ← JSON { html, total, page }   ← wp_send_json_success()
    → Replace DOM + transition
    → Update skeleton → real cards
```

### Localized Data (wp_localize_script)
```php
wp_localize_script('xanh-filter', 'xanhAjax', [
    'url'             => admin_url('admin-ajax.php'),
    'filter_nonce'    => wp_create_nonce('xanh_filter_nonce'),
    'loadmore_nonce'  => wp_create_nonce('xanh_loadmore_nonce'),
    'search_nonce'    => wp_create_nonce('xanh_search_nonce'),
    'loading_text'    => 'Đang tải...',
    'no_results_text' => 'Không tìm thấy kết quả.',
]);
```

---

## 9. Component Dependency Map

```
front-page.php (HomePage)
  ├── hero/hero-home.php
  ├── sections/section-4xanh.php
  │     └── components/card-flip.php ★
  ├── sections/section-counter.php
  ├── components/before-after-slider.php
  ├── forms/form-estimator.php
  ├── sections/section-process-steps.php
  ├── content/content-testimonial.php
  ├── sections/section-partners.php
  ├── sections/section-cta.php
  └── [Global] components/floating-cta-mobile.php
               components/cookie-consent.php
               components/preloader.php
               components/back-to-top.php

single-xanh_project.php (Portfolio Detail)
  ├── components/breadcrumb.php
  ├── hero/hero-page.php
  ├── sections/section-counter.php (mini stats)
  ├── components/before-after-slider.php
  ├── components/material-board.php
  │     └── components/tooltip.php ★
  ├── components/lightbox-gallery.php
  ├── content/content-testimonial.php
  └── sections/section-cta.php

single.php (Blog Detail)
  ├── components/breadcrumb.php
  ├── components/reading-progress-bar.php ★
  ├── components/table-of-contents.php ★
  ├── [Main content]
  ├── components/social-share.php ★
  ├── content/content-related-post.php ★
  └── [Sidebar]
        └── forms/form-lead-capture.php

archive.php (Blog List) / archive-xanh_project.php (Portfolio Grid)
  ├── hero/hero-archive.php
  ├── components/search-autocomplete.php ★ (Blog only)
  ├── [Sticky filter bar]
  ├── content/content-blog-card.php | content-project-card.php
  ├── components/skeleton-loading.php ★ (on filter)
  └── sections/section-cta.php
```

---

## 10. JS State Management

### State Sources

| State | Storage | Ví dụ |
|---|---|---|
| First visit | `sessionStorage` | Preloader chỉ hiện 1 lần |
| Cookie consent | `localStorage` | `xanh_cookie_consent: true` |
| Blog read | `localStorage` | `xanh_read_[post_id]: true` |
| Filter state | URL params | `?type=biet-thu&status=da-ban-giao` |
| Scroll position | JS variable | Cho back-to-top visibility |
| Counter animated | DOM `data-animated` | Tránh re-animate khi scroll lại |
| Active gallery index | JS variable | PhotoSwipe current slide |
| Form progress | JS variable | Estimator form step tracking |

### URL-based Filter State (Portfolio/Blog)
```javascript
// Sync filter state với URL → bookmark-able, shareable
const XanhFilter = {
    updateURL(params) {
        const url = new URL(window.location);
        Object.entries(params).forEach(([k, v]) => {
            v ? url.searchParams.set(k, v) : url.searchParams.delete(k);
        });
        history.pushState({}, '', url);
    },

    getState() {
        const params = new URLSearchParams(window.location.search);
        return {
            type: params.get('type') || 'all',
            status: params.get('status') || 'all',
            page: parseInt(params.get('page')) || 1,
        };
    },
};
```

### IntersectionObserver Registry
```javascript
// Centralized observer cho scroll-based components
const XanhObservers = {
    counters: new IntersectionObserver(cb, { threshold: 0.5 }),
    reveals:  new IntersectionObserver(cb, { threshold: 0.1 }),
    lazyLoad: new IntersectionObserver(cb, { rootMargin: '200px' }),
};
```

---

## Tài Liệu Liên Quan

- `CORE_DATA_MODEL.md` — CPTs, ACF fields
- `ARCH_UI_PATTERNS.md` — 27 UI component specs
- `PLUGIN_ECOSYSTEM.md` — Chi tiết plugins
- `PLUGIN_CUSTOM_DEV.md` — Custom plugins
- `ARCH_PERFORMANCE.md` — Optimization strategies
- `GOV_CODING_STANDARDS.md` — Coding conventions
