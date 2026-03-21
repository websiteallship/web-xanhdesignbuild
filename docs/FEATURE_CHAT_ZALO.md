# FEATURE_CHAT_ZALO — Chat & Zalo Integration

> **Dự án:** Website XANH - Design & Build
> **Ngày tạo:** 2026-03-12

---

## 1. Zalo OA Widget

| Thuộc tính | Giá trị |
|---|---|
| Widget | Zalo Chat Widget (JS embed) |
| Vị trí | Floating, góc phải dưới |
| Z-index | 998 (thấp hơn Floating CTA mobile) |
| Welcome message | "Chào bạn! Đội ngũ Kỹ sư Xanh sẵn sàng tư vấn." |
| Auto popup | ❌ (không tự bật) |
| Lazy load | Load sau DOMContentLoaded + 3s delay |
| Desktop | ✅ Hiển thị |
| Mobile | ✅ Hiển thị (nhưng nằm trên Floating CTA bar) |

---

## 2. Floating CTA Bar (#15) — Mobile Only

```
┌──────────────────────────────┐
│  📞 Gọi ngay  │ 📋 Nhận DT  │
└──────────────────────────────┘
```

| Thuộc tính | Giá trị |
|---|---|
| Position | `fixed; bottom: 0` |
| Z-index | 999 |
| Hiển thị | Chỉ `max-width: 768px` |
| Nút trái | `tel:[hotline]` → gọi điện trực tiếp |
| Nút phải | Scroll to estimator form hoặc link `/lien-he/` |
| Background | `--color-primary` |
| Text | `--color-white` |
| Height | 56px |
| Safe area | `padding-bottom: env(safe-area-inset-bottom)` |
| Scope | **Toàn site** |

---

## 3. Hotline Click-to-Call

| Thuộc tính | Giá trị |
|---|---|
| Format | `<a href="tel:+84XXXXXXXXX">` |
| Data source | ACF Option `xanh_hotline` |
| Tracking | GA4 event `click_cta_call` |

---

## 4. Social Links

| Platform | Thuộc tính |
|---|---|
| Zalo OA | ACF Option `xanh_zalo_oa` |
| Facebook | ACF Option `xanh_facebook` |
| Tracking | GA4 events `click_cta_zalo`, `click_social_fb` |

---

## Tài Liệu Liên Quan

- `ARCH_INTEGRATIONS.md` — Zalo OA embed code
- `ARCH_UI_PATTERNS.md` — Floating CTA specs
- `OPS_MONITORING.md` — Click tracking events
