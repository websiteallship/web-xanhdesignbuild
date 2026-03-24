# Phân Tích Trùng Lặp JS — 7 Wireframe Files

## Tổng quan

| File | Dòng | Kích thước |
|------|------|------------|
| home-page.js | 1192 | 42.9 KB |
| portfolio-detail.js | 607 | 20.1 KB |
| about.js | 519 | 18.0 KB |
| blog.js | 411 | 13.1 KB |
| contact.js | 352 | 12.1 KB |
| portfolio.js | 242 | 10.9 KB |
| blog-detail.js | 191 | 5.5 KB |
| **Tổng** | **3514** | **~122.5 KB** |

---

## 1. Các Khối Code Bị Trùng Lặp

### 🔴 1.1 — Lenis Smooth Scroll (trùng 6/7 file)

Code gần như **giống hệt nhau** ở 6 file, chỉ khác giá trị `lerp` (0.07 hoặc 0.1):

| File | Lerp | Dòng |
|------|------|------|
| `home-page.js` → `XanhSmoothScroll` | 0.1 | 124–152 |
| `portfolio.js` → `initLenis()` | 0.1 | 57–69 |
| `portfolio-detail.js` → `initLenis()` | 0.07 | 59–79 |
| `about.js` → `initLenis()` | 0.1 | 65–82 |
| `blog.js` → `initLenis()` | 0.07 | 292–311 |
| `contact.js` → `initLenis()` | 0.07 | 293–312 |

> ⚠️ **~20 dòng × 6 file = ~120 dòng trùng lặp** thuần túy.

---

### 🔴 1.2 — Lucide Icons Init (trùng 5/7 file)

```js
// Xuất hiện lặp ở: home-page, portfolio, portfolio-detail, about, blog
if (typeof lucide !== 'undefined') {
  lucide.createIcons();
}
```

| File | Vị trí |
|------|--------|
| `home-page.js` → `XanhIcons.init()` | 16–22 |
| `portfolio.js` → `initLucide()` | 50–52 |
| `portfolio-detail.js` → `initLucide()` | 46–54 |
| `about.js` → `initLucide()` | 58–62 |
| `blog.js` → inline trong `init()` | 33–35 |

---

### 🔴 1.3 — `ANIM_DEFAULTS` Object (trùng 3/7 file)

Đối tượng cấu hình animation **copy-paste giống hệt** giữa 3 file:

```js
const ANIM_DEFAULTS = {
  fadeUp:    { opacity: 0, y: 40, duration: 0.8, ease: 'power2.out' },
  fadeLeft:  { opacity: 0, x: -40, duration: 0.8, ease: 'power2.out' },
  fadeRight: { opacity: 0, x: 40, duration: 0.8, ease: 'power2.out' },
  scaleIn:   { opacity: 0, scale: 0.95, duration: 0.6, ease: 'power2.out' },
  stagger:   0.1,
};
```

| File | Dòng |
|------|------|
| `portfolio.js` | 11–18 |
| `portfolio-detail.js` | 11–17 |
| `about.js` | 10–16 |

> ❗ Nếu cần đổi animation timing toàn site, phải sửa **3 file** — rất dễ quên file nào đó.

---

### 🟡 1.4 — Hero Reveal Pattern (trùng 5/7 file)

Cùng pattern: thêm class `is-loaded` cho background + `is-visible` cho text, chỉ khác CSS selector:

| File | Selector BG | Selector Anim |
|------|-------------|---------------|
| `portfolio.js` | `.portfolio-hero__bg` | `.portfolio-hero-el` |
| `portfolio-detail.js` | `.detail-hero__bg` | `.breadcrumb--hero, .detail-hero__title...` |
| `about.js` | `.about-hero__bg` | `.about-hero-el` |
| `blog.js` | `.blog-hero__bg` | `.blog-hero-el` |
| `contact.js` | `#hero-bg` | `.contact-hero-el` |

---

### 🟡 1.5 — Hero Parallax / GSAP Scale (trùng 3/7 file)

```js
gsap.fromTo(img, { scale: 1.06 }, {
  scale: 1, ease: 'none',
  scrollTrigger: { trigger: '...', start: 'top top', end: 'bottom top', scrub: 1 }
});
```

Xuất hiện ở: `portfolio.js`, `about.js`, `home-page.js`

---

### 🟡 1.6 — Back to Top Button (trùng 2/7 file)

Code **gần giống hệt** giữa `blog.js` (L269–287) và `contact.js` (L270–288):

```js
initBackToTop() {
  const btn = document.getElementById('back-to-top');
  if (!btn) return;
  // rAF throttled scroll listener + smooth scrollTo
}
```

---

### 🟡 1.7 — Cookie Consent (1 file duy nhất, nhưng nên dùng chung)

Chỉ có trong `contact.js` (L317–345), nhưng nếu triển khai toàn site sẽ cần copy sang mọi file — tạo thêm trùng lặp.

---

### 🟡 1.8 — Scroll Reveal / IntersectionObserver cho `.anim-fade-up` (trùng 4/7 file)

| File | Pattern |
|------|---------|
| `contact.js` → `initScrollAnimations()` | `.anim-fade-up, .anim-fade-left, .anim-fade-right` + IO |
| `blog.js` → `initScrollReveal()` | `.anim-fade-up` + IO |
| `portfolio.js` → `initEntranceAnimation()` | `.project-card.anim-fade-up` + IO |
| `portfolio-detail.js` → `initEntranceAnimations()` | `.anim-fade-up` + IO |

---

### 🟡 1.9 — Counter Animation (trùng 3/7 file)

Đếm số bằng animation (rAF hoặc GSAP), xuất hiện ở:
- `contact.js` → `initCounters()` (GSAP)
- `portfolio.js` → `initCounterAnimation()` (GSAP + ScrollTrigger)
- `portfolio-detail.js` → `initStatsCounter()` (rAF thuần)
- `home-page.js` → `XanhCTA._animateCounters()` (rAF thuần)

---

## 2. Tổng Kết Trùng Lặp

| Mức độ | Pattern | Số file ảnh hưởng | Dòng trùng ước tính |
|--------|---------|-------------------|---------------------|
| 🔴 Nghiêm trọng | Lenis init | 6/7 | ~120 |
| 🔴 Nghiêm trọng | Lucide init | 5/7 | ~25 |
| 🔴 Nghiêm trọng | ANIM_DEFAULTS | 3/7 | ~24 |
| 🟡 Đáng chú ý | Hero Reveal pattern | 5/7 | ~50 |
| 🟡 Đáng chú ý | Hero Parallax (GSAP) | 3/7 | ~30 |
| 🟡 Đáng chú ý | Back to Top | 2/7 | ~20 |
| 🟡 Đáng chú ý | Scroll Reveal IO | 4/7 | ~50 |
| 🟡 Đáng chú ý | Counter animation | 4/7 | ~60 |
| **Tổng** | | | **~380 dòng** |

> 🚨 Khoảng **380 dòng trùng lặp** trên tổng 3514 dòng (~11%). Nếu cần sửa logic chung (ví dụ Lenis config), phải sửa ở **6 file** — rủi ro inconsistency rất cao.

---

## 3. Giải Pháp Tối Ưu JS

### ✅ Phương án đề xuất: Tạo file `base.js` (shared module)

Đã tạo file dùng chung tại `wireframes/shared/base.js`, chứa toàn bộ logic lặp:

```
wireframes/
├── shared/
│   └── base.js               ← SHARED (~200 dòng)
├── homepage_02/
│   └── home-page.js          ← giảm ~100 dòng
├── portfolio/
│   ├── portfolio.js           ← giảm ~60 dòng
│   └── portfolio-detail.js    ← giảm ~60 dòng
├── about/
│   └── about.js               ← giảm ~60 dòng
├── blog/
│   ├── blog.js                ← giảm ~50 dòng
│   └── blog-detail.js         ← không đổi (ít trùng)
└── contact/
    └── contact.js             ← giảm ~60 dòng
```

### Nội dung `base.js`:

| Module | Chức năng |
|--------|-----------|
| `ANIM_DEFAULTS` | Animation config chuẩn hóa toàn site |
| `XanhBase.initLucide()` | Khởi tạo Lucide icons |
| `XanhBase.initLenis(options)` | Lenis smooth scroll (nhận `lerp` param) |
| `XanhBase.initHeroReveal(bgSelector, elSelector)` | Hero background + text reveal |
| `XanhBase.initHeroParallax(imgSelector, triggerSelector)` | GSAP hero parallax |
| `XanhBase.initScrollReveal(selector, options)` | IntersectionObserver fade-in |
| `XanhBase.initBackToTop(btnId)` | Back to top button |
| `XanhBase.initCookieConsent()` | Cookie consent banner |
| `XanhBase.animateCounters(selector, options)` | Counter animation (GSAP hoặc rAF) |
| `XanhBase.registerGSAP()` | Register GSAP + ScrollTrigger plugins |
| `XanhBase.initFallbackAnimations(selectors)` | IO fallback khi không có GSAP |

### Cách sử dụng trong từng page:

```html
<!-- Mỗi trang HTML thêm trước page JS -->
<script src="../shared/base.js"></script>
<script src="portfolio.js"></script>
```

```js
// portfolio.js — sau khi tách
const XanhPortfolio = {
  init() {
    XanhBase.initLucide();
    XanhBase.initLenis({ lerp: 0.1 });
    XanhBase.initHeroReveal('.portfolio-hero__bg', '.portfolio-hero-el');
    XanhBase.initHeroParallax('.portfolio-hero__bg img', '#portfolio-hero');
    XanhBase.initScrollReveal('.project-card.anim-fade-up', { className: 'is-revealed' });
    
    // Page-specific code only:
    this.initFilterTabs();
    this.initLoadMore();
    this.initCounterAnimation();
  },
  // ... chỉ còn logic riêng của trang
};
```

### Lợi ích:

| Metric | Trước | Sau |
|--------|-------|-----|
| Tổng dòng code | ~3514 | ~3150 (−10%) |
| Điểm sửa khi thay đổi Lenis config | 6 file | 1 file |
| Điểm sửa khi thay đổi animation timing | 3 file | 1 file |
| Risk of inconsistency | Cao | Thấp |
| Cache efficiency (browser) | Kém (mỗi page load riêng) | Tốt (`base.js` cached 1 lần) |

> 💡 **Ưu tiên thực hiện:** Bắt đầu từ 3 khối 🔴 (Lenis, Lucide, ANIM_DEFAULTS) vì dễ tách nhất và hiệu quả cao nhất. Các khối 🟡 có thể tách dần sau.

---

## 4. Bước Tiếp Theo

1. ✅ Backup 7 file JS gốc → `wireframes/backup_js_20260318/`
2. ✅ Tạo `wireframes/shared/base.js` với các module dùng chung
3. ⬜ Refactor lần lượt từng page JS để dùng `XanhBase.*`
4. ⬜ Cập nhật các file HTML để load `base.js`
5. ⬜ Test tất cả các trang
