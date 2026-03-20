/**
 * XANH Theme — Main JS (Global)
 *
 * Migrated from: wireframes/shared/base.js
 * Contains: ANIM_DEFAULTS, XanhBase (Lucide, Lenis, hero reveal/parallax,
 * scroll reveal, back-to-top, cookie consent, counters, GSAP register,
 * fallback animations), XanhMobileMenu, XanhHeader.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

'use strict';

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
    if (typeof lucide === 'undefined') return;

    try {
      lucide.createIcons();
    } catch (error) {
      console.warn('[XANH] Lucide init failed:', error.message);
    }
  },

  /* ══════════════════════════════════════════
     3. Lenis Smooth Scroll
     ══════════════════════════════════════════ */
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
   * Get existing Lenis instance.
   * @returns {Lenis|null}
   */
  getLenis() {
    return this._lenis;
  },

  /* ══════════════════════════════════════════
     4. Hero Reveal
     ══════════════════════════════════════════ */
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
     ══════════════════════════════════════════ */
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
     ══════════════════════════════════════════ */
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
     ══════════════════════════════════════════ */
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
        console.warn('[XANH] Cookie settings modal — not implemented yet');
        banner.classList.remove('is-visible');
      });
    }
  },

  /* ══════════════════════════════════════════
     9. Counter Animation
     ══════════════════════════════════════════ */
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
        const trigger = options.triggerEl || counter;

        gsap.to(counter, {
          textContent: target,
          duration: ANIM_DEFAULTS.counter.duration,
          snap: { textContent: 1 },
          ease: ANIM_DEFAULTS.counter.ease,
          scrollTrigger: {
            trigger: trigger,
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
        const eased = 1 - Math.pow(1 - progress, 3);
        const current = eased * target;

        el.textContent = decimalPlaces > 0
          ? current.toFixed(decimalPlaces) + suffix
          : Math.round(current) + suffix;

        if (progress < 1) requestAnimationFrame(tick);
      }
      requestAnimationFrame(tick);
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
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
     ══════════════════════════════════════════ */
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

/* ─────────────────────────────────────────────── */
/* Global Module — Mobile Drawer Menu               */
/* ─────────────────────────────────────────────── */
const XanhMobileMenu = {
  isOpen: false,
  menuBtn: null,
  drawer: null,
  overlay: null,
  navLinks: null,

  /** @private */
  _refs: { keydownHandler: null },

  init() {
    this.menuBtn = document.getElementById('mobile-menu-btn');
    this.drawer = document.getElementById('mobile-drawer');
    this.overlay = document.getElementById('mobile-overlay');
    this.navLinks = document.querySelectorAll('.mobile-nav-link');

    if (!this.menuBtn || !this.drawer) return;

    this.menuBtn.addEventListener('click', () => this.toggle());
    if (this.overlay) this.overlay.addEventListener('click', () => this.close());

    this._refs.keydownHandler = (e) => {
      if (e.key === 'Escape' && !this.drawer.classList.contains('translate-x-full')) {
        this.close();
      }
    };
    document.addEventListener('keydown', this._refs.keydownHandler);

    this.navLinks.forEach((link) => {
      link.addEventListener('click', () => this.close());
    });

    /* ── Sub-menu accordion toggles ── */
    this._initSubMenuToggles();
  },

  /** @private — init accordion toggles for mobile sub-menus. */
  _initSubMenuToggles() {
    const toggleBtns = this.drawer
      ? this.drawer.querySelectorAll('.submenu-toggle')
      : [];

    toggleBtns.forEach((btn) => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();

        // Find the next sibling <ul class="mobile-sub-menu">.
        const parentLi = btn.closest('.mobile-has-children');
        if (!parentLi) return;

        const subMenu = parentLi.querySelector(':scope > .mobile-sub-menu');
        if (!subMenu) return;

        const isOpen = subMenu.classList.contains('is-open');

        // Toggle this sub-menu.
        subMenu.classList.toggle('is-open');
        btn.classList.toggle('is-rotated');
        btn.setAttribute('aria-expanded', isOpen ? 'false' : 'true');

        // Re-init Lucide for any icons inside the sub-menu.
        if (!isOpen && typeof lucide !== 'undefined') {
          try { lucide.createIcons(); } catch (err) { /* noop */ }
        }
      });
    });
  },

  /** @private — close all open sub-menus. */
  _closeAllSubMenus() {
    if (!this.drawer) return;
    this.drawer.querySelectorAll('.mobile-sub-menu.is-open').forEach((sm) => {
      sm.classList.remove('is-open');
    });
    this.drawer.querySelectorAll('.submenu-toggle.is-rotated').forEach((btn) => {
      btn.classList.remove('is-rotated');
      btn.setAttribute('aria-expanded', 'false');
    });
  },

  toggle() {
    this.isOpen ? this.close() : this.open();
  },

  open() {
    this.isOpen = true;
    this.menuBtn.classList.add('is-active');
    this.menuBtn.setAttribute('aria-expanded', 'true');

    this.drawer.classList.remove('translate-x-full');
    this.drawer.classList.add('translate-x-0');
    this.overlay.classList.remove('opacity-0', 'pointer-events-none');
    this.overlay.classList.add('opacity-100', 'pointer-events-auto');
    document.body.classList.add('is-scroll-locked');

    this.menuBtn.querySelectorAll('.hamburger-line').forEach((l) => {
      l.classList.remove('bg-dark');
      l.classList.add('bg-white');
    });

    this.navLinks.forEach((link, i) => {
      link.classList.remove('drawer-link--visible');
      link.classList.add('drawer-link--hidden');
      setTimeout(() => {
        link.classList.remove('drawer-link--hidden');
        link.classList.add('drawer-link--visible');
      }, 80 + i * 60);
    });
  },

  close() {
    this.isOpen = false;
    this.menuBtn.classList.remove('is-active');
    this.menuBtn.setAttribute('aria-expanded', 'false');

    this.drawer.classList.remove('translate-x-0');
    this.drawer.classList.add('translate-x-full');
    this.overlay.classList.remove('opacity-100', 'pointer-events-auto');
    this.overlay.classList.add('opacity-0', 'pointer-events-none');
    document.body.classList.remove('is-scroll-locked');

    // Close all sub-menus when drawer closes.
    this._closeAllSubMenus();

    const scrollY = window.scrollY || window.pageYOffset;
    this.menuBtn.querySelectorAll('.hamburger-line').forEach((l) => {
      if (scrollY > 80) {
        l.classList.remove('bg-white');
        l.classList.add('bg-dark');
      } else {
        l.classList.remove('bg-dark');
        l.classList.add('bg-white');
      }
    });
  },

  destroy() {
    if (this._refs.keydownHandler) {
      document.removeEventListener('keydown', this._refs.keydownHandler);
    }
  },
};

/* ─────────────────────────────────────────────── */
/* Global Module — Header Scroll Behavior           */
/* ─────────────────────────────────────────────── */
const XanhHeader = {
  header: null,
  _ticking: false,

  init() {
    this.header = document.getElementById('site-header');
    if (!this.header) return;

    // Apply initial state
    this._handleScroll();

    window.addEventListener('scroll', () => {
      if (!this._ticking) {
        requestAnimationFrame(() => {
          this._handleScroll();
          this._ticking = false;
        });
        this._ticking = true;
      }
    }, { passive: true });
  },

  /** @private */
  _handleScroll() {
    const scrollY = window.scrollY || window.pageYOffset;
    const menuBtn = document.getElementById('mobile-menu-btn');
    const isNoHero = document.body.classList.contains('xanh-no-hero');

    if (scrollY > 80 || isNoHero) {
      this.header.classList.add('is-scrolled');
      if (menuBtn) {
        menuBtn.querySelectorAll('.hamburger-line').forEach((l) => {
          l.classList.remove('bg-white');
          l.classList.add('bg-dark');
        });
      }
    } else {
      this.header.classList.remove('is-scrolled');
      if (menuBtn) {
        menuBtn.querySelectorAll('.hamburger-line').forEach((l) => {
          l.classList.remove('bg-dark');
          l.classList.add('bg-white');
        });
      }
    }
  },
};

/* ─────────────────────────────────────────────── */
/* Global Init — DOMContentLoaded                   */
/* ─────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
  /* Core */
  XanhBase.initLucide();
  XanhBase.registerGSAP();
  XanhBase.initLenis();

  /* Header + Mobile Menu */
  XanhHeader.init();
  XanhMobileMenu.init();

  /* Global animations */
  XanhBase.initScrollReveal('.anim-fade-up');
  XanhBase.initScrollReveal('.anim-fade-left');
  XanhBase.initScrollReveal('.anim-fade-right');

  /* Utilities */
  XanhBase.initBackToTop();
  XanhBase.initCookieConsent();
});
