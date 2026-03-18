---
description: SEO and Schema.org structured data rules for WP templates. Apply when creating page templates, meta tags, or JSON-LD markup.
globs: wp-content/themes/xanhdesignbuild/**/*.php
---

# SEO & Schema Rules

## Heading Hierarchy (MANDATORY)
- **1 H1 per page** — NEVER multiple H1 tags
- H2 for section titles, H3 for sub-sections
- NEVER skip levels (H1 → H3 without H2)
- H1 content must match page intent (not brand name)

| Template | H1 Source |
|---|---|
| `front-page.php` | `get_field('hero_headline')` |
| `page-about.php` | `get_field('about_hero_title')` |
| `page-contact.php` | `get_field('contact_hero_title')` |
| `archive.php` | `single_post_title()` or static "Blog & Cảm Hứng" |
| `single.php` | `the_title()` |
| `archive-xanh_project.php` | Static "Dự Án Tiêu Biểu" |
| `single-xanh_project.php` | `the_title()` |

## Semantic HTML5
```php
<main id="main-content" role="main">         <!-- Wrap page content -->
<article>                                      <!-- Blog post, project -->
<section id="..." aria-label="...">           <!-- Named sections -->
<nav aria-label="Breadcrumb">                 <!-- Navigation -->
<aside>                                        <!-- Sidebar, related -->
<figure><figcaption>                           <!-- Images with caption -->
```

## Meta Tags (RankMath handles, theme supports)
```php
// inc/theme-setup.php
add_theme_support('title-tag');  // WP manages <title>
// NEVER output <title> manually in header.php
// NEVER output duplicate meta description
```

## Open Graph
- RankMath auto-generates OG tags
- Theme MUST set `featured_image` on all pages/posts for `og:image`
- Default OG image fallback: set in RankMath settings

## Canonical URLs
- RankMath auto-generates canonical
- NEVER output `<link rel="canonical">` manually
- Pagination: RankMath handles `rel="next"`, `rel="prev"`

## Breadcrumb (All pages except homepage)
```php
// Template usage
<?php if (!is_front_page()) : ?>
<nav class="breadcrumb" aria-label="Breadcrumb">
    <?php xanh_breadcrumb(); // inc/template-tags.php ?>
</nav>
<?php endif; ?>
```

## Schema.org JSON-LD

### Per-Page Schema
| Page | Schema | Ghi chú |
|---|---|---|
| Homepage | `Organization` + `WebSite` | Brand entity |
| About | `Organization` | Same entity, expanded |
| Contact | `LocalBusiness` + `FAQPage` | Physical business + FAQ |
| Blog single | `Article` | With author, dates, image |
| All sub-pages | `BreadcrumbList` | Navigation trail |

### Rules
1. JSON-LD MUST reflect **visible page content** — no fabricated data
2. Use `wp_json_encode($schema, JSON_UNESCAPED_UNICODE)` — Vietnamese characters
3. Place in `wp_head` action — NOT inline in templates
4. ONE `<script type="application/ld+json">` per schema type
5. RankMath may add its own schema — avoid duplicates (check before adding)
6. NEVER hardcode addresses/phones — use ACF Options fields
7. Validate with Google Rich Results Test before deploy

### LocalBusiness Pattern
```php
function xanh_schema_local_business() {
    if (!is_page('lien-he')) return;
    $schema = [
        '@context' => 'https://schema.org',
        '@type'    => 'GeneralContractor',
        'name'     => get_bloginfo('name'),
        'url'      => home_url('/'),
        'telephone' => get_field('xanh_hotline', 'option'),
        'email'     => get_field('xanh_email', 'option'),
        'address'   => [
            '@type' => 'PostalAddress',
            'addressLocality' => 'Nha Trang',
            'addressRegion'   => 'Khánh Hòa',
            'addressCountry'  => 'VN',
        ],
    ];
    echo '<script type="application/ld+json">' .
         wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) .
         '</script>';
}
add_action('wp_head', 'xanh_schema_local_business');
```

## Image SEO
- `alt` text → ALWAYS present, descriptive, Vietnamese
- Filename convention: `biet-thu-quan-7-truoc.webp` (slug format)
- `title` attribute → optional, different from alt

## Internal Linking
- Footer: `wp_nav_menu()` for structured navigation
- Related posts: Show 3 related by same category
- Related projects: Show 3 related by same `project_type` taxonomy
- Breadcrumb: Link to parent pages/archives

## Robots & Sitemap
- `robots.txt`: RankMath manages
- XML Sitemap: RankMath auto-generates
- `noindex` pages: Search results, utility pages, staging
- ALL content pages: `index, follow` (default)

## Reference
- `docs/implement/PERFORMANCE_SEO.md` §7-8 — Full SEO & Schema guide
- Skill: `schema-markup` — Schema eligibility scoring
- Skill: `seo-fundamentals` — E-E-A-T, content quality principles
