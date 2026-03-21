# ARCH_UI_PATTERNS — UI Components & Interaction Patterns

> **Dự án:** Website XANH - Design & Build
> **Phiên bản:** 1.1 | **Cập nhật:** 2026-03-12
> **Tham chiếu:** [ui_ux_enhancement_guide.md](./ui_ux_enhancement_guide.md) | [ARCH_DESIGN_TOKENS.md](./ARCH_DESIGN_TOKENS.md)

---

## Tổng Quan: 27 UI Components

| # | Component | Loại | Phạm vi |
|---|---|---|---|
| 1 | Before/After Image Slider | Interactive | Portfolio Detail |
| 2 | Interactive Process Steps | Interactive | Home |
| 3 | Trust Indicators / Stats | Static | Home, Portfolio |
| 4 | Sticky Navigation / Filter | Navigation | Portfolio, Blog |
| 5 | Accordion FAQ | Interactive | Contact |
| 6 | Lightbox Gallery | Interactive | Portfolio Detail |
| 7 | Material Board Carousel | Interactive | Portfolio Detail |
| 8 | Scroll-triggered Animations | Animation | Global |
| 9 | Sticky Sidebar | Layout | Blog Detail |
| 10 | Animated Counter | Animation | Home, About, Portfolio, Contact |
| 11 | Team Member Cards | Static | About |
| 12 | Services Grid / Icon Box | Static | About, Green Solution |
| 13 | Partner Logos Bar | Carousel | Home |
| 14 | Parallax Section | Visual | Home, Portfolio |
| 15 | Floating CTA Bar (Mobile) | Fixed | Global (mobile only) |
| 16 | Project Timeline / Milestone | Interactive | About |
| 17 | Video Popup / Modal | Interactive | Home, About, Green Solution |
| 18 | Back to Top Button | Fixed | Global |
| 19 | Cookie Consent Banner | Fixed | Global |
| 20 | Preloader | Animation | Global (first visit) |
| 21 | Breadcrumb Navigation | Navigation | Portfolio Detail, Blog Detail |
| **22** | **Reading Progress Bar** | **UI Feedback** | **Blog Detail** |
| **23** | **Card Flip Animation** | **Interactive** | **Home (4 Xanh)** |
| **24** | **Skeleton Loading** | **UI Feedback** | **Portfolio, Blog (filter)** |
| **25** | **Social Share Buttons** | **Interactive** | **Blog Detail** |
| **26** | **Search Autocomplete** | **Interactive** | **Blog** |
| **27** | **Table of Contents** | **Navigation** | **Blog Detail** |

---

## Component Specs Chi Tiết

### 1. Before/After Image Slider ⭐

**Thư viện gợi ý:** `img-comparison-slider` hoặc `twentytwenty`

```
┌─────────────────────────────────────┐
│  ┌────────────┬──│──────────────┐   │
│  │            │  │              │   │
│  │  BẢN VẼ   │◄─┤►  THỰC TẾ   │   │
│  │   3D      │  │   NGHIỆM    │   │
│  │            │  │   THU       │   │
│  └────────────┴──│──────────────┘   │
│              ← Kéo để so sánh →     │
└─────────────────────────────────────┘
```

| Thuộc tính | Giá trị |
|---|---|
| Touch/Swipe | ✅ Bắt buộc (mobile) |
| Keyboard | ✅ Arrow keys |
| Initial position | 50% |
| Label trái | "Concept 3D" |
| Label phải | "Thực tế bàn giao" |
| Responsive | Full-width container |

**Trang sử dụng:** `PAGE_HOME.md` (section 4), `PAGE_PORTFOLIO.md` (detail page)

---

### 2. Interactive Process Steps — Quy Trình 6 Bước

```
Desktop (Horizontal Stepper):
━━●━━━━━━●━━━━━━●━━━━━━●━━━━━━●━━━━━━●━━
  1       2       3       4       5       6
  Tư vấn  Thiết kế Ký kết  Thi công Bàn giao Bảo trì

Mobile (Vertical Timeline):
  ●── Tư vấn & Lắng nghe
  │
  ●── Thiết kế & Dự toán
  │
  ●── Ký kết hợp đồng
  │
  ●── Thi công & Giám sát
  │
  ●── Bàn giao & Nghiệm thu
  │
  ●── Bảo trì & Chăm sóc
```

| Thuộc tính | Giá trị |
|---|---|
| Animation | Dot sáng tuần tự khi scroll (IntersectionObserver) |
| Hover (desktop) | Expand nội dung chi tiết |
| Active color | `--color-accent` (#FF8A00) |
| Line color | `--color-gray-200` |
| Dot inactive | `--color-gray-400` |

---

### 10. Animated Counter / Stats

```
┌──────────────────────────────────────────┐
│  ┌────┐   ┌────┐   ┌────┐   ┌────┐      │
│  │100%│   │150+│   │24/7│   │ 10 │      │
│  │Không│   │Công│   │Bảo │   │Năm │      │
│  │phát │   │trình│   │hành│   │KN  │      │
│  │sinh │   │    │   │    │   │    │      │
│  └────┘   └────┘   └────┘   └────┘      │
│          Background: #14513D             │
└──────────────────────────────────────────┘
```

| Thuộc tính | Giá trị |
|---|---|
| Trigger | `IntersectionObserver` (threshold: 0.5) |
| Duration | 2000ms |
| Easing | `easeOutExpo` |
| Chỉ chạy 1 lần | ✅ |
| Number format | Thêm dấu `+` suffix nếu có |
| Background | `--color-primary` |
| Text color | `--color-white` |

**Trang sử dụng:** Home (sau section 3), About (mini), Portfolio (hero), Contact (mini)

---

### 11. Team Member Cards

```
┌─────────────┐
│   ┌─────┐   │
│   │ 📷  │   │
│   │ Ảnh │   │
│   └─────┘   │
│  Nguyễn A   │
│  KTS Trưởng │
│  ─────────  │
│  [FB] [Zalo]│  ← Hiện khi hover
│  Bio 2 dòng │  ← Hiện khi hover
└─────────────┘
```

| Thuộc tính | Giá trị |
|---|---|
| Ảnh | Tròn hoặc vuông bo góc `--radius-lg` |
| Hover | Scale 1.05 + hiện social + bio |
| Grid | 3-4 cột (desktop), 2 cột (tablet), 1 cột (mobile) |
| Background | `--color-light` |

---

### 13. Partner Logos Bar

| Thuộc tính | Giá trị |
|---|---|
| Thư viện | Swiper hoặc Splide |
| Autoplay | `true`, 3000ms delay |
| Loop | `true` |
| Slides per view | 5 (desktop), 3 (tablet), 2 (mobile) |
| Logo style | Grayscale → Color khi hover |
| Logo Partners | Dulux, An Cường, Schneider, Hafele... |

---

### 14. Parallax Section

| Thuộc tính | Giá trị |
|---|---|
| CSS | `background-attachment: fixed; background-size: cover` |
| Fallback mobile | `background-attachment: scroll` (iOS không hỗ trợ) |
| Overlay | `rgba(20, 81, 61, 0.7)` hoặc `rgba(0,0,0,0.5)` |
| Ảnh | Công trình panorama, WebP, lazy-loaded |
| Text | Trắng, centered |

**Trang sử dụng:** Home (section 2 + 8), Portfolio (CTA cuối)

---

### 15. Floating CTA Bar (Mobile Only)

```
┌──────────────────────────────┐
│  📞 Gọi ngay  │ 📋 Nhận DT  │
└──────────────────────────────┘
```

| Thuộc tính | Giá trị |
|---|---|
| Position | `fixed`, `bottom: 0`, `z-index: 999` |
| Hiển thị | Chỉ mobile (`max-width: 768px`) |
| Nút trái | `tel:` link → gọi điện |
| Nút phải | Scroll to form hoặc link trang Dự Toán |
| Background | `--color-primary` |
| Text | `--color-white` |
| Height | `56px` |
| Safe area | `padding-bottom: env(safe-area-inset-bottom)` |

---

### 17. Video Popup / Modal

| Thuộc tính | Giá trị |
|---|---|
| Trigger | Nút ▶ trên thumbnail/banner |
| Overlay | `rgba(0,0,0,0.85)` |
| Video source | YouTube/Vimeo iframe (lazy) |
| Close | Click overlay / nút X / Escape key |
| Video chỉ load khi | Click nút ▶ (performance) |
| Transition | Fade-in 300ms |

---

### 19. Cookie Consent Banner

| Thuộc tính | Giá trị |
|---|---|
| Position | Bottom, full-width |
| Text | "Website sử dụng cookies để cải thiện trải nghiệm..." |
| Buttons | "Đồng ý" (primary) / "Tùy chỉnh" (secondary) |
| Storage | `localStorage` key: `xanh_cookie_consent` |
| Hiện khi | Chưa có consent trong localStorage |

---

### 20. Preloader

| Thuộc tính | Giá trị |
|---|---|
| Hiển thị | Logo Xanh + animation xoay lá |
| Duration | 1.5-2s |
| Fade out | `opacity 0` → `display none` |
| Chỉ hiện | Lần đầu truy cập (`sessionStorage`) |

---

### 21. Breadcrumb

```
Trang Chủ > Dự Án > [Tên dự án]
Trang Chủ > Tin Tức > [Danh mục] > [Tiêu đề]
```

| Thuộc tính | Giá trị |
|---|---|
| Schema | `BreadcrumbList` (JSON-LD) |
| Separator | `>` hoặc `/` |
| Current page | Không có link, text muted |

---

### 22. Reading Progress Bar ★

```
┌═══════════════════════════════▓▓▓░░░░░░░░░░░░░░░░░░░░┐  ← fixed top
│                              45%                      │
└═══════════════════════════════════════════════════════┘
```

| Thuộc tính | Giá trị |
|---|---|
| Position | `fixed; top: 0; left: 0; z-index: 1000` |
| Height | 3px |
| Color | `--color-accent` (#FF8A00) |
| Calculation | `scrollTop / (scrollHeight - clientHeight) * 100%` |
| Performance | `requestAnimationFrame` throttle |
| Transition | `width: var(--transition-fast)` |
| Template | `components/reading-progress-bar.php` |

**Trang sử dụng:** Blog Detail (`single.php`)

---

### 23. Card Flip Animation ★

```
  Mặt trước (hover)       Mặt sau
┌─────────────┐         ┌─────────────┐
│   🌿 Icon   │  ──→    │  Xanh Chi   │
│             │  flip   │  phí: Minh  │
│  XANH CHI  │  180°   │  bạch, cam  │
│   PHÍ      │         │  kết 100%   │
│             │         │  không phát │
│             │         │  sinh...    │
└─────────────┘         └─────────────┘
```

| Thuộc tính | Giá trị |
|---|---|
| CSS | `transform: rotateY(180deg)` |
| Duration | 600ms |
| Easing | `cubic-bezier(0.4, 0, 0.2, 1)` |
| Trigger | `:hover` (desktop), `:focus` / tap (mobile) |
| Perspective | `1000px` trên parent |
| backface-visibility | `hidden` cho cả 2 mặt |
| Border on hover | `3px solid var(--color-accent)` |
| Template | `components/card-flip.php` |

**Trang sử dụng:** Home section 3 (Triết lý 4 Xanh)

---

### 24. Skeleton Loading ★

```
┌────────────────┐  ┌────────────────┐  ┌────────────────┐
│ ░░░░░░░░░░░░░░ │  │ ░░░░░░░░░░░░░░ │  │ ░░░░░░░░░░░░░░ │
│ ░░░░░░░░░░░░░░ │  │ ░░░░░░░░░░░░░░ │  │ ░░░░░░░░░░░░░░ │
│                │  │                │  │                │
│ ░░░░░░░░       │  │ ░░░░░░░░       │  │ ░░░░░░░░       │
│ ░░░░           │  │ ░░░░           │  │ ░░░░           │
└────────────────┘  └────────────────┘  └────────────────┘
```

| Thuộc tính | Giá trị |
|---|---|
| Animation | Shimmer gradient: `linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%)` |
| Background-size | `200% 100%` |
| Duration | `1.5s infinite` |
| Timing | Hiện khi AJAX loading, ẩn khi data trả về |
| Transition | Skeleton → Real cards: `opacity` crossfade 300ms |
| Shape | Giống shape của real card (aspect ratio, border-radius) |
| Template | `components/skeleton-loading.php` |

**Trang sử dụng:** Portfolio Grid (khi filter), Blog (khi filter)

---

### 25. Social Share Buttons ★

```
  Chia sẻ:  [📘 Facebook]  [💬 Zalo]  [🔗 Copy link]
```

| Thuộc tính | Giá trị |
|---|---|
| Platforms | Facebook, Zalo, Copy link |
| Facebook | `https://www.facebook.com/sharer/sharer.php?u={URL}` |
| Zalo | `https://zalo.me/share?url={URL}` |
| Copy | `navigator.clipboard.writeText(url)` + tooltip "Đã copy!" |
| Style | Icon buttons, pill shape, `--radius-full` |
| Hover | Scale 1.1 + shadow |
| Vị trí | Cuối bài viết, trước Related Articles |
| Template | `components/social-share.php` |

**Trang sử dụng:** Blog Detail (`single.php`)

---

### 26. Search Autocomplete ★

```
┌─────────────────────────────────────┐
│  🔍  Tìm kiếm bài viết...          │
├─────────────────────────────────────┤
│  📄 Gạch AAC giúp giảm 4°C...      │
│  📄 Top 5 vật liệu xanh 2026       │
│  📄 Chi phí xây nhà phố Nha Trang  │
└─────────────────────────────────────┘
```

| Thuộc tính | Giá trị |
|---|---|
| Trigger | Keyup (debounce 300ms) |
| Min characters | 2 |
| Max results | 5 |
| AJAX action | `xanh_search_posts` |
| Highlight | Bold matched text trong kết quả |
| Keyboard | Arrow up/down navigate, Enter select |
| Close | Click outside / Escape |
| No results | "Không tìm thấy bài viết phù hợp" |
| CSS | Dropdown shadow `--shadow-lg`, `z-index: 100` |
| Template | `components/search-autocomplete.php` |

**Trang sử dụng:** Blog Archive (`archive.php`)

---

### 27. Table of Contents (ToC) ★

```
┌─ Mục Lục ───────────────────┐
│  1. Tiêu đề H2 đầu tiên    │
│  2. Tiêu đề H2 thứ hai     │
│     2.1 Tiêu đề H3         │
│  3. Tiêu đề H2 thứ ba      │
└─────────────────────────────┘
```

| Thuộc tính | Giá trị |
|---|---|
| Auto-generate | JavaScript scan `h2, h3` trong `.entry-content` |
| Scroll-to | Smooth scroll khi click item |
| Active state | Highlight mục đang đọc (IntersectionObserver) |
| Collapsible | Toggle ẩn/hiện (mobile) |
| Position | Đầu bài viết, sau article header |
| ID generation | Slug từ text heading: `tiêu-đề-h2-đầu-tiên` |
| Template | `components/table-of-contents.php` |

**Trang sử dụng:** Blog Detail (`single.php`)

---

## Component → Page Matrix

| Component | Home | About | Portfolio | Giải Pháp | Blog | Liên Hệ |
|---|:---:|:---:|:---:|:---:|:---:|:---:|
| 1. Before/After Slider | ✅ | — | ✅ detail | — | — | — |
| 2. Process Steps | ✅ | — | — | — | — | — |
| 3. Trust Indicators | ✅ | — | ✅ | — | — | — |
| 4. Sticky Filter | — | — | ✅ | — | ✅ | — |
| 5. Accordion FAQ | — | — | — | — | — | ✅ |
| 6. Lightbox Gallery | — | — | ✅ detail | — | — | — |
| 7. Material Board | — | — | ✅ detail | — | — | — |
| 8. Scroll Animations | ✅ | ✅ | ✅ | ✅ | — | — |
| 9. Sticky Sidebar | — | — | — | — | ✅ detail | — |
| 10. Animated Counter | ✅ | ✅ mini | ✅ | — | — | ✅ mini |
| 11. Team Cards | — | ✅ | — | — | — | — |
| 12. Services/Icon Box | — | ✅ | — | ✅ | — | — |
| 13. Partner Logos | ✅ | — | — | — | — | — |
| 14. Parallax | ✅ ×2 | — | ✅ | — | — | — |
| 15. Floating CTA ☎ | ✅ all | ✅ | ✅ | ✅ | ✅ | ✅ |
| 16. Timeline | — | ✅ | — | — | — | — |
| 17. Video Popup | ✅ | ✅ | — | ✅ | — | — |
| 18. Back to Top | ✅ all | ✅ | ✅ | ✅ | ✅ | ✅ |
| 19. Cookie Consent | ✅ all | ✅ | ✅ | ✅ | ✅ | ✅ |
| 20. Preloader | ✅ all | ✅ | ✅ | ✅ | ✅ | ✅ |
| 21. Breadcrumb | — | — | ✅ detail | — | ✅ detail | — |
| **22. Reading Progress** ★ | — | — | — | — | **✅ detail** | — |
| **23. Card Flip** ★ | **✅** | — | — | — | — | — |
| **24. Skeleton Loading** ★ | — | — | **✅ filter** | — | **✅ filter** | — |
| **25. Social Share** ★ | — | — | — | — | **✅ detail** | — |
| **26. Search Autocomplete** ★ | — | — | — | — | **✅** | — |
| **27. Table of Contents** ★ | — | — | — | — | **✅ detail** | — |

---

## Scroll Animation Specs (Global)

| Effect | CSS/JS | Trigger | Duration |
|---|---|---|---|
| Fade-in up | `opacity: 0 → 1`, `translateY(20px → 0)` | IntersectionObserver | 600ms |
| Fade-in left/right | `translateX(±30px → 0)` | IntersectionObserver | 600ms |
| Scale-in | `scale(0.95 → 1)` | IntersectionObserver | 500ms |
| Draw SVG path | `stroke-dashoffset` animation | scroll position | 1000ms |
| Stagger children | Delay mỗi child 100ms | Parent visible | 100ms × N |

> **Nguyên tắc:** Nhẹ nhàng, tinh tế. KHÔNG dùng hiệu ứng rối mắt, quá nhanh.
> Tránh animation trên `prefers-reduced-motion: reduce`.

---

## Tài Liệu Liên Quan

- `CORE_ARCHITECTURE.md` — Component dependency map, template-parts structure
- `ARCH_DESIGN_TOKENS.md` — CSS variables cho tất cả components
- `ui_ux_enhancement_guide.md` — Tài liệu gốc chi tiết nâng cấp UX
- `FEATURE_MEDIA_GALLERY.md` — Specs chi tiết Gallery, Slider, Video
- `PAGE_*.md` — Áp dụng components vào từng trang cụ thể
