# PLUGIN_ECOSYSTEM — Hệ Sinh Thái Plugin

> **Dự án:** Website XANH - Design & Build
> **Ngày tạo:** 2026-03-12

---

## Plugin Stack

| Plugin | Version | Vai trò | License |
|---|---|---|---|
| **ACF Pro** | Latest | Custom fields, Options pages, Repeaters, Galleries | Pro |
| **Fluent Forms** | Pro | Form builder, SMTP, notifications, PDF | Pro |
| **LiteSpeed Cache** | Latest | Page cache, CSS/JS minify, CDN, critical CSS | Free |
| **Smush** | Pro | Image compression, WebP, lazy load, CDN | Pro/Free |
| **Classic Editor** | Latest | Blog post editing (thay Gutenberg) | Free |
| Custom Plugins | — | Estimator, shortcodes, utilities | Custom |

---

## ACF Pro Configuration

### Options Pages
```php
acf_add_options_page([
    'page_title' => 'Cài Đặt Xanh',
    'menu_slug'  => 'xanh-settings',
    'capability' => 'manage_options',
]);
```

### Field Groups
| Group | Vị trí | Tham chiếu |
|---|---|---|
| `group_project_details` | CPT `xanh_project` | `CORE_DATA_MODEL.md` |
| `group_testimonial_details` | CPT `xanh_testimonial` | `CORE_DATA_MODEL.md` |
| `group_team_details` | CPT `xanh_team` | `CORE_DATA_MODEL.md` |
| `group_homepage` | Front Page | `CORE_DATA_MODEL.md` |
| `group_estimator` | Options Page | `FEATURE_ESTIMATOR.md` |
| `group_site_settings` | Options Page | Contact info, social links |

---

## Fluent Forms Configuration

### SMTP Settings
| Setting | Giá trị |
|---|---|
| Connection | SMTP |
| From Name | XANH - Design & Build |
| From Email | noreply@[domain] |

### Forms Overview — Xem `FEATURE_LEAD_CAPTURE.md`

---

## LiteSpeed Cache — Xem `ARCH_PERFORMANCE.md`

Key settings: Page cache ON, Mobile separate cache, CSS/JS minify, Defer JS, Critical CSS inline.

---

## Smush — Xem `ARCH_PERFORMANCE.md`

Key settings: Auto-compress on upload, WebP conversion, Strip metadata, Lazy load.

---

## Tài Liệu Liên Quan

- `CORE_DATA_MODEL.md` — ACF field groups chi tiết
- `ARCH_PERFORMANCE.md` — LiteSpeed & Smush config
- `FEATURE_LEAD_CAPTURE.md` — Fluent Form forms
- `PLUGIN_CUSTOM_DEV.md` — Custom plugins
