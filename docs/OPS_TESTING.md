# OPS_TESTING — Testing Checklist

> **Dự án:** Website XANH - Design & Build
> **Ngày tạo:** 2026-03-12

---

## 1. Cross-Browser Testing

| Browser | Desktop | Mobile |
|---|---|---|
| Chrome | ✅ | ✅ (Android) |
| Safari | ✅ (Mac) | ✅ (iOS) |
| Firefox | ✅ | — |
| Edge | ✅ | — |

---

## 2. Responsive Testing

| Breakpoint | Device | Checklist |
|---|---|---|
| 375px | iPhone SE | ✅ Nav hamburger, Floating CTA, form inputs 48px |
| 414px | iPhone 14 | ✅ Hero text readable, card single column |
| 768px | iPad | ✅ 2-column layouts, filter horizontal scroll |
| 1024px | Laptop | ✅ Full layouts, sticky sidebar |
| 1440px | Desktop | ✅ Max container, spacing proportional |

---

## 3. Functional Testing

### Forms
- [ ] Form Tư Vấn gửi thành công → email admin + user
- [ ] Form Dự Toán tính đúng → hiện kết quả + email
- [ ] Form Lead Magnet → email with ebook link
- [ ] Validation real-time (SĐT 10 số, required fields)
- [ ] Thank-you page redirect
- [ ] Anti-spam (honeypot, reCAPTCHA)

### Components
- [ ] Before/After Slider: touch swipe (mobile) + mouse drag (desktop)
- [ ] Lightbox Gallery: swipe, keyboard arrows, close on Escape
- [ ] Video Popup: play, close overlay, pause on close
- [ ] Animated Counter: runs once, correct numbers
- [ ] Accordion FAQ: open/close, icon rotate, first item open
- [ ] Parallax: works desktop, fallback mobile (iOS)
- [ ] AJAX filtering: Portfolio + Blog filter without reload
- [ ] Load More: loads next batch correctly
- [ ] Sticky filter bar: sticks on scroll, blur effect
- [ ] Preloader: shows first visit only (sessionStorage)
- [ ] Cookie Consent: shows once, saves to localStorage
- [ ] Back to Top: appears >500px, smooth scroll

### Navigation
- [ ] All nav links work
- [ ] Mobile hamburger menu open/close
- [ ] Breadcrumbs correct on Portfolio detail + Blog detail

---

## 4. Performance Testing

- [ ] PageSpeed > 90 mobile
- [ ] PageSpeed > 90 desktop
- [ ] All images WebP
- [ ] All images have width + height (no CLS)
- [ ] Fonts use `font-display: swap`
- [ ] No render-blocking JS
- [ ] LiteSpeed Cache active

---

## 5. SEO Testing

- [ ] Each page has unique `<title>` + `<meta description>`
- [ ] Single `<h1>` per page
- [ ] `<img>` alt text meaningful
- [ ] Sitemap.xml submitted to Search Console
- [ ] robots.txt allows crawling
- [ ] Schema markup validates (Schema.org validator)
- [ ] Canonical URLs correct
- [ ] Open Graph tags for social sharing

---

## 6. Accessibility Testing

- [ ] Keyboard navigation works (Tab, Enter, Escape)
- [ ] Focus indicators visible
- [ ] Color contrast passes WCAG AA
- [ ] `alt` text on all images
- [ ] Form labels present
- [ ] `reduced-motion` respected

---

## Tài Liệu Liên Quan

- `GOV_UX_GUIDELINES.md` — Accessibility standards
- `ARCH_PERFORMANCE.md` — Performance targets
- `OPS_DEPLOYMENT.md` — Go-live checklist
