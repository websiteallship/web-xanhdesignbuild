# FEATURE_MEDIA_GALLERY — Gallery, Slider & Video

> **Dự án:** Website XANH - Design & Build
> **Ngày tạo:** 2026-03-12

---

## 1. Before/After Image Slider

| Thuộc tính | Giá trị |
|---|---|
| **Thư viện** | `img-comparison-slider` hoặc `twentytwenty` |
| Touch/Swipe | ✅ Bắt buộc (mobile) |
| Keyboard | Arrow keys |
| Labels | "Concept 3D" ⎮ "Thực tế bàn giao" |
| Help text | "Kéo để so sánh" |
| ACF Fields | `project_before_image`, `project_after_image` |
| Image format | WebP, optimized |
| Responsive | Full-width container |
| **Trang sử dụng** | Home (section 4), Portfolio Detail |

---

## 2. Lightbox Gallery

| Thuộc tính | Giá trị |
|---|---|
| **Thư viện** | **PhotoSwipe** hoặc **GLightbox** |
| Navigation | Swipe (mobile) + Arrow keys (desktop) |
| Thumbnail strip | Có, phía dưới lightbox |
| Zoom | Pinch-to-zoom (mobile) |
| Counter | "3 / 15" |
| ACF | `project_gallery` (Gallery field) |
| Lazy load | Chỉ preload ±2 ảnh lân cận |
| **Trang sử dụng** | Portfolio Detail |

---

## 3. Material Board

| Thuộc tính | Giá trị |
|---|---|
| Layout | **Horizontal scroll cards** với snap |
| CSS | `scroll-snap-type: x mandatory` |
| Card | Ảnh vật liệu + Tên + Tooltip |
| Tooltip | "Vì sao Xanh?" — hiện khi hover |
| ACF | Repeater `project_materials` |
| **Trang sử dụng** | Portfolio Detail |

---

## 4. Video Popup / Modal

| Thuộc tính | Giá trị |
|---|---|
| Trigger | Nút ▶ trên thumbnail/banner |
| Source | YouTube/Vimeo iframe |
| Overlay | `rgba(0,0,0,0.85)` |
| Close | Overlay click / X button / Escape |
| **Lazy load** | Video chỉ load khi click ▶ |
| ACF | `project_video_url`, `testimonial_video_url` |
| **Trang sử dụng** | Home, About, Green Solution, Portfolio Detail |

---

## 5. Partner Logos Carousel

| Thuộc tính | Giá trị |
|---|---|
| Thư viện | **Swiper** hoặc **Splide** |
| Autoplay | 3000ms, loop: true |
| Slides/view | 5 (desktop), 3 (tablet), 2 (mobile) |
| Logo style | Grayscale → Color on hover |
| ACF | `partner_logos` (Gallery) |
| **Trang sử dụng** | Home |

---

## 6. Phase 2: 360°/VR Tour

| Thuộc tính | Giá trị |
|---|---|
| Thư viện đề xuất | Pannellum (free, WebGL-based) |
| Format | Equirectangular panorama images |
| Tích hợp | Page template riêng hoặc modal |
| **Priority** | Phase 2 — sau go-live |

---

## Image Optimization Standards

| Loại | Max Width | Format | Lazy |
|---|---|---|---|
| Before/After pair | 1200px | WebP | ✅ |
| Gallery images | 1600px | WebP | ✅ |
| Material thumbnails | 400px | WebP | ✅ |
| Video thumbnail | 800px | WebP | ✅ |

---

## Tài Liệu Liên Quan

- `PAGE_PORTFOLIO.md` — Portfolio Detail specs
- `ARCH_UI_PATTERNS.md` — Component interaction specs
- `ARCH_PERFORMANCE.md` — Image optimization
