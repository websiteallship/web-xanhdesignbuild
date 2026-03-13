# ARCH_PERFORMANCE — Hiệu Năng & Tối Ưu

> **Dự án:** Website XANH - Design & Build
> **Phiên bản:** 2.0 | **Cập nhật:** 2026-03-12
> **Stack:** Tailwind CSS (CLI) + Alpine.js + GSAP + Lenis + Swiper + GLightbox + Lucide + LiteSpeed + Smush

---

## 1. Mục Tiêu Performance

| Metric | Target | Tool đo |
|---|---|---|
| **PageSpeed Score** | > 90 (mobile + desktop) | Google PageSpeed Insights |
| **Largest Contentful Paint (LCP)** | < 2.5s | Lighthouse |
| **Interaction to Next Paint (INP)** | < 200ms | Lighthouse |
| **Cumulative Layout Shift (CLS)** | < 0.1 | Lighthouse |
| **Time to First Byte (TTFB)** | < 200ms | WebPageTest |
| **Total Page Weight** | < 1.5MB (homepage) | DevTools |
| **JS Bundle (gzip)** | < 70KB total | DevTools |

---

## 2. JS Size Budget (ADR-007)

### Vendor Libraries

| Library | Raw | Gzip | Source | Loading | Pages |
|---|---|---|---|---|---|
| Tailwind CSS (compiled) | ~40KB | ~10KB | Local (CLI build) | `<link>` head | All |
| Alpine.js | ~45KB | ~15KB | CDN (jsDelivr) | `defer` head | All |
| GSAP core | ~60KB | ~15KB | CDN (jsDelivr) | `defer` footer | All |
| ScrollTrigger | ~30KB | ~8KB | CDN (jsDelivr) | `defer` footer | All |
| Lenis | ~15KB | ~4KB | CDN (jsDelivr) | `defer` footer | All |
| Swiper (modular) | ~55KB | ~15KB | CDN (jsDelivr) | `defer` conditional | Home, Portfolio |
| GLightbox | ~25KB | ~8KB | CDN (jsDelivr) | `defer` conditional | Portfolio detail |
| Lucide Icons | 0 | ~0KB | Inline SVG | N/A | As needed |
| **Total vendor** | **~270KB** | **~75KB** | | | |

### Custom JS

| File | Gzip (est.) | Pages |
|---|---|---|
| `main.js` | ~3KB | All |
| `animations.js` | ~2KB | All |
| `slider.js` | ~1KB | Home, Portfolio |
| `gallery.js` | ~1KB | Portfolio detail |
| `filter.js` | ~2KB | Portfolio, Blog |
| `forms.js` | ~2KB | Contact, Home |
| `search.js` | ~1KB | Blog |
| **Total custom** | **~12KB** | |

### Per-Page Budget

| Page | Vendor JS (gzip) | Custom JS | Total JS |
|---|---|---|---|
| **Homepage** | ~62KB (Tailwind+Alpine+GSAP+Lenis+Swiper) | ~8KB | **~70KB** |
| **Portfolio grid** | ~52KB (Tailwind+Alpine+GSAP+Lenis) | ~7KB | **~59KB** |
| **Portfolio detail** | ~75KB (all) | ~6KB | **~81KB** |
| **Blog** | ~52KB (Tailwind+Alpine+GSAP+Lenis) | ~8KB | **~60KB** |
| **Contact** | ~52KB (Tailwind+Alpine+GSAP+Lenis) | ~7KB | **~59KB** |
| **404 / Thank-you** | ~29KB (Tailwind+Alpine+Lenis) | ~1KB | **~30KB** |

> ✅ Trang nặng nhất (Portfolio detail) ~81KB — vẫn nhẹ hơn React app trung bình (~150KB)

---

## 3. CSS Budget

```
Tailwind output.css (purged)  ~10KB gzip
variables.css                 ~2KB gzip
components.css                ~4KB gzip
swiper-bundle.min.css (CDN)   ~3KB gzip (conditional)
glightbox.min.css (CDN)       ~1KB gzip (conditional)
────────────────────────────────────────
Total CSS:                    ~16-20KB gzip
```

---

## 4. Image Optimization (Smush)

### Configuration

| Setting | Giá trị |
|---|---|
| **Plugin** | Smush Pro |
| **Auto-compress** | ✅ On upload |
| **Strip metadata** | ✅ |
| **Resize large images** | Max width 2560px |
| **Lazy load** | ✅ Native (`loading="lazy"`) |
| **WebP conversion** | ✅ Serve WebP khi browser hỗ trợ |
| **CDN** | Smush CDN hoặc LiteSpeed CDN |

### Image Size Guidelines

| Loại | Max Width | Format | Chất lượng |
|---|---|---|---|
| Hero banner | 1920px | WebP | 80% |
| Portfolio thumbnail | 800px | WebP | 80% |
| Portfolio gallery | 1600px | WebP | 85% |
| Blog thumbnail | 600px | WebP | 80% |
| Team photo | 400px | WebP | 80% |
| Partner logo | 200px | PNG/SVG | Lossless |
| Before/After pair | 1200px | WebP | 85% |

### `srcset` Pattern
```html
<img
  src="image-800.webp"
  srcset="image-400.webp 400w,
          image-800.webp 800w,
          image-1200.webp 1200w"
  sizes="(max-width: 768px) 100vw,
         (max-width: 1024px) 50vw,
         33vw"
  loading="lazy"
  alt="[Mô tả SEO]"
  width="800"
  height="600"
>
```

---

## 5. LiteSpeed Cache Configuration

### Page Cache

| Setting | Giá trị |
|---|---|
| **Enable Cache** | ✅ |
| **Cache Mobile** | ✅ (Separate) |
| **Cache Logged-in** | ❌ |
| **Cache REST API** | ✅ |
| **TTL** | 604800 (7 ngày) |

### CSS/JS Optimization

| Setting | Giá trị |
|---|---|
| **Minify CSS** | ✅ |
| **Minify JS** | ✅ |
| **Combine CSS** | ❌ (HTTP/2 native multiplexing) |
| **Combine JS** | ❌ |
| **Load CSS Async** | ✅ |
| **Load JS Deferred** | ✅ |
| **Inline Critical CSS** | ✅ (Auto UCSS generation) |

### Browser Cache (`.htaccess`)

| File Type | Max-Age |
|---|---|
| Images (WebP, PNG, JPG) | 1 year |
| CSS, JS | 1 year (versioned via `?ver=`) |
| Fonts (OTF, TTF, WOFF2) | 1 year |
| HTML | No cache |

---

## 6. Font Loading Strategy

```css
/* Chỉ load 2 weights Founders Grotesk */
@font-face {
  font-family: 'FoundersGrotesk';
  src: url('../fonts/FoundersGrotesk/FoundersGroteskMedium.otf') format('opentype');
  font-weight: 500;
  font-display: swap;
}
@font-face {
  font-family: 'FoundersGrotesk';
  src: url('../fonts/FoundersGrotesk/FoundersGroteskBold.otf') format('opentype');
  font-weight: 700;
  font-display: swap;
}

/* Inter: Variable font — 1 file cho tất cả weights */
@font-face {
  font-family: 'Inter';
  src: url('../fonts/Inter/Inter-VariableFont.ttf') format('truetype');
  font-weight: 100 900;
  font-display: swap;
}
```

### Preload Critical Fonts
```html
<link rel="preload" href="/fonts/FoundersGroteskMedium.otf" as="font" crossorigin>
<link rel="preload" href="/fonts/Inter-VariableFont.ttf" as="font" crossorigin>
```

---

## 7. Critical Rendering Path

### Loading Order
```
[1. Critical — Blocking]
  Critical CSS (LiteSpeed auto-generate)
  Preload hero image + fonts

[2. Early — Head]
  <link> Tailwind output.css + variables.css + components.css (async via LiteSpeed)
  <script defer> Alpine.js CDN

[3. Deferred — Footer]
  GSAP CDN → ScrollTrigger CDN → Lenis CDN → Lucide CDN
  → main.js → animations.js
  Swiper CDN (conditional) → slider.js (conditional)
  GLightbox CDN (conditional) → gallery.js (conditional)

[4. Lazy — After interaction]
  Zalo widget (DOMContentLoaded + 3s delay)
  Google Analytics (after cookie consent)
  Facebook Pixel (after cookie consent)
```

### Preload Hero Image
```html
<link rel="preload" href="/images/hero-home.webp" as="image" type="image/webp"
  media="(min-width: 768px)">
<link rel="preload" href="/images/hero-home-mobile.webp" as="image" type="image/webp"
  media="(max-width: 767px)">
```

---

## 8. Perceived Performance ★ (Luxury UX)

> Luxury users kỳ vọng mọi thứ **mượt mà, tức thì**. Perceived performance quan trọng bằng actual performance.

### Techniques

| Technique | Implementation | Cảm giác tạo ra |
|---|---|---|
| **Preloader** | Logo XANH pulse → fade out (1.5s, sessionStorage skip) | Brand impression, hide FOUC |
| **Skeleton loading** | Shimmer gradient khi AJAX filter | "Đang tải" thay "trống trơn" |
| **Progressive image** | CSS `blur(20px)` → `blur(0)` on load, scale(1.02→1) | Ảnh "hiện dần" mượt mà |
| **Staggered entrance** | GSAP stagger 100ms between cards | "Cascading luxury" |
| **Optimistic UI** | Button → spinner ngay khi click | "Đã nhận" trước API response |
| **Smooth scroll** | Lenis `lerp: 0.07` — 60fps momentum | "Như native app" |
| **No layout shift** | Width+height on all imgs, placeholder cho fonts | Không nhảy layout |

### Progressive Image Pattern
```css
.img-progressive {
  filter: blur(20px);
  transform: scale(1.02);
  transition: filter 500ms var(--ease-out-3), transform 500ms var(--ease-out-3);
}
.img-progressive.is-loaded {
  filter: blur(0);
  transform: scale(1);
}
```

### Skeleton Pattern
```css
.skeleton {
  background: linear-gradient(
    90deg,
    var(--color-gray-100) 25%,
    var(--color-gray-200) 50%,
    var(--color-gray-100) 75%
  );
  background-size: 200% 100%;
  animation: shimmer 1.5s infinite;
  border-radius: var(--radius-md);
}
@keyframes shimmer {
  0% { background-position: 200% 0; }
  100% { background-position: -200% 0; }
}
```

---

## 9. Database Optimization

| Task | Frequency | Tool |
|---|---|---|
| Clean post revisions | Weekly | LiteSpeed / WP-CLI |
| Clean transients | Daily | LiteSpeed Cache |
| Optimize tables | Monthly | WP-CLI `wp db optimize` |
| Clean spam comments | Weekly | Auto |
| Clean orphan meta | Monthly | WP-CLI |

---

## 10. Performance Checklist

### Pre-Launch
- [ ] Images converted to WebP (Smush)
- [ ] `width` & `height` on all `<img>` tags
- [ ] `loading="lazy"` on below-fold images
- [ ] Hero image NOT lazy-loaded (above the fold)
- [ ] Fonts preloaded + `font-display: swap`
- [ ] Only load JS needed per page (conditional enqueue)
- [ ] LiteSpeed Cache enabled & configured
- [ ] Critical CSS inlined (LiteSpeed UCSS)
- [ ] Zalo widget lazy-loaded (3s delay)
- [ ] Open Props loaded via CDN with SRI hash
- [ ] CDN scripts pinned to specific versions with SRI hash
- [ ] Tailwind CSS compiled and purged (no unused utilities)

### Metrics
- [ ] TTFB < 200ms
- [ ] LCP < 2.5s
- [ ] CLS < 0.1
- [ ] INP < 200ms
- [ ] PageSpeed > 90 (both mobile & desktop)
- [ ] Total page weight < 1.5MB
- [ ] JS bundle < 70KB gzip (per page)

### Perceived
- [ ] Preloader implemented (first visit only)
- [ ] Skeleton loading on AJAX filter
- [ ] Progressive image reveal
- [ ] Staggered card entrances
- [ ] Smooth scroll (Lenis) working 60fps
- [ ] No visible layout shifts

---

## Tài Liệu Liên Quan

- `TRACK_DECISIONS.md` — ADR-007 (JS stack), ADR-009 (Stack Migration)
- `ARCH_DESIGN_TOKENS.md` — Font references, CSS tokens
- `GOV_CODING_STANDARDS.md` — JS patterns, conditional loading
- `ARCH_LUXURY_VISUAL_DIRECTION.md` — §5 Micro-interactions, §8 Perceived performance
- `OPS_TESTING.md` — Performance testing procedures
