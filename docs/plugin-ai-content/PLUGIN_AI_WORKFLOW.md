# PLUGIN_AI_WORKFLOW — Quy Trình Sử Dụng

> **Plugin:** XANH AI Content Generator
> **Cập nhật:** 2026-03-22

---

## 1. Single Post Generation [P1]

### User Flow
```
Bước 1: Chọn Angle
├── 8 options (cards hoặc select)
├── Auto-fill: tone, CTA, suggested category
└── Hiển thị description + example topics

Bước 2: Nhập Thông Tin
├── Topic (required): "Chi Phí Xây Nhà Phố 2026"
├── Primary Keyword (required): "chi phí xây nhà phố"
├── Secondary Keywords: "giá xây nhà, dự toán xây dựng"
├── Tone override (optional): warm-luxury / expert / friendly
├── Độ dài: standard (800-1200) / long (1500-2000) / guide (2000+)
├── 📎 Nguồn Tham Khảo:
│   ├── [📄 Upload File] — PDF, DOCX, CSV, MD (max 10MB)
│   ├── [🔗 Paste URL]   — Link bài báo / nghiên cứu
│   ├── [📝 Ghi Chú]     — Paste text trực tiếp
│   └── [📚 Từ Thư Viện]  — Chọn từ Source Library
└── Notes: "Focus vào Nha Trang, data 2026"

Bước 2.5: Xem & Sửa Prompt (MỚI)
├── Click "Xem Prompt →" thay vì tạo ngay
├── AJAX: xanh_ai_preview_prompt → build prompt (0 API calls)
├── Hiển thị toàn bộ system + user prompt trong textarea lớn
├── Ước tính token: ~1,200-2,000 tokens input
├── User có thể chỉnh sửa tự do trước khi gửi
└── Token estimate cập nhật realtime khi edit

Bước 3: Generate
├── Click "Tạo Nội Dung" từ Step 2.5
├── Nếu user đã sửa prompt → gửi như custom_prompt
├── Nếu không sửa → gửi prompt gốc
├── Loading: spinner + "Đang tạo nội dung..." (15-30s)
├── Progress: "Đang phân tích chủ đề..." → "Đang viết..." → "Đang tối ưu SEO..."
└── Error handling: retry button + error message

Bước 4: Preview & Edit
├── Title (editable inline)
├── Meta Description (editable, char counter)
├── Content Score: 85/100 🔵 với breakdown chi tiết
├── Table of Contents (auto-generated)
├── Full Content (rich text editable)
│   └── Mỗi H2 section có icon ✏️ "Viết Lại Section"
├── Featured Image (manual generation)
│   ├── Buttons: "🖼️ Tạo Ảnh Ngay" (on-demand, không tự động)
│   ├── Countdown: "⏳ Đang tạo ảnh... 15s" (realtime timer)
│   ├── Timeout: 150s (tăng từ 60s) + set_time_limit(180) backend
│   └── Error 429: "API quá tải. Vui lòng đợi 1 phút rồi thử lại."
├── Tags (editable chips)
└── FAQ section (expandable, editable)

Bước 5: Save
├── "📝 Lưu Draft" → wp_insert_post (status: draft)
├── Auto-append brand suffix: title + " | {site_name}" (nếu chưa có)
├── Set: category, tags, meta, RankMath fields, _xanh_ai_image_prompt
├── FAQPage JSON-LD schema → _xanh_ai_faq_schema meta
├── Log: generation history [P2]
└── Redirect → WP Post Editor (for final review)
```

### AJAX Endpoints
```
wp_ajax_xanh_ai_generate_content     → Generate text via AI
wp_ajax_xanh_ai_generate_image       → Generate featured image
wp_ajax_xanh_ai_regenerate_section   → Rewrite 1 section
wp_ajax_xanh_ai_save_draft           → Save as WP draft
wp_ajax_xanh_ai_preview_prompt       → Build + return full prompt (0 API calls)
wp_ajax_xanh_ai_suggest_keywords     → Keyword suggestions
wp_ajax_xanh_ai_upload_source        → Upload + process reference file
wp_ajax_xanh_ai_add_source_url       → Scrape + process reference URL
wp_ajax_xanh_ai_get_sources          → Get sources from library
```

---

## 2. Batch Generate [P1]

### Flow
```
1. Chọn Angle (apply cho tất cả bài)
2. Nhập danh sách topics (textarea, 1 topic per line)
   ├── Mỗi dòng: "Topic | Primary Keyword" (pipe separated)
   └── VD: "Chi Phí Xây Nhà 2026 | chi phí xây nhà phố"
3. Preview list: N items, estimated time (N × 30s)
4. Click "🚀 Tạo Hàng Loạt"
5. Queue → WP Cron (1 item/30s)
6. Real-time progress table:
   ├── Item 1: ✅ Done — "Title..." (link to draft)
   ├── Item 2: ⏳ Generating... (spinner)
   ├── Item 3: ⏸️ Queued
   └── Item 4: ❌ Error — "API rate limit" (retry button)
7. Completion: "N/N bài đã tạo!" + links to all drafts
```

### WP Cron Implementation
```php
// Schedule: process 1 item every 30s
function xanh_ai_schedule_batch( array $items ): void {
    set_transient( 'xanh_ai_batch_queue', $items, HOUR_IN_SECONDS );
    set_transient( 'xanh_ai_batch_index', 0, HOUR_IN_SECONDS );

    if ( ! wp_next_scheduled( 'xanh_ai_batch_cron' ) ) {
        wp_schedule_single_event( time() + 5, 'xanh_ai_batch_cron' );
    }
}

add_action( 'xanh_ai_batch_cron', function () {
    $queue = get_transient( 'xanh_ai_batch_queue' );
    $index = (int) get_transient( 'xanh_ai_batch_index' );

    if ( ! $queue || $index >= count( $queue ) ) {
        delete_transient( 'xanh_ai_batch_queue' );
        do_action( 'xanh_ai_batch_complete' );
        return;
    }

    // Process current item
    $item   = $queue[ $index ];
    $result = xanh_ai_generate_single_post( $item );

    // Update status
    $queue[ $index ]['status'] = is_wp_error( $result ) ? 'error' : 'done';
    $queue[ $index ]['post_id'] = is_wp_error( $result ) ? null : $result;
    set_transient( 'xanh_ai_batch_queue', $queue, HOUR_IN_SECONDS );
    set_transient( 'xanh_ai_batch_index', $index + 1, HOUR_IN_SECONDS );

    // Schedule next
    wp_schedule_single_event( time() + 30, 'xanh_ai_batch_cron' );
} );
```

---

## 3. Section Regenerate [P1]

### Flow
```
1. Trong Preview, mỗi H2 section có icon ✏️
2. Click → Modal: "Viết lại section: {H2 title}"
3. Optional: input ghi chú ("Thêm data 2026", "Tone ấm hơn")
4. AI nhận: system prompt + full article context + section title + notes
5. Generate CHỈ section đó (1 H2 block)
6. Replace inline trong preview
```

### Prompt
```
Dưới đây là bài viết đầy đủ: {full_content}

Hãy viết lại section "{H2_title}" với yêu cầu:
- Giữ nguyên tone và style của toàn bài
- {user_notes}
- CHỈ trả về nội dung section (từ H2 đến trước H2 tiếp theo)
```

---

## 4. Auto Schedule [P2]

### Rules (từ GOV_SEO_STRATEGY §7)

| Giai đoạn | Tần suất | Ý nghĩa |
|---|---|---|
| Pre-launch | 5-10 bài có sẵn | SEO foundation |
| Tháng 1-3 | 2 bài/tuần | Build authority |
| Sau 3 tháng | 1 bài/tuần | Maintain |

### 4-Week Rotation
| Tuần | Category | Angle phù hợp |
|---|---|---|
| Tuần 1 | 📘 Kinh Nghiệm Xây Nhà | experience, knowledge, service_intro |
| Tuần 2 | 🧱 Vật Liệu Xanh | product_material |
| Tuần 3 | 🌿 Xu Hướng | trends, local_seo |
| Tuần 4 | 👷 Nhật Ký Xanh | construction_diary, case_study |

### Auto-Schedule Logic
```php
function xanh_ai_get_next_schedule_slot(): DateTime {
    $frequency = get_option( 'xanh_ai_schedule_frequency', '2/week' );
    $time      = get_option( 'xanh_ai_schedule_time', '08:00' );
    $timezone  = new DateTimeZone( 'Asia/Ho_Chi_Minh' );

    $last_scheduled = xanh_ai_get_last_scheduled_date();
    $next = clone $last_scheduled;

    switch ( $frequency ) {
        case '2/week':
            // Thứ 2 + Thứ 5, 8:00 sáng
            $next->modify( '+3 days' ); // alternating
            break;
        case '1/week':
            $next->modify( '+7 days' );
            break;
    }

    $next->setTime( ...explode( ':', $time ) );
    return $next;
}
```

---

## 5. Topic Idea Generator [P2]

### Flow
```
1. Chọn Angle
2. Optional: nhập existing topics (để AI tránh trùng)
3. Click "💡 Gợi Ý Chủ Đề"
4. AI trả về 10 topics:
   ├── Title suggestion
   ├── Primary keyword
   ├── Search intent (info/trans/nav)
   ├── Estimated difficulty (low/med/high)
   └── Why: lý do nên viết topic này
5. User tick chọn → "Tạo bài cho N topics đã chọn" → redirect Batch
```

### Prompt
```
Dựa trên góc viết "{angle.label}" cho XANH - Design & Build (nội thất cao cấp Nha Trang),
đề xuất 10 chủ đề bài viết blog.

Keyword clusters tham khảo: {cluster_keywords}
Topics đã có (TRÁNH TRÙNG): {existing_topics}

Format JSON: [{
  "title": "...",
  "keyword": "...",
  "intent": "informational|transactional",
  "difficulty": "low|medium|high",
  "reason": "..."
}]
```

---

## 6. Content Rewriter [P2]

### Flow
```
1. Chọn bài viết cũ (dropdown WP posts)
2. AI phân tích: structure, keyword, word count, data outdated
3. Options:
   ├── ☑ Cập nhật số liệu (năm/giá mới)
   ├── ☑ Cải thiện SEO structure
   ├── ☑ Align brand voice
   └── ☑ Extend nội dung (thêm sections)
4. Generate → Preview diff (highlight changes)
5. Save as NEW draft (preserve original)
```

---

## 7. Multi-language [P3]

- Toggle: "Tạo thêm bản tiếng Anh"
- AI translate + localize (not literal translation)
- Separate WP post with `_en` suffix
- Hreflang meta tags

---

## 8. Social Media Snippets [P3]

### Auto-generate kèm bài viết:

| Platform | Format | Length |
|---|---|---|
| Facebook | Post text + hashtags | 200-300 chars |
| Zalo OA | Message text | 150 chars |
| Instagram | Caption + 10 hashtags | 200 chars |

---

## 9. Competitor Analysis [P3]

### Flow
```
1. Input: URL bài viết đối thủ
2. Scrape: title, headings, word count, keywords
3. AI phân tích: strengths, weaknesses, content gaps
4. Generate outline cho bài viết tốt hơn
5. Optional: auto-generate full bài từ outline
```

---

## 10. Smart Content Updater [P2]

### Concept

Tự động quét **bài viết AI cũ** (>6 tháng hoặc custom), phát hiện data lỗi thời (giá, năm, số liệu), gọi AI cập nhật nội dung, và **bump Publish Date** lên hiện tại. Giữ vững ranking mà không cần viết bài mới.

### Trigger — WP Cron Hàng Quý

```php
// Schedule: chạy mỗi 90 ngày
register_activation_hook( __FILE__, function () {
    if ( ! wp_next_scheduled( 'xanh_ai_content_refresh_scan' ) ) {
        wp_schedule_event( time(), 'quarterly', 'xanh_ai_content_refresh_scan' );
    }
} );

// Custom interval: quarterly (90 ngày)
add_filter( 'cron_schedules', function ( $schedules ) {
    $schedules['quarterly'] = [
        'interval' => 90 * DAY_IN_SECONDS,
        'display'  => 'Mỗi quý (90 ngày)',
    ];
    return $schedules;
} );
```

### Scan Logic — Tìm Bài Cần Cập Nhật

```php
function xanh_ai_scan_outdated_posts(): array {
    $threshold = get_option( 'xanh_ai_refresh_threshold', 180 ); // 6 tháng

    $args = [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => 50,
        'meta_query'     => [
            [
                'key'   => '_xanh_ai_generated',
                'value' => '1',
            ],
        ],
        'date_query'     => [
            [
                'before' => "-{$threshold} days",
            ],
        ],
        'orderby'        => 'date',
        'order'          => 'ASC',
    ];

    $posts     = get_posts( $args );
    $outdated  = [];

    foreach ( $posts as $post ) {
        $analysis = xanh_ai_analyze_outdated( $post );
        if ( ! empty( $analysis['issues'] ) ) {
            $outdated[] = [
                'post_id'  => $post->ID,
                'title'    => $post->post_title,
                'age_days' => ( time() - strtotime( $post->post_date ) ) / DAY_IN_SECONDS,
                'issues'   => $analysis['issues'],
            ];
        }
    }

    return $outdated;
}
```

### Phát Hiện Nội Dung Lỗi Thời

```php
function xanh_ai_analyze_outdated( WP_Post $post ): array {
    $content   = $post->post_content;
    $issues    = [];
    $current_year = date( 'Y' );
    $prev_year   = $current_year - 1;

    // 1. Năm cũ trong nội dung
    if ( preg_match_all( "/(20[0-9]{2})/", $content, $years ) ) {
        foreach ( array_unique( $years[1] ) as $year ) {
            if ( (int) $year < (int) $current_year ) {
                $issues[] = [
                    'type'    => 'outdated_year',
                    'detail'  => "Năm {$year} — cần cập nhật lên {$current_year}",
                    'value'   => $year,
                ];
            }
        }
    }

    // 2. Giá/số liệu Data Registry đã thay đổi
    $registry  = get_option( 'xanh_ai_verified_data', [] );
    $old_price = $registry['price_updated_date'] ?? '';
    if ( ! empty( $old_price ) && stripos( $content, $old_price ) === false ) {
        $issues[] = [
            'type'   => 'outdated_price',
            'detail' => "Giá tham khảo chưa cập nhật lên {$old_price}",
        ];
    }

    // 3. Sources hết hạn
    $source_ids = get_post_meta( $post->ID, '_xanh_ai_sources', true );
    if ( ! empty( $source_ids ) ) {
        $expired = xanh_ai_check_expired_sources_for_post( json_decode( $source_ids, true ) );
        if ( ! empty( $expired ) ) {
            $issues[] = [
                'type'   => 'expired_sources',
                'detail' => count( $expired ) . ' nguồn tham khảo đã hết hạn',
                'sources' => $expired,
            ];
        }
    }

    return [ 'issues' => $issues ];
}
```

### AI Refresh — Cập Nhật Nội Dung

```php
function xanh_ai_refresh_post( int $post_id, array $issues ): int|WP_Error {
    $post     = get_post( $post_id );
    $registry = get_option( 'xanh_ai_verified_data', [] );
    $current_year = date( 'Y' );

    $prompt = <<<PROMPT
Dưới đây là bài viết CŨ cần cập nhật:
---
{$post->post_content}
---

CÁC VẤN ĐỀ CẦN SỬA:
PROMPT;

    foreach ( $issues as $issue ) {
        $prompt .= "\n- {$issue['detail']}";
    }

    $prompt .= <<<PROMPT

DỮ LIỆU MỚI NHẤT (Data Registry {$current_year}):
- Số dự án: {$registry['projects_completed']}
- Giá basic/m²: {$registry['price_basic_per_sqm']}
- Giá standard/m²: {$registry['price_standard_per_sqm']}
- Giá premium/m²: {$registry['price_premium_per_sqm']}
- Cập nhật: {$registry['price_updated_date']}

YÊU CẦU:
1. Cập nhật TẤT CẢ năm cũ thành {$current_year}
2. Cập nhật giá/số liệu theo Data Registry mới
3. Thêm 1 đoạn "Cập nhật {$current_year}" ở đầu bài (2-3 câu)
4. GIỮ NGUYÊN structure, tone, links, formatting
5. CHỈ thay đổi data lỗi thời, KHÔNG viết lại toàn bộ

Trả về JSON: { "content_html": "...", "changes_summary": "..." }
PROMPT;

    $result = xanh_ai_call_gemini_text( $prompt );
    if ( is_wp_error( $result ) ) {
        return $result;
    }

    // Update post
    wp_update_post( [
        'ID'            => $post_id,
        'post_content'  => $result['content_html'],
        'post_date'     => current_time( 'mysql' ),     // Bump date
        'post_date_gmt' => current_time( 'mysql', true ),
    ] );

    // Update meta
    update_post_meta( $post_id, '_xanh_ai_last_refreshed', current_time( 'mysql' ) );

    do_action( 'xanh_ai_content_refreshed', $post_id, $result['changes_summary'] ?? '' );

    return $post_id;
}
```

### Admin UI — Outdated Content Dashboard

```
┌─────────────────────────────────────────────────────────────┐
│ 🔄 Smart Content Updater — Bài Viết Cần Cập Nhật            │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  📊 Scan lần cuối: 01/01/2026 | Tiếp theo: 01/04/2026       │
│  Tìm thấy: 7 bài cần cập nhật | 23 bài OK                  │
│                                                              │
│  ┌────────────────────────────────────────────────────────┐  │
│  │ ⚠️ "Chi Phí Xây Nhà 2025" (245 ngày)                  │  │
│  │    Issues: Năm 2025→2026, Giá chưa cập nhật Q1/2026   │  │
│  │    [🔄 Auto-Refresh] [✏️ Edit Thủ Công] [⏭️ Bỏ qua]   │  │
│  ├────────────────────────────────────────────────────────┤  │
│  │ ⚠️ "Gạch AAC vs Gạch Đỏ" (198 ngày)                   │  │
│  │    Issues: 2 nguồn tham khảo hết hạn                   │  │
│  │    [🔄 Auto-Refresh] [✏️ Edit Thủ Công] [⏭️ Bỏ qua]   │  │
│  ├────────────────────────────────────────────────────────┤  │
│  │ ✅ "Xu Hướng Nội Thất 2026" (45 ngày) — OK             │  │
│  └────────────────────────────────────────────────────────┘  │
│                                                              │
│  [ 🚀 Auto-Refresh Tất Cả (7 bài) ]    [ ⚙️ Cài Đặt ]      │
└─────────────────────────────────────────────────────────────┘
```

### Settings

| Setting | Default | Mô tả |
|---|---|---|
| Refresh Threshold | `180 ngày` | Bài cũ hơn N ngày → quét |
| Auto-Refresh | `Off` | Tự động refresh không cần approve |
| Scan Frequency | `Quarterly` | Tần suất WP Cron scan |
| Bump Date | `On` | Cập nhật Publish Date khi refresh |
| Notification | `Email` | Gửi email khi scan xong |

---

## Tài Liệu Liên Quan

- `PLUGIN_AI_ADMIN.md` — UI chi tiết cho mỗi workflow
- `PLUGIN_AI_API.md` — API call patterns
- `PLUGIN_AI_SEO.md` §9 — Reverse Internal Linking
- `PLUGIN_AI_SEO.md` §10 — Automated Advanced JSON-LD
- `GOV_SEO_STRATEGY.md` §7 — Content calendar strategy
