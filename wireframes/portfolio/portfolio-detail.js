/**
 * XANH — Design & Build
 * Portfolio Detail Page Module
 * =============================================
 * Scope: Section D1 (Breadcrumb) + Section D2 (Hero Image) + Section D3 (Stats Bar)
 * Pattern: XanhPortfolioDetail object module with init()
 */

const XanhPortfolioDetail = {
  lenis: null,
  isDrawerOpen: false,
  prefersReducedMotion: false,

  init() {
    this.prefersReducedMotion = window.matchMedia(
      '(prefers-reduced-motion: reduce)'
    ).matches;

    this.initLucide();
    this.initMobileDrawer();
    this.initLenis();
    this.initHeaderScroll();
    this.initHeroReveal();

    if (!this.prefersReducedMotion) {
      this.initHeroParallax();
    }

    this.initStatsCounter();
    this.initEntranceAnimations();
  },

  /* ── Lucide Icons ── */
  initLucide() {
    if (typeof lucide !== 'undefined') {
      try {
        lucide.createIcons();
      } catch (error) {
        console.warn('[XANH] Lucide init failed:', error.message);
      }
    }
  },

  /* ── Mobile Drawer ── */
  initMobileDrawer() {
    const btn = document.getElementById('mobile-menu-btn');
    const drawer = document.getElementById('mobile-drawer');
    const overlay = document.getElementById('mobile-overlay');
    const links = document.querySelectorAll('.mobile-nav-link');
    if (!btn || !drawer) return;

    const open = () => {
      this.isDrawerOpen = true;
      btn.classList.add('is-active');
      drawer.classList.remove('translate-x-full');
      drawer.classList.add('translate-x-0');
      if (overlay) {
        overlay.classList.remove('opacity-0', 'pointer-events-none');
        overlay.classList.add('opacity-100', 'pointer-events-auto');
      }
      document.body.style.overflow = 'hidden';
      this.animateDrawerLinks(links);
    };

    const close = () => {
      this.isDrawerOpen = false;
      btn.classList.remove('is-active');
      drawer.classList.remove('translate-x-0');
      drawer.classList.add('translate-x-full');
      if (overlay) {
        overlay.classList.remove('opacity-100', 'pointer-events-auto');
        overlay.classList.add('opacity-0', 'pointer-events-none');
      }
      document.body.style.overflow = '';
      this.updateHamburgerColor(btn);
    };

    btn.addEventListener('click', () => {
      this.isDrawerOpen ? close() : open();
    });
    if (overlay) overlay.addEventListener('click', close);
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && this.isDrawerOpen) close();
    });
    links.forEach((l) => l.addEventListener('click', close));
  },

  animateDrawerLinks(links) {
    links.forEach((link, i) => {
      link.style.opacity = '0';
      link.style.transform = 'translateX(20px)';
      setTimeout(() => {
        link.style.transition =
          'opacity 0.35s ease, transform 0.35s ease';
        link.style.opacity = '1';
        link.style.transform = 'translateX(0)';
      }, 80 + i * 60);
    });
  },

  updateHamburgerColor(btn) {
    const sy = window.scrollY;
    const lines = btn.querySelectorAll('.hamburger-line');
    lines.forEach((l) => {
      if (sy > 80) {
        l.classList.remove('bg-white');
        l.classList.add('bg-dark');
      } else {
        l.classList.remove('bg-dark');
        l.classList.add('bg-white');
      }
    });
  },

  /* ── Lenis Smooth Scroll ── */
  initLenis() {
    if (typeof Lenis === 'undefined') return;

    try {
      this.lenis = new Lenis({
        lerp: 0.07,
        smoothWheel: true,
        wheelMultiplier: 0.8,
      });

      if (
        typeof gsap !== 'undefined' &&
        typeof ScrollTrigger !== 'undefined'
      ) {
        this.lenis.on('scroll', ScrollTrigger.update);
        gsap.ticker.add((time) => this.lenis.raf(time * 1000));
        gsap.ticker.lagSmoothing(0);
      }
    } catch (error) {
      console.warn('[XANH] Lenis init failed:', error.message);
    }
  },

  /* ── Header Scroll State ── */
  initHeaderScroll() {
    const header = document.getElementById('site-header');
    if (!header) return;

    let ticking = false;
    window.addEventListener(
      'scroll',
      () => {
        if (!ticking) {
          requestAnimationFrame(() => {
            this.handleHeaderState(header);
            ticking = false;
          });
          ticking = true;
        }
      },
      { passive: true }
    );
  },

  handleHeaderState(header) {
    const sy = window.scrollY;
    const menuBtn = document.getElementById('mobile-menu-btn');

    if (sy > 80) {
      header.classList.add('is-scrolled');
      if (menuBtn && !this.isDrawerOpen) {
        this.setHamburgerDark(menuBtn);
      }
    } else {
      header.classList.remove('is-scrolled');
      if (menuBtn && !this.isDrawerOpen) {
        this.setHamburgerLight(menuBtn);
      }
    }
  },

  setHamburgerDark(btn) {
    btn.querySelectorAll('.hamburger-line').forEach((l) => {
      l.classList.remove('bg-white');
      l.classList.add('bg-dark');
    });
  },

  setHamburgerLight(btn) {
    btn.querySelectorAll('.hamburger-line').forEach((l) => {
      l.classList.remove('bg-dark');
      l.classList.add('bg-white');
    });
  },

  /* ── Hero Image Reveal (blur → clear) ── */
  initHeroReveal() {
    const bg = document.querySelector('.detail-hero__bg');
    if (!bg) return;

    const img = bg.querySelector('img');
    if (!img) return;

    const reveal = () => {
      bg.classList.add('is-loaded');
      this.revealHeroContent();
    };

    if (img.complete) {
      setTimeout(reveal, 200);
    } else {
      img.addEventListener('load', () => setTimeout(reveal, 100));
      img.addEventListener('error', () => setTimeout(reveal, 100));
    }
  },

  revealHeroContent() {
    // Breadcrumb slides in from top first
    const breadcrumb = document.querySelector('.breadcrumb--hero');
    if (breadcrumb) breadcrumb.classList.add('is-visible');

    // Then eyebrow → title → tagline (staggered via CSS transition-delay)
    const elements = document.querySelectorAll(
      '.detail-hero__eyebrow, .detail-hero__title, .detail-hero__tagline'
    );
    elements.forEach((el) => el.classList.add('is-visible'));
  },

  /* ── Hero Parallax (GSAP ScrollTrigger) ── */
  initHeroParallax() {
    if (
      typeof gsap === 'undefined' ||
      typeof ScrollTrigger === 'undefined'
    ) {
      return;
    }

    try {
      gsap.registerPlugin(ScrollTrigger);

      const img = document.querySelector('.detail-hero__bg img');
      if (!img) return;

      gsap.fromTo(
        img,
        { y: 0 },
        {
          y: -40,
          ease: 'none',
          scrollTrigger: {
            trigger: '.detail-hero',
            start: 'top top',
            end: 'bottom top',
            scrub: 1,
          },
        }
      );
    } catch (error) {
      console.warn('[XANH] Hero parallax init failed:', error.message);
    }
  },

  /* ── Stats Bar Counter Animation ── */
  initStatsCounter() {
    const counters = document.querySelectorAll('.stats-bar__counter');
    if (!counters.length) return;

    const animateCounter = (el) => {
      const target = parseFloat(el.dataset.target);
      const decimals = parseInt(el.dataset.decimals || '0', 10);
      const duration = 1500;
      const start = performance.now();

      const tick = (now) => {
        const elapsed = now - start;
        const progress = Math.min(elapsed / duration, 1);
        // ease-out quad
        const eased = 1 - (1 - progress) * (1 - progress);
        const current = eased * target;

        el.textContent = decimals > 0
          ? current.toFixed(decimals)
          : Math.round(current).toString();

        if (progress < 1) {
          requestAnimationFrame(tick);
        }
      };

      requestAnimationFrame(tick);
    };

    if (this.prefersReducedMotion) {
      counters.forEach((el) => {
        const target = parseFloat(el.dataset.target);
        const decimals = parseInt(el.dataset.decimals || '0', 10);
        el.textContent = decimals > 0
          ? target.toFixed(decimals)
          : target.toString();
      });
      return;
    }

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            const items = entry.target.querySelectorAll('.stats-bar__counter');
            items.forEach((el) => animateCounter(el));
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.3 }
    );

    const bar = document.getElementById('stats-bar');
    if (bar) observer.observe(bar);
  },

  /* ── Entrance Animations (IntersectionObserver) ── */
  initEntranceAnimations() {
    const els = document.querySelectorAll('.anim-fade-up');
    if (!els.length) return;

    if (this.prefersReducedMotion) {
      els.forEach((el) => el.classList.add('is-revealed'));
      return;
    }

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            const delay = entry.target.dataset.delay || 0;
            setTimeout(() => {
              entry.target.classList.add('is-revealed');
            }, parseInt(delay, 10));
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.15 }
    );

    els.forEach((el, i) => {
      el.dataset.delay = el.dataset.delay || i * 80;
      observer.observe(el);
    });
  },
};

document.addEventListener('DOMContentLoaded', () => {
  XanhPortfolioDetail.init();
});
