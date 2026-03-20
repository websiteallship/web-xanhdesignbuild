# PLUGIN_AI_WORKFLOW — Quy Trình Sử Dụng

> **Plugin:** XANH AI Content Generator
> **Cập nhật:** 2026-03-20

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

Bước 3: Generate
├── Click "✨ Tạo Nội Dung"
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
├── Featured Image (auto-generated)
│   └── Buttons: "🔄 Tạo Lại Hình" | "📤 Upload Thủ Công"
├── Tags (editable chips)
└── FAQ section (expandable, editable)

Bước 5: Save
├── "📝 Lưu Draft" → wp_insert_post (status: draft)
├── Set: category, tags, featured image, meta, RankMath fields
├── Log: generation history [P2]
└── Redirect → WP Post Editor (for final review)
```

### AJAX Endpoints
```
wp_ajax_xanh_ai_generate_content     → Generate text via AI
wp_ajax_xanh_ai_generate_image       → Generate featured image
wp_ajax_xanh_ai_regenerate_section   → Rewrite 1 section
wp_ajax_xanh_ai_save_draft           → Save as WP draft
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

## Tài Liệu Liên Quan

- `PLUGIN_AI_ADMIN.md` — UI chi tiết cho mỗi workflow
- `PLUGIN_AI_API.md` — API call patterns
- `GOV_SEO_STRATEGY.md` §7 — Content calendar strategy
