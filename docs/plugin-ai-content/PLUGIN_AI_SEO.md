# PLUGIN_AI_SEO — SEO Engine & Content Score

> **Plugin:** XANH AI Content Generator
> **Tuân thủ:** `GOV_SEO_STRATEGY.md` | `seo-onpage-checklist.md`
> **Cập nhật:** 2026-03-20

---

## 1. Title Tag Optimizer

### Rules (từ GOV_SEO_STRATEGY §3)
- Max 60 ký tự
- Primary keyword ở **đầu** title
- Brand ở **cuối**: `| XANH - Design & Build`
- Unique cho mỗi bài

### Implementation
```php
function xanh_ai_optimize_title( string $title, string $keyword ): string {
    $brand  = '| XANH - Design & Build';
    $max    = 60 - mb_strlen( $brand ) - 1;
    $title  = mb_substr( $title, 0, $max );

    // Đảm bảo keyword ở đầu title
    if ( mb_stripos( $title, $keyword ) > 10 ) {
        // Reorder: keyword first
    }

    return $title . ' ' . $brand;
}
```

---

## 2. Meta Description Optimizer

### Rules
- Max 160 ký tự
- Chứa primary keyword
- Có CTA rõ ràng (tăng CTR)
- Unique cho mỗi bài

### Format theo trang type
```
Blog Post: "{2 câu đầu bài viết}... Đọc thêm tại XANH →"
```

---

## 3. Internal Linking Engine

### Auto-inject Links

Mỗi bài viết phải có ít nhất 2 internal links:

| Link Target | URL | Anchor Text Pattern |
|---|---|---|
| Portfolio | `/du-an/` | "Xem các dự án nội thất XANH" |
| Contact | `/lien-he/` | "Đặt lịch tư vấn riêng" |
| Estimator | `/du-toan/` | "Khám phá dự toán của bạn" |
| Green Solution | `/giai-phap-xanh/` | "Tìm hiểu giải pháp xanh" |
| Blog related | `/blog/{slug}/` | Descriptive anchor |

### Rules (từ GOV_SEO_STRATEGY §6)
- Anchor text: descriptive, chứa keyword (KHÔNG "click here", "xem thêm")
- Related posts: auto-suggest 3 bài cùng category
- Contextual placement: links nằm tự nhiên trong nội dung

### Implementation
```php
function xanh_ai_inject_internal_links( string $content, string $angle_id ): string {
    $angle = Xanh_AI_Angles::get( $angle_id );
    $links = $angle['internal_links']; // ['contact', 'portfolio']

    $link_map = [
        'contact'   => [ 'url' => '/lien-he/', 'anchor' => 'Đặt lịch tư vấn riêng' ],
        'portfolio' => [ 'url' => '/du-an/',   'anchor' => 'Xem các tác phẩm XANH' ],
        'estimator' => [ 'url' => '/du-toan/', 'anchor' => 'Khám phá dự toán của bạn' ],
    ];

    // Check if links already exist in AI-generated content
    foreach ( $links as $key ) {
        if ( isset( $link_map[ $key ] ) && stripos( $content, $link_map[ $key ]['url'] ) === false ) {
            // Inject before closing </article> or at end of content
            $link_html = sprintf(
                '<p><a href="%s">%s</a></p>',
                esc_url( home_url( $link_map[ $key ]['url'] ) ),
                esc_html( $link_map[ $key ]['anchor'] )
            );
            $content .= $link_html;
        }
    }

    return $content;
}
```

---

## 4. Keyword Suggestion Engine [P1]

### Data Source
Keyword clusters từ `GOV_SEO_STRATEGY.md` §2:

```php
private static $clusters = [
    1 => [ // Thương mại
        'thiết kế nội thất nha trang',
        'thi công nội thất nha trang',
        'thiết kế nội thất trọn gói nha trang',
        'công ty nội thất nha trang',
        'xây dựng nhà trọn gói nha trang',
        'báo giá thiết kế nội thất',
    ],
    2 => [ // Thông tin
        'kinh nghiệm xây nhà không phát sinh',
        'vật liệu xanh là gì',
        'chi phí xây nhà phố',
        'nội thất biệt thự hiện đại',
        'quy trình thiết kế nội thất',
    ],
    3 => [ // Local
        'nội thất khánh hòa',
        'xây nhà nha trang',
        'kiến trúc sư nha trang',
        'showroom nội thất nha trang',
    ],
];
```

### Logic
1. User chọn Angle → xác định `keyword_cluster`
2. Hiển thị list keywords từ cluster tương ứng
3. User có thể chọn 1 primary + nhiều secondary
4. Auto-suggest LSI keywords dựa trên topic (AI call nhẹ)

---

## 5. RankMath Compatibility

### Auto-fill RankMath Fields
```php
function xanh_ai_set_rankmath_meta( int $post_id, array $seo_data ): void {
    if ( ! class_exists( 'RankMath' ) ) {
        return;
    }

    update_post_meta( $post_id, 'rank_math_title', $seo_data['title'] );
    update_post_meta( $post_id, 'rank_math_description', $seo_data['meta_description'] );
    update_post_meta( $post_id, 'rank_math_focus_keyword', $seo_data['primary_keyword'] );

    // Schema Article
    update_post_meta( $post_id, 'rank_math_rich_snippet', 'article' );
    update_post_meta( $post_id, 'rank_math_snippet_article_type', 'BlogPosting' );
}
```

---

## 6. Content Score [P1]

### Scoring Rules (0-100)

| Tiêu chí | Điểm max | Check |
|---|---|---|
| **Title** | 10 | < 60 chars, keyword ở đầu, brand ở cuối |
| **Meta Description** | 10 | < 160 chars, có CTA, chứa keyword |
| **Heading Hierarchy** | 10 | 1 H1, H2→H3 không skip level |
| **Word Count** | 15 | ≥ angle.min_words (800-1500+) |
| **Internal Links** | 15 | ≥ 2 (Portfolio + Contact/Estimator) |
| **Banned Words** | 15 | 0 từ CẤM từ GOV_BRAND_VOICE |
| **Featured Image** | 10 | Có ảnh, format WebP/PNG, alt text |
| **External Link** | 5 | ≥ 1 nguồn uy tín |
| **Keyword Density** | 10 | 0.5% - 1.5% cho primary keyword |

### Score Levels

| Score | Level | Color | Action |
|---|---|---|---|
| 90-100 | Xuất sắc | 🟢 Green | Ready to publish |
| 70-89 | Tốt | 🔵 Blue | Minor improvements |
| 50-69 | Trung bình | 🟡 Yellow | Needs edits |
| 0-49 | Yếu | 🔴 Red | Major issues |

### Implementation
```php
function xanh_ai_calculate_score( array $post_data ): array {
    $score  = 0;
    $checks = [];

    // 1. Title check
    $title_len = mb_strlen( $post_data['title'] );
    if ( $title_len <= 60 && $title_len > 10 ) {
        $score += 10;
        $checks['title'] = [ 'pass' => true, 'message' => "Title: {$title_len} ký tự ✅" ];
    } else {
        $checks['title'] = [ 'pass' => false, 'message' => "Title: {$title_len}/60 ký tự ❌" ];
    }

    // 2. Word count
    $word_count = str_word_count( strip_tags( $post_data['content'] ) );
    $min_words  = $post_data['min_words'] ?? 800;
    if ( $word_count >= $min_words ) {
        $score += 15;
        $checks['words'] = [ 'pass' => true, 'message' => "Số từ: {$word_count}/{$min_words} ✅" ];
    }

    // 3. Banned words check
    $banned = xanh_ai_get_banned_words();
    $found  = xanh_ai_find_banned_words( $post_data['content'], $banned );
    if ( empty( $found ) ) {
        $score += 15;
        $checks['banned'] = [ 'pass' => true, 'message' => 'Không có từ CẤM ✅' ];
    } else {
        $checks['banned'] = [ 'pass' => false, 'message' => 'Từ CẤM: ' . implode( ', ', $found ) ];
    }

    // ... (thêm checks khác)

    return [
        'score'  => $score,
        'level'  => xanh_ai_get_score_level( $score ),
        'checks' => $checks,
    ];
}
```

> **Client-side Logic:** Khi user sửa bài trong preview panel, score sẽ được tính toán lại realtime qua JavaScript trong `admin/js/xanh-ai-generator.js` (`recalculateScore`), không cần gọi AJAX. Do đó, logic chấm điểm được implement độc lập cả trên PHP và JS.

---

## 7. FAQ Auto-Generate [P1]

### Logic
- AI generate 3-5 câu FAQ liên quan đến chủ đề
- Output format: `[{"question": "...", "answer": "..."}]`
- Insert FAQ section cuối bài viết (trước CTA)
- HTML: `<details><summary>Q</summary><p>A</p></details>`

### FAQPage Schema
```php
function xanh_ai_build_faq_schema( array $faqs ): array {
    return [
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => array_map( function ( $faq ) {
            return [
                '@type' => 'Question',
                'name'  => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => $faq['answer'],
                ],
            ];
        }, $faqs ),
    ];
}
```

---

## 8. Title A/B Testing [P3]

- AI generate 3-5 title variations
- Phân tích: keyword position, emotional trigger, length
- Suggest "best for CTR" dựa trên pattern matching
- User chọn final title

---

## 9. Reverse Internal Linking [P1]

### Concept

Khi publish bài viết mới, plugin **tự động tìm 3-5 bài cũ** cùng category/keyword và **chèn 1 link trỏ ngược** về bài mới vào cuối mỗi bài cũ đó. Điều này giúp Google bot ngay lập tức phát hiện và index bài mới thông qua các bài đã có rank, tạo cấu trúc "Content Silo" khép kín.

### Trigger

```php
// Hook vào transition_post_status — chỉ khi status chuyển sang 'publish'
add_action( 'transition_post_status', function ( $new, $old, $post ) {
    if ( $new !== 'publish' || $old === 'publish' ) {
        return;
    }
    if ( get_post_meta( $post->ID, '_xanh_ai_generated', true ) !== '1' ) {
        return;
    }
    Xanh_AI_Backlinks::inject_reverse_links( $post->ID );
}, 20, 3 );
```

### Tìm Bài Liên Quan

```php
function xanh_ai_find_related_posts( int $post_id, int $limit = 5 ): array {
    $categories = wp_get_post_categories( $post_id );
    $keyword    = get_post_meta( $post_id, '_xanh_ai_primary_keyword', true );

    $args = [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $limit,
        'post__not_in'   => [ $post_id ],
        'category__in'   => $categories,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'meta_query'     => [
            [
                'key'     => '_xanh_ai_generated',
                'value'   => '1',
                'compare' => '=',
            ],
        ],
    ];

    // Ưu tiên bài cùng keyword cluster
    if ( ! empty( $keyword ) ) {
        $args['s'] = $keyword;
    }

    $posts = apply_filters( 'xanh_ai_backlink_candidates', get_posts( $args ), $post_id );
    return $posts;
}
```

### Chèn Link Vào Bài Cũ

```php
function xanh_ai_inject_backlink( int $target_post_id, int $new_post_id ): bool {
    $target_content = get_post_field( 'post_content', $target_post_id );
    $new_title      = get_the_title( $new_post_id );
    $new_url        = get_permalink( $new_post_id );

    // Kiểm tra link chưa tồn tại
    if ( stripos( $target_content, $new_url ) !== false ) {
        return false;
    }

    // Tạo block "Bài viết liên quan" hoặc append vào cuối
    $link_html = sprintf(
        "\n\n<!-- xanh-ai-backlink -->\n<p><strong>📖 Đọc thêm:</strong> <a href=\"%s\">%s</a></p>",
        esc_url( $new_url ),
        esc_html( $new_title )
    );

    $updated_content = $target_content . $link_html;
    wp_update_post( [
        'ID'           => $target_post_id,
        'post_content' => $updated_content,
    ] );

    // Track: lưu danh sách post đã chèn link
    $injected = get_post_meta( $new_post_id, '_xanh_ai_backlinks_injected', true );
    $injected = $injected ? json_decode( $injected, true ) : [];
    $injected[] = $target_post_id;
    update_post_meta( $new_post_id, '_xanh_ai_backlinks_injected', wp_json_encode( $injected ) );

    do_action( 'xanh_ai_backlink_injected', $target_post_id, $new_post_id );
    return true;
}
```

### Rules & Giới Hạn

| Rule | Chi tiết |
|---|---|
| Max backlinks/bài mới | 5 bài cũ (tránh spam) |
| Vị trí chèn | Cuối bài, dưới CTA cuối cùng |
| Duplicate check | Không chèn nếu URL đã tồn tại trong bài cũ |
| Anchor text | Title bài mới (descriptive, có keyword tự nhiên) |
| HTML marker | `<!-- xanh-ai-backlink -->` để dễ track/remove |
| Undo | Admin có thể xoá tất cả backlinks từ panel History |
| Category match | Chỉ chèn vào bài cùng category (tránh cross-topic) |

### Admin UI — Backlink Report

```
┌─ Reverse Backlinks — Bài: "Chi Phí Xây Nhà 2026" ────────┐
│                                                             │
│  ✅ Link đã chèn vào 4 bài cũ:                             │
│  1. "5 Sai Lầm Khi Xây Nhà" (15/03/2026)    [Xem] [Gỡ]   │
│  2. "Kinh Nghiệm Chọn Nhà Thầu" (08/03/2026) [Xem] [Gỡ]  │
│  3. "Bảng Giá Vật Liệu Q1" (01/03/2026)     [Xem] [Gỡ]   │
│  4. "Quy Trình Thiết Kế A-Z" (22/02/2026)   [Xem] [Gỡ]   │
│                                                             │
│  ❌ Bỏ qua 1 bài (link đã tồn tại):                       │
│  - "Gạch AAC vs Gạch Đỏ" (12/03/2026)                      │
│                                                             │
│  [ 🗑️ Gỡ Tất Cả Backlinks ]                                │
└─────────────────────────────────────────────────────────────┘
```

---

## 10. Automated Advanced JSON-LD Schema [P1]

### Concept

Plugin tự động nhận diện Angle của bài viết và sinh schema JSON-LD phù hợp, nâng cao khả năng hiển thị Rich Snippets trên Google Search.

### Schema Mapping Theo Angle

| Angle | Schema Type | Thuộc tính đặc biệt |
|---|---|---|
| 🏢 Dịch vụ (`service_intro`) | `Service` + `Organization` | `provider`, `areaServed: Nha Trang`, `serviceType` |
| 🛋️ Vật liệu (`product_material`) | `Article` + `Product` (review) | `aggregateRating`, `offers.priceRange` |
| 📍 Local SEO (`local_seo`) | `LocalBusiness` + `Article` | `geo`, `address`, `openingHours`, `telephone` |
| 📚 Kiến thức (`knowledge`) | `HowTo` hoặc `Article` | `step[]`, `tool[]`, `totalTime` |
| 💡 Kinh nghiệm (`experience`) | `Article` (BlogPosting) | `author`, `datePublished`, `wordCount` |
| 🌿 Xu hướng (`trends`) | `Article` (NewsArticle) | `datePublished`, `about: DesignTrend` |
| 👷 Nhật ký (`construction_diary`) | `Article` + `Project` | `startDate`, `endDate`, `percentComplete` |
| 🏆 Case study (`case_study`) | `Article` + `CreativeWork` | `about: RealEstateProject`, `locationCreated` |

### Implementation

```php
function xanh_ai_build_schema( int $post_id ): array {
    $angle  = get_post_meta( $post_id, '_xanh_ai_angle', true );
    $title  = get_the_title( $post_id );
    $url    = get_permalink( $post_id );
    $date   = get_the_date( 'c', $post_id );
    $modified = get_the_modified_date( 'c', $post_id );
    $excerpt  = get_the_excerpt( $post_id );

    // Base Article schema — mọi bài đều có
    $schema = [
        '@context'      => 'https://schema.org',
        '@type'         => 'BlogPosting',
        'headline'      => $title,
        'url'           => $url,
        'datePublished' => $date,
        'dateModified'  => $modified,
        'description'   => $excerpt,
        'author'        => [
            '@type' => 'Organization',
            'name'  => 'XANH - Design & Build',
            'url'   => home_url(),
        ],
        'publisher'     => [
            '@type' => 'Organization',
            'name'  => 'XANH - Design & Build',
            'logo'  => [
                '@type' => 'ImageObject',
                'url'   => get_site_icon_url(),
            ],
        ],
    ];

    // Angle-specific enrichment
    switch ( $angle ) {
        case 'local_seo':
            $schema = xanh_ai_enrich_local_business( $schema );
            break;
        case 'service_intro':
            $schema = xanh_ai_enrich_service( $schema );
            break;
        case 'knowledge':
            $schema = xanh_ai_enrich_howto( $schema, $post_id );
            break;
        case 'case_study':
        case 'construction_diary':
            $schema = xanh_ai_enrich_project( $schema, $post_id );
            break;
    }

    // Merge FAQ Schema nếu có
    $faqs = get_post_meta( $post_id, '_xanh_ai_faq', true );
    if ( ! empty( $faqs ) ) {
        $schema['mainEntity'] = xanh_ai_build_faq_entities( json_decode( $faqs, true ) );
    }

    // Filter cho extensibility
    $schema = apply_filters( 'xanh_ai_schema_data', $schema, $post_id, $angle );

    // Save schema type vào meta
    update_post_meta( $post_id, '_xanh_ai_schema_type', $schema['@type'] );
    do_action( 'xanh_ai_schema_injected', $post_id, $schema['@type'] );

    return $schema;
}
```

### LocalBusiness Enrichment (cho Angle `local_seo`)

```php
function xanh_ai_enrich_local_business( array $schema ): array {
    $schema['@graph'] = [
        $schema,
        [
            '@type'       => 'LocalBusiness',
            'name'        => 'XANH - Design & Build',
            'description' => 'Thiết kế & Thi công nội thất cao cấp tại Nha Trang',
            'url'         => home_url(),
            'telephone'   => get_option( 'xanh_company_phone', '' ),
            'address'     => [
                '@type'           => 'PostalAddress',
                'streetAddress'   => get_option( 'xanh_company_address', '' ),
                'addressLocality' => 'Nha Trang',
                'addressRegion'   => 'Khánh Hòa',
                'addressCountry'  => 'VN',
            ],
            'geo' => [
                '@type'     => 'GeoCoordinates',
                'latitude'  => '12.2388',
                'longitude' => '109.1967',
            ],
            'areaServed'  => [
                '@type' => 'City',
                'name'  => 'Nha Trang',
            ],
            'priceRange'  => '💰💰💰',
        ],
    ];

    unset( $schema['@type'] ); // @type nằm trong @graph
    return $schema;
}
```

### Project Enrichment (cho Angle `case_study`, `construction_diary`)

```php
function xanh_ai_enrich_project( array $schema, int $post_id ): array {
    $score = get_post_meta( $post_id, '_xanh_ai_score', true );

    $schema['about'] = [
        '@type'       => 'Project',
        'name'        => get_the_title( $post_id ),
        'description' => get_the_excerpt( $post_id ),
        'location'    => [
            '@type'           => 'Place',
            'address'         => [
                '@type'           => 'PostalAddress',
                'addressLocality' => 'Nha Trang',
                'addressRegion'   => 'Khánh Hòa',
                'addressCountry'  => 'VN',
            ],
        ],
    ];

    return $schema;
}
```

### Inject vào `<head>`

```php
add_action( 'wp_head', function () {
    if ( ! is_singular( 'post' ) ) {
        return;
    }

    $post_id = get_the_ID();
    if ( get_post_meta( $post_id, '_xanh_ai_generated', true ) !== '1' ) {
        return;
    }

    $schema = xanh_ai_build_schema( $post_id );

    printf(
        '<script type="application/ld+json">%s</script>' . "\n",
        wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT )
    );
} );
```

### RankMath Compatibility

```php
// Tắt RankMath schema cho bài AI (tránh duplicate)
add_filter( 'rank_math/json_ld', function ( $data, $jsonld ) {
    if ( is_singular( 'post' ) && get_post_meta( get_the_ID(), '_xanh_ai_generated', true ) === '1' ) {
        // Giữ BreadcrumbList, xoá Article
        unset( $data['richSnippet'] );
    }
    return $data;
}, 99, 2 );
```

---

## Tài Liệu Liên Quan

- `GOV_SEO_STRATEGY.md` — Full SEO strategy
- `.agent/workflows/seo-onpage-checklist.md` — 12-step audit
- `PLUGIN_AI_ANGLES.md` — Angle-specific SEO requirements
- `PLUGIN_AI_ARCHITECTURE.md` — Hook system, data flow for backlinks & schema
