---
description: WordPress theme development rules for xanhdesignbuild. Apply when editing PHP templates, template-parts, or inc/ files.
globs: wp-content/themes/xanhdesignbuild/**/*.php
---

# WordPress Theme Rules

## Theme Architecture
- Template hierarchy: See `docs/CORE_ARCHITECTURE.md` §5
- Data flow: ACF → `inc/custom-functions.php` (helpers) → template-parts (presentation)
- NEVER put business logic in templates — use `xanh_get_*()` helper functions
- All templates use `get_template_part()` for reusable sections
- Extensibility: Use `do_action()` / `apply_filters()` — See `docs/ARCH_SCALABILITY.md`

## File Organization
```
template-parts/
  hero/         → Hero banners (per-page variants)
  content/      → Content cards (project, blog, testimonial, team, related)
  sections/     → Full-width sections (counter, process, CTA, FAQ, partners, 4xanh)
  components/   → UI components (slider, gallery, breadcrumb, skeleton, ToC, etc.)
  forms/        → Fluent Form wrappers
inc/
  theme-setup.php       → add_theme_support, menus, sidebars
  enqueue.php           → Conditional CSS/JS loading (vendor + custom)
  cpt-registration.php  → CPTs + taxonomies
  acf-fields.php        → ACF field group registration
  custom-functions.php  → xanh_get_*() data helpers
  ajax-handlers.php     → AJAX endpoints (filter, load more, search)
  template-tags.php     → Reusable template functions
  walker-nav.php        → Custom nav walker (nếu cần)
```

## PHP Coding Standards
- WordPress Coding Standards
- Prefix ALL functions: `xanh_`, ALL hooks: `xanh_`, ALL classes: `Xanh_`
- Escape output: `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses_post()`
- Sanitize input: `sanitize_text_field()`, `absint()`, `wp_unslash()`
- AJAX: ALWAYS verify nonces + check capabilities
- Clean code: SRP, early return, max 30 lines/function
- Textdomain: `xanh` | Min PHP: 7.4 | Min WP: 6.5
- Theme constants: `XANH_THEME_VERSION`, `XANH_THEME_DIR`, `XANH_THEME_URI`

## ACF Usage
- Read: `get_field('field_name', $post_id)` — NEVER direct meta queries
- Options: `get_field('field_name', 'option')` → hotline, email, address, social links
- Repeater: `have_rows()` + `the_row()` + `get_sub_field()`
- Relationship: `get_field('featured_projects')` → returns post objects array
- Image: `get_field('image')` → returns array with `['ID']`, `['url']`, `['alt']`
- Null-safe: `$value = get_field('x', $id) ?: 'default';`
- ALWAYS null-check before array access: `if ($image && isset($image['ID']))`
- Field groups: `docs/CORE_DATA_MODEL.md` + `docs/implement/ACF_FIELD_GROUPS.md`
- Options Page: `inc/acf-fields.php` registers "Cài Đặt XANH" menu

## Custom Post Types
| CPT | Slug | Archive | REST API |
|---|---|---|---|
| `xanh_project` | `/du-an/` | Yes | `show_in_rest: true` |
| `xanh_testimonial` | `/chung-thuc/` | No | `show_in_rest: true` |
| `xanh_team` | `/doi-ngu/` | No | `show_in_rest: false` |

## AJAX Pattern
```php
add_action('wp_ajax_xanh_{action}', 'xanh_ajax_{action}');
add_action('wp_ajax_nopriv_xanh_{action}', 'xanh_ajax_{action}');

function xanh_ajax_{action}() {
    check_ajax_referer('xanh_{action}_nonce', 'nonce');
    $type = sanitize_text_field(wp_unslash($_POST['type'] ?? ''));
    // ... query, render
    wp_send_json_success(['html' => $html, 'total' => $total, 'pages' => $pages]);
}
```

## Asset Enqueue Pattern (inc/enqueue.php)
```php
function xanh_enqueue_scripts() {
    $ver = XANH_THEME_VERSION;
    $uri = XANH_THEME_URI;

    // === CSS: Tailwind (compiled) + Custom ===
    wp_enqueue_style('xanh-tailwind', "$uri/assets/css/output.css", [], $ver);
    wp_enqueue_style('xanh-variables', "$uri/assets/css/variables.css", ['xanh-tailwind'], $ver);
    wp_enqueue_style('xanh-components', "$uri/assets/css/components.css", ['xanh-variables'], $ver);

    // === JS: Vendor CDN (global, defer, footer) ===
    // WP 6.5+ script strategy API: ['strategy' => 'defer', 'in_footer' => true]
    wp_enqueue_script('gsap', 'https://cdn.jsdelivr.net/npm/gsap@3.12.7/dist/gsap.min.js',
        [], '3.12.7', ['strategy' => 'defer', 'in_footer' => true]);
    wp_enqueue_script('gsap-st', 'https://cdn.jsdelivr.net/npm/gsap@3.12.7/dist/ScrollTrigger.min.js',
        ['gsap'], '3.12.7', ['strategy' => 'defer', 'in_footer' => true]);
    wp_enqueue_script('lenis', 'https://cdn.jsdelivr.net/npm/lenis@1.3.17/dist/lenis.min.js',
        [], '1.3.17', ['strategy' => 'defer', 'in_footer' => true]);
    wp_enqueue_script('lucide', 'https://unpkg.com/lucide@latest',
        [], null, ['strategy' => 'defer', 'in_footer' => true]);

    // === JS: Custom (global) ===
    wp_enqueue_script('xanh-main', "$uri/assets/js/main.js",
        ['gsap', 'gsap-st', 'lenis'], $ver, ['strategy' => 'defer', 'in_footer' => true]);

    // === JS: Conditional (Swiper + GLightbox via CDN) ===
    if (is_front_page() || is_singular('xanh_project')) {
        wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', [], null);
        wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
            [], '11', ['strategy' => 'defer', 'in_footer' => true]);
    }
    if (is_singular('xanh_project')) {
        wp_enqueue_style('glightbox-css', 'https://cdn.jsdelivr.net/npm/glightbox@3/dist/css/glightbox.min.css', [], null);
        wp_enqueue_script('glightbox', 'https://cdn.jsdelivr.net/npm/glightbox@3/dist/glightbox.min.js',
            [], '3', ['strategy' => 'defer', 'in_footer' => true]);
    }
    if (is_post_type_archive('xanh_project') || is_home()) {
        wp_enqueue_script('xanh-filter', "$uri/assets/js/filter.js",
            ['xanh-main'], $ver, ['strategy' => 'defer', 'in_footer' => true]);
        wp_localize_script('xanh-filter', 'xanhAjax', [
            'url'   => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('xanh_filter_nonce'),
        ]);
    }
}
add_action('wp_enqueue_scripts', 'xanh_enqueue_scripts');
```
> **Note:** Xem thêm `rules/17-wp-optimization.md` cho WP bloat removal + dequeue block styles.

## Custom Hooks (Extensibility)
```php
// Actions — hook into these from plugins
do_action('xanh_before_hero', $page_id);
do_action('xanh_after_hero', $page_id);
do_action('xanh_before_content');
do_action('xanh_after_content');
do_action('xanh_after_form_submit', $form_id, $data);

// Filters — modify behavior from plugins
$columns = apply_filters('xanh_portfolio_columns', 3);
$per_page = apply_filters('xanh_portfolio_per_page', 9);
$cta_text = apply_filters('xanh_default_cta_text', 'Đặt Lịch Tư Vấn Riêng');
```

Full extensibility guide: `docs/ARCH_SCALABILITY.md`
