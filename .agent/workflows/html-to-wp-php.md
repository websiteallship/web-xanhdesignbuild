---
description: Chuyển đổi file HTML/CSS/JS thuần sang file PHP chuẩn WordPress theme. Dùng khi cần convert wireframe/mockup tĩnh thành WordPress theme hoạt động.
---

# HTML/CSS/JS → WordPress PHP Theme

## Skills cần đọc trước khi bắt đầu
- `@wordpress-theme-development` — Cấu trúc theme + template hierarchy
- `@php-pro` — Viết PHP chuẩn
- `@frontend-design` — Đảm bảo UI không thay đổi

## Yêu cầu đầu vào
- File HTML/CSS/JS nguồn (đường dẫn tuyệt đối)
- Theme slug (ví dụ: `xanh-theme`)

---

## Bước 1: Phân Tích HTML Nguồn

Đọc file HTML, xác định và lập mapping:

| HTML Element | WordPress PHP Target |
|---|---|
| `<head>` (meta, title) | `header.php` + `wp_head()` |
| `<link stylesheet>` | `wp_enqueue_style()` trong `inc/enqueue.php` |
| `<script src>` | `wp_enqueue_script()` trong `inc/enqueue.php` |
| `<script>` inline | `wp_add_inline_script()` |
| Navigation HTML | `wp_nav_menu()` |
| Header chung | `header.php` → gọi `get_header()` |
| Footer chung | `footer.php` → gọi `get_footer()` |
| Main content | `page-templates/` hoặc `template-parts/` |
| `src="image/..."` | `esc_url( XANH_THEME_URI . '/assets/images/...' )` |
| `href="#"` / link tĩnh | `esc_url( home_url('/...') )` |
| Blog cards tĩnh | WP Loop + `get_template_part()` |
| Form liên hệ | Contact Form 7 / WPForms shortcode |

---

## Bước 2: Scaffold Theme

Tạo cấu trúc trong `wp-content/themes/{slug}/`:

```
{slug}/
├── style.css              ← Theme header (bắt buộc)
├── functions.php          ← Constants + require inc/*.php
├── index.php              ← Fallback (bắt buộc)
├── header.php / footer.php
├── front-page.php         ← Trang chủ
├── page.php / single.php / archive.php / 404.php
├── page-templates/        ← Template Name: Giới Thiệu, v.v.
├── template-parts/        ← Sections tách nhỏ
├── assets/css/ js/ images/
├── inc/                   ← enqueue.php, theme-support.php, menus.php, custom-post-types.php
└── languages/
```

**`functions.php` pattern:**
- `define()` constants: `XANH_THEME_VERSION`, `XANH_THEME_DIR`, `XANH_THEME_URI`
- `require_once` các file trong `inc/`

---

## Bước 3: Tạo `header.php`

**Quy tắc chuyển đổi:**
- XÓA tất cả `<link>` và `<script>` → đã enqueue
- `<html lang="vi">` → `<html <?php language_attributes(); ?>>`
- `<meta charset="UTF-8">` → `<meta charset="<?php bloginfo('charset'); ?>">`
- THÊM `<?php wp_head(); ?>` trước `</head>`
- `<body class="...">` → `<body <?php body_class('...'); ?>>`
- THÊM `<?php wp_body_open(); ?>` sau `<body>`
- Logo tĩnh → `has_custom_logo()` / `the_custom_logo()`
- Nav tĩnh → `wp_nav_menu(['theme_location' => 'primary', ...])`

## Bước 4: Tạo `footer.php`

- XÓA tất cả `<script>` tags
- THÊM `<?php wp_footer(); ?>` trước `</body>`
- Copyright year → `<?php echo date('Y'); ?>`
- Tên site → `<?php bloginfo('name'); ?>`

---

## Bước 5: Enqueue Assets (`inc/enqueue.php`)

**Global assets** (load mọi trang):
- Google Fonts → `wp_enqueue_style('handle', 'URL', [], null)`
- Base CSS → `wp_enqueue_style('handle', XANH_THEME_URI . '/assets/css/base.css', ['deps'], VER)`
- Tailwind CDN → `wp_enqueue_script()` + `wp_add_inline_script()` cho config
- GSAP, ScrollTrigger, Lenis, Lucide → `wp_enqueue_script()` với `$in_footer = true`

**Page-specific assets** (load theo điều kiện):
- `is_front_page()` → home.css + home.js
- `is_page('slug')` hoặc `is_page_template()` → about/contact/portfolio .css + .js
- `is_home() || is_archive()` → blog.css + blog.js
- `is_singular('post')` → blog-detail.css + blog-detail.js
- `is_singular('portfolio')` → portfolio-detail.css + portfolio-detail.js

**Dependency order:** GSAP → ScrollTrigger → Page JS

---

## Bước 6: Chuyển Sections HTML → PHP Templates

Mỗi file HTML → file PHP template:
```php
<?php
/**
 * Template Name: Giới Thiệu
 * Template Post Type: page
 */
get_header();
// Mỗi <section> → get_template_part('template-parts/{page}/{section}');
get_footer();
```

**Checklist cho mỗi section:**
- [ ] Ảnh: `src="image/..."` → `<?php echo esc_url(XANH_THEME_URI.'/assets/images/...'); ?>`
- [ ] Links: href tĩnh → `<?php echo esc_url(home_url('/...')); ?>`
- [ ] Giữ nguyên: Tailwind classes, GSAP classes, Lucide icons, ARIA attributes
- [ ] Text tĩnh: giữ hardcoded hoặc dùng ACF nếu cần CMS quản lý

---

## Bước 7: Nội Dung Động

**Blog listing** → WP Loop:
```php
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <?php get_template_part('template-parts/components/card-blog'); ?>
<?php endwhile; the_posts_pagination(); endif; ?>
```

**Blog card** dùng: `the_ID()`, `has_post_thumbnail()`, `the_post_thumbnail()`, `get_the_category()`, `the_permalink()`, `the_title()`, `get_the_excerpt()`, `get_the_date()`

**Portfolio CPT** — `register_post_type('portfolio', [...])` trong `inc/custom-post-types.php`

**PHP → JS data** — `wp_localize_script('handle', 'varName', ['ajaxUrl' => admin_url('admin-ajax.php')])`

---

## Bước 8: Theme Support (`inc/theme-support.php`)

Trong `after_setup_theme` hook:
- `add_theme_support('title-tag')`
- `add_theme_support('post-thumbnails')`
- `add_theme_support('custom-logo', [...])`
- `add_theme_support('html5', ['search-form', 'comment-form', ...])`
- `add_image_size()` cho các kích thước custom

---

## Bước 9: Escaping — BẮT BUỘC

| Loại output | Hàm escape |
|---|---|
| URL | `esc_url()` |
| HTML attribute | `esc_attr()` |
| Text | `esc_html()` |
| Rich HTML | `wp_kses_post()` |
| Template tags | `the_title()`, `the_content()` → đã auto-escape |

KHÔNG dùng `eval()`, `base64_decode()`. Form phải có `wp_nonce_field()`.

---

## Bước 10: Verification

1. Activate theme → WP Admin
2. So sánh visual: PHP vs HTML gốc (layout, fonts, animations, icons, ảnh)
3. Responsive: Mobile / Tablet / Desktop
4. Console: không JS errors, không 404 assets
5. WP functions: Menu, Logo, Dynamic content
6. Chạy plugin **Theme Check**

**Lỗi thường gặp:**

| Triệu chứng | Fix |
|---|---|
| CSS không load | Check đường dẫn `XANH_THEME_URI` |
| `gsap is not defined` | Thêm `'gsap'` vào dependency array |
| Ảnh 404 | Copy ảnh vào `assets/images/` |
| Menu không hiển thị | Register menu location trong `inc/menus.php` |
| `wp_head()` thiếu | Thêm trước `</head>` trong `header.php` |
