---
description: Frontend CSS and JavaScript rules. Apply when writing styles or scripts for xanh-theme.
globs: wp-content/themes/xanh-theme/assets/**/*.{css,js}
---

# Frontend Rules (CSS + JS)

## Library Stack (ADR-007 + ADR-008)
| Library | Version | Size (gzip) | Purpose |
|---|---|---|---|
| **Open Props** | latest | ~5KB | CSS foundation: tokens, easing curves, normalize |
| **GSAP** | 3.x | ~15KB | Animation engine — timelines, counters, morphing |
| **ScrollTrigger** | 3.x | ~8KB | Scroll-driven animations, parallax, pin |
| **Lenis** | 1.x | ~4KB | Smooth scrolling — luxury feel, momentum |
| **Swiper** | 11.x | ~15KB | Slider/carousel — partners, materials, testimonials |
| **GLightbox** | 3.x | ~8KB | Lightbox — gallery, video popup |
| **Phosphor Icons** | 2.x | 0KB | SVG icons — inline, chỉ copy icons cần dùng |
| **Total** | | **~55KB** | |

> KHÔNG dùng: jQuery, AOS, Anime.js, Font Awesome, Tailwind CSS, Bootstrap
> Full decision log: `docs/TRACK_DECISIONS.md` (ADR-007 + ADR-008)

## CSS Architecture (3-Layer Token System)
```
Layer 1: Open Props          → Foundation tokens + normalize
Layer 2: variables.css        → XANH brand tokens (override Open Props)
Layer 3: Component tokens     → Semantic bindings (--btn-bg, --card-shadow)
```
- Load order: `open-props` → `normalize` → `variables.css` → `main.css` → `components.css` → `utilities.css` → `responsive.css`
- Vendor CSS: `swiper.min.css`, `glightbox.min.css`
- **NEVER hardcode** colors, spacing, font sizes, shadows, or easing curves
- **ALWAYS use** component tokens (Layer 3) when available: `--card-shadow`, `--btn-primary-bg`
- Mobile-first: Base styles = mobile, then `@media (min-width: ...)`
- Full token reference: `docs/ARCH_DESIGN_TOKENS.md`

## Design Tokens (variables.css)
```css
/* Colors — ALWAYS use these variables */
--color-primary: #14513D;
--color-accent: #FF8A00;
--color-white: #FFFFFF;
--color-light: #F3F4F6;
--color-beige: #D8C7A3;
--color-dark: #1A1A1A;

/* Typography */
--font-heading: 'FoundersGrotesk', Georgia, serif;
--font-body: 'Inter', -apple-system, sans-serif;

/* Spacing (8px grid) */
--space-1: 0.25rem;  --space-2: 0.5rem;   --space-3: 0.75rem;
--space-4: 1rem;     --space-6: 1.5rem;   --space-8: 2rem;
--space-12: 3rem;    --space-16: 4rem;    --space-20: 5rem;
--space-24: 6rem;

/* Breakpoints */
/* sm: 640px | md: 768px | lg: 1024px | xl: 1280px | 2xl: 1440px */

/* Transitions */
--transition-fast: 150ms ease;
--transition-base: 300ms ease;
--transition-slow: 500ms ease;
```
Full reference: `docs/ARCH_DESIGN_TOKENS.md`

## BEM Naming
```css
/* Block */          .hero { }
/* Element */        .hero__title { }
/* Modifier */       .hero--dark { }
/* Component */      .before-after-slider { }
/* State */          .is-active, .is-loading, .is-visible
```

## Responsive Breakpoints
```css
/* Mobile first — base styles are for < 640px */
@media (min-width: 640px)  { /* sm: phone landscape */ }
@media (min-width: 768px)  { /* md: tablet */ }
@media (min-width: 1024px) { /* lg: laptop */ }
@media (min-width: 1280px) { /* xl: desktop */ }
@media (min-width: 1440px) { /* 2xl: large desktop */ }
```

## JavaScript Rules
- **NO jQuery** — Vanilla ES6+ only
- All scripts: `defer` attribute, loaded in footer
- Debounce search: 300ms. Throttle scroll: via `requestAnimationFrame`
- State storage: `sessionStorage` (preloader), `localStorage` (cookie consent, read articles)

### GSAP Usage Pattern
```javascript
// Scroll-triggered entrance animation
gsap.from('.section__title', {
  scrollTrigger: { trigger: '.section', start: 'top 80%' },
  opacity: 0, y: 40, duration: 0.8,
  ease: 'power2.out'
});

// Counter animation
gsap.to(el, { textContent: targetValue, duration: 2,
  snap: { textContent: 1 }, ease: 'power1.inOut' });
```

### Lenis Usage Pattern
```javascript
const lenis = new Lenis({ lerp: 0.1, smoothWheel: true });
function raf(time) { lenis.raf(time); requestAnimationFrame(raf); }
requestAnimationFrame(raf);
// Sync with GSAP ScrollTrigger
lenis.on('scroll', ScrollTrigger.update);
```

### Conditional Loading (inc/enqueue.php)
| Script | Condition | Pages |
|---|---|---|
| `gsap.min.js` | Always | All |
| `ScrollTrigger.min.js` | Always | All (trừ 404, thank-you) |
| `lenis.min.js` | Always | All |
| `swiper-bundle.min.js` | `is_front_page()` or `is_singular('xanh_project')` | Home, Portfolio detail |
| `glightbox.min.js` | `is_singular('xanh_project')` | Portfolio detail |
| `main.js` | Always | All |
| `animations.js` | Always | All |
| `slider.js` | Same as Swiper | Home, Portfolio detail |
| `gallery.js` | Same as GLightbox | Portfolio detail |
| `filter.js` | `is_post_type_archive()` or `is_home()` | Portfolio, Blog |
| `forms.js` | `is_page('lien-he')` or `is_front_page()` | Contact, Home |
| `search.js` | `is_home()` or `is_archive()` | Blog |

## Animation Rules
- Duration: 300-600ms (UI), 600-1000ms (entrances), 1500-2000ms (counters)
- Easing: `power2.out` (entrances), `power1.inOut` (counters), `none` (linear)
- Stagger: 0.1s between siblings for luxury cascading effect
- Respect `prefers-reduced-motion`:
  ```css
  @media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
      animation-duration: 0.01ms !important;
      transition-duration: 0.01ms !important;
    }
  }
  ```
- Animate ONLY: `opacity`, `transform` (GPU-composited)
- NEVER animate: `width`, `height`, `top`, `left`, `margin`, `padding`

## Icons (Phosphor)
- Source: https://phosphoricons.com/ — copy only needed SVGs
- Store in: `assets/icons/` as individual `.svg` files
- Inline in templates: `<?php echo file_get_contents(get_theme_file_path('assets/icons/leaf.svg')); ?>`
- Sizing: CSS `width` + `height` on parent, SVG `fill: currentColor`
- 6 available weights: Thin, Light, Regular, Bold, Fill, Duotone → Use **Light** for luxury feel

## Performance Rules
- Images: Always set `width` + `height` (prevent CLS)
- Fonts: `font-display: swap`, preload critical fonts
- Icons: SVG inline (Phosphor) — NO icon font libraries
- Third-party: Lazy load (Zalo widget: DOMContentLoaded + 3s delay)
- JS budget: ~50KB gzip total (vendor) + ~15KB (custom) = ~65KB

## 27 UI Components Reference
Full specs: `docs/ARCH_UI_PATTERNS.md`
Component → Page matrix included in that file.

## Design Consistency Rules ★ CRITICAL
- **ALWAYS use semantic component tokens** over raw color tokens:
  - ✅ `background: var(--card-bg)` — NOT ❌ `background: var(--color-white)`
  - ✅ `color: var(--text-heading)` — NOT ❌ `color: var(--color-gray-900)`
  - ✅ `box-shadow: var(--card-shadow-hover)` — NOT ❌ `box-shadow: 0 10px 25px...`
- **Card hover pattern** (unified across ALL cards):
  ```css
  .card { box-shadow: var(--card-shadow); transition: var(--card-transition); }
  .card:hover { box-shadow: var(--card-shadow-hover); transform: translateY(-2px); }
  ```
- **Section backgrounds** must alternate: Dark → Light → Alt → Light → Dark
- **Easing:** Use Open Props curves (`--ease-out-3`) — NOT `ease` or custom bezier
- **Focus visible:** `outline: 2px solid var(--border-focus); outline-offset: 2px;`
- Full system: `docs/ARCH_DESIGN_TOKENS.md` §9
