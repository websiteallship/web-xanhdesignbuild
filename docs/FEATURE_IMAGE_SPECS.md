# FEATURE_IMAGE_SPECS — Quy Chuẩn Hình Ảnh

> **Dự án:** Website XANH - Design & Build
> **Ngày tạo:** 2026-03-12

---

## 1. Kích Thước & Crop Ratio

| Vị trí | Kích thước (px) | Aspect Ratio | Format |
|---|---|---|---|
| **Hero Banner** (full-width) | 1920 × 900 | 32:15 | WebP |
| **Project thumbnail** (grid card) | 800 × 600 | 4:3 | WebP |
| **Project featured** (detail hero) | 1600 × 900 | 16:9 | WebP |
| **Project panorama** (detail) | 1920 × 640 | 3:1 | WebP |
| **Before/After pair** | 1200 × 800 | 3:2 | WebP |
| **Material swatch** | 400 × 400 | 1:1 | WebP |
| **Team member avatar** | 600 × 600 | 1:1 | WebP |
| **Blog featured image** | 1200 × 630 | ~1.91:1 | WebP |
| **Blog inline image** | 800 × auto | Free | WebP |
| **Partner logo** | 300 × 120 | 5:2 | PNG (transparent) |
| **Favicon** | 512 × 512 | 1:1 | PNG |
| **OG Image (social)** | 1200 × 630 | 1.91:1 | JPEG |

---

## 2. Quy Chuẩn Chụp Ảnh Công Trình

### Before (Concept 3D)
| Hạng mục | Quy tắc |
|---|---|
| Góc chụp | Cùng góc với ảnh thực tế (quan trọng cho Before/After slider) |
| Ánh sáng | Tự nhiên, ấm |
| Render quality | Min 2K resolution |
| Furniture staging | Có đồ nội thất, cây xanh, ánh sáng tự nhiên |

### After (Thực tế nghiệm thu)
| Hạng mục | Quy tắc |
|---|---|
| Góc chụp | **Khớp exact với Concept 3D** — cùng vị trí, cùng cao độ |
| Ánh sáng | Ban ngày, cửa mở, đèn bật. KHÔNG dùng flash trực tiếp |
| Staging | Dọn dẹp gọn gàng, có vài vật trang trí (sách, cây, gối) |
| Thiết bị | Camera DSLR/Mirrorless + wide-angle 16-24mm. Hoặc iPhone Pro ultra-wide |
| Tripod | Bắt buộc (đặc biệt cho Before/After cần cùng góc) |
| Số lượng tối thiểu | Min 15 ảnh/dự án: 2 exterior + 8 interior + 2 detail + 3 panorama |

### Checklist chụp ảnh dự án
- [ ] Exterior mặt tiền (2 góc: chính diện + 45°)
- [ ] Phòng khách (2-3 góc)
- [ ] Phòng bếp
- [ ] Phòng ngủ master
- [ ] Phòng tắm
- [ ] Cầu thang / hành lang
- [ ] Chi tiết: tay nắm, vật liệu, đèn, ổ cắm
- [ ] Before/After pairs: CÙNG GÓC
- [ ] 1 ảnh toàn cảnh (panorama)
- [ ] 1 ảnh gia đình chủ nhà (nếu consent)

---

## 3. File Naming Convention

### Format
```
[loại]-[mô-tả]-[kích-thước].[ext]
```

### Ví dụ
| File | Mô tả |
|---|---|
| `hero-phong-khach-biet-thu-nha-trang.webp` | Hero ảnh phòng khách |
| `project-biet-thu-anh-hoang-exterior-01.webp` | Ảnh dự án #1 |
| `before-phong-khach-concept-3d.webp` | Ảnh Before (3D) |
| `after-phong-khach-thuc-te.webp` | Ảnh After (thực tế) |
| `material-gach-bong-xam.webp` | Swatch vật liệu |
| `team-nguyen-van-a-avatar.webp` | Avatar team |
| `blog-xu-huong-noi-that-2026-featured.webp` | Blog featured |
| `logo-partner-dulux.png` | Logo đối tác |

### Quy tắc chung
- Lowercase, dấu gạch ngang `-`
- Tiếng Việt không dấu: `phòng khách` → `phong-khach`
- Chứa keyword SEO nếu được (VD: `noi-that-nha-trang`)
- KHÔNG dùng: spaces, uppercase, ký tự đặc biệt, `IMG_20260312_001.jpg`

---

## 4. Optimization Pipeline

```
Chụp ảnh gốc (JPEG/RAW)
    ↓
Chỉnh sửa (Lightroom/Photoshop)
  - White balance
  - Exposure
  - Crop đúng ratio
  - Remove lens distortion
    ↓
Export (JPEG 80-85% quality, color profile sRGB)
    ↓
Upload WordPress
    ↓
Smush auto-process
  - Convert WebP
  - Compress
  - Strip metadata (EXIF)
  - Generate responsive sizes (srcset)
    ↓
Thêm Alt Text (SEO-friendly)
```

### WordPress Responsive Sizes (auto-generated)
| Size | Width | Sử dụng |
|---|---|---|
| Thumbnail | 150px | Admin |
| Medium | 400px | Material swatches |
| Medium Large | 768px | Mobile images |
| Large | 1024px | Tablet |
| Full | Original | Desktop hero, gallery lightbox |
| **Custom: project-card** | 800px | Portfolio grid card |

```php
// Đăng ký custom image size trong functions.php
add_image_size('project-card', 800, 600, true);
add_image_size('hero-banner', 1920, 900, true);
add_image_size('blog-featured', 1200, 630, true);
```

---

## 5. Watermark & Copyright Policy

| Hạng mục | Quy tắc |
|---|---|
| Watermark | ❌ KHÔNG watermark trên website (ảnh hưởng UX) |
| Copyright text | Footer: "© XANH - Design & Build. Bản quyền hình ảnh thuộc về..." |
| Hotlink protection | ✅ `.htaccess` chặn hotlink từ domain khác |
| Right-click | ❌ KHÔNG disable right-click (gây khó chịu, dễ bypass) |
| Download quality | Ảnh trên web đã compressed — không phải ảnh gốc |

---

## 6. Alt Text Guidelines

### Format
```
[Mô tả nội dung] + [keyword tự nhiên nếu phù hợp] + [— XANH Design & Build]
```

### Ví dụ
| Ảnh | Alt text ✅ | Alt text ❌ |
|---|---|---|
| Phòng khách biệt thự | "Phòng khách biệt thự hiện đại với nội thất gỗ tự nhiên — XANH Design & Build" | "IMG001" |
| Before/After | "So sánh concept 3D và thực tế phòng ngủ master — dự án biệt thự Nha Trang" | "before after" |
| Vật liệu | "Gạch bông gió mặt tiền chống nắng — vật liệu xanh XANH sử dụng" | "gach" |
| Team | "Nguyễn Văn A — Kiến trúc sư trưởng XANH Design & Build" | "avatar" |

---

## Tài Liệu Liên Quan

- `ARCH_PERFORMANCE.md` — Image optimization (Smush, WebP, lazy load)
- `FEATURE_MEDIA_GALLERY.md` — Gallery, slider, lightbox specs
- `GOV_SEO_STRATEGY.md` — Image SEO (file naming, alt text)
- `REF_BRAND_ASSETS.md` — Existing brand assets index
