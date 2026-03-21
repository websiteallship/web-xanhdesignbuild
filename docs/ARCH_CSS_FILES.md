# ARCH_CSS_FILES — Kiến Trúc CSS Wireframes

> **Dự án:** Website XANH - Design & Build
> **Phiên bản:** 1.0 | **Cập nhật:** 2026-03-18
> **Tham chiếu:** [ARCH_DESIGN_TOKENS.md](./ARCH_DESIGN_TOKENS.md) | [GOV_CODING_STANDARDS.md](./GOV_CODING_STANDARDS.md)

---

## 0. Tổng Quan Kiến Trúc

```
wireframes/
├── _shared/
│   └── base.css          ← Layer 0: Shared foundation (tất cả page load file này)
├── about/
│   └── about.css         ← Page-specific overrides & components
├── blog/
│   ├── blog.css          ← Blog list page
│   └── blog-detail.css   ← Blog single post (kế thừa blog.css)
├── contact/
│   └── contact.css       ← Contact page
├── homepage_02/
│   └── home-page.css     ← Homepage (file lớn nhất)
└── portfolio/
    ├── portfolio.css     ← Portfolio grid page
    └── portfolio-detail.css ← Portfolio single project
```

### CSS Load Order (mỗi page)

```html
<!-- 1. Shared base — LUÔN load trước -->
<link rel="stylesheet" href="../_shared/base.css">

<!-- 2. Page-specific — load sau, override base -->
<link rel="stylesheet" href="about.css">
```

> **Nguyên tắc:** `base.css` chứa mọi thứ dùng chung. Page CSS chỉ chứa styles **riêng** của page đó. Không được khai báo lại rules đã có trong `base.css`.

---

## 1. base.css — Shared Foundation

**File:** `wireframes/_shared/base.css` | **~510 lines** | **~11KB**

### Nội dung

| Section | Lines | Mô tả |
|---------|-------|-------|
| **1. Design Tokens** | `:root` | Colors, spacing, easing, durations, transitions, typography, card tokens |
| **2. Base Reset** | `*, html, body, img, a` | Box-sizing, font smoothing, overflow-x |
| **3. Reduced Motion** | `@media (prefers-reduced-motion)` | Tắt animation cho accessibility |
| **4. Site Container** | `.site-container` | Max-width 1280px, padding responsive |
| **5. Section Header** | `.section-header`, `.section-eyebrow`, `.section-title`, `.section-subtitle` | Header pattern dùng chung |
| **6. Buttons** | `.btn`, `.btn--primary`, `.btn--outline`, `.btn--ghost` | Tất cả button variants |
| **7. Shimmer Keyframes** | `@keyframes ctaShimmer`, `lightSweep` | Animation cho buttons |
| **8. Header Scroll** | `#site-header.is-scrolled` | Header transparent → white on scroll |
| **9. Text Blocks** | `.text-lead`, `.text-body`, `.text-quote` | Shared typography classes |
| **10. Icon Circle** | `.icon-circle` | Icon container dùng chung |
| **11. Entrance Animations** | `.anim-fade-up`, `.anim-fade-left`, `.anim-fade-right`, `.anim-scale-in` | IntersectionObserver animations |
| **12. Utilities** | `.is-scroll-locked`, `.drawer-link--*` | Mobile menu helpers |

### Button System (chi tiết)

```css
/* Base button — tất cả buttons kế thừa */
.btn { display: inline-flex; align-items: center; gap: 0.5rem; ... }

/* Primary (Orange) — CTA chính, có shimmer animation */
.btn--primary { background: var(--color-accent); }
.btn--primary::after { /* shimmer sweep animation */ }

/* Outline (Green) — CTA phụ, sweep-fill hover effect */
.btn--outline { color: var(--color-primary); border-color: var(--color-primary); }
.btn--outline::before { /* sweep background từ trái → phải on hover */ }
.btn--outline:hover svg { transform: translateY(3px); }

/* Ghost (White border) — dùng trên dark backgrounds */
.btn--ghost { color: var(--color-white); border-color: rgba(255,255,255,0.3); }
```

### Entrance Animations

```css
/* Thêm class vào HTML, JS sẽ add .is-visible khi IntersectionObserver trigger */
.anim-fade-up       → translateY(40px) → translateY(0)
.anim-fade-left     → translateX(-40px) → translateX(0)
.anim-fade-right    → translateX(40px) → translateX(0)
.anim-scale-in      → scale(0.95) → scale(1)
```

### Design Tokens — Quick Reference

| Token | Giá trị | Dùng cho |
|-------|---------|----------|
| `--color-primary` | `#14513D` | Xanh đậm chủ đạo |
| `--color-accent` | `#FF8A00` | CTA buttons, điểm nhấn |
| `--color-beige` | `#D8C7A3` | Warm neutral sections |
| `--ease-smooth` | `cubic-bezier(0.22, 1, 0.36, 1)` | Blog/portfolio animations |
| `--ease-luxury` | `cubic-bezier(0.16, 1, 0.3, 1)` | Entrance, fade animations |
| `--ease-out` | `cubic-bezier(0.25, 0.46, 0.45, 0.94)` | Homepage/about animations |
| `--duration-base` | `0.3s` | Hover states |
| `--transition-base` | `300ms ease` | General transitions |

---

## 2. Page CSS Files — Chi Tiết

### 2.1 about.css

**File:** `wireframes/about/about.css` | **~958 lines** | **~21KB**

| Section | Component | Mô tả |
|---------|-----------|-------|
| **Hero** | `.about-hero` | Full-height hero, entrance animations (`.about-hero-el`) |
| **Empathy** | `.empathy-*` | "Mọi Điểm Chạm" section — counter strip, cards |
| **Turning Point** | `.turn-*` | Circular infographic SVG — center overlay, node hover |
| **Philosophy** | `.philo-card` | 4 Xanh philosophy cards — glassmorphism, flip effect |
| **Core Values** | `.cv-card` | Hover cards — shimmer sweep, icon pulse animation |

**Page-specific overrides:**
```css
.btn { gap: 0.75rem; border-radius: 2px; }  /* About btn spacing */
.icon-circle { width: 3rem; height: 3rem; }  /* About icon sizing */
```

**Keyframes riêng:** `cvIconPulse`

---

### 2.2 blog.css

**File:** `wireframes/blog/blog.css` | **~1249 lines** | **~32KB**

| Section | Component | Mô tả |
|---------|-----------|-------|
| **Hero** | `.blog-hero` | 90svh mobile, 85vh desktop, entrance animations |
| **Search** | `.blog-search` | Glassmorphism search bar, animated placeholder |
| **Category Tabs** | `.category-tab` | Horizontal scrollable filter tabs |
| **Featured** | `.featured-card` | Large + small featured article cards |
| **Article Grid** | `.article-card` | Article cards grid — 1/2/3 columns responsive |
| **Load More** | `.article-grid__load-more` | Outline button, sweep hover effect |
| **Lead Magnet** | `.lead-magnet` | Email capture form + 3D book visual |

**Page-specific tokens:** không
**Đặc biệt:** Blog-detail.css kế thừa blog.css (load cả 2)

---

### 2.3 blog-detail.css

**File:** `wireframes/blog/blog-detail.css` | **~680 lines** | **~14KB**

| Section | Component | Mô tả |
|---------|-----------|-------|
| **Reading Progress** | `.reading-progress` | Fixed top progress bar |
| **Breadcrumb** | `.breadcrumb--blog` | Navigation breadcrumb |
| **Article** | `.article-detail` | Main content area, typography |
| **Sidebar** | `.sidebar` | Sticky sidebar, table of contents |
| **Related** | `.related-*` | Related articles section |

**Phụ thuộc:** Cần load `blog.css` trước

---

### 2.4 contact.css

**File:** `wireframes/contact/contact.css` | **~744 lines** | **~19KB**

| Section | Component | Mô tả |
|---------|-----------|-------|
| **Hero** | `.contact-hero` | Background image hero, entrance animations |
| **Form** | `.form-field__*` | Contact form — input, select, textarea, validation states |
| **Info** | `.contact-info-*` | Contact info cards (phone, email, address) |
| **FAQ** | `.faq-*` | Accordion FAQ section |
| **Counter** | `.counter-strip` | Stats counter strip |

**Page-specific tokens:**
```css
:root { --color-success: #10b981; --color-error: #ef4444; }
```

**Success state:** `.btn--success` cho form submit

---

### 2.5 home-page.css

**File:** `wireframes/homepage_02/home-page.css` | **~3326 lines** | **~69KB**

> ⚠️ **File lớn nhất** — chứa tất cả components của trang chủ

| Section | Component | Mô tả |
|---------|-----------|-------|
| **Layout** | `.section`, `.site-container--full` | Section spacing, container |
| **Overrides** | `.section-eyebrow`, `.section-title`, `.section-subtitle` | Homepage-specific typography |
| **Cards** | `.card`, `.tag` | Card base, tag/badge variants |
| **Hero** | `#hero`, `.hero-swiper` | Swiper hero slider, pagination, entrance |
| **Marquee** | `.marquee` | Scrolling text divider |
| **Vision** | `.vision-*` | "Vì Sao" section — text + image |
| **Services** | `.service-card` | Services grid — image + content cards |
| **Projects** | `.projects-*`, `.ba-slider` | Project thumbs, Before/After slider |
| **CTA** | `.cta-section`, `.cta-contact-section` | Call-to-action sections |
| **Before/After** | `.ba-info`, `.ba-slider__*` | Before/After comparison slider |
| **Process** | `.process-*` | 6-step process timeline |
| **Testimonials** | `.testi-*` | Testimonials Swiper carousel |
| **4 Xanh** | `.xanh4-*` | Philosophy section |
| **Partners** | `.partner-*` | Logo carousel |
| **Blog** | `.home-blog-*` | Blog preview cards |
| **Counter** | `.counter-*` | Animated stats counter |
| **Footer** | `.footer-*` | Global footer |

**Page-specific tokens:**
```css
:root { --border-focus: var(--color-primary); }
```

---

### 2.6 portfolio.css

**File:** `wireframes/portfolio/portfolio.css` | **~504 lines** | **~13KB**

| Section | Component | Mô tả |
|---------|-----------|-------|
| **Hero** | `.portfolio-hero` | 85svh hero, entrance animations |
| **Filter** | `.filter-bar`, `.filter-tab` | Sticky filter tabs + count badges |
| **Grid** | `.project-card` | Project cards with image hover zoom |
| **Pagination** | `.portfolio-pagination` | Pagination controls |

**Page-specific tokens:**
```css
:root { --color-success: rgb(16, 185, 129); --color-warning: rgb(245, 158, 11); }
```

---

### 2.7 portfolio-detail.css

**File:** `wireframes/portfolio/portfolio-detail.css` | **~2826 lines** | **~62KB**

> ⚠️ **File lớn thứ 2** — chứa nhiều interactive components

| Section | Component | Mô tả |
|---------|-----------|-------|
| **Hero** | `.detail-hero` | Full-width hero image + breadcrumb |
| **Overview** | `.overview-*` | Project overview section — stats, description |
| **Comparison** | `.ba-custom-slider` | Clip-path Before/After slider (custom) |
| **Gallery** | `.gallery-*` | Masonry-style photo gallery + lightbox |
| **Lightbox** | `.ba-lightbox` | Fullscreen BA slider lightbox |
| **Related** | `.related-*` | Related projects section |
| **CTA** | `.cta-*` | Call-to-action final section |

**Page-specific tokens:**
```css
:root {
  --color-beige-warm: #F8F6F4;
  --color-success: #22C55E;
  --z-header: 50; --z-drawer: 999; --z-overlay: 998; --z-modal: 9000;
}
```

---

## 3. Bảng Dung Lượng

| File | Lines | Size | Tỷ lệ |
|------|------:|-----:|-------:|
| base.css | 510 | 11KB | 5% |
| about.css | 958 | 21KB | 9% |
| blog-detail.css | 680 | 14KB | 6% |
| blog.css | 1,249 | 32KB | 13% |
| contact.css | 744 | 19KB | 8% |
| home-page.css | 3,326 | 69KB | 29% |
| portfolio-detail.css | 2,826 | 62KB | 26% |
| portfolio.css | 504 | 13KB | 5% |
| **Tổng** | **10,797** | **241KB** | **100%** |

### Sau khi minify (ước tính)

| Giai đoạn | Size | Giảm |
|-----------|------|------|
| Raw (hiện tại) | 241KB | — |
| Minified (loại whitespace/comments) | ~145KB | -40% |
| Gzip compressed (server) | ~35KB | -85% |

---

## 4. Dependency Map

```
base.css ─────────────────────────────────── (không phụ thuộc gì)
  │
  ├── about.css ──────────────────────────── (chỉ phụ thuộc base.css)
  │
  ├── blog.css ───────────────────────────── (chỉ phụ thuộc base.css)
  │     └── blog-detail.css ──────────────── (phụ thuộc blog.css + base.css)
  │
  ├── contact.css ────────────────────────── (chỉ phụ thuộc base.css)
  │
  ├── home-page.css ──────────────────────── (chỉ phụ thuộc base.css)
  │
  ├── portfolio.css ──────────────────────── (chỉ phụ thuộc base.css)
  │
  └── portfolio-detail.css ───────────────── (chỉ phụ thuộc base.css)
```

---

## 5. Shared Components (trong base.css)

Các components sau được định nghĩa trong `base.css` và **KHÔNG ĐƯỢC** khai báo lại trong page CSS:

| Component | Class | Ghi chú |
|-----------|-------|---------|
| Button base | `.btn` | Override chỉ qua page-specific modifier |
| Button Primary | `.btn--primary`, `::after` shimmer | Toàn bộ effect trong base |
| Button Outline | `.btn--outline`, `::before` sweep | Toàn bộ effect trong base |
| Button Ghost | `.btn--ghost` | Toàn bộ trong base |
| Section Header | `.section-header`, `.section-eyebrow`, `.section-title`, `.section-subtitle` | Override qua page CSS |
| Entrance Animations | `.anim-fade-up`, `.anim-fade-left`, `.anim-fade-right` | Trigger: `.is-visible` |
| Header Scroll | `#site-header.is-scrolled` | JS adds class |
| Nav Underline | `.nav-link::after` | Base animation |
| Text Blocks | `.text-lead`, `.text-body`, `.text-quote` | Override qua page CSS |
| Icon Circle | `.icon-circle` | `.group:hover` triggers |
| Container | `.site-container` | Responsive padding |

---

## 6. Override Pattern

Khi page CSS cần override base styles:

```css
/* ✅ ĐÚNG — scope rõ ràng, override nhỏ */
.btn { gap: 0.75rem; }                           /* Chỉ thêm property mới */
.section-eyebrow { margin-bottom: 1rem; }        /* Override 1 property */
@media (min-width: 768px) { .btn { font-size: 1rem; } }  /* Responsive override */

/* ❌ SAI — khai báo lại toàn bộ */
.btn--outline { position: relative; overflow: hidden; ... }  /* Đã có trong base! */
.anim-fade-up { opacity: 0; transform: translateY(40px); }  /* Đã có trong base! */
```

---

## 7. State Classes

| Class | Trigger | Mô tả |
|-------|---------|-------|
| `.is-visible` | IntersectionObserver | Element đã vào viewport → animate in |
| `.is-scrolled` | Scroll > 50px | Header background transition |
| `.is-active` | Click/Toggle | Active tab, accordion open |
| `.is-loading` | AJAX request | Loading state (disable click) |
| `.is-scroll-locked` | Mobile menu open | `overflow: hidden` trên body |

---

## 8. Performance Guidelines

### ✅ Nên

- Dùng `contain: layout style` cho sections lớn (đã áp dụng)
- Dùng `@media (hover: hover)` để tách hover effects cho touch devices
- Chỉ định rõ transition properties: `transition: background 0.3s ease, color 0.3s ease`
- Dùng CSS Custom Properties cho theming
- Dùng `clamp()` cho responsive font sizes

### ❌ Không

- `transition: all` — browser phải watch mọi property
- `will-change` trên static elements — tốn GPU RAM
- Duplicate rules đã có trong `base.css`
- `backdrop-filter` quá nhiều trên cùng 1 page — chậm trên mobile
- Hardcode colors — luôn dùng CSS variables

---

## 9. WordPress Migration Notes

### Conditional Enqueue

```php
// functions.php
wp_enqueue_style('xanh-base', get_template_directory_uri() . '/assets/css/base.css');

if (is_front_page()) {
    wp_enqueue_style('xanh-home', '...home-page.css', ['xanh-base']);
} elseif (is_page('about')) {
    wp_enqueue_style('xanh-about', '...about.css', ['xanh-base']);
} elseif (is_singular('xanh_project')) {
    wp_enqueue_style('xanh-portfolio-detail', '...portfolio-detail.css', ['xanh-base']);
}
// ... tương tự cho từng page/CPT
```

### Critical CSS

Inline hero CSS vào `<head>` để giảm render-blocking:
- Header styles (`#site-header`, `.nav-link`)
- Hero section styles (`.about-hero`, `.blog-hero`, etc.)
- Typography base (font-family, font-size)
- Above-the-fold layout (`.site-container`)

---

## Tài Liệu Liên Quan

- `ARCH_DESIGN_TOKENS.md` — 3-layer token system, component tokens
- `ARCH_UI_PATTERNS.md` — 27 UI component specs
- `GOV_CODING_STANDARDS.md` — CSS rules, BEM naming, clean code
- `ARCH_PERFORMANCE.md` — Core Web Vitals, lazy loading
- `ARCH_LUXURY_VISUAL_DIRECTION.md` — Micro-interaction & animation specs
