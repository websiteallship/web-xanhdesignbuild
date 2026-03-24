# ACF Field Groups — Đặc Tả Chi Tiết

> **Plugin:** ACF Pro (đã cài)
> **Ngày tạo:** 2026-03-18
> **Tham chiếu:** [CORE_DATA_MODEL.md](../CORE_DATA_MODEL.md) | [CONVERT_HTML_TO_WP.md](./CONVERT_HTML_TO_WP.md)

---

## Chiến Lược 3 Tầng

```
ACF Options Page (Global)     ← Thông tin công ty: hotline, email, địa chỉ, social
     ↓
Per-Page Field Groups         ← Content từng section (Homepage, About, Contact)
     ↓
CPT Field Groups              ← Dữ liệu per-post: Project, Testimonial, Team
```

> **Blog / Blog Detail** dùng WP Core (title, content, excerpt, featured image) — không cần ACF.

---

## 1. ACF Options Page — `group_site_options`

> **Menu:** Cài Đặt XANH | **Dùng cho:** Header, Footer, Contact page

| Field Name | Type | Label | Dùng ở |
|---|---|---|---|
| `xanh_hotline` | Text | Hotline | Header CTA, Footer, Contact |
| `xanh_email` | Email | Email công ty | Footer, Contact |
| `xanh_address` | Textarea | Địa chỉ trụ sở | Footer, Contact |
| `xanh_working_hours` | Text | Giờ làm việc | Contact |
| `xanh_tax_code` | Text | Mã số thuế | Footer |
| `xanh_google_maps_embed` | Textarea | Google Maps iframe | Contact |

### Social Links (Group)

| Field Name | Type | Label |
|---|---|---|
| `xanh_facebook` | URL | Facebook |
| `xanh_instagram` | URL | Instagram |
| `xanh_youtube` | URL | YouTube |
| `xanh_zalo_oa` | URL | Zalo OA |

### Footer (Group)

| Field Name | Type | Label |
|---|---|---|
| `footer_brand_desc` | Textarea | Mô tả brand |
| `footer_badges` | Repeater | Badges/Chứng nhận |
| ↳ `badge_text` | Text | Văn bản badge |
| `footer_newsletter_label` | Text | Label newsletter |

### PHP Registration

```php
// inc/acf-fields.php
function xanh_register_options_pages() {
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page([
            'page_title' => 'Cài Đặt XANH',
            'menu_title' => 'Cài Đặt XANH',
            'menu_slug'  => 'xanh-settings',
            'capability' => 'edit_posts',
            'redirect'   => false,
            'icon_url'   => 'dashicons-admin-customizer',
            'position'   => 2,
        ]);
    }
}
add_action('acf/init', 'xanh_register_options_pages');
```

---

## 2. Homepage — `group_homepage`

> **Gắn vào:** Page có template `front-page.php` (Reading Settings → Static page)

### Section 1: Hero

| Field Name | Type | Label |
|---|---|---|
| `hero_headline` | Text | Tiêu đề chính |
| `hero_subheadline` | Textarea | Phụ đề |
| `hero_cta_text` | Text | Nút CTA |
| `hero_cta_link` | URL | Link CTA |
| `hero_slides` | Repeater | Ảnh slider |
| ↳ `slide_image` | Image | Ảnh slide |
| ↳ `slide_alt` | Text | Alt text |

### Section 2: Empathy (Nỗi Trăn Trở)

| Field Name | Type | Label |
|---|---|---|
| `empathy_eyebrow` | Text | Eyebrow |
| `empathy_title` | Text | Tiêu đề |
| `empathy_paragraphs` | Repeater | Đoạn văn |
| ↳ `paragraph_text` | Textarea | Nội dung |
| `empathy_quote` | Textarea | Blockquote |
| `empathy_image` | Image | Ảnh minh họa |

### Section 3: 4 Xanh (Core Values — Homepage)

| Field Name | Type | Label |
|---|---|---|
| `values_eyebrow` | Text | Eyebrow |
| `values_title` | Text | Tiêu đề |
| `values_tagline` | Text | Tagline trái |
| `values_items` | Repeater | 4 giá trị |
| ↳ `value_number` | Text | Số (01, 02...) |
| ↳ `value_icon_svg` | Textarea | SVG icon code |
| ↳ `value_title` | Text | Tên giá trị |
| ↳ `value_description` | Textarea | Mô tả |

### Section 4: Services (Lĩnh Vực)

| Field Name | Type | Label |
|---|---|---|
| `services_eyebrow` | Text | Eyebrow |
| `services_title` | Text | Tiêu đề |
| `services_subtitle` | Textarea | Phụ đề |
| `services_items` | Repeater | 4 dịch vụ |
| ↳ `service_image` | Image | Ảnh |
| ↳ `service_title` | Text | Tên |
| ↳ `service_description` | Textarea | Mô tả |
| ↳ `service_link` | URL | Link |

### Section CTA (Liên Hệ Tư Vấn)

| Field Name | Type | Label |
|---|---|---|
| `cta_eyebrow` | Text | Eyebrow |
| `cta_title` | Text | Tiêu đề |
| `cta_body` | Textarea | Nội dung |
| `cta_primary_text` | Text | Nút chính |
| `cta_primary_link` | URL | Link nút chính |
| `cta_secondary_text` | Text | Nút phụ |
| `cta_secondary_link` | URL | Link nút phụ |
| `cta_image` | Image | Ảnh bên phải |
| `cta_quote` | Text | Quote trên ảnh |
| `cta_counters` | Repeater | Badges số liệu |
| ↳ `counter_number` | Number | Số |
| ↳ `counter_suffix` | Text | Hậu tố (+, %) |
| ↳ `counter_label` | Text | Nhãn |

### Section: Featured Projects

| Field Name | Type | Label | Ghi chú |
|---|---|---|---|
| `featured_projects` | Relationship | Dự án tiêu biểu | Chọn từ CPT `xanh_project` |

> Before/after images, quote, meta → lấy từ CPT fields của từng project.

### Section: Process Steps (6 Bước)

| Field Name | Type | Label |
|---|---|---|
| `process_eyebrow` | Text | Eyebrow |
| `process_title` | Text | Tiêu đề |
| `process_subtitle` | Textarea | Phụ đề |
| `process_steps` | Repeater | 6 bước |
| ↳ `step_number` | Text | Số |
| ↳ `step_title` | Text | Tên bước |
| ↳ `step_description` | Textarea | Mô tả |
| ↳ `step_image` | Image | Ảnh nền |
| ↳ `step_cta_text` | Text | Text CTA |
| ↳ `step_cta_link` | URL | Link CTA |

### Section: CTA Contact (Full-Width Banner)

| Field Name | Type | Label |
|---|---|---|
| `cta_contact_eyebrow` | Text | Eyebrow |
| `cta_contact_title` | Text | Tiêu đề |
| `cta_contact_body` | Textarea | Nội dung |
| `cta_contact_btn_text` | Text | Nút CTA |
| `cta_contact_btn_link` | URL | Link |
| `cta_contact_bg_image` | Image | Ảnh nền |

### Section: Partners

| Field Name | Type | Label |
|---|---|---|
| `partners_overline` | Text | Overline |
| `partner_logos` | Gallery | Logo đối tác |

### Section: Blog (Latest Posts)

| Field Name | Type | Label | Ghi chú |
|---|---|---|---|
| `blog_eyebrow` | Text | Eyebrow | Bài viết lấy tự động WP_Query |
| `blog_title` | Text | Tiêu đề | |

---

## 3. About Page — `group_about_page`

> **Gắn vào:** Page slug `gioi-thieu`

### Section 1: Hero

| Field Name | Type | Label |
|---|---|---|
| `about_hero_eyebrow` | Text | Eyebrow |
| `about_hero_title` | Text | Tiêu đề |
| `about_hero_subtitle` | Textarea | Phụ đề |
| `about_hero_image` | Image | Ảnh nền |
| `about_hero_cta_text` | Text | Nút CTA |
| `about_hero_video_url` | URL | YouTube URL |

### Section 2: The Pain (5 Nỗi Đau)

| Field Name | Type | Label |
|---|---|---|
| `pain_eyebrow` | Text | Eyebrow |
| `pain_title` | Text | Tiêu đề |
| `pain_subtitle` | Textarea | Phụ đề |
| `pain_items` | Repeater | 5 nỗi đau |
| ↳ `pain_icon` | Text | Lucide icon name (VD: `image-off`) |
| ↳ `pain_title_item` | Text | Tiêu đề |
| ↳ `pain_quote` | Textarea | Quote |

### Section 3: Turning Point (Chuỗi Giá Trị Khép Kín)

| Field Name | Type | Label |
|---|---|---|
| `turning_eyebrow` | Text | Eyebrow |
| `turning_title` | Text | Tiêu đề |
| `turning_subtitle` | Textarea | Phụ đề |
| `turning_bg_image` | Image | Ảnh nền |
| `turning_nodes` | Repeater | 5 nodes |
| ↳ `node_title` | Text | Tên |
| ↳ `node_description` | Text | Mô tả |

### Section 4: The Promise (Sứ Mệnh)

| Field Name | Type | Label |
|---|---|---|
| `promise_eyebrow` | Text | Eyebrow |
| `promise_title` | Text | Tiêu đề |
| `promise_body` | Textarea | Nội dung chính |
| `promise_body_2` | Textarea | Nội dung phụ |
| `promise_highlights` | Repeater | 5 highlights |
| ↳ `highlight_strong` | Text | Phần in đậm |
| ↳ `highlight_text` | Text | Phần bình thường |
| `promise_cta_text` | Text | Nút CTA |
| `promise_cta_link` | URL | Link |

### Section 5: 4 Xanh Philosophy (Cards ảnh)

| Field Name | Type | Label |
|---|---|---|
| `philo_eyebrow` | Text | Eyebrow |
| `philo_title` | Text | Tiêu đề |
| `philo_subtitle` | Textarea | Phụ đề |
| `philo_cards` | Repeater | 4 cards |
| ↳ `philo_card_image` | Image | Ảnh nền |
| ↳ `philo_card_icon` | Text | Lucide icon name |
| ↳ `philo_card_title` | Text | Tiêu đề |
| ↳ `philo_card_desc` | Textarea | Mô tả |

### Section 6: Core Values (Bản Sắc Cốt Lõi)

| Field Name | Type | Label |
|---|---|---|
| `cv_eyebrow` | Text | Eyebrow |
| `cv_title` | Text | Tiêu đề |
| `cv_subtitle` | Textarea | Phụ đề |
| `cv_items` | Repeater | 4 cards |
| ↳ `cv_icon` | Text | Lucide icon name |
| ↳ `cv_item_title` | Text | Tiêu đề |
| ↳ `cv_item_desc` | Textarea | Mô tả |

### Section: Team (Header Only)

| Field Name | Type | Label | Ghi chú |
|---|---|---|---|
| `team_eyebrow` | Text | Eyebrow | Members → CPT `xanh_team` |
| `team_title` | Text | Tiêu đề | |
| `team_subtitle` | Textarea | Phụ đề | |

---

## 4. Contact Page — `group_contact_page`

> **Gắn vào:** Page slug `lien-he`

### Hero

| Field Name | Type | Label |
|---|---|---|
| `contact_hero_image` | Image | Ảnh nền hero |
| `contact_hero_title` | Text | Tiêu đề |
| `contact_hero_subtitle` | Textarea | Phụ đề |

### Contact Form

| Field Name | Type | Label | Ghi chú |
|---|---|---|---|
| `contact_form_eyebrow` | Text | Eyebrow | |
| `contact_form_title` | Text | Tiêu đề section | |
| `contact_form_subtitle` | Text | Phụ đề form | |
| `contact_form_shortcode` | Text | Fluent Form shortcode | VD: `[fluentform id="1"]` |

> Thông tin liên hệ (địa chỉ, phone, email, giờ) → lấy từ **ACF Options Page**.

### FAQ

| Field Name | Type | Label |
|---|---|---|
| `faq_eyebrow` | Text | Eyebrow |
| `faq_title` | Text | Tiêu đề |
| `faq_items` | Repeater | Câu hỏi |
| ↳ `faq_question` | Text | Câu hỏi |
| ↳ `faq_answer` | Textarea | Trả lời |

---

## 5. CPT Field Groups (Đã có trong CORE_DATA_MODEL.md)

| CPT | Field Group | Ghi chú |
|---|---|---|
| `xanh_project` | `group_project_details` | 13 fields + repeaters (before/after, gallery, materials) |
| `xanh_testimonial` | `group_testimonial_details` | 6 fields |
| `xanh_team` | `group_team_details` | 5 fields |

> Chi tiết: Xem `CORE_DATA_MODEL.md` §2.

---

## 6. Template Usage Patterns

```php
// === Options Page (global) ===
$hotline = get_field('xanh_hotline', 'option');
$email   = get_field('xanh_email', 'option');
$social  = get_field('xanh_facebook', 'option');

// === Per-page fields (current page) ===
$hero_title = get_field('hero_headline');
$hero_image = get_field('empathy_image');
// Image output:
if ($hero_image) {
    echo wp_get_attachment_image($hero_image['ID'], 'full', false, [
        'class' => 'w-full h-full object-cover',
    ]);
}

// === Repeater ===
$steps = get_field('process_steps');
if ($steps) {
    foreach ($steps as $i => $step) {
        $num   = esc_html($step['step_number']);
        $title = esc_html($step['step_title']);
        $desc  = esc_html($step['step_description']);
        $img   = $step['step_image'];
        // render...
    }
}

// === Relationship (Featured Projects) ===
$projects = get_field('featured_projects');
if ($projects) {
    foreach ($projects as $post) {
        setup_postdata($post);
        $before = get_field('project_before_image', $post->ID);
        $after  = get_field('project_after_image', $post->ID);
        // render before/after slider...
    }
    wp_reset_postdata();
}
```

---

## Tổng Kết

| Nhóm | Số Field Groups | Số Fields (ước tính) |
|---|---|---|
| Options Page | 1 | ~15 |
| Homepage | 1 | ~60 |
| About Page | 1 | ~45 |
| Contact Page | 1 | ~12 |
| Project CPT | 1 | ~15 |
| Testimonial CPT | 1 | ~6 |
| Team CPT | 1 | ~5 |
| **Tổng** | **7** | **~158** |
