---
description: Tối ưu hiệu suất tải trang WordPress. Dùng trước khi launch hoặc khi PageSpeed score thấp.
---

# Tối Ưu Hiệu Suất Tải Trang — XANH Theme

## Skills cần đọc trước
- `@web-performance-optimization` — Core Web Vitals, bundle size, caching, runtime perf
- `@fixing-motion-performance` — Animation jank, compositor, ScrollTrigger
- `@wordpress` — WP backend optimization
- `@wp-performance` — Profiling, query optimization, object caching

## Rules BẮT BUỘC
- `06-performance.md` — **Targets + JS budget + image strategy** (đọc toàn bộ)
- `13-image-performance.md` — Image loading, preload, responsive
- `14-animation-performance.md` — GSAP/CSS compositor, ScrollTrigger
- `17-wp-optimization.md` — WP bloat removal, heartbeat, transient caching

---

## Performance Targets (rule `06`)

| Metric | Target | Công cụ đo |
|---|---|---|
| PageSpeed Score | > 90 (mobile + desktop) | PageSpeed Insights |
| LCP | < 2.5s | PageSpeed / DevTools |
| CLS | < 0.1 | PageSpeed / DevTools |
| INP | < 200ms | PageSpeed / DevTools |
| TTFB | < 200ms | DevTools Network |
| Total page weight | < 1.5MB | DevTools Network |
| JS bundle (gzip) | < 100KB per page | DevTools Network |

---

## Bước 1: Đo Baseline

// turbo
```bash
# Kiểm tra file sizes
$theme = "wp-content/themes/xanhdesignbuild"
$cssFiles = Get-ChildItem "$theme/assets/css" -Recurse -Filter "*.css" | Select-Object Name, @{N='SizeKB';E={[math]::Round($_.Length/1KB,1)}}
$jsFiles = Get-ChildItem "$theme/assets/js" -Recurse -Filter "*.js" | Select-Object Name, @{N='SizeKB';E={[math]::Round($_.Length/1KB,1)}}
Write-Host "=== CSS Files ==="
$cssFiles | Format-Table -AutoSize
Write-Host "=== JS Files ==="
$jsFiles | Format-Table -AutoSize
Write-Host "=== Totals ==="
Write-Host "CSS Total: $([math]::Round(($cssFiles | Measure-Object -Property SizeKB -Sum).Sum, 1)) KB"
Write-Host "JS Total: $([math]::Round(($jsFiles | Measure-Object -Property SizeKB -Sum).Sum, 1)) KB"
```

**Sau đó, dùng browser:**
1. Mở DevTools → Network tab → Hard reload (Ctrl+Shift+R)
2. Ghi lại: Total size, Requests, DOMContentLoaded, Load time
3. Chạy PageSpeed Insights: `https://pagespeed.web.dev/`

---

## Bước 2: CSS Performance

### 2.1 — Tailwind Purge
// turbo
```bash
cd wp-content/themes/xanhdesignbuild && npm run build
# Check output.css size — should be minimal after purge
$out = Get-Item "assets/css/output.css"
Write-Host "output.css: $([math]::Round($out.Length/1KB,1)) KB"
```

### 2.2 — CSS Loading Strategy
- [ ] Load order: `output.css` → `variables.css` → `components.css`
- [ ] Page-specific CSS: conditional per page
- [ ] Vendor CSS conditional: `swiper-bundle.min.css` (Home + Portfolio Detail), `glightbox.min.css` (Portfolio Detail)
- [ ] No `@import url()` trong CSS (blocking)
- [ ] No unused CSS trên mỗi trang

### 2.3 — Critical CSS (Production)
- [ ] LiteSpeed auto-generates UCSS
- [ ] Inline critical CSS cho above-the-fold
- [ ] CSS minification: ON (LiteSpeed)
- [ ] CSS combine: OFF (HTTP/2 multiplexing)

---

## Bước 3: JavaScript Performance

### 3.1 — JS Budget Check (rule `06`)

| Library | Expected Gzip | Loading | Pages |
|---|---|---|---|
| Tailwind CSS (compiled) | ~10KB | `<link>` head | All |
| GSAP | ~15KB | `defer` footer | All |
| ScrollTrigger | ~8KB | `defer` footer | All |
| Lenis | ~4KB | `defer` footer | All |
| Swiper | ~15KB | `defer` conditional | Home, Portfolio |
| GLightbox | ~8KB | `defer` conditional | Portfolio detail |
| Custom JS total | ~12KB | `defer` footer | Per-page |
| **Heaviest page** | **~62KB** | | Portfolio detail |

### 3.2 — Loading Strategy
- [ ] ALL scripts: `defer` + `in_footer: true` (WP 6.5 script strategy API)
- [ ] Vendor: CDN (jsDelivr) với pinned versions
- [ ] Conditional: Swiper/GLightbox CDN chỉ trang cần
- [ ] NO jQuery loaded (save 87KB)
- [ ] Third-party lazy: Zalo widget load + 3s delay
- [ ] Video iframes: load only on click (YouTube facade)
- [ ] Analytics: after cookie consent only

### 3.3 — JS Optimization
- [ ] Debounce scroll/resize events (≥ 100ms)
- [ ] `requestAnimationFrame` cho visual updates
- [ ] Intersection Observer thay vì scroll listener cho lazy effects
- [ ] Event delegation cho dynamic content
- [ ] No `querySelectorAll` trong loops
- [ ] `ScrollTrigger.refresh()` sau dynamic content load

---

## Bước 4: Image Performance (rule `13`)

### 4.1 — Above-the-fold (Hero Images)
- [ ] **KHÔNG** lazy load (above-the-fold)
- [ ] `fetchpriority="high"` trên hero image
- [ ] `<link rel="preload" as="image">` trong `<head>`
- [ ] Responsive: `wp_get_attachment_image()` → auto `srcset`
- [ ] Format: WebP (Smush auto-convert production)

#### Thêm LCP Preload cho trang có Hero Banner mới

Khi thêm một trang mới có hero banner (full-screen image above-the-fold), cần bổ sung vào hàm `xanh_resource_hints()` trong `inc/enqueue.php`. Code pattern chung:

```php
// Trong hàm xanh_resource_hints(), thêm elseif vào chuỗi if/elseif:
} elseif ( is_page( 'slug-trang-moi' ) ) {
    $field = function_exists( 'get_field' ) ? get_field( 'ten_acf_field_image' ) : null;
    if ( is_array( $field ) && isset( $field['ID'] ) ) {
        $lcp_src = wp_get_attachment_image_url( $field['ID'], 'full' );
    } elseif ( is_numeric( $field ) && $field ) {
        $lcp_src = wp_get_attachment_image_url( $field, 'full' );
    } else {
        $lcp_src = site_url( '/wp-content/uploads/2026/03/fallback-image.png' );
    }
}
```

**Checklist khi thêm trang mới:**
1. Xác định ACF field name của hero image trong template (vd: `about_hero_image`, `contact_hero_image`)
2. Xác định ảnh fallback tương ứng trong `/wp-content/uploads/`
3. Thêm `elseif` vào chuỗi điều kiện trong `xanh_resource_hints()`
4. Đảm bảo thẻ `<img>` trong template có `loading="eager"` (KHÔNG lazy load)
5. Verify: mở DevTools → `<head>` phải có `<link rel="preload" as="image" ... fetchpriority="high">`

**Các trang đã implement:** Home (`is_front_page()`), About (`is_page('gioi-thieu')`)

### 4.2 — Below-the-fold
- [ ] `loading="lazy"` trên TẤT CẢ images below-fold
- [ ] Aspect ratio placeholders → prevent CLS
- [ ] `width` + `height` attributes luôn có
- [ ] Progressive reveal: `blur(20px) → blur(0)` on load

### 4.3 — Image Sizes
- [ ] Registered custom sizes: `xanh-hero`, `xanh-card`, `xanh-thumb`
- [ ] `wp_get_attachment_image()` với size đúng (KHÔNG full-size)
- [ ] Background: `var(--color-light)` placeholder color

---

## Bước 5: Font Performance (rule `06`)

- [ ] Inter: Variable font (1 file), self-hosted
- [ ] `font-display: swap` trên `@font-face`
- [ ] `<link rel="preload" as="font" type="font/woff2" crossorigin>`
- [ ] Unicode subsetting: `U+0000-024F,U+1EA0-1EF9` (Latin + Vietnamese)
- [ ] KHÔNG load từ Google Fonts CDN (self-hosted nhanh hơn)

---

## Bước 6: WordPress Backend (rule `17`)

### 6.1 — WP Bloat Removal
- [ ] Emoji scripts removed (~20KB saved)
- [ ] oEmbed removed
- [ ] Meta tags cleaned (rsd_link, wlwmanifest, wp_generator)
- [ ] REST API link removed from head
- [ ] Feed links removed

### 6.2 — Block Styles Dequeue
- [ ] `wp-block-library` dequeued
- [ ] `wp-block-library-theme` dequeued
- [ ] `classic-theme-styles` dequeued
- [ ] `global-styles` dequeued

### 6.3 — Heartbeat & Revisions
- [ ] Heartbeat: 60s interval (admin), disabled (frontend)
- [ ] Post revisions: max 5 (`WP_POST_REVISIONS = 5`)
- [ ] Autosave: 120s (`AUTOSAVE_INTERVAL = 120`)

### 6.4 — Caching
- [ ] Transient caching cho featured projects, team, testimonials
- [ ] Transient clear on `save_post` hook
- [ ] WP Object Cache (if available on production)

---

## Bước 7: Perceived Performance — Luxury UX (rule `06`)

| Technique | Mục đích | Status |
|---|---|---|
| Preloader | Logo pulse → fade (1.5s, skip via sessionStorage) | [ ] |
| Skeleton loading | Shimmer gradient on AJAX filter | [ ] |
| Progressive image | blur(20px) → sharp + subtle scale | [ ] |
| Staggered entrance | GSAP stagger 100ms between cards | [ ] |
| Optimistic UI | Button spinner immediately on click | [ ] |
| Smooth scroll | Lenis lerp: 0.07, 60fps momentum | [ ] |
| No layout shift | Width+height on all imgs, font-display: swap | [ ] |
| Reduced motion | `prefers-reduced-motion: reduce` → skip animations | [ ] |

---

## Bước 8: Animation Performance (rule `14`)

- [ ] Chỉ animate `transform`, `opacity`, `filter` (GPU-composited)
- [ ] KHÔNG animate `width`, `height`, `margin`, `padding`, `top`, `left`
- [ ] `will-change` chỉ trên phần tử animate, ≤ 15 cùng lúc
- [ ] GSAP: `once: true` cho entrance animations
- [ ] GSAP: Kill unused ScrollTriggers
- [ ] `contain: layout style` trên sections
- [ ] CSS `transform3d(0,0,0)` force GPU cho heavy animations

---

## Bước 9: Production Setup (.htaccess)

### Browser Caching
```apache
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/webp              "access plus 1 year"
    ExpiresByType image/avif              "access plus 1 year"
    ExpiresByType font/woff2              "access plus 1 year"
    ExpiresByType text/css                "access plus 1 month"
    ExpiresByType application/javascript  "access plus 1 month"
</IfModule>
```

### Gzip Compression
```apache
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/css text/javascript
    AddOutputFilterByType DEFLATE application/javascript application/json
    AddOutputFilterByType DEFLATE image/svg+xml
</IfModule>
```

### LiteSpeed Cache (Production)
- [ ] Page cache: ON (TTL: 7 days)
- [ ] CSS/JS minify: ON
- [ ] CSS/JS combine: OFF (HTTP/2)
- [ ] Critical CSS: Auto-generate
- [ ] Mobile separate cache: ON
- [ ] Purge on: Post/Plugin update

---

## Bước 10: Verify Results

1. **PageSpeed Insights:** Run lại → Score > 90?
2. **DevTools Network:** Tổng weight < 1.5MB?
3. **DevTools Performance:** No long tasks > 50ms?
4. **CLS:** Layout stable? (no image jumps, no font flash)
5. **LCP:** Hero loads < 2.5s?
6. **INP:** Interactions responsive < 200ms?

---

## Monitoring (Post-launch)

| Công cụ | Tần suất | Mục đích |
|---|---|---|
| PageSpeed Insights | Weekly | Core Web Vitals |
| Google Search Console | Weekly | CWV field data |
| UptimeRobot | Real-time | Uptime alerts |
| DevTools Network | Per-release | JS audit per page |

---

## Tài Liệu Tham Chiếu

| File | Nội dung |
|---|---|
| `.agent/rules/06-performance.md` | Targets, JS budget, image, font, caching |
| `.agent/rules/13-image-performance.md` | Image loading strategies |
| `.agent/rules/14-animation-performance.md` | GSAP/CSS compositor rules |
| `.agent/rules/17-wp-optimization.md` | WP bloat removal, heartbeat, transient |
| `docs/implement/PERFORMANCE_SEO.md` | Full performance reference |
