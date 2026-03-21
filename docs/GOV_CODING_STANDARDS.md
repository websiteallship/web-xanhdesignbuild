# GOV_CODING_STANDARDS — Chuẩn Coding

> **Dự án:** Website XANH - Design & Build
> **Phiên bản:** 2.0 | **Cập nhật:** 2026-03-12
> **Library Stack:** Open Props + GSAP + Lenis + Swiper + GLightbox

---

## 1. HTML Standards

### Nguyên tắc
- Semantic HTML5: `<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<aside>`, `<footer>`
- Mỗi trang chỉ 1 `<h1>`, heading hierarchy đúng (h1 > h2 > h3)
- Tất cả `<img>` phải có `alt`, `width`, `height`
- Form elements phải có `<label>` liên kết
- Interactive elements phải có `id` duy nhất
- `lang="vi"` trên `<html>`

### Naming Convention
```html
<!-- Section IDs: kebab-case, mô tả rõ ràng -->
<section id="hero-home">
<section id="philosophy-4xanh">
<section id="process-steps">

<!-- BEM for classes -->
<div class="project-card">
  <img class="project-card__image">
  <h3 class="project-card__title">
  <span class="project-card__tag project-card__tag--completed">
</div>
```

---

## 2. CSS Standards

### Architecture (3-Layer Token System)
```
Layer 1: Open Props          → Foundation (easing, shadows, sizes, normalize)
Layer 2: variables.css       → XANH brand tokens (override Open Props)
Layer 3: Component tokens    → Semantic bindings (--card-bg, --btn-primary-bg)
```

### File Organization
```
assets/css/
├── vendor/
│   ├── swiper.min.css        # Swiper styles
│   └── glightbox.min.css     # GLightbox styles
├── variables.css             # Layer 2+3: Brand + Component tokens
├── main.css                  # Reset, base, typography, layout
├── components.css            # BEM component styles
├── utilities.css             # Helper classes
└── responsive.css            # Media query overrides
```

### Quy tắc
```css
/* ✅ ĐÚNG — Component tokens (Layer 3) */
.card { background: var(--card-bg); }
.card { box-shadow: var(--card-shadow); }
.card:hover { box-shadow: var(--card-shadow-hover); transform: translateY(-4px); }
.section { padding: var(--section-padding-y) var(--section-padding-x); }
.btn--primary { background: var(--btn-primary-bg); }

/* ✅ OK — Brand tokens (Layer 2) khi không có Layer 3 */
.custom-element { color: var(--color-primary); }

/* ✅ ĐÚNG — Open Props easing (Layer 1) */
.card { transition: transform 400ms var(--ease-out-3); }

/* ❌ SAI */
.card { background: white; }              /* Hardcode color */
.section { padding: 80px 32px; }          /* Magic numbers */
.card { transition: all 0.3s ease; }      /* Generic easing, animates "all" */
.card { box-shadow: 0 10px 25px rgba(0,0,0,.1); } /* Hardcode shadow */
```

### BEM Examples
```
/* Blocks */   .hero, .project-card, .blog-card, .counter-bar, .faq-accordion
/* Elements */ .hero__title, .hero__subtitle, .hero__cta
/* Modifiers */.section--dark, .section--beige, .btn--outline
/* States */   .is-active, .is-loading, .is-visible, .is-scrolled
```

---

## 3. JavaScript Standards

### Architecture — Module Pattern
```javascript
/**
 * Mỗi file JS là 1 module object với init() method.
 * main.js import và init tất cả modules.
 */

// === main.js ===
import { XanhApp } from './modules.js';

document.addEventListener('DOMContentLoaded', () => {
  XanhApp.init();
});

// === Module pattern ===
const XanhApp = {
  lenis: null,

  init() {
    this.initLenis();
    this.initGlobalAnimations();
    this.initComponents();
  },

  initLenis() {
    this.lenis = new Lenis({ lerp: 0.07, smoothWheel: true });
    this.lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add((time) => this.lenis.raf(time * 1000));
    gsap.ticker.lagSmoothing(0);
  },

  initGlobalAnimations() {
    // Fade-up entrance for all sections
    gsap.utils.toArray('[data-animate="fade-up"]').forEach(el => {
      gsap.from(el, {
        scrollTrigger: { trigger: el, start: 'top 85%' },
        opacity: 0, y: 30, duration: 0.8,
        ease: 'power2.out'
      });
    });
  },

  initComponents() {
    // Conditional init based on DOM presence
    if (document.querySelector('.swiper')) this.initSliders();
    if (document.querySelector('[data-counter]')) this.initCounters();
    if (document.querySelector('[data-lightbox]')) this.initLightbox();
  }
};
```

### GSAP Patterns
```javascript
// ✅ Counter animation
gsap.to(el, {
  textContent: targetValue,
  duration: 2,
  snap: { textContent: 1 },
  ease: 'power1.inOut',
  scrollTrigger: { trigger: el, start: 'top 75%' }
});

// ✅ Stagger cards (luxury cascading)
gsap.from('.card', {
  scrollTrigger: { trigger: '.grid', start: 'top 85%' },
  opacity: 0, y: 30,
  duration: 0.8,
  stagger: 0.1,
  ease: 'power2.out'
});

// ✅ Parallax background
gsap.to('.hero__bg', {
  yPercent: -20,
  ease: 'none',
  scrollTrigger: { trigger: '.hero', scrub: 1 }
});
```

### Swiper Pattern
```javascript
// ✅ Init with options object
const partnerSwiper = new Swiper('.partner-logos .swiper', {
  slidesPerView: 2,
  spaceBetween: 24,
  loop: true,
  autoplay: { delay: 3000, disableOnInteraction: false },
  breakpoints: {
    768: { slidesPerView: 4 },
    1024: { slidesPerView: 6 },
  },
});
```

### GLightbox Pattern
```javascript
// ✅ Init lightbox
const lightbox = GLightbox({
  selector: '[data-lightbox]',
  touchNavigation: true,
  loop: true,
  autoplayVideos: true,
});
```

### JS Rules
- `const` / `let` — KHÔNG dùng `var`
- `defer` attribute cho tất cả scripts
- Event delegation khi có nhiều listeners
- Debounce search: 300ms. Throttle scroll: `requestAnimationFrame`
- `prefers-reduced-motion` check trước khi animate
- KHÔNG dùng `document.write()`, `eval()`, `innerHTML` (XSS risk)

### Error Handling
```javascript
// ✅ Defensive init — check element exists before init
function initSlider() {
  const container = document.querySelector('.swiper');
  if (!container) return; // Silent fail, không crash

  try {
    new Swiper(container, { /* options */ });
  } catch (error) {
    console.warn('[XANH] Slider init failed:', error.message);
  }
}

// ✅ AJAX error handling
async function fetchProjects(params) {
  try {
    const response = await fetch(xanhAjax.url, {
      method: 'POST',
      body: new FormData(/* ... */),
    });
    if (!response.ok) throw new Error(`HTTP ${response.status}`);
    const data = await response.json();
    if (!data.success) throw new Error(data.data?.message || 'Unknown error');
    return data.data;
  } catch (error) {
    console.error('[XANH] Fetch failed:', error.message);
    showErrorState(); // Show user-friendly message
    return null;
  }
}
```

---

## 4. PHP Standards (WordPress)

### Naming
```php
// Functions: xanh_ prefix + snake_case
function xanh_register_post_types() {}
function xanh_enqueue_scripts() {}
function xanh_get_featured_projects() {}

// CPTs: xanh_ prefix
register_post_type('xanh_project', [...]);

// Hooks: xanh_ prefix
do_action('xanh_after_project_content', $post_id);
apply_filters('xanh_estimator_price', $price, $area);
```

### Clean Code Principles
```php
// ✅ Single Responsibility — mỗi function làm 1 việc
function xanh_get_project_data($post_id) { /* chỉ lấy data */ }
function xanh_format_project_card($data) { /* chỉ format HTML */ }
function xanh_render_project_grid($projects) { /* chỉ render grid */ }

// ✅ Early return — tránh nested conditions
function xanh_get_testimonial($project_id) {
    if (!$project_id) return null;
    $query = new WP_Query([...]);
    if (!$query->have_posts()) return null;
    return $query->posts[0];
}

// ✅ Null-safe ACF reads
function xanh_get_project_location($post_id) {
    $location = get_field('project_location', $post_id);
    return $location ?: 'Khánh Hòa'; // Default fallback
}

// ❌ SAI — God function, quá nhiều trách nhiệm
function xanh_do_everything($post_id) {
    // Lấy data + format + render + cache + log → chia nhỏ ra
}
```

### Security Patterns
```php
// ✅ AJAX handler pattern
function xanh_ajax_filter_projects() {
    check_ajax_referer('xanh_filter_nonce', 'nonce');

    $type = sanitize_text_field(wp_unslash($_POST['type'] ?? ''));
    $paged = absint($_POST['paged'] ?? 1);

    $query = xanh_get_filtered_projects(['project_type' => $type, 'paged' => $paged]);

    ob_start();
    while ($query->have_posts()) {
        $query->the_post();
        get_template_part('template-parts/content', 'project-card');
    }
    wp_reset_postdata();

    wp_send_json_success([
        'html' => ob_get_clean(),
        'total' => $query->found_posts,
        'pages' => $query->max_num_pages,
    ]);
}
```

---

## 5. Git Workflow

### Branch Strategy
```
main              ← Production (live site)
├── develop       ← Integration branch
│   ├── feature/home-page
│   ├── feature/portfolio-filter
│   ├── feature/estimator-form
│   └── fix/mobile-cta-bar
```

### Commit Convention
```
feat: add Before/After slider component
fix: mobile floating CTA overlapping footer
style: update hero section spacing tokens
refactor: extract counter animation to GSAP module
perf: lazy load Zalo widget after 3s delay
docs: update PAGE_HOME specs
chore: update GSAP to 3.12
```

### Quy trình
1. Branch từ `develop`
2. Develop + test local (LocalWP)
3. Commit với message convention
4. Push → Review (nếu team)
5. Merge vào `develop`
6. Test staging
7. Merge `develop` → `main` (deploy)

---

## 6. File Naming

| Loại | Convention | Ví dụ |
|---|---|---|
| PHP templates | `kebab-case.php` | `front-page.php`, `single-xanh_project.php` |
| Template parts | `category-name.php` | `content-project-card.php` |
| CSS files | `kebab-case.css` | `variables.css`, `components.css` |
| JS files | `kebab-case.js` | `main.js`, `animations.js` |
| Vendor files | `name.min.js/css` | `gsap.min.js`, `swiper.min.css` |
| Images | `kebab-case` | `hero-home.webp`, `logo-full.png` |
| SVG icons | `kebab-case.svg` | `leaf.svg`, `phone.svg` |
| Docs | `UPPER_SNAKE.md` | `CORE_PROJECT.md`, `PAGE_HOME.md` |

---

## 7. Clean Code Checklist

Mỗi file/function MỚI phải pass:

- [ ] **Single Responsibility** — Làm 1 việc, làm tốt
- [ ] **Descriptive naming** — Đọc tên hiểu mục đích (no `$x`, `$temp`, `$data2`)
- [ ] **Early returns** — Tránh if/else lồng sâu
- [ ] **No magic numbers** — Dùng tokens/constants
- [ ] **Error handling** — Try/catch, null checks, fallbacks
- [ ] **Security** — Sanitize input, escape output, nonce
- [ ] **DRY** — Không duplicate code → extract to helper
- [ ] **Max function length** — Target < 30 lines
- [ ] **Max file length** — Target < 300 lines (split if larger)
- [ ] **Comments** — When (logic phức tạp), not What (code rõ ràng)

---

## Tài Liệu Liên Quan

- `ARCH_DESIGN_TOKENS.md` — 3-layer token system, component tokens
- `ARCH_LUXURY_VISUAL_DIRECTION.md` — Micro-interaction specs
- `CORE_ARCHITECTURE.md` — File structure, data flow
- `TRACK_DECISIONS.md` — ADR-007 (JS stack), ADR-008 (Open Props)
