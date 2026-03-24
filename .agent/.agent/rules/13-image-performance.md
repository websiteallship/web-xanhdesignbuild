---
description: WordPress image performance rules. Apply when outputting images in PHP templates or handling WP Media Library assets.
globs: wp-content/themes/xanhdesignbuild/**/*.php
---

# Image Performance Rules

## Custom Image Sizes
```php
// Registered in inc/theme-setup.php
'xanh-hero'    → 1920×1080 (crop) — Hero banners
'xanh-card'    → 640×480 (crop)   — Blog/project cards
'xanh-thumb'   → 400×300 (crop)   — Thumbnails
'xanh-partner' → 320×120 (soft)   — Partner logos
'xanh-team'    → 400×500 (crop)   — Team members
```

## Loading Strategy

### Above-fold (Hero, Logo, Header)
```php
// ✅ Hero image — NO lazy, fetchpriority high
echo wp_get_attachment_image($id, 'xanh-hero', false, [
    'class'         => 'w-full h-full object-cover',
    'loading'       => 'eager',
    'fetchpriority' => 'high',
    'decoding'      => 'async',
]);
// ✅ Preload in <head>
<link rel="preload" as="image" href="<?php echo esc_url($hero_url); ?>"
      type="image/webp" fetchpriority="high">
```

### Below-fold (Cards, Gallery, Partners)
```php
// ✅ Lazy load — DEFAULT behavior
echo wp_get_attachment_image($id, 'xanh-card', false, [
    'class'   => 'w-full h-full object-cover',
    'loading' => 'lazy',
]);
```

## MANDATORY Rules
1. **ALWAYS** include `width` + `height` → prevents CLS
2. **ALWAYS** use `wp_get_attachment_image()` → auto adds srcset, width, height, alt
3. **NEVER** use raw `<img src="<?php echo $url; ?>">` → no srcset, no lazy, no dimensions
4. **NEVER** lazy-load hero/above-fold images → kills LCP
5. **ALWAYS** add `alt` text (WP Media Library alt field, or fallback to title)
6. **NEVER** hardcode image paths in templates → use ACF Image field + WP Media Library
7. **ALWAYS** specify the correct image size → don't load `full` for card thumbnails
8. **ALWAYS** add `decoding="async"` for non-critical images

## Alt Text Fallback
```php
function xanh_get_image_alt($image_id) {
    $alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
    return $alt ?: get_the_title($image_id);
}
```

## Background Images (CSS)
```php
// For section backgrounds — use inline style with ACF
<?php $bg = get_field('section_bg_image');
if ($bg) : ?>
<section style="background-image: url('<?php echo esc_url($bg['url']); ?>')">
<?php endif; ?>
<!-- Background images are NOT lazy-loadable natively.
     Use Intersection Observer in JS for deferred backgrounds. -->
```

## WebP Handling
- Smush Pro auto-converts uploads to WebP (production)
- WordPress 6.9+ serves WebP when browser supports
- Theme code does NOT need to handle format conversion
- `wp_get_attachment_image()` auto-includes WebP in srcset

## Reference
- `docs/implement/PERFORMANCE_SEO.md` §2 — Full image optimization guide
