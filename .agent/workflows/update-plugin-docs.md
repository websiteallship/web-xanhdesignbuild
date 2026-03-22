---
description: Cập nhật docs khi có tính năng mới cho plugin XANH AI Content. Dùng sau khi implement hoặc thay đổi feature trong xanh-ai-content plugin.
---

# Quy Trình Cập Nhật Docs — Plugin AI Content

> **Docs folder:** `docs/plugin-ai-content/`
> **Trigger:** Khi implement xong hoặc thay đổi đáng kể một tính năng trong plugin `xanh-ai-content`

---

## Bước 1: Xác Định Phạm Vi Thay Đổi

Trả lời các câu hỏi sau:

| Câu hỏi | Ghi chú |
|---|---|
| **Feature gì?** | Tên feature, feature number (nếu có từ OVERVIEW) |
| **File code nào thay đổi?** | List các file `.php`, `.js`, `.css` đã sửa/thêm/xóa |
| **Loại thay đổi?** | Thêm mới / Sửa đổi / Xóa / Refactor |
| **Ảnh hưởng giao diện Admin?** | Có thay đổi UI settings, admin page, hoặc AJAX? |
| **Ảnh hưởng API?** | Có thêm/sửa endpoint, model, hoặc API call? |
| **Ảnh hưởng SEO/Schema?** | Có thay đổi SEO logic, schema, hoặc linking? |
| **Ảnh hưởng security?** | Có thêm nonce, capability check, sanitize, encrypt? |
| **Ảnh hưởng database?** | Có thêm option, meta key, custom table column? |

---

## Bước 2: Xác Định File Docs Cần Cập Nhật

Dựa trên phạm vi thay đổi, chọn **TẤT CẢ** file docs liên quan từ bảng mapping dưới đây:

| File Docs | Cập nhật KHI... |
|---|---|
| `PLUGIN_AI_OVERVIEW.md` | Thêm/xóa feature khỏi Feature Map, thay đổi Phase, đổi tech stack, hoặc thêm doc mới vào folder |
| `PLUGIN_AI_ARCHITECTURE.md` | Thêm/xóa file code, thay đổi class, thêm hook/filter, thêm DB table/column/option/meta key, thay đổi data flow |
| `PLUGIN_AI_ANGLES.md` | Thêm/sửa/xóa content angle, thay đổi angle config, prompt template, hoặc tone mapping |
| `PLUGIN_AI_API.md` | Đổi model, thêm API endpoint, thay đổi request/response format, thêm retry logic, rate limit |
| `PLUGIN_AI_SEO.md` | Thay đổi SEO optimizer, content score, internal linking, RankMath integration, schema, reverse linking |
| `PLUGIN_AI_WORKFLOW.md` | Thay đổi generation flow, batch process, schedule, rewrite, regenerate, topic generation |
| `PLUGIN_AI_ADMIN.md` | Thay đổi admin page, settings UI, calendar, history page, analytics dashboard |
| `PLUGIN_AI_SECURITY.md` | Thay đổi nonce, capability, encrypt, rate limit, sanitize/escape pattern |
| `PLUGIN_AI_PROMPTS.md` | Thay đổi prompt system, 7-layer structure, anti-AI patterns, output guard |
| `PLUGIN_AI_BRAND_VOICE.md` | Thay đổi brand persona, tone rules, banned words, CTA templates, E-E-A-T |
| `PLUGIN_AI_DATA_INTEGRITY.md` | Thay đổi data scanner, verified registry, reference sources, source library |
| `PLUGIN_AI_ROADMAP.md` | Feature hoàn thành (tick deliverable), thay đổi timeline, thêm/bỏ feature khỏi sprint |

> [!IMPORTANT]
> Một feature thường ảnh hưởng **2-4 file docs**. Ví dụ: thêm 1 class mới → cập nhật cả `ARCHITECTURE` (file structure, class diagram) + file chuyên môn (SEO, WORKFLOW, v.v.) + `OVERVIEW` (nếu feature mới) + `ROADMAP` (deliverable).

---

## Bước 3: Đọc File Docs Hiện Tại

// turbo
Đọc **TẤT CẢ** các file docs đã xác định ở Bước 2 để hiểu cấu trúc hiện tại trước khi chỉnh sửa.

```
Đọc file: docs/plugin-ai-content/<FILE_NAME>.md
```

---

## Bước 4: Cập Nhật Từng File Docs

Áp dụng các quy tắc sau khi cập nhật:

### 4.1 — Quy tắc chung

- **Giữ nguyên format** hiện tại của mỗi file (heading levels, table structure, code blocks)
- **Cập nhật ngày** `Cập nhật:` ở đầu file thành ngày hôm nay
- **Cập nhật version** nếu là thay đổi lớn (thêm feature mới)
- **Không xóa nội dung cũ** trừ khi feature bị loại bỏ hoàn toàn
- **Thêm marker `[P1]`, `[P2]`, `[P3]`** cho features chưa implement (nếu có)
- **Xóa marker `[P1]`, `[P2]`, `[P3]`** khi feature đã implement xong

### 4.2 — Theo loại tài liệu

#### PLUGIN_AI_OVERVIEW.md
- Cập nhật **Feature Map** (bảng feature): thêm/sửa dòng, cập nhật file tham chiếu
- Cập nhật **Tech Stack** nếu có đổi công nghệ
- Cập nhật **Tài Liệu Trong Folder Này** nếu thêm/xóa file docs

#### PLUGIN_AI_ARCHITECTURE.md
- Cập nhật **File Structure** tree: thêm/xóa file, cập nhật comment
- Cập nhật **Class Diagram**: thêm class + methods mới
- Cập nhật **Data Flow**: nếu flow thay đổi
- Cập nhật **Database** section: thêm option, meta key, table column
- Cập nhật **Hook System**: thêm action/filter mới

#### PLUGIN_AI_ROADMAP.md
- Tick `[x]` deliverables đã hoàn thành
- Cập nhật sprint table nếu feature đổi scope
- Cập nhật Go/No-Go Checklist nếu tiêu chí đã đạt

#### Các file chuyên môn khác
- Thêm section mới cho feature mới (đánh số `§` tiếp theo)
- Cập nhật section hiện tại nếu feature thay đổi
- Thêm code examples nếu có API/hook mới
- Cập nhật bảng reference/config nếu có parameter mới

---

## Bước 5: Cross-Reference Check

Sau khi cập nhật xong, kiểm tra:

- [ ] Tất cả tên file code trong docs **khớp** với file thật trong plugin
- [ ] Feature numbers trong `OVERVIEW` **khớp** với `ROADMAP`
- [ ] Class names trong `ARCHITECTURE` **khớp** với file code thực tế
- [ ] Hook names trong `ARCHITECTURE` **khớp** với code PHP
- [ ] Tất cả `§` references giữa các docs **vẫn đúng** (VD: `PLUGIN_AI_SEO.md §3`)
- [ ] Không có thông tin **mâu thuẫn** giữa các file docs

---

## Bước 6: Commit Message Template

Khi commit cập nhật docs, dùng format:

```
docs(plugin-ai): update [FILE_NAMES] for [FEATURE_NAME]

- Added: [mô tả ngắn những gì thêm mới]
- Updated: [mô tả ngắn những gì cập nhật]
- Removed: [mô tả ngắn những gì xóa] (nếu có)
```

---

## Quick Reference — Feature → Doc Files

| Feature Category | Primary Doc | Secondary Docs |
|---|---|---|
| Angle mới/sửa | `ANGLES` | `OVERVIEW`, `ARCHITECTURE`, `PROMPTS` |
| SEO/Linking | `SEO` | `ARCHITECTURE`, `OVERVIEW` |
| API/Model | `API` | `ARCHITECTURE`, `SECURITY` |
| Admin UI/Settings | `ADMIN` | `ARCHITECTURE`, `OVERVIEW` |
| Security | `SECURITY` | `ARCHITECTURE` |
| Prompt/Voice | `PROMPTS`, `BRAND_VOICE` | `ANGLES` |
| Workflow (generate/batch) | `WORKFLOW` | `ARCHITECTURE`, `OVERVIEW` |
| Data Integrity/Sources | `DATA_INTEGRITY` | `ARCHITECTURE`, `OVERVIEW` |
| Schema/Structured Data | `SEO` | `ARCHITECTURE` |
| Database change | `ARCHITECTURE` | File chuyên môn liên quan |
| Keyword Clusters/Management | `ANGLES`, `ADMIN` | `ARCHITECTURE`, `OVERVIEW` |
| Feature hoàn thành | `ROADMAP` | `OVERVIEW` |

---

## Tài Liệu Tham Chiếu

| File | Nội dung |
|---|---|
| `docs/plugin-ai-content/PLUGIN_AI_OVERVIEW.md` | Feature Map, Tech Stack, Doc index |
| `docs/plugin-ai-content/PLUGIN_AI_ARCHITECTURE.md` | File structure, Class diagram, Data flow, DB schema, Hooks |
| `docs/plugin-ai-content/PLUGIN_AI_ROADMAP.md` | Sprint plan, Deliverables, Go/No-Go criteria |
