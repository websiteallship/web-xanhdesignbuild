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
| **URL dự kiến** | xanhdesignbuild.vn |

---

## 2. Tech Stack

| Layer | Technology |
|---|---|
| **CMS** | WordPress (latest) + Custom Theme `xanh-theme` |
| **PHP** | Min 7.4, WordPress Coding Standards |
| **CSS** | Tailwind CSS 4.x (CLI build, purged) + CSS Variables (brand tokens) |
| **JS** | Alpine.js 3.15.x (CDN) + Vanilla ES6+ (NO jQuery) |
| **Animation** | GSAP 3.12.x + ScrollTrigger (~23KB gzip) |
| **Smooth Scroll** | Lenis 1.3.x (~4KB gzip) |
| **Slider** | Swiper 11.x (~15KB gzip, conditional) |
| **Lightbox** | GLightbox 3.x (~8KB gzip, conditional) |
| **Icons** | Lucide Icons (CDN or inline SVG, stroke-based) |
| **Fonts** | Inter (variable, self-hosted) |
| **Plugins** | ACF Pro, Fluent Form (SMTP), LiteSpeed Cache, Smush, Classic Editor |
| **JS Budget** | ~80-95KB gzip vendor (CDN) + ~12KB custom per page |
| **ADRs** | `docs/TRACK_DECISIONS.md` (ADR-007: JS stack, ADR-009: Stack Migration) |

> **KHÔNG dùng:** jQuery, AOS, Anime.js, Font Awesome, Bootstrap, Open Props

### CSS Architecture: Tailwind + CSS Variables

```
Layer 1: Tailwind CSS       → Utility-first classes (CLI build, purged)
Layer 2: variables.css      → XANH brand tokens (CSS custom properties)
Layer 3: components.css     → Custom component styles (where Tailwind alone isn't enough)
```

- **Build:** `npx @tailwindcss/cli -i ./assets/css/input.css -o ./assets/css/output.css --minify`
- **Load order:** `output.css` (Tailwind + base) → `variables.css` → `components.css`
- Tailwind responsive prefixes: `sm:` 640px, `md:` 768px, `lg:` 1024px, `xl:` 1280px, `2xl:` 1440px

### JS Loading

- **Global (all pages):** Alpine.js (defer, head) → GSAP → ScrollTrigger → Lenis → main.js → animations.js
- **Conditional:** Swiper + slider.js (Home, Portfolio) | GLightbox + gallery.js (Portfolio detail)
- **Lazy:** Zalo widget (3s delay), Analytics (after consent)

---

## 3. Brand & Design

### Colors (Ratio 60-25-10-5)

| Token | Hex | Usage | Ratio |
|---|---|---|---|
| `--color-primary` | `#14513D` | Nav, footer, hero, dark sections | ~60% |
| `--color-beige` | `#D8C7A3` | Section xen kẽ, testimonial, process | ~25% |
| `--color-white` | `#FFFFFF` | Breathing room sections | ~10% |
| `--color-accent` | `#FF8A00` | CTA buttons, active states only | ~5% |
| `--color-light` | `#F3F4F6` | Alternating section bg | — |
| `--color-dark` | `#1A1A1A` | Body text, headlines | — |

### Typography

- **Headings + Body:** `'Inter'` (variable, self-hosted)
- **Heading style:** `letter-spacing: -0.02em` (tighter = premium), `font-weight: 700`
- **Body:** `letter-spacing: 0.01em`, `line-height: 1.6`, `font-weight: 400`
- **Uppercase labels:** `letter-spacing: 0.1em` (spaced = elegant)
- **Type scale:** `clamp()` responsive — Hero 72px → H1 56px → H2 40px → Body 16px

### Design Principles

1. **Restraint** — Ít chi tiết, chất lượng cao. Content/whitespace = 40/60
2. **Subtlety** — Hover: `translateY(-4px)` + shadow. KHÔNG bounce/shake/flash
3. **Consistency** — LUÔN dùng component tokens. KHÔNG hardcode values
4. **Warmth** — Tông ấm (beige), ảnh có người, copy mời gọi
5. **Section BG rotation:** GREEN → BEIGE → GREEN → GREEN(grad) → WHITE → BEIGE → GREEN → BEIGE → WHITE → GREEN(grad) → GREEN footer

### Anti-Patterns ❌

- Bounce animation, color flash, shake effect
- Text typing animation, confetti, particles
- 3D rotate (trừ Card Flip 4 Xanh)
- Entrance duration > 1s, quá nhiều animation cùng lúc
- Hardcode hex colors, spacing, shadows

---

## 4. Voice & Copywriting

**Tone:** Warm Luxury — Chuyên nghiệp + Gần gũi + Sang trọng tinh tế (like Aesop/Aman)

| ❌ Phổ thông | ✅ XANH (Warm Luxury) |
|---|---|
| "Xây nhà rẻ" | "Mỗi đồng đầu tư đều được tôn trọng" |
| "Liên hệ ngay" | "Đặt lịch trao đổi riêng" |
| "Đội ngũ giỏi" | "Biến mong ước thành hiện thực từ từng viên gạch" |

**CTA patterns:** "Đặt Lịch Tư Vấn Riêng" / "Khám Phá Dự Toán Của Bạn" / "Bắt Đầu Câu Chuyện Của Bạn" / "Khám Phá Các Tác Phẩm"

**Brand Keywords:** ✅ Tinh tế, Riêng biệt, Trường tồn, Kiến tạo, Di sản, Minh bạch, Bền vững, Đồng hành

**CẤM dùng:** "giá rẻ", "khuyến mãi", "ưu đãi sốc", "tiết kiệm", "bình dân", "liên hệ ngay", "số 1", "bậc nhất"

**Storytelling:** ASPIRATION → EMPATHY → SOLUTION → PROOF → INVITATION (bắt đầu bằng ước mơ, KHÔNG nỗi đau)

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
- **CSS states:** `.is-active`, `.is-loading`, `.is-visible`, `.is-scrolled`
- **JS modules:** Object pattern → `XanhHome.init()`, `XanhAbout.init()`
- **Files:** kebab-case → `content-project-card.php`

### Rules

- **ALWAYS** use CSS variables (Layer 2) or Tailwind utilities — NEVER hardcode colors/spacing
- **ALWAYS** sanitize input + escape output (WordPress security)
- **ALWAYS** `defer` scripts, conditional loading
- **ALWAYS** nonce + capability checks on AJAX
- **ALWAYS** check library existence before using (`typeof gsap !== 'undefined'`)
- **NEVER** use jQuery, `document.write()`, `eval()`, `innerHTML` for user content
- **NEVER** hardcode colors, spacing, shadows, easing
- **NEVER** skip `width` + `height` on `<img>`
- **NEVER** animate `width`, `height`, `top`, `left`, `margin`, `padding`
- SRP (Single Responsibility), early returns, max 30 lines/function

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
| JS per page | < 100KB gzip |

### Perceived Performance

- Preloader: Logo pulse → fade (first visit, sessionStorage skip)
- Skeleton loading: Shimmer gradient on AJAX
- Progressive image: `blur(20px) → blur(0)` on load
- Staggered entrance: GSAP stagger 100ms between cards
- Smooth scroll: Lenis `lerp: 0.07`

---

## 9. Wireframes (Static Prototypes)

Wireframes nằm trong `wireframes/` — HTML/CSS/JS tĩnh, sẵn sàng convert sang WordPress PHP.

### File Structure

```
wireframes/
├── _shared/base.css           ← Shared CSS (ALL pages load FIRST)
├── shared/base.js             ← XanhBase module (shared JS logic)
├── shared/analysis_results.md ← CSS analysis reference
├── homepage_02/               ← Homepage (home-page.html/css/js)
├── about/                     ← About (about.html/css/js)
├── blog/                      ← Blog list + detail (6 files)
├── contact/                   ← Contact (contact.html/css/js)
├── portfolio/                 ← Portfolio grid + detail (6 files)
├── SVG/                       ← SVG assets
├── img/                       ← Shared images
└── backup_js_20260318/        ← JS backup (reference only)
```

### base.css — Shared Components (CRITICAL)

Components đã có trong `_shared/base.css` — KHÔNG khai báo lại trong page CSS:
- `.btn`, `.btn--primary`, `.btn--outline`, `.btn--ghost`
- `.section-header`, `.section-eyebrow`, `.section-title`, `.section-subtitle`
- `.anim-fade-up`, `.anim-fade-left`, `.anim-fade-right`, `.anim-scale-in`
- `.icon-circle`, `.site-container`, `.text-lead`, `.text-body`
- State classes: `.is-visible`, `.is-scrolled`, `.is-active`

### base.js — XanhBase Module

Shared logic VÀ animation defaults — Page JS PHẢI gọi `XanhBase.*` thay vì viết lại:
- `XanhBase.initLucide()`, `XanhBase.initLenis()`, `XanhBase.registerGSAP()`
- `XanhBase.initHeroReveal()`, `XanhBase.initScrollReveal()`, `XanhBase.animateCounters()`
- `XanhBase.initBackToTop()`, `XanhBase.prefersReducedMotion()`
- `ANIM_DEFAULTS.fadeUp`, `ANIM_DEFAULTS.stagger`

### HTML Script Load Order

```html
<!-- 1. Vendor CDN --> GSAP, ScrollTrigger, Lenis, Swiper, Lucide
<!-- 2. Shared Base --> ../shared/base.js (PHẢI trước page JS)
<!-- 3. Page Module --> home-page.js (LUÔN sau base.js)
```

---

## 10. Documentation (40 files)

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
| CSS files | `docs/ARCH_CSS_FILES.md` |
| JS architecture | `docs/ARCH_JAVASCRIPT.md` |
| SEO | `docs/GOV_SEO_STRATEGY.md` |
| Security | `docs/GOV_SECURITY.md` |
| Performance | `docs/ARCH_PERFORMANCE.md` |
| Scalability | `docs/ARCH_SCALABILITY.md` |
| Content/Copy | `docs/GOV_BRAND_VOICE.md` |
| Decisions | `docs/TRACK_DECISIONS.md` (8 ADRs) |

---

## 11. Rules Files

13 rule files trong `.agent/rules/` — AI agent tự động áp dụng theo glob pattern:

| File | Globs | Scope |
|---|---|---|
| `00-project-core.md` | `**/*` | Brand, stack, critical rules |
| `01-wordpress-theme.md` | `*.php` | Theme architecture, enqueue, hooks |
| `02-frontend-css-js.md` | `*.css, *.js` | Tailwind + CSS Variables, Alpine.js, GSAP, container system |
| `03-ux-ui-design.md` | `*.php, *.css` | Luxury design, typography, section BG rotation |
| `04-seo.md` | `*.php` | On-page SEO, schema JSON-LD, local SEO |
| `05-security.md` | `*.php` | Sanitize, nonce, WordPress hardening |
| `06-performance.md` | `*` | JS budget, images, caching, perceived perf |
| `07-content-brand-voice.md` | `*.md, *.txt, *.php` | Warm luxury voice, CTA templates, keywords |
| `08-cross-section-consistency.md` | `*.html, *.css, *.php, *.js` | Shared UI classes: section header, buttons, cards, icons, spacing, animations |
| `09-css-optimization.md` | `*.css` | Rendering perf, selectors, shorthand, DRY, media queries |
| `10-js-optimization.md` | `*.js` | Module pattern, GSAP best practices, event handling, memory, state |
| `11-js-wireframe-architecture.md` | `wireframes/**/*.{js,html}` | base.js + page module pattern, script load order, components |
| `11-wireframe-css-architecture.md` | `wireframes/**/*.css` | base.css + page CSS pattern, override rules, duplicate prevention |

---

## 12. Workflows

1 workflow trong `.agent/workflows/`:

| File | Trigger | Mô tả |
|---|---|---|
| `html-to-wp-php.md` | `/html-to-wp-php` | Convert wireframe HTML/CSS/JS → WordPress PHP theme (10 bước) |

### Workflow: HTML → WordPress PHP (Tóm tắt)

1. Phân tích HTML nguồn → mapping elements sang WordPress functions
2. Scaffold theme structure (`wp-content/themes/{slug}/`)
3. Tạo `header.php` + `footer.php` (thay tags tĩnh bằng WP functions)
4. Enqueue assets (`inc/enqueue.php`) — global + conditional
5. Convert sections HTML → `template-parts/` PHP files
6. Dynamic content: WP Loop, CPT, ACF fields
7. Theme support + escaping
8. Verification: visual match, responsive, console, Theme Check

---

## 13. Layout Container System

Mỗi section **BẮT BUỘC** dùng container class:

| Class | `max-width` | Sử dụng |
|---|---|---|
| `.site-container` | `1280px` | Mặc định — tất cả sections thông thường |
| `.site-container--hero` | `1440px` | Chỉ Hero — ấn tượng thị giác đầu tiên |
| `.site-container--full` | `1280px` | Layout full-width — grid vẫn giới hạn |
| `.content-text` | `800px` | Nội dung text thuần — luxury reading |

> ❌ KHÔNG dùng `max-w-[1280px] mx-auto px-8` — dùng `.site-container`

---

## 14. Cross-Section Consistency (Tham chiếu nhanh)

Trước khi tạo section mới, kiểm tra shared classes:

- [ ] Header: `.section-header` + `.section-eyebrow` + `.section-title` + `.section-subtitle`
- [ ] CTA: `.btn--primary` / `.btn--outline` / `.btn--ghost`
- [ ] Cards: base `.card` class + variant
- [ ] Icons: `.icon-circle` class
- [ ] Spacing: section tokens, không hardcode
- [ ] Animation: `.anim-*` shared classes + `ANIM_DEFAULTS`
- [ ] Tags: `.tag` / `.badge` classes
- [ ] Text: `.text-lead` / `.text-body`
- [ ] Container: `.site-container`
- [ ] Màu: variant theo nền section (sáng/tối)

> **Chi tiết đầy đủ:** `.agent/rules/08-cross-section-consistency.md`
