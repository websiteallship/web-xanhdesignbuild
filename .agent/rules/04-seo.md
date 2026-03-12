---
description: SEO rules for XANH website. Apply when creating pages, writing content, or implementing structured data.
globs: wp-content/themes/xanh-theme/**/*.php
---

# SEO Rules

## On-Page SEO (Every Page)
- **H1:** Single H1 per page, contains primary keyword
- **Title:** < 60 characters, keyword first, brand last: `{Keyword} | XANH - Design & Build`
- **Meta description:** < 160 characters, include CTA, unique per page
- **Heading hierarchy:** H1 → H2 → H3 (never skip levels)
- **Alt text:** Descriptive + natural keyword, never empty
- **Canonical:** Set on all pages
- **noindex:** Only on Thank-you pages, admin pages

## Title Templates
| Page Type | Template |
|---|---|
| Home | `Thiết Kế & Thi Công Nội Thất Trọn Gói | XANH` |
| Portfolio Detail | `{Tên dự án} - {Type} {Location} | XANH` |
| Blog Post | `{Tiêu đề bài} | XANH - Design & Build` |
| Contact | `Liên Hệ Tư Vấn Miễn Phí | XANH` |

## Schema Markup (JSON-LD)
| Schema | Where |
|---|---|
| `LocalBusiness` + `Organization` | Footer (all pages) |
| `BreadcrumbList` | Portfolio detail, Blog detail |
| `Article` | Blog posts |
| `FAQPage` | Contact page FAQ section |

## Internal Linking
- Every blog post: min 2 internal links (1 → Portfolio, 1 → Contact/Estimator)
- Anchor text: Descriptive with keyword — NEVER "click here"
- Related posts: 3 posts from same category (auto-generated)

## Local SEO
- **NAP consistency:** Name, Address, Phone IDENTICAL everywhere (website, Google, FB, Zalo)
- Google Business Profile: Keep updated with photos monthly
- Target keywords: `nội thất nha trang`, `xây nhà khánh hòa`, `thiết kế nội thất trọn gói`

## Image SEO
- File naming: `thiet-ke-noi-that-biet-thu-nha-trang.webp` (lowercase, dashes, keywords)
- Always set `width` + `height` attributes
- WebP format preferred

## Technical SEO
- HTTPS forced, HTTP → HTTPS redirect
- sitemap.xml auto-generated
- robots.txt: Allow all public content, block admin
- Clean URLs: `/%postname%/`

Full strategy: `docs/GOV_SEO_STRATEGY.md`
