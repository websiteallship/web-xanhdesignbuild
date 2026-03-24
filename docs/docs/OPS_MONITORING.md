# OPS_MONITORING — Giám Sát & Tracking

> **Dự án:** Website XANH - Design & Build
> **Ngày tạo:** 2026-03-12

---

## 1. Google Analytics 4

### Custom Events

| Event Name | Trigger | Parameters |
|---|---|---|
| `form_submit_estimator` | Gửi form Dự Toán | `form_id`, `page_location` |
| `form_submit_contact` | Gửi form Liên Hệ | `form_id` |
| `form_submit_lead_magnet` | Tải ebook | `form_id` |
| `click_cta_call` | Click "Gọi ngay" | `page_location` |
| `click_cta_zalo` | Click Zalo | `page_location` |
| `page_thankyou` | View Thank-you page | `conversion_type` |
| `video_play` | Play video popup | `video_url` |
| `scroll_depth` | Scroll 25/50/75/100% | `percent_scrolled` |

### Conversions
| Conversion | Event |
|---|---|
| Lead — Form Submit | `form_submit_*` |
| Lead — Thank You Page | `page_thankyou` |
| Phone Call Click | `click_cta_call` |

---

## 2. Facebook Pixel

| Event | Trigger |
|---|---|
| `PageView` | All pages |
| `Lead` | Form submit success |
| `ViewContent` | Portfolio detail page |
| `Contact` | Click hotline / Zalo |

---

## 3. SEO Monitoring

| Tool | Mục đích |
|---|---|
| Google Search Console | Indexing, sitemap, search performance |
| Google PageSpeed Insights | Core Web Vitals monitoring |
| Schema Validator | Verify structured data |

### Schema Markup Cần Có
| Schema | Trang |
|---|---|
| `LocalBusiness` | Toàn site (footer/header) |
| `Organization` | Toàn site |
| `BreadcrumbList` | Portfolio detail, Blog detail |
| `Article` | Blog posts |
| `FAQPage` | Contact |

---

## 4. Uptime & Performance

| Metric | Target | Tool |
|---|---|---|
| Uptime | 99.9% | UptimeRobot (free) |
| PageSpeed | > 90 | Weekly check |
| TTFB | < 200ms | WebPageTest |

---

## Tài Liệu Liên Quan

- `ARCH_INTEGRATIONS.md` — GA4, FB Pixel, Schema setup
- `ARCH_PERFORMANCE.md` — Performance targets
- `FEATURE_LEAD_CAPTURE.md` — Conversion flows
