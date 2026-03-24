# ARCH_DESIGN_TOKENS — Hệ Thống Thiết Kế

> **Dự án:** Website XANH - Design & Build
> **Phiên bản:** 2.1 | **Cập nhật:** 2026-03-13
> **Foundation:** Open Props (CSS Variables) + Custom Brand Tokens
> **Tham chiếu:** [Brand Guideline PDF](./brand_guideline/BRAND%20GUIDELINE_XANH.pdf)

---

## 0. Architecture — Layered Token System

```
┌──────────────────────────────────────────────────┐
│  Layer 3: COMPONENT TOKENS (Semantic binding)    │
│  --btn-bg, --card-shadow, --hero-padding         │
│  → Gắn kết tokens vào components cụ thể         │
├──────────────────────────────────────────────────┤
│  Layer 2: BRAND TOKENS (XANH overrides)          │
│  --color-primary, --font-heading, --space-section│
│  → Brand-specific values, override Open Props    │
├──────────────────────────────────────────────────┤
│  Layer 1: OPEN PROPS (Foundation)                │
│  --size-*, --font-size-*, --ease-*, --shadow-*   │
│  → Proven defaults, easing, gradients, shadows   │
└──────────────────────────────────────────────────┘
```

### CSS Load Order
```css
/* 1. Open Props — foundation tokens (CDN hoặc local) */
@import 'https://unpkg.com/open-props';
@import 'https://unpkg.com/open-props/normalize.min.css';

/* 2. variables.css — Brand overrides + component tokens */
/* 3. main.css → components.css → utilities.css → responsive.css */
```

> **Tại sao 3 layers?** Đây là giải pháp cho **tính đồng bộ (consistency)**.
> Mọi component đều dùng chung tokens → đổi 1 biến = thay đổi toàn bộ website.

---

## 1. Color Palette

### Color Ratio System — 60 / 25 / 10 / 5 ★★★

> **Nguồn:** Brand & Visual Style Guide chính thức của XANH.
> Hệ thống màu được xây dựng nhằm cân bằng giữa nhận diện thương hiệu và trải nghiệm thị giác.
> Hệ màu dựa trên sự cân bằng giữa **kỹ thuật, kiến trúc và cảm xúc**.

### Ý Nghĩa Thương Hiệu Từng Màu (Brand & Visual Style Guide)

| Màu | Hex | Ý nghĩa thương hiệu |
|-----|-----|---------------------|
| **Green** | `#14513D` | Màu xanh đậm thể hiện **nền tảng kỹ thuật và độ tin cậy** — giữ vai trò chủ đạo |
| **Orange (Cam)** | `#FF8A00` | Tạo **điểm nhấn năng lượng và hành động** — gợi sự quyết đoán, nhiệt huyết |
| **Beige (Tông Be)** | `#D8C7A3` | Gợi **chất liệu kiến trúc tự nhiên** — sự ấm áp, sang trọng tinh tế |
| **White (Trắng)** | `#FFFFFF` | Đảm bảo sự **rõ ràng, hiện đại và tính hệ thống** trong toàn bộ nhận diện |
| **Black (Đen)** | `#000000` | Dùng linh hoạt cho **typography & line system** — không tính vào tỷ lệ màu thương hiệu |

### Bảng Tỷ Lệ Phân Bổ Màu

| Tỷ lệ | Màu | Hex | Vai trò | Áp dụng cụ thể |
|-------|------|-----|---------|----------------|
| **60%** | Green | `#14513D` | **Chủ đạo** — kết hợp gam trung tính tạo chiều sâu không gian | Nav, footer, hero, dark sections, mảng khối lớn |
| **25%** | Beige | `#D8C7A3` | **Gam trung tính ấm** — luxury warmth, chuyển tiếp giữa các section | Section xen kẽ, testimonial, values, process, card backgrounds |
| **10%** | White | `#FFFFFF` | **Bố cục thoáng** — breathing room, điểm nghỉ thị giác | 1-2 sections nhẹ, card content areas, input fields |
| **5%** | Orange | `#FF8A00` | **Điểm nhấn** — thu hút sự chú ý vào hành động | CTA buttons, active states, hover borders, counter numbers |
| _linh hoạt_ | Black | `#000000` | **Typography & line system** — không tính vào tỷ lệ màu | Headlines, body text, borders, dividers |

```
┌─────────────────────────────────────────────────────────────┐
│ 60% GREEN ████████████████████████████████████              │
│ 25% BEIGE ███████████████                                   │
│ 10% WHITE ██████                                            │
│  5% ORANGE ███                                              │
│  ∞  BLACK  (typography & lines — không tính tỷ lệ)         │
└─────────────────────────────────────────────────────────────┘
```

> **Quy tắc vàng:** Khi scroll toàn bộ trang, user phải cảm nhận được
> **Xanh đậm là màu chủ đạo**, Beige tạo sự ấm áp, White chỉ xuất hiện
> như khoảng thở nhỏ, và Cam chỉ ở các nút CTA/điểm nhấn.

---

### Brand Colors (Layer 2 — XANH Overrides)

| Token | Hex | Open Props base | Vai trò |
|---|---|---|---|
| `--color-primary` | `#14513D` | — | Xanh đậm chủ đạo: nav, footer, mảng khối lớn |
| `--color-primary-light` | `#1d7a5a` | — | Hover states, subtle backgrounds |
| `--color-primary-dark` | `#0a2e22` | — | Gradient deep, pressed states |
| `--color-accent` | `#FF8A00` | `--orange-7` | CTA buttons, điểm nhấn, borders hover |
| `--color-accent-hover` | `#E67A00` | `--orange-8` | CTA hover state |

### Neutral Scale (Layer 2 — Extended)

| Token | Hex | Open Props | Vai trò | Tỷ lệ |
|---|---|---|---|---|
| `--color-white` | `#FFFFFF` | `--gray-0` | Nền sáng — breathing room, chỉ 1-2 sections | **10%** |
| `--color-light` | `#F3F4F6` | `--gray-1` | Nền nhẹ — tính vào nhóm White | (thuộc 10%) |
| `--color-beige` | `#D8C7A3` | — | Nền ấm — luxury warmth, section xen kẽ chính | **25%** |
| `--color-black` | `#000000` | — | Typography & line system | _linh hoạt_ |
| `--color-gray-100` | `#F9FAFB` | `--gray-1` | Card background nhẹ nhất | — |
| `--color-gray-200` | `#E5E7EB` | `--gray-3` | Borders, dividers | — |
| `--color-gray-400` | `#9CA3AF` | `--gray-5` | Placeholder text | — |
| `--color-gray-600` | `#4B5563` | `--gray-7` | Secondary text | — |
| `--color-gray-800` | `#1F2937` | `--gray-9` | Body text chính | — |
| `--color-gray-900` | `#111827` | `--gray-10` | Headlines | — |

### Semantic Colors

| Token | Hex | Vai trò |
|---|---|---|
| `--color-success` | `#10B981` | Validation OK, "Đã bàn giao" |
| `--color-warning` | `#F59E0B` | "Đang thi công" |
| `--color-error` | `#EF4444` | Form validation lỗi |

### CSS Declaration
```css
:root {
  /* === XANH Brand Colors === */
  --color-primary: #14513D;
  --color-primary-light: #1d7a5a;
  --color-primary-dark: #0a2e22;
  --color-accent: #FF8A00;
  --color-accent-hover: #E67A00;

  /* === Neutrals (theo Color Ratio 60-25-10-5) === */
  --color-white: #FFFFFF;       /* 10% — breathing room */
  --color-light: #F3F4F6;       /* thuộc nhóm 10% */
  --color-beige: #D8C7A3;       /* 25% — warm neutral */
  --color-black: #000000;        /* linh hoạt — typography & lines */
  --color-gray-100: #F9FAFB;
  --color-gray-200: #E5E7EB;
  --color-gray-400: #9CA3AF;
  --color-gray-600: #4B5563;
  --color-gray-800: #1F2937;
  --color-gray-900: #111827;

  /* === Semantic === */
  --color-success: #10B981;
  --color-warning: #F59E0B;
  --color-error: #EF4444;
}
```

---

## 2. Typography

### Font Families
| Vai trò | Font | Weights loaded | File |
|---|---|---|---|
| **Heading** | Inter | 600 (SemiBold), 700 (Bold) | Self-hosted Variable TTF |
| **Body** | Inter | Variable 100-900 | Self-hosted TTF |

### Type Scale (Open Props `--font-size-*` + Custom)

| Token | Value | Line Height | Font | Sử dụng |
|---|---|---|---|---|
| `--text-hero` | `clamp(2.5rem, 5vw, 4.5rem)` | 1.1 | Inter Bold | Hero headline |
| `--text-h1` | `var(--font-size-7)` ≈ `clamp(2rem, 4vw, 3.5rem)` | 1.2 | Inter Bold | Section title |
| `--text-h2` | `var(--font-size-6)` ≈ `clamp(1.5rem, 3vw, 2.5rem)` | 1.25 | Inter SemiBold | Sub-section |
| `--text-h3` | `var(--font-size-5)` ≈ `clamp(1.25rem, 2.5vw, 1.75rem)` | 1.3 | Inter SemiBold | Card title |
| `--text-h4` | `var(--font-size-4)` = `1.25rem` | 1.4 | Inter SemiBold | Small heading |
| `--text-body-lg` | `var(--font-size-3)` = `1.125rem` | 1.7 | Inter Regular | Lead text |
| `--text-body` | `var(--font-size-2)` = `1rem` | 1.6 | Inter Regular | Body text |
| `--text-small` | `var(--font-size-1)` = `0.875rem` | 1.5 | Inter Regular | Captions |
| `--text-xs` | `var(--font-size-0)` = `0.75rem` | 1.4 | Inter Regular | Tags, badges |

```css
:root {
  --font-heading: 'Inter', var(--font-sans);
  --font-body: 'Inter', var(--font-sans);

  --text-hero: clamp(2.5rem, 5vw, 4.5rem);
  --text-h1: var(--font-size-7, clamp(2rem, 4vw, 3.5rem));
  --text-h2: var(--font-size-6, clamp(1.5rem, 3vw, 2.5rem));
  --text-h3: var(--font-size-5, clamp(1.25rem, 2.5vw, 1.75rem));
  --text-h4: var(--font-size-4, 1.25rem);
  --text-body-lg: var(--font-size-3, 1.125rem);
  --text-body: var(--font-size-2, 1rem);
  --text-small: var(--font-size-1, 0.875rem);
  --text-xs: var(--font-size-0, 0.75rem);
}
```

---

## 3. Spacing System (Open Props `--size-*`)

| Token | Value | Open Props | Sử dụng |
|---|---|---|---|
| `--space-1` | `0.25rem` (4px) | `--size-1` | Micro gaps |
| `--space-2` | `0.5rem` (8px) | `--size-2` | Icon gaps, tag padding |
| `--space-3` | `0.75rem` (12px) | `--size-3` | Input padding |
| `--space-4` | `1rem` (16px) | `--size-4` | Card padding |
| `--space-6` | `1.5rem` (24px) | `--size-6` | Section padding mobile |
| `--space-8` | `2rem` (32px) | `--size-7` | Between elements |
| `--space-12` | `3rem` (48px) | `--size-9` | Between sections mobile |
| `--space-16` | `4rem` (64px) | `--size-10` | Between sections tablet |
| `--space-20` | `5rem` (80px) | `--size-11` | Between sections desktop |
| `--space-24` | `6rem` (96px) | `--size-12` | Hero padding |
| `--space-32` | `8rem` (128px) | `--size-13` | Maximum spacing |

### Section Spacing (Consistent rhythm)
```css
.section {
  padding-block: var(--space-12);   /* Mobile: 48px */
  padding-inline: var(--space-6);   /* Mobile: 24px */
}
@media (min-width: 768px) {
  .section { padding-block: var(--space-16); padding-inline: var(--space-8); }
}
@media (min-width: 1024px) {
  .section { padding-block: var(--space-20); padding-inline: var(--space-8); }
}
```

---

## 4. Grid & Layout

| Token | Value |
|---|---|
| `--container-sm` | `640px` |
| `--container-md` | `768px` |
| `--container-lg` | `1024px` |
| `--container-xl` | `1280px` |
| `--container-max` | `1440px` |

```css
.grid {
  display: grid;
  gap: var(--space-6);
}
.grid-2 { grid-template-columns: repeat(2, 1fr); }
.grid-3 { grid-template-columns: repeat(3, 1fr); }
.grid-4 { grid-template-columns: repeat(4, 1fr); }

@media (max-width: 768px) {
  .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
}
@media (min-width: 769px) and (max-width: 1024px) {
  .grid-3, .grid-4 { grid-template-columns: repeat(2, 1fr); }
}
```

---

## 5. Responsive Breakpoints

| Token | Value | Target |
|---|---|---|
| `--bp-sm` | `640px` | Small phones (landscape) |
| `--bp-md` | `768px` | Tablets |
| `--bp-lg` | `1024px` | Laptops |
| `--bp-xl` | `1280px` | Desktops |
| `--bp-2xl` | `1536px` | Large screens |

---

## 6. Shadows & Effects (Open Props `--shadow-*`)

| Token | Value | Open Props | Sử dụng |
|---|---|---|---|
| `--shadow-sm` | `0 1px 2px rgba(0,0,0,0.05)` | `--shadow-1` | Cards mặc định |
| `--shadow-md` | `0 4px 6px -1px rgba(0,0,0,0.07)` | `--shadow-3` | Cards hover |
| `--shadow-lg` | `0 10px 25px -3px rgba(0,0,0,0.1)` | `--shadow-4` | Elevated elements |
| `--shadow-xl` | `0 20px 40px -5px rgba(0,0,0,0.15)` | `--shadow-5` | Modals, popups |

---

## 7. Border Radius (Open Props `--radius-*`)

| Token | Value | Open Props | Sử dụng |
|---|---|---|---|
| `--radius-sm` | `4px` | `--radius-2` | Buttons, inputs |
| `--radius-md` | `8px` | `--radius-3` | Cards |
| `--radius-lg` | `12px` | `--radius-4` | Large cards, modals |
| `--radius-xl` | `16px` | `--radius-5` | Featured sections |
| `--radius-full` | `9999px` | `--radius-round` | Pill buttons, avatars |

---

## 8. Easing & Transitions (Open Props `--ease-*`) ★

> Open Props cung cấp **easing curves chuyên nghiệp** — đây là yếu tố tạo cảm giác luxury.

| Token | Value | Open Props | Sử dụng |
|---|---|---|---|
| `--ease-in-out` | `cubic-bezier(.42, 0, .58, 1)` | `--ease-in-out-3` | General UI transitions |
| `--ease-out` | `cubic-bezier(.2, 0, 0, 1)` | `--ease-out-3` | Entrances (elements appearing) |
| `--ease-in` | `cubic-bezier(1, 0, .8, 0)` | `--ease-in-3` | Exits (elements leaving) |
| `--ease-elastic` | `cubic-bezier(.5, 1.25, .75, 1.25)` | `--ease-elastic-3` | Playful bounces (CTA hover) |
| `--ease-squish` | `cubic-bezier(.5, -.3, .1, 1.5)` | `--ease-squish-3` | Scale micro-animations |
| `--transition-fast` | `150ms var(--ease-out)` | — | Button states |
| `--transition-base` | `300ms var(--ease-in-out)` | — | Cards, general |
| `--transition-slow` | `500ms var(--ease-out)` | — | Section reveals |
| `--transition-luxury` | `600ms var(--ease-out)` | — | Hero, page transitions |

---

## 9. Design Consistency System ★★★

> **Đây là giải pháp cho tính đồng bộ thiết kế.**
> **Tuân thủ Color Ratio 60-25-10-5** (xem §1 Color Ratio System).

### 9.1 Component Tokens (Layer 3 — Semantic Binding)

Thay vì mỗi component tự chọn color/spacing, dùng **semantic tokens** gắn kết:

```css
:root {
  /* === BACKGROUND TOKENS (Color Ratio 60-25-10-5) === */
  --bg-page: var(--color-primary);             /* 60% Green — chủ đạo */
  --bg-section-warm: var(--color-beige);       /* 25% Beige — sections ấm */
  --bg-section-light: var(--color-white);      /* 10% White — breathing room */
  --bg-section-dark: var(--color-primary-dark); /* Deep green — gradient, footer */
  --bg-card: var(--color-white);
  --bg-card-hover: var(--color-gray-100);
  --bg-overlay: rgba(0, 0, 0, 0.6);           /* Modals, lightbox */

  /* === TEXT TOKENS === */
  --text-default: var(--color-gray-800);
  --text-heading: var(--color-gray-900);
  --text-muted: var(--color-gray-600);
  --text-on-dark: var(--color-white);
  --text-on-accent: var(--color-white);
  --text-link: var(--color-primary);
  --text-link-hover: var(--color-primary-light);

  /* === BORDER TOKENS === */
  --border-default: var(--color-gray-200);
  --border-focus: var(--color-accent);
  --border-error: var(--color-error);

  /* === BUTTON TOKENS === */
  --btn-primary-bg: var(--color-accent);
  --btn-primary-hover: var(--color-accent-hover);
  --btn-primary-text: var(--color-white);
  --btn-outline-border: var(--color-primary);
  --btn-outline-text: var(--color-primary);
  --btn-outline-hover-bg: var(--color-primary);
  --btn-outline-hover-text: var(--color-white);

  /* === CARD TOKENS === */
  --card-bg: var(--bg-card);
  --card-border: var(--border-default);
  --card-shadow: var(--shadow-sm);
  --card-shadow-hover: var(--shadow-lg);
  --card-radius: var(--radius-md);
  --card-padding: var(--space-6);
  --card-transition: var(--transition-base);

  /* === SECTION TOKENS === */
  --section-padding-y: var(--space-20);
  --section-padding-x: var(--space-8);
  --section-gap: var(--space-12);              /* Giữa title và content */
  --section-title-mb: var(--space-4);          /* Title → subtitle */

  /* === NAV TOKENS === */
  --nav-height: 72px;
  --nav-bg: var(--color-white);
  --nav-bg-scroll: var(--color-white);
  --nav-shadow-scroll: var(--shadow-md);
  --nav-link-color: var(--color-gray-800);
  --nav-link-active: var(--color-accent);

  /* === FOOTER TOKENS === */
  --footer-bg: var(--color-primary);
  --footer-text: var(--color-white);
  --footer-link: var(--color-gray-200);
  --footer-link-hover: var(--color-accent);

  /* === INPUT TOKENS === */
  --input-bg: var(--color-white);
  --input-border: var(--border-default);
  --input-border-focus: var(--border-focus);
  --input-border-error: var(--border-error);
  --input-radius: var(--radius-sm);
  --input-padding: var(--space-3) var(--space-4);
  --input-height: 48px;                       /* iOS zoom prevention */
}
```

### 9.2 Section Rhythm Pattern (Consistent Spacing)
```
┌─── NAV (72px fixed) ────────────────────────────┐
│                                                   │
├─── SECTION (light bg) ──────────────────────────┤
│  padding: var(--section-padding-y) var(--section-padding-x)
│  ┌── Section Title (--text-h1, mb: --section-title-mb) ─┐
│  │  Subtitle (--text-body-lg, --text-muted)              │
│  └───────────────────────────────────────────────────────┘
│  gap: var(--section-gap)
│  ┌── Content (grid/flex) ─────────────────────────────┐
│  └────────────────────────────────────────────────────┘
├─── SECTION (alt bg: --bg-section-alt) ──────────┤
│  (Same spacing pattern — IDENTICAL rhythm)       │
├─── SECTION (dark bg: --bg-section-dark) ────────┤
│  (Same spacing, text: --text-on-dark)            │
├─── FOOTER ──────────────────────────────────────┤
└──────────────────────────────────────────────────┘
```

### 9.3 Color Usage Rules (Consistency Matrix — Theo Tỷ Lệ 60-25-10-5)

| Context | Background | Text | Accent | Nhóm tỷ lệ |
|---|---|---|---|---|
| **Green section** (chủ đạo) | `--bg-page` (primary) | `--text-on-dark` | `--color-accent` | **60%** |
| **Warm section** (ấm) | `--bg-section-warm` (beige) | `--text-default` | `--color-accent` | **25%** |
| **Light section** (thở) | `--bg-section-light` (white) | `--text-default` | `--color-accent` | **10%** |
| **Card trên nền green** | `--card-bg` (white) | `--text-default` | `--color-accent` | — |
| **Card trên nền beige** | `--card-bg` (white) | `--text-default` | `--color-accent` | — |
| **CTA button** | `--btn-primary-bg` (orange) | `--btn-primary-text` | — | **5%** |
| **Nav** | `--color-primary` | `--text-on-dark` | `--nav-link-active` | 60% |
| **Footer** | `--footer-bg` (dark green) | `--footer-text` | `--color-accent` | 60% |

### 9.4 Background Alternation Pattern ★ (Color Ratio 60-25-10-5)

```
┌── HERO ──────────────── GREEN (#14513D)  ── 60%  ── FULL IMPACT
├── Empathy / Story ───── BEIGE (#D8C7A3)  ── 25%  ── WARM
├── Values ────────────── GREEN (#14513D)  ── 60%  ── DEEP
├── Counter ───────────── GREEN (dark grad) ── 60%  ── IMPACT
├── Projects ──────────── WHITE (#FFFFFF)  ── 10%  ── BREATHE ★
├── Services ──────────── BEIGE (#D8C7A3)  ── 25%  ── WARM
├── Process ───────────── GREEN (#14513D)  ── 60%  ── DEEP
├── Testimonials ──────── BEIGE (#D8C7A3)  ── 25%  ── WARM
├── Partners ──────────── WHITE (#FFFFFF)  ── 10%  ── BREATHE ★
├── CTA Final ─────────── GREEN (dark grad) ── 60%  ── CLOSE
└── FOOTER ────────────── GREEN (#0a2e22)  ── 60%  ── ANCHOR
```

> **Quy tắc tỷ lệ:**
> - GREEN chiếm ~6/11 sections (Hero, Values, Counter, Process, CTA, Footer) = **~60%**
> - BEIGE chiếm ~3/11 sections (Empathy, Services, Testimonials) = **~25%**
> - WHITE chiếm ~2/11 sections (Projects, Partners) = **~10%**
> - ORANGE chỉ ở CTA buttons & micro-accents = **~5%**
>
> **KHÔNG bao giờ có 2 sections cùng nhóm màu liền nhau.**

### 9.5 Component Consistency Checklist

Mỗi component MỚI phải tuân thủ:
- [ ] Dùng `--card-radius` cho border-radius (KHÔNG hardcode)
- [ ] Dùng `--card-shadow` / `--card-shadow-hover` (KHÔNG tự viết shadow)
- [ ] Dùng `--transition-base` cho hover states (KHÔNG tự đặt timing)
- [ ] Dùng `--text-heading` / `--text-default` (KHÔNG tự chọn gray)
- [ ] Dùng `--space-*` tokens cho gap/padding (KHÔNG hardcode px)
- [ ] Hover state: `transform: translateY(-2px)` + `--card-shadow-hover` (THỐNG NHẤT)
- [ ] Focus state: `outline: 2px solid var(--border-focus)` + `outline-offset: 2px`
- [ ] Typography: Heading = `--font-heading`, Body = `--font-body` (KHÔNG mix)

---

## Tài Liệu Liên Quan

- `ARCH_UI_PATTERNS.md` — 27 Component implementations using these tokens
- `GOV_CODING_STANDARDS.md` — How to use tokens in code
- `REF_BRAND_ASSETS.md` — Font files & logo references
- `TRACK_DECISIONS.md` — ADR-007: Library stack + ADR-008: Open Props
