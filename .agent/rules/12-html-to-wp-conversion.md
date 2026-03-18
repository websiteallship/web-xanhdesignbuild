---
description: Rules for converting HTML/CSS/JS wireframes to WordPress PHP templates. Apply when creating or editing template files derived from wireframes.
globs: wp-content/themes/xanhdesignbuild/**/*.php
---

# HTML → WordPress Conversion Rules

## Source Files
- Wireframes: `wireframes/` (homepage_02, about, contact, blog, portfolio)
- Shared CSS: `wireframes/_shared/base.css` → extract tokens to `assets/css/variables.css`
- Shared JS: `wireframes/shared/base.js` → migrate to `assets/js/main.js`
- Page CSS/JS: `wireframes/{page}/{page}.css|.js` → `assets/css|js/pages/{page}.css|.js`

## Conversion Checklist (per Section)

### HTML → PHP Replacements (MANDATORY)
1. `<img src="image/...">` → `wp_get_attachment_image($id, 'size')` (ACF Image field)
2. `<img src="../img/...">` → `wp_get_attachment_image()` hoặc ACF Gallery
3. Hardcoded text (h1, h2, p, span) → `esc_html(get_field('field_name'))`
4. `href="#"` / static links → `esc_url(home_url('/slug/'))`
5. `href="tel:..."` → `esc_url('tel:' . get_field('xanh_hotline', 'option'))`
6. `href="mailto:..."` → `esc_url('mailto:' . get_field('xanh_email', 'option'))`
7. Inline `<style>` / Tailwind CDN config → REMOVE (dùng Tailwind CLI build)
8. `<script src="...CDN">` → `wp_enqueue_script()` trong `inc/enqueue.php`
9. `<link rel="stylesheet">` → `wp_enqueue_style()` trong `inc/enqueue.php`
10. Form HTML → Fluent Form shortcode: `<?php echo do_shortcode(get_field('contact_form_shortcode')); ?>`
11. Copyright year → `<?php echo esc_html(date('Y')); ?>`
12. Site name → `<?php echo esc_html(get_bloginfo('name')); ?>`

### Preserve EXACTLY (DO NOT change)
- Tailwind utility classes (`class="flex items-center gap-4 ..."`)
- BEM class names (`.process-panel__content`, `.blog-card__img`)
- `data-*` attributes (GSAP targets, Swiper, Alpine.js)
- `aria-*` attributes (accessibility)
- `id="..."` attributes (JS targets)
- SVG inline icons (keep as-is)
- Animation classes (`.anim-fade-up`, `.is-active`)

### Structure Migration
| HTML | WordPress |
|---|---|
| `<head>...</head>` | `header.php`: `wp_head()` + theme supports |
| Header navigation | `header.php`: `wp_nav_menu()` + `the_custom_logo()` |
| Footer | `footer.php`: ACF Options + `wp_footer()` |
| Main content sections | `template-parts/sections/section-{name}.php` |
| Hero banners | `template-parts/hero/hero-{page}.php` |
| Cards (blog, project) | `template-parts/content/card-{type}.php` |
| Repeating items | `get_template_part()` inside WP Loop or ACF Repeater |

## ACF Data Flow
```
WP Admin → ACF Fields → get_field() → esc_html/esc_url/esc_attr → HTML output
```

### Repeater Pattern
```php
<?php $items = get_field('repeater_name');
if ($items) : foreach ($items as $item) : ?>
    <div class="...">
        <h3><?php echo esc_html($item['title']); ?></h3>
        <p><?php echo esc_html($item['description']); ?></p>
        <?php if ($item['image']) :
            echo wp_get_attachment_image($item['image']['ID'], 'xanh-card', false, [
                'class' => 'w-full h-full object-cover',
                'loading' => 'lazy',
            ]);
        endif; ?>
    </div>
<?php endforeach; endif; ?>
```

### Relationship Pattern (Featured Projects)
```php
<?php $projects = get_field('featured_projects');
if ($projects) : foreach ($projects as $post) : setup_postdata($post); ?>
    <?php get_template_part('template-parts/content/card', 'project'); ?>
<?php endforeach; wp_reset_postdata(); endif; ?>
```

## Global Options (Footer, Header, Contact Info)
```php
// ALWAYS use ACF Options for shared data
$hotline = get_field('xanh_hotline', 'option');
$email   = get_field('xanh_email', 'option');
$address = get_field('xanh_address', 'option');
// NEVER hardcode company info in templates
```

## Error Prevention
- NEVER output `get_field()` without escaping → XSS risk
- ALWAYS null-check before ACF Repeater: `if ($items) : foreach ...`
- ALWAYS `wp_reset_postdata()` after custom WP_Query
- NEVER use `query_posts()` → use `WP_Query` or `get_posts()`
- ALWAYS test ACF Image field before accessing `['ID']`: `if ($image) :`
- NEVER `echo` raw HTML from ACF — use `wp_kses_post()` for rich content

## References
- `docs/implement/CONVERT_HTML_TO_WP.md` — Full mapping guide
- `docs/implement/ACF_FIELD_GROUPS.md` — Field specifications
- `docs/implement/THEME_CONVENTIONS.md` — Code conventions
