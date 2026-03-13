# CORE_AI_CONTEXT — Context Cho AI Agent

> **Dự án:** Website XANH - Design & Build
> **Mục đích:** Cung cấp context nhanh cho AI agent khi làm việc với dự án này

---

## Quick Reference

| Key | Value |
|---|---|
| **Project** | Website cho công ty thiết kế & thi công nội thất bền vững |
| **CMS** | WordPress + Custom Theme (`xanh-theme`) |
| **Key Plugins** | ACF Pro, Fluent Form, LiteSpeed Cache, Smush, Classic Editor |
| **Language** | Vietnamese (UI + Content) |
| **Docs Dir** | `docs/` — Tài liệu dự án (34 file .md) |
| **Theme Dir** | `wp-content/themes/xanh-theme/` |

---

## Brand Rules (Bắt Buộc Tuân Thủ)

### Colors (Color Ratio System: 60 – 25 – 10 – 5)
```css
--color-primary: #14513D;     /* 60% — Xanh đậm chủ đạo: nav, footer, hero, mảng lớn */
--color-beige: #D8C7A3;       /* 25% — Gam trung tính ấm: sections xen kẽ, warmth */
--color-white: #FFFFFF;        /* 10% — Bố cục thoáng: breathing room */
--color-accent: #FF8A00;      /* 5%  — Điểm nhấn: CTA buttons, active states */
--color-black: #000000;        /* linh hoạt — typography & line system */
```

### Fonts
- **Headline:** Founders Grotesk (`.otf` trong `assets/fonts/`)
- **Body:** Inter (Variable font `.ttf`)

### Tone of Voice
- ✅ Chuyên nghiệp, kỹ thuật nhưng dễ hiểu, gần gũi
- ✅ Từ khóa: "Minh bạch", "Bền vững", "Đồng hành", "Tối ưu"
- ❌ KHÔNG dùng: "bậc nhất", "đẳng cấp", lời hứa sáo rỗng
- ❌ KHÔNG dùng ảnh stock — chỉ ảnh thực tế của Xanh

### CTA Standards
- ✅ "Nhận Dự Toán" hoặc "Tư Vấn Kỹ Thuật"
- ❌ KHÔNG dùng CTA generic: "Liên hệ ngay", "Đăng ký"

---

## File Map — Tài Liệu Quan Trọng

```
docs/
├── README.md                    ← MỤC LỤC CHÍNH
├── CORE_PROJECT.md              ← Tổng quan dự án, sitemap, roadmap
├── CORE_ARCHITECTURE.md         ← Theme structure, tech stack
├── CORE_DATA_MODEL.md           ← CPTs, ACF fields, taxonomies
├── ARCH_DESIGN_TOKENS.md        ← Colors, fonts, spacing chi tiết
├── ARCH_UI_PATTERNS.md          ← 27 UI components, interaction specs
├── GOV_UX_GUIDELINES.md         ← Design philosophy, mobile first
├── PAGE_HOME.md                 ← ⭐ Trang Chủ (8-10 sections)
├── PAGE_PORTFOLIO.md            ← ⭐ Portfolio + Detail page
├── FEATURE_ESTIMATOR.md         ← Công cụ Dự Toán
├── PLUGIN_ECOSYSTEM.md          ← Plugin config
└── ... (34 files total)
```

---

## Common Tasks & Hướng Dẫn

### Khi code theme templates
1. Đọc `CORE_ARCHITECTURE.md` → hiểu folder structure
2. Đọc `ARCH_DESIGN_TOKENS.md` → dùng đúng CSS variables
3. Đọc `PAGE_*.md` tương ứng → hiểu sections & specs
4. Đọc `ARCH_UI_PATTERNS.md` → component interactions

### Khi thêm/sửa CPT hoặc ACF fields
1. Đọc `CORE_DATA_MODEL.md` → hiểu data model hiện tại
2. Cập nhật `CORE_DATA_MODEL.md` sau khi thay đổi

### Khi viết content/copywriting
1. Đọc `GOV_BRAND_VOICE.md` → tuân thủ tone of voice
2. Đọc `PAGE_*.md` tương ứng → xem copywriting mẫu
3. Luôn chèn internal link về Dự Toán hoặc Portfolio

### Khi tối ưu performance
1. Đọc `ARCH_PERFORMANCE.md` → targets & strategies
2. Đọc `PLUGIN_ECOSYSTEM.md` → LiteSpeed & Smush config

### Do's & Don'ts

| ✅ DO | ❌ DON'T |
|---|---|
| Dùng CSS Variables cho colors | Hardcode hex colors |
| Mobile-first responsive | Desktop-first |
| Lazy load images | Load tất cả ảnh cùng lúc |
| Dùng `font-display: swap` | Để font blocking render |
| AJAX filtering (Portfolio, Blog) | Full page reload khi filter |
| WebP cho ảnh công trình | Dùng PNG/JPG gốc |
| `defer` cho JS | JS blocking trong `<head>` |
| Dùng Fluent Form cho forms | Tự code form từ đầu |
| Classic Editor cho blog posts | Gutenberg blocks |
| Viết copy đứng về phía khách hàng | Copy kiểu "chúng tôi giỏi nhất" |

---

## Tham Chiếu Nhanh

| Cần tìm | Xem file |
|---|---|
| Sitemap, roadmap | `CORE_PROJECT.md` |
| Theme folders, file structure | `CORE_ARCHITECTURE.md` |
| CPTs, ACF fields, taxonomies | `CORE_DATA_MODEL.md` |
| Color tokens, fonts, spacing | `ARCH_DESIGN_TOKENS.md` |
| UI components (21 items) | `ARCH_UI_PATTERNS.md` |
| Google Maps, Analytics, Zalo | `ARCH_INTEGRATIONS.md` |
| LiteSpeed, Smush, WebP | `ARCH_PERFORMANCE.md` |
| Coding standards | `GOV_CODING_STANDARDS.md` |
| Tone of voice, CTA rules | `GOV_BRAND_VOICE.md` |
| Mobile first, animations | `GOV_UX_GUIDELINES.md` |
| Trang Chủ specs | `PAGE_HOME.md` |
| Portfolio specs | `PAGE_PORTFOLIO.md` |
| Blog specs | `PAGE_BLOG.md` |
| Estimator tool | `FEATURE_ESTIMATOR.md` |
| Lead forms (Fluent Form) | `FEATURE_LEAD_CAPTURE.md` |
| Plugin stack | `PLUGIN_ECOSYSTEM.md` |
| Custom plugins | `PLUGIN_CUSTOM_DEV.md` |
| Deployment | `OPS_DEPLOYMENT.md` |
| Sprint plan | `TRACK_BLUEPRINT.md` |
| Brand assets (fonts, logos) | `REF_BRAND_ASSETS.md` |
