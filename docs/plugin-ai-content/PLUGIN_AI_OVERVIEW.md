# PLUGIN_AI_OVERVIEW — Tổng Quan Plugin AI Content Generator

> **Plugin:** XANH AI Content Generator
> **Slug:** `xanh-ai-content`
> **Version:** 1.2.0 | **Ngày tạo:** 2026-03-20 | **Cập nhật:** 2026-03-22
> **Stack:** PHP 7.4+ | WordPress 6.0+ | Google Gemini API
> **Text Domain:** `xanh-ai-content`

---

## 1. Mục Tiêu

Plugin tự động tạo bài viết blog + hình ảnh cho website XANH - Design & Build bằng AI (Google Gemini), tuân thủ:

- `GOV_BRAND_VOICE.md` — Warm luxury tone, từ khóa NÊN/CẤM
- `GOV_SEO_STRATEGY.md` — Keyword clusters, content calendar, on-page SEO
- `GOV_CODING_STANDARDS.md` — PHP standards, security patterns
- `ARCH_LUXURY_VISUAL_DIRECTION.md` — Visual direction, editorial photography

---

## 2. Feature Map — 30 Tính Năng, 3 Phases

### 🔴 Phase 1 — Core + High Priority (17 features)

| # | Feature | File tham chiếu |
|---|---|---|
| 1 | AI Text Generation (Gemini 2.5 Flash) | `PLUGIN_AI_API.md` §1 |
| 2 | AI Image Generation (Gemini 3.1 Imagen) | `PLUGIN_AI_API.md` §2 |
| 3 | 9 Content Angles | `PLUGIN_AI_ANGLES.md` |
| 4 | SEO Auto-Optimizer | `PLUGIN_AI_SEO.md` |
| 5 | Internal Linking Engine | `PLUGIN_AI_SEO.md` §3 |
| 6 | Draft Workflow (Generate → Preview → Edit → Save) | `PLUGIN_AI_WORKFLOW.md` §1 |
| 7 | RankMath Compatibility | `PLUGIN_AI_SEO.md` §5 |
| 8 | Settings Page (API key, models, config) | `PLUGIN_AI_ADMIN.md` §1 |
| 9 | Security Layer (nonce, capability, sanitize) | `PLUGIN_AI_SECURITY.md` |
| 10 | Brand Voice Engine | `PLUGIN_AI_ANGLES.md` §2 |
| 11 | Batch Generate (3-10 posts, queue) | `PLUGIN_AI_WORKFLOW.md` §2 |
| 12 | Content Calendar Dashboard | `PLUGIN_AI_ADMIN.md` §3 |
| 13 | AI Regenerate Sections | `PLUGIN_AI_WORKFLOW.md` §3 |
| 14 | Keyword Suggestion + Management | Auto-suggest từ 5 keyword clusters khi chọn Angle. CRUD via Settings UI + CSV/TXT upload (merge-dedup) |
| 15 | Content Score | Chấm điểm SEO + Brand compliance trước publish |
| 16 | Reference Sources | Upload file (PDF/CSV/MD) hoặc URL bài báo làm căn cứ cho AI |
| 17 | Source Library | Thư viện nguồn tham khảo global, quản lý expiry, auto-extract data |
| 18 | Data Integrity Scanner | Tự động detect số liệu AI bịa, cảnh báo trước khi publish |
| 19 | Reverse Internal Linking | Khi publish bài mới, tự động chèn link vào bài cũ cùng topic | `PLUGIN_AI_SEO.md` §9 |
| 20 | Automated Advanced JSON-LD Schema | Auto-generate schema nâng cao theo Angle (Article, LocalBusiness, Project) | `PLUGIN_AI_SEO.md` §10 |

### 🟡 Phase 2 — Nâng Cấp (7 features)

| # | Feature | File |
|---|---|---|
| 19 | Auto Schedule | `PLUGIN_AI_WORKFLOW.md` §4 |
| 20 | In-Content Images (2-3 per post) | `PLUGIN_AI_API.md` §3 |
| 21 | Topic Idea Generator | `PLUGIN_AI_WORKFLOW.md` §5 |
| 22 | Content Rewriter | `PLUGIN_AI_WORKFLOW.md` §6 |
| 23 | FAQ Auto-Generate + Schema | `PLUGIN_AI_SEO.md` §7 |
| 24 | Generation History/Log | `PLUGIN_AI_ADMIN.md` §4 |
| 25 | Smart Content Updater | Tự động quét bài cũ, cập nhật data/giá/năm, bump publish date | `PLUGIN_AI_WORKFLOW.md` §10 |

### 🟢 Phase 3 — Nice-to-have (6 features)

> **Note:** Feature numbers adjusted after adding new features above.

| # | Feature | File |
|---|---|---|
| 25 | Multi-language (EN) | `PLUGIN_AI_WORKFLOW.md` §7 |
| 26 | Social Media Snippets | `PLUGIN_AI_WORKFLOW.md` §8 |
| 27 | Title A/B Testing | `PLUGIN_AI_SEO.md` §8 |
| 28 | Competitor Analysis | `PLUGIN_AI_WORKFLOW.md` §9 |
| 29 | Webhook/Notification | `PLUGIN_AI_ADMIN.md` §5 |
| 30 | Usage Dashboard | `PLUGIN_AI_ADMIN.md` §6 |

---

## 3. Tech Stack

| Component | Technology |
|---|---|
| Text AI | Google Gemini 2.5 Flash (`gemini-2.5-flash`) |
| Image AI | Google Gemini 3.1 Flash Image (`gemini-3.1-flash-image-preview`) |
| API Transport | PHP `wp_remote_post()` → REST `generativelanguage.googleapis.com` |
| Admin UI | WordPress Settings API + custom admin pages |
| Styling | XANH brand CSS (Inter font, green/beige/orange palette) |
| Queue | WP Cron (batch generation) |
| Storage | `wp_options` (settings), `wp_posts` (drafts), custom table (history) |
| Security | Nonce + Capability + Encrypt + Rate Limit |

---

## 4. Tài Liệu Trong Folder Này

| File | Nội dung |
|---|---|
| `PLUGIN_AI_OVERVIEW.md` | ★ Tổng quan (file này) |
| `PLUGIN_AI_ARCHITECTURE.md` | Cấu trúc plugin, class diagram, data flow |
| `PLUGIN_AI_ANGLES.md` | 9 content angles, 5 keyword clusters, prompt templates, brand voice |
| `PLUGIN_AI_API.md` | Gemini API integration (text + image) |
| `PLUGIN_AI_SEO.md` | SEO engine, content score, keywords |
| `PLUGIN_AI_WORKFLOW.md` | Quy trình: single, batch, schedule, rewrite |
| `PLUGIN_AI_ADMIN.md` | Admin UI, settings, calendar, history |
| `PLUGIN_AI_SECURITY.md` | Security patterns, encryption, rate limiting |
| `PLUGIN_AI_PROMPTS.md` | Prompt engineering, anti-AI detection, 7-layer system |
| `PLUGIN_AI_BRAND_VOICE.md` | Brand voice cho AI, persona, E-E-A-T, CTA |
| `PLUGIN_AI_DATA_INTEGRITY.md` | Chống AI bịa số liệu, Reference Sources, Source Library |

---

## 5. Governance Compliance

| Governance Doc | Áp dụng trong plugin |
|---|---|
| `GOV_BRAND_VOICE.md` | System prompt cho AI: warm luxury tone, banned words list |
| `GOV_SEO_STRATEGY.md` | Keyword clusters, title/meta templates, content calendar |
| `GOV_CODING_STANDARDS.md` | `xanh_ai_` prefix, BEM CSS, JS module pattern, security |
| `ARCH_LUXURY_VISUAL_DIRECTION.md` | Image prompt style, admin UI aesthetic |
| `PLUGIN_CUSTOM_DEV.md` §4-5 | Plugin architecture pattern, development standards |

---

## Tài Liệu Liên Quan

- `PLUGIN_CUSTOM_DEV.md` — Plugin architecture pattern chung
- `GOV_BRAND_VOICE.md` — Brand voice chi tiết
- `GOV_SEO_STRATEGY.md` — Keyword strategy & content calendar
- `GOV_CODING_STANDARDS.md` — Coding standards
- `ARCH_LUXURY_VISUAL_DIRECTION.md` — Visual direction
