# REF_WORKFLOW — Quy Trình Chuẩn

> **Dự án:** Website XANH - Design & Build
> **Ngày tạo:** 2026-03-12

---

## 1. Quy Trình Đăng Bài Blog

```
1. Soạn bài (Classic Editor)
   ├── Chọn Category (1 trong 4 danh mục)
   ├── Viết theo tone GOV_BRAND_VOICE.md
   ├── Chèn internal links (→ Portfolio, Dự Toán)
   └── Thêm Featured Image (WebP, min 800px)
2. SEO check
   ├── Title tag (< 60 ký tự)
   ├── Meta description (< 160 ký tự)
   └── Alt text cho tất cả ảnh
3. Preview → Review
4. Publish
5. Share social (FB, Zalo)
```

---

## 2. Quy Trình Thêm Dự Án (Portfolio)

```
1. Tạo post mới (CPT: xanh_project)
   ├── Title: "Loại hình - Tên KH (Khu vực)"
   ├── Featured Image: Ảnh thực tế đẹp nhất
   ├── Content: Có thể để trống (dùng ACF)
   └── Taxonomies: project_type + project_status
2. Fill ACF fields
   ├── Stats: location, area, floors, duration, budget
   ├── Story: client_story + solution
   ├── Media: before_image + after_image
   ├── Gallery: project_gallery (min 6 ảnh)
   └── Materials: project_materials (repeater)
3. Link Testimonial (nếu có)
4. Preview → Check Before/After slider
5. Publish
```

---

## 3. Quy Trình Xử Lý Lead

```
Lead đến (form submission)
  │
  ├── Email admin tự động (Fluent Form)
  │
  ├── Email user xác nhận (autoresponder)
  │
  └── Admin review trong 4h
      ├── Qualified → Assign KTS → Liên hệ trong 24h
      └── Not qualified → Tag + Archive
```

---

## 4. Quy Trình Update Nội Dung Trang

```
1. Xác định trang cần update
2. Đọc PAGE_*.md tương ứng
3. Edit ACF fields (nếu dynamic content)
   hoặc Edit template (nếu structural change)
4. Clear LiteSpeed Cache
5. Test responsive
6. Verify PageSpeed không giảm
```

---

## 5. Quy Trình Update Design Tokens

```
1. Thay đổi trong variables.css
2. Cập nhật ARCH_DESIGN_TOKENS.md
3. Kiểm tra tất cả pages bị ảnh hưởng
4. Clear cache
5. Test responsive
```

---

## Tài Liệu Liên Quan

- `GOV_BRAND_VOICE.md` — Blog writing rules
- `CORE_DATA_MODEL.md` — ACF fields reference
- `FEATURE_LEAD_CAPTURE.md` — Lead workflow
