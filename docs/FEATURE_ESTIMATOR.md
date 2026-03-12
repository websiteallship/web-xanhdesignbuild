# FEATURE_ESTIMATOR — Công Cụ Dự Toán Thông Minh

> **Dự án:** Website XANH - Design & Build
> **Phiên bản:** 1.0 | **Ngày tạo:** 2026-03-12

---

## 1. Mục Tiêu

Giải quyết nỗi sợ **"đội vốn"** — cho phép khách hàng nhận báo giá sơ bộ thông minh chỉ sau 1 phút, đồng thời thu Lead chất lượng cao.

---

## 2. Form Inputs

| Trường | Type | Options | Bắt buộc |
|---|---|---|---|
| Loại hình công trình | Dropdown | Nhà phố / Biệt thự / Căn hộ / Nghỉ dưỡng / Khác | ✅ |
| Diện tích (m²) | Number | Min: 30, Max: 2000 | ✅ |
| Số tầng | Number | 1-5 | ✅ |
| Gói vật liệu | Radio | Cơ bản / Tiêu chuẩn / Cao cấp | ✅ |
| Họ tên | Text | — | ✅ |
| Số điện thoại | Tel | Validation: 10 số, bắt đầu 0 | ✅ |
| Ghi chú | Textarea | — | ❌ |

---

## 3. Logic Tính Toán

```
Dự toán sơ bộ = Diện tích × Số tầng × Đơn giá/m² (theo gói)
```

| Gói | Đơn giá/m² | Ghi chú |
|---|---|---|
| Cơ bản | ACF: `price_per_sqm_basic` | Vật liệu tiêu chuẩn |
| Tiêu chuẩn | ACF: `price_per_sqm_standard` | Vật liệu trung cấp |
| Cao cấp | ACF: `price_per_sqm_premium` | Vật liệu cao cấp |

> **Output:** Khoảng giá "Từ X đến Y Tỷ VNĐ" (±10% range) — không hiện con số chính xác để tránh hiểu lầm.

---

## 4. Output & User Flow

```
User điền form
  ├── Real-time: Thanh progress bar khi điền
  ├── CTA pulse animation → "Nhận Bản Dự Toán Tham Khảo Ngay"
  │
  └── Submit
      ├── Hiện kết quả on-screen (optional)
      ├── Gửi PDF báo giá qua email (Fluent Form)
      ├── Gửi notification admin
      └── Redirect → Thank-you page
```

---

## 5. Implementation

| Aspect | Decision |
|---|---|
| Form plugin | **Fluent Form** (shortcode trong template) |
| Calculation | Custom PHP function hook vào Fluent Form submission |
| PDF generation | Custom plugin hoặc Fluent Form Pro PDF add-on |
| Đơn giá config | ACF Options Page (`group_estimator`) |
| Kết quả hiển thị | AJAX response → hiện inline (không reload) |
| Disclaimer | ACF: `estimator_disclaimer` — hiện dưới kết quả |

---

## 6. UX Specs

| Feature | Specs |
|---|---|
| Floating labels | Label trong input, float khi focus |
| Progress bar | Micro-animation khi điền từng trường |
| Validation | Real-time: viền xanh OK, đỏ lỗi |
| CTA | Pulse animation liên tục, Cam #FF8A00 |
| Mobile | Full-width form, inputs 48px height |
| Loading state | Spinner trên button khi đang tính |
| Result display | Animated number (count-up) cho kết quả |

---

## Tài Liệu Liên Quan

- `PAGE_HOME.md` — Section 5 (Dự Toán)
- `CORE_DATA_MODEL.md` — ACF `group_estimator`
- `FEATURE_LEAD_CAPTURE.md` — Lead workflow
- `PLUGIN_CUSTOM_DEV.md` — Custom estimator plugin
