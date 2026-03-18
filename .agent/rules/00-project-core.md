---
description: Core project rules for XANH - Design & Build website. Always read this file first. Theme slug is xanhdesignbuild.
globs: **/*
---

# XANH Project — Core Rules

## Brand Identity
- **Name:** XANH - Design & Build (luôn viết đúng, có dấu gạch)
- **Positioning:** Thương hiệu nội thất & xây dựng **cao cấp** — Warm Luxury, Tinh tế, Ấm áp
- **Target:** Khách hàng cao cấp — chủ biệt thự, doanh nhân, gia đình thành đạt
- **Triết lý:** "Real Luxury" — Sang trọng thực chất, không phô trương, chất lượng vượt kỳ vọng
- **4 Xanh:** Chi phí minh bạch / Vật liệu bền vững / Vận hành thông minh / Giá trị trường tồn
- **Tagline:** "Đừng Chỉ Xây Một Ngôi Nhà. Hãy Xây Dựng Sự Bình Yên."
- **Voice:** Warm Luxury — Chuyên nghiệp + Gần gũi + Sang trọng tinh tế (xem `docs/GOV_BRAND_VOICE.md`)
- **KHÔNG dùng:** "giá rẻ", "khuyến mãi", "ưu đãi sốc", "tiết kiệm", "bình dân", "liên hệ ngay"
- **NÊN dùng:** "Tinh tế", "Riêng biệt", "Trường tồn", "Kiến tạo", "Di sản", "Đồng hành"
- **CTA:** "Đặt Lịch Tư Vấn Riêng" / "Khám Phá Dự Toán Của Bạn" / "Bắt Đầu Câu Chuyện Của Bạn"

## Design Aesthetic
- **Phong cách:** Warm Luxury — Ít chi tiết, chất lượng cao, khoảng trắng rộng, tông ấm
- **Color Ratio (Brand Guide):** 60% Green `#14513D` / 25% Beige `#D8C7A3` / 10% White `#FFFFFF` / 5% Orange `#FF8A00`. Black `#000000` linh hoạt cho typography — không tính vào tỷ lệ
- **Photography:** Ánh sáng tự nhiên, editorial, tông ấm, detail shots. KHÔNG stock photos
- **Typography:** Inter (headings + body). Letter-spacing: -0.02em headings
- **Motion:** GSAP + ScrollTrigger — mượt mà, tinh tế. Stagger 100ms cho luxury cascading
- **Whitespace:** Content/space ratio 40/60. Sections padding: 80px desktop, 48px mobile
- **Micro-interactions:** Hover translateY(-4px) + shadow. Nav underline slide-in. Image scale(1.03)
- **Full specs:** `docs/ARCH_LUXURY_VISUAL_DIRECTION.md`

## Tech Stack
- **CMS:** WordPress (latest) + Custom Theme `xanhdesignbuild`
- **CSS:** Tailwind CSS (CLI build, utility-first) + CSS Variables (brand tokens)
- **JS:** Vanilla ES6+ (no jQuery) + GSAP + ScrollTrigger + Lenis + Swiper + GLightbox (all CDN)
- **Icons:** Lucide Icons (CDN or inline SVG)
- **Fonts:** Inter (variable, self-hosted, `font-display: swap`)
- **Colors:** Primary `#14513D`, Accent `#FF8A00`, Light `#F3F4F6`, Beige `#D8C7A3`
- **Plugins (local):** ACF Pro, Classic Editor, Classic Widgets, Fluent Form, Fluent Form Pro
- **Plugins (production):** Smush Pro, RankMath SEO, LiteSpeed Cache
- **JS budget:** ~80KB gzip vendor (CDN) + ~12KB custom per page max
- **Images:** WP Media Library (KHÔNG copy ảnh vào theme assets)
- **ADRs:** `docs/TRACK_DECISIONS.md` (ADR-007: JS stack, ADR-009: Stack Migration)

## Documentation Hub (40+ files)
- Start: `docs/README.md` → `docs/CORE_PROJECT.md` → `docs/CORE_AI_CONTEXT.md`
- Architecture: `docs/CORE_ARCHITECTURE.md`, `docs/ARCH_SCALABILITY.md`
- Design: `docs/ARCH_DESIGN_TOKENS.md` (§9 Consistency), `docs/ARCH_LUXURY_VISUAL_DIRECTION.md`
- Data Model: `docs/CORE_DATA_MODEL.md` (CPTs, ACF fields, taxonomies)
- **Implementation Guides:**
  - `docs/implement/CONVERT_HTML_TO_WP.md` — Lộ trình chuyển đổi HTML → WP
  - `docs/implement/ACF_FIELD_GROUPS.md` — 7 field groups, ~158 fields
  - `docs/implement/THEME_CONVENTIONS.md` — Naming, enqueue, structure
  - `docs/implement/PERFORMANCE_SEO.md` — Core Web Vitals, Schema.org, caching
  - `docs/implement/plan.md` — Sprint 1+2 execution plan
- Task lookup: `docs/REF_AI_HANDOVER.md`

## Critical Rules
1. **Mobile-first** — Thiết kế mobile trước, mở rộng desktop sau
2. **Performance** — PageSpeed > 90, LCP < 2.5s, CLS < 0.1, INP < 200ms (rule `06`, `13`, `14`, `17`)
3. **Accessibility** — WCAG 2.1 AA, `prefers-reduced-motion`, keyboard nav, semantic HTML5
4. **SEO** — Single H1/page, schema JSON-LD, unique title+meta, breadcrumbs (rule `15`)
5. **Security** — Nonce + capability checks, sanitize inputs, escape outputs (rule `05`, `16`)
6. **Consistency** — ALWAYS use component tokens (Layer 3). NEVER hardcode colors/spacing
7. **Clean code** — SRP, early return, max 30 lines/function, descriptive naming
8. **Extensibility** — `do_action()` + `apply_filters()` tại integration points
9. **Prefix** — Functions: `xanh_`, slugs: `xanh-`, CSS: BEM `.block__element--modifier`
10. **HTML → WP** — Follow conversion rules strictly (rule `12`)
11. **Zero errors** — No console errors, no PHP warnings, no 404 assets (rule `16`)
