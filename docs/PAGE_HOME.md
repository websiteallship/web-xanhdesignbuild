# PAGE_HOME — Trang Chủ (HomePage)

> **Dự án:** Website XANH - Design & Build
> **Template:** `front-page.php`
> **Ngày tạo:** 2026-03-12
> **Tham chiếu:** [HomePage.md](./page_dev_guide/HomePage.md) | [ui_ux_enhancement_guide.md](./ui_ux_enhancement_guide.md)

---

## Tổng Quan

- **Bố cục:** Storytelling Layout — Dẫn dắt từ cảm xúc → nỗi đau → giải pháp → bằng chứng → hành động
- **Số sections:** 11 (8 gốc + 3 bổ sung bao gồm Lĩnh Vực Thực Hiện)
- **Global components:** Preloader (#20), Floating CTA Mobile (#15), Back to Top (#18), Cookie Consent (#19)

---

## Section 1: Hero — The Hook (100vh)

### Content
- **Headline:** *"Đừng Chỉ Xây Một Ngôi Nhà. Hãy Xây Dựng Sự Bình Yên."*
- **Sub-headline:** *"Tại Xanh, chúng tôi tin rằng hành trình kiến tạo tổ ấm không nên bắt đầu bằng sự lo âu..."*
- **CTA:** `[Lắng Nghe Câu Chuyện Của Xanh]` — Cam #FF8A00

### UX/UI Specs
| Thuộc tính | Giá trị |
|---|---|
| Background | Video/ảnh nền: gia đình bình yên + timelapse thi công |
| Overlay | `rgba(0,0,0,0.4)` |
| Height | `100vh` (hero full-screen) |
| Typography | Headline: Inter Bold, `--text-hero` |
| Text color | `--color-white` |
| CTA Button | Cam `--color-accent`, min-width 200px, border-radius `--radius-sm` |
| Preloader (#20) | Logo Xanh + xoay lá → fade out vào Hero. Chỉ lần đầu (`sessionStorage`) |

### Animation
- Headline: Fade-in từ dưới, delay 0.5s (sau preloader)
- Sub-headline: Fade-in, delay 0.8s
- CTA: Fade-in + subtle pulse, delay 1.2s

---

## Section 2: Nỗi Trăn Trở — Empathy

### Content
- **Headline:** *"Chúng Tôi Từng Thấy Những Ngôi Nhà Đánh Cắp Nụ Cười Của Gia Chủ..."*
- **Body:** Đoạn text cảm xúc về góc khuất ngành xây dựng (bảng dự toán "chào mồi", vật liệu kém, bảo hành im lặng...)
- **Câu chốt:** *"Đó không phải là cách một tổ ấm được sinh ra. Và đó là lúc, **Xanh** chọn đi một con đường khác."*

### UX/UI Specs
| Thuộc tính | Giá trị |
|---|---|
| Background | **Parallax (#14)**: Ảnh công trường bừa bộn cố định, text cuộn phía trên |
| Overlay | `rgba(30, 30, 30, 0.7)` trên parallax |
| Text color | `--color-white` hoặc `--color-beige` |
| Font body | Inter Regular, `--text-body-lg` |
| Padding | `--space-24` vertical |

### Animation
- Text blocks: Fade-in tuần tự từng đoạn khi scroll

---

## Section 3: Triết Lý "4 Xanh" — Core Values

### Content
- **Headline:** *"Con Đường Của Xanh: Xây Bằng Sự Tử Tế & Tầm Nhìn Thế Hệ"*
- 4 Cards: Chi phí / Vật liệu / Vận hành / Giá trị (với icon + mô tả)

### UX/UI Specs
| Thuộc tính | Giá trị |
|---|---|
| Layout | Grid 4 cột (desktop), 2 cột (tablet), 1 cột (mobile) |
| Card style | **Card flip** — Mặt trước: icon + tên. Mặt sau: mô tả chi tiết |
| Hover | Viền Cam `--color-accent` khi hover |
| Background section | `--color-white` |
| Icons | SVG custom 4 chiếc (Đồng xu, Lá cây, Nắng, Bắt tay) |

### Animation
- Cards: Stagger fade-in (100ms delay mỗi card)
- Card flip: CSS `transform: rotateY(180deg)` trên hover

---

## ★ Section 3.5: Animated Counter (#10)

### Content
4 con số: **"100% Không Phát Sinh"** / **"X+ Công Trình"** / **"24/7 Bảo Hành"** / **"X Năm Kinh Nghiệm"**

### UX/UI Specs
| Thuộc tính | Giá trị |
|---|---|
| Background | `--color-primary` (#14513D) full-width |
| Text | `--color-white`, font Inter Bold |
| Number size | `--text-h1` |
| Label size | `--text-small` |
| Layout | Grid 4 cột (desktop), 2×2 (mobile) |
| Animation | Count-up 2000ms, easeOutExpo, `IntersectionObserver` |
| ACF Fields | `counter_projects`, `counter_years` (dynamic) |

---

## ★ Section 4: Lĩnh Vực Thực Hiện (Services)

### Content
- **Headline:** *"Giải Pháp Khép Kín. Dấu Ấn Độc Bản."*
- **Sub-headline:** *"Từ ý tưởng kiến trúc sơ khai đến khi trao tay chiếc chìa khóa tổ ấm — Xanh đồng hành cùng bạn trong mọi giai đoạn."*
- 4 Cards Dịch Vụ chính: Thiết kế Kiến trúc & Nội thất, Thi công Xây dựng Trọn gói, Sản xuất & Thi công Nội thất, Cải tạo & Nâng cấp.

### UX/UI Specs
| Thuộc tính | Giá trị |
|---|---|
| Background | `--color-beige-light` (#F5F0E8) để cân bằng tỷ lệ 25% Beige |
| Component | Services Grid / Icon Box (#12) |
| Layout | Grid 3 cột (desktop), 1 cột (mobile) |
| Card Style | Nền trắng, shadow nhẹ, icon vector outline thanh mảnh |
| Hover | Scale ảnh nhẹ, nhích lên (translateY), chữ chuyển màu Cam `--color-accent` |

### Animation
- Cards: Stagger fade-in up khi cuộn đến (100ms delay mỗi card)

---

## Section 5: Proof of Concept — Before/After

### Content
- **Headline:** *"Những Câu Chuyện Đã Được Viết Nên Bằng Sự Thật"*
- Before/After Slider cho 1-2 dự án tiêu biểu
- Quote nhỏ kể bài toán gia chủ
- **CTA:** `[Khám Phá Các Tác Phẩm Khác]`

### UX/UI Specs
| Thuộc tính | Giá trị |
|---|---|
| Component | Before/After Image Slider (#1) |
| Touch | ✅ Swipe support (mobile) |
| Ảnh | ACF fields `project_before_image`, `project_after_image` |
| Label | "Concept 3D" / "Thực tế bàn giao" |
| Data source | ACF Relationship: `featured_projects` |

### Animation
- Subtle zoom on scroll cho thumbnail bên cạnh slider

---

## Section 6: Công Cụ Dự Toán — Lead Capture

### Content
- **Headline:** *"Hãy Để Sự Minh Bạch Bắt Đầu Ngay Lúc Này"*
- Form dự toán ngắn gọn (Fluent Form)
- **CTA:** `[Nhận Bản Dự Toán Tham Khảo Ngay]` — Cam

### UX/UI Specs
| Thuộc tính | Giá trị |
|---|---|
| Form plugin | Fluent Form |
| Fields | Loại hình (dropdown), Diện tích (number), Gói VL (radio), SĐT (tel) |
| Background | `--color-light` |
| Form style | Floating labels, real-time validation |
| Progress | Micro-animation progress bar khi điền |
| CTA pulse | Subtle pulse animation liên tục |

**Chi tiết:** Xem `FEATURE_ESTIMATOR.md`

---

## Section 7: Quy Trình 6 Bước — Process Steps (#2)

### Content
- **Headline:** *"Chúng Tôi Đồng Hành Cùng Bạn Đến Từng Viên Gạch Cuối Cùng"*
- 6 bước: Tư vấn → Thiết kế → Ký kết → Thi công → Bàn giao → Bảo trì

### UX/UI Specs
| Thuộc tính | Giá trị |
|---|---|
| Desktop | **Horizontal stepper** — 6 dots nối bằng line |
| Mobile | **Vertical timeline** (#16) — dots dọc + text |
| Active color | `--color-accent` |
| Inactive | `--color-gray-400` |
| Animation | Dots sáng tuần tự khi scroll (IntersectionObserver) |
| Hover (desktop) | Expand mô tả chi tiết bước |

---

## Section 8: Testimonials — Social Proof

### Content
- **Headline:** *"Chủ Nhà Nói Gì Về Xanh?"*
- 3-4 trích dẫn ngắn + ảnh chủ nhà tại ngôi nhà
- **Video Popup (#17):** Nút ▶ xem video phỏng vấn (nếu có)

### UX/UI Specs
| Thuộc tính | Giá trị |
|---|---|
| Layout | Carousel hoặc Grid quotes |
| Card style | Ảnh tròn, quote italic, tên + địa điểm |
| Video Popup | Overlay rgba(0,0,0,0.85), lazy load video khi click |
| Data source | CPT `xanh_testimonial` |
| Font | Inter Italic cho quote |

---

## ★ Section 8.5: Partner Logos Bar (#13)

### UX/UI Specs
| Thuộc tính | Giá trị |
|---|---|
| Library | Swiper hoặc Splide |
| Autoplay | 3000ms, loop: true |
| Slides | 5 (desktop), 3 (tablet), 2 (mobile) |
| Logo style | Grayscale → Color on hover |
| Background | `--color-white` hoặc `--color-light` |
| Data source | ACF Gallery: `partner_logos` |
| Partners | Dulux, An Cường, Schneider, Hafele... |

---

## Section 9: CTA Kết Thúc — The Invitation

### Content
- **Headline:** *"Cùng Xanh, Viết Tiếp Câu Chuyện Của Riêng Bạn"*
- **CTA 1:** `[Gặp Gỡ Đội Ngũ Của Xanh — Mức Phí 0 Đồng]`
- **CTA 2:** `[Tải Cẩm Nang: "Xây Nhà Không Phát Sinh Chi Phí"]`

### UX/UI Specs
| Thuộc tính | Giá trị |
|---|---|
| Background | **Parallax (#14)**: Ảnh panorama công trình đẹp nhất |
| Overlay | `rgba(20, 81, 61, 0.75)` |
| Text | `--color-white`, centered |
| CTA 1 | `--color-accent`, full-width mobile |
| CTA 2 | Outline white, text link style |

---

## Global Components (Toàn trang)

| Component | Specs |
|---|---|
| **Floating CTA (#15)** | Fixed bottom mobile: "📞 Gọi ngay" + "📋 Nhận DT". `z-index: 999`. Chỉ `max-width: 768px` |
| **Back to Top (#18)** | Tròn, góc phải dưới, hiện khi scroll > 500px. `scrollTo` smooth |
| **Cookie Consent (#19)** | Bottom bar, "Đồng ý" + "Tùy chỉnh". `localStorage` |
| **Preloader (#20)** | Logo Xanh + animation. 1.5-2s. `sessionStorage` first-visit only |

---

## Tài Liệu Liên Quan

- `page_dev_guide/HomePage.md` — Copywriting gốc chi tiết
- `ui_ux_enhancement_guide.md` — UI enhancements gốc
- `ARCH_UI_PATTERNS.md` — Component specs kỹ thuật
- `FEATURE_ESTIMATOR.md` — Form Dự Toán chi tiết
- `CORE_DATA_MODEL.md` — ACF fields cho Homepage
