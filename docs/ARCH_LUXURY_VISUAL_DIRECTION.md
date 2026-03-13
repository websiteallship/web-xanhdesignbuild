# ARCH_LUXURY_VISUAL_DIRECTION — Định Hướng Thiết Kế Cao Cấp

> **Dự án:** Website XANH - Design & Build
> **Phiên bản:** 1.1 | **Ngày tạo:** 2026-03-12 | **Cập nhật:** 2026-03-13
> **Positioning:** Warm Luxury — Tinh tế, Ấm áp, Đẳng cấp
> **Color Ratio:** 60% Green / 25% Beige / 10% White / 5% Orange (Brand Guide)

---

## 1. Visual Philosophy

> **"Luxury is in each detail."** — Hubert de Givenchy

### Nguyên tắc thiết kế XANH:

| Nguyên tắc | Mô tả | Ví dụ |
|---|---|---|
| **Restraint** | Ít chi tiết hơn, chất lượng cao hơn. Mỗi element đều có lý do | Trang Portfolio: ảnh lớn, ít text, để công trình nói |
| **Breathing Room** | Khoảng trắng không phải "trống" — nó thể hiện sự sang trọng | Section padding: min 80px (desktop), 48px (mobile) |
| **Subtlety** | Hiệu ứng tinh tế, không gây chú ý — nhưng thiếu thì thấy "rẻ" | Hover: translateY(-2px) + shadow, không bounce/shake |
| **Consistency** | Mọi trang phải cảm thấy thuộc về cùng 1 thương hiệu | Cùng spacing, cùng animation timing, cùng card style |
| **Warmth** | Sang trọng nhưng chào đón, không lạnh lẽo | Tông beige ấm, ảnh có con người, copy mời gọi |

---

## 2. Reference Benchmarks

### Websites tham khảo (visual direction):

| Website | Lý do tham khảo | Take-away cho XANH |
|---|---|---|
| **Aesop.com** | Warm luxury, editorial layout, typography excellence | Typography hierarchy, whitespace, warm tones |
| **Aman.com** | Resort luxury, large photography, minimal UI | Hero impact, image-led storytelling |
| **Cereal Magazine** | Editorial design, clean grid, sophisticated typography | Blog layout, content presentation |
| **Dezeen.com** | Architecture content, clean grid, professional | Portfolio grid, project detail layout |
| **B&O (bang-olufsen.com)** | Product luxury, smooth scroll, premium interactions | Micro-interactions, scroll animations |
| **Menu.as** | Scandinavian design, minimal, warm materials | Color palette usage, product photography |

### Lưu ý:
- Tham khảo LAYOUT và FEEL — KHÔNG copy style
- XANH có bản sắc riêng: Xanh đậm + Cam + Beige ấm

---

## 3. Typography Direction

### Hierarchy — Tạo Contrast Đẳng Cấp

```
HERO (Founders Grotesk Bold + clamp 56-72px)
  ↕  Contrast gap lớn
H1  (Founders Grotesk Bold + clamp 32-56px)
  ↕  
H2  (Founders Grotesk Medium + clamp 24-40px)
  ↕  
Body (Inter Regular 16-18px, line-height 1.6-1.7)
  ↕  
Small (Inter Regular 14px, --text-muted)
```

### Typography Rules cho Luxury:

| Rule | Mô tả |
|---|---|
| **Letter-spacing headings** | `letter-spacing: -0.02em` cho H1 (tighter = premium) |
| **Letter-spacing body** | `letter-spacing: 0.01em` cho body text (hơi thoáng = dễ đọc) |
| **Letter-spacing uppercase** | `letter-spacing: 0.1em` cho labels, tags, badges (spaced = elegant) |
| **Line-height** | Headings: 1.1-1.2 (tight). Body: 1.6-1.7 (airy). Sự tương phản tạo depth |
| **Max-width paragraphs** | `max-width: 65ch` — Luxury reading experience |
| **Font weight contrast** | Heading Bold (700) vs Body Regular (400) — KHÔNG dùng heading Medium cho body |

### Uppercase Usage:
- **NÊN:** Nav links, labels, badges, đội ngũ footer, category tags
- **KHÔNG:** Headlines, body text, CTA buttons (buttons dùng Title Case)

---

## 4. Whitespace Strategy — "Luxury Breathing Room"

### Vertical Rhythm

| Context | Mobile | Tablet | Desktop | Token |
|---|---|---|---|---|
| Section padding (top/bottom) | 48px | 64px | 80px | `--section-padding-y` |
| Section padding (sides) | 24px | 32px | 32px | `--section-padding-x` |
| Between sections | 0 (liền nhau) | 0 | 0 | — |
| Title → subtitle | 16px | 16px | 16px | `--section-title-mb` |
| Title group → content | 48px | 48px | 48px | `--section-gap` |
| Between cards | 24px | 24px | 24px | `--space-6` |
| Card internal padding | 24px | 24px | 32px | `--card-padding` |

### Horizontal Spaciousness

```css
/* Container max-width KHÔNG full-width */
.container { max-width: 1280px; margin-inline: auto; padding-inline: var(--space-6); }

/* Hero có thể full-width (1440px) */
.hero { max-width: 1440px; }

/* Text content luôn hẹp hơn — dễ đọc */
.content-text { max-width: 800px; margin-inline: auto; }
```

> **Key insight:** Luxury websites có tỷ lệ content/whitespace khoảng **40/60**.
> Website bình dân thường **70/30** — chật chội, thiếu thở.

---

## 5. Micro-Interactions — The Luxury Details

### 5.1 Hover States (Global)

| Element | Hover Effect | Duration | Easing |
|---|---|---|---|
| **CTA Button** | `background` darken + `translateY(-1px)` + shadow | 300ms | `--ease-out` |
| **Card** | `translateY(-4px)` + `--card-shadow-hover` | 400ms | `--ease-out` |
| **Nav link** | Underline slide-in from left (pseudo-element `scaleX 0→1`) | 300ms | `--ease-out` |
| **Image** | Subtle `scale(1.03)` with `overflow: hidden` on parent | 600ms | `--ease-out` |
| **Text link** | Color transition to `--color-accent` | 200ms | `--ease-out` |
| **Icon** | Subtle `scale(1.1)` + color change | 200ms | `--ease-out` |

### 5.2 Scroll Entrance Animations (GSAP)

| Pattern | Properties | Duration | Stagger | Trigger |
|---|---|---|---|---|
| **Fade Up** | `opacity: 0→1`, `y: 30→0` | 800ms | 100ms | `top 85%` |
| **Fade In** | `opacity: 0→1` (no movement) | 600ms | 0 | `top 90%` |
| **Slide Right** | `opacity: 0→1`, `x: -30→0` | 800ms | 150ms | `top 85%` |
| **Counter** | Count up from 0 | 2000ms | 0 | `top 75%` |
| **Parallax** | `y: -10% → 10%` (background) | Scrub | 0 | Section scroll |

### Stagger Pattern cho Cards:
```javascript
// Luxury cascading effect — cards appear one by one
gsap.from('.card', {
  scrollTrigger: { trigger: '.grid', start: 'top 85%' },
  opacity: 0, y: 30,
  duration: 0.8,
  stagger: 0.1,          // 100ms between each card
  ease: 'power2.out'
});
```

### 5.3 Page Transition Feel

```javascript
// Lenis smooth scroll — luxury momentum
const lenis = new Lenis({
  lerp: 0.07,             // Slower = more luxury (0.1 = standard, 0.05 = very smooth)
  smoothWheel: true,
  syncTouch: false,        // Native touch on mobile
});

// Note: Easing curves handled by GSAP (power2.out, power1.inOut)
// and Tailwind CSS transitions (ease-in, ease-out, ease-in-out)
```

### 5.4 Loading States

| State | Visual | Duration |
|---|---|---|
| **Preloader** | Logo XANH + subtle pulse → fade out | 1-2s (sessionStorage skip) |
| **Skeleton** | Shimmer gradient (light → beige → light) | Until AJAX response |
| **Image reveal** | Blur → sharp + slight scale up | 500ms on load |
| **Button loading** | Spinner icon replace text + disabled state | Until API response |

### 5.5 KHÔNG BAO GIỜ (Anti-patterns cho Luxury)

| ❌ Anti-pattern | Lý do |
|---|---|
| Bounce animation | Trẻ con, thiếu tinh tế |
| Color flash / blink | Rẻ tiền, gây khó chịu |
| Shake effect | Gây lo lắng, không tin cậy |
| Quá nhiều animation cùng lúc | Rối mắt, thiếu focus |
| Animation duration > 1s (entrance) | Làm user mất kiên nhẫn |
| 3D rotate cards (quá mức) | Gimmicky, trừ Card Flip 4 Xanh |
| Text typing animation | Quá chậm cho luxury user |
| Confetti / particles | Không phù hợp nội thất |

---

## 6. Photography & Image Direction

### Color Grading

```
Tông ấm (warm tone):
  - Shadows: nhẹ warm (#1a1612)
  - Midtones: natural, vàng nhẹ
  - Highlights: cream, không quá trắng
  - Saturation: 90-95% (hơi giảm = tinh tế)
  - Contrast: Vừa phải (không quá flat, không quá harsh)
```

### Composition Rules

| Rule | Áp dụng |
|---|---|
| **Rule of thirds** | Ảnh không gian nội thất |
| **Leading lines** | Dùng đường nét kiến trúc dẫn mắt |
| **Framing** | Dùng cửa, hành lang làm khung |
| **Lower angle** | Chụp hơi thấp → không gian cao ráo, đẳng cấp |
| **Detail shots** | 30% ảnh close-up: vật liệu, kỹ thuật, nội thất chi tiết |
| **Human scale** | Thỉnh thoảng có người trong không gian → tạo cảm xúc ấm áp |

### Before/After — Luxury Standard
- **CÙNG góc chụp** (tripod position, same focal length)
- **CÙNG thời điểm ánh sáng** (cùng giờ trong ngày)
- **3D render: warm lighting** tương đồng with real photo
- **Slider interaction:** Smooth, responsive, no jump

---

## 7. Color Application — Luxury Palette Usage (Color Ratio 60-25-10-5)

> **Nguồn:** Brand & Visual Style Guide chính thức.
> Tổng thể phân bổ: **60% Green – 25% Beige – 10% White – 5% Orange**.
> Black `#000000` dùng linh hoạt cho typography & line system, không tính vào tỷ lệ.

### Section Background Rotation (Theo Tỷ Lệ Brand Guide)

```
┌── HERO ──────────────────────────── #14513D (primary)      60% ── FULL IMPACT
├── Empathy / Story ───────────────── #D8C7A3 (beige)        25% ── WARM
├── Values ────────────────────────── #14513D (primary)      60% ── DEEP
├── Counter ───────────────────────── #14513D → #0a2e22 (grad) 60% ── IMPACT
├── Projects ──────────────────────── #FFFFFF (white)        10% ── BREATHE ★
├── Services ──────────────────────── #D8C7A3 (beige)        25% ── WARM
├── Process ───────────────────────── #14513D (primary)      60% ── DEEP
├── Testimonials ──────────────────── #D8C7A3 (beige)        25% ── WARM
├── Partners ──────────────────────── #FFFFFF (white)        10% ── BREATHE ★
├── CTA Final ─────────────────────── #14513D → #0a2e22 (grad) 60% ── CLOSE
└── FOOTER ────────────────────────── #0a2e22 (darker green) 60% ── ANCHOR
```

> **Kết quả tỷ lệ thực tế:**
> - **GREEN**: Hero + Values + Counter + Process + CTA + Footer = 6/11 sections ≈ **55-60%** ✅
> - **BEIGE**: Empathy + Services + Testimonials = 3/11 sections ≈ **25-27%** ✅
> - **WHITE**: Projects + Partners = 2/11 sections ≈ **10-18%** ✅
> - **ORANGE**: Chỉ CTA buttons & micro-accents ≈ **5%** ✅

### Beige Usage (★ Luxury Differentiator — 25% diện tích)

`--color-beige: #D8C7A3` là yếu tố tạo sự khác biệt luxury cho XANH.
Theo Brand Guide, Beige chiếm **25%** — gam trung tính ấm tạo chiều sâu không gian:

- **Empathy / Brand Story section** (chuyển tiếp sau Hero)
- **Services section** (dịch vụ, tạo không khí thân thiện)
- **Testimonial section** (phản hồi khách hàng, cảm xúc ấm áp)
- **Quote blocks** trong blog
- **Card backgrounds** trên nền Green (tạo contrast ấm)
- KHÔNG dùng cho: nền toàn trang, full-width images

### Accent Orange — Điểm nhấn tinh tế (5% diện tích)

`--color-accent: #FF8A00` chỉ dùng cho:
- CTA buttons (primary)
- Active states (nav link, filter tab)
- Hover borders
- Counter numbers
- **KHÔNG dùng cho:** text blocks, backgrounds, borders tĩnh

### Black — Typography & Line System (linh hoạt)

`#000000` dùng linh hoạt cho:
- Headlines trên nền sáng (White/Beige)
- Body text
- Borders, dividers, line decorations
- **Không tính vào tỷ lệ màu** brand

---

## 8. Perceived Performance — Cảm Giác Nhanh

> Luxury user kỳ vọng mọi thứ phải **mượt mà, tức thì**.

| Technique | Thực hiện | Tạo cảm giác |
|---|---|---|
| **Skeleton loading** | Shimmer gradient khi AJAX filter | "Đang tải" thay vì "trống trơn" |
| **Progressive image** | Blur placeholder → sharp on load | Ảnh "hiện dần" thay vì "nhảy vào" |
| **Staggered entrance** | Cards vào lần lượt 100ms stagger | "Mượt mà" thay vì "tất cả cùng lúc" |
| **Optimistic UI** | Form button → spinner ngay khi click | "Đã nhận" trước khi API response |
| **Preloader (first visit)** | Logo pulse 1.5s → fade out | Brand impression + hide initial paint |
| **Smooth scroll (Lenis)** | Momentum scrolling 60fps | "Như ứng dụng native" |
| **Transition between states** | Fade in/out 300ms, never instant swap | "Chuyển cảnh" thay vì "nhảy cóc" |

---

## Tài Liệu Liên Quan

- `ARCH_DESIGN_TOKENS.md` — §9 Design Consistency System
- `GOV_BRAND_VOICE.md` — Warm luxury tone, CTA patterns
- `GOV_UX_GUIDELINES.md` — UX philosophy
- `ARCH_UI_PATTERNS.md` — 27 component specifications
- `FEATURE_IMAGE_SPECS.md` — Image dimensions, crop ratios
