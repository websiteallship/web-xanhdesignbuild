---
description: JavaScript optimization rules for performance, maintainability, and reliability. Apply when writing or reviewing any JS file.
globs: "**/*.js"
---

# JavaScript Optimization Rules ★★

> **Mục tiêu:** JS nhẹ, nhanh, dễ bảo trì, không memory leak.
> Bổ sung cho `02-frontend-css-js.md` (library stack) và `06-performance.md` (budgets).

---

## 1. Module Architecture — Cấu Trúc Mã ★

### 1.1 — Object Module Pattern (Bắt Buộc)
```javascript
/* ✅ Mỗi file JS là 1 module object với init() */
const XanhAbout = {
  init() {
    this.initHero();
    this.initAnimations();
    this.initVideoModal();
  },

  initHero() { /* ... */ },
  initAnimations() { /* ... */ },
  initVideoModal() { /* ... */ },
};

document.addEventListener('DOMContentLoaded', () => {
  XanhAbout.init();
});
```

```javascript
/* ❌ CẤM: Tất cả logic dồn trong 1 DOMContentLoaded callback */
document.addEventListener('DOMContentLoaded', () => {
  // 300+ lines code không tổ chức
  const a = document.querySelector('.a');
  // ... hàng trăm dòng tiếp ...
});
```

**Quy tắc:**
- Mỗi file → 1 module object: `XanhHome`, `XanhAbout`, `XanhSlider`, `XanhFilter`...
- Prefix `Xanh` cho tất cả module names → tránh xung đột global
- `init()` là entry point duy nhất
- Mỗi method ≤ **30 dòng**. Nếu dài hơn → tách thành method con

### 1.2 — File Organization (Theo CORE_ARCHITECTURE)
```
assets/js/
├── main.js          → XanhApp: Lenis, global animations, shared utils
├── animations.js    → XanhAnimations: GSAP timelines, counters, card-flip
├── slider.js        → XanhSlider: Swiper instances
├── gallery.js       → XanhGallery: GLightbox instances
├── filter.js        → XanhFilter: AJAX filtering, skeleton
├── forms.js         → XanhForms: validation, progress
└── search.js        → XanhSearch: autocomplete
```

### 1.3 — Conditional Init (Kiểm Tra DOM Trước)
```javascript
/* ✅ ĐÚNG — init chỉ khi element tồn tại */
initComponents() {
  if (document.querySelector('.swiper')) this.initSliders();
  if (document.querySelector('[data-counter]')) this.initCounters();
  if (document.querySelector('[data-lightbox]')) this.initLightbox();
}

/* ❌ SAI — gọi init blind, crash nếu element không có */
initComponents() {
  this.initSliders();   // Error nếu trang không có .swiper
  this.initCounters();
}
```

---

## 2. Library Safety — Kiểm Tra Thư Viện ★

### 2.1 — Luôn Kiểm Tra Library Trước Khi Dùng
```javascript
/* ✅ Defensive — CDN có thể fail */
if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
  gsap.registerPlugin(ScrollTrigger);
  // ... animation code
}

if (typeof Lenis !== 'undefined') {
  const lenis = new Lenis({ lerp: 0.1, smoothWheel: true });
  // ...
}

if (typeof lucide !== 'undefined') {
  lucide.createIcons();
}

/* ❌ SAI — giả định library luôn có sẵn */
gsap.registerPlugin(ScrollTrigger); // → ReferenceError nếu CDN down
```

### 2.2 — Try/Catch Cho Init Operations
```javascript
/* ✅ Init với error boundary */
function initSlider() {
  const container = document.querySelector('.swiper');
  if (!container) return;

  try {
    new Swiper(container, { /* options */ });
  } catch (error) {
    console.warn('[XANH] Slider init failed:', error.message);
  }
}
```

### 2.3 — Console Logging Convention
```javascript
/* ✅ Prefix [XANH] cho dễ filter trong DevTools */
console.warn('[XANH] Slider init failed:', error.message);
console.error('[XANH] Fetch failed:', error.message);

/* ❌ CẤM trong production */
console.log('debug:', data);      // → xóa trước deploy
console.log(el);                  // → debug litter
```

---

## 3. GSAP & ScrollTrigger — Best Practices ★

### 3.1 — RegisterPlugin 1 Lần
```javascript
/* ✅ Register 1 lần ở đầu main.js */
gsap.registerPlugin(ScrollTrigger);

/* ❌ Register lại ở mỗi file/function */
function initSection2() {
  gsap.registerPlugin(ScrollTrigger); // thừa
}
```

### 3.2 — Animation Defaults Chuẩn Hóa (Từ 08-cross-section-consistency.md)
```javascript
/* ✅ Config chuẩn — DRY, nhất quán toàn site */
const ANIM_DEFAULTS = {
  fadeUp:   { opacity: 0, y: 40, duration: 0.8, ease: 'power2.out' },
  fadeLeft: { opacity: 0, x: -40, duration: 0.8, ease: 'power2.out' },
  fadeRight:{ opacity: 0, x: 40, duration: 0.8, ease: 'power2.out' },
  scaleIn:  { opacity: 0, scale: 0.95, duration: 0.6, ease: 'power2.out' },
  stagger:  0.1,
};

/* ✅ Dùng shared config */
gsap.from('.section-title', {
  scrollTrigger: { trigger: section, start: 'top 85%' },
  ...ANIM_DEFAULTS.fadeUp,
});

/* ❌ Mỗi section tự viết opacity/y/duration khác nhau */
gsap.from('.sec1-title', { opacity:0, y:30, duration:0.9, ease:'power3.out' });
gsap.from('.sec2-title', { opacity:0, y:50, duration:0.7, ease:'power2.out' });
// → inconsistent motion
```

### 3.3 — ScrollTrigger Cleanup
```javascript
/* ✅ Kill khi không cần (SPA, dynamic content) */
const st = ScrollTrigger.create({ /* ... */ });
// Khi remove section:
st.kill();

/* ✅ Kill tất cả khi chuyển trang (nếu SPA) */
ScrollTrigger.getAll().forEach(st => st.kill());

/* ✅ Refresh sau khi DOM thay đổi (AJAX filter) */
ScrollTrigger.refresh();
```

### 3.4 — Tôn Trọng prefers-reduced-motion
```javascript
/* ✅ Kiểm tra TRƯỚC khi tạo animations */
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

if (!prefersReducedMotion) {
  // Init GSAP animations
  gsap.from('.card', { opacity: 0, y: 40, stagger: 0.1 });
} else {
  // Hiển thị ngay, không animate
  gsap.set('.card', { opacity: 1, y: 0 });
}
```

### 3.5 — Animation Timing (Từ 02-frontend-css-js.md)

| Loại | Duration | Ease | Dùng cho |
|---|---|---|---|
| UI feedback | 150-300ms | `power2.out` | Hover, toggle, dropdown |
| Entrance | 600-1000ms | `power2.out` | Section reveal, card stagger |
| Counter | 1500-2000ms | `power1.inOut` | Số chạy, progress bar |
| Parallax | `scrub: 1` | `none` | Background movement |
| Stagger gap | 100ms | — | Giữa siblings |

---

## 4. Event Handling — Xử Lý Sự Kiện ★

### 4.1 — Passive Listeners Cho Scroll/Touch
```javascript
/* ✅ passive: true → không block scroll */
window.addEventListener('scroll', handleScroll, { passive: true });
window.addEventListener('touchmove', handleTouch, { passive: true });

/* ❌ Quên passive → browser chờ JS xong mới scroll */
window.addEventListener('scroll', handleScroll);
```

### 4.2 — Throttle Scroll Handlers Với rAF
```javascript
/* ✅ requestAnimationFrame throttle — 1 call/frame */
let ticking = false;
window.addEventListener('scroll', () => {
  if (!ticking) {
    requestAnimationFrame(() => {
      handleHeaderScroll();
      ticking = false;
    });
    ticking = true;
  }
}, { passive: true });

/* ❌ Gọi handler mỗi pixel scroll */
window.addEventListener('scroll', handleHeaderScroll);
```

### 4.3 — Debounce Cho Input/Search
```javascript
/* ✅ Debounce 300ms — tránh gọi API mỗi keystroke */
function debounce(fn, ms = 300) {
  let timer;
  return (...args) => {
    clearTimeout(timer);
    timer = setTimeout(() => fn.apply(this, args), ms);
  };
}

searchInput.addEventListener('input', debounce(handleSearch, 300));

/* ❌ Gọi API mỗi keystroke */
searchInput.addEventListener('input', handleSearch);
```

### 4.4 — Event Delegation Cho Collections
```javascript
/* ✅ 1 listener, xử lý nhiều targets */
document.querySelector('.tabs').addEventListener('click', (e) => {
  const tab = e.target.closest('[data-tab]');
  if (!tab) return;
  activateTab(tab.dataset.tab);
});

/* ❌ N listeners cho N elements */
document.querySelectorAll('.tab-btn').forEach(btn => {
  btn.addEventListener('click', () => activateTab(btn.dataset.tab));
});
```

### 4.5 — Cleanup Event Listeners
```javascript
/* ✅ Remove khi không cần → tránh memory leak */
const handler = () => { /* ... */ };
element.addEventListener('click', handler);
// Khi remove element:
element.removeEventListener('click', handler);

/* ✅ AbortController cho batch cleanup */
const controller = new AbortController();
el.addEventListener('click', handler, { signal: controller.signal });
el.addEventListener('keydown', handler, { signal: controller.signal });
// Cleanup tất cả:
controller.abort();
```

---

## 5. DOM Operations — Thao Tác DOM ★

### 5.1 — Batch DOM Reads/Writes
```javascript
/* ❌ Layout thrashing — đọc/ghi xen kẽ */
elements.forEach(el => {
  const height = el.offsetHeight;    // READ → trigger layout
  el.style.height = height + 'px';   // WRITE → invalidate layout
});

/* ✅ Đọc TRƯỚC, ghi SAU */
const heights = elements.map(el => el.offsetHeight); // Batch READ
elements.forEach((el, i) => {
  el.style.height = heights[i] + 'px'; // Batch WRITE
});
```

### 5.2 — Dùng classList, Không Manipulate style Trực Tiếp
```javascript
/* ✅ Toggle classes → CSS xử lý visual */
element.classList.add('is-active');
element.classList.remove('is-loading');
element.classList.toggle('is-visible');

/* ❌ Inline styles → khó maintain, khó override */
element.style.opacity = '1';
element.style.transform = 'translateY(0)';
element.style.background = '#14513D';
```

**Ngoại lệ:** Chỉ dùng `style` khi GSAP yêu cầu (GSAP tự quản lý inline style).

### 5.3 — Fragment Cho Batch Insert
```javascript
/* ✅ Render vào fragment, insert 1 lần → 1 reflow */
const fragment = document.createDocumentFragment();
items.forEach(item => {
  const card = createCard(item);
  fragment.appendChild(card);
});
container.appendChild(fragment);

/* ❌ Insert từng phần tử → N reflows */
items.forEach(item => {
  container.appendChild(createCard(item));
});
```

### 5.4 — KHÔNG Dùng innerHTML Cho User Content
```javascript
/* ❌ XSS attack vector */
element.innerHTML = userInput;
element.innerHTML = `<p>${data.message}</p>`;

/* ✅ An toàn */
element.textContent = userInput;

/* ✅ OK cho static HTML (từ server đã sanitize) */
container.innerHTML = sanitizedHTMLFromServer;
```

---

## 6. Async & Data Fetching ★

### 6.1 — Async/Await + Error Handling Pattern
```javascript
/* ✅ AJAX handler pattern (theo CORE_ARCHITECTURE §8) */
async function fetchProjects(params) {
  try {
    const formData = new FormData();
    formData.append('action', 'xanh_filter_projects');
    formData.append('nonce', xanhAjax.filter_nonce);
    formData.append('type', params.type);

    const response = await fetch(xanhAjax.url, {
      method: 'POST',
      body: formData,
    });

    if (!response.ok) throw new Error(`HTTP ${response.status}`);
    const data = await response.json();
    if (!data.success) throw new Error(data.data?.message || 'Unknown error');

    return data.data;
  } catch (error) {
    console.error('[XANH] Fetch failed:', error.message);
    showErrorState();
    return null;
  }
}
```

### 6.2 — Loading State Feedback
```javascript
/* ✅ Skeleton loading → real content (perceived performance) */
async function filterProjects(type) {
  showSkeleton();          // 1. Immediately show skeleton
  disableFilterButtons();  // 2. Prevent double-click

  const data = await fetchProjects({ type });

  if (data) {
    renderCards(data.html);   // 3. Replace skeleton with real cards
    updatePagination(data);
  }

  enableFilterButtons();     // 4. Re-enable
}

/* ✅ Button spinner pattern */
function handleSubmit(btn) {
  btn.disabled = true;
  btn.classList.add('is-loading');         // CSS: show spinner
  btn.querySelector('span').textContent = 'Đang gửi...';
}
```

---

## 7. Memory & Performance — Tránh Rò Rỉ ★

### 7.1 — Cleanup Timers
```javascript
/* ✅ Clear khi không cần */
let autoplayTimer = setInterval(nextSlide, 5000);

// Khi user rời trang hoặc destroy component:
clearInterval(autoplayTimer);
autoplayTimer = null;

/* ✅ Clear setTimeout chưa chạy */
let delayTimer = setTimeout(doSomething, 3000);
// Nếu cần hủy:
clearTimeout(delayTimer);
```

### 7.2 — IntersectionObserver Unobserve
```javascript
/* ✅ unobserve sau khi đã trigger (one-shot reveal) */
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('is-visible');
      observer.unobserve(entry.target);  // ← quan trọng
    }
  });
}, { threshold: 0.15 });

/* ✅ disconnect toàn bộ khi cleanup */
observer.disconnect();
```

### 7.3 — Tránh Closure Memory Leak
```javascript
/* ❌ Closure giữ reference đến large DOM node */
function init() {
  const heavyElement = document.querySelector('.huge-gallery');
  setInterval(() => {
    console.log(heavyElement.offsetHeight); // heavyElement never GC'd
  }, 1000);
}

/* ✅ WeakRef hoặc re-query khi cần */
function init() {
  setInterval(() => {
    const el = document.querySelector('.huge-gallery');
    if (el) console.log(el.offsetHeight);
  }, 1000);
}
```

---

## 8. State Management — Quản Lý Trạng Thái ★

### 8.1 — Storage Convention (Từ CORE_ARCHITECTURE §10)

| Dữ liệu | Storage | Key | Ví dụ |
|---|---|---|---|
| First visit | `sessionStorage` | `xanh_preloaded` | Preloader 1 lần |
| Cookie consent | `localStorage` | `xanh_cookie_consent` | `true` / `false` |
| Blog read | `localStorage` | `xanh_read_{id}` | `true` |
| Filter state | URL params | `?type=...&page=...` | Shareable URL |
| Scroll position | JS variable | — | Back-to-top |
| Animation done | DOM `data-*` | `data-animated` | Tránh re-animate |

### 8.2 — URL State Cho Filters (Bookmark-able)
```javascript
/* ✅ Sync filter → URL → user có thể share/bookmark */
const XanhFilter = {
  updateURL(params) {
    const url = new URL(window.location);
    Object.entries(params).forEach(([k, v]) => {
      v ? url.searchParams.set(k, v) : url.searchParams.delete(k);
    });
    history.pushState({}, '', url);
  },

  getState() {
    const params = new URLSearchParams(window.location.search);
    return {
      type: params.get('type') || 'all',
      page: parseInt(params.get('page')) || 1,
    };
  },
};
```

---

## 9. Lenis Integration — Smooth Scroll ★

```javascript
/* ✅ Init pattern chuẩn — main.js */
if (typeof Lenis !== 'undefined') {
  const lenis = new Lenis({
    lerp: 0.07,          // 0.07-0.1 cho luxury feel
    smoothWheel: true,
    wheelMultiplier: 0.8, // Giảm tốc độ scroll
  });

  // Sync với GSAP ticker (KHÔNG dùng rAF loop riêng)
  lenis.on('scroll', ScrollTrigger.update);
  gsap.ticker.add((time) => lenis.raf(time * 1000));
  gsap.ticker.lagSmoothing(0);

  // Pause khi modal mở
  // lenis.stop();
  // Resume khi modal đóng
  // lenis.start();
}

/* ❌ Dùng rAF loop riêng khi đã có GSAP ticker */
function raf(time) { lenis.raf(time); requestAnimationFrame(raf); }
requestAnimationFrame(raf);
// → double update, waste performance
```

---

## 10. Anti-Patterns — Những Điều KHÔNG Làm ❌

| Anti-Pattern | Vấn đề | Thay thế |
|---|---|---|
| `var` keyword | Hoisting bugs, scope leak | `const` / `let` |
| `document.write()` | Block parser, destroy DOM | `createElement` / `innerHTML` |
| `eval()` / `Function()` | XSS, performance | Direct code |
| `element.innerHTML = userInput` | XSS | `textContent` |
| `setInterval` không clear | Memory leak | Clear trong cleanup |
| Scroll handler không throttle | Jank 60fps | `requestAnimationFrame` |
| `querySelector` trong loop | Slow repeated query | Cache reference |
| Sync XHR | Block main thread | `fetch` + async/await |
| Global variables | Collision, debug hell | Module pattern, `const` |
| `onclick="..."` inline | Unmaintainable | `addEventListener` |
| Magic numbers | Unclear intent | Named constants |

---

## 11. Checklist — Khi Review JS ✓

Trước khi commit JS mới, kiểm tra:

- [ ] **Module pattern:** Code nằm trong `XanhXxx` object, có `init()`?
- [ ] **Library check:** `typeof gsap/Lenis/Swiper !== 'undefined'` trước khi dùng?
- [ ] **DOM check:** `querySelector` + null guard trước `addEventListener`?
- [ ] **Scroll listener:** Có `{ passive: true }` và rAF throttle?
- [ ] **Debounce:** Input/search handler có debounce 300ms?
- [ ] **Cleanup:** Timers cleared? Observers unobserved? Listeners removed?
- [ ] **Error handling:** try/catch cho init và fetch operations?
- [ ] **Reduced motion:** Check `prefers-reduced-motion` trước animations?
- [ ] **GSAP config:** Dùng `ANIM_DEFAULTS` chuẩn hóa, không tự ý đổi easings?
- [ ] **No console.log:** Chỉ `warn`/`error` với prefix `[XANH]`?
- [ ] **No `var`:** Dùng `const` (preferred) hoặc `let`?
- [ ] **Max 30 lines/method:** Tách method nếu dài hơn?
- [ ] **Nonce:** AJAX request gửi kèm nonce từ `xanhAjax`?

---

## Tài Liệu Liên Quan

- `02-frontend-css-js.md` — Library stack, GSAP/Lenis/Alpine patterns
- `06-performance.md` — JS budget per page, loading strategy
- `08-cross-section-consistency.md` — Shared animation classes
- `09-css-optimization.md` — CSS rendering performance
- `docs/CORE_ARCHITECTURE.md` §10 — State management, AJAX flow
- `docs/GOV_CODING_STANDARDS.md` §3 — Module pattern, error handling
- `docs/ARCH_PERFORMANCE.md` §7 — Critical rendering path
