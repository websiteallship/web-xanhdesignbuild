# Implementation Plan — XANH Theme Development

> **Phase:** Sprint 1+2 (Theme Foundation → Homepage)
> **Scope:** Setup xanh-theme, design tokens, header/footer, homepage
> **Duration:** ~25 man-days (Sprint 1: 10d + Sprint 2: 15d)

## Current State

- ✅ 40 docs + 8 rules — Research complete
- ✅ ADR-007 (JS: GSAP+Lenis+Swiper+GLightbox), ADR-008 (CSS: Open Props)
- ✅ 3-layer token system designed
- ❌ No `xanh-theme` directory exists yet
- ❌ No code written

## Execution Order (Sprint 1+2)

### Phase 1: Theme Scaffold (Day 1)

#### [NEW] Theme boilerplate files

```
wp-content/themes/xanh-theme/
├── style.css                  # Theme header (WordPress required)
├── functions.php              # Theme setup, inc/ includes
├── index.php                  # Fallback template
├── front-page.php             # Homepage template
├── page.php                   # Generic page
├── single.php                 # Blog single
├── single-xanh_project.php   # Project detail
├── archive.php                # Blog archive
├── archive-xanh_project.php  # Portfolio grid
├── 404.php                    # 404 page
├── header.php                 # Site header
├── footer.php                 # Site footer
├── searchform.php             # Search form
├── screenshot.png             # Theme thumbnail
│
├── inc/
│   ├── theme-setup.php        # add_theme_support, menus, image sizes
│   ├── enqueue.php            # CSS/JS loading (from 01-wordpress-theme rule)
│   ├── cpt-registration.php   # 3 CPTs + taxonomies
│   ├── acf-fields.php         # ACF field group registration
│   ├── custom-functions.php   # xanh_get_*() helpers
│   ├── ajax-handlers.php      # AJAX endpoints
│   ├── template-tags.php      # Reusable template functions
│   └── walker-nav.php         # Custom nav walker
│
├── template-parts/
│   ├── hero/
│   ├── content/
│   ├── sections/
│   ├── components/
│   └── forms/
│
├── assets/
│   ├── css/
│   │   ├── variables.css      # Layer 2+3 tokens
│   │   ├── main.css           # Base + typography
│   │   ├── components.css     # BEM component styles
│   │   ├── utilities.css      # Helper classes
│   │   ├── responsive.css     # Media queries
│   │   └── vendor/            # swiper.min.css, glightbox.min.css
│   ├── js/
│   │   ├── main.js            # App init, Lenis, GSAP global
│   │   ├── animations.js      # Scroll animations
│   │   ├── slider.js          # Swiper init
│   │   ├── gallery.js         # GLightbox init
│   │   ├── filter.js          # AJAX filtering
│   │   ├── forms.js           # Form UX
│   │   ├── search.js          # Blog search
│   │   └── vendor/            # gsap, scrolltrigger, lenis, swiper, glightbox
│   ├── icons/                 # Phosphor SVGs
│   ├── fonts/                 # FoundersGrotesk + Inter
│   └── images/
│       └── placeholders/
│
└── languages/
```

> [!IMPORTANT]
> This creates ~30+ files. All file contents follow the patterns defined in the rules files.

---

### Phase 2: Design Tokens (Day 2)

#### [NEW] `assets/css/variables.css`
- Open Props imports (CDN)
- Layer 2: Brand tokens (colors, typography, spacing) from `ARCH_DESIGN_TOKENS.md`
- Layer 3: Component tokens (40+ semantic variables from §9)

#### [NEW] `assets/css/main.css`
- Open Props normalize (base reset)
- Typography styles (Founders Grotesk + Inter, type scale)
- Base element styles (body, headings, links, lists)
- Section base class
- Container classes

#### [NEW] `assets/css/utilities.css`
- `.sr-only`, `.text-center`, `.text-left`, `.text-right`
- `.grid`, `.grid-2`, `.grid-3`, `.grid-4`
- `.container`, `.container-narrow`, `.container-wide`
- Visibility helpers, spacing helpers

#### [NEW] `assets/css/responsive.css`
- Breakpoint overrides (sm, md, lg, xl, 2xl)
- Grid responsive collapse
- Typography scaling

---

### Phase 3: Font + Vendor Setup (Day 2-3)

#### [NEW] `assets/fonts/`
- Copy FoundersGrotesk Medium + Bold (from `docs/FONT/`)
- Copy Inter Variable (from `docs/FONT/`)

#### [NEW] `assets/js/vendor/`
- Download minified: gsap, ScrollTrigger, lenis, swiper-bundle, glightbox
- Download minified CSS: swiper.min.css, glightbox.min.css

#### [NEW] `assets/icons/`
- Download essential Phosphor SVGs (Light weight): house, phone, envelope, map-pin, calendar, clock, arrow-right, caret-down, x, list, leaf, drop, sun, shield-check

---

### Phase 4: Theme Setup + Enqueue (Day 3-4)

#### [NEW] `functions.php`
- Include all `inc/` files
- Define `XANH_THEME_VERSION`

#### [NEW] `inc/theme-setup.php`
- `add_theme_support('title-tag', 'post-thumbnails', 'html5', 'custom-logo')`
- Register nav menus: `primary`, `footer`
- Register custom image sizes

#### [NEW] `inc/enqueue.php`
- Complete enqueue function (copy from `01-wordpress-theme.md` rule)
- Open Props → variables.css → main.css → components → utilities → responsive
- Vendor JS: GSAP → ScrollTrigger → Lenis (global), Swiper + GLightbox (conditional)
- wp_localize_script for AJAX nonce

#### [NEW] `inc/cpt-registration.php`
- `xanh_project` CPT (portfolio)
- `xanh_testimonial` CPT
- `xanh_team` CPT
- Taxonomies: `project_type`, `project_status`

#### [NEW] `inc/custom-functions.php`
- `xanh_get_featured_projects()`
- `xanh_get_testimonials()`
- `xanh_get_team_members()`
- Counter data helper

---

### Phase 5: Header + Footer (Day 4-5)

#### [NEW] `header.php`
- Sticky nav (scroll → shadow + compact)
- Logo (SVG inline)
- Primary nav menu (walker)
- Mobile hamburger + off-canvas menu
- CTA button "Đặt Lịch Tư Vấn Riêng"
- Preloader (first visit, sessionStorage skip)

#### [NEW] `footer.php`
- 4-column layout (dark primary bg)
- Logo + tagline, nav links, contact info, social links
- Schema markup (LocalBusiness JSON-LD)
- Copyright
- Lazy-load Zalo widget (3s delay)
- Cookie consent banner

#### [NEW] `inc/walker-nav.php`
- Custom nav walker for accessibility
- Active state class

---

### Phase 6: Homepage (Day 6-12)

#### [NEW] `front-page.php`
- 10 sections with `do_action()` hooks between each

#### [NEW] Template parts (10 files):
1. `template-parts/hero/hero-home.php` — Full-screen hero + video bg option + CTA
2. `template-parts/sections/section-pain-points.php` — 3-column icon blocks
3. `template-parts/sections/section-4xanh.php` — Card flip philosophy
4. `template-parts/sections/section-counter.php` — Animated counters (GSAP)
5. `template-parts/sections/section-before-after.php` — Swiper slider
6. `template-parts/sections/section-process.php` — 6-step timeline
7. `template-parts/sections/section-portfolio-featured.php` — 3 project cards
8. `template-parts/sections/section-testimonials.php` — Swiper + beige bg
9. `template-parts/sections/section-blog-latest.php` — 3 blog cards
10. `template-parts/sections/section-cta-final.php` — Full-width CTA block

#### [NEW] `assets/css/components.css`
- BEM styles for all homepage components
- Card, counter, process, testimonial styles

#### [NEW] `assets/js/main.js`
- Lenis init, GSAP global, scroll reveal with `data-animate` attributes

#### [NEW] `assets/js/animations.js`
- Counter GSAP animation
- Stagger card entrances
- Parallax hero background

---

### Phase 7: Responsive + Polish (Day 13-15)

- All breakpoints: 640 → 768 → 1024 → 1280 → 1440px
- Mobile floating CTA bar
- Touch interactions (Swiper touch)
- `prefers-reduced-motion` support
- Skeleton loading CSS
- Progressive image reveal CSS

---

## Verification Plan

### During Development
- Browser DevTools responsive mode (iPhone SE → iPad → Desktop)
- Lighthouse audit per section
- Validate HTML (W3C validator)

### After Sprint 2
- [ ] PageSpeed > 90 (mobile + desktop)
- [ ] All 10 homepage sections render correctly
- [ ] Header sticky + mobile menu works
- [ ] Footer links + schema markup valid
- [ ] GSAP animations smooth (60fps)
- [ ] Lenis smooth scroll working
- [ ] Swiper sliders touch-enabled
- [ ] Counter animation triggers once
- [ ] CLS < 0.1 (no layout shifts)
- [ ] Font loading (no FOUT/FOIT)
- [ ] Preloader works (first visit only)
