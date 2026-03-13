# TRACK_DECISIONS — Nhật Ký Quyết Định

> **Dự án:** Website XANH - Design & Build
> **Ngày tạo:** 2026-03-12

---

## ADR-001: Custom WordPress Theme

| Hạng mục | Chi tiết |
|---|---|
| **Ngày** | 2026-03-12 |
| **Trạng thái** | ✅ Accepted |
| **Bối cảnh** | Cần website phù hợp 100% với brand identity Xanh, không bị ràng buộc bởi theme có sẵn |
| **Quyết định** | Phát triển custom theme `xanh-theme` từ đầu |
| **Lý do** | Full control over design, performance, no bloat, exact match brand guideline |
| **Rủi ro** | Thời gian phát triển lâu hơn → Giảm thiểu bằng reusable template-parts |

---

## ADR-002: ACF Pro cho Custom Fields

| Hạng mục | Chi tiết |
|---|---|
| **Ngày** | 2026-03-12 |
| **Trạng thái** | ✅ Accepted |
| **Bối cảnh** | Cần custom fields phức tạp: Repeater (materials), Gallery, Relationship |
| **Quyết định** | Sử dụng ACF Pro |
| **Thay thế xem xét** | Carbon Fields (free), Meta Box (pro) |
| **Lý do** | Ecosystem mature, UI admin tốt, Repeater + Options Page, team quen thuộc |

---

## ADR-003: Fluent Form thay Contact Form 7

| Hạng mục | Chi tiết |
|---|---|
| **Ngày** | 2026-03-12 |
| **Trạng thái** | ✅ Accepted |
| **Quyết định** | Sử dụng Fluent Form Pro |
| **Lý do** | Built-in SMTP, conditional logic, multi-step forms, PDF add-on, better UI, quiz-style estimator |

---

## ADR-004: Classic Editor thay Gutenberg

| Hạng mục | Chi tiết |
|---|---|
| **Ngày** | 2026-03-12 |
| **Trạng thái** | ✅ Accepted |
| **Bối cảnh** | Blog content editing cho nội bộ Xanh |
| **Quyết định** | Dùng Classic Editor cho blog posts |
| **Lý do** | Đơn giản hơn cho content team, ít bugs, không cần block editor vì PageS dùng templates cố định |

---

## ADR-005: LiteSpeed Cache + Smush

| Hạng mục | Chi tiết |
|---|---|
| **Ngày** | 2026-03-12 |
| **Trạng thái** | ✅ Accepted |
| **Quyết định** | LiteSpeed Cache (cache, minify) + Smush (images) |
| **Thay thế** | WP Rocket + ShortPixel |
| **Lý do** | LiteSpeed native với LiteSpeed server (tốc độ tối ưu), Smush Pro WebP conversion, free tiers available |

---

## ADR-006: Vanilla JS (No jQuery)

| Hạng mục | Chi tiết |
|---|---|
| **Ngày** | 2026-03-12 |
| **Trạng thái** | ✅ Accepted |
| **Quyết định** | Vanilla ES6+ JavaScript, không load jQuery |
| **Lý do** | Performance — tránh load jQuery 87KB, browser support đã đủ tốt |
| **Ngoại lệ** | Nếu plugin nào bắt buộc jQuery thì chỉ load conditionally |

## ADR-007: Frontend Library Stack — Luxury Performance

| Hạng mục | Chi tiết |
|---|---|
| **Ngày** | 2026-03-12 |
| **Trạng thái** | ✅ Accepted |
| **Bối cảnh** | Dự án XANH hướng đến phân khúc cao cấp/luxury, cần animations cinematic, smooth scrolling mượt mà, gallery chuyên nghiệp — nhưng vẫn đạt PageSpeed > 90 |

### Quyết Định Chính Thức

| Hạng mục | Thư viện | Version | Size (min+gzip) | Lý do chọn |
|---|---|---|---|---|
| **Animation** | **GSAP + ScrollTrigger** | 3.x | ~23KB | Chuẩn công nghiệp, 60fps, timeline API, scroll-driven animations |
| **Smooth Scroll** | **Lenis** | 1.x | ~4KB | Luxury feel, tối ưu RAF, momentum scroll |
| **Slider/Carousel** | **Swiper** | 11.x | ~15KB (modular) | Touch gestures, responsive, lazy load, modular import |
| **Lightbox/Gallery** | **GLightbox** | 3.x | ~8KB | Nhẹ, video+image, touch, keyboard, accessible |
| **Icons** | **Phosphor Icons** (SVG) | 2.x | 0KB (inline SVG) | 6 style weights (thin→bold), chỉ import icons cần |

### Size Budget

```
GSAP core              ~15KB gzip
ScrollTrigger plugin    ~8KB gzip
Lenis                   ~4KB gzip
Swiper (modular)       ~15KB gzip  (chỉ core + navigation + pagination)
GLightbox               ~8KB gzip
Phosphor Icons          ~0KB       (inline SVG, không load full package)
───────────────────────────────────
Total JS:              ~50KB gzip  ← RẤT NHẸ (React = 45KB, jQuery UI = 80KB)
```

### Thay Thế Đã Xem Xét

| Thay thế | Lý do KHÔNG chọn |
|---|---|
| **AOS** (Animate On Scroll) | Quá đơn giản cho luxury, không có timeline, parallax hạn chế |
| **Anime.js** | API tốt nhưng không có ScrollTrigger tương đương, ecosystem nhỏ hơn GSAP |
| **Locomotive Scroll** | Nặng hơn Lenis 4x, khó debug, ít maintained |
| **PhotoSwipe** | Nặng hơn GLightbox 2x, API phức tạp hơn cho cùng kết quả |
| **Splide** | Nhẹ nhưng thiếu advanced features Swiper có (Virtual slides, Effects) |
| **Flickity** | Ít maintained, license issues |
| **Lucide / Heroicons** | Ít style weights hơn Phosphor, Phosphor có 6 weights phù hợp hơn cho luxury typography |
| **Font Awesome** | Quá nặng (~100KB), dùng web font thay vì SVG, không cần |

### Loading Strategy

```
[Critical — Inline trong <head>]
  variables.css (design tokens)

[Preload]
  FoundersGrotesk-Medium.otf
  FoundersGrotesk-Bold.otf

[Defer — Footer]
  gsap.min.js              → Tất cả trang
  ScrollTrigger.min.js     → Tất cả trang (trừ 404, thank-you)
  lenis.min.js             → Tất cả trang
  swiper-bundle.min.js     → Chỉ trang có slider (Home, Portfolio detail)
  glightbox.min.js         → Chỉ Portfolio detail
  main.js                  → Tất cả trang (app init)
```

---

## ADR-008: Open Props CSS Foundation

| Hạng mục | Chi tiết |
|---|---|
| **Ngày** | 2026-03-12 |
| **Trạng thái** | ⚠️ **Superseded by ADR-009** |
| **Bối cảnh** | Cần đảm bảo tính đồng bộ thiết kế (consistency) trên 27 components × 7 trang. Custom CSS tokens vẫn cần foundation chuyên nghiệp cho easing curves, shadows, normalize |
| **Quyết định** | ~~Dùng **Open Props** + normalize.css làm CSS foundation layer~~ → Thay bằng **Tailwind CSS CLI** |
| **Lý do supersede** | Chuyển sang Tailwind CSS để có utility-first approach, tốt hơn cho developer experience. Xem ADR-009 |

---

## ADR-009: Library Stack Migration — Tailwind + Alpine.js + Lucide + CDN

| Hạng mục | Chi tiết |
|---|---|
| **Ngày** | 2026-03-13 |
| **Trạng thái** | ✅ Accepted |
| **Bối cảnh** | Cần cải thiện developer experience, giảm thiểu vendor files self-hosted, modernize CSS architecture. Project đang trong giai đoạn wireframe, chưa có code production → thời điểm tốt để thay đổi stack |

### Quyết Định — Thay Đổi

| Thay đổi | Cũ | Mới | Lý do |
|---|---|---|---|
| **CSS Framework** | Open Props (~5KB, CDN) | **Tailwind CSS 4.x** (CLI build, ~10KB purged) | Utility-first, DX tốt hơn, purge unused CSS, customizable via config |
| **Interactivity** | Vanilla JS only | **Alpine.js 3.15** (~15KB, CDN) + Vanilla JS | Declarative HTML, code ngắn gọn hơn cho menus/accordions/tabs |
| **Icons** | Phosphor Icons (inline SVG) | **Lucide Icons** (inline SVG / CDN) | 1500+ icons, consistent stroke, popular ecosystem, lighter |
| **Vendor hosting** | Self-host (vendor/ folder) | **CDN** (jsDelivr/unpkg) | Shared cache, faster delivery, less server storage |

### Stack Chính Thức (sau ADR-009)

| Library | Version | Size (gzip) | Source | Purpose |
|---|---|---|---|---|
| **Tailwind CSS** | 4.x | ~8-15KB (purged) | CLI build | Utility-first CSS |
| **Alpine.js** | 3.15.x | ~15KB | CDN (jsDelivr) | Declarative interactivity |
| **GSAP** | 3.12.x | ~15KB | CDN (jsDelivr) | Animation engine |
| **ScrollTrigger** | 3.12.x | ~8KB | CDN (jsDelivr) | Scroll animations |
| **Lenis** | 1.3.x | ~4KB | CDN (jsDelivr) | Smooth scrolling |
| **Swiper** | 11.x | ~15KB | CDN (jsDelivr) | Slider/carousel |
| **GLightbox** | 3.x | ~8KB | CDN (jsDelivr) | Lightbox/gallery |
| **Lucide Icons** | latest | ~0KB | Inline SVG | Line icons |
| **Total** | | **~80-95KB** | | |

### CSS Architecture Mới

```
Trước (ADR-008):
  Open Props → normalize → variables.css → main.css → components.css → utilities.css → responsive.css

Sau (ADR-009):
  Tailwind output.css (CLI build, purged) → variables.css (brand tokens) → components.css (custom)
```

### CDN Strategy

- Tất cả vendor JS qua **jsDelivr** CDN
- **Pin version** (không dùng `@latest` cho JS, trừ Lucide icons)
- **SRI hash** cho security (nên thêm)
- Alpine.js: `defer` in `<head>` (official recommendation)
- GSAP/Lenis/Swiper/GLightbox: `defer` in footer
- **Fallback**: Nếu CDN down, LiteSpeed Cache sẽ serve cached version

### Build Step Mới

```bash
# Cần thêm vào theme workflow
cd wp-content/themes/xanh-theme/
npm init -y
npm install -D @tailwindcss/cli
npx @tailwindcss/cli -i ./assets/css/input.css -o ./assets/css/output.css --minify
# Hoặc watch mode khi develop:
npx @tailwindcss/cli -i ./assets/css/input.css -o ./assets/css/output.css --watch
```

### Thay Thế Đã Xem Xét

| Thay thế | Lý do KHÔNG chọn |
|---|---|
| **Tailwind Play CDN** | ~300KB raw, FOUC, không purge → KHÔNG dùng cho production |
| **Open Props** (giữ nguyên) | Ít utility classes, DX không bằng Tailwind |
| **Phosphor Icons** (giữ nguyên) | Lucide phổ biến hơn, ecosystem lớn hơn, consistent stroke |
| **htmx** (thay Alpine.js) | Quá server-centric, Alpine.js phù hợp hơn cho client-side |

---

<!-- Thêm ADR mới ở đây theo format trên -->


