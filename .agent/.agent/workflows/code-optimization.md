---
description: Tối ưu code CSS, JS, PHP cho WordPress theme. Dùng sau khi hoàn thành chuyển đổi HTML→WP hoặc khi cần refactor code.
---

# Tối Ưu Code — XANH Theme

## Skills cần đọc trước
- `@clean-code` — Principles từ Clean Code (Robert C. Martin)
- `@code-simplifier` — Simplify + refine code
- `@php-pro` — PHP generators, iterators, modern OOP
- `@javascript-pro` — ES6+, async patterns
- `@frontend-design` — Đảm bảo UI không bị ảnh hưởng

## Rules BẮT BUỘC
- `09-css-optimization.md` — **Đọc toàn bộ 7 sections** trước khi review CSS
- `10-js-optimization.md` — **Đọc toàn bộ** trước khi review JS
- `00-project-core.md` — Critical rule #7: SRP, early return, max 30 lines/function

---

## Bước 1: Chọn Scope Tối Ưu

| Scope | Khi nào | Files |
|---|---|---|
| **Single page** | Vừa xong chuyển đổi 1 trang | `page-*.php` + CSS + JS tương ứng |
| **Component** | Refactor shared component | `template-parts/` + `components.css` |
| **Global** | Pre-launch review | Toàn bộ theme |

---

## Bước 2: CSS Optimization (rule `09`)

### 2.1 — Kiểm Tra Rendering Performance
- [ ] Chỉ animate `transform`, `opacity`, `filter` (KHÔNG animate `width`, `height`, `margin`)
- [ ] `will-change` chỉ trên phần tử animate, ≤ 15 cùng lúc
- [ ] `contain: layout style` trên sections độc lập
- [ ] Không có `transition: all` → list rõ thuộc tính

### 2.2 — Kiểm Tra Selector Efficiency
- [ ] Specificity ≤ `0,2,0` (tối đa 2 classes)
- [ ] Không nesting > 3 cấp
- [ ] Dùng BEM: `.block__element--modifier`
- [ ] Không có tag selector thừa (`div.card` → `.card`)
- [ ] Không có `!important` (trừ `prefers-reduced-motion`)

### 2.3 — Kiểm Tra DRY & Shorthand
- [ ] Tất cả colors dùng `var(--token)`, KHÔNG hardcode `#14513D`
- [ ] Spacing dùng tokens `var(--space-*)`, KHÔNG hardcode px
- [ ] Fonts dùng `var(--font-*)`, `var(--text-*)`
- [ ] Shorthand: `inset`, `margin-inline`, `gap`
- [ ] Font-size: `rem`/`clamp()`, KHÔNG hardcode `px`
- [ ] Kiểm tra trùng lặp với `08-cross-section-consistency.md` shared classes

### 2.4 — Kiểm Tra Responsive
- [ ] Mobile-first: base = mobile, `min-width` mở rộng
- [ ] Breakpoints chuẩn: 640/768/1024/1280/1440
- [ ] Media queries gom theo component, KHÔNG 1 block cuối file
- [ ] Không mix `min-width` và `max-width`

### 2.5 — File Organization
- [ ] `:root` tokens chỉ khai báo 1 lần trong `variables.css`
- [ ] Thuộc tính theo thứ tự: Layout → Box Model → Typography → Visual → Effects
- [ ] Comment sections rõ ràng

---

## Bước 3: JavaScript Optimization (rule `10`)

### 3.1 — Architecture Check
- [ ] Guard clause ở đầu function cho DOM elements
- [ ] Optional chaining (`?.`) cho property access
- [ ] Event delegation cho dynamic content (AJAX)
- [ ] No global variables (module pattern hoặc IIFE)

### 3.2 — Performance Check
- [ ] Debounce cho scroll/resize events (≥ 100ms)
- [ ] `requestAnimationFrame` cho visual updates
- [ ] Intersection Observer thay vì scroll listener
- [ ] No `querySelectorAll` trong loops

### 3.3 — GSAP Check
- [ ] `ScrollTrigger.refresh()` sau dynamic content load
- [ ] Stagger: 0.1s cho luxury cascading
- [ ] `once: true` cho entrance animations
- [ ] `markers: false` trong production
- [ ] Kill ScrollTriggers khi không cần: `ScrollTrigger.getAll().forEach(t => t.kill())`

### 3.4 — Dependency & Loading
- [ ] Dependency chain đúng: GSAP → ScrollTrigger → Page JS
- [ ] Conditional loading: Swiper chỉ Home + Portfolio Detail
- [ ] Conditional loading: GLightbox chỉ Portfolio Detail
- [ ] Tất cả scripts: `defer` + `in_footer: true`
- [ ] Custom JS ≤ 12KB gzip per page

### 3.5 — Error Prevention
- [ ] `DOMContentLoaded` hoặc `defer` cho DOM manipulation
- [ ] `try/catch` cho third-party library init
- [ ] `wp_localize_script` cho PHP→JS data (Ajax URL, nonce)

---

## Bước 4: PHP Optimization

### 4.1 — Clean Code (rule `00` #7)
- [ ] Functions ≤ 30 lines (SRP)
- [ ] Early return pattern (guard clause)
- [ ] Descriptive naming: `xanh_get_featured_projects()`, KHÔNG `getData()`
- [ ] Prefix `xanh_` cho tất cả functions
- [ ] `defined('ABSPATH') || exit;` ở đầu `inc/` files

### 4.2 — Security (rule `05`)
- [ ] ALL output escaped: `esc_html()`, `esc_url()`, `esc_attr()`, `wp_kses_post()`
- [ ] ALL ACF fields null-checked trước khi dùng
- [ ] `wp_reset_postdata()` sau mỗi `WP_Query` custom
- [ ] AJAX handlers: `check_ajax_referer()` + capability check
- [ ] No `query_posts()` — dùng `WP_Query`
- [ ] No `eval()`, `base64_decode()`

### 4.3 — Performance (rule `17`)
- [ ] WP bloat đã remove (emoji, oEmbed, meta tags)
- [ ] Block styles dequeued (classic theme)
- [ ] Heartbeat: 60s admin, disabled frontend
- [ ] Transient caching cho heavy queries
- [ ] Third-party scripts delayed (Zalo: 3s)

### 4.4 — Template Organization
- [ ] Sections tách thành `template-parts/` (SRP)
- [ ] `get_template_part()` cho reusable components
- [ ] `do_action()` + `apply_filters()` tại integration points
- [ ] No inline `<script>` hoặc `<style>` — dùng enqueue

---

## Bước 5: Rebuild & Verify

// turbo
```bash
# Rebuild Tailwind sau khi optimize CSS
cd wp-content/themes/xanhdesignbuild && npm run build
```

**Checklist verify:**
- [ ] CSS output size giảm sau purge
- [ ] JS không có lỗi console
- [ ] Visual diff: so sánh trước/sau optimize (layout, animation, responsive)
- [ ] PageSpeed score: check trước/sau

---

## Tài Liệu Tham Chiếu

| File | Nội dung |
|---|---|
| `.agent/rules/09-css-optimization.md` | CSS rendering, selector, DRY, responsive |
| `.agent/rules/10-js-optimization.md` | JS architecture, GSAP, dependency |
| `.agent/rules/08-cross-section-consistency.md` | Shared UI classes |
| `.agent/rules/05-security.md` | Escape + sanitize |
| `.agent/rules/17-wp-optimization.md` | WP bloat removal |
| `docs/ARCH_DESIGN_TOKENS.md` | Full token reference |
