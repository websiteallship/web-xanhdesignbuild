# PLUGIN_AI_ADMIN — Admin UI & Dashboard

> **Plugin:** XANH AI Content Generator
> **Cập nhật:** 2026-03-20

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

## 6. Usage Dashboard [P3]

### Metrics

| Metric | Source |
|---|---|
| Tổng bài tạo (tháng) | History table |
| Tokens used (tháng) | History table SUM |
| API cost estimate | tokens × price per token |
| Avg Content Score | History AVG |
| Top angles used | History GROUP BY |
| Success rate | Success / Total |

### Chart
- Monthly trend: posts generated + tokens used (bar + line chart)
- Pie chart: angle distribution

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
