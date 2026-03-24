---
description: Quy trình viết content chuẩn SEO theo định vị Warm Luxury & Visual Direction
---

Quy trình này hướng dẫn cách tạo nội dung chuẩn SEO kết hợp với định vị thương hiệu "Warm Luxury", được tổng hợp từ `.agent/rules/04-seo.md`, `.agent/rules/07-content-brand-voice.md`, `GOV_BRAND_VOICE.md` và `ARCH_LUXURY_VISUAL_DIRECTION.md`. Vui lòng kết hợp với các SEO skills có liên quan trong `.agent/skills` để đạt hiệu quả tối ưu.

## 1. Nghiên Cứu & Lên Kế Hoạch (Planning)
- **Skills sử dụng:** `seo-content-planner`, `seo-keyword-strategist`.
- Tuân thủ **On-Page SEO Checklist** (`04-seo.md`): Lập kế hoạch từ khóa chi tiết. Từ khóa chính (Primary Keyword) và từ khóa phụ (LSI) phải được phân bổ tự nhiên, tránh nhồi nhét.
- **Brand Voice (**`GOV_BRAND_VOICE.md`**):** Tư duy content xuất phát từ **ASPIRATION** (Giấc mơ/Khát vọng) thay vì PAIN (Nỗi đau). Người viết đóng vai trò là "chuyên gia chia sẻ insight" (VD: "98% độ sát 3D theo chia sẻ từ 47 gia chủ...").

## 2. Xây Dựng Cấu Trúc (Structure & Hierarchy)
- **Skills sử dụng:** `seo-structure-architect`, `seo-fundamentals`.
- **Thẻ H1:** Chỉ 1 thẻ H1 duy nhất trên mỗi trang, chứa từ khóa chính, không bao giờ dùng cho logo.
- **Heading Hierarchy:** `H1 → H2 → H3` logic, không nhảy cóc cấp độ heading.
- **Typography Luxury (**`ARCH_LUXURY_VISUAL_DIRECTION.md`**):** 
  - Đảm bảo khoảng trắng văn bản, đoạn văn tối đa 2-3 câu (`max-width: 65ch` cho trải nghiệm đọc luxury). 
  - Bôi đậm các cụm từ đắt giá để tối ưu scannability.
- **Storytelling Framework:** `ASPIRATION → EMPATHY → SOLUTION → PROOF → INVITATION`.

## 3. Viết Nội Dung (Writing & Word Choices)
- **Skills sử dụng:** `seo-content-writer`, `content-creator`, `copywriting`.
- Đảm bảo từ khóa chính xuất hiện trong **100 từ đầu tiên**.
- **Tone "Warm Luxury":** Tinh tế, Chân thành, Đồng cảm, Ấm áp (Giữa mức lạnh lùng của Chanel và xô bồ của hàng giá rẻ).
- **Từ Khóa NÊN DÙNG:** *Tinh tế, Bản sắc, Trường tồn, Kiến tạo, Di sản, Tổ ấm, Đồng hành, Không gian sống.* 
- **Từ Khóa CẤM & KHÔNG PHÙ HỢP:** *Giá rẻ, Khuyến mãi, Tiết kiệm, "Tuyệt đối", "Số 1", "Bậc nhất".* Thay thế bằng: "Cam kết minh bạch chi phí", "Tối ưu giá trị".
- **Quy tắc trích dẫn số liệu:** Dùng dạng "120m²", "2.5 Tỷ VNĐ", "120 ngày", "98%". KHÔNG dùng dạng chữ ("một trăm hai mươi", "hai phết năm tỷ").

## 4. Tối Ưu Hình Ảnh & Trải Nghiệm (Media & Visual Direction)
- **Visual Philosophy (**`ARCH_LUXURY_VISUAL_DIRECTION.md`**):** 
  - Hình ảnh phải tuân thủ triết lý *Warm Luxury*, màu ấm (Warm Tones), KHÔNG DÙNG ảnh AI cho showcase, KHÔNG DÙNG ảnh stock rập khuôn. Ưu tiên có "Human Touch" (người thực sinh hoạt).
  - Tỷ lệ nội dung/khoảng trắng là **40/60** (Restraint & Breathing Room). Không chồng chéo nhiều element.
- **Image SEO (**`04-seo.md`**):** 
  - Tên file chuẩn SEO: viết thường, không dấu, ngăn cách bằng gạch ngang (vd: `thiet-ke-noi-that-biet-thu.webp`).
  - Thẻ `Alt text` chèn từ khóa tự nhiên, miêu tả chính xác.
  - Sử dụng định dạng `WebP` hoặc `AVIF` và thuộc tính `loading="lazy"` cho phần nội dung dưới fold.

## 5. Tối Ưu Metadata & Schema (Meta & Technical)
- **Skills sử dụng:** `seo-meta-optimizer`, `schema-markup`.
- **Title Tag Templates (< 60 chars):** 
  - Blog: `{Tiêu đề bài} | XANH - Design & Build`
  - Portfolio: `{Tên dự án} - {Kiểu dáng} {Vị trí} | XANH`
- **Meta Description (< 160 chars):** Chứa từ khóa chính + Thông điệp mời gọi tinh tế.
- **Canonical & Open Graph:** Luôn luôn áp dụng thẻ Canonical để tránh trùng lặp nội dung. `<meta property="og:...">` chuẩn chỉnh cho ảnh và description.
- Cấu hình Schema (JSON-LD): Áp dụng `Article` hoặc `NewsArticle` cho bài viết blog, `BreadcrumbList` cho các trang con.

## 6. Lời Mời Gọi Hành Động & Liên Kết Cấu Trúc (CTA & Links)
- **Calls to Action (Warm Luxury):** 
  - KHÔNG DÙNG: "Liên hệ ngay", "Click here", "Nhấn vào đây" (Pushy & Generic).
  - NÊN DÙNG (`GOV_BRAND_VOICE.md`): *"Bắt Đầu Câu Chuyện Của Bạn"*, *"Khám Phá Dự Toán Của Bạn"*, *"Đặt Lịch Tư Vấn Riêng"*, *"Nhận Cẩm Nang Xây Dựng"*.
- **Internal Linking:** 
  - Mỗi bài viết phải có ít nhất 2 liên kết nội bộ về Dự Án thực tế (Portfolio) hoặc Liên Hệ (Contact).
  - Phải dùng Keyword tự nhiên làm Anchor Text.
  - 3 bài viết liên quan (Related Posts) xuất hiện ở cuối trang.
- **External Links:** Dẫn link ra ngoài tham chiếu các nguồn uy tín 1-2 lần để tăng Trust/E-E-A-T.
