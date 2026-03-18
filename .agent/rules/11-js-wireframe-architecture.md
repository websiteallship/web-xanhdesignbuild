---
description: JavaScript architecture rules for wireframe pages. Apply when creating, editing, or reviewing JS files inside wireframes/.
globs: "wireframes/**/*.{js,html}"
---

# JavaScript — Wireframe Architecture ★★

> **Mục tiêu:** Đảm bảo tất cả JS trong `wireframes/` tuân thủ kiến trúc `base.js` + per-page module.
> Bổ sung cho `10-js-optimization.md` (performance) và `02-frontend-css-js.md` (stack).
> Tham chiếu đầy đủ: `docs/ARCH_JAVASCRIPT.md`.

---

## 1. File Structure — BẮT BUỘC ★

```
wireframes/
├── shared/
│   └── base.js              ← XanhBase — Logic dùng chung, KHÔNG chỉnh sửa bừa
├── homepage_02/
│   └── home-page.js         ← Modules riêng trang chủ
├── about/
│   └── about.js             ← XanhAbout
├── blog/
│   ├── blog.js              ← XanhBlog
│   └── blog-detail.js       ← XanhBlogDetail
├── contact/
│   └── contact.js           ← XanhContact
├── portfolio/
│   ├── portfolio.js         ← XanhPortfolio
│   └── portfolio-detail.js  ← XanhPortfolioDetail
```

**Quy tắc:**
- Mỗi page folder chỉ có **1 file JS chính**
- Logic dùng chung → thêm vào `shared/base.js`, KHÔNG copy-paste
- Backup cũ nằm trong `backup_js_20260318/` — chỉ tham khảo, KHÔNG sửa

---

## 2. XanhBase — Shared Module (shared/base.js) ★

### 2.1 — LUÔN gọi XanhBase cho logic dùng chung

```javascript
/* ✅ ĐÚNG — Dùng XanhBase thay vì tự viết lại */
init() {
  XanhBase.initLucide();
  this.lenis = XanhBase.initLenis();
  XanhBase.initHeroReveal('.about-hero__bg', '.about-hero-el');
  XanhBase.initScrollReveal('.anim-fade-up');
  XanhBase.initBackToTop('back-to-top', 500);
  XanhBase.registerGSAP();
  XanhBase.animateCounters('.counter-number', { dataAttr: 'target', useGSAP: true });
}

/* ❌ SAI — Viết lại logic đã có trong base.js */
init() {
  if (typeof lucide !== 'undefined') lucide.createIcons();    // ← đã có XanhBase.initLucide()
  const lenis = new Lenis({ lerp: 0.07, smoothWheel: true }); // ← đã có XanhBase.initLenis()
}
```

### 2.2 — Danh sách XanhBase methods (tham chiếu nhanh)

| Category | Methods |
|---|---|
| **Thư viện** | `initLucide()` · `initLenis(opts?)` · `registerGSAP()` · `prefersReducedMotion()` |
| **Animation** | `initHeroReveal(bg, els, delay?)` · `initHeroParallax(img, trigger)` · `initScrollReveal(sel, opts?)` · `animateCounters(sel, opts?)` |
| **UI** | `initBackToTop(id, threshold)` · `initCookieConsent()` |
| **Const** | `ANIM_DEFAULTS.fadeUp` · `ANIM_DEFAULTS.stagger` |

### 2.3 — Khi nào thêm method mới vào base.js

- ✅ **Thêm** khi: logic được dùng bởi **≥2 trang** (ví dụ: lightbox pattern, FAQ accordion)
- ❌ **KHÔNG thêm** khi: logic chỉ dùng cho 1 trang (ví dụ: typing placeholder chỉ blog)

---

## 3. HTML Script Loading — THỨ TỰ BẮT BUỘC ★

```html
<!-- 1. Vendor CDN (cuối body) -->
<script src="https://cdn.jsdelivr.net/npm/lenis@1/dist/lenis.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3/dist/gsap.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3/dist/ScrollTrigger.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>
<script src="https://unpkg.com/lucide@latest" defer></script>

<!-- 2. Shared Base — PHẢI trước page JS -->
<script src="../shared/base.js" defer></script>

<!-- 3. Page Module — LUÔN sau base.js -->
<script src="home-page.js" defer></script>
```

**Quy tắc:**
- `base.js` **PHẢI** load trước page JS (page modules gọi `XanhBase.*`)
- Vendor CDNs load trước `base.js`
- Swiper chỉ load ở trang có slider (Home, Portfolio Detail)
- GLightbox chỉ load ở Portfolio Detail

```html
/* ❌ SAI — Page JS load trước base.js */
<script src="about.js" defer></script>
<script src="../shared/base.js" defer></script>  <!-- XanhBase chưa có khi about.js chạy -->
```

---

## 4. Object Module Pattern — Quy Tắc Tạo Module ★

### 4.1 — Template chuẩn cho page module mới

```javascript
/**
 * XANH — Design & Build
 * [Page Name] Page Module
 * =============================================
 * Libraries: GSAP, ScrollTrigger, Lenis, Lucide [thêm nếu cần]
 * Pattern:   Xanh[PageName] module
 */

/* ── Animation Defaults: inherited from base.js (ANIM_DEFAULTS) ── */

const Xanh[PageName] = {
  lenis: null,
  prefersReducedMotion: false,

  /* ── Entry Point ── */
  init() {
    this.prefersReducedMotion = XanhBase.prefersReducedMotion();

    XanhBase.initLucide();
    this.lenis = XanhBase.initLenis();
    XanhBase.initHeroReveal('[hero-bg-selector]', '[hero-el-selector]');

    // Page-specific inits
    this.initFeatureA();
    this.initFeatureB();

    // Animations — respect reduced motion
    if (!this.prefersReducedMotion) {
      XanhBase.initHeroParallax('[hero-img]', '[hero-section]');
      XanhBase.initScrollReveal('.anim-fade-up');
    }
  },

  /* ── Feature A ── */
  initFeatureA() {
    const el = document.getElementById('feature-a');
    if (!el) return; // Defensive

    // ...feature logic
  },

  /* ── Feature B ── */
  initFeatureB() {
    // ...
  },
};

/* ── Bootstrap ── */
document.addEventListener('DOMContentLoaded', () => {
  Xanh[PageName].init();
});
```

### 4.2 — Naming Convention

| Thành phần | Convention | Ví dụ |
|---|---|---|
| Module object | `Xanh` + PascalCase page name | `XanhAbout`, `XanhBlog`, `XanhPortfolioDetail` |
| Public method | `init` + PascalCase feature | `initVideoModal()`, `initFAQ()` |
| Private method | `_` prefix + camelCase | `_initDragLogic()`, `_setPosition()` |
| Event handler | `_handle` + Event | `_handleScroll()`, `_handleSubmit()` |
| DOM refs cache | `_els` object hoặc `els` | `this._els = { slider, handle, ... }` |

### 4.3 — Method Size Limit

- Mỗi method ≤ **30 dòng**
- Nếu dài hơn → tách thành private sub-methods
- Ví dụ: `initLightbox()` → `_collectSlides()` + `_buildThumbs()` + `_bindEvents()`

---

## 5. Before/After Slider — Component Pattern ★

### 5.1 — Custom Drag Slider (Pointer Events)

```javascript
/* ✅ Pattern chuẩn — dùng cho cả homepage và portfolio-detail */
_initCustomDragSliders() {
  document.querySelectorAll('.ba-custom-slider').forEach(slider => {
    if (slider._dragInit) return;     // Skip nếu đã init (Swiper loop duplicates)
    slider._dragInit = true;

    const beforeClip = slider.querySelector('.ba-custom-slider__before');
    const handle = slider.querySelector('.ba-custom-slider__handle');
    if (!beforeClip || !handle) return;

    let isDragging = false;

    function setPos(pct) {
      pct = Math.max(0, Math.min(100, pct));
      beforeClip.style.clipPath = 'inset(0 ' + (100 - pct) + '% 0 0)';
      handle.style.left = pct + '%';
    }

    function getPct(e) {
      const r = slider.getBoundingClientRect();
      return ((e.clientX - r.left) / r.width) * 100;
    }

    slider.addEventListener('pointerdown', (e) => {
      e.stopPropagation();              // Tránh conflict Swiper touch
      isDragging = true;
      slider.setPointerCapture(e.pointerId);
      setPos(getPct(e));
    });
    slider.addEventListener('pointermove', (e) => {
      if (!isDragging) return;
      setPos(getPct(e));
    });
    slider.addEventListener('pointerup', () => { isDragging = false; });
    slider.addEventListener('pointercancel', () => { isDragging = false; });
    setPos(50);                         // Initial 50%
  });
}
```

### 5.2 — Quan trọng trên Mobile

- `e.stopPropagation()` trong `pointerdown` — ngăn Swiper bắt touch
- `slider.setPointerCapture(e.pointerId)` — giữ pointer ngay cả khi di ra ngoài
- Swiper chứa BA cards: `allowTouchMove: false` — dùng nút prev/next thay vì swipe

---

## 6. Lightbox Pattern — Reusable ★

Khi tạo lightbox (zoom, gallery, video):

```javascript
/* ✅ Structure chuẩn */
initLightbox() {
  const lightbox = document.getElementById('my-lightbox');
  if (!lightbox) return;

  const slides = this._collectSlides();    // 1. Thu thập data từ DOM
  if (!slides.length) return;

  this._buildThumbs(lightbox, slides);     // 2. Render thumbnails
  this._bindLightboxEvents(lightbox, slides); // 3. Bind events
},

/* Events pattern */
_bindLightboxEvents(lightbox, slides) {
  let currentIndex = 0;

  const open = (index) => {
    buildSlider(index);
    lightbox.classList.add('is-open');
    document.body.style.overflow = 'hidden';
    if (this.lenis) this.lenis.stop();     // Pause smooth scroll
  };

  const close = () => {
    lightbox.classList.remove('is-open');
    document.body.style.overflow = '';
    if (this.lenis) this.lenis.start();    // Resume smooth scroll
  };

  // Keyboard navigation
  document.addEventListener('keydown', (e) => {
    if (!lightbox.classList.contains('is-open')) return;
    if (e.key === 'Escape') close();
    if (e.key === 'ArrowLeft') goTo(currentIndex - 1);
    if (e.key === 'ArrowRight') goTo(currentIndex + 1);
  });
}
```

---

## 7. Graceful Degradation — GSAP Fallback ★

### 7.1 — Luôn có fallback khi GSAP không load

```javascript
/* ✅ Pattern 3 cấp */
init() {
  if (!this.prefersReducedMotion) {
    if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
      // Cấp 1: GSAP có → full cinematic animations
      this.initGSAPAnimations();
    } else {
      // Cấp 2: GSAP không có → IntersectionObserver fallback
      this.initFallbackAnimations();
    }
  } else {
    // Cấp 3: Reduced motion → hiện ngay, không animate
    this.initReducedMotionFallback();
  }
}
```

### 7.2 — IO Fallback Pattern

```javascript
initFallbackAnimations() {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.transition = 'opacity 0.7s ease, transform 0.7s ease';
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'none';
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.anim-fade-up, .anim-fade-left').forEach(el => {
    observer.observe(el);
  });
}
```

---

## 8. Checklist — Khi Viết/Sửa JS Wireframe ✓

### Tạo file mới:
- [ ] Dùng Object Module Pattern (`const XanhXxx = { init() {} }`)
- [ ] Gọi `XanhBase.*` cho logic đã có (KHÔNG copy-paste)
- [ ] HTML: load `base.js` trước page JS
- [ ] Check DOM element tồn tại trước khi init
- [ ] Check `prefersReducedMotion` trước animations
- [ ] Có fallback IO khi GSAP không available
- [ ] Dùng `ANIM_DEFAULTS` cho animation config
- [ ] Bootstrap via `DOMContentLoaded`

### Sửa file hiện có:
- [ ] Logic cần dùng chung → move vào `base.js`, gọi qua `XanhBase.*`
- [ ] KHÔNG duplicate code giữa các page modules
- [ ] Re-init drag sliders sau Swiper `slideChangeTransitionEnd`
- [ ] Lightbox: pause Lenis khi mở, resume khi đóng
- [ ] Mobile BA slider: `e.stopPropagation()` + `setPointerCapture`
- [ ] Cập nhật `docs/ARCH_JAVASCRIPT.md` nếu thêm module mới

---

## Tài Liệu Liên Quan

- `docs/ARCH_JAVASCRIPT.md` — Tài liệu kiến trúc JS đầy đủ
- `10-js-optimization.md` — Performance, memory, anti-patterns
- `02-frontend-css-js.md` — Library stack, design tokens
- `08-cross-section-consistency.md` — Animation consistency
