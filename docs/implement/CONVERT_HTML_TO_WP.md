# Chuyển Đổi HTML Wireframes → WordPress Theme

> **Theme:** `xanhdesignbuild`
> **Ngày tạo:** 2026-03-18
> **Tham chiếu:** [HTML_TO_WP_ROADMAP.md](./HTML_TO_WP_ROADMAP.md) | [ACF_FIELD_GROUPS.md](./ACF_FIELD_GROUPS.md) | [THEME_CONVENTIONS.md](./THEME_CONVENTIONS.md)

---

## 1. Trạng Thái Hiện Tại

| Thành phần | Trạng thái |
|---|---|
| Wireframe HTML/CSS/JS | ✅ 7 trang hoàn thành |
| Shared `_shared/base.css` (11.5KB) + `shared/base.js` (14.5KB) | ✅ |
| Docs & Rules (40+ files) | ✅ |
| WordPress Core (Local Sites) | ✅ |
| **Plugins đã cài** | ✅ ACF Pro, Classic Editor, Classic Widgets, Fluent Form, Fluent Form Pro |
| Plugins production (Smush, RankMath...) | ⏳ Cài khi lên production |
| Theme `xanhdesignbuild` | ❌ Chưa tồn tại |

### Quyết Định Đã Xác Nhận

- **Theme slug:** `xanhdesignbuild`
- **Tailwind CSS:** CLI build (`npx @tailwindcss/cli`) → output purged CSS
- **Ảnh:** WP Media Library (không copy ảnh vào theme)
- **Content:** Quản lý qua ACF Pro (xem `ACF_FIELD_GROUPS.md`)
- **Prefix:** `xanh_` (functions), `xanh-` (slugs)

---

## 2. Wireframe → WP Mapping

### 2.1 Trang & Template Files

| # | Trang | Wireframe Source | WP Template | Độ phức tạp |
|---|---|---|---|---|
| 1 | Homepage | `homepage_02/home-page.html` | `front-page.php` | ⭐⭐⭐ |
| 2 | About | `about/about.html` | `page-about.php` | ⭐⭐ |
| 3 | Contact | `contact/contact.html` | `page-contact.php` | ⭐ |
| 4 | Blog List | `blog/blog.html` | `archive.php` | ⭐⭐ |
| 5 | Blog Detail | `blog/blog-detail.html` | `single.php` | ⭐⭐ |
| 6 | Portfolio List | `portfolio/portfolio.html` | `archive-xanh_project.php` | ⭐⭐⭐ |
| 7 | Portfolio Detail | `portfolio/portfolio-detail.html` | `single-xanh_project.php` | ⭐⭐⭐ |

### 2.2 Element Mapping (HTML → WP PHP)

| HTML Element | WordPress PHP |
|---|---|
| `<head>` meta, title | `wp_head()` + `add_theme_support('title-tag')` |
| `<link stylesheet>` | `wp_enqueue_style()` trong `inc/enqueue.php` |
| `<script src>` | `wp_enqueue_script()` trong `inc/enqueue.php` |
| `<script>` inline (Tailwind config) | Xóa — dùng Tailwind CLI build |
| Navigation HTML | `wp_nav_menu()` |
| Header chung | `header.php` → `get_header()` |
| Footer chung | `footer.php` → `get_footer()` |
| Main content | Page templates + `template-parts/` |
| `src="image/..."` | `wp_get_attachment_image()` (ACF Image field) |
| `href="#"` / link tĩnh | `esc_url(home_url('/...'))` |
| Blog cards tĩnh | WP Loop + `get_template_part()` |
| Form liên hệ | Fluent Form shortcode |
| Text tĩnh (headings, paragraphs) | ACF `get_field()` |

---

## 3. Lộ Trình 3 Phase

### Phase A — Theme Foundation (Bước 1-5)

Tạo skeleton hoạt động với header/footer/enqueue đúng.

**Thứ tự thực hiện:**

1. Scaffold folder structure `wp-content/themes/xanhdesignbuild/`
2. `style.css` (theme header) + `functions.php` (constants, requires)
3. `inc/theme-setup.php` (theme support, menus)
4. `inc/enqueue.php` (CSS/JS conditional loading)
5. `header.php` (từ wireframe header HTML)
6. `footer.php` (từ wireframe footer HTML)
7. `index.php` (fallback) + `front-page.php` (placeholder)
8. `tailwind.config.js` + `package.json` + `input.css`
9. Copy `base.css` tokens → `variables.css` + `components.css`
10. Copy `base.js` → `assets/js/main.js`
11. Copy logo SVGs → `assets/images/`
12. Activate theme → verify

**Kết quả mong đợi:** Header/footer hiển thị đúng, không lỗi console.

---

### Phase B — Page Templates (Bước 6-7)

Chuyển từng trang HTML → PHP, thứ tự ưu tiên:

1. **Contact** (đơn giản nhất, ít sections)
2. **About** (nhiều sections, ACF Repeater)
3. **Homepage** (phức tạp nhất, 10+ sections)
4. **Blog List** (WP Loop)
5. **Blog Detail** (WP Loop)
6. **Portfolio List** (CPT + AJAX filter)
7. **Portfolio Detail** (ACF + Gallery)

**Chuyển đổi cho mỗi section:**
- [ ] Ảnh: `src="image/..."` → `wp_get_attachment_image()` từ ACF
- [ ] Text: hardcoded → `get_field('field_name')`
- [ ] Links: href tĩnh → `esc_url(home_url('/...'))`
- [ ] Giữ nguyên: Tailwind classes, GSAP selectors, ARIA attributes

---

### Phase C — Polish & Verify (Bước 8-10)

| # | Bước | Nội dung |
|---|---|---|
| 8 | Theme Support | `add_theme_support()`, `add_image_size()`, register menus |
| 9 | Security | Escape outputs, sanitize inputs, nonces, capability checks |
| 10 | Verification | Visual diff HTML↔PHP, responsive, console errors |

---

## 4. Shared Assets Migration

### CSS

| Wireframe | Theme | Ghi chú |
|---|---|---|
| `_shared/base.css` :root tokens | `assets/css/variables.css` | Chỉ giữ CSS custom properties |
| `_shared/base.css` components | `assets/css/components.css` | Buttons, header, animations |
| Tailwind CDN inline config | `tailwind.config.js` | CLI build thay CDN |
| Page-specific CSS (home-page.css...) | `assets/css/pages/home.css` | Conditional enqueue |

### JS

| Wireframe | Theme | Ghi chú |
|---|---|---|
| `shared/base.js` | `assets/js/main.js` | Lenis, GSAP global, header scroll |
| Page-specific JS | `assets/js/pages/home.js` | Conditional enqueue |
| Vendor CDN links | `wp_enqueue_script()` CDN | Giữ CDN, không copy local |

---

## 5. Verification Checklist

- [ ] Console: không JS errors, không 404 assets
- [ ] Visual: Header/footer render y hệt wireframe
- [ ] Responsive: 375px / 768px / 1024px / 1440px
- [ ] WP Functions: Menu hoạt động, Logo hiển thị
- [ ] ACF: Thay đổi content trong admin → frontend cập nhật
- [ ] GSAP animations: Hoạt động mượt mà
- [ ] Swiper sliders: Touch-enabled trên mobile
- [ ] Fluent Form: Form liên hệ submit thành công
- [ ] Plugin Theme Check: Pass

---

## Tài Liệu Liên Quan

| File | Mô tả |
|---|---|
| [HTML_TO_WP_ROADMAP.md](./HTML_TO_WP_ROADMAP.md) | Implementation plan gốc (Sprint 1+2) |
| [ACF_FIELD_GROUPS.md](./ACF_FIELD_GROUPS.md) | Chi tiết ACF fields cho tất cả trang |
| [THEME_CONVENTIONS.md](./THEME_CONVENTIONS.md) | Quy ước code và cấu trúc theme |
| [../CORE_ARCHITECTURE.md](../CORE_ARCHITECTURE.md) | Kiến trúc tổng thể |
| [../CORE_DATA_MODEL.md](../CORE_DATA_MODEL.md) | CPTs, ACF fields, taxonomies |
