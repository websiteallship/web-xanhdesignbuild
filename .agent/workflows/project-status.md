---
description: Kiểm tra tiến độ dự án HTML→WP, xác định phase hiện tại, và liệt kê tasks cần hoàn thành. Dùng khi cần biết dự án đang ở đâu.
---

# Theo Dõi Tiến Độ Dự Án XANH

## Skills cần đọc trước
- `@wordpress-theme-development` — Template hierarchy, theme structure
- `@wordpress` — WordPress development workflow
- `@wordpress-router` — Phân loại project type

## Khi nào dùng workflow này?
- Bắt đầu session mới và cần biết dự án đang ở đâu
- Trước khi bắt tay vào task tiếp theo
- Khi user hỏi "tiến độ dự án thế nào?"

---

## Bước 1: Kiểm Tra Theme Directory

// turbo
```bash
# Kiểm tra theme đã tồn tại chưa
ls wp-content/themes/xanhdesignbuild/ 2>$null || echo "THEME NOT CREATED"
```

**Kết quả:**
- Nếu `THEME NOT CREATED` → Dự án đang ở **trước Phase A** (chưa bắt đầu)
- Nếu có folder → Tiếp bước 2

---

## Bước 2: Kiểm Tra Phase A — Foundation

Kiểm tra các file core có tồn tại và có nội dung chưa:

// turbo
```bash
# Kiểm tra core files
$theme = "wp-content/themes/xanhdesignbuild"
$files = @(
    "$theme/style.css",
    "$theme/functions.php",
    "$theme/index.php",
    "$theme/header.php",
    "$theme/footer.php",
    "$theme/inc/theme-setup.php",
    "$theme/inc/enqueue.php",
    "$theme/inc/cpt-registration.php",
    "$theme/package.json",
    "$theme/tailwind.config.js",
    "$theme/assets/css/variables.css",
    "$theme/assets/css/components.css",
    "$theme/assets/js/main.js"
)
foreach ($f in $files) {
    if (Test-Path $f) { Write-Host "✅ $f" } else { Write-Host "❌ $f" }
}
```

**Checklist Phase A:**
- [ ] `style.css` — Theme header (Name, Version, Text Domain)
- [ ] `functions.php` — Constants + require inc/*.php
- [ ] `index.php` — Fallback template
- [ ] `header.php` — Chuyển từ wireframe header HTML
- [ ] `footer.php` — Chuyển từ wireframe footer HTML
- [ ] `inc/theme-setup.php` — Theme supports, menus, image sizes
- [ ] `inc/enqueue.php` — Conditional CSS/JS loading
- [ ] `inc/cpt-registration.php` — 3 CPTs + taxonomies
- [ ] `package.json` + `tailwind.config.js` — Tailwind CLI build
- [ ] `assets/css/variables.css` — CSS custom properties from `_shared/base.css`
- [ ] `assets/css/components.css` — BEM components from `_shared/base.css`
- [ ] `assets/js/main.js` — Lenis, GSAP global from `shared/base.js`
- [ ] Theme có thể activate không lỗi

---

## Bước 3: Kiểm Tra Phase B — Page Templates

// turbo
```bash
$theme = "wp-content/themes/xanhdesignbuild"
$pages = @(
    @{name="Contact";    file="$theme/page-contact.php";             complexity="⭐"},
    @{name="About";      file="$theme/page-about.php";              complexity="⭐⭐"},
    @{name="Homepage";   file="$theme/front-page.php";              complexity="⭐⭐⭐"},
    @{name="Blog List";  file="$theme/archive.php";                 complexity="⭐⭐"},
    @{name="Blog Detail";file="$theme/single.php";                  complexity="⭐⭐"},
    @{name="Portfolio";  file="$theme/archive-xanh_project.php";    complexity="⭐⭐⭐"},
    @{name="Portfolio D"; file="$theme/single-xanh_project.php";    complexity="⭐⭐⭐"}
)
foreach ($p in $pages) {
    $status = if (Test-Path $p.file) { "✅" } else { "❌" }
    Write-Host "$status $($p.complexity) $($p.name) → $($p.file)"
}
```

**Checklist Phase B (thứ tự từ đơn giản → phức tạp):**

| # | Trang | Template | Complexity | Status |
|---|---|---|---|---|
| B1 | Contact | `page-contact.php` | ⭐ | ❌ |
| B2 | About | `page-about.php` | ⭐⭐ | ❌ |
| B3 | Homepage | `front-page.php` | ⭐⭐⭐ | ❌ |
| B4 | Blog List | `archive.php` | ⭐⭐ | ❌ |
| B5 | Blog Detail | `single.php` | ⭐⭐ | ❌ |
| B6 | Portfolio | `archive-xanh_project.php` | ⭐⭐⭐ | ❌ |
| B7 | Portfolio Detail | `single-xanh_project.php` | ⭐⭐⭐ | ❌ |

**Mỗi trang cần kiểm tra:**
- [ ] PHP template file tồn tại
- [ ] Template parts (`template-parts/sections/`) tách riêng
- [ ] CSS page-specific: `assets/css/pages/{page}.css`
- [ ] JS page-specific: `assets/js/pages/{page}.js`
- [ ] Enqueue conditional trong `inc/enqueue.php`
- [ ] Visual match với wireframe HTML gốc

---

## Bước 4: Kiểm Tra Phase C — Polish & Verify

**Checklist Phase C:**
- [ ] ACF: 7 field groups (~158 fields) đã register
- [ ] ACF: Hardcoded text → `get_field()` + `esc_html()`
- [ ] Security: ALL output escaped (`esc_html`, `esc_url`, `esc_attr`)
- [ ] Security: ACF fields null-checked
- [ ] Security: AJAX nonces + capability checks
- [ ] Performance: Tailwind CLI purge → minimal output.css
- [ ] Performance: Image lazy loading (`loading="lazy"`)
- [ ] Performance: `prefers-reduced-motion` support
- [ ] Performance: JS budget ≤ 80KB vendor + 12KB custom/page
- [ ] Responsive: 375px / 768px / 1024px / 1440px
- [ ] Console: 0 JS errors, 0 404 assets
- [ ] WP functions: Menu, Logo, Dynamic content hoạt động
- [ ] Plugin Theme Check: Pass

---

## Bước 5: Tạo Báo Cáo Tiến Độ

Sau khi kiểm tra, tổng hợp báo cáo dạng:

```markdown
# 📊 Báo Cáo Tiến Độ — XANH Design & Build
**Ngày:** [DATE]

## Phase hiện tại: [A / B / C / Done]

### ✅ Hoàn thành
- [Liệt kê items đã xong]

### 🔄 Đang thực hiện
- [Liệt kê items đang làm]

### ❌ Chưa bắt đầu
- [Liệt kê items còn lại]

### ⚠️ Blockers / Issues
- [Liệt kê vấn đề cần giải quyết]

### 📋 Task tiếp theo
1. [Task ưu tiên cao nhất]
2. [Task ưu tiên thứ 2]
```

---

## Bước 6: Chạy Workflow Kiểm Tra Chất Lượng

Tùy Phase hiện tại, gợi ý chạy thêm:

| Phase | Workflow cần chạy |
|---|---|
| Sau Phase A | Activate theme + check console errors |
| Sau mỗi trang Phase B | `/fix-bug` → `/code-optimization` |
| Sau Phase C | `/seo-onpage-checklist` → `/performance-optimization` |
| Pre-launch | Tất cả workflows trên + security audit |

---

## Tài Liệu Tham Chiếu

| File | Nội dung |
|---|---|
| `docs/implement/plan.md` | Sprint plan gốc |
| `docs/implement/CONVERT_HTML_TO_WP.md` | Lộ trình chuyển đổi chi tiết |
| `.agent/rules/00-project-core.md` | Core rules |
| `.agent/rules/12-html-to-wp-conversion.md` | Quy tắc chuyển đổi HTML→WP |
| `.agent/workflows/html-to-wp-php.md` | Workflow chuyển đổi 10 bước |
