---
description: Checklist SEO On-Page cho mỗi trang WordPress. Dùng sau khi hoàn thành chuyển đổi HTML→WP hoặc trước khi launch.
---

# SEO On-Page Checklist — XANH Theme

## Skills cần đọc trước
- `@seo-fundamentals` — E-E-A-T, Core Web Vitals, technical SEO foundations
- `@seo-meta-optimizer` — Tối ưu meta title, description, URL
- `@seo-structure-architect` — Header hierarchy, schema markup, internal linking
- `@schema-markup` — Schema.org structured data validation
- `@fixing-metadata` — Audit page titles, OG tags, JSON-LD, robots
- `@seo-keyword-strategist` — Keyword density, LSI keywords

## Rules BẮT BUỘC
- `04-seo.md` — **Đọc toàn bộ 8 sections** trước khi kiểm tra
- `15-seo-schema.md` — Heading hierarchy + Schema JSON-LD patterns
- `13-image-performance.md` — Image SEO (alt, filename, lazy load)

---

## Bước 1: Chọn Trang Cần Audit

| Trang | Template | Primary Keyword (gợi ý) |
|---|---|---|
| Homepage | `front-page.php` | thiết kế nội thất trọn gói |
| About | `page-about.php` | công ty thiết kế nội thất nha trang |
| Contact | `page-contact.php` | liên hệ tư vấn thiết kế nội thất |
| Blog List | `archive.php` | blog thiết kế nội thất |
| Blog Detail | `single.php` | [tùy bài viết] |
| Portfolio List | `archive-xanh_project.php` | dự án thiết kế nội thất |
| Portfolio Detail | `single-xanh_project.php` | [tùy dự án] |

---

## Bước 2: Title Tag & Meta Description (rule `04` §1-2)

### Title Tag
- [ ] ≤ 60 ký tự
- [ ] Keyword đặt ở **đầu** title
- [ ] Brand ở **cuối**: `{Keyword} | XANH - Design & Build`
- [ ] Unique cho mỗi trang (không trùng lặp)
- [ ] `add_theme_support('title-tag')` đã bật (KHÔNG output `<title>` thủ công)

| Trang | Title Template |
|---|---|
| Home | `Thiết Kế & Thi Công Nội Thất Trọn Gói | XANH` |
| About | `Về XANH - Công Ty Thiết Kế Nội Thất Nha Trang | XANH` |
| Contact | `Liên Hệ Tư Vấn Miễn Phí | XANH - Design & Build` |
| Blog | `Blog & Cảm Hứng Thiết Kế | XANH` |
| Blog Post | `{Tiêu đề bài} | XANH - Design & Build` |
| Portfolio | `Dự Án Nội Thất Tiêu Biểu | XANH` |
| Project | `{Tên dự án} - {Type} {Location} | XANH` |

### Meta Description
- [ ] ≤ 160 ký tự
- [ ] Unique cho mỗi trang
- [ ] Chứa primary keyword
- [ ] Có CTA rõ ràng (tăng CTR)
- [ ] RankMath xử lý → KHÔNG output thủ công

---

## Bước 3: Heading Hierarchy (rule `15` §1)

- [ ] **DUY NHẤT 1 `<h1>` per page** — KHÔNG dùng H1 cho logo
- [ ] H1 chứa primary keyword
- [ ] Hierarchy logic: H1 → H2 → H3 (KHÔNG skip level)
- [ ] H2 cho section titles, H3 cho sub-sections
- [ ] H2/H3 chứa secondary/LSI keywords tự nhiên

### Kiểm tra heading hierarchy:
// turbo
```bash
# Tìm tất cả heading tags trong template
$theme = "wp-content/themes/xanhdesignbuild"
Select-String -Path "$theme/*.php","$theme/template-parts/**/*.php" -Pattern '<h[1-6]' -Recurse | ForEach-Object { "$($_.Filename):$($_.LineNumber) $($_.Line.Trim())" }
```

---

## Bước 4: URL Structure

- [ ] Clean, short, lowercase
- [ ] Dùng dashes (`-`), KHÔNG underscore
- [ ] Chứa primary keyword
- [ ] Không ký tự đặc biệt
- [ ] Ví dụ: `/thiet-ke-noi-that-biet-thu/`, `/du-an/biet-thu-nha-trang/`

---

## Bước 5: Content Quality — E-E-A-T (rule `04` §3)

- [ ] Keyword trong 100 từ đầu tiên
- [ ] Paragraphs ngắn (2-3 câu max)
- [ ] Bullet points / numbered lists cho dễ scan
- [ ] Bold important phrases
- [ ] Outbound links: 1-2 high-authority resources (cho blog)
- [ ] No keyword stuffing — dùng LSI keywords tự nhiên
- [ ] Phù hợp search intent (Informational / Navigational / Transactional)

---

## Bước 6: Internal Linking (rule `04` §4)

- [ ] Mỗi blog post: ≥ 2 internal links (1 → Portfolio, 1 → Contact)
- [ ] Anchor text: descriptive, keyword-rich (KHÔNG "click here", "xem thêm")
- [ ] Related Posts: 3 bài liên quan (cùng category)
- [ ] Related Projects: 3 dự án liên quan (cùng `project_type`)
- [ ] Breadcrumb: link tới parent pages/archives
- [ ] Không orphan pages (mỗi trang phải được link từ ≥ 1 trang khác)
- [ ] Footer navigation: `wp_nav_menu()` có structured links

---

## Bước 7: Image SEO (rule `04` §5 + rule `13`)

- [ ] **Alt text:** Có trên TẤT CẢ images, tiếng Việt, mô tả chính xác, chứa keyword
- [ ] **Filename:** Lowercase, unaccented, dashes: `biet-thu-nha-trang-phong-khach.webp`
- [ ] **Dimensions:** `width` + `height` attributes trên tất cả `<img>`
- [ ] **Format:** WebP hoặc AVIF (Smush auto-convert production)
- [ ] **Loading:** `loading="lazy"` cho below-fold, KHÔNG lazy cho hero
- [ ] **Hero:** `fetchpriority="high"` + `<link rel="preload">`
- [ ] **Responsive:** `wp_get_attachment_image()` auto-generates srcset

---

## Bước 8: Semantic HTML5 (rule `15` §2)

- [ ] `<main id="main-content" role="main">` wrap page content
- [ ] `<article>` cho blog posts, projects
- [ ] `<section id="..." aria-label="...">` cho named sections
- [ ] `<nav aria-label="Breadcrumb">` cho navigation
- [ ] `<aside>` cho sidebar, related content
- [ ] `<figure>` + `<figcaption>` cho images with caption
- [ ] Skip navigation link cho accessibility

---

## Bước 9: Open Graph & Social (rule `04` §1)

- [ ] `og:title` — RankMath auto-generates
- [ ] `og:description` — RankMath auto-generates
- [ ] `og:image` — Featured image set trên tất cả pages/posts
- [ ] `og:type` — `website` (home), `article` (blog)
- [ ] Twitter Cards — RankMath handles
- [ ] Default OG image fallback trong RankMath settings
- [ ] KHÔNG output `<meta property="og:...">` thủ công

---

## Bước 10: Schema.org JSON-LD (rule `15` §3)

- [ ] Homepage: `Organization` + `WebSite`
- [ ] About: `Organization` (expanded)
- [ ] Contact: `LocalBusiness` + `FAQPage`
- [ ] Blog single: `Article` (author, dates, image)
- [ ] All sub-pages: `BreadcrumbList`
- [ ] JSON-LD reflects visible page content (no fabricated data)
- [ ] `wp_json_encode($schema, JSON_UNESCAPED_UNICODE)`
- [ ] Placed in `wp_head` action, NOT inline
- [ ] Không duplicate với RankMath schema
- [ ] Addresses/phones từ ACF Options (KHÔNG hardcode)
- [ ] Validate: Google Rich Results Test ✅

---

## Bước 11: Technical SEO (rule `04` §8)

- [ ] HTTPS forced (redirect HTTP → HTTPS)
- [ ] Non-www → www (hoặc ngược lại, nhất quán)
- [ ] `sitemap.xml` — RankMath auto-generates
- [ ] `robots.txt` — cho phép crawl public content
- [ ] Canonical tag — RankMath auto-generates
- [ ] `noindex`: chỉ Thank You, Admin, Search Results
- [ ] Mobile-friendly: font-size ≥ 16px body, touch targets ≥ 48x48px

---

## Bước 12: Local SEO (rule `04` §7)

- [ ] NAP consistency: Name, Address, Phone **GIỐNG NHAU** trên website, Google Business, Facebook, Zalo
- [ ] Google Map embedded trên trang Contact
- [ ] Mention target areas tự nhiên: "thiết kế nội thất tại Nha Trang", "thi công nhà phố tại Khánh Hòa"
- [ ] Google Business Profile: 100% complete
- [ ] Reviews strategy: encourage keyword-rich reviews

---

## Tài Liệu Tham Chiếu

| File | Nội dung |
|---|---|
| `.agent/rules/04-seo.md` | On-page SEO toàn bộ |
| `.agent/rules/15-seo-schema.md` | Heading + Schema patterns |
| `.agent/rules/13-image-performance.md` | Image SEO |
| `docs/implement/PERFORMANCE_SEO.md` | Full SEO & Schema guide |
| `docs/GOV_SEO_STRATEGY.md` | SEO strategy document |
