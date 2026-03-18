---
description: Wireframe CSS architecture rules — file structure, base.css shared components, override patterns, and page-specific styling. Apply when creating or editing CSS in wireframes/.
globs: wireframes/**/*.css
---

# Wireframe CSS Architecture Rules ★★

> XANH wireframe CSS architecture: base.css shared layer + page-specific overrides.
> Tham chiếu: `docs/ARCH_CSS_FILES.md`

---

## 1. File Structure — BẮT BUỘC

```
wireframes/
├── _shared/base.css        ← Shared foundation (ALL pages load this FIRST)
├── about/about.css         ← Page-specific only
├── blog/blog.css           ← Blog list
├── blog/blog-detail.css    ← Blog detail (depends on blog.css + base.css)
├── contact/contact.css     ← Contact page
├── homepage_02/home-page.css ← Homepage
├── portfolio/portfolio.css   ← Portfolio grid
└── portfolio/portfolio-detail.css ← Portfolio detail
```

### Load Order (mỗi page HTML)
```html
<!-- 1. ALWAYS load base first -->
<link rel="stylesheet" href="../_shared/base.css">
<!-- 2. Page CSS second — overrides base -->
<link rel="stylesheet" href="about.css">
```

> blog-detail.html loads **3 files**: base.css → blog.css → blog-detail.css

---

## 2. base.css — Shared Components ★ CRITICAL

Các components sau đã có trong `base.css`. **KHÔNG ĐƯỢC khai báo lại** trong page CSS:

| Component | Class | Ghi chú |
|-----------|-------|---------|
| Button base | `.btn` | Override chỉ qua page modifier |
| Button Primary | `.btn--primary` + `::after` shimmer | TOÀN BỘ effect trong base |
| Button Outline | `.btn--outline` + `::before` sweep | TOÀN BỘ effect trong base |
| Button Ghost | `.btn--ghost` | TOÀN BỘ trong base |
| Section Header | `.section-header`, `.section-eyebrow`, `.section-title`, `.section-subtitle` | Override qua page CSS |
| Entrance Anim | `.anim-fade-up`, `.anim-fade-left`, `.anim-fade-right`, `.anim-scale-in` | Trigger: `.is-visible` |
| Header Scroll | `#site-header.is-scrolled` | JS adds class |
| Text Blocks | `.text-lead`, `.text-body`, `.text-quote` | Override qua page CSS |
| Icon Circle | `.icon-circle` | `.group:hover` triggers |
| Container | `.site-container` | Responsive padding |

### Trước khi viết CSS mới, LUÔN kiểm tra:
1. Component/class đã có trong `base.css` chưa?
2. Nếu CÓ → chỉ override property cần thay đổi, KHÔNG copy toàn bộ
3. Nếu CHƯA có, và dùng ≥ 2 pages → thêm vào `base.css`
4. Nếu CHƯA có, và chỉ 1 page → viết trong page CSS

---

## 3. Override Pattern — Cách Đúng/Sai

```css
/* ✅ ĐÚNG — chỉ thêm/override property cần thiết */
.btn { gap: 0.75rem; border-radius: 2px; }
.section-eyebrow { margin-bottom: 1rem; color: rgb(20 81 61 / 0.5); }

/* ✅ ĐÚNG — responsive override */
@media (min-width: 768px) {
  .btn { font-size: 1rem; }
}

/* ❌ SAI — khai báo lại toàn bộ component đã có trong base */
.btn--outline {
  position: relative;
  overflow: hidden;
  color: var(--color-primary);
  border: 2px solid var(--color-primary);
  /* ... 40 lines copy từ base.css ... */
}

/* ❌ SAI — duplicate animation đã có trong base */
.anim-fade-up {
  opacity: 0;
  transform: translateY(40px);
  transition: opacity 0.6s ease, transform 0.6s ease;
}
```

---

## 4. Khi Thêm Component Mới

### 4.1 — Dùng chung (≥ 2 pages) → base.css
```css
/* Thêm vào base.css, KHÔNG thêm vào page CSS */
.new-shared-component { ... }
```

### 4.2 — Dùng riêng (1 page) → page CSS
```css
/* Viết trong page-specific CSS file */
.about-specific-widget { ... }
```

### 4.3 — Page-specific tokens
```css
/* ✅ OK — tokens chỉ dùng trong 1 page */
:root {
  --color-success: #10b981;
  --z-modal: 9000;
}

/* ❌ SAI — khai báo lại token đã có trong base.css */
:root {
  --color-primary: #14513D;  /* ĐÃ CÓ trong base! */
}
```

---

## 5. Transition & Animation Rules

```css
/* ✅ List rõ từng property */
.card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
.footer-link { transition: color var(--transition-base), opacity var(--transition-base); }

/* ❌ KHÔNG BAO GIỜ dùng transition: all */
.card { transition: all 0.3s ease; }
```

### will-change
```css
/* ✅ Chỉ dùng cho GSAP initial-state (JS quản lý lifecycle) */
.hero-headline { opacity: 0; transform: translateY(20px); will-change: opacity, transform; }

/* ❌ KHÔNG đặt will-change trên static image/card elements */
.card__image img { will-change: transform; }  /* → GPU RAM waste */
```

---

## 6. State Classes (nhất quán)

| Class | Trigger | Mô tả |
|-------|---------|-------|
| `.is-visible` | IntersectionObserver | Element vào viewport → animate in |
| `.is-scrolled` | Scroll > threshold | Header background transition |
| `.is-active` | Click/Toggle | Active tab, accordion open |
| `.is-loading` | AJAX | Loading state |
| `.is-scroll-locked` | Mobile menu | `overflow: hidden` on body |

---

## 7. Naming Convention

- **BEM:** `.block__element--modifier`
- **Page prefix:** `.about-hero`, `.blog-search`, `.contact-form`
- **Section comment:**
  ```css
  /* =============================================
     SECTION NAME — Description
     ============================================= */

  /* ── Sub-section ── */
  ```

---

## 8. Duplicate Prevention Checklist ✓

Trước khi viết CSS mới:

- [ ] Kiểm tra `base.css` — component/class đã tồn tại?
- [ ] Kiểm tra page CSS khác — cùng pattern đã viết ở file nào chưa?
- [ ] Nếu pattern dùng ≥ 2 files → gộp vào `base.css`
- [ ] Không copy `.btn--outline` sweep, `.anim-fade-*`, entrance animations
- [ ] Không khai báo lại `:root` tokens đã có
- [ ] Không dùng `transition: all` — list rõ properties
- [ ] Không đặt `will-change` trên static elements

---

## 9. WordPress Migration Enqueue

```php
// Conditional loading — chỉ load CSS cần thiết cho từng page
wp_enqueue_style('xanh-base', '...base.css');

if (is_front_page()) {
    wp_enqueue_style('xanh-home', '...home-page.css', ['xanh-base']);
} elseif (is_page('gioi-thieu')) {
    wp_enqueue_style('xanh-about', '...about.css', ['xanh-base']);
} elseif (is_singular('xanh_project')) {
    wp_enqueue_style('xanh-portfolio-detail', '...portfolio-detail.css', ['xanh-base']);
}
```

---

## Tài Liệu Liên Quan

- `docs/ARCH_CSS_FILES.md` — Chi tiết từng file, dependency map, dung lượng
- `09-css-optimization.md` — Rendering performance, selector efficiency
- `08-cross-section-consistency.md` — Shared UI classes
- `docs/ARCH_DESIGN_TOKENS.md` — Full token reference
