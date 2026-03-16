---
description: CSS optimization rules for rendering performance, selector efficiency, and maintainability. Apply when writing or reviewing any CSS.
globs: "**/*.css"
---

# CSS Optimization Rules ★★

> **Mục tiêu:** CSS gọn nhẹ, render nhanh, dễ bảo trì.
> Bổ sung cho `02-frontend-css-js.md` (architecture) và `06-performance.md` (budgets).

---

## 1. Rendering Performance — Tối Ưu Dựng Hình ★

### 1.1 — Chỉ Animate Thuộc Tính Composited
```css
/* ✅ GPU-composited — KHÔNG gây layout/paint */
transform, opacity, filter

/* ❌ KHÔNG BAO GIỜ animate — gây layout thrashing */
width, height, top, left, right, bottom,
margin, padding, border-width, font-size
```

> Lý do: `transform` & `opacity` chạy trên GPU compositor thread, không chạm layout/paint.
> Animate `width`/`height` → layout **toàn bộ trang** mỗi frame → jank.

### 1.2 — `will-change` Có Kiểm Soát
```css
/* ✅ Chỉ dùng trên phần tử SẼ animate */
.card { will-change: transform; }

/* ✅ Dọn dẹp sau khi animate xong (JS) */
el.addEventListener('transitionend', () => {
  el.style.willChange = 'auto';
});

/* ❌ CẤM lạm dụng */
* { will-change: transform; }           /* tạo composite layer MỌI phần tử */
.static-text { will-change: transform; } /* phần tử KHÔNG animate */
```

**Quy tắc:**
- Chỉ dùng cho phần tử có `transition` hoặc GSAP animation
- Tối đa **10-15 phần tử** cùng lúc trên viewport
- KHÔNG đặt trên `:root`, `body`, `*`
- Ưu tiên set qua JS trước animation, gỡ sau khi xong

### 1.3 — Tránh Layout Thrashing
```css
/* ❌ Trigger reflow liên tục */
.bad { height: auto; transition: height 0.3s; }

/* ✅ Dùng max-height pattern hoặc GSAP */
.collapse { max-height: 0; overflow: hidden; transition: max-height 0.4s ease; }
.collapse.is-open { max-height: 500px; }

/* ✅ Hoặc dùng CSS Grid trick */
.grid-collapse { display: grid; grid-template-rows: 0fr; transition: grid-template-rows 0.4s ease; }
.grid-collapse.is-open { grid-template-rows: 1fr; }
```

### 1.4 — Contain để Giới Hạn Repaint
```css
/* ✅ Sections độc lập — giới hạn phạm vi layout/paint */
.section { contain: layout style; }

/* ✅ Cards trong grid — paint riêng biệt */
.card { contain: layout; }

/* ❌ KHÔNG dùng contain: strict trên phần tử cần scroll */
```

---

## 2. Selector Efficiency — Viết Selector Gọn ★

### 2.1 — Nguyên Tắc Selector
```css
/* ✅ Tốt — class đơn, flat, BEM */
.card__title { }
.btn--primary { }
.section-header { }

/* ⚠️ Tránh — nesting sâu > 3 cấp */
.section .card .card__body .card__title span { }

/* ❌ CẤM — tag selector không cần thiết */
div.card { }       /* → .card */
ul.nav__links li { } /* → .nav__link */
```

**Quy tắc tính điểm specificity:**
| Loại | Specificity | Ví dụ |
|---|---|---|
| Class đơn | `0,1,0` | `.card` |
| Class kép | `0,2,0` | `.section--dark .card` |
| ID | `1,0,0` | `#hero` — **hạn chế** |
| `!important` | ∞ | **CẤM** (trừ reduced-motion override) |

- Giữ specificity **đồng đều** trong cùng 1 component → dễ override
- Ưu tiên **class đơn** + **BEM** → `0,1,0` nhất quán
- ID selectors: CHỈ cho JS hooks (`#site-header`), KHÔNG dùng cho styling
- `!important`: CHỈ được phép trong `prefers-reduced-motion` media query

### 2.2 — Tránh Selector Universal Tốn Kém
```css
/* ❌ Slow selectors */
[class*="icon"] { }     /* attribute substring → scan toàn bộ DOM */
:nth-child(odd) > * { } /* universal child combo */

/* ✅ Explicit class */
.icon { }
.row--striped { }
```

---

## 3. Shorthand & DRY — Viết Ngắn Gọn ★

### 3.1 — Dùng Shorthand Hợp Lý
```css
/* ✅ Shorthand khi set ≥ 2 giá trị */
margin: 0 auto;
padding: var(--space-12) var(--space-6);
transition: opacity 0.3s ease, transform 0.3s ease;
border: 1px solid var(--color-gray-200);
inset: 0;                              /* = top/right/bottom/left: 0 */
gap: var(--space-6);                    /* = row-gap + column-gap */

/* ✅ Logical properties — hỗ trợ RTL */
margin-inline: auto;                   /* thay margin-left + margin-right */
padding-block: var(--space-20);        /* thay padding-top + padding-bottom */

/* ❌ KHÔNG tách khi shorthand đủ */
padding-top: 1rem;
padding-right: 1.5rem;
padding-bottom: 1rem;
padding-left: 1.5rem;
/* → padding: 1rem 1.5rem; */
```

### 3.2 — DRY: Không Lặp Lại
```css
/* ❌ Copy-paste pattern */
.service-card { box-shadow: 0 4px 6px rgba(0,0,0,0.07); transition: all 0.3s; }
.service-card:hover { box-shadow: 0 10px 25px rgba(0,0,0,0.1); transform: translateY(-4px); }
.blog-card { box-shadow: 0 4px 6px rgba(0,0,0,0.07); transition: all 0.3s; }
.blog-card:hover { box-shadow: 0 10px 25px rgba(0,0,0,0.1); transform: translateY(-4px); }

/* ✅ Dùng shared class từ 08-cross-section-consistency.md */
.card { box-shadow: var(--card-shadow); transition: var(--card-transition); }
.card:hover { box-shadow: var(--card-shadow-hover); transform: translateY(-4px); }
/* .service-card, .blog-card chỉ thêm phần khác biệt */
```

> Trước khi viết CSS mới, **KIỂM TRA** `08-cross-section-consistency.md` xem đã có shared class chưa.

### 3.3 — Gom Transition Thông Minh
```css
/* ❌ transition: all — animate mọi thứ, kể cả không cần */
.card { transition: all 0.3s ease; }

/* ✅ Chỉ list thuộc tính cần animate */
.card { transition: transform 0.3s ease, box-shadow 0.3s ease; }

/* ✅ Nếu > 3 thuộc tính, dùng custom property */
.card {
  --card-transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
  transition: var(--card-transition);
}
```

---

## 4. Media Query Organization — Tổ Chức Responsive ★

### 4.1 — Mobile-First, Không Trùng Breakpoint
```css
/* ✅ Mobile-first: base → mở rộng dần */
.grid { grid-template-columns: 1fr; }

@media (min-width: 768px) {
  .grid { grid-template-columns: repeat(2, 1fr); }
}

@media (min-width: 1024px) {
  .grid { grid-template-columns: repeat(3, 1fr); }
}

/* ❌ CẤM: desktop-first (max-width) khi đã dùng min-width ở nơi khác */
@media (max-width: 767px) { /* → xung đột với min-width: 768px */ }
```

### 4.2 — Gom Media Query Theo Component
```css
/* ✅ Mỗi component tự chứa responsive */
.card { padding: var(--space-4); }
.card__title { font-size: 1rem; }

@media (min-width: 768px) {
  .card { padding: var(--space-6); }
  .card__title { font-size: 1.25rem; }
}

/* ❌ CẤM: 1 media query khổng lồ ở cuối file chứa mọi component */
```

### 4.3 — Breakpoints Chuẩn Hóa
| Token | Value | Dùng cho |
|---|---|---|
| `sm` | `640px` | Mobile landscape |
| `md` | `768px` | Tablet |
| `lg` | `1024px` | Desktop |
| `xl` | `1280px` | Large desktop |
| `2xl` | `1440px` | Ultra-wide |

> KHÔNG tạo breakpoint tùy ý (`max-width: 850px`). Dùng tokens ở trên.

---

## 5. File Organization — Cấu Trúc File CSS ★

### 5.1 — Thứ Tự Thuộc Tính (Grouped)
```css
.element {
  /* 1. Layout — vị trí & hiển thị */
  display: flex;
  position: relative;
  align-items: center;
  gap: var(--space-4);

  /* 2. Box Model — kích thước */
  width: 100%;
  max-width: 1280px;
  padding: var(--space-6);
  margin-inline: auto;

  /* 3. Typography */
  font-family: var(--font-heading);
  font-size: var(--text-h2);
  line-height: 1.15;
  letter-spacing: -0.02em;
  color: var(--color-dark);

  /* 4. Visual — nền, viền, bóng */
  background: var(--color-white);
  border: 1px solid var(--color-gray-200);
  border-radius: var(--radius-md);
  box-shadow: var(--shadow-md);

  /* 5. Effects — chuyển động */
  opacity: 1;
  transform: translateY(0);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  will-change: transform;
}
```

### 5.2 — Comment Sections Rõ Ràng
```css
/* ==============================================
   SECTION NAME — Description
   ============================================== */

/* ── Sub-section ── */
```

### 5.3 — Không Trùng `:root` Giữa Các File
- `:root` tokens chỉ khai báo **1 lần** trong `variables.css` (hoặc file gốc)
- File component: KHÔNG khai báo lại `:root`, chỉ dùng `var(--token-name)`
- Nếu cần token mới → thêm vào `variables.css`, KHÔNG inline

---

## 6. Anti-Patterns — Những Điều KHÔNG Làm ❌

| Anti-Pattern | Vấn đề | Thay thế |
|---|---|---|
| `* { transition: all 0.3s; }` | Animate mọi thứ, kể cả layout | List cụ thể từng phần tử |
| `div > div > div > span` | Specificity cao, brittle | `.block__text` (BEM) |
| `!important` trên mọi override | Không thể override tiếp | Tăng specificity bằng class |
| `@import url()` trong CSS | Blocking request chuỗi | `<link>` song song |
| Hardcode `px` cho font-size | Không accessible (zoom) | `rem`, `clamp()`, `em` |
| `display: none` cho animate | Không transition được | `opacity: 0` + `visibility: hidden` + `pointer-events: none` |
| `height: 100vh` trên mobile | Address bar ẩn/hiện → jump | `100svh` hoặc `100dvh` |
| Color trực tiếp `#14513D` | Không nhất quán | `var(--color-primary)` |
| `z-index: 9999` random | Z-index war | Token hóa: `--z-modal: 9000` |
| `box-shadow` + `border-radius` bên ngoài | double paint | `overflow: hidden` trên parent |

---

## 7. Checklist — Khi Review CSS ✓

Trước khi commit CSS mới, kiểm tra:

- [ ] **Tokens:** Tất cả colors, spacing, fonts dùng `var(--token)`?
- [ ] **Selectors:** Specificity ≤ `0,2,0`? Không nesting > 3 cấp?
- [ ] **Animate:** Chỉ `transform`, `opacity`, `filter`?
- [ ] **Transition:** List rõ thuộc tính, không dùng `all`?
- [ ] **will-change:** Chỉ trên phần tử animate, ≤ 15 cùng lúc?
- [ ] **Mobile-first:** Base = mobile, `min-width` mở rộng?
- [ ] **Media queries:** Dùng breakpoints chuẩn, gom theo component?
- [ ] **DRY:** Kiểm tra shared classes `08-cross-section-consistency.md`?
- [ ] **`:root`:** Không khai báo lại tokens đã có trong `variables.css`?
- [ ] **Shorthand:** Dùng `inset`, `margin-inline`, `gap` khi được?
- [ ] **No `!important`:** Trừ `prefers-reduced-motion`?
- [ ] **Font-size:** Dùng `rem`/`clamp()`, không hardcode `px`?

---

## Tài Liệu Liên Quan

- `02-frontend-css-js.md` — CSS architecture, container system, animation rules
- `06-performance.md` — Loading strategy, JS budget, image optimization
- `08-cross-section-consistency.md` — Shared UI classes (card, btn, header)
- `docs/ARCH_DESIGN_TOKENS.md` — Full token reference
