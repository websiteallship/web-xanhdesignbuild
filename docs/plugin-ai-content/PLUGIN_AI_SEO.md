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

---

## 7. FAQ Auto-Generate [P2]

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

## Tài Liệu Liên Quan

- `GOV_SEO_STRATEGY.md` — Full SEO strategy
- `.agent/workflows/seo-onpage-checklist.md` — 12-step audit
- `PLUGIN_AI_ANGLES.md` — Angle-specific SEO requirements
