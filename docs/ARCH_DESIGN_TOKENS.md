# ARCH_DESIGN_TOKENS — Hệ Thống Thiết Kế

> **Dự án:** Website XANH - Design & Build
> **Phiên bản:** 2.0 | **Cập nhật:** 2026-03-12
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

### Brand Colors (Layer 2 — XANH Overrides)

| Token | Hex | Open Props base | Vai trò |
|---|---|---|---|
| `--color-primary` | `#14513D` | — | Xanh đậm chủ đạo: nav, footer, mảng khối lớn |
| `--color-primary-light` | `#1d7a5a` | — | Hover states, subtle backgrounds |
| `--color-primary-dark` | `#0a2e22` | — | Gradient deep, pressed states |
| `--color-accent` | `#FF8A00` | `--orange-7` | CTA buttons, điểm nhấn, borders hover |
| `--color-accent-hover` | `#E67A00` | `--orange-8` | CTA hover state |

### Neutral Scale (Layer 2 — Extended)

| Token | Hex | Open Props | Vai trò |
|---|---|---|---|
| `--color-white` | `#FFFFFF` | `--gray-0` | Nền sáng, text trên nền tối |
| `--color-light` | `#F3F4F6` | `--gray-1` | Nền section xen kẽ |
| `--color-beige` | `#D8C7A3` | — | Nền câu chuyện, mộc mạc, luxury warmth |
| `--color-gray-100` | `#F9FAFB` | `--gray-1` | Card background nhẹ nhất |
| `--color-gray-200` | `#E5E7EB` | `--gray-3` | Borders, dividers |
| `--color-gray-400` | `#9CA3AF` | `--gray-5` | Placeholder text |
| `--color-gray-600` | `#4B5563` | `--gray-7` | Secondary text |
| `--color-gray-800` | `#1F2937` | `--gray-9` | Body text chính |
| `--color-gray-900` | `#111827` | `--gray-10` | Headlines |

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

  /* === Neutrals === */
  --color-white: #FFFFFF;
  --color-light: #F3F4F6;
  --color-beige: #D8C7A3;
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
| **Heading** | Founders Grotesk | 500 (Medium), 700 (Bold) | Self-hosted OTF |
| **Body** | Inter | Variable 100-900 | Self-hosted TTF |

### Type Scale (Open Props `--font-size-*` + Custom)

| Token | Value | Line Height | Font | Sử dụng |
|---|---|---|---|---|
| `--text-hero` | `clamp(2.5rem, 5vw, 4.5rem)` | 1.1 | Founders Bold | Hero headline |
| `--text-h1` | `var(--font-size-7)` ≈ `clamp(2rem, 4vw, 3.5rem)` | 1.2 | Founders Bold | Section title |
| `--text-h2` | `var(--font-size-6)` ≈ `clamp(1.5rem, 3vw, 2.5rem)` | 1.25 | Founders Medium | Sub-section |
| `--text-h3` | `var(--font-size-5)` ≈ `clamp(1.25rem, 2.5vw, 1.75rem)` | 1.3 | Founders Medium | Card title |
| `--text-h4` | `var(--font-size-4)` = `1.25rem` | 1.4 | Inter SemiBold | Small heading |
| `--text-body-lg` | `var(--font-size-3)` = `1.125rem` | 1.7 | Inter Regular | Lead text |
| `--text-body` | `var(--font-size-2)` = `1rem` | 1.6 | Inter Regular | Body text |
| `--text-small` | `var(--font-size-1)` = `0.875rem` | 1.5 | Inter Regular | Captions |
| `--text-xs` | `var(--font-size-0)` = `0.75rem` | 1.4 | Inter Regular | Tags, badges |

```css
:root {
  --font-heading: 'FoundersGrotesk', var(--font-sans);
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

### 9.1 Component Tokens (Layer 3 — Semantic Binding)

Thay vì mỗi component tự chọn color/spacing, dùng **semantic tokens** gắn kết:

```css
:root {
  /* === BACKGROUND TOKENS === */
  --bg-page: var(--color-white);
  --bg-section-alt: var(--color-light);        /* Sections xen kẽ */
  --bg-section-dark: var(--color-primary);     /* Section tối (counter, CTA) */
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

### 9.3 Color Usage Rules (Consistency Matrix)

| Context | Background | Text | Accent |
|---|---|---|---|
| **Default section** | `--bg-page` | `--text-default` | `--color-accent` |
| **Alternating section** | `--bg-section-alt` | `--text-default` | `--color-accent` |
| **Dark section** | `--bg-section-dark` | `--text-on-dark` | `--color-accent` |
| **Card** | `--card-bg` | `--text-default` | `--color-accent` |
| **CTA button** | `--btn-primary-bg` | `--btn-primary-text` | — |
| **Nav** | `--nav-bg` | `--nav-link-color` | `--nav-link-active` |
| **Footer** | `--footer-bg` | `--footer-text` | `--color-accent` |

### 9.4 Background Alternation Pattern ★
```
Section 1 (Hero):      bg-section-dark (primary) — FULL IMPACT
Section 2 (4 Xanh):    bg-page (white)           — BREATHE
Section 3 (Counter):   bg-section-dark (primary) — IMPACT
Section 4 (Before):    bg-section-alt (light)     — SOFT
Section 5 (Process):   bg-page (white)           — BREATHE
Section 6 (Testi):     bg-section-alt (light)     — SOFT
Section 7 (CTA):       bg-section-dark (primary) — CLOSE IMPACT
```

> **Quy tắc:** KHÔNG bao giờ có 2 sections cùng màu nền liền nhau.
> Luôn xen kẽ: Dark → Light → Alt → Light → Dark

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
