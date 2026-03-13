# PAGE_ABOUT — Trang Giới Thiệu (AboutPage)

> **Dự án:** Website XANH - Design & Build
> **Template:** `page-about.php`
> **Ngày tạo:** 2026-03-12
> **Tham chiếu:** [AboutPage.md](./page_dev_guide/AboutPage.md) | [ui_ux_enhancement_guide.md](./ui_ux_enhancement_guide.md)

---

## Tổng Quan

- **Bố cục:** Kể chuyện thương hiệu: Ấn tượng → Nỗi đau → Bước ngoặt → Triết lý → Cam kết → Tầm nhìn
- **Số sections:** 8 (6 gốc + 2 bổ sung)

---

## Section 1: Hero Banner

| Thuộc tính | Giá trị |
|---|---|
| **Headline** | *"Xanh - Design & Build: Câu Chuyện Của Sự Liền Mạch & Bền Vững."* |
| **Sub-headline** | *"Chúng tôi là thương hiệu cung cấp giải pháp nội thất... kiến tạo những công trình đáng để đầu tư."* |
| Background | Ảnh/video nội thất sang trọng. Overlay tối |
| Typography | Headline: Inter Bold, `--text-hero`. Text: `--color-white` |
| **Video Popup (#17)** | Overlay nút ▶ → click mở video giới thiệu công ty trong modal. Banner giữ ảnh tĩnh cho performance |

---

## Section 2: The Pain — Nỗi Trăn Trở

| Thuộc tính | Giá trị |
|---|---|
| **Headline** | *"Mọi Điểm Chạm Bắt Đầu Từ Một Sự Thật..."* |
| Content | 5 nỗi đau chủ đầu tư (bản vẽ không giống, đội chi phí, đùn đẩy trách nhiệm, xuống cấp nhanh, không ai chịu trách nhiệm) |
| Background | `--color-beige` (#D8C7A3) |
| Font body | Inter Regular |
| **Layout nâng cấp** | **Zig-zag alternating**: Dòng 1 (text trái, ảnh phải), dòng 2 (ảnh trái, text phải)... |
| Animation | Mỗi "nỗi đau" fade-in lần lượt khi scroll |

---

## Section 3: The Turning Point — Bước Ngoặt

| Thuộc tính | Giá trị |
|---|---|
| **Headline** | *"Lời Giải Cho Sự 'Đứt Gãy' Của Thị Trường"* |
| Content | Chuỗi giá trị khép kín: Thiết kế → Dự toán → Vật liệu → Thi công → Bảo hành |
| Background | `--color-white`. Text: `--color-primary` |
| Layout | **Sticky Scroll** hoặc Infographic vòng tròn khép kín |
| **SVG Animation** | Vòng tròn vẽ dần khi scroll: `stroke-dasharray` + `stroke-dashoffset` |
| Mỗi điểm | Sáng lên lần lượt khi scroll đến |

---

## Section 4: Philosophy "4 Xanh" — Triết Lý

| Thuộc tính | Giá trị |
|---|---|
| **Headline** | *"Tại Đây, 'Xanh' Là Một Giải Pháp"* |
| Content | 4 Cards: Chi phí / Vật liệu / Vận hành / Giá trị |
| **Layout nâng cấp** | **Services Grid / Icon Box (#12)**: 4 Icon Box lớn với SVG custom |
| Hover | Scale 1.05 + shadow-lift + border-bottom Cam `--color-accent` |
| Icons | Cần thiết kế 4 icon SVG riêng cho brand |
| Grid | 4 cột (desktop), 2 cột (tablet), 1 cột (mobile) |

---

## ★ Section 4.5: Team Member Cards (#11)

| Thuộc tính | Giá trị |
|---|---|
| Content | 3-4 thành viên: CEO/Founder, KTS trưởng, Kỹ sư trưởng, Quản lý DA |
| Layout | Grid 3-4 cột (desktop), 2 (tablet), 1 (mobile) |
| Card | Ảnh tròn/vuông bo `--radius-lg`, tên, chức danh |
| Hover | Hiện social links + bio 2 dòng |
| Background | `--color-light` |
| Data source | CPT `xanh_team` |
| Ảnh | **Cần ảnh chân dung thực** của team |

---

## Section 5: Core Values — Bản Sắc Cốt Lõi

| Thuộc tính | Giá trị |
|---|---|
| **Headline** | *"Bản Sắc Cốt Lõi — Lời Cam Kết Bền Vững"* |
| Content | 4 giá trị: Hiệu Quả Thực Tế / Minh Bạch / Bền Vững / Đồng Hành |
| Background | `--color-primary` toàn màn hình |
| Typography | Headline: Inter Bold, `--color-white` |
| **Layout nâng cấp** | **Sticky Scroll**: Cột trái giữ headline + icon lớn (fixed). Cột phải cuộn từng giá trị fade-in. Divider line mỏng |
| Mobile fallback | Accordion (sticky scroll không phù hợp mobile) |

---

## ★ Section 5.5: Project Timeline / Milestone (#16)

| Thuộc tính | Giá trị |
|---|---|
| Content | Mốc phát triển công ty: Thành lập → Dự án đầu tiên → Cột mốc X công trình → Mở rộng... |
| Layout | Timeline dọc |
| Line | `--color-primary` (#14513D) |
| Dot | `--color-accent` (#FF8A00) |
| Background | `--color-white` |
| Animation | Dot + line vẽ dần khi scroll (IntersectionObserver) |

---

## Section 6: Mission & Vision — Tầm Nhìn

| Thuộc tính | Giá trị |
|---|---|
| **Tầm nhìn** | *"Khát vọng trở thành đơn vị chuyên cung cấp giải pháp nội thất hoàn thiện hàng đầu tại Khánh Hòa..."* |
| **Sứ mệnh** | *"Cung cấp giải pháp thiết kế, thi công và hoàn thiện nội thất trọn gói..."* |
| **Animated Counter (#10)** mini | 3 con số inline: X+ công trình / X tỉnh / X% hài lòng |
| CTA | `[Bắt Đầu Kiến Tạo Công Trình Của Bạn Cùng Xanh]` — Cam |
| Layout | Text căn giữa (center-aligned), đơn giản tinh gọn |

---

## Tài Liệu Liên Quan

- `page_dev_guide/AboutPage.md` — Copywriting gốc chi tiết
- `ui_ux_enhancement_guide.md` — Nâng cấp UI gốc
- `CORE_DATA_MODEL.md` — CPT `xanh_team`
- `ARCH_UI_PATTERNS.md` — Team Cards, Timeline, Sticky Scroll specs
