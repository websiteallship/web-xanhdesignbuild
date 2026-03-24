---
description: SEO rules for XANH website. Apply when creating pages, writing content, or implementing structured data.
globs: wp-content/themes/xanh-theme/**/*.php
---

# SEO Rules

## 1. On-Page SEO Checklist (Every Page)
- **URL Structure:** Clean, short, uses dashes (`-`), no special characters, lowercase, contains primary keyword (e.g., `/thiet-ke-noi-that-biet-thu/`).
- **Title Tag:** < 60 characters. Keyword placed at the beginning, brand at the end: `{Keyword} | XANH - Design & Build`.
- **Meta Description:** < 160 characters. Must be unique per page, include the primary keyword, and a clear Call to Action (CTA) to improve CTR.
- **H1 Heading:** EXACTLY one `<h1>` per page. Must contain the primary keyword. Never use `<h1>` for logos.
- **Heading Hierarchy:** Logical structure `H1 → H2 → H3`. Never skip levels (e.g., H1 directly to H3). Use secondary/LSI keywords in H2 & H3.
- **Keyword Placement:** The primary keyword MUST appear within the first 100 words of the content.
- **Keyword Density:** Natural language only. Avoid keyword stuffing. Use Latent Semantic Indexing (LSI) and synonym keywords contextually.
- **Open Graph & Twitter Cards:** Always include `<meta property="og:..." />` and `<meta name="twitter:..." />` tags for social sharing (especially `og:image`, `og:title`, `og:description`).
- **Canonical Tag:** Self-referencing canonical tag on every page to prevent duplicate content issues.
- **Robots / Indexing:** Use `noindex` only for Thank You pages, Admin dashboards, authentication, and internal search results.

## 2. Title Templates
| Page Type | Template |
|---|---|
| Home | `Thiết Kế & Thi Công Nội Thất Trọn Gói | XANH` |
| Portfolio Detail | `{Tên dự án} - {Type} {Location} | XANH` |
| Blog Post | `{Tiêu đề bài} | XANH - Design & Build` |
| Contact | `Liên Hệ Tư Vấn Miễn Phí | XANH` |

## 3. Content Quality & Formatting (E-E-A-T)
- **Readability:** Keep paragraphs short (2-3 sentences max). Use bullet points and numbered lists to break down information.
- **Visual Scannability:** Bold important phrases and keywords to help users scan the page.
- **Search Intent:** Ensure the content directly answers the user's query (Informational, Navigational, Transactional).
- **Outbound Links:** For blog posts, link to 1-2 high-authority external resources (e.g., industry magazines, trusted news, Wikipedia) to build trust.

## 4. Internal Linking Strategy
- **Frequency:** Every blog post must have a minimum of 2 internal links (e.g., 1 → Portfolio, 1 → Contact/Estimator).
- **Anchor Text:** Use descriptive, keyword-rich anchor text. NEVER use generic phrases like "click here", "xem thêm", "tại đây".
- **Related Posts:** Automatically generate and display 3 relevant posts from the same category at the end of articles.
- **Orphan Pages:** Avoid orphan pages. Every page must be linked from at least one other page on the site.

## 5. Image SEO & Media
- **File Naming:** Rename files descriptively BEFORE uploading. Use lowercase, unaccented letters with dashes (e.g., `thiet-ke-noi-that-biet-thu-nha-trang.webp`).
- **Alt Text:** Mandatory for all images. Describe the image accurately for accessibility and naturally include target keywords. Do not leave empty.
- **Dimensions:** Always set `width` and `height` attributes explicitly in HTML/CSS to prevent Cumulative Layout Shift (CLS).
- **Format & Loading:** Prefer `WebP` or `AVIF` formats. Implement `loading="lazy"` for all images below the fold.

## 6. Schema Markup (JSON-LD)
| Schema Type | Where to Apply |
|---|---|
| `LocalBusiness` & `Organization` | Site-wide (usually in Footer or Header) |
| `BreadcrumbList` | All sub-pages (Portfolio detail, Blog detail, Category pages) |
| `Article` / `NewsArticle` | Blog posts |
| `FAQPage` | Pages with FAQ sections (e.g., Contact, Service details) |

## 7. Local SEO & Google Business Profile (GBP)
- **NAP Consistency:** Name, Address, Phone number must be 100% IDENTICAL across all platforms (Website footer, Contact page, Google Business, Facebook, Zalo, Bing Places, and local directories/yellow pages).
- **Google Business Profile Optimization:**
  - Complete 100% of the profile information (categories, description, attributes).
  - Monthly updates: Post new project photos, updates, and offers regularly.
  - Populate "Products/Services" tab comprehensively with descriptions and prices/ranges if applicable.
- **On-Page Local Signals:**
  - Embed the interactive Google Map of the business location on the Contact page.
  - Mention specific target areas naturally in content (e.g., "Công ty thiết kế nội thất tại [Nha Trang]", "Dự án thi công nhà phố tại [Khánh Hòa]").
  - Create dedicated "Location Pages" or "Service Area Pages" if serving multiple distinct cities/districts.
- **Review Strategy (Social Proof & SEO):**
  - Actively encourage satisfied clients to leave detailed Google Reviews mentioning specific services (e.g., "Cảm ơn Xanh đã *thiết kế nội thất* tuyệt đẹp cho *biệt thự* của tôi").
  - Respond to ALL reviews (positive and negative) promptly and professionally, incorporating keywords naturally in responses.
- **Target Keywords (Geo-modifiers):** e.g., `nội thất nha trang`, `xây nhà khánh hòa`, `thiết kế nội thất trọn gói nha trang`, `công ty xây dựng uy tín nha trang`.
- **Local Link Building:** Seek backlinks from local news websites (e.g., Báo Khánh Hòa), local industry partners (suppliers, real estate agents), and sponsor local events.

## 8. Technical SEO & Performance
- **Security:** HTTPS forced. Redirect all HTTP traffic to HTTPS and non-www to www (or vice versa consistently).
- **Sitemap & Robots.txt:** Auto-generate `sitemap.xml`. Ensure `robots.txt` allows search engines to crawl public content but blocks admin/system paths.
- **Core Web Vitals:** Optimize for Fast LCP (Largest Contentful Paint), minimal CLS (Cumulative Layout Shift) < 0.1, and fast INP (Interaction to Next Paint).
- **Mobile-Friendliness:** Ensure readable font sizes (>= 16px for body text) and adequate touch target sizes (>= 48x48px) for mobile users.

Full strategy reference: `docs/GOV_SEO_STRATEGY.md`
