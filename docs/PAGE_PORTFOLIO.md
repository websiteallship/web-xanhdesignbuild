# PAGE_PORTFOLIO — Trang Dự Án (Portfolio)

> **Dự án:** Website XANH - Design & Build
> **Templates:** `archive-xanh_project.php` + `single-xanh_project.php`
> **Ngày tạo:** 2026-03-12
> **Tham chiếu:** [Portfolio.md](./page_dev_guide/Portfolio.md) | [ui_ux_enhancement_guide.md](./ui_ux_enhancement_guide.md)

---

## Tổng Quan

- **Vai trò:** Trang **trọng tâm uy tín** — mỗi dự án là một Landing Page thu nhỏ
- **2 views:** Grid Page (listing) + Detail Page (single project)
- **CPT:** `xanh_project` | **Taxonomies:** `project_type`, `project_status`

---

## GRID PAGE (archive-xanh_project.php)

### Section 1: Hero + Counter Strip

| Thuộc tính | Giá trị |
|---|---|
| **Headline** | *"Tác Phẩm Thực Tế. Giá Trị Khởi Nguồn Từ Sự Thật."* |
| **Sub-headline** | *"Một dự án của Xanh chỉ thực sự hoàn hảo khi nó bước ra đời thực, đúng ngân sách, đúng tiến độ..."* |
| Background | Nền sáng, Minimalist |
| **Counter strip (#10)** | Ngang dưới sub-headline: "X+ Dự án ⎮ X% Sát 3D ⎮ 0% Phát sinh" |
| Counter style | `--color-primary` text, animation count-up |

### Section 2: Filter Bar

| Thuộc tính | Giá trị |
|---|---|
| **Tabs trạng thái** | Tất cả ⎮ Đã bàn giao ⎮ Đang thi công ⎮ Concept 3D |
| **Lọc loại hình** | Biệt thự ⎮ Nhà phố ⎮ Căn hộ ⎮ Nghỉ dưỡng |
| Behavior | **Sticky** khi cuộn |
| Sticky style | `backdrop-filter: blur(10px)` — glassmorphism nhẹ |
| Filtering | **AJAX / Isotope** — transition mượt, không reload |
| **View Toggle** | Grid view ⎮ List view (icon toggle) |
| Mobile | Horizontal scroll pills |

### Section 3: Project Grid

| Thuộc tính | Giá trị |
|---|---|
| Layout | **Masonry Grid** hoặc Grid 3 cột |
| Card content | Ảnh thumbnail (thực tế) + Tên dự án + Tagline |
| Tagline mẫu | *"Hoàn thiện sát 3D 98% ⎮ 0% Phát sinh chi phí"* |
| **Tag badge** | Góc trên card: 🟢 Đã bàn giao / 🟡 Đang thi công / ⚪ Concept |
| Hover | Zoom 1.05x + overlay tối + nút `[Xem câu chuyện dự án]` |
| **Skeleton loading** | Khi filter: hiện skeleton placeholder → fade-in |
| Load More | AJAX `[Xem thêm dự án ⬇️]` |
| Mobile | 1 cột full-width |

### Section 4: CTA Cuối

| Thuộc tính | Giá trị |
|---|---|
| **CTA 1** | `[Sử Dụng Công Cụ Dự Toán Xanh]` → Trang Dự Toán |
| **CTA 2** | `[Chat với Kỹ sư trưởng]` → Zalo OA |
| Background | **Parallax (#14)**: Ảnh panorama công trình, overlay `--color-primary` |

---

## DETAIL PAGE (single-xanh_project.php) ⭐

> **Đây là trang quan trọng nhất.** Mỗi dự án là một Landing Page thu nhỏ kể về hành trình hiện thực hóa ngôi nhà.

### Section D1: Breadcrumb (#21)

```
Trang Chủ > Dự Án > [Tên dự án]
```
Schema: `BreadcrumbList` (JSON-LD)

### Section D2: Hero Image

| Thuộc tính | Giá trị |
|---|---|
| Image | Featured image (ảnh thực tế đẹp nhất) |
| Aspect ratio | 16:9 hoặc 21:9 panorama |
| Overlay | Gradient bottom → title visible |

### Section D3: Stats Bar — Thông Số Minh Bạch

```
📍 Nha Trang  |  📐 120m²  |  🏗 2 tầng  |  ⏱ 120 ngày  |  💰 2.5 Tỷ VNĐ
                                                             0% Phát sinh
```

| Thuộc tính | Giá trị |
|---|---|
| Layout | Thanh ngang (hoặc grid 5 cột) |
| Style | **Icon SVG + Number** animation counter |
| ACF Fields | `project_location`, `project_area`, `project_floors`, `project_duration`, `project_budget` |
| Highlight | `project_cost_overrun` = "0%" hiển thị màu `--color-success` |

### Section D4: Câu Chuyện Dự Án (Bài Toán & Lời Giải)

| Thuộc tính | Giá trị |
|---|---|
| Layout | 2 cột: **Bài toán** (trái) ⎮ **Lời giải** (phải). Hoặc Timeline |
| Content | ACF fields `project_client_story` + `project_solution` |
| Mẫu bài toán | *"Anh Hoàng tìm đến Xanh với khu đất hướng Tây cực nóng, ngân sách tối đa 2.5 tỷ..."* |
| Mẫu lời giải | *"Ứng dụng gạch bông gió mặt tiền + hệ lam chắn nắng..."* |
| Typography | Headline h2 cho mỗi cột. Body Inter |

### Section D5: Before/After Image Slider (#1) ⭐

| Thuộc tính | Giá trị |
|---|---|
| Component | `img-comparison-slider` hoặc `twentytwenty` |
| Left image | ACF `project_before_image` (Concept 3D) |
| Right image | ACF `project_after_image` (Thực tế) |
| Label | "Concept 3D" ⎮ "Thực tế nghiệm thu" |
| Help text | "Kéo để so sánh" |
| Touch/Swipe | ✅ Bắt buộc |
| Full-width | Container max |

### Section D6: Material Board (#7)

| Thuộc tính | Giá trị |
|---|---|
| Layout | **Horizontal scroll cards** với snap points |
| CSS | `scroll-snap-type: x mandatory` |
| Card content | Ảnh vật liệu + Tên + Tooltip "Vì sao Xanh?" khi hover |
| Data source | ACF Repeater `project_materials` |
| Mobile | Swipe horizontal |

### Section D7: Real Gallery (#6) + Video

| Thuộc tính | Giá trị |
|---|---|
| Layout | Lưới ảnh Grid full-width |
| Lightbox | **PhotoSwipe** hoặc **GLightbox** |
| Features | Swipe (mobile) + keyboard arrows (desktop) + thumbnail strip |
| Data source | ACF Gallery `project_gallery` |
| Video | ACF `project_video_url` → Video Popup (#17) nếu có |
| Image format | WebP, lazy-loaded |

### Section D8: Testimonial

| Thuộc tính | Giá trị |
|---|---|
| Content | Lời nhận xét thực tế chủ nhà (tập trung vận hành) |
| Style | Block `--color-light`, font italic, ảnh gia đình |
| Data source | ACF Post Object `testimonial_project` liên kết |

### Section D9: Related Projects

| Thuộc tính | Giá trị |
|---|---|
| Content | 3 dự án liên quan (cùng `project_type`) |
| Layout | Carousel 3 cards |
| Auto-suggest | Query cùng taxonomy term |

### Section D10: CTA

| Thuộc tính | Giá trị |
|---|---|
| **Headline** | *"Bạn cũng muốn một không gian sống trọn vẹn và minh bạch như thế này?"* |
| **CTA 1** | `[Sử Dụng Công Cụ Dự Toán Xanh]` |
| **CTA 2** | `[Chat với Kỹ sư trưởng]` → Zalo OA |

---

## Tài Liệu Liên Quan

- `page_dev_guide/Portfolio.md` — Copywriting gốc
- `CORE_DATA_MODEL.md` — CPT `xanh_project`, ACF fields
- `FEATURE_MEDIA_GALLERY.md` — Gallery, Slider, Video specs
- `ARCH_UI_PATTERNS.md` — Before/After, Lightbox, Material Board
