---
description: Performance optimization rules. Apply when optimizing images, caching, or loading strategies.
globs: wp-content/themes/xanh-theme/**/*
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
| Alpine.js | ~15KB | CDN (jsDelivr) | `defer` head | All |
| GSAP | ~15KB | CDN (jsDelivr) | `defer` footer | All |
| ScrollTrigger | ~8KB | CDN (jsDelivr) | `defer` footer | All |
| Lenis | ~4KB | CDN (jsDelivr) | `defer` footer | All |
| Swiper | ~15KB | CDN (jsDelivr) | `defer` conditional | Home, Portfolio |
| GLightbox | ~8KB | CDN (jsDelivr) | `defer` conditional | Portfolio detail |
| Lucide Icons | ~0KB | Inline SVG | N/A | As needed |
| Custom JS total | ~12KB | Local | `defer` footer | Per-page |

> Heaviest page (Portfolio detail) ≈ 77KB gzip — lighter than React hello world

## Images
- Format: WebP (Smush auto-converts)
- Lazy load: ALL below-fold (`loading="lazy"`)
- Hero: NOT lazy-loaded (above the fold) + `<link rel="preload">`
- Always: `width` + `height` attributes (prevents CLS)
- Responsive: `srcset` for 400w, 800w, 1200w
- Progressive reveal: CSS `blur(20px) → blur(0)` on load

## CSS Loading
```
Tailwind output.css → variables.css → components.css
+ Conditional CDN: swiper-bundle.min.css, glightbox.min.css
```
- Critical CSS: LiteSpeed auto-generates (UCSS)
- Tailwind purges unused CSS via CLI build → minimal output
- No unused CSS: Only load what's needed per page

## JS Loading
- ALL scripts: `defer`, footer (except Alpine.js → defer, head)
- Vendor: CDN (jsDelivr) with pinned versions + SRI hash
- Conditional: Swiper/GLightbox CDN only on pages that need them
- NO jQuery: Alpine.js + Vanilla ES6+ (save 87KB)
- Third-party lazy: Zalo widget = DOMContentLoaded + 3s delay
- Analytics: After cookie consent only
- Video iframes: Load only on click

## Fonts
- Founders Grotesk: Only Medium (500) + Bold (700), self-hosted
- Inter: Variable font (single file), self-hosted
- `font-display: swap` on all @font-face
- Preload: `<link rel="preload" as="font" crossorigin>`

## Caching (LiteSpeed)
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

## Monitoring
- PageSpeed: Weekly check
- UptimeRobot: Uptime alerts
- Core Web Vitals: Google Search Console
- DevTools: Network tab for per-page JS audit

Full reference: `docs/ARCH_PERFORMANCE.md`
