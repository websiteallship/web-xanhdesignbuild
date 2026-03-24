# PLUGIN_AI_ADMIN — Admin UI & Dashboard

> **Plugin:** XANH AI Content Generator
> **Cập nhật:** 2026-03-22

---

## Admin Menu Structure

```
XANH AI (icon: dashicons-edit-page)
├── Tạo Bài Viết          → generator-page.php       [P1]
├── Tạo Hàng Loạt          → batch-page.php           [P1]
├── Lịch Nội Dung           → calendar-page.php        [P1]
├── Thư Viện Nguồn         → sources-page.php         [P1]
├── Ý Tưởng Chủ Đề         → topics-page.php          [P2]
├── Lịch Sử                 → history-page.php         [P2]
├── Thống Kê                → analytics-page.php       [P3]
└── Cài Đặt                 → settings-page.php        [P1]
```

### Menu Registration
```php
function xanh_ai_admin_menu(): void {
    add_menu_page(
        'XANH AI Content',
        'XANH AI',
        'edit_posts',
        'xanh-ai',
        [ $this, 'render_generator_page' ],
        'dashicons-edit-page',
        30
    );

    add_submenu_page( 'xanh-ai', 'Tạo Bài Viết', 'Tạo Bài Viết', 'edit_posts', 'xanh-ai' );
    add_submenu_page( 'xanh-ai', 'Tạo Hàng Loạt', 'Tạo Hàng Loạt', 'edit_posts', 'xanh-ai-batch' );
    add_submenu_page( 'xanh-ai', 'Lịch Nội Dung', 'Lịch Nội Dung', 'edit_posts', 'xanh-ai-calendar' );
    add_submenu_page( 'xanh-ai', 'Thư Viện Nguồn', 'Thư Viện Nguồn', 'edit_posts', 'xanh-ai-sources' );
    add_submenu_page( 'xanh-ai', 'Cài Đặt', 'Cài Đặt', 'manage_options', 'xanh-ai-settings' );
}
```

---

## 1. Settings Page [P1]

### Sections

#### API Configuration
| Field | Type | Default | Validation |
|---|---|---|---|
| Gemini API Key | password input | — | Required, encrypted storage |
| Test Connection | button | — | Call API health check |
| Text Model | select | `gemini-2.5-flash` | Whitelist models |
| Image Model | select | `gemini-3.1-flash-image-preview` | Whitelist models |
| Temperature | range slider | `0.7` | 0.0 - 1.0 |

#### Image Settings
| Field | Type | Default |
|---|---|---|
| Auto-generate Image | toggle | On |
| Aspect Ratio | select | `16:9` |
| Image Size | select | `2K` |

#### Content Defaults
| Field | Type | Default |
|---|---|---|
| Default Author | user select | Admin |
| Default Status | select | `draft` |

#### Keyword Cluster Management
| Field | Type | Default |
|---|---|---|
| Cluster 1-5 Keywords | textarea (mỗi dòng = 1 keyword) | Hardcoded defaults |
| Import CSV/TXT | file upload + preview | — |
| Import Mode | — | Merge with de-duplication |

> Keywords được lưu vào `wp_options` key `xanh_ai_keyword_clusters`. Fallback về hardcoded defaults nếu chưa có data.

#### Schedule [P2]
| Field | Type | Default |
|---|---|---|
| Frequency | select | `2/week` |
| Publish Time | time picker | `08:00` |

#### Notifications [P3]
| Field | Type | Default |
|---|---|---|
| Email Notification | email | admin email |
| Zalo Webhook | URL | — |

---

## 2. Generator Page [P1]

### Layout

```
┌──────────────────────────────────────────────────────────────────┐
│ XANH AI — Tạo Bài Viết                                          │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌─ Step 1: Chọn Góc Viết ──────────────────────────────────┐   │
│  │  [🏢 Dịch vụ] [🛋️ Vật liệu] [📍 SEO Local] [📚 Kiến thức] │   │
│  │  [💡 Kinh nghiệm] [🌿 Xu hướng] [👷 Nhật ký] [🏆 Case study]│   │
│  └───────────────────────────────────────────────────────────┘   │
│                                                                  │
│  ┌─ Step 2: Chi Tiết ───────────────────────────────────────┐   │
│  │  Topic:        [________________________]                 │   │
│  │  Keyword:      [________________] 📋 Gợi ý từ SEO cluster│   │
│  │  Keywords phụ: [________________________]                 │   │
│  │  Độ dài:       [Standard ▾]                               │   │
│  │  Ghi chú:      [________________________]                 │   │
│  └───────────────────────────────────────────────────────────┘   │
│                                                                  │
│  [ ✨ Tạo Nội Dung ]                                             │
│                                                                  │
│  ┌─ Preview ─────────────────────────────────────────────────┐   │
│  │  Score: [85/100 🔵]                                       │   │
│  │  ┌─ Title ──────────────────────────────────────┐         │   │
│  │  │  Chi Phí Xây Nhà Phố 2026 | XANH            │ [✎]    │   │
│  │  ├─ Meta ───────────────────────────────────────┤         │   │
│  │  │  Tìm hiểu chi phí xây nhà phố... (148/160)  │ [✎]    │   │
│  │  ├─ Featured Image ─────────────────────────────┤         │   │
│  │  │  [🖼️ AI Generated Image Preview]              │         │   │
│  │  │  [🔄 Tạo Lại] [📤 Upload]                    │         │   │
│  │  ├─ Table of Contents ──────────────────────────┤         │   │
│  │  │  1. Tổng Quan Chi Phí                        │         │   │
│  │  │  2. Các Yếu Tố Ảnh Hưởng                    │         │   │
│  │  │  3. Bảng Giá Tham Khảo                       │         │   │
│  │  ├─ Content ────────────────────────────────────┤         │   │
│  │  │  ## 1. Tổng Quan Chi Phí           [✏️ Viết lại]│       │   │
│  │  │  (full rich content preview)                  │         │   │
│  │  │  ## 2. Các Yếu Tố Ảnh Hưởng       [✏️ Viết lại]│       │   │
│  │  │  ...                                          │         │   │
│  │  ├─ Score Breakdown ────────────────────────────┤         │   │
│  │  │  ✅ Title: 45/60 chars                        │         │   │
│  │  │  ✅ Word count: 1,230 words                   │         │   │
│  │  │  ✅ Internal links: 2 found                   │         │   │
│  │  │  ❌ Banned words: "giá rẻ" found             │         │   │
│  │  └──────────────────────────────────────────────┘         │   │
│  │                                                           │   │
│  │  [ 📝 Lưu Draft ]  [ 📋 Copy HTML ]                      │   │
│  └───────────────────────────────────────────────────────────┘   │
└──────────────────────────────────────────────────────────────────┘
```

---

## 3. Content Calendar [P1]

### Layout — Monthly View

```
┌─────────────────────────────────────────────────────────────┐
│ Lịch Nội Dung — Tháng 3, 2026                   [◀ ▶]     │
├─────┬─────┬─────┬─────┬─────┬─────┬─────────────────────────┤
│ Mon │ Tue │ Wed │ Thu │ Fri │ Sat │ Legend:                  │
├─────┼─────┼─────┼─────┼─────┼─────┤ 📘 Kinh Nghiệm          │
│     │     │     │     │     │     │ 🧱 Vật Liệu             │
│     │     │     │     │     │     │ 🌿 Xu Hướng              │
│  2  │  3  │  4  │ 5📘│  6  │  7  │ 👷 Nhật Ký               │
│     │Draft│     │Pub  │     │     │                          │
├─────┼─────┼─────┼─────┼─────┼─────┤ Status:                  │
│  9  │ 10  │ 11  │12🧱 │ 13  │ 14  │ 📝 Draft                 │
│     │     │     │Sched│     │     │ 📅 Scheduled             │
├─────┼─────┼─────┼─────┼─────┼─────┤ ✅ Published             │
│ 16  │ 17  │ 18  │19🌿 │ 20  │ 21  │ ⚠️ Gap (cần bài mới)    │
│     │     │     │⚠gap │     │     │                          │
├─────┼─────┼─────┼─────┼─────┼─────┤                          │
│ 23  │ 24  │ 25  │26👷 │ 27  │ 28  │                          │
│     │     │     │     │     │     │                          │
└─────┴─────┴─────┴─────┴─────┴─────┴──────────────────────────┘
```

### Features
- Color-coded by category (4-week rotation)
- Click date → create new post (redirect to Generator)
- Click post → edit in WP editor
- ⚠️ Gap detection: highlight dates missing content per schedule
- Filter: by category, status, angle

---

## 4. Generation History [P2]

### Table View

| Ngày | Topic | Angle | Score | Tokens | Status | Actions |
|---|---|---|---|---|---|---|
| 20/03 | Chi Phí Xây Nhà | 💡 Kinh nghiệm | 85 | 4,200 | ✅ Draft | [View] [Edit] |
| 19/03 | Gạch AAC vs Đỏ | 🛋️ Vật liệu | 92 | 3,800 | ✅ Published | [View] |
| 18/03 | Xu Hướng 2026 | 🌿 Xu hướng | 78 | 5,100 | ✅ Scheduled | [View] [Edit] |
| 17/03 | Biệt Thự ABC | 🏆 Case study | — | 3,500 | ❌ Error | [Retry] |

### Filters
- Date range
- Angle
- Status (success/error)
- Search by topic

### Stats (summary bar)
- Total posts generated: 45
- Success rate: 93%
- Avg score: 82/100
- Total tokens: 189,000

---

## 5. Webhook/Notification [P3]

### Email Notification
- Trigger: khi batch complete hoặc scheduled post tạo xong
- Template: "XANH AI đã tạo {N} bài viết. Review tại: {admin_url}"

### Zalo Webhook
- POST to custom URL với JSON payload
- `{ "event": "post_generated", "title": "...", "url": "..." }`

---

## 6. Usage Dashboard — AI Analytics [IMPLEMENTED ✅]

> **File:** `admin/views/dashboard-page.php`
> **Tracker:** `includes/class-xanh-ai-tracker.php`
> **Chart Library:** Chart.js v4 (CDN)
> **Data Storage:** `wp_options` key `xanh_ai_usage_YYYY_MM` (monthly JSON)

### Layout

```
┌──────────────────────────────────────────────────────────────────┐
│ AI Analytics Dashboard                    [Export CSV] [Reset]   │
│ Tháng này (2026-03-01 — 2026-03-24)                             │
├──────────────────────────────────────────────────────────────────┤
│ [Hôm nay] [Hôm qua] [7 ngày qua] [Tuần này]                   │
│ [●Tháng này] [Tháng trước] [Năm nay] [📅 Tuỳ chỉnh]           │
│   └─ Từ [____] Đến [____] [Áp dụng]   (shown on click)        │
├──────────────────────────────────────────────────────────────────┤
│ ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌────────┐ │
│ │ Tổng     │ │ API      │ │ Hình Ảnh │ │ Chi Phí  │ │ TB/Req │ │
│ │ Token    │ │ Requests │ │ AI       │ │ Ước Tính │ │        │ │
│ │ 9,932    │ │ 5        │ │ 1        │ │ 1.077 ₫  │ │ 1,986  │ │
│ │ 6K in    │ │ all      │ │ ImageGen │ │ $0.0422  │ │ tok/req│ │
│ │ + 3.8K   │ │ models   │ │          │ │ USD      │ │        │ │
│ └──────────┘ └──────────┘ └──────────┘ └──────────┘ └────────┘ │
├──────────────────────────────────────────────────────────────────┤
│ 📊 Token Usage — Tháng này                                      │
│ ┌────────────────────────────────────────────────────────────┐   │
│ │  [Chart.js stacked bar: Input ■ Output ■ Images ■]        │   │
│ │  X: days (01/03 ... 24/03)                                 │   │
│ │  Y-left: Tokens (0 - 7K)   Y-right: Images (0 - 1)       │   │
│ └────────────────────────────────────────────────────────────┘   │
├──────────────────────────────────────────────────────────────────┤
│ 🗄️ Chi Tiết theo Model — Tháng này                              │
│ ┌────────────┬──────┬────────┬────────┬──────┬──────────┐       │
│ │ Model      │ Calls│ Input  │ Output │ Imgs │ Cost USD │       │
│ ├────────────┼──────┼────────┼────────┼──────┼──────────┤       │
│ │ gemini-2.5 │ 4    │ 6,056  │ 3,876  │ 0    │ $0.0032  │       │
│ │ ████████── │      │        │        │      │          │       │
│ │ gemini-3.1 │ 1    │ 0      │ 0      │ 1    │ $0.0390  │       │
│ └────────────┴──────┴────────┴────────┴──────┴──────────┘       │
├──────────────────────────────────────────────────────────────────┤
│ 📅 Lịch Sử Sử Dụng (6 Tháng)                                   │
│ ┌────────┬──────┬────────┬──────┬──────────┐                    │
│ │ Tháng  │ Calls│ Tokens │ Imgs │ Cost USD │                    │
│ ├────────┼──────┼────────┼──────┼──────────┤                    │
│ │ 2026/03│ 5    │ 9,932  │ 1    │ $0.0422  │ ← hiện tại        │
│ │ 2026/02│ 0    │ 0      │ 0    │ $0.0000  │                    │
│ │ ...    │      │        │      │          │                    │
│ └────────┴──────┴────────┴──────┴──────────┘                    │
│ Footer: Cập nhật lần cuối: ... • Chi phí dựa trên bảng giá     │
│         Gemini API (Pay-as-you-go). Free tier = $0.             │
└──────────────────────────────────────────────────────────────────┘
```

### Date Range Presets (8)

| Preset | Start Date | End Date |
|---|---|---|
| `today` | today | today |
| `yesterday` | yesterday | yesterday |
| `last_7` | today - 6 days | today |
| `this_week` | Monday | today |
| `this_month` | 1st of month | today |
| `last_month` | 1st of prev month | last day of prev month |
| `this_year` | Jan 1st | today |
| `custom` | user input `from` | user input `to` |

URL scheme: `?page=xanh-ai-dashboard&preset=last_7` hoặc `&preset=custom&from=2026-03-01&to=2026-03-24`

### Stat Cards (5)

| Card | Metric | Source |
|---|---|---|
| Tổng Token | `text_input + text_output` tất cả models | Tracker range data |
| API Requests | `total_api_calls` | Tracker range data |
| Hình Ảnh AI | `image_calls` tất cả models | Tracker range data |
| Chi Phí Ước Tính | VND (primary) + USD (sub) | `estimate_cost_from_data()` |
| Trung Bình / Request | `total_tokens / total_api_calls` | Computed |

### Cost Estimation — Gemini Pricing

```php
// class-xanh-ai-tracker.php
const PRICING = [
    'gemini-2.5-flash'      => [ 'input' => 0.15,  'output' => 0.60  ], // per 1M tokens
    'gemini-2.5-flash-lite'  => [ 'input' => 0.075, 'output' => 0.30  ],
    'gemini-2.5-pro'         => [ 'input' => 1.25,  'output' => 10.00 ],
    'gemini-3.1-flash-image' => [ 'per_image' => 0.039 ],
    'imagen-4.0'             => [ 'per_image' => 0.04  ],
];
const USD_TO_VND = 25_500;
```

### Chart.js Configuration
- **Type:** Grouped bar chart
- **Datasets:** Input Tokens (blue), Output Tokens (green), Images (red, right Y-axis)
- **X-axis:** Day labels (dd/mm format)
- **Y-left:** Tokens scale (auto with K suffix)
- **Y-right:** Image count (integer step)
- **Interaction:** Index mode, intersect false
- **Tooltip:** Custom formatter with `toLocaleString()`

### AJAX Endpoints

| Action | Method | Params | Response |
|---|---|---|---|
| `xanh_ai_export_usage` | POST | `month` (YYYY_MM) | `{ csv, month }` |
| `xanh_ai_reset_usage` | POST | `month` (YYYY_MM) | `{ message }` |

### Data Architecture

```php
// wp_options key: xanh_ai_usage_2026_03
[
    'models' => [
        'gemini-2.5-flash' => [
            'text_input'  => 6056,
            'text_output' => 3876,
            'image_calls' => 0,
            'api_calls'   => 4,
        ],
        'gemini-3.1-flash-image-preview' => [ ... ],
    ],
    'total_api_calls' => 5,
    'daily' => [
        '24' => [ 'text_input' => 6056, 'text_output' => 3876, 'image_calls' => 1, 'api_calls' => 5 ],
    ],
    'last_updated' => '2026-03-24T16:40:30+07:00',
]
```

### Tracker Methods

| Method | Purpose |
|---|---|
| `record_usage($model, $input, $output, $images)` | Ghi nhận usage + daily tracking |
| `get_month($ym)` | Lấy data 1 tháng |
| `get_summary($n)` | Lấy data N tháng gần nhất |
| `get_range($start, $end)` | Aggregate daily data theo date range |
| `estimate_cost($ym)` | Chi phí 1 tháng |
| `estimate_cost_from_data($models)` | Chi phí từ arbitrary model data |
| `export_csv($ym)` | Xuất CSV |
| `reset_month($ym)` | Xóa data tháng |
| `get_available_months($n)` | Danh sách tháng có data |

---

## Admin Styling — XANH Brand

### CSS Custom Properties (admin)
```css
:root {
    --xanh-primary: #14513D;
    --xanh-primary-dark: #0a2e22;
    --xanh-accent: #FF8A00;
    --xanh-beige: #D8C7A3;
    --xanh-white: #FFFFFF;
    --xanh-text: #1a1612;
    --xanh-font: 'Inter', -apple-system, sans-serif;
    --xanh-radius: 8px;
    --xanh-transition: 300ms ease;
}
```

### Design Guidelines
- Buttons primary: `--xanh-accent` background
- Headers: `--xanh-primary` background
- Cards: white background, subtle shadow, 8px radius
- Font: Inter (via Google Fonts CDN)
- Transitions: 300ms (per `ARCH_LUXURY_VISUAL_DIRECTION.md`)
- No bounce/shake animations (luxury anti-patterns)

---

## Tài Liệu Liên Quan

- `PLUGIN_AI_WORKFLOW.md` — Quy trình chi tiết
- `PLUGIN_AI_ARCHITECTURE.md` — File structure
- `ARCH_LUXURY_VISUAL_DIRECTION.md` — Visual direction
