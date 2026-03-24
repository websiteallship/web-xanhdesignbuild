---
description: Cross-section UI element synchronization rules. Apply when creating or modifying any section to ensure visual consistency across pages and reduce code duplication.
globs: "**/*.{html,css,php,js}"
---

# Cross-Section Element Consistency ★★★

> **Nguyên tắc cốt lõi:** Các phần tử UI giống nhau ở các section khác nhau **BẮT BUỘC** phải dùng chung CSS classes.
> Viết 1 lần — dùng mọi nơi. **KHÔNG** viết lại inline style cho mỗi section.

---

## 1. Section Header Pattern — Bộ Tiêu Đề Section ★

Mọi section (trừ Hero) **BẮT BUỘC** dùng chung cấu trúc header:

```html
<div class="section-header [section-header--center | section-header--left]">
  <span class="section-eyebrow">Eyebrow Label</span>
  <h2 class="section-title">Tiêu Đề <em>Highlight</em></h2>
  <p class="section-subtitle">Mô tả ngắn gọn cho section.</p>
</div>
```

### Shared Classes — THỐNG NHẤT toàn site:

| Class | Công dụng | Quy tắc |
|---|---|---|
| `.section-eyebrow` | Label / tagline trên tiêu đề | `text-[0.7rem]`, `font-bold`, `tracking-[0.2em]`, `uppercase`, `mb-4` |
| `.section-title` | Tiêu đề H2 chính | `font-heading`, `clamp(2rem, 4vw, 3.25rem)`, `font-bold`, `leading-[1.15]`, `tracking-[-0.02em]` |
| `.section-subtitle` | Mô tả dưới tiêu đề | `text-base md:text-lg`, `leading-[1.8]`, `tracking-wide`, `max-w-2xl` |
| `.section-header--center` | Căn giữa | `text-center mx-auto` |
| `.section-header--left` | Căn trái | Mặc định |

### Variant màu theo nền section:

| Nền section | Eyebrow | Title | Subtitle |
|---|---|---|---|
| **White / Beige** (nền sáng) | `text-primary/50` | `text-dark` | `text-dark/80` |
| **Green / Dark** (nền tối) | `text-white/50` | `text-white` | `text-white/70` |

> ❌ **CẤM:** Tự hardcode `font-heading text-[clamp(2.5rem,5vw,4rem)]` cho mỗi section.
> ✅ **ĐÚNG:** Dùng class `.section-title` đã định nghĩa sẵn.

---

## 2. CTA Button Pattern — Nút Hành Động ★

Toàn site chỉ có **3 variant** CTA button, KHÔNG tạo thêm:

```html
<!-- Primary (Orange) -->
<a href="#" class="btn btn--primary">
  <span>Đặt Lịch Tư Vấn Riêng</span>
  <i data-lucide="arrow-right" class="btn__icon"></i>
</a>

<!-- Outline (trên nền sáng) -->
<a href="#" class="btn btn--outline">Text</a>

<!-- Ghost (trên nền tối) -->
<a href="#" class="btn btn--ghost">Text</a>
```

| Class | Background | Text | Border | Hover |
|---|---|---|---|---|
| `.btn--primary` | `bg-accent` | `text-white` | none | `bg-[#e67a00]` + `shadow-xl` |
| `.btn--outline` | transparent | `text-primary` | `border-primary` | `bg-primary text-white` |
| `.btn--ghost` | transparent | `text-white` | `border-white/30` | `bg-white/10` |

> Shared specs: `px-8 py-3.5`, `text-sm font-semibold`, `tracking-wide uppercase`, `transition-all duration-300`
> ❌ **CẤM:** Inline `bg-accent text-white text-sm font-semibold uppercase tracking-[0.1em] hover:bg-accent/90` trên mỗi button.

---

## 3. Icon Circle Pattern — Vòng Tròn Icon ★

Khi một section dùng icon trong vòng tròn, **BẮT BUỘC** dùng class chung:

```html
<div class="icon-circle [icon-circle--sm | icon-circle--lg]">
  <i data-lucide="pen-tool"></i>
</div>
```

| Variant | Size | Icon | Sử dụng |
|---|---|---|---|
| `icon-circle--sm` | `w-12 h-12` | `w-5 h-5` | Pain items, list items |
| (default) | `w-14 h-14` | `w-6 h-6` | Service cards, features |
| `icon-circle--lg` | `w-[72px] h-[72px]` | `w-7 h-7` | Process steps, value chain |

> Shared: `rounded-full`, `border border-dark/10`, `flex items-center justify-center`
> Hover: `group-hover:bg-primary group-hover:text-white group-hover:border-primary transition-all duration-500`

---

## 4. Card Pattern — Thẻ Nội Dung ★

Mọi card (service, value, blog, project) chia sẻ base class:

```html
<div class="card [card--service | card--value | card--blog]">
  <!-- Nội dung card -->
</div>
```

### Shared Card Styles:
```css
.card {
  background: var(--card-bg);
  border-radius: var(--card-radius);
  padding: var(--card-padding);
  box-shadow: var(--card-shadow);
  transition: var(--card-transition);
}
.card:hover {
  transform: translateY(-4px);
  box-shadow: var(--card-shadow-hover);
}
```

> ❌ **CẤM:** Mỗi section tự viết hover shadow, translate riêng.
> ✅ **ĐÚNG:** Dùng `var(--card-shadow)` / `var(--card-shadow-hover)` từ Design Tokens §9.

---

## 5. Content Text Block — Khối Văn Bản ★

Khi section có đoạn văn bản mô tả, dùng class chung:

| Class | Mô tả | Specs |
|---|---|---|
| `.content-text` | Thu hẹp text cho reading experience | `max-width: 800px`, `mx-auto` |
| `.text-lead` | Đoạn mở đầu nổi bật | `text-base md:text-lg`, `leading-[1.8]`, `tracking-wide` |
| `.text-body` | Đoạn nội dung thường | `text-base`, `leading-[1.6]`, `max-w-[65ch]` |
| `.text-quote` | Trích dẫn / italic | `italic`, `font-light`, `leading-[1.7]`, `text-dark/75` |

---

## 6. Section Spacing — Nhịp Điệu Đồng Bộ ★

Mọi section **BẮT BUỘC** tuân thủ spacing tokens:

| Token | Mobile | Tablet | Desktop | Mục đích |
|---|---|---|---|---|
| `section padding-y` | `48px` | `64px` | `80px` | Khoảng cách trên/dưới mỗi section |
| `section-gap` | `32px` | `40px` | `48px` | Giữa header và content |
| `section-title-mb` | `16px` | `16px` | `16px` | Title → subtitle |
| `card-gap` | `24px` | `24px` | `24px` | Giữa các cards |

> ❌ **CẤM:** Section A dùng `py-20 lg:py-32`, section B dùng `py-16 lg:py-24`.
> ✅ **ĐÚNG:** Tất cả dùng `py-12 md:py-16 lg:py-20` (hoặc CSS class `.section`) nhất quán.

---

## 7. Tag / Badge / Label — Nhãn Phân Loại

Khi cần nhãn phân loại (category, tag), dùng class chung:

```html
<span class="tag">Biệt Thự</span>
<span class="badge badge--accent">Mới</span>
```

| Class | Specs |
|---|---|
| `.tag` | `text-xs`, `px-3 py-1`, `bg-primary/10 text-primary`, `rounded-full`, `font-medium` |
| `.badge` | `text-xs`, `px-2 py-0.5`, `rounded-sm`, `font-semibold uppercase tracking-wider` |
| `.badge--accent` | `bg-accent text-white` |

---

## 8. Divider / Separator — Đường Phân Cách

| Pattern | Class | Specs |
|---|---|---|
| Nền sáng | `.divider` | `border-dark/10`, `h-px` |
| Nền tối | `.divider--light` | `border-white/10`, `h-px` |
| Decorative | `.divider--accent` | `w-12 h-0.5 bg-accent` — dùng cho section header |

---

## 9. Entrance Animation Classes — GSAP Standardized

Mọi section dùng chung animation pattern:

| Class | Effect | Dùng cho |
|---|---|---|
| `.anim-fade-up` | `opacity: 0 → 1, y: 40 → 0` | Section headers, cards |
| `.anim-fade-left` | `opacity: 0 → 1, x: -40 → 0` | Left-aligned content |
| `.anim-fade-right` | `opacity: 0 → 1, x: 40 → 0` | Right-aligned content |
| `.anim-scale-in` | `opacity: 0 → 1, scale: 0.95 → 1` | Images, cards |
| `.anim-stagger` | Stagger children `100ms` | Card grids, lists |

```javascript
// GSAP setup DUY NHẤT — KHÔNG viết lại cho mỗi section
gsap.utils.toArray('.anim-fade-up').forEach(el => {
  gsap.from(el, {
    scrollTrigger: { trigger: el, start: 'top 85%' },
    opacity: 0, y: 40, duration: 0.8, ease: 'power2.out'
  });
});
```

> ❌ **CẤM:** Mỗi section tự viết GSAP riêng với opacity/y/duration khác nhau.
> ✅ **ĐÚNG:** Dùng shared animation classes, config chuẩn hóa.

---

## 10. Implementation Checklist — Khi Tạo Section Mới

Trước khi code một section mới, kiểm tra:

- [ ] Header dùng `.section-header` + `.section-eyebrow` + `.section-title` + `.section-subtitle`?
- [ ] CTA button dùng `.btn--primary` / `.btn--outline` / `.btn--ghost`?
- [ ] Cards dùng base `.card` class + variant?
- [ ] Icon circles dùng `.icon-circle` class?
- [ ] Spacing dùng section tokens, không hardcode?
- [ ] Animation dùng `.anim-*` shared classes?
- [ ] Tags/badges dùng `.tag` / `.badge` classes?
- [ ] Text blocks dùng `.text-lead` / `.text-body`?
- [ ] Màu sắc theo variant nền section (sáng/tối)?
- [ ] Container wrap bằng `.site-container`?

> **Mục tiêu:** Mỗi section mới chỉ cần viết CSS cho layout riêng.
> Typography, colors, spacing, hover, animation → đã có sẵn shared classes.
> **Kết quả:** Clean code, ít CSS, nhất quán toàn site.

---

## Tài Liệu Liên Quan

- `ARCH_DESIGN_TOKENS.md` §9 — Component token system
- `ARCH_UI_PATTERNS.md` — 27 Component specs
- `02-frontend-css-js.md` — Container system & CSS architecture
- `03-ux-ui-design.md` — Layout rules & anti-patterns
