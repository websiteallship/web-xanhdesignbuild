# ARCH_INTEGRATIONS — Tích Hợp Bên Thứ 3

> **Dự án:** Website XANH - Design & Build
> **Phiên bản:** 1.0 | **Ngày tạo:** 2026-03-12

---

## 1. Google Maps

| Thuộc tính | Chi tiết |
|---|---|
| **Phương thức** | iframe embed (không cần API key) |
| **Vị trí trên web** | Trang Liên Hệ (`PAGE_CONTACT.md` — Section 2) |
| **Custom Styling** | Tông xanh/xám phù hợp brand (Google Maps Styling Wizard) |
| **Pin** | Custom pin logo Xanh |
| **Lazy load** | `loading="lazy"` trên iframe |

```html
<iframe
  src="https://www.google.com/maps/embed?pb=..."
  loading="lazy"
  referrerpolicy="no-referrer-when-downgrade"
  title="Văn phòng XANH - Design & Build"
></iframe>
```

---

## 2. Google Analytics 4 (GA4)

| Thuộc tính | Chi tiết |
|---|---|
| **Phương thức** | gtag.js (qua script hoặc plugin) |
| **Vị trí script** | `<head>` (trước `</head>`) |
| **Cookie consent** | Chỉ load sau khi user đồng ý cookie (#19) |

### Events cần track

| Event | Trigger | Trang |
|---|---|---|
| `form_submit_estimator` | Gửi form Dự Toán | Home, Portfolio |
| `form_submit_contact` | Gửi form Liên Hệ | Contact |
| `form_submit_lead_magnet` | Tải ebook | Blog |
| `click_cta_call` | Click "Gọi ngay" (mobile) | Global |
| `click_cta_zalo` | Click Zalo chat | Global |
| `page_thankyou` | Đến trang cảm ơn | Thank-you pages |
| `scroll_depth` | 25%, 50%, 75%, 100% | All pages |
| `video_play` | Click nút ▶ video | Home, About |

---

## 3. Facebook Pixel

| Thuộc tính | Chi tiết |
|---|---|
| **Phương thức** | fbevents.js |
| **Vị trí** | `<head>` (sau GA4) |
| **Cookie consent** | Cùng với GA4 |

### Standard Events

| Event | Trigger |
|---|---|
| `PageView` | Mỗi trang |
| `Lead` | Gửi form thành công |
| `ViewContent` | Xem chi tiết dự án |
| `Contact` | Click hotline / Zalo |

---

## 4. Zalo OA (Official Account)

| Thuộc tính | Chi tiết |
|---|---|
| **Widget** | Zalo Chat Widget (JS embed) |
| **Vị trí** | Floating button góc phải dưới |
| **Z-index** | Thấp hơn Floating CTA Bar mobile |
| **Lazy load** | Load sau `DOMContentLoaded` + 3s delay |
| **Fallback** | Link `https://zalo.me/[OA_ID]` |

```html
<div class="zalo-chat-widget"
  data-oaid="[ZALO_OA_ID]"
  data-welcome-message="Chào bạn! Đội ngũ Kỹ sư Xanh sẵn sàng tư vấn."
  data-autopopup="0">
</div>
```

---

## 5. Fluent Form + SMTP

| Thuộc tính | Chi tiết |
|---|---|
| **Plugin** | Fluent Forms Pro |
| **SMTP** | Tích hợp sẵn trong Fluent Form |
| **Forms cần tạo** | Xem bảng bên dưới |

### Danh sách Forms

| Form | Vị trí | Trường | Tham chiếu |
|---|---|---|---|
| Form Tư Vấn (chính) | Contact page | Tên, SĐT, Dropdown, Textarea | `PAGE_CONTACT.md` |
| Form Dự Toán | Home, Portfolio | Loại hình, Diện tích, Gói VL, SĐT | `FEATURE_ESTIMATOR.md` |
| Form Lead Magnet | Blog | Tên, SĐT/Zalo | `PAGE_BLOG.md` |
| Form Sidebar CTA | Blog detail | SĐT, Tên | `PAGE_BLOG.md` |

### SMTP Config

| Setting | Giá trị |
|---|---|
| From Name | XANH - Design & Build |
| From Email | `noreply@[domain]` |
| Reply-To | `[email công ty]` |
| Autoresponder | ✅ Gửi email xác nhận cho khách |

### Notifications Flow
```
User gửi form
  ├── → Email admin (Fluent Form notification)
  ├── → Email user (autoresponder)
  └── → Redirect → Thank-you page
```

---

## 6. SSL / HTTPS

| Thuộc tính | Chi tiết |
|---|---|
| **Cert** | Let's Encrypt (tự động gia hạn) hoặc từ hosting |
| **Force HTTPS** | `.htaccess` redirect 301 |
| **HSTS** | `Strict-Transport-Security: max-age=31536000` |

---

## 7. CDN (Tùy chọn)

| Thuộc tính | Chi tiết |
|---|---|
| **Option 1** | LiteSpeed Cache CDN (QUIC.cloud) — miễn phí tier |
| **Option 2** | Cloudflare Free |
| **Assets served** | CSS, JS, Images, Fonts |

---

## 8. Social Sharing (Blog)

| Thuộc tính | Chi tiết |
|---|---|
| **Platforms** | Facebook, Zalo, Copy link |
| **Vị trí** | Cuối bài viết blog |
| **Phương thức** | Share URLs (không cần plugin) |

```
Facebook: https://www.facebook.com/sharer/sharer.php?u={URL}
Zalo: https://zalo.me/share?url={URL}
Copy link: navigator.clipboard.writeText(url)
```

---

## 9. Schema Markup (SEO)

| Schema | Trang | Dữ liệu |
|---|---|---|
| `LocalBusiness` | Toàn site | Tên, địa chỉ, SĐT, giờ làm việc |
| `Organization` | Toàn site | Logo, social profiles |
| `BreadcrumbList` | Portfolio detail, Blog detail | Navigation path |
| `Article` | Blog posts | Author, date, headline |
| `FAQPage` | Contact | 4 câu FAQ |

**Chi tiết Schema:** Xem `OPS_MONITORING.md`

---

## Tài Liệu Liên Quan

- `FEATURE_LEAD_CAPTURE.md` — Chi tiết form workflow
- `FEATURE_CHAT_ZALO.md` — Zalo integration
- `PLUGIN_ECOSYSTEM.md` — Fluent Form config
- `OPS_MONITORING.md` — Tracking setup
