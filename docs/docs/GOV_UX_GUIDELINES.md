# GOV_UX_GUIDELINES — Hướng Dẫn UX/UI

> **Dự án:** Website XANH - Design & Build
> **Phiên bản:** 1.0 | **Ngày tạo:** 2026-03-12
> **Tham chiếu:** [ui_ux_enhancement_guide.md](./ui_ux_enhancement_guide.md)

---

## 1. Triết Lý Thiết Kế Cốt Lõi

### "Thực" Hơn "Đẹp"

> Tuyệt đối không dùng ảnh stock. Mọi hình ảnh phải là người thật, việc thật của Xanh.
> Website phải truyền tải sự **đáng tin cậy**, không phải sự hào nhoáng.

| Nguyên tắc | Giải thích |
|---|---|
| **Storytelling Layout** | Bố cục kể chuyện: Nỗi đau → Giải pháp → Bằng chứng → Hành động |
| **Transparency (Minh bạch)** | Thông số, bảng giá, quy trình phải rõ ràng, không mập mờ |
| **Trust-first** | Mọi section đều phải tăng cường trust: số liệu, testimonial, brand marks |
| **Warmth** | Gần gũi, ấm áp — không lạnh lùng corporate. Thể hiện qua tone màu be, ảnh gia đình |

---

## 2. Mobile First — Bắt Buộc

> **Đa số khách hàng tìm kiếm trên điện thoại khi đang ở công trường hoặc di chuyển.**

### Breakpoint Strategy

| Breakpoint | Target | Priority |
|---|---|---|
| < 640px | Mobile phone | 🔴 Thiết kế ĐẦU TIÊN |
| 640-768px | Tablet portrait | 🟠 |
| 768-1024px | Tablet landscape / small laptop | 🟡 |
| 1024-1280px | Laptop | 🟢 |
| > 1280px | Desktop | 🟢 |

### Mobile-specific UX Rules

| Rule | Implementation |
|---|---|
| Touch targets | Min 44×44px cho buttons, links |
| Form inputs | Min height 48px, font-size ≥ 16px (tránh iOS zoom) |
| Tables/Charts | Horizontal scroll hoặc reflow |
| Navigation | Hamburger menu (☰) |
| Floating CTA | Bottom bar cố định: "Gọi ngay" + "Nhận Dự Toán" |
| Portfolio filter | Horizontal scroll pills |
| Sticky Elements | Chỉ sticky filter bar, KHÔNG sticky toàn bộ nav |
| Image galleries | Swipe gesture support |
| Parallax | `background-attachment: scroll` (iOS fallback) |

---

## 3. Animation & Motion Guidelines

### Nguyên tắc chung

> **Nhẹ nhàng, tinh tế.** Tránh hiệu ứng quá nhanh hoặc rối mắt — giữ sự "Bình yên" của thương hiệu.

| ✅ NÊN | ❌ KHÔNG |
|---|---|
| Fade-in khi scroll đến | Bounce / flash effects |
| Subtle scale (1.00 → 1.05) | Quay xoay liên tục |
| Smooth transitions 300-600ms | Transitions < 100ms hoặc > 1000ms |
| Stagger animation (tuần tự) | Tất cả cùng animate 1 lúc |
| Counter chạy 1 lần duy nhất | Loop animation vô hạn (trừ preloader) |

### Accessibility: Reduced Motion
```css
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    animation-duration: 0.01ms !important;
    transition-duration: 0.01ms !important;
    scroll-behavior: auto !important;
  }
}
```

### Animation Inventory

| Animation | Duration | Trigger | Lần chạy |
|---|---|---|---|
| Scroll reveal (fade-up) | 600ms | IntersectionObserver | 1x |
| Counter count-up | 2000ms | IntersectionObserver | 1x |
| Card hover lift | 300ms | `:hover` | Unlimited |
| Parallax scroll | Continuous | Scroll | Unlimited |
| SVG draw line | 1000ms | IntersectionObserver | 1x |
| Preloader fade-out | 500ms | Window load | 1x |
| Button pulse (CTA) | 2000ms loop | Always | Unlimited (subtle) |
| Accordion expand | 300ms | Click | Unlimited |

---

## 4. Accessibility (WCAG 2.1 Level AA)

| Requirement | Implementation |
|---|---|
| **Color Contrast** | Minimum 4.5:1 cho text, 3:1 cho large text |
| **Keyboard Navigation** | Tab order logic, Enter/Space triggers, Escape closes |
| **Focus Visible** | Outline rõ ràng cho keyboard users (không `outline: none`) |
| **Image Alt Text** | Mô tả nội dung ảnh, không phải tên file |
| **Form Labels** | Mọi input có `<label>` hoặc `aria-label` |
| **Screen Reader** | `aria-expanded` cho accordion, `role="dialog"` cho modals |
| **Skip Link** | "Bỏ qua đến nội dung chính" ẩn, hiện khi Tab |
| **Reduced Motion** | Respect `prefers-reduced-motion` |

### Contrast Check — Brand Colors

| Combination | Ratio | Status |
|---|---|---|
| White (#FFF) on Primary (#14513D) | 8.4:1 | ✅ AAA |
| White (#FFF) on Accent (#FF8A00) | 2.9:1 | ⚠️ Large text only |
| Gray-900 (#111827) on White (#FFF) | 16.3:1 | ✅ AAA |
| Gray-900 (#111827) on Beige (#D8C7A3) | 7.8:1 | ✅ AAA |
| White (#FFF) on Accent-Hover (#E67A00) | 3.2:1 | ✅ Large text AA |

> ⚠️ Nút CTA cam (#FF8A00) cần chữ đen hoặc tăng kích thước chữ ≥ 18px bold.

---

## 5. Layout Patterns Theo Trang

### Home — Storytelling Flow
```
┌─ Hero (100vh) ──────────────────────────┐
├─ Nỗi trăn trở (parallax bg, text ←→ img)│
├─ Triết lý 4 Xanh (4-col grid, card flip)│
├─ ★ Animated Counter (#14513D bg)         │
├─ Proof of Concept (Before/After Slider)  │
├─ Dự Toán Form (lead capture)             │
├─ Quy trình 6 bước (stepper/timeline)     │
├─ Testimonials (quotes + video popup)     │
├─ ★ Partner Logos (auto carousel)         │
├─ CTA (parallax bg, 2 buttons)           │
└─ [Floating CTA - mobile only]           │
```

### Portfolio Grid → Detail
```
Grid Page:
┌─ Hero + Counter strip ──────────────────┐
├─ Sticky Filter Bar ─────────────────────│
├─ Project Cards (masonry/3-col grid)     │
└─ CTA ───────────────────────────────────│

Detail Page:
┌─ Breadcrumb ────────────────────────────┐
├─ Hero image ────────────────────────────│
├─ Stats Bar (icon + counter animation)   │
├─ Câu chuyện (2-col: Bài toán ↔ Lời giải)│
├─ Before/After Slider ───────────────────│
├─ Material Board (snap scroll cards)     │
├─ Gallery (lightbox) ───────────────────│
├─ Testimonial ──────────────────────────│
├─ Related Projects (carousel)           │
└─ CTA ───────────────────────────────────│
```

### Blog — Content + Lead Capture
```
List Page:
┌─ Hero + Search Bar ─────────────────────┐
├─ Category Tabs (sticky pills)           │
├─ Featured (1 large + 2 small)           │
├─ Article Grid (masonry/3-col)           │
├─ Load More (AJAX)                       │
└─ Lead Magnet (ebook 3D + form)          │

Detail Page:
┌─ Breadcrumb ────────────────────────────┐
├─ Title + Meta (date, author, read time) │
├─ Reading Progress Bar (top sticky)      │
├─ [Main content] + [Sticky Sidebar CTA]  │
├─ Social Share (FB, Zalo, Copy)          │
├─ Related Articles (3-grid)              │
└─ Lead Magnet ───────────────────────────│
```

---

## 6. Form UX Guidelines

| Rule | Implementation |
|---|---|
| **Floating labels** | Label trong input, float lên khi focus |
| **Real-time validation** | Viền xanh OK, viền đỏ lỗi, message inline |
| **Error messages** | Rõ ràng: "Vui lòng nhập số điện thoại hợp lệ (10 số)" |
| **CTA button** | Full-width trên mobile, mô tả lợi ích |
| **Loading state** | Button hiện spinner khi đang gửi |
| **Success state** | Redirect → Thank-you page hoặc inline success message |
| **Micro-copy** | "🔒 Xanh cam kết bảo mật 100% thông tin cá nhân" dưới form |
| **Required fields** | Đánh dấu (*) rõ ràng |
| **Min fields** | Chỉ hỏi thông tin cần thiết (tối đa 4-5 trường) |

---

## Tài Liệu Liên Quan

- `ARCH_UI_PATTERNS.md` — 27 UI components chi tiết
- `ARCH_DESIGN_TOKENS.md` — Design tokens
- `GOV_BRAND_VOICE.md` — Copywriting & CTA rules
- `PAGE_*.md` — Layout specs từng trang
- `ui_ux_enhancement_guide.md` — Tài liệu gốc UI/UX enhancements
