# PAGE_GLOBAL_ELEMENTS — Header, Footer, 404, Thank-you

> **Dự án:** Website XANH - Design & Build
> **Templates:** `header.php`, `footer.php`, `404.php`
> **Ngày tạo:** 2026-03-12

---

## 1. Header / Navigation

### Desktop Header
```
┌─────────────────────────────────────────────────────────────────┐
│  [Logo]    Trang Chủ  Dự Án  Giải Pháp  Tin Tức  Về Chúng Tôi │ [Liên Hệ Tư Vấn]
│  XANH                  Xanh                                     │   ← CTA button
└─────────────────────────────────────────────────────────────────┘
```

| Thuộc tính | Giá trị |
|---|---|
| Logo | Logo No Tagline (nhỏ), link về Home |
| Logo size | Max height 48px |
| Background | `--color-white` (transparent khi ở Hero, chuyển solid khi scroll) |
| Transition | Background `transparent → white` + `box-shadow` khi scroll > 100px |
| Sticky | `position: sticky; top: 0; z-index: 1000` |
| CTA Button | `[Liên Hệ Tư Vấn]` → `/lien-he/`. Style: `--color-accent`, `--radius-sm` |
| Active link | Underline `--color-accent` (3px) |
| Hover link | Color `--color-accent` |
| Font | Inter SemiBold, `--text-small` (14px) |

### Mobile Header
```
┌─────────────────────────────┐
│  [Logo XANH]       [☰ Menu] │
└─────────────────────────────┘
        ↓ click ☰
┌─────────────────────────────┐
│  ✕ Close                     │
│                              │
│  Trang Chủ                   │
│  Dự Án                       │
│  Giải Pháp Xanh              │
│  Tin Tức & Cẩm Nang          │
│  Về Chúng Tôi                │
│                              │
│  ┌─────────────────────┐    │
│  │  Liên Hệ Tư Vấn    │    │  ← CTA button
│  └─────────────────────┘    │
│                              │
│  📞 0xxx.xxx.xxx             │
│  📧 email@xanh.vn           │
└─────────────────────────────┘
```

| Thuộc tính | Giá trị |
|---|---|
| Trigger | Hamburger icon (☰) |
| Animation | Slide-in từ phải, overlay `rgba(0,0,0,0.5)` |
| Close | Nút ✕ + click overlay + Escape key |
| Menu items | Full-width, padding `--space-4`, font `--text-body-lg` |
| Contact info | Hiện ở cuối menu mobile (hotline + email) |
| Body scroll | `overflow: hidden` khi menu mở |

### Menu Items

| Label | URL | Trang |
|---|---|---|
| Trang Chủ | `/` | `front-page.php` |
| Dự Án | `/du-an/` | `archive-xanh_project.php` |
| Giải Pháp Xanh | `/giai-phap-xanh/` | `page-green-solution.php` |
| Tin Tức & Cẩm Nang | `/tin-tuc/` | `archive.php` |
| Về Chúng Tôi | `/gioi-thieu/` | `page-about.php` |
| **CTA:** Liên Hệ Tư Vấn | `/lien-he/` | `page-contact.php` |

> WordPress Menu: `register_nav_menus(['primary' => 'Main Navigation'])`

---

## 2. Footer

```
┌─────────────────────────────────────────────────────────────────┐
│                         (Dark BG: --color-primary)               │
│                                                                   │
│  [Logo Full Tagline]                                              │
│  "Đừng chỉ xây một ngôi nhà. Hãy xây dựng sự bình yên."       │
│                                                                   │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────────────┐   │
│  │ GIỚI THIỆU   │  │ DỊCH VỤ      │  │ LIÊN HỆ              │   │
│  │ Về Xanh      │  │ Thiết kế     │  │ 📍 [Địa chỉ KH]     │   │
│  │ Đội ngũ      │  │ Thi công     │  │ 📞 [Hotline]         │   │
│  │ Triết lý     │  │ Tư vấn       │  │ 📧 [Email]           │   │
│  │ Blog         │  │ Dự toán      │  │ ⏰ T2-T7: 08:00-17:30│   │
│  └──────────────┘  └──────────────┘  └──────────────────────┘   │
│                                                                   │
│  ┌─────────────────────────────────────────────────┐             │
│  │ 📧 Đăng ký nhận tin tư vấn: [Email]  [Gửi]    │  ← Optional │
│  └─────────────────────────────────────────────────┘             │
│                                                                   │
│  [FB]  [Zalo]                                                     │
│                                                                   │
│  ─────────────────────────────────────────────────────────────   │
│  © 2026 XANH - Design & Build. Bản quyền thuộc CTCP...          │
│  MST: [Mã số thuế]                                               │
└─────────────────────────────────────────────────────────────────┘
```

| Thuộc tính | Giá trị |
|---|---|
| Background | `--color-primary` (#14513D) |
| Text | `--color-white`, links `--color-gray-200` hover `--color-accent` |
| Logo | Logo Full Tagline (trắng) |
| Tagline | Italic, `--color-beige` |
| Grid | 3 cột (desktop), stack (mobile) |
| Social icons | SVG, 32×32px, hover scale 1.1 + `--color-accent` |
| Copyright | `--text-xs`, `--color-gray-400` |
| Spacing | `padding: var(--space-20) var(--space-8)` |
| Data | ACF Options: `xanh_hotline`, `xanh_email`, `xanh_address` |

### Schema (footer)
```json
{
  "@type": "LocalBusiness",
  "name": "XANH - Design & Build",
  "address": { ... },
  "telephone": "...",
  "openingHours": "Mo-Sa 08:00-17:30"
}
```

---

## 3. 404 Page

```
┌─────────────────────────────────────────┐
│                                          │
│          🏗️  (Illustration)              │
│                                          │
│     "Ôi! Trang này đang được xây..."    │
│                                          │
│  Có vẻ trang bạn tìm không tồn tại     │
│  hoặc đã được di chuyển.                │
│                                          │
│  ┌─────────────────────────────────┐    │
│  │  🔍  Tìm kiếm nội dung...      │    │
│  └─────────────────────────────────┘    │
│                                          │
│  Hoặc quay về:                          │
│  [🏠 Trang Chủ]  [📸 Dự Án]  [📞 Liên Hệ] │
│                                          │
└─────────────────────────────────────────┘
```

| Thuộc tính | Giá trị |
|---|---|
| Template | `404.php` |
| Illustration | SVG custom: công nhân/kiến trúc sư đang xây. Tông brand |
| Headline | Founders Grotesk, `--text-h1` |
| Body | Inter, `--text-body` |
| Search bar | WordPress search form |
| Quick links | 3 buttons: Home, Portfolio, Contact |
| Background | `--color-light` |
| Tracking | GA4 event `page_404` với URL gốc |

---

## 4. Thank-You Pages

### 4.1 `/cam-on/` — Form Tư Vấn

```
┌─────────────────────────────────────────┐
│                                          │
│           ✅ (Animated checkmark)        │
│                                          │
│    "Cảm Ơn Bạn Đã Tin Tưởng Xanh!"    │
│                                          │
│  Yêu cầu tư vấn của bạn đã được ghi   │
│  nhận. Kỹ sư trưởng sẽ liên hệ trong  │
│  vòng 24 giờ làm việc.                  │
│                                          │
│  Trong khi chờ đợi, bạn có thể:        │
│  [📸 Xem Dự Án Của Xanh]               │
│  [📘 Đọc Cẩm Nang Xây Nhà]            │
│                                          │
│  📞 Cần gấp? Gọi ngay: [Hotline]       │
│                                          │
└─────────────────────────────────────────┘
```

### 4.2 `/cam-on-du-toan/` — Form Dự Toán

```
┌─────────────────────────────────────────┐
│                                          │
│          📧 (Animated envelope)          │
│                                          │
│  "Dự Toán Sơ Bộ Đang Được Gửi!"       │
│                                          │
│  Vui lòng kiểm tra email/Zalo của bạn  │
│  trong vòng 5 phút.                     │
│                                          │
│  🎁 Bonus: Tải miễn phí                 │
│  📘 "Bí Quyết Xây Nhà Không Phát Sinh" │
│  [Tải Cẩm Nang Ngay]                    │
│                                          │
└─────────────────────────────────────────┘
```

### Thank-You Page Tracking

```html
<!-- Cả 2 trang Thank-you đều cần -->
<!-- GA4 Conversion -->
<script>
  gtag('event', 'conversion', { 'send_to': 'AW-XXXXX/YYYYY' });
  gtag('event', 'page_thankyou', { 'conversion_type': 'contact' });
</script>

<!-- Facebook Pixel Lead Event -->
<script>
  fbq('track', 'Lead');
</script>
```

| Thuộc tính | Giá trị |
|---|---|
| Animation | CSS checkmark/envelope draw animation |
| Auto-redirect | ❌ Không redirect tự động (user cần thấy confirmation) |
| Background | `--color-white` |
| CTA links | Dẫn về Portfolio + Blog (tiếp tục engage) |
| noindex | `<meta name="robots" content="noindex">` (không index trang này) |

---

## 5. Skip Navigation (Accessibility)

```html
<!-- Đầu tiên trong body, trước header -->
<a class="skip-link sr-only" href="#main-content">
  Bỏ qua đến nội dung chính
</a>
```

```css
.skip-link {
  position: absolute;
  top: -100%;
  left: 0;
  z-index: 9999;
}
.skip-link:focus {
  top: 0;
  background: var(--color-accent);
  color: white;
  padding: var(--space-3) var(--space-4);
}
```

---

## Tài Liệu Liên Quan

- `CORE_ARCHITECTURE.md` — Template hierarchy (header, footer, 404)
- `ARCH_DESIGN_TOKENS.md` — Colors, spacing
- `FEATURE_LEAD_CAPTURE.md` — Thank-you page redirect
- `GOV_UX_GUIDELINES.md` — Accessibility, mobile nav
- `ARCH_INTEGRATIONS.md` — Schema markup
