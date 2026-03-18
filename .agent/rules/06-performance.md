---
description: Performance optimization rules. Apply when optimizing images, caching, or loading strategies.
globs: wp-content/themes/xanhdesignbuild/**/*
---

# Performance Rules

## Targets
| Metric | Target |
|---|---|
| PageSpeed Score | > 90 (mobile + desktop) |
| LCP | < 2.5s |
| CLS | < 0.1 |
| INP | < 200ms |
| TTFB | < 200ms |
| Total page weight | < 1.5MB |
| JS bundle (gzip) | < 100KB per page |

## JS Budget (ADR-007 + ADR-009)
| Library | Gzip | Source | Loading | Pages |
|---|---|---|---|---|
| Tailwind CSS (compiled) | ~10KB | Local (CLI build) | `<link>` head | All |
| GSAP | ~15KB | CDN (jsDelivr) | `defer` footer | All |
| ScrollTrigger | ~8KB | CDN (jsDelivr) | `defer` footer | All |
| Lenis | ~4KB | CDN (jsDelivr) | `defer` footer | All |
| Swiper | ~15KB | CDN (jsDelivr) | `defer` conditional | Home, Portfolio |
| GLightbox | ~8KB | CDN (jsDelivr) | `defer` conditional | Portfolio detail |
| Lucide Icons | ~0KB | Inline SVG / CDN | `defer` footer | As needed |
| Custom JS total | ~12KB | Local | `defer` footer | Per-page |

> Heaviest page (Portfolio detail) ≈ 62KB gzip — lighter than React hello world
> WP 6.5+ script strategy API: `['strategy' => 'defer', 'in_footer' => true]`

## Images
- Format: WebP (Smush auto-converts on production)
- Lazy load: ALL below-fold (`loading="lazy"`)
- Hero: NOT lazy-loaded (above the fold) + `fetchpriority="high"` + `<link rel="preload">`
- Always: `width` + `height` attributes (prevents CLS)
- Responsive: `wp_get_attachment_image()` auto-generates srcset
- Use registered image sizes: `xanh-hero`, `xanh-card`, `xanh-thumb`
- Progressive reveal: CSS `blur(20px) → blur(0)` on load
- Detailed rules: `rules/13-image-performance.md`

## CSS Loading
```
Tailwind output.css → variables.css → components.css
+ Conditional page CSS (home.css, about.css, contact.css)
+ Conditional CDN: swiper-bundle.min.css, glightbox.min.css
```
- Critical CSS: LiteSpeed auto-generates (UCSS) on production
- Tailwind purges unused CSS via CLI build → minimal output
- No unused CSS: Only load what's needed per page

## JS Loading
- ALL scripts: `defer`, footer (WP 6.5 script strategy API)
- Vendor: CDN (jsDelivr) with pinned versions
- Conditional: Swiper/GLightbox CDN only on pages that need them
- NO jQuery: Vanilla ES6+ (save 87KB)
- Third-party lazy: Zalo widget = load + 3s delay
- Analytics: After cookie consent only
- Video iframes: Load only on click

## Fonts
- Inter: Variable font (single file), self-hosted
- `font-display: swap` on all @font-face
- Preload: `<link rel="preload" as="font" type="font/woff2" crossorigin>`
- Unicode subsetting: `U+0000-024F,U+1EA0-1EF9` (Latin + Vietnamese)

## Caching (LiteSpeed — Production)
- Page cache: ON (TTL: 7 days)
- CSS/JS minify: ON | Combine: OFF (HTTP/2)
- Critical CSS: Auto-generate
- Mobile separate cache: ON
- Purge on: Post/Plugin update

## Perceived Performance ★ (Luxury UX)
| Technique | Effect |
|---|---|
| **Preloader** | Logo pulse → fade (1.5s, skip repeat via sessionStorage) |
| **Skeleton loading** | Shimmer gradient on AJAX filter |
| **Progressive image** | blur(20px) → sharp + subtle scale |
| **Staggered entrance** | GSAP stagger 100ms between cards |
| **Optimistic UI** | Button spinner immediately on click |
| **Smooth scroll** | Lenis lerp: 0.07, 60fps momentum |
| **No layout shift** | Width+height on all imgs, font-display: swap |
| **Reduced motion** | `prefers-reduced-motion: reduce` → skip animations |

## Monitoring
- PageSpeed: Weekly check
- UptimeRobot: Uptime alerts
- Core Web Vitals: Google Search Console
- DevTools: Network tab for per-page JS audit

## Related Rules
- `13-image-performance.md` — Image loading strategies, alt text
- `14-animation-performance.md` — GSAP/CSS compositor, ScrollTrigger rules
- `17-wp-optimization.md` — WP bloat removal, heartbeat, transient caching

Full reference: `docs/implement/PERFORMANCE_SEO.md`
