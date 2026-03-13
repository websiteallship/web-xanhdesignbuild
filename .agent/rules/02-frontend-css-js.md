---
description: Frontend CSS and JavaScript rules. Apply when writing styles or scripts for xanh-theme.
globs: wp-content/themes/xanh-theme/assets/**/*.{css,js}
---

# Frontend Rules (CSS + JS)

## Library Stack (ADR-007 + ADR-009)
| Library | Version | Size (gzip) | Source | Purpose |
|---|---|---|---|---|
| **Tailwind CSS** | 4.x | ~8-15KB (purged) | CLI build | Utility-first CSS framework |
| **Alpine.js** | 3.15.x | ~15KB | CDN (jsDelivr) | Declarative interactivity (menus, accordions, tabs) |
| **GSAP** | 3.12.x | ~15KB | CDN (jsDelivr) | Animation engine — timelines, counters, morphing |
| **ScrollTrigger** | 3.12.x | ~8KB | CDN (jsDelivr) | Scroll-driven animations, parallax, pin |
| **Lenis** | 1.3.x | ~4KB | CDN (jsDelivr) | Smooth scrolling — luxury feel, momentum |
| **Swiper** | 11.x | ~15KB | CDN (jsDelivr) | Slider/carousel — partners, materials, testimonials |
| **GLightbox** | 3.x | ~8KB | CDN (jsDelivr) | Lightbox — gallery, video popup |
| **Lucide Icons** | latest | 0KB | Inline SVG | Line icons — 1500+ icons, consistent stroke |
| **Total** | | **~80-95KB** | | |

> KHÔNG dùng: jQuery, AOS, Anime.js, Font Awesome, Bootstrap, Open Props
> Full decision log: `docs/TRACK_DECISIONS.md` (ADR-007 + ADR-009)

## CSS Architecture (Tailwind CSS + CSS Variables)
```
Layer 1: Tailwind CSS       → Utility-first classes (CLI build, purged)
Layer 2: variables.css      → XANH brand tokens (CSS custom properties)
Layer 3: components.css     → Custom component styles (where Tailwind alone isn't enough)
```
- Build: `npx @tailwindcss/cli -i ./assets/css/input.css -o ./assets/css/output.css --minify`
- Load order: `output.css` (Tailwind + base) → `variables.css` → `components.css`
- Vendor CSS: `swiper` + `glightbox` via CDN `<link>`
- **NEVER hardcode** colors, spacing, font sizes, shadows, or easing curves
- **ALWAYS use** CSS variables for brand tokens: `--color-primary`, `--color-accent`, etc.
- **Use Tailwind utilities** for layout, spacing, typography, responsive
- **Use CSS variables** for brand-specific values that Tailwind config defines
- Mobile-first: Tailwind's responsive prefixes (`sm:`, `md:`, `lg:`, `xl:`, `2xl:`)
- Full token reference: `docs/ARCH_DESIGN_TOKENS.md`

## Tailwind CSS Configuration
```javascript
// tailwind.config.js (in xanh-theme root)
export default {
  content: ['./**/*.php', './assets/js/**/*.js'],
  theme: {
    extend: {
      colors: {
        primary: '#14513D',
        accent: '#FF8A00',
        light: '#F3F4F6',
        beige: '#D8C7A3',
        dark: '#1A1A1A',
      },
      fontFamily: {
        heading: ['Inter', '-apple-system', 'sans-serif'],
        body: ['Inter', '-apple-system', 'sans-serif'],
      },
      screens: {
        'sm': '640px',
        'md': '768px',
        'lg': '1024px',
        'xl': '1280px',
        '2xl': '1440px',
      },
    },
  },
}
```

## Design Tokens (variables.css)
```css
/* Colors — CSS custom properties for dynamic usage */
--color-primary: #14513D;
--color-accent: #FF8A00;
--color-white: #FFFFFF;
--color-light: #F3F4F6;
--color-beige: #D8C7A3;
--color-dark: #1A1A1A;

/* Typography */
--font-heading: 'Inter', -apple-system, sans-serif;
--font-body: 'Inter', -apple-system, sans-serif;

/* Spacing (8px grid) — use Tailwind utilities primarily */
--space-1: 0.25rem;  --space-2: 0.5rem;   --space-3: 0.75rem;
--space-4: 1rem;     --space-6: 1.5rem;   --space-8: 2rem;
--space-12: 3rem;    --space-16: 4rem;    --space-20: 5rem;
--space-24: 6rem;

/* Breakpoints — handled by Tailwind (sm/md/lg/xl/2xl) */

/* Transitions */
--transition-fast: 150ms ease;
--transition-base: 300ms ease;
--transition-slow: 500ms ease;
```
Full reference: `docs/ARCH_DESIGN_TOKENS.md`

## CSS Class Strategy
```css
/* Tailwind utilities — primary approach */
<div class="flex items-center gap-6 p-8 bg-primary text-white rounded-lg">

/* Component classes — for complex/reusable patterns */
.hero { }
.hero__title { }
.hero--dark { }

/* State classes */
.is-active, .is-loading, .is-visible
```
> Use Tailwind utilities for layout + spacing. Use component CSS for complex animations, multi-state components, or elements with many pseudo-selectors.

## Responsive — Tailwind Breakpoints
```html
<!-- Mobile first — base = mobile, prefix = larger screens -->
<div class="text-sm sm:text-base md:text-lg lg:text-xl">
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
<div class="px-6 md:px-8 lg:px-8">
```

## JavaScript Rules
- **NO jQuery** — Alpine.js + Vanilla ES6+
- All vendor scripts: CDN with `defer`, loaded in footer
- Alpine.js: `defer` in `<head>` (official recommendation)
- Debounce search: 300ms. Throttle scroll: via `requestAnimationFrame`
- State storage: `sessionStorage` (preloader), `localStorage` (cookie consent, read articles)

### Alpine.js Usage Pattern
```html
<!-- Mobile menu toggle -->
<nav x-data="{ open: false }">
  <button @click="open = !open" :aria-expanded="open">Menu</button>
  <div x-show="open" x-transition.opacity x-cloak>
    <!-- nav links -->
  </div>
</nav>

<!-- FAQ Accordion -->
<div x-data="{ active: null }">
  <template x-for="(item, index) in items">
    <div>
      <button @click="active = active === index ? null : index">
        <span x-text="item.question"></span>
      </button>
      <div x-show="active === index" x-collapse>
        <p x-text="item.answer"></p>
      </div>
    </div>
  </template>
</div>

<!-- Filter tabs -->
<div x-data="{ tab: 'all' }">
  <button @click="tab = 'all'" :class="tab === 'all' && 'is-active'">Tất cả</button>
  <button @click="tab = 'villa'" :class="tab === 'villa' && 'is-active'">Biệt thự</button>
</div>
```

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

### CDN Enqueue (inc/enqueue.php)
| Script | Source | Condition | Pages |
|---|---|---|---|
| `alpinejs` CDN | jsDelivr | Always | All (defer, head) |
| `gsap` CDN | jsDelivr | Always | All |
| `ScrollTrigger` CDN | jsDelivr | Always | All (trừ 404, thank-you) |
| `lenis` CDN | jsDelivr | Always | All |
| `swiper` CDN | jsDelivr | `is_front_page()` or `is_singular('xanh_project')` | Home, Portfolio detail |
| `glightbox` CDN | jsDelivr | `is_singular('xanh_project')` | Portfolio detail |
| `main.js` | Local | Always | All |
| `animations.js` | Local | Always | All |
| `slider.js` | Local | Same as Swiper | Home, Portfolio detail |
| `gallery.js` | Local | Same as GLightbox | Portfolio detail |
| `filter.js` | Local | `is_post_type_archive()` or `is_home()` | Portfolio, Blog |
| `forms.js` | Local | `is_page('lien-he')` or `is_front_page()` | Contact, Home |
| `search.js` | Local | `is_home()` or `is_archive()` | Blog |

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

## Icons (Lucide)
- Source: https://lucide.dev/ — copy only needed SVGs
- Usage options:
  1. **Inline SVG** (preferred): Copy SVG markup directly into templates
  2. **CDN script**: `<script src="https://unpkg.com/lucide@latest"></script>` + `lucide.createIcons()`
- Inline in templates: `<?php echo file_get_contents(get_theme_file_path('assets/icons/house.svg')); ?>`
- Store custom SVGs in: `assets/icons/` as individual `.svg` files
- Sizing: CSS `width` + `height` on parent, SVG `stroke: currentColor`
- Default: 24x24, stroke-width 2
- Style: Consistent line weight, clean minimal design → luxury feel

## Performance Rules
- Images: Always set `width` + `height` (prevent CLS)
- Fonts: `font-display: swap`, preload critical fonts
- Icons: SVG inline (Lucide) — NO icon font libraries
- Third-party: Lazy load (Zalo widget: DOMContentLoaded + 3s delay)
- JS budget: ~80KB gzip total (vendor CDN) + ~15KB (custom) = ~95KB
- CDN scripts: Pin version, add SRI hash for security

## Design Consistency Rules ★ CRITICAL
- **ALWAYS use CSS variables** for brand-specific values:
  - ✅ `bg-[var(--color-primary)]` or custom Tailwind color `bg-primary`
  - ❌ `bg-[#14513D]` — NEVER hardcode hex in templates
- **Card hover pattern** (unified across ALL cards):
  ```css
  .card { @apply shadow-md transition-all duration-300; }
  .card:hover { @apply shadow-xl -translate-y-1; }
  ```
- **Section backgrounds** must alternate: Dark → Light → Alt → Light → Dark
- **Easing:** Use GSAP easing (`power2.out`) for JS animations, Tailwind transitions for CSS
- **Focus visible:** `@apply outline-2 outline-offset-2 outline-primary`
- Full system: `docs/ARCH_DESIGN_TOKENS.md` §9
