# Theme Conventions — `xanhdesignbuild`

> **Ngày tạo:** 2026-03-18
> **Tham chiếu:** [../GOV_CODING_STANDARDS.md](../GOV_CODING_STANDARDS.md) | [../../.agent/rules/01-wordpress-theme.md](../../.agent/rules/01-wordpress-theme.md)

---

## 1. Theme Structure

```
wp-content/themes/xanhdesignbuild/
├── style.css                    ← Theme header (bắt buộc)
├── functions.php                ← Constants + require inc/*.php
├── index.php                    ← Fallback (bắt buộc)
├── screenshot.png               ← Theme thumbnail
│
├── front-page.php               ← Homepage
├── page-about.php               ← Giới Thiệu (slug: gioi-thieu)
├── page-contact.php             ← Liên Hệ (slug: lien-he)
├── archive.php                  ← Blog listing
├── single.php                   ← Blog detail
├── archive-xanh_project.php     ← Portfolio grid
├── single-xanh_project.php      ← Portfolio detail
├── 404.php                      ← Not Found
├── header.php / footer.php      ← Global
│
├── template-parts/
│   ├── hero/                    ← Hero banners
│   ├── content/                 ← Cards (project, blog, testimonial, team)
│   ├── sections/                ← Full-width sections
│   ├── components/              ← UI components (slider, gallery, breadcrumb)
│   └── forms/                   ← Fluent Form wrappers
│
├── inc/
│   ├── theme-setup.php          ← add_theme_support, menus
│   ├── enqueue.php              ← Conditional CSS/JS loading
│   ├── cpt-registration.php     ← CPTs + taxonomies
│   ├── acf-fields.php           ← ACF Options Page + field helpers
│   ├── custom-functions.php     ← xanh_get_*() data helpers
│   ├── ajax-handlers.php        ← AJAX endpoints
│   └── template-tags.php        ← Reusable template functions
│
├── assets/
│   ├── css/
│   │   ├── input.css            ← Tailwind directives
│   │   ├── output.css           ← CLI-generated (DO NOT EDIT)
│   │   ├── variables.css        ← Brand tokens (CSS custom properties)
│   │   └── components.css       ← Custom component styles
│   ├── js/
│   │   ├── main.js              ← Lenis, GSAP global, scroll reveal
│   │   ├── animations.js        ← GSAP timelines, counters
│   │   ├── slider.js            ← Swiper init
│   │   ├── gallery.js           ← GLightbox init
│   │   ├── filter.js            ← AJAX filtering
│   │   └── forms.js             ← Form UX
│   ├── fonts/Inter/             ← Self-hosted variable font
│   └── images/                  ← Logo SVGs only
│
├── tailwind.config.js           ← Tailwind CLI config
├── package.json                 ← npm scripts (dev/build)
└── languages/                   ← i18n
```

---

## 2. Naming Conventions

### PHP

| Element | Pattern | Example |
|---|---|---|
| Functions | `xanh_` prefix | `xanh_get_project_stats()` |
| Hooks (actions) | `xanh_` prefix | `do_action('xanh_before_hero')` |
| Hooks (filters) | `xanh_` prefix | `apply_filters('xanh_portfolio_columns', 3)` |
| Classes | `Xanh_` prefix | `class Xanh_Walker_Nav` |
| Constants | `XANH_THEME_` prefix | `XANH_THEME_VERSION` |
| Textdomain | `xanh` | `__('Text', 'xanh')` |

### CSS

| Element | Pattern | Example |
|---|---|---|
| BEM Block | `.block-name` | `.service-card` |
| BEM Element | `.block__element` | `.service-card__title` |
| BEM Modifier | `.block--modifier` | `.service-card--featured` |
| CSS Variables | `--color-*`, `--space-*` | `--color-primary` |
| Animation classes | `.anim-*` | `.anim-fade-up` |
| State classes | `.is-*` | `.is-scrolled`, `.is-active` |

### JS

| Element | Pattern | Example |
|---|---|---|
| Module objects | `Xanh*` | `XanhFilter`, `XanhObservers` |
| DOM IDs | kebab-case | `#site-header`, `#ba-slider` |
| Data attributes | `data-*` | `data-counter`, `data-step` |
| Events | camelCase | `onFilterChange` |

---

## 3. Tailwind CLI Build

```json
// package.json
{
  "name": "xanhdesignbuild",
  "scripts": {
    "dev": "npx @tailwindcss/cli -i ./assets/css/input.css -o ./assets/css/output.css --watch",
    "build": "npx @tailwindcss/cli -i ./assets/css/input.css -o ./assets/css/output.css --minify"
  }
}
```

```js
// tailwind.config.js
module.exports = {
  content: [
    './*.php',
    './template-parts/**/*.php',
    './inc/**/*.php',
    './assets/js/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        primary: '#14513D',
        accent: '#FF8A00',
        light: '#F3F4F6',
        beige: '#D8C7A3',
        dark: '#1A1A1A',
      },
      fontFamily: {
        heading: ['Inter', '-apple-system', 'sans-serif'],
        body: ['Inter', '-apple-system', 'sans-serif'],
      },
      screens: {
        sm: '640px',
        md: '768px',
        lg: '1024px',
        xl: '1280px',
        '2xl': '1440px',
      },
    },
  },
};
```

---

## 4. Enqueue Pattern

```php
// inc/enqueue.php
function xanh_enqueue_scripts() {
    $ver = XANH_THEME_VERSION;
    $uri = XANH_THEME_URI;

    // === CSS: Tailwind (compiled) + Custom ===
    wp_enqueue_style('xanh-tailwind', "$uri/assets/css/output.css", [], $ver);
    wp_enqueue_style('xanh-variables', "$uri/assets/css/variables.css", ['xanh-tailwind'], $ver);
    wp_enqueue_style('xanh-components', "$uri/assets/css/components.css", ['xanh-variables'], $ver);

    // === JS: Vendor CDN (global) ===
    wp_enqueue_script('gsap', 'https://cdn.jsdelivr.net/npm/gsap@3.12.7/dist/gsap.min.js', [], '3.12.7', true);
    wp_enqueue_script('gsap-st', 'https://cdn.jsdelivr.net/npm/gsap@3.12.7/dist/ScrollTrigger.min.js', ['gsap'], '3.12.7', true);
    wp_enqueue_script('lenis', 'https://cdn.jsdelivr.net/npm/lenis@1.3.17/dist/lenis.min.js', [], '1.3.17', true);
    wp_enqueue_script('lucide', 'https://unpkg.com/lucide@latest', [], null, true);

    // === JS: Custom (global) ===
    wp_enqueue_script('xanh-main', "$uri/assets/js/main.js", ['gsap', 'gsap-st', 'lenis'], $ver, true);

    // === Conditional: Swiper ===
    if (is_front_page() || is_singular('xanh_project')) {
        wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', [], null);
        wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', [], '11', true);
    }

    // === Conditional: GLightbox ===
    if (is_singular('xanh_project')) {
        wp_enqueue_style('glightbox-css', 'https://cdn.jsdelivr.net/npm/glightbox@3/dist/css/glightbox.min.css', [], null);
        wp_enqueue_script('glightbox', 'https://cdn.jsdelivr.net/npm/glightbox@3/dist/glightbox.min.js', [], '3', true);
    }

    // === Conditional: AJAX Filter ===
    if (is_post_type_archive('xanh_project') || is_home()) {
        wp_enqueue_script('xanh-filter', "$uri/assets/js/filter.js", ['xanh-main'], $ver, true);
        wp_localize_script('xanh-filter', 'xanhAjax', [
            'url'   => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('xanh_filter_nonce'),
        ]);
    }
}
add_action('wp_enqueue_scripts', 'xanh_enqueue_scripts');
```

---

## 5. Security Checklist

| Rule | Implementation |
|---|---|
| Escape URLs | `esc_url()` |
| Escape attributes | `esc_attr()` |
| Escape text | `esc_html()` |
| Rich HTML | `wp_kses_post()` |
| Sanitize input | `sanitize_text_field()`, `absint()` |
| AJAX nonce | `check_ajax_referer()` |
| Capability check | `current_user_can()` |
| No `eval()` or `base64_decode()` | |
| Form nonce | `wp_nonce_field()` |

---

## 6. Data Flow

```
ACF Admin UI → wp_postmeta / wp_options
                    ↓
         inc/custom-functions.php (helpers)
                    ↓
           template-parts/*.php (render)
```

**Rule:** NEVER put business logic in templates. Use `xanh_get_*()` helpers.

---

## 7. Custom Post Types

| CPT | Slug | Archive | Taxonomies |
|---|---|---|---|
| `xanh_project` | `/du-an/` | Yes | `project_type`, `project_status` |
| `xanh_testimonial` | `/chung-thuc/` | No | — |
| `xanh_team` | `/doi-ngu/` | No | — |

---

## Tài Liệu Liên Quan

| File | Mô tả |
|---|---|
| [CONVERT_HTML_TO_WP.md](./CONVERT_HTML_TO_WP.md) | Lộ trình chuyển đổi |
| [ACF_FIELD_GROUPS.md](./ACF_FIELD_GROUPS.md) | Chi tiết ACF fields |
| [plan.md](./plan.md) | Implementation plan gốc |
| [../CORE_ARCHITECTURE.md](../CORE_ARCHITECTURE.md) | Kiến trúc tổng thể |
| [../CORE_DATA_MODEL.md](../CORE_DATA_MODEL.md) | Data model |
| [../GOV_CODING_STANDARDS.md](../GOV_CODING_STANDARDS.md) | Coding standards |
