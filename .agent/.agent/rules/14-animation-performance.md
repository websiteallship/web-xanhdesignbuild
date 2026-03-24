---
description: GSAP and CSS animation performance rules. Apply when writing animations, scroll effects, or transitions in JS/CSS.
globs:
  - wp-content/themes/xanhdesignbuild/assets/js/**/*.js
  - wp-content/themes/xanhdesignbuild/assets/css/**/*.css
  - wireframes/**/*.js
  - wireframes/**/*.css
---

# Animation Performance Rules

## Compositor-Only Properties (GSAP + CSS)

### ✅ SAFE — Compositor layer (GPU, 60fps)
```javascript
gsap.from(el, { opacity: 0, y: 30, x: 0, scale: 0.95, rotation: 5 });
// transform + opacity = compositor = NO layout or paint
```

### ❌ NEVER Animate — Layout triggers
```javascript
// These trigger expensive layout recalculation
gsap.to(el, { width: '200px' });   // ❌ layout
gsap.to(el, { height: '100px' });  // ❌ layout
gsap.to(el, { top: '50px' });      // ❌ layout
gsap.to(el, { left: '100px' });    // ❌ layout
gsap.to(el, { padding: '20px' });  // ❌ layout
gsap.to(el, { margin: '10px' });   // ❌ layout
```

### ⚠️ CAUTION — Paint triggers (OK for small elements only)
```javascript
gsap.to(el, { backgroundColor: '#14513D' }); // ⚠️ paint
gsap.to(el, { borderColor: '#FF8A00' });      // ⚠️ paint
gsap.to(el, { boxShadow: '...' });            // ⚠️ paint
gsap.to(el, { filter: 'blur(4px)' });         // ⚠️ paint — keep ≤8px, short
```

## ScrollTrigger Rules

### ✅ Correct Pattern — Play once, no reverse
```javascript
gsap.utils.toArray('.anim-fade-up').forEach(el => {
    gsap.from(el, {
        scrollTrigger: {
            trigger: el,
            start: 'top 85%',
            toggleActions: 'play none none none', // ✅ Play once only
        },
        opacity: 0, y: 40,
        duration: 0.6,
        ease: 'power2.out',
    });
});
```

### ❌ AVOID — Continuous scroll-driven animation on large surfaces
```javascript
// ❌ Animates every scroll pixel — expensive
gsap.to('.hero__bg', {
    scrollTrigger: { scrub: true }, // ❌ continuous
    y: -100, // on a full-width image = expensive
});
```

### ✅ Parallax — Only on small elements
```javascript
// ✅ OK — small element, transform only
gsap.to('.floating-badge', {
    scrollTrigger: { scrub: 0.5 },
    y: -30, // small element, compositor property
});
```

## `will-change` Usage

### ❌ NEVER — Static/global declaration
```css
.anim-fade-up { will-change: transform; }  /* ❌ Too many layers */
* { will-change: auto; }                   /* ❌ Meaningless */
```

### ✅ TEMPORARY — Only during animation
```javascript
// GSAP handles will-change automatically via force3D
// No need to manually add will-change in most cases
gsap.defaults({ force3D: true }); // ✅ GSAP adds/removes translateZ(0)
```

## Stagger Pattern (Luxury Cascading)
```javascript
// ✅ Brand-appropriate luxury entrance
gsap.from('.card', {
    scrollTrigger: { trigger: '.cards-grid', start: 'top 80%' },
    opacity: 0, y: 40,
    duration: 0.6,
    stagger: 0.1,      // 100ms between cards — luxury feel
    ease: 'power2.out',
});
```

## `prefers-reduced-motion` (MANDATORY)
```css
@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }
}
```
```javascript
// Check in main.js — disable Lenis + GSAP if user prefers
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
if (prefersReducedMotion) {
    // Skip Lenis init
    // Set all elements visible immediately
    gsap.set('.anim-fade-up', { opacity: 1, y: 0 });
}
```

## CSS Transitions
```css
/* ✅ Only transition compositor properties */
.service-card {
    transition: transform 0.3s ease, opacity 0.3s ease, box-shadow 0.3s ease;
}
.service-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.08);
}

/* ❌ NEVER transition layout properties */
.bad-card {
    transition: width 0.3s, height 0.3s, padding 0.3s; /* ❌ */
}
```

## Lazy Animation Init
```javascript
// ✅ Only init Swiper when section is visible
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            initSwiper(entry.target);
            observer.unobserve(entry.target);
        }
    });
}, { rootMargin: '200px' });

document.querySelectorAll('.swiper').forEach(el => observer.observe(el));
```

## Reference
- `docs/implement/PERFORMANCE_SEO.md` §5 — Animation optimization
- Skill: `fixing-motion-performance` — Full animation audit rules
