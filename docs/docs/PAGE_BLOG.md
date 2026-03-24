# PAGE_BLOG — Trang Tin Tức & Cẩm Nang

> **Dự án:** Website XANH - Design & Build
> **Templates:** `archive.php` + `single.php` + `sidebar.php`
> **Ngày tạo:** 2026-03-12
> **Tham chiếu:** [Blog Tin Tức.md](./page_dev_guide/Blog%20Tin%20Tức.md) | [ui_ux_enhancement_guide.md](./ui_ux_enhancement_guide.md)

---

## Tổng Quan

- **Vai trò:** Kênh **SEO + giáo dục + thu thập Lead**
- **2 views:** List Page (archive) + Detail Page (single post)
- **Taxonomy:** `category` (4 danh mục)

---

## LIST PAGE (archive.php)

### Section 1: Hero + Search

| Thuộc tính | Giá trị |
|---|---|
| **Headline** | *"Cẩm Nang Xây Dựng & Không Gian Sống Bền Vững."* |
| **Sub-headline** | *"Trở thành chuyên gia cho chính ngôi nhà của bạn..."* |
| **Search Bar** | Lớn, giữa màn hình. Placeholder nhấp nháy gợi ý |
| **Autocomplete** | Gõ → dropdown gợi ý bài viết liên quan |
| Icon 🔍 | Animation pulse nhẹ |

### Section 2: Category Tabs (#4)

| Thuộc tính | Giá trị |
|---|---|
| Style | **Sticky Pill Buttons** |
| Tabs | Tất cả ⎮ 📘 Kinh Nghiệm Xây Nhà ⎮ 🧱 Vật Liệu Xanh ⎮ 🌿 Xu Hướng ⎮ 👷 Nhật Ký Xanh |
| **Count badge** | Mỗi tab hiện số bài: `Vật Liệu (12)` |
| Active tab | Underline animation slide |
| Filtering | AJAX, không reload |
| Mobile | Horizontal scroll |

### Section 3: Featured Articles

| Thuộc tính | Giá trị |
|---|---|
| Layout | **1 lớn + 2 nhỏ** (trái 1, phải xếp chồng 2) |
| Card | Thumbnail sắc nét + Tag danh mục + Tiêu đề + "⏳ 5 phút đọc" |
| **Reading progress** | Badge "Đã đọc" nếu user đã xem (localStorage) |

### Section 4: Article Grid

| Thuộc tính | Giá trị |
|---|---|
| Layout | Masonry Grid hoặc 3 cột |
| Card content | Ngày đăng + Tác giả → Tiêu đề (max 2 dòng) → Excerpt (120 ký tự) → "Đọc tiếp ➡️" |
| Hover | Zoom 1.05x + tiêu đề đổi màu Cam/Xanh |
| **Shadow lift** | `translateY(-4px) + box-shadow` khi hover |
| Load More | AJAX `[Xem thêm bài viết ⬇️]` hoặc infinite scroll option |

### Section 5: Lead Magnet

| Thuộc tính | Giá trị |
|---|---|
| **Headline** | *"Xây Nhà Lần Đầu? Đừng Bỏ Qua Cuốn Cẩm Nang Này."* |
| **Sub-text** | *"Ebook: 'Bí Quyết Xây Nhà Không Phát Sinh Chi Phí & Tối Ưu Vận Hành'"* |
| Background | `--color-primary` (#14513D), full-width |
| Visual | Mockup sách 3D |
| **3D tilt effect** | Di chuột → sách nghiêng nhẹ theo hướng cursor (JS `mousemove`) |
| Form | Fluent Form: Tên + SĐT/Zalo. Nút Cam: `[Gửi Cho Tôi Ngay]` |
| **CTA shimmer** | Shimmer animation trên nút |

---

## DETAIL PAGE (single.php)

### Breadcrumb (#21)
```
Trang Chủ > Tin Tức > [Danh mục] > [Tiêu đề]
```
Schema: `BreadcrumbList`

### Article Header
| Thuộc tính | Giá trị |
|---|---|
| Reading time | "⏳ 5 phút đọc" |
| Meta | Ngày đăng + Tác giả (KTS/Kỹ sư trưởng Xanh) |
| **Reading Progress Bar** | Thanh ngang top sticky — hiện % đã đọc |
| CSS | `position: fixed; top: 0; width: scroll%` |

### Content Area (2 columns on desktop)

**Main Content (left 70%):**
- **Table of Contents** (tự động từ headings) — đầu bài
- Nội dung bài viết (Classic Editor)
- **Inline Banner** giữa bài: *"Bạn đang đau đầu tìm vật liệu? Xanh cung cấp giải pháp."*
- **Social Share** cuối bài: Facebook + Zalo + Copy link

**Sticky Sidebar (right 30%) (#9):**
- **Mini CTA card** với ảnh: *"Nhận dự toán miễn phí →"*
- Form nhập SĐT (Fluent Form shortcode)
- Sidebar sticky khi scroll, stop trước footer

### Related Articles
| Thuộc tính | Giá trị |
|---|---|
| Layout | Grid 3 bài cùng danh mục |

### Lead Magnet (lặp lại)
- Hiển thị lại ebook CTA block cuối bài

---

## Back to Top (#18)
- Nút tròn góc phải dưới, hiện khi scroll > 500px
- Đặc biệt cần cho blog vì bài viết dài

---

## Tài Liệu Liên Quan

- `page_dev_guide/Blog Tin Tức.md` — Copywriting gốc
- `GOV_BRAND_VOICE.md` — Quy tắc viết blog
- `FEATURE_LEAD_CAPTURE.md` — Form Lead Magnet
- `ARCH_INTEGRATIONS.md` — Social sharing
