# PAGE_GREEN_SOLUTION — Trang Giải Pháp Xanh

> **Dự án:** Website XANH - Design & Build
> **Template:** `page-green-solution.php`
> **Ngày tạo:** 2026-03-12
> **Tham chiếu:** [Page Giải Pháp Xanh.md](./page_dev_guide/Page%20Giải%20Pháp%20Xanh.md) | [ui_ux_enhancement_guide.md](./ui_ux_enhancement_guide.md)

---

## Tổng Quan

- **Vai trò:** Trang chuyên sâu **giáo dục khách hàng** về triết lý "Xanh là giải pháp"
- **Số sections:** 6

---

## Section 1: Hero — Tuyên Ngôn

| Thuộc tính | Giá trị |
|---|---|
| **Headline** | *"'Xanh' Không Chỉ Là Một Khẩu Hiệu. Xanh Là Giải Pháp."* |
| **Sub-headline** | *"Tại Xanh - Design & Build, chúng tôi không định nghĩa 'Xanh' đơn thuần là một cái cây..."* |
| Background | Video mờ hoặc ảnh nội thất, `filter: brightness(0.4)` |
| **Text animation** | Split animation: Headline xuất hiện từng từ (stagger delay), sub-headline fade-in sau 0.5s |
| Animation lib | GSAP hoặc CSS `@keyframes` |

---

## Section 2: The Pain — Nỗi Đau

| Thuộc tính | Giá trị |
|---|---|
| **Headline** | *"Mọi Bất Ổn Đều Bắt Nguồn Từ Sự Đứt Gãy."* |
| Content | 4 nỗi đau: bản vẽ không giống, đội chi phí, đùn đẩy, xuống cấp nhanh |
| Layout | **Timeline / Zig-zag layout**. Tông màu trầm (xám/đỏ nhạt) |
| **Nâng cấp** | **Interactive cards**: Click/hover → expand mô tả chi tiết + icon minh họa |
| Trạng thái visual | Tông đỏ/xám = "chưa giải quyết" |

---

## Section 3: The Turning Point — Giải Pháp Khép Kín

| Thuộc tính | Giá trị |
|---|---|
| **Headline** | *"Khép Kín Chuỗi Giá Trị — Trách Nhiệm Quy Về Một Mối."* |
| Content | Chuỗi: Thiết kế → Dự toán → Vật liệu → Thi công → Bảo hành |
| Visual | Infographic vòng tròn khép kín |
| **SVG Animation** | Scroll-triggered draw animation — vòng tròn vẽ dần khi cuộn |
| **Video Popup (#17)** | Nút ▶ "Xem quy trình của Xanh" → modal video |
| Background | Tông sáng, `--color-white` |

---

## Section 4: Triết Lý 4 Xanh — Chi Tiết

| Thuộc tính | Giá trị |
|---|---|
| **Headline** | *"Chúng Tôi Định Nghĩa 'Xanh' Qua 4 Góc Độ."* |
| Content | 4 khối: Chi Phí / Vật Liệu / Vận Hành / Giá Trị |
| **Layout** | **Services Grid / Icon Box (#12)**: 4 box lớn |
| Icons | SVG animated (draw-on-scroll) |
| Hover | Nâng nhẹ + border Cam `--color-accent` |
| Background | Pattern subtle, `--color-light` |
| Grid | 4 cột → 2 cột → 1 cột responsive |

---

## Section 5: Brand DNA — Cam Kết

| Thuộc tính | Giá trị |
|---|---|
| **Headline** | *"Giá Trị Cốt Lõi — Lời Cam Kết Từ Xanh"* |
| Content | 4 giá trị: Hiệu Quả Thực Tế / Minh Bạch / Bền Vững / Đồng Hành |
| Layout | **Sticky Scroll**: Trái giữ tiêu đề (fixed), phải cuộn nội dung |
| **Progress indicator** | Thanh dọc bên trái hiện % đã đọc qua 4 giá trị |
| Mỗi giá trị | Fade-in kèm icon lớn |
| Mobile | Fallback sang accordion/scroll bình thường |

---

## Section 6: CTA — Kêu Gọi Hành Động

| Thuộc tính | Giá trị |
|---|---|
| **Headline** | *"Cùng Xanh Xây Dựng Những Công Trình Đáng Để Đầu Tư."* |
| Background | **Dark gradient**: `#14513D` → `#0a2e22` |
| **CTA button** | **Gradient border animation** chạy liên tục (Cam → Xanh → Cam) |
| Button text | `[Bắt Đầu Dự Án Của Bạn Với Xanh]` |
| Text | `--color-white` |

---

## Tài Liệu Liên Quan

- `page_dev_guide/Page Giải Pháp Xanh.md` — Copywriting gốc
- `ARCH_UI_PATTERNS.md` — Icon Box, Sticky Scroll, Video Popup specs
- `GOV_BRAND_VOICE.md` — Tone of voice
