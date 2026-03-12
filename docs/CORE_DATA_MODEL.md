# CORE_DATA_MODEL — Mô Hình Dữ Liệu

> **Dự án:** Website XANH - Design & Build
> **Phiên bản:** 1.0 | **Ngày tạo:** 2026-03-12
> **Plugin chính:** ACF Pro, Classic Editor

---

## 1. Custom Post Types (CPTs)

### 1.1 `xanh_project` — Dự Án / Portfolio

| Thuộc tính | Giá trị |
|---|---|
| **Slug** | `du-an` |
| **Supports** | `title`, `editor`, `thumbnail`, `excerpt` |
| **Has Archive** | `true` |
| **Menu Icon** | `dashicons-portfolio` |
| **Public** | `true` |

**Taxonomies:**

| Taxonomy | Slug | Terms mẫu |
|---|---|---|
| `project_type` | `loai-hinh` | Biệt thự, Nhà phố, Căn hộ, Nghỉ dưỡng |
| `project_status` | `trang-thai` | Đã bàn giao, Đang thi công, Concept 3D |

### 1.2 `xanh_testimonial` — Lời Chứng Thực

| Thuộc tính | Giá trị |
|---|---|
| **Slug** | `chung-thuc` |
| **Supports** | `title`, `editor`, `thumbnail` |
| **Public** | `false` (chỉ hiển thị qua template) |
| **Menu Icon** | `dashicons-format-quote` |

### 1.3 `xanh_team` — Thành Viên Đội Ngũ

| Thuộc tính | Giá trị |
|---|---|
| **Slug** | `doi-ngu` |
| **Supports** | `title`, `thumbnail` |
| **Public** | `false` |
| **Menu Icon** | `dashicons-groups` |

---

## 2. ACF Field Groups

### 2.1 Project Details (`group_project_details`)

> Gắn vào CPT: `xanh_project`

| Field Name | Field Type | Label | Ghi chú |
|---|---|---|---|
| `project_location` | Text | Vị trí | VD: "Nha Trang, Khánh Hòa" |
| `project_area` | Number | Diện tích (m²) | |
| `project_floors` | Number | Số tầng | |
| `project_duration` | Text | Thời gian thi công | VD: "120 ngày" |
| `project_budget` | Text | Ngân sách | VD: "2.5 Tỷ VNĐ" |
| `project_3d_match` | Text | % sát 3D | VD: "98%" |
| `project_cost_overrun` | Text | % phát sinh | VD: "0%" |
| `project_client_story` | Textarea | Bài toán gia chủ | Câu chuyện dự án |
| `project_solution` | Textarea | Giải pháp Xanh | Lời giải |
| `project_before_image` | Image | Ảnh 3D (Before) | Cho Before/After Slider |
| `project_after_image` | Image | Ảnh thực tế (After) | Cho Before/After Slider |
| `project_gallery` | Gallery | Thư viện ảnh | Lightbox gallery |
| `project_materials` | Repeater | Vật liệu sử dụng | Sub-fields bên dưới |
| ↳ `material_image` | Image | Ảnh vật liệu | |
| ↳ `material_name` | Text | Tên vật liệu | |
| ↳ `material_why_green` | Textarea | Vì sao "Xanh"? | Tooltip content |
| `project_video_url` | URL | Video dự án | YouTube/Vimeo URL |

### 2.2 Testimonial Details (`group_testimonial_details`)

> Gắn vào CPT: `xanh_testimonial`

| Field Name | Field Type | Label |
|---|---|---|
| `testimonial_name` | Text | Tên khách hàng |
| `testimonial_location` | Text | Địa điểm |
| `testimonial_quote` | Textarea | Trích dẫn |
| `testimonial_photo` | Image | Ảnh chân dung |
| `testimonial_project` | Post Object | Dự án liên quan |
| `testimonial_video_url` | URL | Video phỏng vấn |

### 2.3 Team Member Details (`group_team_details`)

> Gắn vào CPT: `xanh_team`

| Field Name | Field Type | Label |
|---|---|---|
| `team_position` | Text | Chức danh |
| `team_bio` | Textarea | Tiểu sử ngắn |
| `team_facebook` | URL | Facebook |
| `team_zalo` | URL | Zalo |
| `team_order` | Number | Thứ tự hiển thị |

### 2.4 Homepage Sections (`group_homepage`)

> Gắn vào Page: Trang Chủ (front-page)

| Field Name | Field Type | Label |
|---|---|---|
| `hero_video_url` | URL | Video Hero |
| `hero_background` | Image | Ảnh nền Hero (fallback) |
| `featured_projects` | Relationship | Dự án tiêu biểu |
| `counter_projects` | Number | Số công trình |
| `counter_years` | Number | Số năm kinh nghiệm |
| `partner_logos` | Gallery | Logo đối tác |

### 2.5 Estimator Config (`group_estimator`)

> Gắn vào Options Page

| Field Name | Field Type | Label |
|---|---|---|
| `price_per_sqm_basic` | Number | Đơn giá gói Cơ bản (VNĐ/m²) |
| `price_per_sqm_standard` | Number | Đơn giá gói Tiêu chuẩn |
| `price_per_sqm_premium` | Number | Đơn giá gói Cao cấp |
| `estimator_disclaimer` | Textarea | Ghi chú pháp lý |

---

## 3. WordPress Options (wp_options)

| Option Key | Mô tả | Plugin |
|---|---|---|
| `xanh_hotline` | Số hotline kỹ thuật | ACF Options |
| `xanh_email` | Email công ty | ACF Options |
| `xanh_address` | Địa chỉ trụ sở | ACF Options |
| `xanh_working_hours` | Giờ làm việc | ACF Options |
| `xanh_tax_code` | Mã số thuế | ACF Options |
| `xanh_zalo_oa` | Zalo OA URL | ACF Options |
| `xanh_facebook` | Facebook URL | ACF Options |
| `xanh_google_maps_embed` | Google Maps iframe | ACF Options |

---

## 4. Blog Categories (taxonomy: `category`)

| Category | Slug | Mô tả |
|---|---|---|
| Kinh Nghiệm Xây Nhà | `kinh-nghiem` | Đọc bản vẽ, pháp lý, tránh phát sinh |
| Vật Liệu Xanh | `vat-lieu` | Review vật liệu bền, cách âm, cách nhiệt |
| Xu Hướng & Không Gian | `xu-huong` | Công năng, ánh sáng, phong thủy |
| Nhật Ký Xanh | `nhat-ky` | Tiến độ dự án, chuyện nghề |

---

## 5. Sơ Đồ Quan Hệ

```
xanh_project ──1:N──► project_gallery (ACF Gallery)
     │
     ├──1:N──► project_materials (ACF Repeater)
     │
     ├──N:M──► project_type (Taxonomy)
     │
     ├──N:M──► project_status (Taxonomy)
     │
     └──1:1◄── xanh_testimonial.testimonial_project (Post Object)

xanh_team ── Standalone (hiển thị qua template)

Posts (Blog) ──N:M──► category
```

---

## Tài Liệu Liên Quan

- `CORE_ARCHITECTURE.md` — Theme structure, data flow layer
- `PLUGIN_ECOSYSTEM.md` — ACF Pro config
- `PAGE_PORTFOLIO.md` — Portfolio page specs
- `FEATURE_ESTIMATOR.md` — Estimator ACF fields

---

## 6. WP_Query Patterns (Dev Reference)

### Portfolio Filter (AJAX)
```php
function xanh_get_filtered_projects($args = []) {
    $defaults = [
        'post_type'      => 'xanh_project',
        'posts_per_page' => 9,
        'paged'          => 1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];
    $args = wp_parse_args($args, $defaults);

    // Tax query cho filter
    $tax_query = [];
    if (!empty($args['project_type']) && $args['project_type'] !== 'all') {
        $tax_query[] = [
            'taxonomy' => 'project_type',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($args['project_type']),
        ];
    }
    if (!empty($args['project_status']) && $args['project_status'] !== 'all') {
        $tax_query[] = [
            'taxonomy' => 'project_status',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($args['project_status']),
        ];
    }
    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
    }

    return new WP_Query($args);
}
```

### Related Projects (cùng project_type)
```php
function xanh_get_related_projects($post_id, $count = 3) {
    $types = wp_get_post_terms($post_id, 'project_type', ['fields' => 'slugs']);

    return new WP_Query([
        'post_type'      => 'xanh_project',
        'posts_per_page' => $count,
        'post__not_in'   => [$post_id],
        'tax_query'      => [
            [
                'taxonomy' => 'project_type',
                'field'    => 'slug',
                'terms'    => $types,
            ],
        ],
    ]);
}
```

### Blog Posts by Category
```php
function xanh_get_blog_posts($category_slug = '', $paged = 1) {
    $args = [
        'post_type'      => 'post',
        'posts_per_page' => 9,
        'paged'          => $paged,
    ];
    if ($category_slug) {
        $args['category_name'] = sanitize_text_field($category_slug);
    }
    return new WP_Query($args);
}
```

### Featured Projects (Homepage)
```php
function xanh_get_homepage_featured() {
    $ids = get_field('featured_projects', get_option('page_on_front'));
    if (empty($ids)) return new WP_Query();

    return new WP_Query([
        'post_type'      => 'xanh_project',
        'post__in'       => wp_list_pluck($ids, 'ID'),
        'orderby'        => 'post__in',
        'posts_per_page' => count($ids),
    ]);
}
```

### Team Members (ordered)
```php
function xanh_get_team_members() {
    return new WP_Query([
        'post_type'      => 'xanh_team',
        'posts_per_page' => -1,
        'meta_key'       => 'team_order',
        'orderby'        => 'meta_value_num',
        'order'          => 'ASC',
    ]);
}
```

### Testimonial by Project
```php
function xanh_get_testimonial_for_project($project_id) {
    return new WP_Query([
        'post_type'      => 'xanh_testimonial',
        'posts_per_page' => 1,
        'meta_query'     => [
            [
                'key'     => 'testimonial_project',
                'value'   => $project_id,
                'compare' => '=',
            ],
        ],
    ]);
}
```

### Search Autocomplete
```php
function xanh_search_posts($keyword, $limit = 5) {
    return new WP_Query([
        'post_type'      => 'post',
        's'              => sanitize_text_field($keyword),
        'posts_per_page' => $limit,
        'fields'         => 'ids',
    ]);
}
```

