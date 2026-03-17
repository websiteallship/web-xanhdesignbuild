/**
 * XANH — Design & Build
 * Blog List Page Scripts
 * =============================================
 * Module: XanhBlog — Hero, Search, Animations
 */

const XanhBlog = {
  /* ── Config ── */
  SEARCH_PLACEHOLDERS: [
    'Tìm kiếm "kinh nghiệm xây nhà"...',
    'Tìm kiếm "vật liệu xanh"...',
    'Tìm kiếm "xu hướng thiết kế 2026"...',
    'Tìm kiếm "chi phí xây dựng"...',
  ],
  TYPING_SPEED: 80,
  ERASE_SPEED: 40,
  PAUSE_BETWEEN: 2000,

  /* ── Entry point ── */
  init() {
    this.initHeader();
    this.initMobileMenu();
    this.initHeroEntrance();
    this.initSearchPlaceholder();
    this.initSearchDropdown();
    this.initCategoryTabs();
    this.initScrollReveal();
    this.initLoadMore();
    this.initBackToTop();
    this.initLenis();

    if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }
  },

  /* ============================================= */
  /* HEADER — Scroll behavior                      */
  /* ============================================= */
  initHeader() {
    const header = document.getElementById('site-header');
    if (!header) return;

    let ticking = false;
    window.addEventListener('scroll', () => {
      if (!ticking) {
        requestAnimationFrame(() => {
          header.classList.toggle('is-scrolled', window.scrollY > 60);
          ticking = false;
        });
        ticking = true;
      }
    }, { passive: true });
  },

  /* ============================================= */
  /* MOBILE MENU                                   */
  /* ============================================= */
  initMobileMenu() {
    const btn = document.getElementById('mobile-menu-btn');
    const drawer = document.getElementById('mobile-drawer');
    const overlay = document.getElementById('mobile-overlay');
    if (!btn || !drawer || !overlay) return;

    const toggle = (open) => {
      btn.classList.toggle('is-active', open);
      drawer.classList.toggle('translate-x-full', !open);
      overlay.classList.toggle('opacity-0', !open);
      overlay.classList.toggle('pointer-events-none', !open);
      document.body.style.overflow = open ? 'hidden' : '';
    };

    btn.addEventListener('click', () => {
      const isOpen = !drawer.classList.contains('translate-x-full');
      toggle(!isOpen);
    });
    overlay.addEventListener('click', () => toggle(false));
  },

  /* ============================================= */
  /* HERO ENTRANCE                                 */
  /* ============================================= */
  initHeroEntrance() {
    const bg = document.querySelector('.blog-hero__bg');
    const els = document.querySelectorAll('.blog-hero-el');
    if (!bg && !els.length) return;

    requestAnimationFrame(() => {
      if (bg) bg.classList.add('is-loaded');
      els.forEach(el => el.classList.add('is-visible'));
    });
  },

  /* ============================================= */
  /* SEARCH — Animated Placeholder (Typing Effect) */
  /* ============================================= */
  initSearchPlaceholder() {
    const input = document.getElementById('blog-search-input');
    const placeholder = document.getElementById('blog-search-placeholder');
    if (!input || !placeholder) return;

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (prefersReducedMotion) {
      placeholder.textContent = this.SEARCH_PLACEHOLDERS[0].replace('...', '');
      placeholder.style.display = 'block';
      input.addEventListener('focus', () => { placeholder.style.display = 'none'; });
      input.addEventListener('blur', () => {
        if (!input.value) placeholder.style.display = 'block';
      });
      return;
    }

    let currentIndex = 0;
    let charIndex = 0;
    let isErasing = false;
    let timer = null;

    const type = () => {
      const text = this.SEARCH_PLACEHOLDERS[currentIndex];

      if (!isErasing) {
        charIndex++;
        placeholder.textContent = text.substring(0, charIndex);

        if (charIndex === text.length) {
          isErasing = true;
          timer = setTimeout(type, this.PAUSE_BETWEEN);
          return;
        }
        timer = setTimeout(type, this.TYPING_SPEED);
      } else {
        charIndex--;
        placeholder.textContent = text.substring(0, charIndex);

        if (charIndex === 0) {
          isErasing = false;
          currentIndex = (currentIndex + 1) % this.SEARCH_PLACEHOLDERS.length;
          timer = setTimeout(type, 400);
          return;
        }
        timer = setTimeout(type, this.ERASE_SPEED);
      }
    };

    /* Hide placeholder when user types */
    input.addEventListener('focus', () => {
      placeholder.style.opacity = '0';
      clearTimeout(timer);
    });
    input.addEventListener('blur', () => {
      if (!input.value) {
        placeholder.style.opacity = '1';
        charIndex = 0;
        isErasing = false;
        currentIndex = 0;
        timer = setTimeout(type, 600);
      }
    });

    /* Start typing */
    timer = setTimeout(type, 800);
  },

  /* ============================================= */
  /* SEARCH — Autocomplete Dropdown Demo           */
  /* ============================================= */
  initSearchDropdown() {
    const input = document.getElementById('blog-search-input');
    const dropdown = document.getElementById('blog-search-dropdown');
    if (!input || !dropdown) return;

    const debounce = (fn, ms = 300) => {
      let t;
      return (...args) => { clearTimeout(t); t = setTimeout(() => fn.apply(this, args), ms); };
    };

    const showDropdown = debounce((value) => {
      if (value.length >= 2) {
        dropdown.classList.remove('hidden');
      } else {
        dropdown.classList.add('hidden');
      }
    }, 300);

    input.addEventListener('input', (e) => {
      showDropdown(e.target.value);
    });

    input.addEventListener('blur', () => {
      setTimeout(() => dropdown.classList.add('hidden'), 200);
    });
  },

  /* ============================================= */
  /* CATEGORY TABS — filter cards by data-category */
  /* ============================================= */
  initCategoryTabs() {
    const bar = document.getElementById('category-tabs-bar');
    if (!bar) return;

    bar.addEventListener('click', (e) => {
      const tab = e.target.closest('.category-tab');
      if (!tab) return;

      const category = tab.dataset.category;

      /* Update active state */
      bar.querySelectorAll('.category-tab').forEach(t => {
        t.classList.remove('is-active');
        t.setAttribute('aria-selected', 'false');
      });
      tab.classList.add('is-active');
      tab.setAttribute('aria-selected', 'true');

      /* Filter cards with data-category attribute */
      const cards = document.querySelectorAll('[data-category]');
      cards.forEach(card => {
        if (!card.closest('#category-tabs-bar')) {
          const show = category === 'all' || card.dataset.category === category;
          card.style.display = show ? '' : 'none';
        }
      });
    });
  },

  /* ============================================= */
  /* SCROLL REVEAL — anim-fade-up via IO            */
  /* ============================================= */
  initScrollReveal() {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const targets = document.querySelectorAll('.anim-fade-up');
    if (!targets.length) return;

    if (prefersReducedMotion) {
      targets.forEach(el => el.classList.add('is-revealed'));
      return;
    }

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-revealed');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });

    targets.forEach(el => observer.observe(el));
  },

  /* ============================================= */
  /* LOAD MORE — Article Grid                      */
  /* ============================================= */
  initLoadMore() {
    const btn    = document.getElementById('load-more-btn');
    const count  = document.getElementById('article-count');
    if (!btn) return;

    btn.addEventListener('click', () => {
      const hidden = document.querySelectorAll('.article-card--hidden[hidden]');
      const BATCH  = 3;
      let revealed = 0;

      hidden.forEach(card => {
        if (revealed >= BATCH) return;
        card.removeAttribute('hidden');
        card.classList.remove('article-card--hidden');

        /* Trigger scroll-reveal on newly visible card */
        requestAnimationFrame(() => {
          card.classList.add('is-revealed');
        });

        revealed++;
      });

      /* Re-run Lucide in case icons are inside new cards */
      if (typeof lucide !== 'undefined') lucide.createIcons();

      /* Update count text */
      const total   = document.querySelectorAll('.article-card').length;
      const visible = document.querySelectorAll('.article-card:not([hidden])').length;
      if (count) count.innerHTML = `Hiển thị <strong>${visible}</strong> / <strong>${total}</strong> bài viết`;

      /* Hide button if nothing left */
      const remaining = document.querySelectorAll('.article-card--hidden[hidden]').length;
      if (remaining === 0) {
        btn.disabled = true;
        btn.textContent = 'Đã hiển thị tất cả';
      }
    });
  },

  /* ============================================= */
  /* BACK TO TOP                                   */
  /* ============================================= */
  initBackToTop() {
    const btn = document.getElementById('back-to-top');
    if (!btn) return;

    let ticking = false;
    window.addEventListener('scroll', () => {
      if (!ticking) {
        requestAnimationFrame(() => {
          btn.classList.toggle('is-visible', window.scrollY > 500);
          ticking = false;
        });
        ticking = true;
      }
    }, { passive: true });

    btn.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  },

  /* ============================================= */
  /* LENIS — Smooth Scroll                         */
  /* ============================================= */
  initLenis() {
    if (typeof Lenis === 'undefined') return;
    if (typeof gsap === 'undefined') return;

    try {
      const lenis = new Lenis({
        lerp: 0.07,
        smoothWheel: true,
        wheelMultiplier: 0.8,
      });

      if (typeof ScrollTrigger !== 'undefined') {
        lenis.on('scroll', ScrollTrigger.update);
        gsap.ticker.add((time) => lenis.raf(time * 1000));
        gsap.ticker.lagSmoothing(0);
      }
    } catch (error) {
      console.warn('[XANH] Lenis init failed:', error.message);
    }
  },
};

/* ── Bootstrap ── */
document.addEventListener('DOMContentLoaded', () => {
  XanhBlog.init();
});
