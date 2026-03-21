/**
 * XANH — Design & Build
 * About Page Module (WordPress)
 * =============================================
 * Libraries: GSAP, ScrollTrigger, Lenis, Lucide (loaded globally by main.js)
 * Pattern:   XanhAbout module
 *
 * main.js already initialises: Lucide, Lenis, ScrollReveal, Header, MobileMenu.
 * This module ONLY adds About-specific logic.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

const XanhAbout = {
  // Shared state.
  lenis: null,
  prefersReducedMotion: false,

  /* ── Entry Point ── */
  init() {
    this.prefersReducedMotion = typeof XanhBase !== 'undefined'
      ? XanhBase.prefersReducedMotion()
      : window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Grab Lenis instance from global module.
    if (typeof XanhBase !== 'undefined' && XanhBase.getLenis) {
      this.lenis = XanhBase.getLenis();
    }

    // Hero entrance — delegate to Base if available.
    if (typeof XanhBase !== 'undefined' && XanhBase.initHeroReveal) {
      XanhBase.initHeroReveal('.about-hero__bg', '.hero-el--slow');
    }

    this.initVideoModal();

    // Animations — only if user allows motion.
    if (!this.prefersReducedMotion) {
      if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
        // Hero parallax.
        if (typeof XanhBase !== 'undefined' && XanhBase.initHeroParallax) {
          XanhBase.initHeroParallax('.about-hero__bg img', '#about-hero');
        }
        this.initSectionAnimations();
        this.initPainAnimations();
        this.initPromiseAnimations();
        this.initTurningPointAnimations();
        this.initNodeHover();
        this.initTeamAnimations();
        this.initCoreValuesAnimations();
        this.initFinalCTAAnimations();
      } else {
        // GSAP unavailable → IO fallback.
        this.initFallbackAnimations();
        this.initNodeHover();
      }
    } else {
      // Reduced motion → show everything immediately.
      this.initReducedMotionFallback();
    }
  },

  /* ── Video Modal — delegate to shared XanhBase ── */
  initVideoModal() {
    if (typeof XanhBase !== 'undefined' && XanhBase.initVideoModal) {
      XanhBase.initVideoModal();
    }
  },

  /* ── Unified Entrance Animations (.anim-fade-up) ── */
  initSectionAnimations() {
    gsap.utils.toArray('.anim-fade-up').forEach(el => {
      if (el.closest('#about-pain') || el.closest('#about-promise')) return;
      gsap.fromTo(el,
        { opacity: 0, y: 40 },
        { scrollTrigger: { trigger: el, start: 'top 85%', once: true }, opacity: 1, y: 0, duration: 0.8, ease: 'power2.out' }
      );
    });
  },

  /* ── Section 2: Pain — Dedicated Animations ── */
  initPainAnimations() {
    const section = document.getElementById('about-pain');
    if (!section) return;

    const painHeader = section.querySelector('.about-pain-header');
    if (painHeader) {
      gsap.fromTo(painHeader,
        { opacity: 0, x: -40 },
        { scrollTrigger: { trigger: painHeader, start: 'top 85%', once: true }, opacity: 1, x: 0, duration: 1, ease: 'power2.out' }
      );
    }

    const painDivider = section.querySelector('.pain-divider-line');
    if (painDivider) {
      gsap.fromTo(painDivider,
        { scaleY: 0 },
        { scrollTrigger: { trigger: painDivider, start: 'top 80%', once: true }, scaleY: 1, transformOrigin: 'top center', duration: 1.2, ease: 'power2.inOut' }
      );
    }

    const painItems = gsap.utils.toArray('.pain-el');
    painItems.forEach((item, i) => {
      const iconCircle = item.querySelector('.icon-circle');
      const textContent = item.querySelector('div:last-child');

      gsap.fromTo(item,
        { opacity: 0, y: 50 },
        { scrollTrigger: { trigger: item, start: 'top 88%', once: true }, opacity: 1, y: 0, duration: 0.8, ease: 'power2.out', delay: i * 0.08 }
      );

      if (iconCircle) {
        gsap.fromTo(iconCircle,
          { scale: 0, opacity: 0 },
          { scrollTrigger: { trigger: item, start: 'top 88%', once: true }, scale: 1, opacity: 1, duration: 0.6, delay: 0.2 + i * 0.08, ease: 'back.out(1.7)' }
        );
      }

      if (textContent) {
        gsap.fromTo(textContent,
          { opacity: 0, x: 30 },
          { scrollTrigger: { trigger: item, start: 'top 88%', once: true }, opacity: 1, x: 0, duration: 0.7, ease: 'power2.out', delay: 0.15 + i * 0.08 }
        );
      }
    });
  },

  /* ── Section 4: Promise — Dedicated Animations ── */
  initPromiseAnimations() {
    const section = document.getElementById('about-promise');
    if (!section) return;

    const STAGGER = 0.1;

    const promiseWrappers = section.querySelectorAll('.anim-fade-up');
    promiseWrappers.forEach((wrapper, i) => {
      gsap.fromTo(wrapper,
        { opacity: 0, y: 40 },
        { scrollTrigger: { trigger: section, start: 'top 80%', once: true }, opacity: 1, y: 0, duration: 0.8, ease: 'power2.out', delay: i * 0.15 }
      );
    });

    const promiseEyebrow = section.querySelector('.section-eyebrow');
    if (promiseEyebrow) {
      gsap.fromTo(promiseEyebrow,
        { opacity: 0, y: 20 },
        { scrollTrigger: { trigger: section, start: 'top 75%', once: true }, opacity: 1, y: 0, duration: 0.7, ease: 'power2.out' }
      );
    }

    const promiseTitle = section.querySelector('.section-title');
    if (promiseTitle) {
      gsap.fromTo(promiseTitle,
        { opacity: 0, y: 40 },
        { scrollTrigger: { trigger: section, start: 'top 75%', once: true }, opacity: 1, y: 0, duration: 0.9, delay: 0.15, ease: 'power2.out' }
      );
    }

    const promiseTextBlock = section.querySelector('.promise-el.max-w-xl');
    if (promiseTextBlock) {
      gsap.fromTo(promiseTextBlock,
        { opacity: 0, y: 40 },
        { scrollTrigger: { trigger: promiseTextBlock, start: 'top 85%', once: true }, opacity: 1, y: 0, duration: 0.8, ease: 'power2.out' }
      );
    }

    const highlightItems = section.querySelectorAll('.grid > .flex');
    highlightItems.forEach((item, i) => {
      const icon = item.querySelector('[data-lucide]');
      gsap.fromTo(item,
        { opacity: 0, x: -25 },
        { scrollTrigger: { trigger: item, start: 'top 90%', once: true }, opacity: 1, x: 0, duration: 0.6, ease: 'power2.out', delay: i * STAGGER }
      );
      if (icon) {
        gsap.fromTo(icon,
          { scale: 0, opacity: 0 },
          { scrollTrigger: { trigger: item, start: 'top 90%', once: true }, scale: 1, opacity: 1, duration: 0.5, delay: 0.15 + i * STAGGER, ease: 'back.out(2)' }
        );
      }
    });

    const promiseCTA = section.querySelector('.promise-el.mt-14');
    if (promiseCTA) {
      gsap.fromTo(promiseCTA,
        { opacity: 0, y: 30 },
        { scrollTrigger: { trigger: promiseCTA, start: 'top 90%', once: true }, opacity: 1, y: 0, duration: 0.7, ease: 'power2.out' }
      );
    }
  },

  /* ── Section 3: Turning Point SVG Animations ── */
  initTurningPointAnimations() {
    const progressCircle = document.getElementById('turning-progress-circle');
    if (progressCircle) {
      gsap.to(progressCircle, {
        scrollTrigger: { trigger: '#about-turning', start: 'top 60%', once: true },
        strokeDashoffset: 0, duration: 2.5, ease: 'power2.inOut',
      });
    }

    gsap.utils.toArray('.turn-node').forEach((node, i) => {
      gsap.from(node, {
        scrollTrigger: { trigger: '#about-turning', start: 'top 60%', once: true },
        scale: 0, opacity: 0, transformOrigin: 'center center',
        duration: 0.6, delay: 0.4 + i * 0.25, ease: 'back.out(1.7)',
      });
    });

    gsap.utils.toArray('.turn-arrow').forEach((arrow, i) => {
      gsap.to(arrow, {
        scrollTrigger: { trigger: '#about-turning', start: 'top 60%', once: true },
        opacity: 1, duration: 0.8, delay: 0.8 + i * 0.25, ease: 'power2.out',
      });
    });
  },

  /* ── Section 3: Node Hover → Center Overlay ── */
  initNodeHover() {
    const svg = document.getElementById('turning-circle-svg');
    const centerOverlay = document.getElementById('turn-center-overlay');
    const detailTitle = document.getElementById('turn-detail-title');
    const detailDesc = document.getElementById('turn-detail-desc');
    if (!svg || !centerOverlay || !detailTitle || !detailDesc) return;

    const nodeTitles = ['Thiết Kế', 'Dự Toán', 'Vật Liệu', 'Thi Công', 'Bảo Hành'];

    // Event delegation — 1 listener on SVG instead of N listeners on nodes
    svg.addEventListener('mouseover', (e) => {
      const node = e.target.closest('.turn-node');
      if (!node) return;
      const desc = node.getAttribute('data-desc');
      const idx = parseInt(node.getAttribute('data-index'), 10);
      if (!desc) return;
      detailTitle.textContent = nodeTitles[idx] || '';
      detailDesc.textContent = desc;
      node.classList.add('is-active');
      centerOverlay.classList.add('is-hovering');
    });

    svg.addEventListener('mouseout', (e) => {
      const node = e.target.closest('.turn-node');
      if (!node) return;
      node.classList.remove('is-active');
      centerOverlay.classList.remove('is-hovering');
    });
  },

  /* ── Section 4.5: Team — Staggered Entrance ── */
  initTeamAnimations() {
    const teamCards = gsap.utils.toArray('.team-card');
    if (!teamCards.length) return;

    teamCards.forEach((card, i) => {
      gsap.fromTo(card,
        { opacity: 0, y: 40 },
        { scrollTrigger: { trigger: '#about-team', start: 'top 80%', once: true }, opacity: 1, y: 0, duration: 0.8, ease: 'power2.out', delay: i * 0.15, clearProps: 'transform' }
      );
    });
  },

  /* ── Section 5: Core Values — Staggered Cards ── */
  initCoreValuesAnimations() {
    const cvCards = gsap.utils.toArray('.cv-card');
    if (!cvCards.length) return;

    cvCards.forEach((card, i) => {
      gsap.fromTo(card,
        { opacity: 0, y: 60, scale: 0.92 },
        { scrollTrigger: { trigger: '#about-core-values', start: 'top 70%', once: true }, opacity: 1, y: 0, scale: 1, duration: 0.7, ease: 'power3.out', delay: i * 0.12, clearProps: 'transform' }
      );

      const number = card.querySelector('.cv-card__number');
      const iconWrap = card.querySelector('.cv-card__icon-wrap');
      const title = card.querySelector('.cv-card__title');
      const desc = card.querySelector('.cv-card__desc');

      if (number) {
        gsap.fromTo(number,
          { opacity: 0, x: 20 },
          { scrollTrigger: { trigger: card, start: 'top 80%', once: true }, opacity: 1, x: 0, duration: 0.5, delay: 0.3, ease: 'power2.out' }
        );
      }
      if (iconWrap) {
        gsap.fromTo(iconWrap,
          { opacity: 0, scale: 0.7 },
          { scrollTrigger: { trigger: card, start: 'top 80%', once: true }, opacity: 1, scale: 1, duration: 0.5, delay: 0.35, ease: 'back.out(1.7)' }
        );
      }
      if (title) {
        gsap.fromTo(title,
          { opacity: 0, y: 16 },
          { scrollTrigger: { trigger: card, start: 'top 80%', once: true }, opacity: 1, y: 0, duration: 0.5, delay: 0.45, ease: 'power2.out' }
        );
      }
      if (desc) {
        gsap.fromTo(desc,
          { opacity: 0, y: 12 },
          { scrollTrigger: { trigger: card, start: 'top 80%', once: true }, opacity: 1, y: 0, duration: 0.5, delay: 0.55, ease: 'power2.out' }
        );
      }
    });
  },

  /* ── Section 6: Final CTA — Timeline Entrance ── */
  initFinalCTAAnimations() {
    const ctaSection = document.getElementById('about-final-cta');
    if (!ctaSection) return;

    const ctaEyebrow  = document.getElementById('cta-eyebrow');
    const ctaTitle    = document.getElementById('cta-title');
    const ctaSubtitle = document.getElementById('cta-subtitle');
    const ctaButtons  = document.getElementById('cta-buttons');
    const ctaImgCol   = document.getElementById('cta-image-col');

    const ctaTl = gsap.timeline({
      scrollTrigger: { trigger: ctaSection, start: 'top 75%', once: true },
    });

    if (ctaEyebrow) {
      ctaTl.fromTo(ctaEyebrow,
        { opacity: 0, x: -30 },
        { opacity: 1, x: 0, duration: 0.7, ease: 'power2.out' },
      0);
    }

    if (ctaTitle) {
      ctaTl.fromTo(ctaTitle,
        { opacity: 0, y: 44 },
        { opacity: 1, y: 0, duration: 0.95, ease: 'power3.out' },
      0.15);
    }

    if (ctaSubtitle) {
      ctaTl.fromTo(ctaSubtitle,
        { opacity: 0, y: 28 },
        { opacity: 1, y: 0, duration: 0.8, ease: 'power2.out' },
      0.35);
    }

    if (ctaButtons) {
      ctaTl.fromTo(ctaButtons,
        { opacity: 0, y: 24 },
        { opacity: 1, y: 0, duration: 0.7, ease: 'power2.out' },
      0.55);
    }

    if (ctaImgCol) {
      ctaTl.fromTo(ctaImgCol,
        { opacity: 0, x: 60, scale: 0.96 },
        { opacity: 1, x: 0, scale: 1, duration: 1.1, ease: 'power3.out', clearProps: 'transform' },
      0.1);
    }
  },

  /* ── Fallback: IntersectionObserver (no GSAP) ── */
  initFallbackAnimations() {
    const animSelectors = [
      '.anim-fade-up',
      '.anim-fade-left',
      '.anim-fade-right',
      '.anim-scale-in',
      '.pain-el',
      '.promise-el',
      '.team-card',
      '.cv-card',
    ];

    const allAnimEls = document.querySelectorAll(animSelectors.join(','));
    if (!allAnimEls.length) return;

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            const el = entry.target;
            el.style.transition = 'opacity 0.7s ease, transform 0.7s ease';
            el.style.opacity = '1';
            el.style.transform = 'none';
            el.classList.add('is-visible');
            observer.unobserve(el);
          }
        });
      },
      { threshold: 0.1 }
    );

    allAnimEls.forEach((el) => observer.observe(el));

    // Also reveal section-specific elements.
    const sectionObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            const section = entry.target;
            const children = section.querySelectorAll(animSelectors.join(','));
            children.forEach((child, i) => {
              setTimeout(() => {
                child.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                child.style.opacity = '1';
                child.style.transform = 'none';
                child.classList.add('is-visible');
              }, i * 100);
            });
            sectionObserver.unobserve(section);
          }
        });
      },
      { threshold: 0.15 }
    );

    document.querySelectorAll('section').forEach((sec) => sectionObserver.observe(sec));
  },

  /* ── Reduced Motion: show everything immediately ── */
  initReducedMotionFallback() {
    const allAnimEls = document.querySelectorAll(
      '.anim-fade-up, .anim-fade-left, .anim-fade-right, .anim-scale-in, .pain-el, .promise-el, .team-card, .cv-card'
    );
    allAnimEls.forEach((el) => {
      el.style.opacity = '1';
      el.style.transform = 'none';
      el.classList.add('is-visible');
    });
  },
};

/* ── Bootstrap ── */
document.addEventListener('DOMContentLoaded', () => {
  XanhAbout.init();
});
