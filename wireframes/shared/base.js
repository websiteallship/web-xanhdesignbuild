/**
 * XANH — Design & Build
 * Base Module (Shared Utilities)
 * =============================================
 * Chứa các module dùng chung cho toàn bộ wireframe pages.
 * Load trước page-specific JS.
 *
 * Modules:
 *   1. ANIM_DEFAULTS  — Animation config chuẩn hóa
 *   2. initLucide()   — Lucide Icons
 *   3. initLenis()    — Lenis Smooth Scroll
 *   4. initHeroReveal()   — Hero BG + text entrance
 *   5. initHeroParallax() — GSAP hero parallax
 *   6. initScrollReveal() — IntersectionObserver fade-in
 *   7. initBackToTop()    — Back to top button
 *   8. initCookieConsent() — Cookie consent banner
 *   9. animateCounters()  — Counter animation (GSAP or rAF)
 */

/* ── 1. Animation Defaults (chuẩn hóa toàn site) ── */
const ANIM_DEFAULTS = {
  fadeUp:    { opacity: 0, y: 40, duration: 0.8, ease: 'power2.out' },
  fadeLeft:  { opacity: 0, x: -40, duration: 0.8, ease: 'power2.out' },
  fadeRight: { opacity: 0, x: 40, duration: 0.8, ease: 'power2.out' },
  scaleIn:   { opacity: 0, scale: 0.95, duration: 0.6, ease: 'power2.out' },
  counter:   { duration: 2, ease: 'power1.inOut' },
  stagger:   0.1,
};

/* ── Shared Utility ── */
const XanhBase = {
  /* Internal state */
  _lenis: null,
  _prefersReducedMotion: null,

  /**
   * Check reduced motion preference (cached).
   * @returns {boolean}
   */
  prefersReducedMotion() {
    if (this._prefersReducedMotion === null) {
      this._prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    }
    return this._prefersReducedMotion;
  },

  /* ══════════════════════════════════════════
     2. Lucide Icons
     ══════════════════════════════════════════ */
  initLucide() {
    if (typeof lucide !== 'undefined') {
      try {
        lucide.createIcons();
      } catch (error) {
        console.warn('[XANH] Lucide init failed:', error.message);
      }
    }
  },

  /* ══════════════════════════════════════════
     3. Lenis Smooth Scroll
     ══════════════════════════════════════════
     @param {Object} options - { lerp: 0.1 }
     lerp 0.07 = smoother (blog, contact, detail pages)
     lerp 0.1  = snappier (homepage, portfolio, about)
  */
  initLenis(options = {}) {
    if (typeof Lenis === 'undefined') return null;
    if (typeof gsap === 'undefined') return null;

    const defaults = { lerp: 0.1, smoothWheel: true, wheelMultiplier: 0.8 };
    const config = Object.assign({}, defaults, options);

    try {
      this._lenis = new Lenis(config);

      if (typeof ScrollTrigger !== 'undefined') {
        this._lenis.on('scroll', ScrollTrigger.update);
      }
      gsap.ticker.add((time) => this._lenis.raf(time * 1000));
      gsap.ticker.lagSmoothing(0);

      return this._lenis;
    } catch (error) {
      console.warn('[XANH] Lenis init failed:', error.message);
      return null;
    }
  },

  /**
   * Get existing Lenis instance (useful for pause/resume, e.g. modals).
   * @returns {Lenis|null}
   */
  getLenis() {
    return this._lenis;
  },

  /* ══════════════════════════════════════════
     4. Hero Reveal
     ══════════════════════════════════════════
     Adds `is-loaded` to BG element, `is-visible` to anim elements.
     @param {string} bgSelector  — e.g. '.portfolio-hero__bg'
     @param {string} elSelector  — e.g. '.portfolio-hero-el'
     @param {number} delay       — ms before reveal (default 300)
  */
  initHeroReveal(bgSelector, elSelector, delay = 300) {
    const bg = document.querySelector(bgSelector);
    const els = document.querySelectorAll(elSelector);

    setTimeout(() => {
      if (bg) bg.classList.add('is-loaded');
      els.forEach(el => el.classList.add('is-visible'));
    }, delay);
  },

  /* ══════════════════════════════════════════
     5. Hero Parallax (GSAP + ScrollTrigger)
     ══════════════════════════════════════════
     @param {string} imgSelector     — e.g. '.portfolio-hero__bg img'
     @param {string} triggerSelector — e.g. '#portfolio-hero'
     @param {Object} options         — { startScale: 1.06 }
  */
  initHeroParallax(imgSelector, triggerSelector, options = {}) {
    if (this.prefersReducedMotion()) return;
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

    const img = document.querySelector(imgSelector);
    if (!img) return;

    const startScale = options.startScale || 1.06;

    gsap.registerPlugin(ScrollTrigger);
    gsap.fromTo(img,
      { scale: startScale },
      {
        scale: 1,
        ease: 'none',
        scrollTrigger: {
          trigger: triggerSelector,
          start: 'top top',
          end: 'bottom top',
          scrub: 1,
        },
      }
    );
  },

  /* ══════════════════════════════════════════
     6. Scroll Reveal (IntersectionObserver)
     ══════════════════════════════════════════
     Adds className to elements when they enter viewport.
     @param {string} selector  — CSS selector for elements
     @param {Object} options   — { className, threshold, rootMargin }
  */
  initScrollReveal(selector = '.anim-fade-up', options = {}) {
    const className   = options.className   || 'is-visible';
    const threshold   = options.threshold   || 0.15;
    const rootMargin  = options.rootMargin  || '0px';

    const targets = document.querySelectorAll(selector);
    if (!targets.length) return null;

    if (this.prefersReducedMotion()) {
      targets.forEach(el => el.classList.add(className));
      return null;
    }

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const delay = entry.target.dataset.delay || 0;
          if (delay > 0) {
            setTimeout(() => entry.target.classList.add(className), parseInt(delay, 10));
          } else {
            entry.target.classList.add(className);
          }
          observer.unobserve(entry.target);
        }
      });
    }, { threshold, rootMargin });

    targets.forEach(el => observer.observe(el));
    return observer;
  },

  /* ══════════════════════════════════════════
     7. Back to Top Button
     ══════════════════════════════════════════
     @param {string} btnId      — element ID (default 'back-to-top')
     @param {number} showAfter  — scroll threshold in px (default 400)
  */
  initBackToTop(btnId = 'back-to-top', showAfter = 400) {
    const btn = document.getElementById(btnId);
    if (!btn) return;

    let ticking = false;
    window.addEventListener('scroll', () => {
      if (!ticking) {
        requestAnimationFrame(() => {
          btn.classList.toggle('is-visible', window.scrollY > showAfter);
          ticking = false;
        });
        ticking = true;
      }
    }, { passive: true });

    btn.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  },

  /* ══════════════════════════════════════════
     8. Cookie Consent
     ══════════════════════════════════════════ */
  initCookieConsent() {
    const banner     = document.getElementById('cookie-consent');
    const acceptBtn  = document.getElementById('cookie-accept');
    const settingsBtn = document.getElementById('cookie-settings');
    if (!banner) return;

    if (localStorage.getItem('xanh_cookie_consent') === 'true') return;

    setTimeout(() => {
      banner.classList.add('is-visible');
    }, 2000);

    if (acceptBtn) {
      acceptBtn.addEventListener('click', () => {
        localStorage.setItem('xanh_cookie_consent', 'true');
        banner.classList.remove('is-visible');
      });
    }

    if (settingsBtn) {
      settingsBtn.addEventListener('click', () => {
        console.warn('[XANH] Cookie settings modal — not implemented in wireframe');
        banner.classList.remove('is-visible');
      });
    }
  },

  /* ══════════════════════════════════════════
     9. Counter Animation
     ══════════════════════════════════════════
     Supports both GSAP and pure rAF fallback.
     @param {string} selector   — CSS selector for counter elements
     @param {Object} options    — {
       dataAttr: 'target' | 'counter' | 'count',  // attribute name
       duration: 1500,  // ms for rAF, seconds for GSAP
       useGSAP: false,  // true to use GSAP + ScrollTrigger
       decimals: false,  // support decimal values
     }
  */
  animateCounters(selector, options = {}) {
    const dataAttr  = options.dataAttr  || 'target';
    const duration  = options.duration  || 1500;
    const useGSAP   = options.useGSAP  || false;
    const decimals  = options.decimals  || false;

    const counters = document.querySelectorAll(selector);
    if (!counters.length) return;

    if (this.prefersReducedMotion()) {
      counters.forEach(el => {
        el.textContent = el.dataset[dataAttr];
      });
      return;
    }

    /* ── GSAP path ── */
    if (useGSAP && typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
      gsap.registerPlugin(ScrollTrigger);

      counters.forEach(counter => {
        const target = parseFloat(counter.dataset[dataAttr]);

        gsap.to(counter, {
          textContent: target,
          duration: ANIM_DEFAULTS.counter.duration,
          snap: { textContent: 1 },
          ease: ANIM_DEFAULTS.counter.ease,
          scrollTrigger: {
            trigger: counter,
            start: 'top 85%',
            once: true,
          },
        });
      });
      return;
    }

    /* ── rAF path (pure JS) ── */
    const animateOne = (el) => {
      const target = parseFloat(el.dataset[dataAttr]);
      const decimalPlaces = decimals
        ? parseInt(el.dataset.decimals || '0', 10)
        : 0;
      const suffix = el.dataset.suffix || '';
      const start = performance.now();

      function tick(now) {
        const elapsed = now - start;
        const progress = Math.min(elapsed / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3); // ease-out cubic
        const current = eased * target;

        el.textContent = decimalPlaces > 0
          ? current.toFixed(decimalPlaces) + suffix
          : Math.round(current) + suffix;

        if (progress < 1) requestAnimationFrame(tick);
      }
      requestAnimationFrame(tick);
    };

    /* Observe and trigger animation when visible */
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const els = entry.target.querySelectorAll
            ? entry.target.querySelectorAll(selector)
            : [entry.target];
          // If the observed element itself matches, animate it
          if (entry.target.matches && entry.target.matches(selector)) {
            animateOne(entry.target);
          }
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.3 });

    counters.forEach(el => observer.observe(el));
  },

  /* ══════════════════════════════════════════
     10. Register GSAP Plugins (call once)
     ══════════════════════════════════════════ */
  registerGSAP() {
    if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
      gsap.registerPlugin(ScrollTrigger);
      return true;
    }
    return false;
  },

  /* ══════════════════════════════════════════
     11. Fallback Animation (no GSAP)
     ══════════════════════════════════════════
     IntersectionObserver-based staggered reveal for when GSAP is unavailable.
     @param {string} selectors — comma-separated CSS selectors
  */
  initFallbackAnimations(selectors = '.anim-fade-up, .anim-fade-left, .anim-fade-right') {
    const allAnimEls = document.querySelectorAll(selectors);
    if (!allAnimEls.length) return;

    if (this.prefersReducedMotion()) {
      allAnimEls.forEach(el => {
        el.style.opacity = '1';
        el.style.transform = 'none';
        el.classList.add('is-visible');
      });
      return;
    }

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.style.transition = 'opacity 0.7s ease, transform 0.7s ease';
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'none';
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.1 }
    );

    allAnimEls.forEach(el => observer.observe(el));
  },
};
