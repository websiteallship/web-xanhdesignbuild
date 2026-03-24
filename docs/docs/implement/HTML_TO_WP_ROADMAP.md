# Lộ Trình Chuyển Đổi HTML → WordPress Theme `xanhdesignbuild`

> **Ngày tạo:** 2026-03-18
> **Tham chiếu:** [CONVERT_HTML_TO_WP.md](./CONVERT_HTML_TO_WP.md) | [ACF_FIELD_GROUPS.md](./ACF_FIELD_GROUPS.md) | [THEME_CONVENTIONS.md](./THEME_CONVENTIONS.md)

## Tổng Quan

Chuyển đổi 7 trang wireframe HTML/CSS/JS tĩnh thành WordPress theme hoạt động, tuân thủ đầy đủ quy tắc trong `.agent/rules/12-html-to-wp-conversion.md`, workflow `/html-to-wp-php`, và các docs trong `docs/implement/`.

### Hiện Trạng

| Mục | Trạng thái |
|---|---|
| 7 wireframe pages (HTML/CSS/JS) | ✅ Hoàn thành |
| Shared assets (`_shared/base.css` + `shared/base.js`) | ✅ |
| Docs & Rules (40+ files + 17 rules) | ✅ |
| WordPress Core (Local Sites) | ✅ |
| Plugins (ACF Pro, Classic Editor, Fluent Form, Fluent Form Pro) | ✅ |
| Theme `xanhdesignbuild` | ❌ Chưa tồn tại |

### Quyết Định Đã Xác Nhận

- **Theme slug:** `xanhdesignbuild`
- **Tailwind CSS:** CLI build (`npx @tailwindcss/cli`) → output purged CSS
- **Ảnh:** WP Media Library (không copy ảnh vào theme)
- **Content:** Hardcoded trước (Phase B), ACF integration ở Phase C
- **Header/Footer:** Lấy chuẩn từ `wireframes/homepage_02/home-page.html`
- **Thứ tự trang:** Homepage → About → Portfolio → Blog → Contact
- **Prefix:** `xanh_` (functions), `xanh-` (slugs)

### Wireframe Inventory

| # | Trang | Source Files | HTML Size | CSS Size | JS Size | Độ phức tạp |
|---|---|---|---|---|---|---|
| 1 | Homepage | `homepage_02/home-page.*` | 101KB | 69KB | 43KB | ⭐⭐⭐ |
| 2 | About | `about/about.*` | 48KB | 21KB | 17KB | ⭐⭐ |
| 3 | Contact | `contact/contact.*` | 18KB | 20KB | 7KB | ⭐ |
| 4 | Blog List | `blog/blog.*` | 32KB | 32KB | 10KB | ⭐⭐ |
| 5 | Blog Detail | `blog/blog-detail.*` | 29KB | 14KB | 6KB | ⭐⭐ |
| 6 | Portfolio List | `portfolio/portfolio.*` | 21KB | 14KB | 8KB | ⭐⭐⭐ |
| 7 | Portfolio Detail | `portfolio/portfolio-detail.*` | 63KB | 62KB | 19KB | ⭐⭐⭐ |
| — | Shared | `_shared/base.css` + `shared/base.js` | — | 12KB | 15KB | — |

---

## Phase A — Theme Foundation

> **Mục tiêu:** Tạo skeleton theme hoạt động với header/footer/enqueue đúng, không lỗi console.

### A1. Scaffold Theme Structure

#### [NEW] Theme directory `wp-content/themes/xanhdesignbuild/`

```
xanhdesignbuild/
├── style.css                    ← Theme header (required)
├── functions.php                ← Constants + require inc/*.php
├── index.php                    ← Fallback (required)
├── screenshot.png               ← Theme thumbnail
├── header.php / footer.php
├── front-page.php               ← Homepage (placeholder)
├── page-about.php
├── page-contact.php
├── archive.php                  ← Blog listing
├── single.php                   ← Blog detail
├── archive-xanh_project.php     ← Portfolio grid
├── single-xanh_project.php      ← Portfolio detail
├── 404.php
│
├── inc/
│   ├── theme-setup.php          ← add_theme_support, menus, image sizes
│   ├── enqueue.php              ← Conditional CSS/JS loading
│   ├── cpt-registration.php     ← 3 CPTs + taxonomies
│   ├── acf-fields.php           ← ACF Options Page
│   ├── custom-functions.php     ← xanh_get_*() helpers
│   ├── ajax-handlers.php        ← AJAX endpoints
│   └── template-tags.php        ← Reusable template functions
│
├── template-parts/
│   ├── hero/
│   ├── content/
│   ├── sections/
│   ├── components/
│   └── forms/
│
├── assets/
│   ├── css/
│   │   ├── input.css            ← Tailwind directives (@tailwind base/components/utilities)
│   │   ├── output.css           ← CLI-generated (DO NOT EDIT)
│   │   ├── variables.css        ← CSS custom properties (from _shared/base.css)
│   │   ├── components.css       ← Component styles (from _shared/base.css)
│   │   └── pages/               ← Page-specific CSS
│   │       ├── home.css
│   │       ├── about.css
│   │       ├── contact.css
│   │       ├── blog.css
│   │       ├── blog-detail.css
│   │       ├── portfolio.css
│   │       └── portfolio-detail.css
│   ├── js/
│   │   ├── main.js              ← From shared/base.js (Lenis, GSAP global)
│   │   └── pages/               ← Page-specific JS
│   │       ├── home.js
│   │       ├── about.js
│   │       ├── contact.js
│   │       ├── blog.js
│   │       ├── blog-detail.js
│   │       ├── portfolio.js
│   │       └── portfolio-detail.js
│   ├── fonts/Inter/             ← Self-hosted variable font
│   └── images/                  ← Logo SVGs only
│
├── tailwind.config.js
├── package.json
└── languages/
```

---

### A2. Core Files

#### [NEW] `style.css` — Theme header
- Theme Name, Author, Version, Text Domain (`xanh`)

#### [NEW] `functions.php`
- Define: `XANH_THEME_VERSION`, `XANH_THEME_DIR`, `XANH_THEME_URI`
- `require_once` all `inc/*.php` files

#### [NEW] `inc/theme-setup.php`
- `add_theme_support('title-tag', 'post-thumbnails', 'html5', 'custom-logo')`
- Register nav menus: `primary`, `footer`
- Register custom image sizes

#### [NEW] `inc/enqueue.php`
- Follow pattern from `01-wordpress-theme.md` rule
- Global: Tailwind output.css → variables.css → components.css
- Vendor CDN: GSAP → ScrollTrigger → Lenis → Lucide
- Custom: main.js (dep: gsap, gsap-st, lenis)
- Conditional page CSS/JS via `is_front_page()`, `is_page()`, etc.
- Conditional: Swiper (homepage + portfolio detail), GLightbox (portfolio detail)

#### [NEW] `inc/cpt-registration.php`
- `xanh_project` CPT (`/du-an/`, archive: yes, `show_in_rest: true`)
- `xanh_testimonial` CPT (`/chung-thuc/`, archive: no)
- `xanh_team` CPT (`/doi-ngu/`, archive: no)
- Taxonomies: `project_type`, `project_status`

#### [NEW] `inc/acf-fields.php`
- ACF Options Page: "Cài Đặt XANH"
- Fields: hotline, email, address, social links, footer settings

---

### A3. Header & Footer

> **Source:** `wireframes/homepage_02/home-page.html` (header: lines 61-206, footer: lines 1751-1894)

#### [NEW] `header.php`
**Conversion từ wireframe header HTML:**
- `<html lang="vi">` → `<html <?php language_attributes(); ?>>`
- `<meta charset>` → `<?php bloginfo('charset'); ?>`
- Remove all `<link>` / `<script>` → handled by enqueue
- Add `<?php wp_head(); ?>` before `</head>`
- `<body>` → `<body <?php body_class(); ?>>`+ `<?php wp_body_open(); ?>`
- Static logo → `the_custom_logo()` (hoặc giữ inline SVG + link `home_url()`)
- Static nav → `wp_nav_menu(['theme_location' => 'primary'])`
- CTA hotline → `get_field('xanh_hotline', 'option')`
- **Giữ nguyên:** Tailwind classes, GSAP selectors, ARIA attributes, mobile menu HTML

#### [NEW] `footer.php`
- Remove all `<script>` tags
- Add `<?php wp_footer(); ?>` before `</body>`
- Copyright year → `<?php echo date('Y'); ?>`
- Site name → `<?php bloginfo('name'); ?>`
- Company info → ACF Options (`get_field('xanh_*', 'option')`)
- Social links → ACF Options
- **Giữ nguyên:** Layout classes, animations, icons

---

### A4. Tailwind CLI Setup

#### [NEW] `package.json` + `tailwind.config.js`
- Brand colors: primary `#14513D`, accent `#FF8A00`, light `#F3F4F6`, beige `#D8C7A3`
- Font: Inter
- Content paths: `./*.php`, `./template-parts/**/*.php`

#### [NEW] `assets/css/input.css`
- `@tailwind base; @tailwind components; @tailwind utilities;`
- Custom `@layer` definitions nếu cần

---

### A5. Shared Assets Migration

| Source | Target | Action |
|---|---|---|
| `_shared/base.css` :root tokens | `assets/css/variables.css` | Extract CSS custom properties only |
| `_shared/base.css` components | `assets/css/components.css` | BEM component styles, animations |
| `shared/base.js` | `assets/js/main.js` | Lenis init, GSAP global, header scroll, scroll reveal |
| Wireframe `img/` logos | `assets/images/` | Copy logo SVGs only |
| `docs/FONT/` Inter font | `assets/fonts/Inter/` | Self-hosted variable font |

---

## Phase B — Page Templates

> **Mục tiêu:** Chuyển từng trang HTML → PHP templates.
> **Thứ tự:** Homepage → About → Portfolio → Blog → Contact

### Quy trình chung cho mỗi trang

1. **Phân tích HTML:** Xác định các `<section>` chính
2. **Tạo page template:** `page-{slug}.php` hoặc template file phù hợp
3. **Tách sections:** Mỗi `<section>` → `template-parts/sections/section-{name}.php`
4. **Chuyển đổi:** Áp dụng checklist từ `12-html-to-wp-conversion.md`:
   - Ảnh: `src="image/..."` → `esc_url(XANH_THEME_URI.'/assets/images/...')` hoặc ACF
   - Links: href tĩnh → `esc_url(home_url('/...'))`
   - Giữ nguyên: Tailwind classes, GSAP selectors, data-* attributes, ARIA
5. **Migrate CSS:** Page CSS → `assets/css/pages/{page}.css`
6. **Migrate JS:** Page JS → `assets/js/pages/{page}.js`
7. **Enqueue:** Add conditional loading trong `inc/enqueue.php`

---

### B1. Homepage (⭐⭐⭐ Phức tạp nhất — Ưu tiên #1)

#### [NEW] `front-page.php`
- 10+ sections, `do_action()` hooks giữa mỗi section

#### [NEW] Template parts (10 files):
- `template-parts/hero/hero-home.php` — Full-screen hero + Swiper slider
- `template-parts/sections/section-marquee.php` — Marquee divider
- `template-parts/sections/section-empathy.php`
- `template-parts/sections/section-4xanh.php`
- `template-parts/sections/section-services.php`
- `template-parts/sections/section-cta.php`
- `template-parts/sections/section-portfolio-featured.php` — Before/After slider
- `template-parts/sections/section-process.php`
- `template-parts/sections/section-cta-contact.php`
- `template-parts/sections/section-partners.php`
- `template-parts/sections/section-blog-latest.php` — Blog Swiper

#### Assets:
- `assets/css/pages/home.css` ← from `wireframes/homepage_02/home-page.css`
- `assets/js/pages/home.js` ← from `wireframes/homepage_02/home-page.js`

---

### B2. About Page (⭐⭐)

#### [NEW] `page-about.php`
- 7+ sections (Hero, Pain, Turning Point, Promise, 4 Xanh, Core Values, Team)

#### [NEW] Template parts:
- `template-parts/hero/hero-about.php`
- `template-parts/sections/section-pain.php`
- `template-parts/sections/section-turning-point.php`
- `template-parts/sections/section-promise.php`
- `template-parts/sections/section-4xanh-about.php`
- `template-parts/sections/section-core-values.php`
- `template-parts/sections/section-team.php`

#### Assets:
- `assets/css/pages/about.css` ← from `wireframes/about/about.css`
- `assets/js/pages/about.js` ← from `wireframes/about/about.js`

---

### B3. Portfolio List + Detail (⭐⭐⭐)

#### [NEW] `archive-xanh_project.php`
- CPT archive with AJAX filter
- `WP_Query` + `template-parts/content/card-project.php`

#### [NEW] `single-xanh_project.php`
- Gallery (GLightbox), Before/After (Swiper), Project specs, Related projects

#### [NEW] Template parts:
- `template-parts/hero/hero-project.php`
- `template-parts/content/card-project.php`
- `template-parts/sections/section-project-overview.php`
- `template-parts/sections/section-before-after.php`
- `template-parts/sections/section-project-gallery.php`
- `template-parts/sections/section-related-projects.php`

#### Assets:
- `assets/css/pages/portfolio.css` + `portfolio-detail.css`
- `assets/js/pages/portfolio.js` + `portfolio-detail.js`
- `assets/js/filter.js` ← AJAX filtering

---

### B4. Blog List + Detail (⭐⭐)

#### [NEW] `archive.php`
- WP Loop: `have_posts()` / `the_post()` / `get_template_part()`
- `the_posts_pagination()`

#### [NEW] `template-parts/content/card-blog.php`
- `the_post_thumbnail()`, `get_the_category()`, `the_permalink()`, `the_title()`, `get_the_excerpt()`, `get_the_date()`

#### [NEW] `single.php`
- Single post template: `the_content()`, sidebar, related posts, breadcrumb

#### Assets:
- `assets/css/pages/blog.css` + `blog-detail.css`
- `assets/js/pages/blog.js` + `blog-detail.js`

---

### B5. Contact Page (⭐ Đơn giản nhất)

#### [NEW] `page-contact.php`
- 3-4 sections (Hero, Contact Info + Form, Map, FAQ)

#### [NEW] Template parts:
- `template-parts/hero/hero-contact.php`
- `template-parts/sections/section-contact-form.php` — Fluent Form shortcode
- `template-parts/sections/section-contact-map.php`
- `template-parts/sections/section-faq.php`

#### Assets:
- `assets/css/pages/contact.css` ← from `wireframes/contact/contact.css`
- `assets/js/pages/contact.js` ← from `wireframes/contact/contact.js`

---

## Phase C — Polish & Verify

### C1. ACF Integration
- Register 7 field groups (~158 fields) theo `ACF_FIELD_GROUPS.md`
- Replace hardcoded text → `get_field()` + `esc_html()`

### C2. Security Audit
- Escape ALL output: `esc_html()`, `esc_url()`, `esc_attr()`, `wp_kses_post()`
- Null-check tất cả ACF fields trước khi truy cập
- AJAX nonces + capability checks
- No `eval()`, no `base64_decode()`

### C3. Performance
- Tailwind CLI purge (chỉ giữ classes dùng thật)
- Image lazy loading (`loading="lazy"`)
- `prefers-reduced-motion` support
- JS budget: ~80KB gzip vendor + ~12KB custom/page

---

## Verification Plan

### Automated (sau mỗi Phase)

```bash
# 1. Check theme có activate được không
# Truy cập WP Admin → Appearance → Themes → Activate xanhdesignbuild

# 2. Check PHP errors
# WP_DEBUG = true trong wp-config.php, check debug.log

# 3. Tailwind CLI build
cd wp-content/themes/xanhdesignbuild
npm run build
# Expected: output.css generated without errors
```

### Manual Verification (Browser)

1. **Phase A complete:**
   - Activate theme → trang không bị trắng
   - Header hiển thị: logo, navigation, CTA
   - Footer hiển thị: thông tin, links, copyright
   - DevTools Console: **0 JS errors**, **0 404 assets**
   - View source: `wp_head()` và `wp_footer()` có output

2. **Phase B — sau mỗi trang:**
   - Mở trang PHP trên browser
   - So sánh visual với wireframe HTML gốc (mở song song)
   - Check: layout đúng, fonts đúng, colors đúng
   - Check: GSAP animations chạy smooth
   - Check: Swiper sliders touch-enabled
   - Responsive: 375px → 768px → 1024px → 1440px

3. **Phase C complete:**
   - ACF: Thay đổi content trong admin → frontend cập nhật
   - Fluent Form: Submit thành công
   - PageSpeed Insights: Score > 90
   - Plugin Theme Check: Pass

---

## Tài Liệu Liên Quan

| File | Mô tả |
|---|---|
| [CONVERT_HTML_TO_WP.md](./CONVERT_HTML_TO_WP.md) | Lộ trình chuyển đổi HTML → WP |
| [ACF_FIELD_GROUPS.md](./ACF_FIELD_GROUPS.md) | Chi tiết ACF fields cho tất cả trang |
| [THEME_CONVENTIONS.md](./THEME_CONVENTIONS.md) | Quy ước code và cấu trúc theme |
| [../CORE_ARCHITECTURE.md](../CORE_ARCHITECTURE.md) | Kiến trúc tổng thể |
| [../CORE_DATA_MODEL.md](../CORE_DATA_MODEL.md) | CPTs, ACF fields, taxonomies |
| `.agent/rules/12-html-to-wp-conversion.md` | Quy tắc chuyển đổi |
| `.agent/workflows/html-to-wp-php.md` | Workflow 10 bước |
| `.agent/rules/00-project-core.md` | Core rules |
| `.agent/rules/01-wordpress-theme.md` | WP Theme rules |
