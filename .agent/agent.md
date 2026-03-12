# XANH - Design & Build — AI Agent Context

> **Đây là file mô tả tổng quan dự án. AI agent PHẢI đọc file này trước khi code.**

---

## 1. Dự Án

**XANH - Design & Build** — Website giới thiệu dịch vụ thiết kế & thi công nội thất/xây dựng cao cấp tại Khánh Hòa, Việt Nam.

| Key | Value |
|---|---|
| **Brand** | XANH - Design & Build |
| **Positioning** | Warm Luxury — Sang trọng tinh tế, ấm áp, không phô trương |
| **Target** | Khách hàng cao cấp — chủ biệt thự, doanh nhân, gia đình thành đạt |
| **Tagline** | "Đừng Chỉ Xây Một Ngôi Nhà. Hãy Xây Dựng Sự Bình Yên." |
| **Triết lý** | 4 Xanh: Chi phí minh bạch / Vật liệu bền vững / Vận hành thông minh / Giá trị trường tồn |
| **Ngôn ngữ** | Tiếng Việt (chính), chuẩn bị sẵn i18n cho English (Phase 2) |
| **URL dự kiến** | xanhdesignbuild.com |

---

## 2. Tech Stack

| Layer | Technology |
|---|---|
| **CMS** | WordPress (latest) + Custom Theme `xanh-theme` |
| **PHP** | Min 7.4, WordPress Coding Standards |
| **CSS** | Open Props (foundation) + Vanilla CSS (BEM) — 3-layer token system |
| **JS** | Vanilla ES6+ (NO jQuery) |
| **Animation** | GSAP 3.x + ScrollTrigger (~23KB gzip) |
| **Smooth Scroll** | Lenis 1.x (~4KB gzip) |
| **Slider** | Swiper 11.x (~15KB gzip, conditional) |
| **Lightbox** | GLightbox 3.x (~8KB gzip, conditional) |
| **Icons** | Phosphor Icons (SVG, Light weight, inline) |
| **Fonts** | Founders Grotesk (500+700, self-hosted) + Inter (variable, self-hosted) |
| **Plugins** | ACF Pro, Fluent Form (SMTP), LiteSpeed Cache, Smush, Classic Editor |
| **JS Budget** | ~55KB gzip vendor + ~12KB custom per page |

### CSS Architecture: 3-Layer Token System
```
Layer 1: Open Props          → Foundation (easing, shadows, normalize)
Layer 2: variables.css       → XANH brand tokens (colors, fonts, spacing)
Layer 3: Component tokens    → Semantic bindings (--card-bg, --btn-primary-bg)
```
**Load order:** Open Props → normalize → variables.css → main.css → components.css → utilities.css → responsive.css

### JS Loading
- **Global (all pages):** GSAP → ScrollTrigger → Lenis → main.js → animations.js
- **Conditional:** Swiper + slider.js (Home, Portfolio) | GLightbox + gallery.js (Portfolio detail)
- **Lazy:** Zalo widget (3s delay), Analytics (after consent)

---

## 3. Brand & Design

### Colors
| Token | Hex | Usage |
|---|---|---|
| `--color-primary` | `#14513D` | Nav, footer, dark sections |
| `--color-accent` | `#FF8A00` | CTA buttons, active states |
| `--color-light` | `#F3F4F6` | Alternating section bg |
| `--color-beige` | `#D8C7A3` | Warm luxury sections (testimonials, story) |
| `--color-white` | `#FFFFFF` | Default page bg |
| `--color-gray-800` | `#1F2937` | Body text |
| `--color-gray-900` | `#111827` | Headlines |

### Typography
- **Headings:** `'FoundersGrotesk'` — `letter-spacing: -0.02em` (tighter = premium)
- **Body:** `'Inter'` — `letter-spacing: 0.01em`, `line-height: 1.6`
- **Type scale:** `clamp()` responsive — Hero 72px → H1 56px → H2 40px → Body 16px

### Design Principles
1. **Restraint** — Ít chi tiết, chất lượng cao. Content/whitespace = 40/60
2. **Subtlety** — Hover: `translateY(-4px)` + shadow. KHÔNG bounce/shake/flash
3. **Consistency** — LUÔN dùng component tokens. KHÔNG hardcode values
4. **Warmth** — Tông ấm (beige), ảnh có người, copy mời gọi
5. **Section BG rotation:** Dark → White → Light → White → Dark → Beige → White → Dark

### Anti-Patterns ❌
- Bounce animation, color flash, shake effect
- Text typing animation, confetti, particles
- Entrance duration > 1s, quá nhiều animation cùng lúc

---

## 4. Voice & Copywriting

**Tone:** Warm Luxury — Chuyên nghiệp + Gần gũi + Sang trọng tinh tế

| ❌ Phổ thông | ✅ XANH (Warm Luxury) |
|---|---|
| "Xây nhà rẻ" | "Mỗi đồng đầu tư đều được tôn trọng" |
| "Liên hệ ngay" | "Đặt lịch trao đổi riêng" |
| "Đội ngũ giỏi" | "Biến mong ước thành hiện thực từ từng viên gạch" |

**CTA patterns:** "Đặt Lịch Tư Vấn Riêng" / "Khám Phá Dự Toán Của Bạn" / "Bắt Đầu Câu Chuyện Của Bạn"

**CẤM dùng:** "giá rẻ", "khuyến mãi", "ưu đãi sốc", "tiết kiệm", "bình dân", "liên hệ ngay"

---

## 5. Sitemap & Pages

| Page | Template | Sections | Slug |
|---|---|---|---|
| **Homepage** | `front-page.php` | 10 sections | `/` |
| **About** | `page-about.php` | 8 sections | `/gioi-thieu/` |
| **Portfolio Grid** | `archive-xanh_project.php` | AJAX filter | `/du-an/` |
| **Portfolio Detail** | `single-xanh_project.php` | 10 sections | `/du-an/{slug}/` |
| **Green Solution** | `page-green.php` | 6 sections | `/giai-phap-xanh/` |
| **Blog List** | `archive.php` / `home.php` | Search + grid | `/tin-tuc/` |
| **Blog Detail** | `single.php` | ToC + progress | `/tin-tuc/{slug}/` |
| **Contact** | `page-contact.php` | Form + FAQ | `/lien-he/` |
| **404** | `404.php` | Error page | — |
| **Thank You** | `page-thank-you.php` | Confirmation | `/cam-on/` |

---

## 6. Data Model

### Custom Post Types
| CPT | Slug | Archive | REST |
|---|---|---|---|
| `xanh_project` | `/du-an/` | Yes | `show_in_rest: true` |
| `xanh_testimonial` | — | No | `show_in_rest: true` |
| `xanh_team` | — | No | `show_in_rest: false` |

### Taxonomies
- `project_type` — Biệt thự, Nhà phố, Căn hộ, Vu sáng
- `project_status` — Đã bàn giao, Đang thi công

### ACF Field Groups
- `group_project` — Location, area, budget, 3D images, material board, timeline
- `group_testimonial` — Rating, role, project link, avatar
- `group_team` — Position, experience, photo
- `group_estimator` — Price per sqm (basic/standard/premium), disclaimer
- `group_site_settings` — Phone, address, social links, Google Maps embed

---

## 7. Coding Standards

### Naming
- **PHP functions:** `xanh_` prefix + snake_case → `xanh_get_featured_projects()`
- **PHP hooks:** `xanh_` prefix → `do_action('xanh_after_hero', $page_id)`
- **CSS classes:** BEM → `.project-card__title--featured`
- **CSS states:** `.is-active`, `.is-loading`, `.is-visible`
- **JS modules:** Object pattern → `XanhApp.init()`
- **Files:** kebab-case → `content-project-card.php`

### Rules
- **ALWAYS** use component tokens (Layer 3) over raw colors
- **ALWAYS** sanitize input + escape output (WordPress security)
- **ALWAYS** `defer` scripts, conditional loading
- **ALWAYS** nonce + capability checks on AJAX
- **NEVER** use jQuery, `document.write()`, `eval()`, `innerHTML`
- **NEVER** hardcode colors, spacing, shadows, easing
- **NEVER** skip `width` + `height` on `<img>`
-  SRP (Single Responsibility), early returns, max 30 lines/function

### Template Pattern
```php
<?php get_header(); ?>
<main id="main-content" class="page-{slug}">
  <?php do_action('xanh_before_hero', get_the_ID()); ?>
  <?php get_template_part('template-parts/hero', '{slug}'); ?>
  <?php do_action('xanh_after_hero', get_the_ID()); ?>
  <?php get_template_part('template-parts/sections', 'section-name'); ?>
</main>
<?php get_footer(); ?>
```

---

## 8. Performance Targets

| Metric | Target |
|---|---|
| PageSpeed | > 90 (mobile + desktop) |
| LCP | < 2.5s |
| CLS | < 0.1 |
| INP | < 200ms |
| TTFB | < 200ms |
| Page weight | < 1.5MB |
| JS per page | < 70KB gzip |

### Perceived Performance
- Preloader: Logo pulse → fade (first visit, sessionStorage skip)
- Skeleton loading: Shimmer gradient on AJAX
- Progressive image: `blur(20px) → blur(0)` on load
- Staggered entrance: GSAP stagger 100ms between cards
- Smooth scroll: Lenis `lerp: 0.07`

---

## 9. Documentation (40 files)

Tất cả docs nằm trong `docs/` directory. Bắt đầu đọc:
1. `docs/README.md` → Table of Contents
2. `docs/CORE_PROJECT.md` → Brand & sitemap overview
3. `docs/CORE_ARCHITECTURE.md` → File structure, data flow
4. `docs/ARCH_DESIGN_TOKENS.md` → Full token system (§9 Consistency)
5. `docs/ARCH_LUXURY_VISUAL_DIRECTION.md` → Micro-interactions, typography, photography

### Key Docs by Task
| Task | Read |
|---|---|
| Coding | `docs/GOV_CODING_STANDARDS.md` |
| Page layout | `docs/PAGE_{PAGE_NAME}.md` |
| Components | `docs/ARCH_UI_PATTERNS.md` (27 components) |
| Design | `docs/ARCH_DESIGN_TOKENS.md` + `docs/ARCH_LUXURY_VISUAL_DIRECTION.md` |
| SEO | `docs/GOV_SEO_STRATEGY.md` |
| Security | `docs/GOV_SECURITY.md` |
| Performance | `docs/ARCH_PERFORMANCE.md` |
| Scalability | `docs/ARCH_SCALABILITY.md` |
| Content/Copy | `docs/GOV_BRAND_VOICE.md` |
| Decisions | `docs/TRACK_DECISIONS.md` (8 ADRs) |

---

## 10. Rules Files

8 rule files trong `.agent/rules/` — AI agent tự động áp dụng theo glob pattern:

| File | Scope |
|---|---|
| `00-project-core.md` | Mọi file — brand, stack, critical rules |
| `01-wordpress-theme.md` | `*.php` — theme architecture, enqueue pattern, hooks |
| `02-frontend-css-js.md` | `*.css, *.js` — 3-layer CSS, GSAP patterns, consistency |
| `03-ux-ui-design.md` | `*.php, *.css` — luxury design, typography, anti-patterns |
| `04-seo.md` | `*.php` — on-page SEO, schema, local SEO |
| `05-security.md` | `*.php` — sanitize, nonce, hardening |
| `06-performance.md` | `*` — JS budget, images, caching, perceived perf |
| `07-content-brand-voice.md` | `*` — warm luxury voice, CTA, keywords |
