---
description: WordPress theme error prevention and debugging rules. Apply when writing PHP templates or debugging theme issues.
globs: wp-content/themes/xanhdesignbuild/**/*.php
---

# Error Prevention Rules

## PHP Error Prevention

### Null Safety (ACF Fields)
```php
// ✅ ALWAYS null-check ACF fields before use
$title = get_field('hero_title');
if ($title) {
    echo '<h1>' . esc_html($title) . '</h1>';
}

// ✅ Default fallback
$cta_text = get_field('cta_text') ?: 'Đặt Lịch Tư Vấn Riêng';

// ✅ Repeater null-check
$items = get_field('process_steps');
if ($items) :
    foreach ($items as $item) :
        // render...
    endforeach;
endif;

// ✅ Image null-check before accessing array keys
$image = get_field('hero_image');
if ($image && isset($image['ID'])) {
    echo wp_get_attachment_image($image['ID'], 'xanh-hero');
}

// ❌ CRASH — No null check
echo $image['url']; // Fatal if $image is null/false
```

### WP_Query Cleanup
```php
// ✅ ALWAYS reset postdata after custom queries
$query = new WP_Query($args);
if ($query->have_posts()) :
    while ($query->have_posts()) : $query->the_post();
        get_template_part('template-parts/content/card', 'project');
    endwhile;
    wp_reset_postdata(); // ✅ MANDATORY
endif;

// ❌ NEVER use query_posts() — breaks main loop
query_posts($args); // ❌ BANNED
```

### Escape Everything (Security + Error Prevention)
```php
// Text content
echo esc_html($value);

// HTML attributes
echo '<div class="' . esc_attr($class) . '">';

// URLs
echo '<a href="' . esc_url($url) . '">';

// Rich HTML (ACF WYSIWYG)
echo wp_kses_post($rich_content);

// Integers
echo absint($number);

// JSON in data attributes
echo 'data-config="' . esc_attr(wp_json_encode($config)) . '"';

// ❌ NEVER unescaped output
echo $user_input;           // ❌ XSS
echo get_field('content');  // ❌ Unescaped
```

## JavaScript Error Prevention

### DOM Ready
```javascript
// ✅ ALWAYS check element exists before manipulating
document.addEventListener('DOMContentLoaded', () => {
    const slider = document.querySelector('.swiper');
    if (!slider) return; // ✅ Guard clause

    new Swiper(slider, { /* config */ });
});
```

### Optional Chaining
```javascript
// ✅ Safe property access
const title = document.querySelector('.hero__title')?.textContent;
const items = data?.results?.items ?? [];
```

### Event Delegation
```javascript
// ✅ Safe for dynamic content (AJAX)
document.addEventListener('click', (e) => {
    const btn = e.target.closest('.filter-btn');
    if (!btn) return;
    // handle click...
});
```

## CSS Error Prevention

### Layout Shift Prevention
```css
/* ✅ ALWAYS reserve space for images */
.card__img-wrap {
    aspect-ratio: 16 / 9;
    overflow: hidden;
    background: var(--color-light); /* placeholder color */
}

/* ✅ Font loading — no FOUT */
@font-face {
    font-display: swap; /* ✅ Show fallback immediately */
}
```

### Z-index Management
```css
/* ✅ Centralized z-index scale */
:root {
    --z-dropdown: 100;
    --z-sticky: 200;
    --z-overlay: 300;
    --z-modal: 400;
    --z-toast: 500;
}
/* ❌ NEVER use arbitrary z-index: 9999 */
```

## Console Error Checklist (Zero Errors Policy)
- [ ] No 404 errors for CSS/JS/images/fonts
- [ ] No `undefined` or `null` reference errors
- [ ] No `Mixed Content` warnings (HTTP on HTTPS)
- [ ] No `CORS` errors for CDN resources
- [ ] No deprecated function warnings
- [ ] No ACF field registration errors
- [ ] No `wp_enqueue` handle conflicts
- [ ] No missing dependency errors

## Debug Mode (Development Only)
```php
// wp-config.php — Development
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false); // Don't show on frontend
define('SCRIPT_DEBUG', true);      // Load unminified WP scripts

// wp-config.php — Production
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', false);
```

## Template File Checklist (per file)
- [ ] `<?php defined('ABSPATH') || exit; ?>` at top of inc/ files
- [ ] All ACF fields null-checked
- [ ] All output escaped (`esc_html`, `esc_url`, `esc_attr`)
- [ ] `wp_reset_postdata()` after custom queries
- [ ] No hardcoded URLs or content
- [ ] No inline `<script>` or `<style>` (use enqueue)
- [ ] Valid HTML nesting (no `<div>` inside `<p>`)
- [ ] ARIA labels on interactive elements

## Reference
- `docs/implement/THEME_CONVENTIONS.md` §5 — Security checklist
- `.agent/rules/05-security.md` — Full security rules
