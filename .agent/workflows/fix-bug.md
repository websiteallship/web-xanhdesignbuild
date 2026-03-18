---
description: Quy trình fix bug có hệ thống cho WordPress theme. Dùng khi gặp lỗi PHP, JS console errors, layout sai, CSS không load, hoặc bất kỳ vấn đề nào.
---

# Quy Trình Fix Bug — XANH Theme

## Skills cần đọc trước
- `@systematic-debugging` — Phương pháp debug có hệ thống (BẮT BUỘC đọc trước)
- `@debugger` — Debugging specialist
- `@bug-hunter` — Trace từ triệu chứng → root cause

## Rules liên quan
- `16-error-prevention.md` — PHP null safety, JS DOM guards, zero errors policy
- `05-security.md` — Escape rules (vì thiếu escape có thể gây lỗi hiển thị)
- `10-js-optimization.md` — JS dependency, init patterns

---

## Bước 1: Thu Thập Triệu Chứng

Trước khi debug, trả lời **5W**:

| Câu hỏi | Ghi chép |
|---|---|
| **What?** | Lỗi gì? (error message, screenshot, mô tả) |
| **Where?** | Trang nào? Section nào? File nào? |
| **When?** | Lúc nào xảy ra? (load, hover, scroll, click, resize?) |
| **Who?** | Ảnh hưởng ai? (all users, mobile only, specific browser?) |
| **Why?** | Có thay đổi gì gần đây? (code change, plugin update?) |

---

## Bước 2: Kiểm Tra Error Logs

### PHP Errors
// turbo
```bash
# Kiểm tra WP debug log
if (Test-Path "wp-content/debug.log") {
    Get-Content "wp-content/debug.log" -Tail 50
} else {
    Write-Host "⚠️ debug.log not found — check WP_DEBUG in wp-config.php"
}
```

### Kiểm tra WP_DEBUG đã bật chưa
// turbo
```bash
Select-String -Path "wp-config.php" -Pattern "WP_DEBUG" | Select-Object -ExpandProperty Line
```

> **Nếu WP_DEBUG chưa bật**, thêm vào `wp-config.php`:
> ```php
> define('WP_DEBUG', true);
> define('WP_DEBUG_LOG', true);
> define('WP_DEBUG_DISPLAY', false);
> ```

### JS Console Errors
- Mở DevTools (F12) → Console tab
- Lọc: Errors only
- Ghi lại: error message + file name + line number

---

## Bước 3: Phân Loại Bug

| Loại Bug | Triệu chứng | Bắt đầu từ |
|---|---|---|
| **PHP Fatal** | Trang trắng, 500 error | `debug.log` → file + line |
| **PHP Warning** | Trang load nhưng có cảnh báo | `debug.log` → null check |
| **JS Error** | Feature không hoạt động | Console → dependency order |
| **404 Asset** | CSS/JS/image không load | Network tab → path check |
| **Layout Broken** | Hiển thị sai | DevTools Elements → CSS inspect |
| **Animation Jank** | Animation giật | Performance tab → compositor |
| **Responsive** | Mobile hiển thị sai | Toggle device toolbar → breakpoints |

---

## Bước 4: Debug Theo Loại

### 4.1 — PHP Errors

```php
// Checklist fix PHP common errors:

// ❌ Fatal: Undefined function
// → Kiểm tra file đã require_once trong functions.php chưa

// ❌ Fatal: Cannot access array on null
// → Thiếu null-check ACF field (rule 16-error-prevention.md)
$value = get_field('field_name');
if ($value) { /* use $value */ }

// ❌ Warning: Undefined variable
// → Khởi tạo biến trước khi dùng
$items = get_field('repeater') ?: [];

// ❌ Fatal: wp_reset_postdata() not called
// → Luôn gọi sau WP_Query custom
```

### 4.2 — JS Errors

```javascript
// ❌ "gsap is not defined"
// → Thiếu dependency trong wp_enqueue_script
// Fix: Thêm 'gsap' vào deps array: ['gsap', 'gsap-st']

// ❌ "Cannot read properties of null"
// → Element chưa tồn tại trong DOM
// Fix: Guard clause
const el = document.querySelector('.target');
if (!el) return;

// ❌ "Swiper is not defined" (nhưng chỉ ở 1 trang)
// → Swiper chưa được enqueue conditional
// Fix: Kiểm tra is_front_page() || is_singular('xanh_project')
```

### 4.3 — 404 Assets

```
// Checklist đường dẫn:
// ✅ Đúng: XANH_THEME_URI . '/assets/css/pages/home.css'
// ❌ Sai: '/assets/css/pages/home.css' (thiếu theme URI)
// ❌ Sai: 'assets/css/pages/home.css' (relative path)
// ❌ Sai: hardcoded 'http://xanhdesignbuild.local/...'
```

### 4.4 — CSS/Layout Issues

```css
/* Checklist CSS debug:
 * 1. Inspect affected element → Check computed styles
 * 2. Check specificity conflicts: class vs class
 * 3. Check media query overlap (min-width vs max-width)
 * 4. Check Tailwind output.css đã rebuild chưa: npm run build
 * 5. Check CSS file đã enqueue đúng page chưa
 * 6. Check z-index (dùng tokens, không hardcode)
 */
```

---

## Bước 5: Fix & Verify

1. **Tạo fix** — áp dụng rules liên quan:
   - PHP: `16-error-prevention.md` (null-check, escape, wp_reset_postdata)
   - JS: `10-js-optimization.md` (guard clause, event delegation)
   - CSS: `09-css-optimization.md` (selector, specificity)

2. **Test fix:**
   - [ ] Lỗi ban đầu đã biến mất
   - [ ] Không tạo lỗi mới (regression)
   - [ ] Console: 0 errors
   - [ ] Responsive: 375px, 768px, 1024px, 1440px

3. **Document:**
   - Ghi lại: nguyên nhân + fix vào commit message
   - Nếu lỗi phổ biến → cập nhật rules

---

## Bước 6: Checklist Zero Errors (rule 16)

Chạy kiểm tra toàn bộ sau khi fix:

- [ ] PHP: `debug.log` trống (no new warnings/errors)
- [ ] JS Console: 0 errors, 0 warnings
- [ ] Network: 0 failed requests (404, 500)
- [ ] No `Mixed Content` warnings
- [ ] No `CORS` errors cho CDN resources
- [ ] No deprecated function warnings
- [ ] GSAP animations chạy smooth (no jank)
- [ ] Swiper/GLightbox hoạt động đúng trang

---

## Bảng Tra Cứu Nhanh — Lỗi Thường Gặp

| Triệu chứng | Nguyên nhân phổ biến | Fix |
|---|---|---|
| Trang trắng | PHP Fatal Error | Check `debug.log` |
| CSS không load | Sai đường dẫn `XANH_THEME_URI` | Fix path trong `enqueue.php` |
| `gsap is not defined` | Thiếu dependency array | Thêm `'gsap'` vào deps |
| Ảnh 404 | Chưa copy/upload ảnh | Upload qua Media Library |
| Menu không hiện | Chưa register menu location | `register_nav_menus()` |
| `wp_head()` thiếu | Thiếu trong `header.php` | Thêm trước `</head>` |
| ACF `null` error | Thiếu null-check | `$val = get_field('x') ?: ''` |
| Swiper không chạy | CDN chưa conditional load | Check `is_front_page()` |
| GSAP animation không chạy | ScrollTrigger chưa register | `gsap.registerPlugin(ScrollTrigger)` |
| Layout vỡ mobile | Thiếu responsive CSS | Check breakpoints `min-width: 768px` |

---

## Tài Liệu Tham Chiếu

| File | Nội dung |
|---|---|
| `.agent/rules/16-error-prevention.md` | PHP/JS/CSS error prevention patterns |
| `.agent/rules/05-security.md` | Escape + sanitize rules |
| `.agent/rules/10-js-optimization.md` | JS architecture + dependency |
| `.agent/rules/09-css-optimization.md` | CSS selector + render perf |
| `.agent/skills/23_systematic-debugging/SKILL.md` | Phương pháp debug có hệ thống |
| `.agent/skills/27_bug-hunter/SKILL.md` | Trace root cause |
