# 🎨 GỢI Ý NÂNG CẤP UI/UX CHO TỪNG TRANG

> Áp dụng các component bổ sung (10–21) vào từng section cụ thể.
> **Nguyên tắc:** Giữ nguyên 100% nội dung copywriting — chỉ cải thiện thiết kế, bố cục và trải nghiệm.

---

## TRANG CHỦ (HomePage) — 8 → 10 Sections

| Section | Nâng cấp UI/UX | Ghi chú |
|---|---|---|
| **1. Hero** | Thêm **Preloader** (#20): Khi trang load, hiện logo Xanh + animation xoay lá → fade out vào Hero video. Tạo ấn tượng "premium" ngay từ giây đầu tiên | Preloader chỉ hiện lần đầu truy cập (dùng `sessionStorage`) |
| **2. Nỗi trăn trở** | Thêm **Parallax** (#14): Ảnh công trình "trước khi có Xanh" (công trường bừa bộn) làm nền parallax cố định → text cuộn phía trên. Tạo cảm giác "đi qua" nỗi đau | Ảnh parallax cần tối ưu WebP, lazy load |
| **3. Triết lý 4 Xanh** | Nâng cấp từ Grid tĩnh → **hover card flip**: Mặt trước hiện icon + tên. Mặt sau hiện mô tả chi tiết. Viền Cam `#FF8A00` khi hover | Giữ nguyên 4 nội dung, chỉ đổi cách trình bày |
| **★ MỚI: Sau section 3** | Chèn **Animated Counter** (#10): 4 con số chạy animation — "100% Không Phát Sinh" / "X+ Công Trình" / "24/7 Bảo Hành" / "X Năm Kinh Nghiệm". Nền `#14513D`, chữ trắng, counter chạy khi scroll đến | Section ngắn gọn, impact lớn. Animation dùng `IntersectionObserver` |
| **4. Proof of Concept** | Giữ nguyên Before/After Slider. Bổ sung: hiệu ứng **subtle zoom on scroll** cho ảnh thumbnail dự án bên cạnh slider | — |
| **5. Dự Toán** | Thêm **micro-animation**: Progress bar hiệu ứng khi điền từng trường. Nút CTA có hiệu ứng pulse nhẹ liên tục thu hút ánh mắt | Không thay đổi các trường form |
| **6. Quy trình 6 bước** | Nâng cấp thành **vertical timeline** (#16) trên mobile, **horizontal stepper** trên desktop. Mỗi bước có dot sáng lên tuần tự khi scroll | Giữ nguyên 6 bước, đổi layout |
| **7. Testimonials** | Thêm **Video Popup** (#17): Ngoài trích dẫn text, thêm nút ▶ để xem video phỏng vấn khách hàng trong modal overlay | Nếu chưa có video, dự phòng fallback về ảnh + text |
| **★ MỚI: Sau section 7** | Chèn **Partner Logos Bar** (#13): Carousel tự chạy logo đối tác vật liệu (Dulux, An Cường, Schneider, Hafele…). Nền trắng/xám nhạt | Dùng Swiper/Splide, `autoplay: true`, `loop: true` |
| **8. CTA kết thúc** | Thêm nền **Parallax** (#14): ảnh panorama công trình đẹp nhất làm background cố định → text CTA nổi bật phía trên | — |
| **★ GLOBAL** | **Floating CTA Bar** (#15): Thanh cố định bottom mobile — 2 nút: 📞 "Gọi ngay" (tel:) + 📋 "Nhận Dự Toán" (scroll to form). Chỉ hiện trên mobile, ẩn trên desktop | `position: fixed; bottom: 0; z-index: 999` |

---

## TRANG GIỚI THIỆU (AboutPage) — 6 → 8 Sections

| Section | Nâng cấp UI/UX | Ghi chú |
|---|---|---|
| **1. Hero** | Thêm **Video Popup** (#17): Overlay nút ▶ trên banner → click mở video giới thiệu công ty trong modal. Giữ banner ảnh tĩnh cho performance | Video chỉ load khi click |
| **2. The Pain** | Layout đổi thành **zig-zag alternating**: Dòng 1 (text trái, ảnh phải), dòng 2 (ảnh trái, text phải)… Mỗi "nỗi đau" fade-in lần lượt khi scroll | Giữ nguyên 5 bullet points, chia thành 5 block xen kẽ |
| **3. Turning Point** | Nâng cấp infographic vòng tròn thành **animated SVG path**: Vòng tròn vẽ dần khi scroll, mỗi điểm (Thiết kế → Dự toán → Vật liệu → Thi công → Bảo hành) sáng lên lần lượt | `stroke-dasharray` + `stroke-dashoffset` animation |
| **4. Philosophy "4 Xanh"** | Thêm **Services Grid / Icon Box** (#12): Đổi 4 Card thành 4 Icon Box với icon SVG custom, hover scale 1.05 + shadow lift + border-bottom Cam | Cần thiết kế 4 icon SVG riêng cho brand |
| **★ MỚI: Sau section 4** | Chèn **Team Member Cards** (#11): Grid 3-4 thành viên chủ chốt (CEO/Founder, KTS trưởng, Kỹ sư trưởng, Quản lý DA). Ảnh tròn/vuông bo góc, hover hiện social links + bio 2 dòng. Nền `#F3F4F6` | Cần ảnh chân dung thực của team |
| **5. Core Values** | Nâng cấp layout **Sticky Scroll** tinh tế hơn: Cột trái giữ headline + icon lớn (fixed). Cột phải cuộn từng giá trị với transition fade-in. Divider line mỏng giữa các giá trị | Chỉ hoạt động trên desktop, mobile fallback sang accordion |
| **★ MỚI: Sau section 5** | Chèn **Project Timeline / Milestone** (#16): Timeline dọc — các mốc phát triển công ty (Thành lập → Dự án đầu tiên → Cột mốc X công trình → Mở rộng…). Dot + line vẽ dần khi scroll | Nền trắng, line màu `#14513D`, dot màu `#FF8A00` |
| **6. Mission & Vision** | Thêm **Animated Counter** (#10) mini: 3 con số nhỏ inline (X+ công trình / X tỉnh / X% hài lòng) trước nút CTA cuối | Counter nhỏ, không tạo section riêng |

---

## TRANG DỰ ÁN (Portfolio) — Nâng cấp 5 Sections

| Section | Nâng cấp UI/UX | Ghi chú |
|---|---|---|
| **1. Hero** | Thêm **Animated Counter** (#10): Strip counter ngang ngay dưới sub-headline — "X+ Dự án hoàn thành \| X% Sát 3D \| 0% Phát sinh". Chạy animation khi load | Nền sáng, counter màu `#14513D` |
| **2. Filter Bar** | Nâng cấp UX: Thêm **View Toggle** (Grid vs List view). Sticky filter có hiệu ứng `backdrop-filter: blur()` khi cuộn — glassmorphism nhẹ | Filter vẫn giữ nguyên categories |
| **3. Project Grid** | Thêm **skeleton loading**: Khi filter chuyển đổi, hiện skeleton placeholder trước rồi fade-in ảnh dự án. Card thêm **tag badge** góc trên (🟢 Đã bàn giao / 🟡 Đang thi công / ⚪ Concept) | Tăng perceived performance |
| **4. Chi tiết dự án** | Nhiều cải tiến: |  |
| ↳ Đầu trang | Thêm **Breadcrumb** (#21): Home > Dự Án > [Tên dự án] | Schema BreadcrumbList |
| ↳ Stats Bar | Đổi sang **Icon + Number** animation: Mỗi stat (Diện tích, Thời gian, Ngân sách) có icon SVG + số chạy counter animation | Giữ nguyên data |
| ↳ Gallery | Thêm **Lightbox navigation** mượt hơn: swipe gesture (mobile) + keyboard arrow (desktop) + thumbnail strip dưới | `PhotoSwipe` hoặc `GLightbox` |
| ↳ Material Board | Đổi từ Carousel → **Horizontal scroll cards** với snap points. Mỗi card: ảnh vật liệu + tên + tooltip "Vì sao Xanh?" khi hover | `scroll-snap-type: x mandatory` |
| ↳ Cuối trang | Thêm **Related Projects**: Carousel 3 dự án liên quan (cùng loại hình). Auto-suggested | Giữ người dùng ở lại lâu hơn |
| **5. CTA cuối** | Thêm **Parallax** (#14): Ảnh panorama công trình làm nền cố định, text CTA floating phía trên. Nút CTA có hiệu ứng `box-shadow` pulse | — |

---

## TRANG GIẢI PHÁP XANH — Nâng cấp 6 Sections

| Section | Nâng cấp UI/UX | Ghi chú |
|---|---|---|
| **1. Hero** | Text **split animation**: Headline xuất hiện từng từ (stagger delay), sub-headline fade-in sau 0.5s. Nền video mờ có `filter: brightness(0.4)` | GSAP hoặc CSS `@keyframes` |
| **2. The Pain** | Nâng cấp Timeline thành **interactive cards**: Click/hover vào mỗi "nỗi đau" → expand ra mô tả chi tiết + icon minh họa tương ứng. Tông đỏ/xám cho trạng thái "chưa giải quyết" | Giữ nguyên 4 nỗi đau |
| **3. Turning Point** | Thêm **Video Popup** (#17): Cạnh infographic vòng tròn, đặt 1 nút ▶ "Xem quy trình của Xanh" → mở video giải thích chuỗi giá trị khép kín | Video bổ trợ, không thay thế infographic |
| **4. Triết lý 4 Xanh** | Đổi sang **Services Grid / Icon Box** (#12): 4 box lớn, mỗi box có icon SVG animated (draw-on-scroll), tiêu đề, mô tả. Hover nâng nhẹ + border Cam. Background pattern subtle | Giữ nguyên 4 nội dung |
| **5. Brand DNA** | Cải thiện Sticky Scroll: Thêm **progress indicator** — thanh dọc bên trái hiện % đã đọc qua 4 giá trị. Mỗi giá trị fade-in kèm icon lớn | Tạo cảm giác "hành trình cam kết" |
| **6. CTA** | Nền **dark gradient** (`#14513D` → `#0a2e22`). Nút CTA có hiệu ứng **gradient border animation** chạy liên tục (Cam → Xanh → Cam). Text trắng | Hiệu ứng subtle, không rối |

---

## TRANG TIN TỨC & CẨM NANG (Blog) — Nâng cấp 6 Sections

| Section | Nâng cấp UI/UX | Ghi chú |
|---|---|---|
| **1. Hero** | Search Bar thêm **autocomplete/suggest**: Gõ "vật liệu" → dropdown gợi ý bài viết liên quan. Icon 🔍 có animation pulse nhẹ | Cải thiện UX tìm kiếm |
| **2. Categories** | Pill buttons thêm **count badge**: Mỗi tab hiện số bài viết — `Vật Liệu Xanh (12)`. Active tab có underline animation slide | Giúp user biết có bao nhiêu nội dung |
| **3. Featured Articles** | Card bài viết lớn thêm **reading progress indicator**: Nếu user đã đọc 1 bài → hiện badge "Đã đọc" hoặc progress bar nhỏ | Dùng `localStorage` track |
| **4. Article Grid** | Thêm **infinite scroll option** ngoài Load More button. Card hover thêm hiệu ứng **lift shadow** (`translateY(-4px) + box-shadow`) | Cho user chọn preference |
| **5. Bài viết chi tiết** | Nhiều cải tiến: | |
| ↳ Đầu trang | **Breadcrumb** (#21): Home > Tin Tức > [Danh mục] > [Tiêu đề] | Schema BreadcrumbList |
| ↳ Đầu bài | **Reading time + Progress bar**: Thanh ngang trên cùng hiện % đã đọc (scroll progress). Hiện "⏳ 5 phút đọc" | `scroll` event → width% |
| ↳ Cuối bài | **Related Articles**: Grid 3 bài liên quan cùng danh mục. Thêm **Social Share buttons** (Facebook, Zalo, Copy link) | — |
| ↳ Sidebar (PC) | Sticky sidebar thêm **mini CTA card** với ảnh nhỏ: "Bạn đang tìm giải pháp? Nhận dự toán miễn phí →" | CTA không thay đổi |
| **6. Lead Magnet** | Ebook mockup thêm **3D tilt effect**: Di chuột → sách nghiêng nhẹ theo hướng cursor (JS `mousemove`). Nút CTA có **shimmer animation** | Tăng visual appeal |
| **★ GLOBAL** | **Back to Top** (#18): Nút tròn góc phải dưới, icon mũi tên. Hiện khi scroll > 500px. Transition smooth `scrollTo` | `position: fixed; opacity` toggle |

---

## TRANG LIÊN HỆ (ContactPage) — Nâng cấp 3 Sections

| Section | Nâng cấp UI/UX | Ghi chú |
|---|---|---|
| **1. Hero** | Thêm **subtle particle animation**: Các chấm nhỏ xanh bay nhẹ trên nền ảnh tối → tạo cảm giác "sống động" mà không rối | Nhẹ, dùng CSS `@keyframes` thay vì canvas |
| **2. Contact Block** | Nhiều cải tiến form: | |
| ↳ Form UX | **Floating labels**: Label nằm trong input, float lên trên khi focus. Validation real-time (viền xanh khi hợp lệ, đỏ khi sai) | Giữ nguyên 4 trường |
| ↳ Form UX | **Multi-step option**: Thay vì 1 form dài, chia thành 2 step nhỏ (Step 1: Tên + SĐT, Step 2: Loại hình + Ghi chú). Progress bar trên | Optional — test A/B |
| ↳ Google Maps | Style map **custom colors** phù hợp brand: bản đồ tông xanh/xám thay vì mặc định. Pin custom logo Xanh | Google Maps Styling Wizard |
| ↳ Trust Box | Thêm **hover effect**: Khi hover vào MST/Pháp nhân → hiện tooltip "Tra cứu trên Cổng thông tin DN" kèm link | Tăng minh bạch |
| **3. FAQ** | Accordion thêm **icon rotation**: Mũi tên xoay 180° khi mở. Transition mượt `max-height`. Câu hỏi đầu tiên mặc định mở sẵn | Giữ nguyên 4 câu hỏi |
| **★ AFTER FAQ** | Chèn **Animated Counter** (#10) mini strip: "X+ Dự án \| X tỉnh phục vụ \| 100% Không phát sinh" → kéo trust trước khi user rời trang | Thanh nhỏ, nền `#14513D` |
| **★ GLOBAL** | **Cookie Consent** (#19): Banner bottom "Website sử dụng cookies để cải thiện trải nghiệm…" + nút Đồng ý / Tùy chỉnh | Hiện trên mọi trang, nhưng quan trọng nhất ở trang thu thập data |

---

## BẢNG TỔNG HỢP: COMPONENT → TRANG

| Component | Home | About | Portfolio | Giải Pháp | Blog | Liên Hệ |
|---|:---:|:---:|:---:|:---:|:---:|:---:|
| 10. Animated Counter | ✅ | ✅ mini | ✅ | — | — | ✅ mini |
| 11. Team Cards | — | ✅ | — | — | — | — |
| 12. Services/Icon Box | — | ✅ | — | ✅ | — | — |
| 13. Partner Logos | ✅ | — | — | — | — | — |
| 14. Parallax | ✅ ×2 | — | ✅ | — | — | — |
| 15. Floating CTA (Mobile) | ✅ toàn site | ✅ | ✅ | ✅ | ✅ | ✅ |
| 16. Timeline/Milestone | — | ✅ | — | — | — | — |
| 17. Video Popup | ✅ | ✅ | — | ✅ | — | — |
| 18. Back to Top | ✅ toàn site | ✅ | ✅ | ✅ | ✅ | ✅ |
| 19. Cookie Consent | ✅ toàn site | ✅ | ✅ | ✅ | ✅ | ✅ |
| 20. Preloader | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| 21. Breadcrumb | — | — | ✅ detail | — | ✅ detail | — |
