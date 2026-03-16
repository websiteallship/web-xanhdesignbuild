/**
 * XANH — Design & Build
 * About Page Module
 * =============================================
 * Libraries: GSAP, ScrollTrigger, Lenis, Lucide
 * Pattern:   XanhAbout module (rule 10 §1)
 */

/* ── Animation Defaults (rule 10 §3.2 / rule 08 §9) ── */
const ANIM_DEFAULTS = {
  fadeUp:    { opacity: 0, y: 40, duration: 0.8, ease: 'power2.out' },
  fadeLeft:  { opacity: 0, x: -40, duration: 0.8, ease: 'power2.out' },
  fadeRight: { opacity: 0, x: 40, duration: 0.8, ease: 'power2.out' },
  scaleIn:   { opacity: 0, scale: 0.95, duration: 0.6, ease: 'power2.out' },
  stagger:   0.1,
};

const XanhAbout = {
  // Shared state
  lenis: null,
  isDrawerOpen: false,
  prefersReducedMotion: false,

  /* ── Entry Point ── */
  init() {
    this.prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    this.initLucide();
    this.initMobileDrawer();
    this.initLenis();
    this.initHeaderScroll();
    this.initHeroReveal();
    this.initVideoModal();

    // Animations — only if user allows motion
    if (!this.prefersReducedMotion) {
      this.initHeroParallax();
      this.initSectionAnimations();
      this.initPainAnimations();
      this.initPromiseAnimations();
      this.initTurningPointAnimations();
      this.initNodeHover();
      this.initTeamAnimations();
      this.initCoreValuesAnimations();
      this.initFinalCTAAnimations();
    }
  },

  /* ── Lucide Icons ── */
  initLucide() {
    if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }
  },

  /* ── Mobile Drawer Menu ── */
  initMobileDrawer() {
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileDrawer = document.getElementById('mobile-drawer');
    const mobileOverlay = document.getElementById('mobile-overlay');
    const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');

    if (!mobileMenuBtn || !mobileDrawer) return;

    const openDrawer = () => {
      this.isDrawerOpen = true;
      mobileMenuBtn.classList.add('is-active');
      mobileDrawer.classList.remove('translate-x-full');
      mobileDrawer.classList.add('translate-x-0');
      if (mobileOverlay) {
        mobileOverlay.classList.remove('opacity-0', 'pointer-events-none');
        mobileOverlay.classList.add('opacity-100', 'pointer-events-auto');
      }
      document.body.classList.add('is-scroll-locked');

      mobileMenuBtn.querySelectorAll('.hamburger-line').forEach(l => {
        l.classList.remove('bg-dark');
        l.classList.add('bg-white');
      });

      mobileNavLinks.forEach((link, i) => {
        link.classList.remove('drawer-link--visible');
        link.classList.add('drawer-link--hidden');
        setTimeout(() => {
          link.classList.remove('drawer-link--hidden');
          link.classList.add('drawer-link--visible');
        }, 80 + i * 60);
      });
    };

    const closeDrawer = () => {
      this.isDrawerOpen = false;
      mobileMenuBtn.classList.remove('is-active');
      mobileDrawer.classList.remove('translate-x-0');
      mobileDrawer.classList.add('translate-x-full');
      if (mobileOverlay) {
        mobileOverlay.classList.remove('opacity-100', 'pointer-events-auto');
        mobileOverlay.classList.add('opacity-0', 'pointer-events-none');
      }
      document.body.classList.remove('is-scroll-locked');

      const scrollY = window.scrollY || window.pageYOffset;
      mobileMenuBtn.querySelectorAll('.hamburger-line').forEach(l => {
        if (scrollY > 80) {
          l.classList.remove('bg-white');
          l.classList.add('bg-dark');
        } else {
          l.classList.remove('bg-dark');
          l.classList.add('bg-white');
        }
      });
    };

    mobileMenuBtn.addEventListener('click', () => {
      this.isDrawerOpen ? closeDrawer() : openDrawer();
    });

    if (mobileOverlay) mobileOverlay.addEventListener('click', closeDrawer);

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && !mobileDrawer.classList.contains('translate-x-full')) {
        closeDrawer();
      }
    });

    mobileNavLinks.forEach(link => {
      link.addEventListener('click', closeDrawer);
    });
  },

  /* ── Lenis Smooth Scroll (rule 10 §9 — GSAP ticker only) ── */
  initLenis() {
    if (typeof Lenis === 'undefined') return;

    this.lenis = new Lenis({
      lerp: 0.1,
      smoothWheel: true,
      wheelMultiplier: 0.8,
    });

    // Sync with GSAP ticker — NO separate rAF loop
    if (typeof ScrollTrigger !== 'undefined') {
      this.lenis.on('scroll', ScrollTrigger.update);
      gsap.ticker.add((time) => {
        this.lenis.raf(time * 1000);
      });
      gsap.ticker.lagSmoothing(0);
    }
  },

  /* ── Header Scroll Behavior ── */
  initHeaderScroll() {
    const header = document.getElementById('site-header');
    if (!header) return;

    const handleHeaderScroll = () => {
      const scrollY = window.scrollY || window.pageYOffset;
      const menuBtn = document.getElementById('mobile-menu-btn');

      if (scrollY > 80) {
        header.classList.add('is-scrolled');
        if (menuBtn && !this.isDrawerOpen) {
          menuBtn.querySelectorAll('.hamburger-line').forEach(l => {
            l.classList.remove('bg-white');
            l.classList.add('bg-dark');
          });
        }
      } else {
        header.classList.remove('is-scrolled');
        if (menuBtn && !this.isDrawerOpen) {
          menuBtn.querySelectorAll('.hamburger-line').forEach(l => {
            l.classList.remove('bg-dark');
            l.classList.add('bg-white');
          });
        }
      }
    };

    let headerTicking = false;
    window.addEventListener('scroll', () => {
      if (!headerTicking) {
        requestAnimationFrame(() => {
          handleHeaderScroll();
          headerTicking = false;
        });
        headerTicking = true;
      }
    }, { passive: true });
  },

  /* ── Hero Content Reveal ── */
  initHeroReveal() {
    const heroBg = document.querySelector('.about-hero__bg');
    const heroEls = document.querySelectorAll('.about-hero-el');

    setTimeout(() => {
      if (heroBg) heroBg.classList.add('is-loaded');
      heroEls.forEach(el => el.classList.add('is-visible'));
    }, 300);
  },

  /* ── Hero Parallax (GSAP) ── */
  initHeroParallax() {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;
    gsap.registerPlugin(ScrollTrigger);

    const heroBgImg = document.querySelector('.about-hero__bg img');
    if (!heroBgImg) return;

    gsap.fromTo(heroBgImg,
      { scale: 1.06 },
      {
        scale: 1,
        ease: 'none',
        scrollTrigger: { trigger: '#about-hero', start: 'top top', end: 'bottom top', scrub: 1 },
      }
    );
  },

  /* ── Video Modal ── */
  initVideoModal() {
    const videoPlayBtn = document.getElementById('video-play-btn');
    const videoModal = document.getElementById('video-modal');
    const videoModalBackdrop = document.getElementById('video-modal-backdrop');
    const videoModalClose = document.getElementById('video-modal-close');
    const videoIframe = document.getElementById('video-iframe');

    if (!videoPlayBtn || !videoModal) return;

    const VIDEO_URL = 'https://www.youtube.com/embed/dQw4w9WgXcQ?autoplay=1&rel=0&modestbranding=1';

    const openModal = () => {
      if (videoIframe) videoIframe.src = VIDEO_URL;
      videoModal.classList.add('is-open');
      document.body.style.overflow = 'hidden';
      if (this.lenis) this.lenis.stop();
    };

    const closeModal = () => {
      videoModal.classList.remove('is-open');
      document.body.style.overflow = '';
      setTimeout(() => { if (videoIframe) videoIframe.src = ''; }, 400);
      if (this.lenis) this.lenis.start();
    };

    videoPlayBtn.addEventListener('click', openModal);
    if (videoModalClose) videoModalClose.addEventListener('click', closeModal);
    if (videoModalBackdrop) videoModalBackdrop.addEventListener('click', closeModal);

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && videoModal.classList.contains('is-open')) {
        closeModal();
      }
    });
  },

  /* ── Unified Entrance Animations (.anim-fade-up) ── */
  initSectionAnimations() {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

    gsap.utils.toArray('.anim-fade-up').forEach(el => {
      if (el.closest('#about-pain') || el.closest('#about-promise')) return;
      gsap.from(el, {
        scrollTrigger: { trigger: el, start: 'top 85%' },
        ...ANIM_DEFAULTS.fadeUp,
      });
    });
  },

  /* ── Section 2: Pain — Dedicated Animations ── */
  initPainAnimations() {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;
    const section = document.getElementById('about-pain');
    if (!section) return;

    const painHeader = section.querySelector('.about-pain-header');
    if (painHeader) {
      gsap.from(painHeader, {
        scrollTrigger: { trigger: painHeader, start: 'top 85%' },
        ...ANIM_DEFAULTS.fadeLeft,
        duration: 1,
      });
    }

    const painDivider = section.querySelector('.pain-divider-line');
    if (painDivider) {
      gsap.from(painDivider, {
        scrollTrigger: { trigger: painDivider, start: 'top 80%' },
        scaleY: 0, transformOrigin: 'top center', duration: 1.2, ease: 'power2.inOut',
      });
    }

    const painItems = gsap.utils.toArray('.pain-el');
    painItems.forEach((item, i) => {
      const iconCircle = item.querySelector('.icon-circle');
      const textContent = item.querySelector('div:last-child');

      gsap.from(item, {
        scrollTrigger: { trigger: item, start: 'top 88%' },
        ...ANIM_DEFAULTS.fadeUp,
        y: 50, delay: i * 0.08,
      });

      if (iconCircle) {
        gsap.from(iconCircle, {
          scrollTrigger: { trigger: item, start: 'top 88%' },
          scale: 0, opacity: 0, duration: 0.6, delay: 0.2 + i * 0.08, ease: 'back.out(1.7)',
        });
      }

      if (textContent) {
        gsap.from(textContent, {
          scrollTrigger: { trigger: item, start: 'top 88%' },
          ...ANIM_DEFAULTS.fadeRight,
          x: 30, duration: 0.7, delay: 0.15 + i * 0.08,
        });
      }
    });
  },

  /* ── Section 4: Promise — Dedicated Animations ── */
  initPromiseAnimations() {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;
    const section = document.getElementById('about-promise');
    if (!section) return;

    const promiseEyebrow = section.querySelector('.section-eyebrow');
    if (promiseEyebrow) {
      gsap.from(promiseEyebrow, {
        scrollTrigger: { trigger: section, start: 'top 75%' },
        ...ANIM_DEFAULTS.fadeUp,
        y: 20, duration: 0.7,
      });
    }

    const promiseTitle = section.querySelector('.section-title');
    if (promiseTitle) {
      gsap.from(promiseTitle, {
        scrollTrigger: { trigger: section, start: 'top 75%' },
        ...ANIM_DEFAULTS.fadeUp,
        duration: 0.9, delay: 0.15,
      });
    }

    const promiseTextBlock = section.querySelector('.promise-el.max-w-xl');
    if (promiseTextBlock) {
      gsap.from(promiseTextBlock, {
        scrollTrigger: { trigger: promiseTextBlock, start: 'top 85%' },
        ...ANIM_DEFAULTS.fadeUp,
      });
    }

    const highlightItems = section.querySelectorAll('.grid > .flex');
    highlightItems.forEach((item, i) => {
      const icon = item.querySelector('[data-lucide]');
      gsap.from(item, {
        scrollTrigger: { trigger: item, start: 'top 90%' },
        ...ANIM_DEFAULTS.fadeLeft,
        x: -25, duration: 0.6, delay: i * ANIM_DEFAULTS.stagger,
      });
      if (icon) {
        gsap.from(icon, {
          scrollTrigger: { trigger: item, start: 'top 90%' },
          scale: 0, opacity: 0, duration: 0.5, delay: 0.15 + i * ANIM_DEFAULTS.stagger, ease: 'back.out(2)',
        });
      }
    });

    const promiseCTA = section.querySelector('.promise-el.mt-14');
    if (promiseCTA) {
      gsap.from(promiseCTA, {
        scrollTrigger: { trigger: promiseCTA, start: 'top 90%' },
        ...ANIM_DEFAULTS.fadeUp,
        y: 30, duration: 0.7,
      });
    }
  },

  /* ── Section 3: Turning Point SVG Animations ── */
  initTurningPointAnimations() {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

    const progressCircle = document.getElementById('turning-progress-circle');
    if (progressCircle) {
      gsap.to(progressCircle, {
        scrollTrigger: { trigger: '#about-turning', start: 'top 60%' },
        strokeDashoffset: 0, duration: 2.5, ease: 'power2.inOut',
      });
    }

    gsap.utils.toArray('.turn-node').forEach((node, i) => {
      gsap.from(node, {
        scrollTrigger: { trigger: '#about-turning', start: 'top 60%' },
        scale: 0, opacity: 0, transformOrigin: 'center center',
        duration: 0.6, delay: 0.4 + i * 0.25, ease: 'back.out(1.7)',
      });
    });

    gsap.utils.toArray('.turn-arrow').forEach((arrow, i) => {
      gsap.to(arrow, {
        scrollTrigger: { trigger: '#about-turning', start: 'top 60%' },
        opacity: 1, duration: 0.8, delay: 0.8 + i * 0.25, ease: 'power2.out',
      });
    });
  },

  /* ── Section 3: Node Hover → Center Overlay ── */
  initNodeHover() {
    const centerOverlay = document.getElementById('turn-center-overlay');
    const detailTitle = document.getElementById('turn-detail-title');
    const detailDesc = document.getElementById('turn-detail-desc');
    if (!centerOverlay || !detailTitle || !detailDesc) return;

    const nodeTitles = ['Thiết Kế', 'Dự Toán', 'Vật Liệu', 'Thi Công', 'Bảo Hành'];
    const nodes = document.querySelectorAll('.turn-node');

    nodes.forEach(node => {
      node.addEventListener('mouseenter', () => {
        const desc = node.getAttribute('data-desc');
        const idx = parseInt(node.getAttribute('data-index'), 10);
        if (!desc) return;
        detailTitle.textContent = nodeTitles[idx] || '';
        detailDesc.textContent = desc;
        node.classList.add('is-active');
        centerOverlay.classList.add('is-hovering');
      });

      node.addEventListener('mouseleave', () => {
        node.classList.remove('is-active');
        centerOverlay.classList.remove('is-hovering');
      });
    });
  },

  /* ── Section 4.5: Team — Staggered Entrance ── */
  initTeamAnimations() {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

    const teamCards = gsap.utils.toArray('.team-card');
    if (!teamCards.length) return;

    gsap.from(teamCards, {
      scrollTrigger: { trigger: '#about-team', start: 'top 80%' },
      ...ANIM_DEFAULTS.fadeUp,
      stagger: 0.15,
      clearProps: 'transform',
    });
  },

  /* ── Section 5: Core Values — Staggered Cards ── */
  initCoreValuesAnimations() {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

    const cvCards = gsap.utils.toArray('.cv-card');
    if (!cvCards.length) return;

    const cvTl = gsap.timeline({
      scrollTrigger: { trigger: '#about-core-values', start: 'top 70%' },
    });

    cvTl.from(cvCards, {
      ...ANIM_DEFAULTS.fadeUp,
      y: 60, scale: 0.92, duration: 0.7,
      stagger: 0.12, ease: 'power3.out',
      clearProps: 'transform',
    });

    cvCards.forEach((card) => {
      const number = card.querySelector('.cv-card__number');
      const iconWrap = card.querySelector('.cv-card__icon-wrap');
      const title = card.querySelector('.cv-card__title');
      const desc = card.querySelector('.cv-card__desc');

      const innerTl = gsap.timeline({
        scrollTrigger: { trigger: card, start: 'top 80%' },
      });

      if (number) {
        innerTl.from(number, {
          ...ANIM_DEFAULTS.fadeRight,
          x: 20, duration: 0.5,
        }, 0.3);
      }
      if (iconWrap) {
        innerTl.from(iconWrap, {
          ...ANIM_DEFAULTS.scaleIn,
          scale: 0.7, duration: 0.5, ease: 'back.out(1.7)',
        }, 0.35);
      }
      if (title) {
        innerTl.from(title, {
          ...ANIM_DEFAULTS.fadeUp,
          y: 16, duration: 0.5,
        }, 0.45);
      }
      if (desc) {
        innerTl.from(desc, {
          ...ANIM_DEFAULTS.fadeUp,
          y: 12, duration: 0.5,
        }, 0.55);
      }
    });
  },

  /* ── Section 6: Final CTA — Timeline Entrance ── */
  initFinalCTAAnimations() {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

    const ctaSection = document.getElementById('about-final-cta');
    if (!ctaSection) return;

    const ctaEyebrow  = document.getElementById('cta-eyebrow');
    const ctaTitle    = document.getElementById('cta-title');
    const ctaSubtitle = document.getElementById('cta-subtitle');
    const ctaButtons  = document.getElementById('cta-buttons');
    const ctaImgCol   = document.getElementById('cta-image-col');

    const ctaTl = gsap.timeline({
      scrollTrigger: { trigger: ctaSection, start: 'top 75%' },
    });

    if (ctaEyebrow) {
      ctaTl.from(ctaEyebrow, {
        ...ANIM_DEFAULTS.fadeLeft,
        x: -30, duration: 0.7,
      }, 0);
    }

    if (ctaTitle) {
      ctaTl.from(ctaTitle, {
        ...ANIM_DEFAULTS.fadeUp,
        y: 44, duration: 0.95, ease: 'power3.out',
      }, 0.15);
    }

    if (ctaSubtitle) {
      ctaTl.from(ctaSubtitle, {
        ...ANIM_DEFAULTS.fadeUp,
        y: 28,
      }, 0.35);
    }

    if (ctaButtons) {
      ctaTl.from(ctaButtons, {
        ...ANIM_DEFAULTS.fadeUp,
        y: 24, duration: 0.7,
      }, 0.55);
    }

    if (ctaImgCol) {
      ctaTl.from(ctaImgCol, {
        ...ANIM_DEFAULTS.fadeRight,
        x: 60, scale: 0.96, duration: 1.1, ease: 'power3.out',
        clearProps: 'transform',
      }, 0.1);
    }
  },
};

/* ── Bootstrap ── */
document.addEventListener('DOMContentLoaded', () => {
  XanhAbout.init();
});
