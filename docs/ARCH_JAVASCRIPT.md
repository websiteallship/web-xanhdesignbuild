# ARCH_JAVASCRIPT — Kiến Trúc JavaScript (Wireframes)

> **Dự án:** Website XANH - Design & Build
> **Phiên bản:** 1.0 | **Cập nhật:** 2026-03-18
> **Stack:** Vanilla JS (ES6+) · GSAP · ScrollTrigger · Swiper · Lenis · Lucide · GLightbox

---

## 1. Tổng Quan Kiến Trúc

```
wireframes/
├── shared/
│   └── base.js              ← Module dùng chung (XanhBase) — 14 KB
│
├── homepage_02/
│   └── home-page.js         ← XanhMobileMenu, XanhHeader, XanhHero,
│                                XanhScrollAnimations, XanhCTA, XanhProjects,
│                                XanhPartners, XanhBlog, XanhProcess,
│                                XanhMarquee, XanhFooter — 43 KB
├── about/
│   └── about.js             ← XanhAbout — 16 KB
├── blog/
│   ├── blog.js              ← XanhBlog — 10 KB
│   └── blog-detail.js       ← XanhBlogDetail — 5.5 KB
├── contact/
│   └── contact.js           ← XanhContact — 6.4 KB
├── portfolio/
│   ├── portfolio.js         ← XanhPortfolio — 7.9 KB
│   └── portfolio-detail.js  ← XanhPortfolioDetail — 18.5 KB
│
└── backup_js_20260318/      ← Bản sao trước refactor (tham khảo)
```

### Nguyên tắc

| # | Nguyên tắc | Giải thích |
|---|---|---|
| 1 | **Object Module Pattern** | Mỗi file export 1 object literal với `init()` method |
| 2 | **Shared Base** | Logic dùng chung nằm trong `shared/base.js` (`XanhBase`) |
| 3 | **DOM-Ready Bootstrap** | `DOMContentLoaded → Module.init()` ở cuối mỗi file |
| 4 | **Defensive Init** | Kiểm tra element tồn tại trước khi init (`if (!el) return`) |
| 5 | **Graceful Degradation** | Fallback `IntersectionObserver` khi GSAP không load được |
| 6 | **Reduced Motion** | Tôn trọng `prefers-reduced-motion` — skip animations |

---

## 2. `shared/base.js` — XanhBase API Reference

`XanhBase` chứa các utility dùng chung cho tất cả trang. Mỗi trang `<script src="../shared/base.js">` trước page JS.

### 2.1 Khởi tạo & Quản lý thư viện

| Method | Mô tả | Params |
|---|---|---|
| `initLucide()` | Gọi `lucide.createIcons()` nếu có | — |
| `initLenis(opts?)` | Khởi tạo Lenis smooth scroll + sync GSAP ticker | `{ lerp, smoothWheel }` |
| `registerGSAP()` | Đăng ký `ScrollTrigger` plugin 1 lần | — |
| `prefersReducedMotion()` | Trả `true/false` dựa trên media query | — |

### 2.2 Animation Utilities

| Method | Mô tả | Params |
|---|---|---|
| `initHeroReveal(bgSel, elSel, delay?)` | Fade-in hero background + reveal elements | Selectors, delay ms |
| `initHeroParallax(imgSel, triggerSel)` | GSAP parallax scrub cho hero image | Selectors |
| `initScrollReveal(selector, opts?)` | IntersectionObserver → add `.is-revealed` class | `{ className, rootMargin }` |
| `animateCounters(sel, opts?)` | Counter number animation (GSAP hoặc rAF fallback) | `{ dataAttr, duration, decimals, useGSAP }` |

### 2.3 UI Components

| Method | Mô tả | Params |
|---|---|---|
| `initBackToTop(id, threshold)` | Show/hide nút back-to-top dựa trên scroll | Button ID, scroll threshold (px) |
| `initCookieConsent()` | Banner cookie consent + localStorage lưu trạng thái | — |

### 2.4 Animation Defaults (const)

```javascript
const ANIM_DEFAULTS = {
  fadeUp:  { y: 40, duration: 0.8, ease: 'power2.out' },
  stagger: 0.12,
};
```

> Tất cả page modules import `ANIM_DEFAULTS` từ `base.js` để đảm bảo nhất quán.

---

## 3. Per-Page Modules

### 3.1 `home-page.js` — Trang Chủ

| Module | Chức năng | Phụ thuộc |
|---|---|---|
| `XanhMobileMenu` | Drawer menu mobile (open/close/ESC/overlay) | — |
| `XanhHeader` | Header scroll behavior (`.is-scrolled` + hamburger color) | — |
| `XanhHero` | Hero Swiper (fade, autoplay, pagination) | Swiper |
| `XanhScrollAnimations` | GSAP fade-up + empathy parallax + services reveal | GSAP, ScrollTrigger |
| `XanhCTA` | CTA section entrance + counter animation | GSAP, ScrollTrigger |
| `XanhProjects` | Before/After slider + thumbnails Swiper + mobile cards | Swiper, GSAP |
| `XanhPartners` | Partner logos auto-scroll Swiper | Swiper |
| `XanhBlogSlider` | Blog cards Swiper + navigation | Swiper |
| `XanhProcess` | Process steps GSAP timeline | GSAP, ScrollTrigger |
| `XanhMarquee` | Infinite marquee animation | GSAP (optional) |
| `XanhFooter` | Footer accordion (mobile) + newsletter form | — |

**Homepage-specific patterns:**
- `XanhProjects._initMobileDragSliders()` — Pointer-based before/after drag cho mobile cards
- `XanhProjects._switchProject(index)` — Cross-fade transition khi click thumbnail
- Mobile Swiper: `allowTouchMove: false` để tránh conflict với BA slider drag

---

### 3.2 `about.js` — Trang Giới Thiệu

| Method | Section | Chức năng |
|---|---|---|
| `initVideoModal()` | Hero | YouTube modal popup |
| `initSectionAnimations()` | All | Unified `.anim-fade-up` scroll entrance |
| `initPainAnimations()` | §2 Pain | Icon scale + stagger reveal |
| `initPromiseAnimations()` | §4 Promise | Timeline entrance + highlight items |
| `initTurningPointAnimations()` | §3 SVG | SVG circle progress + node pop-in |
| `initNodeHover()` | §3 | Hover node → center overlay detail |
| `initTeamAnimations()` | §4.5 | Team cards stagger |
| `initCoreValuesAnimations()` | §5 | Core value cards multi-element reveal |
| `initFinalCTAAnimations()` | §6 | CTA timeline entrance |
| `initFallbackAnimations()` | All | IO fallback khi không có GSAP |
| `initReducedMotionFallback()` | All | Skip tất cả — hiện ngay |

---

### 3.3 `blog.js` — Trang Blog

| Method | Chức năng |
|---|---|
| `initSearchPlaceholder()` | Typing animation cho search input placeholder |
| `initSearchDropdown()` | Autocomplete dropdown (debounced) |
| `initCategoryTabs()` | Filter cards theo `data-category` |
| `initLoadMore()` | Load more articles (batch 3) |
| `initLeadMagnet()` | 3D book tilt effect + form submit |

---

### 3.4 `blog-detail.js` — Chi Tiết Bài Viết

| Method | Chức năng |
|---|---|
| `initReadingProgress()` | Reading progress bar (scroll-based) |
| `initTOC()` | Auto-generate Table of Contents từ H2/H3 |
| `initSocialShare()` | Copy link button + tooltip |
| `initCleanup()` | Disconnect observers on `beforeunload` |

---

### 3.5 `contact.js` — Trang Liên Hệ

| Method | Chức năng |
|---|---|
| `initFAQ()` | FAQ accordion (open/close, single open) |
| `initFormValidation()` | Real-time field validation (blur/input) |
| `initFormSubmit()` | Form submit handler (mock async) |
| `initSelectPlaceholder()` | Track select empty state |

---

### 3.6 `portfolio.js` — Trang Dự Án

| Method | Chức năng |
|---|---|
| `initFilterTabs()` | Filter cards theo category + re-reveal animation |
| `initLoadMore()` | Load 9 extra cards (dynamic HTML generation) |
| `observeNewCards(cards)` | Observe mới thêm cards cho scroll reveal |

---

### 3.7 `portfolio-detail.js` — Chi Tiết Dự Án

| Method | Chức năng |
|---|---|
| `initHeroReveal()` | Hero bg load + breadcrumb/title reveal |
| `initHeroParallax()` | rAF scroll parallax |
| `initBeforeAfterSlider()` | Swiper main + thumbs + custom drag init |
| `_initCustomDragSliders()` | Pointer-based BA drag (reusable) |
| `initLightbox()` | BA lightbox modal (zoom, nav, keyboard, thumbnails) |
| `initVideoLightbox()` | GLightbox for video |
| `initGallery()` | Gallery lightbox + GSAP stagger + load more |
| `initRelatedProjects()` | IO-based stagger reveal |

**Lightbox sub-methods:**
- `_collectLightboxSlides()` — Đọc `data-first`, `data-second`, `data-title` từ zoom buttons
- `_buildLightboxThumbs()` — Render thumbnail navigation HTML
- `_bindLightboxEvents()` — Open/close/prev/next/keyboard/thumbnail click
- `_updateLightboxThumbs()` — Scroll active thumbnail into view

---

## 4. HTML Script Loading Order

```html
<!-- Vendor CDN -->
<script src="https://cdn.jsdelivr.net/npm/lenis@1/dist/lenis.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3/dist/gsap.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3/dist/ScrollTrigger.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>
<script src="https://unpkg.com/lucide@latest" defer></script>

<!-- Shared Base (PHẢI load trước page JS) -->
<script src="../shared/base.js" defer></script>

<!-- Page-specific Module -->
<script src="home-page.js" defer></script>
```

> **Quan trọng:** `base.js` phải load trước page JS vì page modules gọi `XanhBase.*` methods.

---

## 5. Before/After Slider — Custom Component

### HTML Structure

```html
<div class="ba-custom-slider">
  <img src="after.png" class="ba-custom-slider__after" draggable="false" />
  <div class="ba-custom-slider__before">
    <img src="before.png" draggable="false" />
  </div>
  <div class="ba-custom-slider__handle">
    <div class="ba-custom-slider__handle-line"></div>
    <div class="ba-custom-slider__handle-knob">
      <svg><!-- chevrons-horizontal --></svg>
    </div>
    <div class="ba-custom-slider__handle-line"></div>
  </div>
</div>
```

### Drag Logic (Pointer Events)

```
pointerdown → setPointerCapture → isDragging = true → setPos(%)
pointermove → if isDragging → setPos(clientX → %)
pointerup / pointercancel → isDragging = false
```

- `clipPath: inset(0 ${100-pct}% 0 0)` trên `.ba-custom-slider__before`
- `handle.style.left = pct + '%'`
- Mobile: `e.stopPropagation()` để tránh conflict với Swiper

---

## 6. Dependency Map

```
                    ┌──────────────┐
                    │   base.js    │
                    │  (XanhBase)  │
                    └──────┬───────┘
           ┌───────────────┼───────────────┐
           │               │               │
    ┌──────┴──────┐ ┌──────┴──────┐ ┌──────┴──────┐
    │ home-page.js│ │  about.js   │ │  blog.js    │
    │ (11 modules)│ │ (XanhAbout) │ │ (XanhBlog)  │
    └─────────────┘ └─────────────┘ └─────────────┘
           │
    ┌──────┴──────────────┐
    │ contact.js          │
    │ portfolio.js        │
    │ portfolio-detail.js │
    │ blog-detail.js      │
    └─────────────────────┘
```

### External Dependencies

| Library | Version | Dùng bởi | Mục đích |
|---|---|---|---|
| **GSAP** | 3.x | Tất cả | Scroll animations, timelines |
| **ScrollTrigger** | 3.x | Tất cả | Trigger animations on scroll |
| **Swiper** | 11.x | Home, Portfolio Detail | Carousels, slider navigation |
| **Lenis** | 1.x | Tất cả | Smooth scroll |
| **Lucide** | latest | Tất cả | SVG icon rendering |
| **GLightbox** | 3.x | Portfolio Detail | Video popup |

---

## 7. Dung Lượng JS

| File | Size | Lines |
|---|---:|---:|
| `shared/base.js` | 14.2 KB | ~400 |
| `homepage_02/home-page.js` | 43.0 KB | ~1,196 |
| `about/about.js` | 16.3 KB | ~458 |
| `blog/blog.js` | 10.3 KB | ~324 |
| `blog/blog-detail.js` | 5.5 KB | ~191 |
| `contact/contact.js` | 6.4 KB | ~189 |
| `portfolio/portfolio.js` | 7.9 KB | ~159 |
| `portfolio/portfolio-detail.js` | 18.5 KB | ~540 |
| **Tổng** | **122.0 KB** | **~3,457** |

> Mỗi trang load ~14 KB (base) + page JS (5–43 KB). Tổng JS custom trên mỗi trang:
> - Homepage: **57 KB** (base + home-page)
> - Portfolio Detail: **33 KB** (base + portfolio-detail)
> - About: **30 KB** (base + about)
> - Blog: **24 KB** (base + blog)
> - Blog Detail: **20 KB** (base + blog-detail)
> - Contact: **21 KB** (base + contact)
> - Portfolio: **22 KB** (base + portfolio)

---

## 8. Patterns & Conventions

### GSAP Animation Pattern
```javascript
// ✅ fromTo with ScrollTrigger
gsap.fromTo(el,
  { opacity: 0, y: ANIM_DEFAULTS.fadeUp.y },
  {
    scrollTrigger: { trigger: el, start: 'top 85%', once: true },
    opacity: 1, y: 0,
    duration: ANIM_DEFAULTS.fadeUp.duration,
    ease: ANIM_DEFAULTS.fadeUp.ease,
  }
);
```

### IntersectionObserver Fallback
```javascript
// Khi GSAP không load được (CDN fail, file:// protocol)
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.style.transition = 'opacity 0.7s ease, transform 0.7s ease';
      entry.target.style.opacity = '1';
      entry.target.style.transform = 'none';
      observer.unobserve(entry.target);
    }
  });
}, { threshold: 0.1 });
```

### Swiper Init Pattern
```javascript
new Swiper('.my-swiper', {
  slidesPerView: 1,
  spaceBetween: 16,
  loop: true,
  pagination: { el: '.my-pagination', clickable: true },
  breakpoints: {
    640: { slidesPerView: 2 },
    1024: { slidesPerView: 3 },
  },
});
```

### Error Handling
```javascript
// ✅ Defensive — không crash nếu DOM thiếu element
init() {
  const el = document.getElementById('my-el');
  if (!el) return; // Silent fail

  try { /* init logic */ }
  catch (err) { console.warn('[XANH] Init failed:', err.message); }
}
```

---

## 9. Checklist Thêm Module Mới

- [ ] Tạo file `kebab-case.js` trong thư mục page tương ứng
- [ ] Dùng Object Module Pattern: `const XanhNewModule = { init() { ... } };`
- [ ] Gọi `XanhBase.*` cho logic dùng chung (Lucide, Lenis, ScrollReveal, ...)
- [ ] Check `if (!el) return` cho mọi DOM query
- [ ] Check `prefers-reduced-motion` trước khi animate
- [ ] Thêm fallback IO khi GSAP không available
- [ ] Load `base.js` trước page JS trong HTML
- [ ] Bootstrap via `DOMContentLoaded`
- [ ] Cập nhật tài liệu này (thêm vào §3 Per-Page Modules)

---

## Tài Liệu Liên Quan

- `GOV_CODING_STANDARDS.md` — JS coding rules, GSAP patterns, error handling
- `CORE_ARCHITECTURE.md` — Full stack architecture, asset pipeline
- `ARCH_UI_PATTERNS.md` — UI component specifications
- `ARCH_PERFORMANCE.md` — Performance optimization strategies
- `ARCH_DESIGN_TOKENS.md` — CSS token system used alongside JS
