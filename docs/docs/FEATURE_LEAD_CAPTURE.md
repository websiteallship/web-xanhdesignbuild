# FEATURE_LEAD_CAPTURE — Hệ Thống Thu Lead

> **Dự án:** Website XANH - Design & Build
> **Plugin:** Fluent Form + SMTP
> **Ngày tạo:** 2026-03-12

---

## 1. Các Form Thu Lead

| Form | Vị trí | Trường | Form ID |
|---|---|---|---|
| **Form Tư Vấn** | Contact page | Tên, SĐT, Loại hình (dropdown), Ghi chú | FF-001 |
| **Form Dự Toán** | Home, Portfolio | Loại hình, Diện tích, Tầng, Gói VL, Tên, SĐT | FF-002 |
| **Form Lead Magnet** | Blog cuối trang | Tên, SĐT/Zalo | FF-003 |
| **Form Sidebar CTA** | Blog sidebar | SĐT, Tên | FF-004 |
| **Form Inline Banner** | Giữa bài blog | SĐT | FF-005 |

---

## 2. Notification Flow

```
User gửi form (bất kỳ)
  │
  ├──► [1] Email Admin (Fluent Form notification)
  │    Subject: "[Xanh Lead] {Tên} - {Loại hình}"
  │    Body: Tất cả field values + timestamp + source page
  │
  ├──► [2] Email User (autoresponder)
  │    Subject: "Xanh đã nhận yêu cầu của bạn!"
  │    Body: Confirmation + thời gian phản hồi + link Portfolio
  │
  └──► [3] Redirect → Thank-you Page
       URL: /cam-on/ (hoặc /cam-on-du-toan/ cho estimator)
```

---

## 3. Thank-You Pages

| Page | Tiêu đề | Nội dung |
|---|---|---|
| `/cam-on/` | "Cảm Ơn Bạn!" | Xác nhận đã nhận, KTS liên hệ trong 24h, link xem Portfolio |
| `/cam-on-du-toan/` | "Dự Toán Đang Được Gửi!" | Kiểm tra email/Zalo, link tải cẩm nang miễn phí |

> **Tracking:** Cả 2 Thank-you pages cần có GA4 `Conversion` event + FB Pixel `Lead` event.

---

## 4. SMTP Configuration (Fluent Form)

| Setting | Giá trị |
|---|---|
| From Name | XANH - Design & Build |
| From Email | noreply@[domain] |
| Reply-To | [email công ty] |
| Protocol | SMTP (qua Fluent Form built-in) |

---

## 5. Anti-Spam & Validation

| Measure | Implementation |
|---|---|
| Honeypot | Fluent Form built-in honeypot field |
| reCAPTCHA | Google reCAPTCHA v3 (invisible) |
| Phone validation | 10 số, bắt đầu 0 |
| Rate limiting | Max 3 submissions / IP / hour |

---

## Tài Liệu Liên Quan

- `FEATURE_ESTIMATOR.md` — Form Dự Toán
- `ARCH_INTEGRATIONS.md` — Fluent Form + SMTP
- `OPS_MONITORING.md` — Conversion tracking
- `PLUGIN_ECOSYSTEM.md` — Fluent Form config
