# PLUGIN_AI_ARCHITECTURE — Kiến Trúc Plugin

> **Plugin:** XANH AI Content Generator
> **Pattern:** Singleton + Class-based OOP (theo `PLUGIN_CUSTOM_DEV.md` §4)
> **Cập nhật:** 2026-03-22

---

## 1. File Structure

```
wp-content/plugins/xanh-ai-content/
│
├── xanh-ai-content.php               # Bootstrap: header, constants, autoloader
├── uninstall.php                     # Cleanup: remove options + custom table
│
├── includes/                         # Core business logic
│   ├── class-xanh-ai-settings.php    # Settings API (encrypted key, models)
│   ├── class-xanh-ai-generator.php   # Text generation engine (Gemini 2.5)
│   ├── class-xanh-ai-image.php       # Image generation engine (Gemini 3.1)
│   ├── class-xanh-ai-seo.php         # SEO optimizer + Content Score
│   ├── class-xanh-ai-angles.php      # 9 angle definitions + 5 keyword clusters
│   ├── class-xanh-ai-batch.php       # Batch generation queue (WP Cron)      [P1]
│   ├── class-xanh-ai-calendar.php    # Content calendar logic                [P1]
│   ├── class-xanh-ai-keywords.php    # Keyword suggestion engine             [P1]
│   ├── class-xanh-ai-sources.php     # Reference Sources + Source Library    [P1]
│   ├── class-xanh-ai-scanner.php     # Data integrity scanner                [P1]
│   ├── class-xanh-ai-backlinks.php   # Reverse internal linking              [P1]
│   ├── class-xanh-ai-schema.php      # Advanced JSON-LD schema generator     [P1]
│   ├── class-xanh-ai-scheduler.php   # Auto-schedule posts                   [P2]
│   ├── class-xanh-ai-rewriter.php    # Content rewriter                      [P2]
│   ├── class-xanh-ai-history.php     # Generation history + DB table         [P2]
│   ├── class-xanh-ai-updater.php     # Smart content updater (evergreen)     [P2]
│   ├── class-xanh-ai-topics.php      # Topic idea generator                  [P2]
│   ├── class-xanh-ai-social.php      # Social media snippets                 [P3]
│   └── class-xanh-ai-analytics.php   # Usage dashboard                       [P3]
│
├── admin/                            # Admin-only code (behind is_admin())
│   ├── class-xanh-ai-admin.php       # Admin pages registration + AJAX
│   ├── views/                        # PHP view templates
│   │   ├── settings-page.php
│   │   ├── generator-page.php
│   │   ├── batch-page.php            [P1]
│   │   ├── calendar-page.php         [P1]
│   │   ├── sources-page.php          [P1]
│   │   ├── history-page.php          [P2]
│   │   ├── topics-page.php           [P2]
│   │   └── analytics-page.php        [P3]
│   ├── css/
│   │   └── xanh-ai-admin.css         # Admin styling (XANH brand)
│   └── js/
│       └── xanh-ai-admin.js          # AJAX, preview, inline edit, progress
│
└── languages/
    └── xanh-ai-content-vi.pot         # Vietnamese translation template
```

---

## 2. Class Diagram

```
Xanh_AI_Content (Singleton — main plugin class)
├── init_hooks()
│   ├── admin_menu → Xanh_AI_Admin
│   ├── admin_init → Xanh_AI_Settings
│   ├── wp_ajax_xanh_ai_preview_prompt → ajax_preview_prompt() (0 API calls)
│   ├── wp_ajax_xanh_ai_import_keywords → ajax_import_keywords() (CSV/TXT merge-dedup)
│   └── wp_ajax_* → AJAX handlers
│
├── Xanh_AI_Settings         → register_setting(), sanitize, encrypt API key
├── Xanh_AI_Angles           → get_angles(), get_angle_prompt(), get_angle_config(), get_keyword_clusters()
├── Xanh_AI_Generator        → generate_content(), build_prompt(), call_gemini_text()
├── Xanh_AI_Image            → generate_image(), build_image_prompt(), upload_to_media()
├── Xanh_AI_SEO              → optimize_post(), calculate_score(), inject_links()
├── Xanh_AI_Keywords         → suggest_keywords(), get_cluster_keywords()
├── Xanh_AI_Batch            → queue_batch(), process_next(), get_status()
├── Xanh_AI_Calendar         → get_calendar_data(), check_rotation_gaps()
├── Xanh_AI_Sources          → add_source(), process_file(), scrape_url(), get_sources()
├── Xanh_AI_Scanner          → scan_data_integrity(), check_expired_sources()
├── Xanh_AI_Backlinks        → scan_related_posts(), inject_backlink(), get_link_map()
├── Xanh_AI_Schema           → build_schema(), get_angle_schema_type(), inject_jsonld()
│
├── [P2] Xanh_AI_Scheduler   → schedule_post(), get_optimal_time()
├── [P2] Xanh_AI_Rewriter    → rewrite_post(), diff_changes()
├── [P2] Xanh_AI_History     → log_generation(), get_history(), create_table()
├── [P2] Xanh_AI_Updater     → scan_outdated(), refresh_post(), bump_date()
├── [P2] Xanh_AI_Topics      → generate_ideas(), rank_topics()
│
├── [P3] Xanh_AI_Social      → generate_snippets(), format_facebook(), format_zalo()
└── [P3] Xanh_AI_Analytics   → get_usage_stats(), calculate_cost()
```

---

## 3. Data Flow

### Single Post Generation
```
User Input (angle, topic, keywords)
      ↓
Xanh_AI_Prompts::build_system_prompt()  → Layer 1-7 (persona, voice DNA, anti-AI, E-E-A-T, angle, SEO, output)
      ↓  ← ajax_preview_prompt() returns here (0 API call) — user can edit before proceeding
Xanh_AI_Generator::generate()          → Call Gemini 2.5 Flash API (custom_prompt if user edited)
      ↓
Xanh_AI_SEO::optimize_post()            → Title, meta, slug, TOC, internal links
      ↓
Xanh_AI_SEO::calculate_score()          → Score 0-100 (client-side recalc on edit)
      ↓
Preview → User edits → Save Draft       → wp_insert_post() + FAQPage JSON-LD to _xanh_ai_faq_schema
      ↓
Image generation (manual / on-demand)   → Call Gemini Imagen API when user clicks button
      ↓
Xanh_AI_Backlinks::scan_related_posts() → Tìm bài cũ cùng category/keyword
      ↓
Xanh_AI_Backlinks::inject_backlink()    → Chèn link bài mới vào bài cũ (khi publish)
      ↓
Xanh_AI_History::log_generation()       → Log to custom table [P2]
```

### Batch Generation
```
User Input (angle, N topics, keywords)
      ↓
Xanh_AI_Batch::queue_batch()           → Store in transient queue
      ↓
WP Cron → Xanh_AI_Batch::process_next() (1 post/30s)
      ↓
[Repeat Single Flow for each item]
      ↓
Admin UI: Progress bar (pending → generating → done/error)
```

---

## 4. Database

### Options (wp_options)

| Option Name | Type | Encrypted |
|---|---|---|
| `xanh_ai_gemini_key` | string | ✅ AES via wp_salt() |
| `xanh_ai_text_model` | string | — |
| `xanh_ai_image_model` | string | — |
| `xanh_ai_image_aspect` | string | — |
| `xanh_ai_image_size` | string | — |
| `xanh_ai_auto_image` | bool | — |
| `xanh_ai_default_author` | int | — |
| `xanh_ai_temperature` | float | — |
| `xanh_ai_keyword_clusters` | JSON (array) | — |
| `xanh_ai_schedule_frequency` | string | — |
| `xanh_ai_schedule_time` | string | — |

### Custom Table [P2] — `{prefix}xanh_ai_history`

| Column | Type | Description |
|---|---|---|
| `id` | BIGINT AUTO_INCREMENT | Primary key |
| `post_id` | BIGINT | Generated post ID (nullable) |
| `angle` | VARCHAR(50) | Content angle used |
| `topic` | VARCHAR(255) | Topic input |
| `keywords` | TEXT | Keywords used |
| `tokens_used` | INT | Total tokens consumed |
| `model_text` | VARCHAR(100) | Text model |
| `model_image` | VARCHAR(100) | Image model |
| `score` | INT | Content score |
| `status` | ENUM | pending/success/error |
| `error_message` | TEXT | Error details (nullable) |
| `created_at` | DATETIME | Timestamp |

### Custom Table [P1] — `{prefix}xanh_ai_sources`

| Column | Type | Description |
|---|---|---|
| `id` | BIGINT AUTO_INCREMENT | Primary key |
| `title` | VARCHAR(255) | Tiêu đề nguồn |
| `type` | ENUM | file / url / note |
| `source_url` | TEXT | URL gốc hoặc attachment URL |
| `attachment_id` | BIGINT | WP Media Library ID (nullable) |
| `extracted_text` | LONGTEXT | Nội dung đã extract |
| `summary` | TEXT | AI summary |
| `key_data` | JSON | Số liệu trích xuất |
| `publisher` | VARCHAR(255) | Nhà xuất bản |
| `publish_date` | DATE | Ngày xuất bản |
| `expiry_date` | DATE | Hết hạn (nullable) |
| `tags` | VARCHAR(255) | Tags |
| `is_active` | TINYINT(1) | Active / Archived |
| `created_at` | DATETIME | Timestamp |

### Post Meta

| Meta Key | Description |
|---|---|
| `_xanh_ai_generated` | `1` = post was AI-generated |
| `_xanh_ai_angle` | Angle ID used |
| `_xanh_ai_score` | Content score 0-100 |
| `_xanh_ai_tokens` | Tokens consumed |
| `_xanh_ai_sources` | JSON array of source IDs used |
| `_xanh_ai_schema_type` | Schema type used (Article, LocalBusiness...) |
| `_xanh_ai_image_prompt` | Prompt tiếng Anh để tạo AI thumbnail thủ công sau này |
| `_xanh_ai_backlinks_injected` | JSON array of post IDs đã được chèn link |
| `_xanh_ai_last_refreshed` | Datetime lần cuối Smart Updater cập nhật [P2] |

---

## 5. Hook System (Extensibility)

### Actions
```php
do_action('xanh_ai_before_generate', $angle, $topic, $keywords);
do_action('xanh_ai_after_generate', $post_id, $content, $score);
do_action('xanh_ai_before_image', $prompt);
do_action('xanh_ai_after_image', $attachment_id);
do_action('xanh_ai_batch_item_complete', $post_id, $index, $total);
do_action('xanh_ai_batch_complete', $post_ids);
do_action('xanh_ai_backlink_injected', $target_post_id, $new_post_id);
do_action('xanh_ai_schema_injected', $post_id, $schema_type);
do_action('xanh_ai_content_refreshed', $post_id, $changes);  // [P2]
```

### Filters
```php
$prompt   = apply_filters('xanh_ai_system_prompt', $prompt, $angle);
$content  = apply_filters('xanh_ai_generated_content', $content, $post_id);
$score    = apply_filters('xanh_ai_content_score', $score, $post_id);
$img_prompt = apply_filters('xanh_ai_image_prompt', $img_prompt, $title);
$post_args  = apply_filters('xanh_ai_post_args', $args, $angle);
$clusters   = apply_filters('xanh_ai_keyword_clusters', $clusters);
$schema     = apply_filters('xanh_ai_schema_data', $schema, $post_id, $angle);
$backlink_posts = apply_filters('xanh_ai_backlink_candidates', $posts, $new_post_id);
```

---

## Tài Liệu Liên Quan

- `PLUGIN_AI_OVERVIEW.md` — Feature map
- `PLUGIN_AI_API.md` — Gemini API chi tiết
- `PLUGIN_CUSTOM_DEV.md` §4 — Plugin architecture pattern
- `GOV_CODING_STANDARDS.md` §4 — PHP standards
