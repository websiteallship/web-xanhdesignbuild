---
description: UX/UI design rules for XANH website. Apply when creating or modifying page layouts, components, or user flows.
globs: wp-content/themes/xanh-theme/**/*.{php,css}
---

# UX/UI Design Rules

## Design Philosophy — Warm Luxury
- **Restraint** — Ít chi tiết, chất lượng cao. Mỗi element phải có lý do
- **Breathing Room** — Content/whitespace ratio 40/60. Khoảng trắng = sang trọng
- **Subtlety** — Hiệu ứng tinh tế, hover nhẹ, transitions mượt. KHÔNG bounce/shake
- **Consistency** — Component tokens bắt buộc (§9 `ARCH_DESIGN_TOKENS.md`). Cross-section element sync: `.agent/rules/08-cross-section-consistency.md`
- **Warmth** — Tông beige ấm, ảnh có con người, copy mời gọi (không lạnh lẽo)
- **Storytelling:** Aspiration → Empathy → Solution → Proof → Invitation (bắt đầu từ ước mơ, KHÔNG nỗi đau)
- **Full direction:** `docs/ARCH_LUXURY_VISUAL_DIRECTION.md`

## Page Specifications
| Page | Sections | Doc |
|---|---|---|
| Homepage | 10 sections | `docs/PAGE_HOME.md` |
| About | 8 sections | `docs/PAGE_ABOUT.md` |
| Portfolio Grid + Detail | 10 sections | `docs/PAGE_PORTFOLIO.md` |
| Green Solution | 6 sections | `docs/PAGE_GREEN_SOLUTION.md` |
| Blog List + Detail | — | `docs/PAGE_BLOG.md` |
| Contact | 3 sections + counter | `docs/PAGE_CONTACT.md` |
| Header/Footer/404/Thank-you | — | `docs/PAGE_GLOBAL_ELEMENTS.md` |

## Layout Rules
- Container: `max-width: 1280px` (hero: 1440px full-width, text: 800px)
- Grid: CSS Grid + Flexbox, `gap: var(--space-6)`
- Section padding: `var(--section-padding-y)` × `var(--section-padding-x)` (80px × 32px desktop)
- Section gap (title → content): `var(--section-gap)` (48px)
- Mobile: 1 column, `var(--space-6)` padding | Tablet: 2 cols | Desktop: 3-4 cols

## Section Background Rotation ★ (Color Ratio 60-25-10-5)
```
GREEN (#14513D)  → BEIGE (#D8C7A3) → GREEN → GREEN(grad) → WHITE (#FFF)
→ BEIGE → GREEN → BEIGE → WHITE → GREEN(grad) → GREEN footer
```
> **Tỷ lệ:** GREEN ~60% (6/11 sections) / BEIGE ~25% (3/11) / WHITE ~10% (2/11) / ORANGE chỉ CTA/accents ~5%
> **KHÔNG** bao giờ 2 sections cùng nhóm màu liền nhau
> **Nguồn:** Brand & Visual Style Guide chính thức — xem `docs/ARCH_DESIGN_TOKENS.md` §1

## Component Library (27 items)
Full specs: `docs/ARCH_UI_PATTERNS.md`
Key luxury interactions:
- **Card hover:** `translateY(-4px)` + `var(--card-shadow-hover)` — 400ms `--ease-out`
- **Image hover:** `scale(1.03)` with `overflow: hidden` — 600ms
- **Nav link:** Underline slide-in from left — 300ms
- **Stagger entrance:** GSAP fade-up, stagger 100ms between cards
- **Counter:** GSAP count-up 2000ms, trigger at `top 75%`
- **Skeleton:** Shimmer gradient on AJAX filter

## CTA Rules (Warm Luxury Style)
- Primary: `var(--btn-primary-bg)` (#FF8A00), min-width 200px
- Secondary: Outline style, `var(--color-primary)` border
- EVERY page: at least 1 CTA → Contact or Estimator
- Mobile: Full-width buttons, min height 48px
- Text: Inviting, NOT pushy — "Đặt Lịch Tư Vấn Riêng" / "Khám Phá Dự Toán Của Bạn"
- KHÔNG: "Liên hệ ngay!", "Đăng ký!", "Click here"

## Form UX
- Floating labels (float up on focus)
- Real-time validation (green = valid, red = error)
- Min input height: 48px (`var(--input-height)`)
- Progress indicator for multi-field forms
- Submit button: spinner on click, disable until response
- Success: Redirect to thank-you page (NOT inline only)
- Focus: `outline: 2px solid var(--border-focus); outline-offset: 2px`

## Image Guidelines
- Crop ratios: See `docs/FEATURE_IMAGE_SPECS.md`
- Before/After: MUST same camera angle, same lighting
- Editorial style: warm tone, natural light, detail shots (30% close-ups)
- All: WebP, lazy-loaded (except hero), `width`+`height` set
- Alt text: Descriptive + keyword — "Phòng khách biệt thự Nha Trang — XANH"
- Progressive reveal: `blur(20px) → blur(0)` on load

## Typography Luxury Rules
- Headings: `letter-spacing: -0.02em` (tighter = premium)
- Body: `letter-spacing: 0.01em` (slightly airy)
- Uppercase labels: `letter-spacing: 0.1em` (spaced = elegant)
- Paragraphs: `max-width: 65ch` (comfortable reading)
- Heading/Body weight contrast: Bold (700) vs Regular (400). KHÔNG mix

## Anti-Patterns ❌
- Bounce animation, color flash/blink, shake effect
- Text typing animation, confetti/particles
- 3D rotate (trừ Card Flip 4 Xanh)
- Quá nhiều animation cùng lúc
- Entrance duration > 1s
