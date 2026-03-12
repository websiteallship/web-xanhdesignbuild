# PAGE_CONTACT — Trang Liên Hệ

> **Dự án:** Website XANH - Design & Build
> **Template:** `page-contact.php`
> **Ngày tạo:** 2026-03-12
> **Tham chiếu:** [ContactPage.md](./page_dev_guide/ContactPage.md) | [ui_ux_enhancement_guide.md](./ui_ux_enhancement_guide.md)

---

## Tổng Quan

- **Số sections:** 3 gốc + 1 bổ sung (Counter strip)
- **Form plugin:** Fluent Form

---

## Section 1: Hero

| Thuộc tính | Giá trị |
|---|---|
| **Headline** | *"Mọi Công Trình Bền Vững Đều Bắt Đầu Từ Một Cuộc Trò Chuyện."* |
| **Sub-headline** | *"Bạn đang ấp ủ một không gian sống mới nhưng còn nhiều trăn trở?..."* |
| Background | Ảnh văn phòng Xanh hoặc buổi tư vấn KTS + khách hàng |
| Height | 40-50vh (để thấy form bên dưới ngay) |
| **Subtle particles** | Các chấm nhỏ xanh bay nhẹ trên nền tối (CSS `@keyframes`, không canvas) |

---

## Section 2: Contact Block (2 cột)

### Layout
| Thuộc tính | Giá trị |
|---|---|
| Desktop | 2 cột (4:6 hoặc 5:5) |
| Mobile | Stack: Thông tin trên, Form dưới |

### Cột Trái — Thông Tin

**Khối 1: Liên lạc**
- 📍 Trụ sở: [Địa chỉ Khánh Hòa]
- 📞 Hotline Kỹ Thuật: [SĐT] — `tel:` link
- 📧 Email: [email]
- ⏰ Giờ làm việc: Thứ 2 - Thứ 7 (08:00 - 17:30)

**Khối 2: Trust Box (Pháp lý)**
| Thuộc tính | Giá trị |
|---|---|
| Background | `--color-light` hoặc `--color-primary` nhạt |
| Content | Tên pháp nhân + MST + Thương hiệu |
| **Hover** | Tooltip "Tra cứu trên Cổng thông tin DN" kèm link |

**Khối 3: Google Maps**
| Thuộc tính | Giá trị |
|---|---|
| Embed | iframe `loading="lazy"` |
| Style | **Custom colors** tông xanh/xám (Maps Styling Wizard) |
| Pin | Custom logo Xanh |
| Data | ACF Option `xanh_google_maps_embed` |

### Cột Phải — Form Tư Vấn (Fluent Form)

| Thuộc tính | Giá trị |
|---|---|
| **Tiêu đề** | *"Kể Cho Chúng Tôi Nghe Về Bài Toán Của Bạn"* |
| **Sub-text** | *"Kỹ sư trưởng sẽ liên hệ lại trong vòng 24 giờ."* |
| Trường 1 | Họ và tên (*) |
| Trường 2 | Số điện thoại / Zalo (*) |
| Trường 3 | Dropdown: Thiết kế & Thi công trọn gói / Thương mại / Nội thất / Khác |
| Trường 4 | Textarea: Chia sẻ thêm (không bắt buộc) |
| **CTA** | `[YÊU CẦU TƯ VẤN (Mức Phí 0 Đồng)]` — Cam, full-width |
| Cam kết | 🔒 *"Xanh cam kết bảo mật 100% thông tin cá nhân"* |

### Form UX Nâng Cấp

| Feature | Specs |
|---|---|
| **Floating labels** | Label trong input, float lên khi focus |
| **Real-time validation** | Viền xanh hợp lệ, đỏ khi sai |
| **Multi-step option** | Optional A/B test: Step 1 (Tên + SĐT) → Step 2 (Loại hình + Ghi chú) |
| **Loading** | Button hiện spinner khi gửi |
| **Success** | Redirect → Thank-you page |
| **Input sizing** | Min height 48px, font ≥ 16px (tránh iOS zoom) |

---

## Section 3: FAQ Accordion (#5)

| Thuộc tính | Giá trị |
|---|---|
| **Headline** | *"Những Câu Hỏi Thường Gặp"* |
| Câu hỏi mặc định | Câu 1 mở sẵn |
| **Icon rotation** | Mũi tên xoay 180° khi mở |
| Transition | `max-height` mượt, 300ms |
| Font | Inter, dễ đọc |

### 4 câu FAQ:
1. **"Tôi có phải trả phí cho buổi tư vấn ban đầu không?"** → Hoàn toàn không...
2. **"Xanh thi công ở khu vực nào?"** → Khánh Hòa + tỉnh lân cận...
3. **"Bảng dự toán có phát sinh chi phí không?"** → Cam kết 100% không phát sinh...
4. **"Xanh có nhận thi công nếu tôi đã có bản vẽ?"** → Có, thẩm định + dự toán...

Schema: `FAQPage` (JSON-LD)

---

## ★ After FAQ: Counter Strip (#10) Mini

| Thuộc tính | Giá trị |
|---|---|
| Content | "X+ Dự án ⎮ X tỉnh phục vụ ⎮ 100% Không phát sinh" |
| Background | `--color-primary` |
| Style | Thanh nhỏ, không tạo section riêng |

---

## Cookie Consent (#19)

| Thuộc tính | Giá trị |
|---|---|
| Quan trọng | Đặc biệt ở trang thu thập data |
| Text | "Website sử dụng cookies để cải thiện trải nghiệm..." |
| Buttons | "Đồng ý" (primary) / "Tùy chỉnh" (secondary) |

---

## Tài Liệu Liên Quan

- `page_dev_guide/ContactPage.md` — Copywriting gốc
- `FEATURE_LEAD_CAPTURE.md` — Form workflow chi tiết
- `ARCH_INTEGRATIONS.md` — Google Maps, Fluent Form SMTP
- `GOV_UX_GUIDELINES.md` — Form UX guidelines
