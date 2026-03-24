# CORE_PROJECT — Tổng Quan Dự Án

> **Dự án:** Website XANH - Design & Build
> **Phiên bản:** 1.0 | **Ngày tạo:** 2026-03-12
> **Tham chiếu:** [project_review.md](./project_review.md) | [Brand Guideline PDF](./brand_guideline/BRAND%20GUIDELINE_XANH.pdf)

---

## 1. Thông Tin Thương Hiệu

| Thông tin | Chi tiết |
|---|---|
| **Tên thương hiệu** | XANH - Design & Build |
| **Pháp nhân** | Công ty Cổ phần Đầu tư Thiết bị và Giải pháp Xanh |
| **Lĩnh vực** | Thiết kế & Thi công nội thất, xây dựng trọn gói theo định hướng bền vững |
| **Khu vực hoạt động** | Khánh Hòa (trụ sở chính), phục vụ các tỉnh lân cận |
| **Tầm nhìn** | Đơn vị dẫn đầu giải pháp "Chìa khóa trao tay" minh bạch & hiệu quả tại Khánh Hòa |
| **Thông điệp cốt lõi** | *"Đừng chỉ xây một ngôi nhà. Hãy xây dựng sự bình yên."* |

---

## 2. Triết Lý "4 Xanh" — DNA Thương Hiệu

| # | Tên | Ý nghĩa | Icon |
|---|---|---|---|
| 1 | **Xanh Chi phí** | Minh bạch, cam kết 100% không phát sinh chi phí | 💰 Đồng xu nảy mầm |
| 2 | **Xanh Vật liệu** | Bền bỉ, an toàn sức khỏe, vòng đời dài | 🌿 Lá cây |
| 3 | **Xanh Vận hành** | Tiết kiệm năng lượng, tối ưu gió trời & ánh sáng | ☀️ Nắng/Gió |
| 4 | **Xanh Giá trị** | Đồng hành, bảo trì trọn đời, giảm thiểu rủi ro | 🤝 Cái bắt tay |

## 3. Giá Trị Cốt Lõi (Core Values)

1. **Hiệu Quả Thực Tế** — Chỉ thiết kế những gì có thể thi công được
2. **Minh Bạch** — Rõ ràng trong chi phí, vật liệu và quy trình
3. **Bền Vững** — Chất lượng thi công, công năng sử dụng, tài chính
4. **Đồng Hành** — Cam kết suốt vòng đời công trình

---

## 4. Sitemap Website

```
🏠 Trang Chủ (Home)
├── 📸 Dự Án (Portfolio)
│   └── Trang chi tiết dự án
├── 🌿 Giải Pháp Xanh
├── 📰 Tin Tức & Cẩm Nang (Blog)
│   └── Trang chi tiết bài viết
├── ℹ️ Giới Thiệu (About)
├── 📞 Liên Hệ (Contact)
└── 🧮 Dự Toán (tích hợp trong Home/Portfolio)
```

**Chi tiết từng trang:** Xem nhóm `PAGE_*.md`

---

## 5. Phân Chia Tính Năng Theo Phase

### Phase 1 — MVP (Go-live)

| Tính năng | Mức độ | Tham chiếu |
|---|---|---|
| 6 trang chính (Home, About, Portfolio, Green, Blog, Contact) | ⭐ Chủ chốt | `PAGE_*.md` |
| Công cụ Dự Toán Thông Minh | ⭐ Chủ chốt | `FEATURE_ESTIMATOR.md` |
| Before/After Image Slider | ⭐ Chủ chốt | `FEATURE_MEDIA_GALLERY.md` |
| Form Lead Capture (Fluent Form) | Quan trọng | `FEATURE_LEAD_CAPTURE.md` |
| Blog/CMS (Classic Editor) | Quan trọng | `PAGE_BLOG.md` |
| Chatbox & Zalo | Quan trọng | `FEATURE_CHAT_ZALO.md` |
| SEO & Tracking | Quan trọng | `OPS_MONITORING.md` |

### Phase 2 — Nâng cao

| Tính năng | Tham chiếu |
|---|---|
| Cổng Theo Dõi Tiến Độ (Client Portal) | `PLUGIN_CUSTOM_DEV.md` |
| Tham quan 360°/VR | `FEATURE_MEDIA_GALLERY.md` |

---

## 6. Lộ Trình Triển Khai (Sprint Roadmap)

| Sprint | Nội dung | Ưu tiên |
|---|---|---|
| **Sprint 1** | Wireframe & UI Mockup (Home, Portfolio) | 🔴 Cao |
| **Sprint 2** | Front-end custom theme (HTML/CSS/JS), UI components | 🔴 Cao |
| **Sprint 3** | Dự Toán + Lead system (Back-end, Fluent Form) | 🟠 Trung bình |
| **Sprint 4** | SEO, performance (LiteSpeed, Smush), mobile testing | 🟠 Trung bình |
| **Sprint 5** | Go-live + Tracking (GA, FB Pixel) | 🟢 Hoàn thiện |

**Chi tiết roadmap:** Xem `TRACK_BLUEPRINT.md`

---

## 7. Tech Stack Tổng Quan

| Layer | Công nghệ |
|---|---|
| **CMS** | WordPress (latest) |
| **Theme** | Custom theme (tự phát triển) |
| **Custom Fields** | ACF Pro |
| **Forms** | Fluent Form + SMTP |
| **Cache** | LiteSpeed Cache |
| **Image Optimization** | Smush |
| **Editor** | Classic Editor |
| **Custom Plugins** | Estimator, shortcodes, etc. |

**Chi tiết kiến trúc:** Xem `CORE_ARCHITECTURE.md`
**Chi tiết plugin:** Xem `PLUGIN_ECOSYSTEM.md`

---

## Tài Liệu Liên Quan

| Tài liệu | Mô tả |
|---|---|
| `CORE_ARCHITECTURE.md` | Kiến trúc hệ thống |
| `CORE_DATA_MODEL.md` | CPTs, ACF fields, taxonomies |
| `CORE_AI_CONTEXT.md` | Context cho AI agent |
| `TRACK_BLUEPRINT.md` | Blueprint chi tiết |
| `REF_BRAND_ASSETS.md` | Tài nguyên brand |
