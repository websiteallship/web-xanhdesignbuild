# Hiệu Suất & SEO — Hướng Dẫn Tối Ưu Toàn Diện

> **Dự án:** XANH Design & Build WordPress Theme
> **Mục tiêu:** PageSpeed ≥ 90 (Mobile + Desktop), Core Web Vitals Pass, SEO-ready
> **Ngày tạo:** 2026-03-18

---

## Mục Lục

1. [Core Web Vitals](#1-core-web-vitals)
2. [Tối Ưu Ảnh](#2-tối-ưu-ảnh)
3. [Tối Ưu CSS](#3-tối-ưu-css)
4. [Tối Ưu JavaScript](#4-tối-ưu-javascript)
5. [Tối Ưu Animation (GSAP/CSS)](#5-tối-ưu-animation)
6. [Tối Ưu Font](#6-tối-ưu-font)
7. [SEO On-Page](#7-seo-on-page)
8. [Schema.org Structured Data](#8-schema-structured-data)
9. [WordPress Backend Performance](#9-wordpress-backend-performance)
10. [Caching & CDN](#10-caching--cdn)
11. [Checklist Tổng Hợp](#11-checklist-tổng-hợp)

---

## 1. Core Web Vitals

Đây là 3 chỉ số Google dùng đánh giá trải nghiệm người dùng:

| Metric | Mục tiêu | Đo lường |
|---|---|---|
| **LCP** (Largest Contentful Paint) | < 2.5s | Thời gian render phần tử lớn nhất (hero image) |
| **INP** (Interaction to Next Paint) | < 200ms | Thời gian phản hồi tương tác |
| **CLS** (Cumulative Layout Shift) | < 0.1 | Mức độ nhảy layout |

### Chiến lược cho XANH:

**LCP — Hero Image là yếu tố quyết định:**
```php
// header.php — Preload hero image
<?php if (is_front_page()) : ?>
  <link rel="preload" as="image" href="<?php echo esc_url($hero_img_url); ?>"
        type="image/webp" fetchpriority="high">
<?php endif; ?>
```

**CLS — Luôn set width/height cho ảnh:**
```php
// Dùng wp_get_attachment_image() — tự động thêm width/height
echo wp_get_attachment_image($image_id, 'full', false, [
    'class'   => 'w-full h-full object-cover',
    'loading' => 'lazy',
]);
```

**INP — Giảm JS blocking main thread:**
- Defer tất cả scripts non-critical
- Conditional enqueue (chỉ load JS khi cần)
- Tránh long tasks > 50ms

---

## 2. Tối Ưu Ảnh

### 2.1 WordPress Image Sizes

```php
// inc/theme-setup.php
function xanh_custom_image_sizes() {
    add_image_size('xanh-hero', 1920, 1080, true);      // Hero (crop)
    add_image_size('xanh-card', 640, 480, true);         // Blog/project card
    add_image_size('xanh-thumb', 400, 300, true);        // Thumbnail
    add_image_size('xanh-partner', 320, 120, false);     // Partner logo
    add_image_size('xanh-team', 400, 500, true);         // Team member

    // Disable default large sizes (tiết kiệm storage)
    remove_image_size('1536x1536');
    remove_image_size('2048x2048');
}
add_action('after_setup_theme', 'xanh_custom_image_sizes');
```

### 2.2 WebP Auto-Conversion

> Plugin **Smush Pro** (production) tự convert sang WebP. Theme code không cần xử lý format.

### 2.3 Lazy Loading Strategy

| Vị trí | Loading | Lý do |
|---|---|---|
| Hero image (above fold) | `loading="eager"` + `fetchpriority="high"` | LCP element |
| Logo, header icons | `loading="eager"` | Above fold |
| Section images (below fold) | `loading="lazy"` | Giảm initial load |
| Gallery images | `loading="lazy"` | Nhiều ảnh, load khi scroll |
| Partner logos | `loading="lazy"` | Thấp ưu tiên |

### 2.4 Responsive srcset

```php
// Template helper — responsive hero image
function xanh_responsive_hero($image_id) {
    $full   = wp_get_attachment_image_url($image_id, 'xanh-hero');
    $medium = wp_get_attachment_image_url($image_id, 'large');
    $small  = wp_get_attachment_image_url($image_id, 'medium_large');
    $alt    = get_post_meta($image_id, '_wp_attachment_image_alt', true);
    ?>
    <img src="<?php echo esc_url($full); ?>"
         srcset="<?php echo esc_url($small); ?> 768w,
                 <?php echo esc_url($medium); ?> 1024w,
                 <?php echo esc_url($full); ?> 1920w"
         sizes="100vw"
         alt="<?php echo esc_attr($alt); ?>"
         width="1920" height="1080"
         loading="eager"
         fetchpriority="high"
         decoding="async"
         class="w-full h-full object-cover">
    <?php
}
```

---

## 3. Tối Ưu CSS

### 3.1 Tailwind CSS Purge (Critical)

```bash
# Build production — purge classes không dùng
npm run build
# → npx @tailwindcss/cli -i input.css -o output.css --minify
# → file output ~20-50KB thay vì 3MB+
```

### 3.2 Critical CSS Strategy

```php
// inc/enqueue.php — Inline critical CSS, defer the rest
function xanh_critical_css() {
    if (is_front_page()) {
        // Inline critical CSS cho hero + header + above fold
        $critical = file_get_contents(XANH_THEME_DIR . '/assets/css/critical-home.css');
        echo '<style id="critical-css">' . $critical . '</style>';
    }
}
add_action('wp_head', 'xanh_critical_css', 1);
```

### 3.3 Tách CSS theo trang

```php
// Conditional loading — chỉ load CSS khi cần
function xanh_enqueue_page_styles() {
    $uri = XANH_THEME_URI;
    $ver = XANH_THEME_VERSION;

    // Page-specific CSS
    if (is_front_page()) {
        wp_enqueue_style('xanh-home', "$uri/assets/css/pages/home.css", ['xanh-main'], $ver);
    }
    if (is_page('gioi-thieu')) {
        wp_enqueue_style('xanh-about', "$uri/assets/css/pages/about.css", ['xanh-main'], $ver);
    }
    if (is_page('lien-he')) {
        wp_enqueue_style('xanh-contact', "$uri/assets/css/pages/contact.css", ['xanh-main'], $ver);
    }
}
```

### 3.4 CSS Performance Rules

- ✅ Dùng `transform` và `opacity` cho animation thay vì `width`, `height`, `top`, `left`
- ✅ Tránh `@import` — dùng `wp_enqueue_style()` dependency chain
- ✅ Minify CSS production build
- ❌ Không dùng `!important` trừ utilities
- ❌ Không animate `box-shadow`, `filter` trên large surfaces

---

## 4. Tối Ưu JavaScript

### 4.1 Defer & Conditional Loading

```php
// inc/enqueue.php
function xanh_enqueue_scripts() {
    $uri = XANH_THEME_URI;
    $ver = XANH_THEME_VERSION;

    // ═══ Global (mọi trang) ═══
    wp_enqueue_script('gsap', 'https://cdn.jsdelivr.net/npm/gsap@3.12.7/dist/gsap.min.js',
        [], '3.12.7', ['strategy' => 'defer', 'in_footer' => true]);
    wp_enqueue_script('gsap-st', 'https://cdn.jsdelivr.net/npm/gsap@3.12.7/dist/ScrollTrigger.min.js',
        ['gsap'], '3.12.7', ['strategy' => 'defer', 'in_footer' => true]);
    wp_enqueue_script('lenis', 'https://cdn.jsdelivr.net/npm/lenis@1.3.17/dist/lenis.min.js',
        [], '1.3.17', ['strategy' => 'defer', 'in_footer' => true]);

    // Main JS
    wp_enqueue_script('xanh-main', "$uri/assets/js/main.js",
        ['gsap', 'gsap-st', 'lenis'], $ver, ['strategy' => 'defer', 'in_footer' => true]);

    // ═══ Conditional (chỉ trang cần) ═══
    // Swiper — Homepage + Portfolio Detail
    if (is_front_page() || is_singular('xanh_project')) {
        wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
            [], '11', ['strategy' => 'defer', 'in_footer' => true]);
    }
    // GLightbox — Portfolio Detail only
    if (is_singular('xanh_project')) {
        wp_enqueue_script('glightbox', 'https://cdn.jsdelivr.net/npm/glightbox@3/dist/glightbox.min.js',
            [], '3', ['strategy' => 'defer', 'in_footer' => true]);
    }
    // AJAX Filter — Portfolio List + Blog
    if (is_post_type_archive('xanh_project') || is_home()) {
        wp_enqueue_script('xanh-filter', "$uri/assets/js/filter.js",
            ['xanh-main'], $ver, ['strategy' => 'defer', 'in_footer' => true]);
        wp_localize_script('xanh-filter', 'xanhAjax', [
            'url'   => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('xanh_filter_nonce'),
        ]);
    }
    // Lucide Icons — load sau cùng
    wp_enqueue_script('lucide', 'https://unpkg.com/lucide@latest',
        [], null, ['strategy' => 'defer', 'in_footer' => true]);
}
add_action('wp_enqueue_scripts', 'xanh_enqueue_scripts');
```

### 4.2 Third-Party Script Delay (Analytics, Zalo, Chat)

```php
// Delay loading non-essential 3rd party scripts (3s)
function xanh_delayed_scripts() {
    ?>
    <script>
    // Zalo Widget — delay 3s
    setTimeout(function() {
        var s = document.createElement('script');
        s.src = 'https://sp.zalo.me/plugins/sdk.js';
        s.async = true;
        document.body.appendChild(s);
    }, 3000);
    </script>
    <?php
}
add_action('wp_footer', 'xanh_delayed_scripts', 99);
```

### 4.3 Remove WP Bloat

```php
// inc/theme-setup.php — Remove unnecessary WP scripts
function xanh_remove_wp_bloat() {
    // Remove emoji scripts
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');

    // Remove oEmbed
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');

    // Remove REST API link
    remove_action('wp_head', 'rest_output_link_wp_head');

    // Remove RSD link
    remove_action('wp_head', 'rsd_link');

    // Remove Windows Live Writer
    remove_action('wp_head', 'wlwmanifest_link');

    // Remove shortlink
    remove_action('wp_head', 'wp_shortlink_wp_head');

    // Remove generator
    remove_action('wp_head', 'wp_generator');

    // Disable Global Styles inline CSS (Classic Theme — WP 6.9)
    remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
    remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');

    // Dequeue block library CSS (nếu không dùng Gutenberg frontend)
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('classic-theme-styles');
}
add_action('wp_enqueue_scripts', 'xanh_remove_wp_bloat', 100);
```

---

## 5. Tối Ưu Animation

### 5.1 GSAP Performance Rules (cho XANH)

```javascript
// ✅ Chỉ animate compositor properties
gsap.from('.hero-el', {
    opacity: 0,          // ✅ compositor
    y: 30,               // ✅ transform (translateY)
    duration: 0.8,
    stagger: 0.15,
    ease: 'power2.out',
});

// ❌ KHÔNG animate layout properties
gsap.to('.box', {
    width: '200px',      // ❌ triggers layout
    height: '100px',     // ❌ triggers layout
    left: '50px',        // ❌ triggers layout
});
```

### 5.2 ScrollTrigger — Pause Off-Screen

```javascript
// ✅ Chỉ animate khi element visible
gsap.utils.toArray('.anim-fade-up').forEach(el => {
    gsap.from(el, {
        scrollTrigger: {
            trigger: el,
            start: 'top 85%',
            toggleActions: 'play none none none', // Chỉ play 1 lần
        },
        opacity: 0,
        y: 40,
        duration: 0.6,
        ease: 'power2.out',
    });
});
```

### 5.3 `will-change` — Dùng cẩn thận

```css
/* ✅ Chỉ add khi animation sắp xảy ra */
.process-panel.is-animating {
    will-change: transform, opacity;
}

/* ❌ KHÔNG set will-change permanent trên mọi element */
/* .anim-fade-up { will-change: transform; } ← TRÁNH */
```

### 5.4 `prefers-reduced-motion`

```css
/* Tôn trọng user preference */
@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }
}
```

```javascript
// JS check
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
if (prefersReducedMotion) {
    // Skip Lenis smooth scroll
    // Disable GSAP ScrollTrigger animations
    gsap.globalTimeline.timeScale(100); // instant
}
```

---

## 6. Tối Ưu Font

### 6.1 Self-Host Inter (Variable)

```css
/* assets/css/variables.css */
@font-face {
    font-family: 'Inter';
    src: url('../fonts/Inter/InterVariable.woff2') format('woff2-variations');
    font-weight: 300 800;
    font-display: swap;         /* ← Không block render */
    font-style: normal;
    unicode-range: U+0000-024F; /* Latin + Vietnamese */
}
```

### 6.2 Preload Font

```php
// header.php
<link rel="preload" href="<?php echo esc_url(XANH_THEME_URI); ?>/assets/fonts/Inter/InterVariable.woff2"
      as="font" type="font/woff2" crossorigin>
```

### 6.3 Subsetting (Production)

```bash
# Chỉ giữ ký tự Latin + Vietnamese → giảm ~60% size
npx glyphhanger --whitelist="U+0000-024F,U+1EA0-1EF9" \
    --subset=InterVariable.woff2 --formats=woff2
```

---

## 7. SEO On-Page

### 7.1 Meta Tags (RankMath sẽ handle, nhưng theme cần hỗ trợ)

```php
// inc/theme-setup.php
add_theme_support('title-tag'); // WordPress quản lý <title>
```

### 7.2 Heading Hierarchy

| Trang | H1 | H2 | H3 |
|---|---|---|---|
| Homepage | 1 (Hero headline) | Mỗi section 1 H2 | Sub-sections |
| About | 1 (Hero) | Mỗi section | Pain items, team names |
| Contact | 1 (Hero) | Section titles | FAQ questions |
| Blog List | 1 (page title) | — | Post titles |
| Blog Detail | 1 (post title) | Content headings | — |
| Portfolio List | 1 (page title) | — | Project titles |
| Portfolio Detail | 1 (project title) | Section titles | — |

> **Rule:** Mỗi trang chỉ có **1 H1 duy nhất**.

### 7.3 Image Alt Text

```php
// Luôn output alt text từ WP Media Library
$alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
echo '<img src="..." alt="' . esc_attr($alt ?: get_the_title($image_id)) . '">';
```

### 7.4 Internal Linking

```php
// Breadcrumb trên mọi trang (trừ homepage)
function xanh_breadcrumb() {
    if (is_front_page()) return;
    ?>
    <nav aria-label="Breadcrumb" class="breadcrumb">
        <a href="<?php echo esc_url(home_url('/')); ?>">Trang Chủ</a>
        <span>›</span>
        <?php if (is_singular('xanh_project')) : ?>
            <a href="<?php echo esc_url(get_post_type_archive_link('xanh_project')); ?>">Dự Án</a>
            <span>›</span>
        <?php endif; ?>
        <span><?php the_title(); ?></span>
    </nav>
    <?php
}
```

### 7.5 Canonical & Open Graph

> **RankMath Pro** sẽ tự handle canonical, OG tags, Twitter cards.
> Theme chỉ cần đảm bảo:
- `add_theme_support('title-tag')`
- Không output duplicate `<title>` tags
- Mỗi page có featured image cho OG image

---

## 8. Schema Structured Data

### 8.1 Applicable Schema Types cho XANH

| Trang | Schema Type | Rich Result |
|---|---|---|
| Homepage | `Organization` + `WebSite` | Sitelinks search box |
| About | `Organization` | Brand knowledge panel |
| Contact | `LocalBusiness` | Google Maps, contact info |
| Blog Detail | `Article` / `BlogPosting` | Article rich result |
| Portfolio Detail | `CreativeWork` | — |
| Mọi trang | `BreadcrumbList` | Breadcrumb trail |
| Contact FAQ | `FAQPage` | FAQ rich result |

### 8.2 Implementation — `inc/schema.php`

```php
// LocalBusiness (Contact page)
function xanh_schema_local_business() {
    if (!is_page('lien-he')) return;

    $schema = [
        '@context'    => 'https://schema.org',
        '@type'       => 'GeneralContractor',
        'name'        => 'XANH Design & Build',
        'url'         => home_url('/'),
        'logo'        => XANH_THEME_URI . '/assets/images/logo.svg',
        'telephone'   => get_field('xanh_hotline', 'option'),
        'email'       => get_field('xanh_email', 'option'),
        'address'     => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => '123 Nguyễn Tất Thành',
            'addressLocality' => 'Nha Trang',
            'addressRegion'   => 'Khánh Hòa',
            'addressCountry'  => 'VN',
        ],
        'openingHoursSpecification' => [
            '@type'     => 'OpeningHoursSpecification',
            'dayOfWeek' => ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'],
            'opens'     => '08:00',
            'closes'    => '17:30',
        ],
        'areaServed'  => 'Khánh Hòa, Việt Nam',
        'priceRange'  => '$$$',
    ];
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE) . '</script>';
}
add_action('wp_head', 'xanh_schema_local_business');

// FAQPage (Contact page)
function xanh_schema_faq() {
    if (!is_page('lien-he')) return;
    $faq_items = get_field('faq_items');
    if (!$faq_items) return;

    $entities = [];
    foreach ($faq_items as $item) {
        $entities[] = [
            '@type'          => 'Question',
            'name'           => $item['faq_question'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => $item['faq_answer'],
            ],
        ];
    }
    $schema = [
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => $entities,
    ];
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE) . '</script>';
}
add_action('wp_head', 'xanh_schema_faq');

// Article (Blog single)
function xanh_schema_article() {
    if (!is_singular('post')) return;

    $schema = [
        '@context'      => 'https://schema.org',
        '@type'         => 'Article',
        'headline'      => get_the_title(),
        'datePublished' => get_the_date('c'),
        'dateModified'  => get_the_modified_date('c'),
        'author'        => ['@type' => 'Person', 'name' => get_the_author()],
        'publisher'     => [
            '@type' => 'Organization',
            'name'  => 'XANH Design & Build',
            'logo'  => ['@type' => 'ImageObject', 'url' => XANH_THEME_URI . '/assets/images/logo.svg'],
        ],
        'image'         => get_the_post_thumbnail_url(null, 'xanh-hero'),
        'description'   => wp_trim_words(get_the_excerpt(), 30),
    ];
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE) . '</script>';
}
add_action('wp_head', 'xanh_schema_article');

// BreadcrumbList (All pages except homepage)
function xanh_schema_breadcrumb() {
    if (is_front_page()) return;

    $items = [['@type' => 'ListItem', 'position' => 1, 'name' => 'Trang Chủ', 'item' => home_url('/')]];
    $pos = 2;

    if (is_singular('xanh_project')) {
        $items[] = ['@type' => 'ListItem', 'position' => $pos++, 'name' => 'Dự Án',
                    'item' => get_post_type_archive_link('xanh_project')];
    }
    if (is_singular('post')) {
        $items[] = ['@type' => 'ListItem', 'position' => $pos++, 'name' => 'Blog',
                    'item' => get_permalink(get_option('page_for_posts'))];
    }
    $items[] = ['@type' => 'ListItem', 'position' => $pos, 'name' => get_the_title()];

    $schema = ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $items];
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE) . '</script>';
}
add_action('wp_head', 'xanh_schema_breadcrumb');
```

---

## 9. WordPress Backend Performance

### 9.1 Disable Unnecessary Features

```php
// inc/theme-setup.php
// Disable XML-RPC (bảo mật + hiệu suất)
add_filter('xmlrpc_enabled', '__return_false');

// Disable Heartbeat (admin only, giảm AJAX requests)
function xanh_heartbeat_settings($settings) {
    $settings['interval'] = 60; // 60s thay vì 15s
    return $settings;
}
add_filter('heartbeat_settings', 'xanh_heartbeat_settings');

// Limit post revisions
if (!defined('WP_POST_REVISIONS')) {
    define('WP_POST_REVISIONS', 5);
}
```

### 9.2 Database Query Optimization

```php
// ✅ Dùng 'fields' => 'ids' khi chỉ cần IDs
$project_ids = new WP_Query([
    'post_type'      => 'xanh_project',
    'posts_per_page' => 6,
    'fields'         => 'ids', // ← Nhanh hơn nhiều
]);

// ✅ Update meta cache 1 lần cho batch queries
update_meta_cache('post', $project_ids->posts);

// ✅ Transient caching cho heavy queries
function xanh_get_featured_projects_cached() {
    $cached = get_transient('xanh_featured_projects');
    if ($cached !== false) return $cached;

    $projects = get_field('featured_projects', get_option('page_on_front'));
    set_transient('xanh_featured_projects', $projects, HOUR_IN_SECONDS);
    return $projects;
}
```

### 9.3 Autoload Optimization

```php
// wp-config.php — Monitor autoload
// Kiểm tra: SELECT SUM(LENGTH(option_value)) FROM wp_options WHERE autoload = 'yes';
// Mục tiêu: < 800KB autoloaded options
```

---

## 10. Caching & CDN

### 10.1 Browser Cache Headers

```apache
# .htaccess
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/webp                 "access plus 1 year"
    ExpiresByType image/avif                 "access plus 1 year"
    ExpiresByType image/jpeg                 "access plus 1 year"
    ExpiresByType image/png                  "access plus 1 year"
    ExpiresByType image/svg+xml              "access plus 1 year"
    ExpiresByType font/woff2                 "access plus 1 year"
    ExpiresByType text/css                   "access plus 1 month"
    ExpiresByType application/javascript     "access plus 1 month"
</IfModule>

# Gzip compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/css text/javascript
    AddOutputFilterByType DEFLATE application/javascript application/json
    AddOutputFilterByType DEFLATE image/svg+xml
</IfModule>
```

### 10.2 Plugin Caching (Production)

| Plugin | Chức năng |
|---|---|
| **WP Super Cache** hoặc **LiteSpeed Cache** | Full page cache |
| **Smush Pro** | Image optimization + WebP + lazy load |
| **RankMath SEO** | Meta tags + schema + sitemap |

### 10.3 CDN Strategy

- Static assets (CSS, JS, fonts, images) → CDN
- Dynamic pages → WP cache plugin
- GSAP, Swiper, Lenis → jsDelivr CDN (đã dùng)

---

## 11. Checklist Tổng Hợp

### Core Web Vitals

- [ ] LCP < 2.5s — hero image preloaded, optimized
- [ ] INP < 200ms — JS deferred, no long tasks
- [ ] CLS < 0.1 — tất cả ảnh có width/height, font-display: swap

### Images

- [ ] WebP format (Smush auto-convert)
- [ ] Custom image sizes registered
- [ ] Lazy loading cho below-fold images
- [ ] `fetchpriority="high"` cho hero
- [ ] Alt text cho tất cả ảnh
- [ ] srcset cho responsive images

### CSS

- [ ] Tailwind purged (production build)
- [ ] Critical CSS inline (hero + header)
- [ ] Page-specific CSS conditional loading
- [ ] No render-blocking stylesheets
- [ ] Minified output

### JavaScript

- [ ] All scripts `defer` + `in_footer`
- [ ] Conditional enqueue (Swiper, GLightbox, filter)
- [ ] WP bloat removed (emoji, oEmbed, etc.)
- [ ] Third-party delayed (Zalo, analytics)
- [ ] AJAX nonce localized

### Animations

- [ ] GSAP only animates `transform` + `opacity`
- [ ] ScrollTrigger `toggleActions: 'play none none none'`
- [ ] `prefers-reduced-motion` respected
- [ ] No `will-change` on static elements
- [ ] IntersectionObserver for visibility

### SEO

- [ ] `add_theme_support('title-tag')`
- [ ] 1 H1 per page
- [ ] Semantic HTML5 (section, article, nav, main)
- [ ] Breadcrumb on all sub-pages
- [ ] Schema.org: LocalBusiness, Article, FAQPage, BreadcrumbList
- [ ] Open Graph meta (via RankMath)
- [ ] XML Sitemap (via RankMath)
- [ ] `robots.txt` configured

### WordPress

- [ ] WP bloat removed
- [ ] Post revisions limited (5)
- [ ] Heartbeat interval increased (60s)
- [ ] XML-RPC disabled
- [ ] Transient caching for heavy queries
- [ ] Object cache (production)

### Hosting & Caching

- [ ] Gzip/Brotli compression enabled
- [ ] Browser cache headers set
- [ ] Full page cache plugin (production)
- [ ] CDN for static assets (production)

---

## Tài Liệu Tham Khảo

| Nguồn | URL |
|---|---|
| Core Web Vitals | https://web.dev/vitals/ |
| PageSpeed Insights | https://pagespeed.web.dev/ |
| Schema.org Validator | https://validator.schema.org/ |
| Rich Results Test | https://search.google.com/test/rich-results |
| WebPageTest | https://webpagetest.org/ |
| Lighthouse | Chrome DevTools → Lighthouse tab |

---

## Tài Liệu Liên Quan

| File | Mô tả |
|---|---|
| [THEME_CONVENTIONS.md](./THEME_CONVENTIONS.md) | Enqueue patterns, security |
| [CONVERT_HTML_TO_WP.md](./CONVERT_HTML_TO_WP.md) | Lộ trình chuyển đổi |
| [ACF_FIELD_GROUPS.md](./ACF_FIELD_GROUPS.md) | Quản lý nội dung |
| [../CORE_ARCHITECTURE.md](../CORE_ARCHITECTURE.md) | Kiến trúc tổng thể |
