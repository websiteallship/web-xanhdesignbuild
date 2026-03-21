# PAGE_SERVICE_DETAIL — Trang Chi Tiết Dịch Vụ (ServiceDetailPage)

> **Dự án:** Website XANH - Design & Build
> **Template:** `page-service-{slug}.php` hoặc `single-xanh_service.php`
> **Ngày tạo:** 2026-03-19
> **Positioning:** Warm Luxury — Tinh tế, Ấm áp, Đẳng cấp
> **Tham chiếu:** [ARCH_LUXURY_VISUAL_DIRECTION.md](./ARCH_LUXURY_VISUAL_DIRECTION.md) | [GOV_BRAND_VOICE.md](./GOV_BRAND_VOICE.md) | [PAGE_ABOUT.md](./PAGE_ABOUT.md)

---

## Tổng Quan

- **Số trang:** 4 trang (mỗi dịch vụ 1 trang riêng)
- **Bố cục:** Storytelling Layout — `Aspiration → Empathy → Solution → Proof → Invitation`
- **Số sections:** 8 (Hero + Empathy + Features + Process + Portfolio + Testimonial + FAQ + CTA)
- **Tỷ lệ Brand Color:** 60% Green / 25% Beige / 10% White / 5% Orange (theo Brand Guide)
- **Global components:** Breadcrumb (#21), Floating CTA Mobile (#15), Back to Top (#18)

### 4 Dịch Vụ (Từ Homepage Section 4)

| # | Tên Dịch Vụ | Slug | Keyword Chính |
|---|---|---|---|
| 1 | Thiết Kế Kiến Trúc & Nội Thất | `thiet-ke-kien-truc` | thiết kế kiến trúc nội thất Nha Trang |
| 2 | Thi Công Xây Dựng Trọn Gói | `thi-cong-xay-dung` | thi công xây dựng trọn gói Nha Trang |
| 3 | Sản Xuất & Thi Công Nội Thất | `thi-cong-noi-that` | thi công nội thất cao cấp Nha Trang |
| 4 | Cải Tạo & Nâng Cấp | `cai-tao-nang-cap` | cải tạo sửa chữa nhà Nha Trang |

---

## Section 1: Hero Banner (Aspiration) — Full-screen Image

> **Layout giống `portfolio-detail.html`:** Full-screen background image + gradient overlay + nội dung text bottom-aligned.

```
┌──────────────────────────────────────────────────────┐
│                                                      │
│          [Background Image - Full Width]             │
│           + Gradient Overlay Bottom                  │
│                                                      │
│                                                      │
│                                                      │
│  ── Breadcrumb (bottom-left) ──────────────────────  │
│  Trang Chủ > Dịch Vụ > Thiết Kế Kiến Trúc           │
│                                                      │
│  H1: Thiết Kế Kiến Trúc & Nội Thất                  │    ← detail-hero__title
│  Eyebrow: Dịch Vụ Của Xanh — Design & Build         │    ← detail-hero__eyebrow
│  Tagline: Cá Nhân Hoá | Thẩm Mỹ | Phong Thuỷ       │    ← detail-hero__tagline
│                                                      │
└──────────────────────────────────────────────────────┘
```

### Content (Mỗi Dịch Vụ)

| Dịch Vụ | H1 | Eyebrow | Tagline |
|---|---|---|---|
| **Thiết Kế** | Thiết Kế Kiến Trúc & Nội Thất | Dịch Vụ Của Xanh — Design & Build | Cá Nhân Hoá · Thẩm Mỹ · Phong Thuỷ |
| **Thi Công** | Thi Công Xây Dựng Trọn Gói | Dịch Vụ Của Xanh — Design & Build | Đúng Tiến Độ · Không Phát Sinh · Giám Sát 3 Lớp |
| **Nội Thất** | Sản Xuất & Thi Công Nội Thất | Dịch Vụ Của Xanh — Design & Build | Xưởng Mộc Trực Tiếp · Vật Liệu An Cường · Tinh Xảo |
| **Cải Tạo** | Cải Tạo & Nâng Cấp Công Trình | Dịch Vụ Của Xanh — Design & Build | Bảo Tồn Ký Ức · Kiến Tạo Giá Trị Mới |

### UX/UI Specs

| Thuộc tính | Giá trị |
|---|---|
| **CSS Class** | `.detail-hero` (tái sử dụng từ portfolio-detail) |
| Height | `80vh` (min `500px`, max `800px`) — không full 100vh như Home |
| Background | Ảnh công trình thực XANH, `<img>` không background-image (SEO) |
| Image Dimensions | 1920×1080, WebP, preload LCP |
| Overlay | `.detail-hero__overlay` — gradient: `linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.1) 60%, transparent 100%)` |
| Text position | Bottom-aligned (`.detail-hero__bottom`), padding-bottom: `--space-12` |
| Breadcrumb | Schema `BreadcrumbList` JSON-LD (#21). Style: `.breadcrumb--hero` trắng, opacity 0.8 |
| H1 Typography | Inter Bold, `clamp(2rem, 5vw, 3.5rem)`, `letter-spacing: -0.02em`, `color: white` |
| Eyebrow | Uppercase, `letter-spacing: 0.1em`, Inter Regular 14px, `color: rgba(255,255,255,0.7)` |
| Tagline | Inter Regular 16px, `color: rgba(255,255,255,0.8)`, separator: `·` hoặc `|` |

### Animation

- Background image: Subtle `scale(1.03)` zoom-in trong 15s (CSS keyframe, không GSAP — performance)
- Text content: GSAP Fade Up stagger (Breadcrumb → H1 → Eyebrow → Tagline, mỗi cái delay 100ms)
- Duration: 800ms, easing: `power2.out`

---

## Section 2: Sự Thấu Hiểu (Empathy)

### Content (Ví dụ: Thiết Kế Kiến Trúc)

- **Eyebrow:** TẠI SAO CHỌN XANH?
- **Headline (H2):** *"Chúng Tôi Hiểu — Xây Dựng Tổ Ấm Là Quyết Định Lớn Nhất Đời Người."*
- **Body:** *"Bạn lo lắng: bản vẽ liệu có sát thực tế? Công năng có phù hợp lối sống? Phong thuỷ có hài hoà? Chi phí có trong tầm kiểm soát? Tại XANH, chúng tôi không chỉ vẽ bản vẽ — chúng tôi lắng nghe để tạo nên nơi thuộc về riêng bạn."*

### UX/UI Specs

| Thuộc tính | Giá trị |
|---|---|
| Background | `--color-beige` (#D8C7A3) — 25% Color Ratio, tạo chiều sâu ấm áp |
| Layout | Text căn giữa, `max-width: 65ch` (Luxury Reading Experience) |
| Padding | `min 80px` (desktop), `48px` (mobile) — Luxury Breathing Room |
| H2 Typography | Inter SemiBold, `clamp(1.5rem, 3vw, 2.5rem)`, `color: --color-primary` |
| Body Typography | Inter Regular, 16-18px, `line-height: 1.7`, `color: --text-dark/80` |

### Animation

- Eyebrow + H2 + Body: GSAP `anim-fade-up` stagger, `trigger: top 85%`

---

## Section 3: Giải Pháp & Lợi Ích (Solution — Features Grid)

### Content (Ví dụ: Thiết Kế Kiến Trúc)

- **Section Eyebrow:** NĂNG LỰC CỐT LÕI
- **Headline (H2):** *"Từ Ý Tưởng Đến Bản Vẽ Hoàn Hảo — Mọi Đường Nét Đều Có Lý Do"*

| # | Icon | Tiêu đề | Mô tả |
|---|---|---|---|
| 1 | `compass` | Tư Vấn Phong Thuỷ & Công Năng | Phân tích hướng nhà, luồng khí, ánh sáng tự nhiên trước khi đặt bút |
| 2 | `layers` | Thiết Kế 3D Photo-realistic | Bản vẽ 3D sát thực — cam kết 98% khớp thực tế khi thi công |
| 3 | `shield-check` | Hồ Sơ Kỹ Thuật Đầy Đủ | Bản vẽ MEP, kết cấu, shop drawing — đảm bảo không phát sinh |
| 4 | `pen-tool` | Cá Nhân Hoá Từng Không Gian | Mỗi thiết kế là bản duy nhất — phản ánh phong cách sống của gia chủ |
| 5 | `refresh-ccw` | Chỉnh Sửa Không Giới Hạn | Lắng nghe, điều chỉnh đến khi bạn hoàn toàn hài lòng |
| 6 | `award` | Đội Ngũ KTS Giàu Kinh Nghiệm | Kiến trúc sư chính quy, tận tâm, đã hoàn thành 47+ công trình |

### UX/UI Specs

| Thuộc tính | Giá trị |
|---|---|
| Background | `--color-white` (#FFFFFF) — 10% Color Ratio, khoảng thở |
| Component | Services Grid / Icon Box (#12) |
| Layout | Grid 3 cột (desktop), 2 cột (tablet), 1 cột (mobile) |
| Card Style | Nền `--color-light`, `border-radius: --radius-md`, padding: `--card-padding` (32px) |
| Icon | Lucide icons, `48×48px`, `color: --color-primary` |
| Hover | `translateY(-4px)` + `--card-shadow-hover` — 400ms `--ease-out` |
| Gap | `--space-6` (24px) |

### Animation

- Cards: Staggered cascading — GSAP `opacity: 0→1`, `y: 30→0`, stagger: 100ms, `trigger: top 85%`

---

## Section 4: Quy Trình Thực Hiện (Process)

### Content (Ví dụ: Thiết Kế Kiến Trúc)

- **Eyebrow:** QUY TRÌNH LÀM VIỆC
- **Headline (H2):** *"Minh Bạch Từng Bước — Đồng Hành Từng Giai Đoạn"*

| Bước | Tiêu đề | Mô tả ngắn |
|---|---|---|
| 01 | Lắng Nghe & Khảo Sát | Gặp gỡ gia chủ, tìm hiểu nhu cầu, khảo sát hiện trạng khu đất |
| 02 | Phương Án Sơ Bộ | Đề xuất 2-3 phương án mặt bằng + phong cách, phân tích phong thuỷ |
| 03 | Phát Triển Thiết Kế | Bản vẽ 3D photo-realistic, gia chủ duyệt từng không gian |
| 04 | Hồ Sơ Kỹ Thuật | Bản vẽ kỹ thuật đầy đủ (kết cấu, MEP, shop drawing) + dự toán chi tiết |
| 05 | Bàn Giao & Hỗ Trợ | Bàn giao hồ sơ, hỗ trợ chọn nhà thầu hoặc chuyển tiếp thi công nội bộ |

### UX/UI Specs

| Thuộc tính | Giá trị |
|---|---|
| Background | `--color-primary` (#14513D) — 60% Color Ratio |
| Text color | `--color-white` |
| Layout Desktop | **Horizontal Stepper** — dots nối bằng line (tham chiếu Component #2) |
| Layout Mobile | **Vertical Timeline** — dots dọc + text |
| Active dot | `--color-accent` (#FF8A00) |
| Inactive line | `rgba(255,255,255,0.2)` |
| Step number | Font Inter Bold, 14px, background `--color-accent`, border-radius full |

### Animation

- Dots sáng tuần tự khi scroll (`IntersectionObserver`)
- Content mỗi bước: Fade-in stagger 150ms

---

## Section 5: Dự Án Tiêu Biểu (Proof — Related Portfolio)

### Content

- **Section Eyebrow:** TÁC PHẨM THỰC TẾ
- **Headline (H2):** *"Mỗi Tác Phẩm Là Một Câu Chuyện — Hãy Để Chúng Lên Tiếng"*
- **CTA:** `[Khám Phá Thêm Tác Phẩm]` — Outline button

### UX/UI Specs

| Thuộc tính | Giá trị |
|---|---|
| Background | `--color-white` (#FFFFFF) — 10% Color Ratio, khoảng thở |
| Layout | Grid 3 cột (desktop), 2 cột (tablet), 1 cột (mobile) |
| Data source | ACF Relationship → CPT `xanh_portfolio`, filter theo dịch vụ tương ứng |
| Số lượng | 3-6 dự án nổi bật |
| Card Style | Portfolio Card chuẩn (ảnh + tên dự án + loại hình + vị trí) |
| Image Style | Aspect ratio 4:3, `overflow: hidden`, hover `scale(1.03)` 600ms |
| Photography | **Ảnh thực 100%**, tông ấm, Editorial Style, KHÔNG ảnh stock |

### Animation

- Cards: Stagger `anim-fade-up`, 100ms delay mỗi card

---

## Section 6: Khách Hàng Nói Gì (Testimonial)

### Content

- **Section Eyebrow:** GIA CHỦ NÓI GÌ VỀ XANH
- **Headline (H2):** *"Sự Hài Lòng Là Thước Đo Duy Nhất"*
- Trích dẫn từ gia chủ đã sử dụng dịch vụ này

### UX/UI Specs

| Thuộc tính | Giá trị |
|---|---|
| Background | `--color-beige` (#D8C7A3) — 25% Color Ratio |
| Layout | Carousel (Swiper) hoặc Single Large Quote |
| Card Style | Ảnh tròn 80px, quote italic, tên + địa điểm |
| Font quote | Inter Italic, `--text-body-lg`, `line-height: 1.8` |
| Data source | CPT `xanh_testimonial`, filter theo dịch vụ |

---

## Section 7: Câu Hỏi Thường Gặp (FAQ)

### Content (Ví dụ: Thiết Kế Kiến Trúc)

- **Headline (H2):** *"Câu Hỏi Thường Gặp"*

| # | Câu Hỏi |
|---|---|
| 1 | Chi phí thiết kế kiến trúc được tính như thế nào? |
| 2 | Thời gian hoàn thành hồ sơ thiết kế là bao lâu? |
| 3 | Bản vẽ 3D có giống thực tế 100% không? |
| 4 | Nếu không hài lòng với thiết kế, XANH hỗ trợ chỉnh sửa thế nào? |
| 5 | XANH có thiết kế cho nhà phố/chung cư không, hay chỉ biệt thự? |

### UX/UI Specs

| Thuộc tính | Giá trị |
|---|---|
| Background | `--color-white` |
| Component | Accordion FAQ (#5) |
| Schema | `FAQPage` JSON-LD (SEO Rich Snippets) |
| Hover | Background subtle highlight |
| Animation | Expand 300ms ease, chỉ 1 item mở mỗi lần |
| Data source | ACF Repeater: `service_faqs` (question + answer) |

---

## Section 8: CTA Kết Thúc (Invitation)

### Content

- **Headline (H2):** *"Hành Trình Bắt Đầu Bằng Một Cuộc Trò Chuyện"*
- **Body:** *"Đặt lịch trao đổi riêng — để mỗi không gian được lắng nghe, và để bạn an tâm từ bước đầu tiên."*
- **Primary CTA:** `[Đặt Lịch Tư Vấn Riêng]` — Cam `--color-accent` (KHÔNG dùng "Liên hệ ngay")
- **Secondary CTA:** `[Khám Phá Dự Toán Của Bạn]` — Outline white

### UX/UI Specs

| Thuộc tính | Giá trị |
|---|---|
| Background | Gradient: `linear-gradient(135deg, #14513D 0%, #0a2e22 100%)` — 60% Green |
| Text | `--color-white`, centered |
| Primary CTA | `--btn-primary-bg` (Cam #FF8A00 — 5% accent), min-width 240px |
| Secondary CTA | Border white, text white, hover fill white |
| Padding | `--space-24` vertical — Generous Breathing Room |

### Animation

- Content: GSAP Fade Up, `trigger: top 85%`

---

## Cấu Trúc Dữ Liệu — ACF Fields

### ACF Group: `group_service_detail`

| Tab | Field | Type | Notes |
|---|---|---|---|
| **Hero** | `hero_image` | Image (1920×1080) | Preload LCP |
| | `hero_title` | Text | H1 — Tên dịch vụ |
| | `hero_eyebrow` | Text | Dòng nhỏ phía dưới H1 |
| | `hero_tagline` | Text | Các USP ngắn, phân cách bằng `·` |
| **Empathy** | `empathy_headline` | Text | H2 — Sự thấu hiểu |
| | `empathy_content` | Wysiwyg | Nội dung đồng cảm |
| **Features** | `features_headline` | Text | H2 |
| | `features` | Repeater | → `icon` (Text/Select), `title` (Text), `description` (Textarea) |
| **Process** | `process_headline` | Text | H2 |
| | `process_steps` | Repeater | → `step_title` (Text), `step_desc` (Textarea) |
| **Portfolio** | `related_projects` | Relationship → `xanh_portfolio` | Filter theo taxonomy `service_type` |
| **Testimonial** | `related_testimonials` | Relationship → `xanh_testimonial` | 1-3 testimonials |
| **FAQ** | `service_faqs` | Repeater | → `question` (Text), `answer` (Wysiwyg). Schema `FAQPage` |
| **CTA** | `cta_headline` | Text | Optional override |
| | `cta_body` | Textarea | Optional override |

---

## Color Rotation — Brand Ratio Check

```
┌── S1: HERO ──────────── #14513D img + overlay     60% ── FULL IMPACT
├── S2: EMPATHY ──────── #D8C7A3 (beige)            25% ── WARM
├── S3: FEATURES ─────── #FFFFFF (white)             10% ── BREATHE
├── S4: PROCESS ──────── #14513D (primary)           60% ── DEEP
├── S5: PORTFOLIO ────── #FFFFFF (white)             10% ── BREATHE
├── S6: TESTIMONIAL ──── #D8C7A3 (beige)            25% ── WARM
├── S7: FAQ ──────────── #FFFFFF (white)             10% ── BREATHE
└── S8: CTA ──────────── #14513D → #0a2e22 (grad)   60% ── CLOSE
```

> **Kết quả:** Green 3/8 ≈ 37% + Hero overlay ≈ **55-60%** ✅ | Beige 2/8 ≈ **25%** ✅ | White 3/8 ≈ **~15%** ✅ | Orange: CTA + accents ≈ **5%** ✅

---

## SEO Requirements

| Yếu tố | Giá trị |
|---|---|
| **Title Tag** | `{Tên DV} — XANH Design & Build \| Nha Trang` (≤60 ký tự) |
| **Meta Description** | Mô tả USP dịch vụ + CTA (≤155 ký tự) |
| **H1** | Unique mỗi trang, chứa keyword chính |
| **Schema** | `Service` JSON-LD + `BreadcrumbList` + `FAQPage` |
| **Internal Links** | Link sang Portfolio liên quan, trang Liên Hệ, Blog bài viết liên quan |
| **Canonical** | `https://xanhdesignbuild.com/dich-vu/{slug}/` |
| **Open Graph** | Ảnh hero dịch vụ, title, description |

---

## Tài Liệu Liên Quan

- `ARCH_LUXURY_VISUAL_DIRECTION.md` — Visual direction, micro-interactions, color ratio
- `GOV_BRAND_VOICE.md` — Warm luxury tone, CTA patterns, từ khoá cấm
- `GOV_UX_GUIDELINES.md` — Mobile-first, animation guidelines, form UX
- `ARCH_UI_PATTERNS.md` — Component specs (#2, #5, #10, #12, #21)
- `PAGE_HOME.md` — Services section gốc (Section 4)
- `FEATURE_IMAGE_SPECS.md` — Image dimensions, crop ratios
- `CORE_DATA_MODEL.md` — CPT & Taxonomy definitions
