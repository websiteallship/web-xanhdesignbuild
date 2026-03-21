/**
 * XANH — Design & Build
 * Homepage 02 Wireframe: Minnaro-inspired
 * =========================================
 * Architecture: Object Module Pattern (Rule 10)
 * Libraries: Swiper, GSAP, ScrollTrigger, Lenis, Lucide
 */

/* Shared matchMedia queries (Rule 10 §5.3) */
const mqDesktop = window.matchMedia('(min-width: 1024px)');
const mqTablet = window.matchMedia('(min-width: 640px)');
const mqReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

// XanhIcons removed, using XanhBase

/* ─── Shared Utilities (Rule 10 §3.2, §9) ──── */

/** Debounce helper — delays fn by `wait` ms (default 150). */
function xanhDebounce(fn, wait = 150) {
  let timer;
  return (...args) => {
    clearTimeout(timer);
    timer = setTimeout(() => fn.apply(null, args), wait);
  };
}

/** Shared IntersectionObserver fallback for .anim-fade-up elements. */
function observeFadeIn(section) {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          section.querySelectorAll('.anim-fade-up').forEach((el, i) => {
            setTimeout(() => {
              el.style.opacity = '1';
              el.style.transform = 'translateY(0)';
              el.style.transition = 'opacity 0.7s ease, transform 0.7s ease';
            }, i * 150);
          });
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.15 }
  );
  observer.observe(section);
}

/* XanhMobileMenu → moved to shared/base.js */
/* XanhHeader → moved to shared/base.js */

/* ─────────────────────────────────────────────── */
/* Module 5 — Hero Slider                          */
/* ─────────────────────────────────────────────── */
const XanhHero = {
  init() {
    // Hero content reveal — use shared initHeroReveal if available.
    if (typeof XanhBase !== 'undefined' && XanhBase.initHeroReveal) {
      XanhBase.initHeroReveal('.hero-swiper', '.home-hero-el');
    } else {
      // Fallback: simple is-visible toggle.
      setTimeout(() => {
        document.querySelectorAll('.home-hero-el').forEach((el) => {
          el.classList.add('is-visible');
        });
      }, 400);
    }

    // Hero Swiper (background images)
    if (typeof Swiper !== 'undefined') {
      const heroSwiper = new Swiper('.hero-swiper', {
        loop: true,
        speed: 1500,
        effect: 'fade',
        fadeEffect: { crossFade: true },
        autoplay: {
          delay: 5000,
          disableOnInteraction: false,
          pauseOnMouseEnter: true,
        },
      });

      // Custom dots — sync with Swiper
      const heroDotsContainer = document.querySelector('.hero-dots');
      if (heroDotsContainer && heroDotsContainer.querySelectorAll('.hero-dot').length) {
        // Click handler (delegated to container so cloned dots still work)
        heroDotsContainer.addEventListener('click', (e) => {
          const dot = e.target.closest('.hero-dot');
          if (!dot) return;
          const idx = parseInt(dot.dataset.goto, 10);
          heroSwiper.slideToLoop(idx);
        });

        // Slide change → update active dot + restart progress animation
        heroSwiper.on('slideChange', () => {
          const realIndex = heroSwiper.realIndex;
          // Re-query every time so references are always fresh
          const dots = heroDotsContainer.querySelectorAll('.hero-dot');
          dots.forEach((dot, i) => {
            if (i === realIndex) {
              // Restart animation by replacing the node
              dot.classList.remove('is-active');
              dot.setAttribute('aria-selected', 'false');
              // Force reflow to restart ::after animation
              const clone = dot.cloneNode(true);
              if (dot.parentNode) {
                dot.parentNode.replaceChild(clone, dot);
              }
              clone.classList.add('is-active');
              clone.setAttribute('aria-selected', 'true');
            } else {
              dot.classList.remove('is-active');
              dot.setAttribute('aria-selected', 'false');
            }
          });
        });
      }
    }

    // Video Modal
    this._initVideoModal();
  },

  /** @private — Video modal open/close */
  _initVideoModal() {
    const videoPlayBtn = document.getElementById('video-play-btn');
    const videoModal = document.getElementById('video-modal');
    const videoModalBackdrop = document.getElementById('video-modal-backdrop');
    const videoModalClose = document.getElementById('video-modal-close');
    const videoIframe = document.getElementById('video-iframe');

    if (!videoPlayBtn || !videoModal) return;

    const videoSrc = videoIframe ? videoIframe.dataset.src : '';

    const openModal = () => {
      if (videoIframe && videoSrc) videoIframe.src = videoSrc;
      videoModal.classList.add('is-open');
      document.body.style.overflow = 'hidden';
      if (typeof XanhBase !== 'undefined' && XanhBase.getLenis) {
        const lenis = XanhBase.getLenis();
        if (lenis) lenis.stop();
      }
    };

    const closeModal = () => {
      videoModal.classList.remove('is-open');
      document.body.style.overflow = '';
      setTimeout(() => { if (videoIframe) videoIframe.src = ''; }, 400);
      if (typeof XanhBase !== 'undefined' && XanhBase.getLenis) {
        const lenis = XanhBase.getLenis();
        if (lenis) lenis.start();
      }
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
};

/* ─────────────────────────────────────────────── */
/* Module 6 — GSAP Scroll Animations               */
/* ─────────────────────────────────────────────── */
const XanhScrollAnimations = {
  init() {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
      this._initFallback();
      return;
    }

    gsap.registerPlugin(ScrollTrigger);
    this._initEmpathyParallax();
    this._initHeroParallax();
    this._initUnifiedFadeUp();
    this._initCoreValuesReveal();
    this._initServicesReveal();
  },

  /** @private — Empathy section parallax */
  _initEmpathyParallax() {
    const empathyBg = document.querySelector('.empathy-bg img');
    if (!empathyBg) return;

    gsap.fromTo(empathyBg,
      { scale: 1.08 },
      {
        scale: 1,
        ease: 'none',
        scrollTrigger: {
          trigger: '#empathy',
          start: 'top bottom',
          end: 'bottom top',
          scrub: 1,
        },
      }
    );
  },

  /** @private — Hero images parallax */
  _initHeroParallax() {
    gsap.utils.toArray('.hero-swiper .swiper-slide img').forEach((img) => {
      gsap.fromTo(img,
        { scale: 1.05 },
        {
          scale: 1,
          ease: 'none',
          scrollTrigger: {
            trigger: '#hero',
            start: 'top top',
            end: 'bottom top',
            scrub: 1,
          },
        }
      );
    });
  },

  /** @private — Unified .anim-fade-up entrance (§9) */
  _initUnifiedFadeUp() {
    gsap.utils.toArray('.anim-fade-up').forEach((el) => {
      gsap.fromTo(el,
        { opacity: 0, y: 40 },
        {
          scrollTrigger: { trigger: el, start: 'top 85%', once: true },
          opacity: 1, y: 0, duration: 0.8, ease: 'power2.out',
        }
      );
    });
  },

  /** @private — Core Values items stagger reveal */
  _initCoreValuesReveal() {
    const items = document.querySelectorAll('.core-value-item');
    if (!items.length) return;

    gsap.set(items, { opacity: 0, y: 40 });

    ScrollTrigger.create({
      trigger: '#core-values',
      start: 'top 75%',
      once: true,
      onEnter: () => {
        gsap.to(items, {
          opacity: 1,
          y: 0,
          duration: 0.7,
          ease: 'power2.out',
          stagger: 0.12,
        });
      },
    });
  },

  /** @private — Services section multi-stage reveal */
  _initServicesReveal() {
    const servicesSection = document.getElementById('services');
    if (!servicesSection) return;

    const serviceCards = document.querySelectorAll('.service-card');
    if (!serviceCards.length) return;

    const servicesTl = gsap.timeline({
      scrollTrigger: {
        trigger: '#services',
        start: 'top 70%',
        once: true,
      },
    });

    // Image clip-path reveal
    serviceCards.forEach((card, i) => {
      const imgWrap = card.querySelector('.service-card__img-wrap');
      const img = card.querySelector('.service-card__img');
      if (imgWrap && img) {
        servicesTl.fromTo(imgWrap,
          { clipPath: 'inset(100% 0 0 0)' },
          { clipPath: 'inset(0% 0 0 0)', duration: 0.85, ease: 'power3.out', clearProps: 'all' },
          i === 0 ? '-=0.3' : '-=0.6'
        );
        servicesTl.fromTo(img,
          { scale: 1.12 },
          { scale: 1, duration: 0.85, ease: 'power2.out', clearProps: 'all' },
          '<'
        );
      }
    });

    // Card body content stagger
    servicesTl.fromTo(
      document.querySelectorAll('.service-card__icon'),
      { y: 16, opacity: 0 },
      { y: 0, opacity: 0.7, duration: 0.6, stagger: 0.08, ease: 'power2.out' },
      '-=1.0'
    );
    servicesTl.fromTo(
      document.querySelectorAll('.service-card__title'),
      { y: 16, opacity: 0 },
      { y: 0, opacity: 1, duration: 0.6, stagger: 0.08, ease: 'power2.out' },
      '<+0.1'
    );
    servicesTl.fromTo(
      document.querySelectorAll('.service-card__desc'),
      { y: 12, opacity: 0 },
      { y: 0, opacity: 1, duration: 0.55, stagger: 0.08, ease: 'power2.out' },
      '<+0.08'
    );
  },

  /** @private — Fallback when GSAP is not loaded */
  _initFallback() {
    document.querySelectorAll('section').forEach((sec) => observeFadeIn(sec));
  },
};

/* ─────────────────────────────────────────────── */
/* Module 7 — CTA Section                          */
/* ─────────────────────────────────────────────── */
const XanhCTA = {
  init() {
    const ctaSection = document.getElementById('cta');
    if (!ctaSection) return;

    const ctaEls = ctaSection.querySelectorAll('.gsap-el');
    const ctaImgPanel = ctaSection.querySelector('.cta-panel--image');
    const counterEls = ctaSection.querySelectorAll('.cta-badge__num[data-count]');

    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
      this._initFallback(ctaSection, ctaEls, ctaImgPanel, counterEls);
      return;
    }

    const isDesktop = mqDesktop.matches;

    gsap.set(ctaEls, { opacity: 0, y: 36 });
    if (ctaImgPanel) gsap.set(ctaImgPanel, { opacity: 0, x: 40 });

    // PC-only: individual badge & card animations
    const ctaCard = ctaSection.querySelector('.cta-card');
    const ctaBadges = ctaSection.querySelectorAll('.cta-badge');
    const ctaBadgeSeps = ctaSection.querySelectorAll('.cta-badge-sep');
    if (isDesktop && ctaCard) {
      gsap.set(ctaCard, { clipPath: 'inset(0 100% 0 0)' });
    }
    if (isDesktop && ctaBadges.length) {
      gsap.set(ctaBadges, { opacity: 0, y: 20 });
      gsap.set(ctaBadgeSeps, { opacity: 0, scaleY: 0 });
    }

    ScrollTrigger.create({
      trigger: ctaSection,
      start: 'top 78%',
      once: true,
      onEnter: () => {
        if (isDesktop) {
          this._animateDesktop(ctaCard, ctaEls, ctaImgPanel, ctaBadges, ctaBadgeSeps, counterEls);
        } else {
          this._animateMobile(ctaEls, ctaImgPanel, counterEls);
        }
      },
    });
  },

  /** @private — Counter animation */
  _animateCounters(counterEls) {
    counterEls.forEach((el) => {
      const target = parseInt(el.dataset.count, 10);
      const suffix = el.dataset.suffix || '';
      const duration = 1800;
      const start = performance.now();

      function update(now) {
        const elapsed = now - start;
        const progress = Math.min(elapsed / duration, 1);
        const ease = 1 - Math.pow(1 - progress, 4);
        el.textContent = Math.round(ease * target) + suffix;
        if (progress < 1) requestAnimationFrame(update);
      }

      requestAnimationFrame(update);
    });
  },

  /** @private — Desktop cinematic reveal */
  _animateDesktop(ctaCard, ctaEls, ctaImgPanel, ctaBadges, ctaBadgeSeps, counterEls) {
    const tl = gsap.timeline({ onComplete: () => setTimeout(() => this._animateCounters(counterEls), 200) });

    if (ctaCard) {
      tl.to(ctaCard, { clipPath: 'inset(0 0% 0 0)', duration: 0.9, ease: 'power3.out' });
    }

    tl.to(ctaEls, { opacity: 1, y: 0, duration: 0.7, ease: 'power3.out', stagger: 0.1 }, '-=0.55');

    if (ctaImgPanel) {
      tl.to(ctaImgPanel, { opacity: 1, x: 0, duration: 0.8, ease: 'power3.out' }, '-=0.6');
    }

    if (ctaBadges.length) {
      tl.to(ctaBadges, { opacity: 1, y: 0, duration: 0.5, ease: 'back.out(1.4)', stagger: 0.12 }, '-=0.3');
      tl.to(ctaBadgeSeps, { opacity: 1, scaleY: 1, duration: 0.4, transformOrigin: 'center center', ease: 'power2.out', stagger: 0.08 }, '<+0.1');
    }
  },

  /** @private — Mobile stagger fade-up */
  _animateMobile(ctaEls, ctaImgPanel, counterEls) {
    gsap.to(ctaEls, { opacity: 1, y: 0, duration: 0.75, ease: 'power3.out', stagger: 0.12 });
    if (ctaImgPanel) {
      gsap.to(ctaImgPanel, { opacity: 1, x: 0, duration: 0.9, ease: 'power3.out', delay: 0.1 });
    }
    setTimeout(() => this._animateCounters(counterEls), 500);
  },

  /** @private — Fallback for no GSAP */
  _initFallback(ctaSection, ctaEls, ctaImgPanel, counterEls) {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            ctaEls.forEach((el, i) => {
              setTimeout(() => el.classList.add('is-visible'), i * 120);
            });
            if (ctaImgPanel) ctaImgPanel.classList.add('is-visible');
            setTimeout(() => this._animateCounters(counterEls), 500);
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.15 }
    );
    observer.observe(ctaSection);
  },
};

/* ─────────────────────────────────────────────── */
/* Module 8 — Before / After Slider + Thumbnails   */
/* ─────────────────────────────────────────────── */
const XanhProjects = {
  currentIndex: 0,
  isDragging: false,
  thumbsSwiper: null,

  /** @private — Read project data from HTML data-* attributes */
  _readProjectsFromDOM() {
    const thumbs = document.querySelectorAll('.project-thumb');
    return Array.from(thumbs).map((btn) => ({
      beforeImg: btn.dataset.beforeImg,
      afterImg: btn.dataset.afterImg,
      tag: btn.dataset.tag,
      title: btn.dataset.title,
      meta: `
        <span class="meta-item"><i data-lucide="maximize"></i> ${btn.dataset.area}</span>
        <span class="meta-sep"></span>
        <span class="meta-item"><i data-lucide="clock"></i> ${btn.dataset.duration}</span>
        <span class="meta-sep"></span>
        <span class="meta-item"><i data-lucide="home"></i> ${btn.dataset.type}</span>
      `,
      quote: btn.dataset.quote,
      author: btn.dataset.author,
    }));
  },

  init() {
    const slider = document.getElementById('ba-slider');
    if (!slider) return;

    // Build projects array from DOM data-* attributes
    this.PROJECTS = this._readProjectsFromDOM();

    // Cache DOM refs
    this._els = {
      slider,
      beforeClip: document.getElementById('ba-before-clip'),
      beforeImg: document.getElementById('ba-before-img'),
      afterImg: document.getElementById('ba-after-img'),
      handle: document.getElementById('ba-handle'),
      tagEl: document.getElementById('ba-tag'),
      titleEl: document.getElementById('ba-title'),
      metaEl: document.getElementById('ba-meta'),
      quoteEl: document.getElementById('ba-quote'),
      authorEl: document.getElementById('ba-author'),
      thumbs: document.querySelectorAll('.project-thumb'),
    };

    this._initThumbsSwiper();
    this._initDragLogic();
    this._initThumbnailClicks();
    this._initScrollAnimations();
  },

  /** @private — Thumbs Swiper (desktop) */
  _initThumbsSwiper() {
    if (typeof Swiper === 'undefined') return;

    const slides = document.querySelectorAll('.projects-thumbs-swiper .swiper-slide');
    const slideCount = slides.length;

    this.thumbsSwiper = new Swiper('.projects-thumbs-swiper', {
      slidesPerView: 2,
      spaceBetween: 12,
      loop: true,
      loopedSlides: slideCount,
      watchOverflow: true,
      navigation: {
        prevEl: '.projects-thumbs-prev',
        nextEl: '.projects-thumbs-next',
      },
      pagination: {
        el: '.thumbs-pagination',
        clickable: true,
        dynamicBullets: false,
      },
      breakpoints: {
        640: { slidesPerView: 3, spaceBetween: 16 },
        1024: { slidesPerView: 3, spaceBetween: 20 },
      },
    });

    // Mobile bottom nav
    const thumbsMobPrev = document.querySelector('.thumbs-mobile-prev');
    const thumbsMobNext = document.querySelector('.thumbs-mobile-next');
    if (thumbsMobPrev) thumbsMobPrev.addEventListener('click', () => this.thumbsSwiper.slidePrev());
    if (thumbsMobNext) thumbsMobNext.addEventListener('click', () => this.thumbsSwiper.slideNext());

    // Pagination bullets entrance animation (desktop only)
    if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined' && window.innerWidth >= 640) {
      const thumbsPagination = document.querySelector('.thumbs-pagination');
      if (thumbsPagination) {
        ScrollTrigger.create({
          trigger: thumbsPagination,
          start: 'top 90%',
          once: true,
          onEnter: () => {
            const bullets = thumbsPagination.querySelectorAll('.swiper-pagination-bullet');
            gsap.fromTo(bullets,
              { opacity: 0, scaleX: 0, transformOrigin: 'left center' },
              { opacity: 1, scaleX: 1, duration: 0.4, ease: 'power2.out', stagger: 0.06 }
            );
          },
        });
      }
    }

    // Mobile Projects Swiper (≤1023px only)
    if (!mqDesktop.matches) {
      const mobileSwiper = new Swiper('.projects-mobile-swiper', {
        slidesPerView: 1,
        spaceBetween: 16,
        loop: true,
        allowTouchMove: true,
        grabCursor: false,
        noSwiping: true,
        noSwipingSelector: '.ba-custom-slider',
        pagination: {
          el: '.thumbs-pagination',
          clickable: true,
        },
      });

      if (thumbsMobPrev) thumbsMobPrev.addEventListener('click', () => mobileSwiper.slidePrev());
      if (thumbsMobNext) thumbsMobNext.addEventListener('click', () => mobileSwiper.slideNext());

      // Init drag sliders for mobile cards
      this._initMobileDragSliders();
      mobileSwiper.on('slideChangeTransitionEnd', () => this._initMobileDragSliders());
    }
  },

  /** @private — Init drag sliders for mobile BA cards */
  _initMobileDragSliders() {
    document.querySelectorAll('.ba-custom-slider').forEach(slider => {
      if (slider._dragInit) return;
      slider._dragInit = true;

      const beforeClip = slider.querySelector('.ba-custom-slider__before');
      const handle = slider.querySelector('.ba-custom-slider__handle');
      if (!beforeClip || !handle) return;

      let isDragging = false;

      function setPos(pct) {
        pct = Math.max(0, Math.min(100, pct));
        beforeClip.style.clipPath = 'inset(0 ' + (100 - pct) + '% 0 0)';
        handle.style.left = pct + '%';
      }

      function getPct(e) {
        const r = slider.getBoundingClientRect();
        return ((e.clientX - r.left) / r.width) * 100;
      }

      slider.addEventListener('pointerdown', (e) => {
        e.stopPropagation();
        isDragging = true;
        slider.setPointerCapture(e.pointerId);
        setPos(getPct(e));
      });
      slider.addEventListener('pointermove', (e) => {
        if (!isDragging) return;
        setPos(getPct(e));
      });
      slider.addEventListener('pointerup', () => { isDragging = false; });
      slider.addEventListener('pointercancel', () => { isDragging = false; });
      setPos(50);
    });
  },

  /** @private — Before/After drag logic */
  _initDragLogic() {
    const { slider, handle } = this._els;

    slider.addEventListener('pointerdown', (e) => {
      this.isDragging = true;
      slider.setPointerCapture(e.pointerId);
      this._setPosition(this._getPercentFromEvent(e));
    });

    slider.addEventListener('pointermove', (e) => {
      if (!this.isDragging) return;
      this._setPosition(this._getPercentFromEvent(e));
    });

    slider.addEventListener('pointerup', () => { this.isDragging = false; });
    slider.addEventListener('pointercancel', () => { this.isDragging = false; });

    // Initial position
    window.addEventListener('load', () => this._setPosition(50));
    this._setPosition(50);
  },

  /** @private */
  _setPosition(pct) {
    pct = Math.max(0, Math.min(100, pct));
    this._els.beforeClip.style.clipPath = 'inset(0 ' + (100 - pct) + '% 0 0)';
    this._els.handle.style.left = pct + '%';
  },

  /** @private */
  _getPercentFromEvent(e) {
    const rect = this._els.slider.getBoundingClientRect();
    return ((e.clientX - rect.left) / rect.width) * 100;
  },

  /** @private — Thumbnail click → switch project */
  _initThumbnailClicks() {
    this._els.thumbs.forEach((btn) => {
      btn.addEventListener('click', () => {
        const idx = parseInt(btn.dataset.index, 10);
        this._switchProject(idx);
      });
    });
  },

  /** @private */
  _switchProject(index) {
    if (index === this.currentIndex) return;
    this.currentIndex = index;
    const proj = this.PROJECTS[index];
    const { afterImg, beforeImg, tagEl, titleEl, metaEl, quoteEl, authorEl, thumbs } = this._els;

    // Update active thumbnail
    thumbs.forEach((t) => t.classList.remove('is-active'));
    if (thumbs[index]) thumbs[index].classList.add('is-active');

    // Slide clicked thumb to first position
    if (this.thumbsSwiper) {
      this.thumbsSwiper.slideToLoop(index);
    }

    // Cross-fade
    if (typeof gsap !== 'undefined') {
      const tl = gsap.timeline();
      tl.to([afterImg, this._els.beforeClip], { opacity: 0, duration: 0.3, ease: 'power2.in' })
        .to('#ba-info', { opacity: 0, y: 12, duration: 0.25, ease: 'power2.in' }, '<')
        .call(() => {
          afterImg.src = proj.afterImg;
          beforeImg.src = proj.beforeImg;
          tagEl.textContent = proj.tag;
          titleEl.textContent = proj.title;
          metaEl.innerHTML = proj.meta;
          if (typeof lucide !== 'undefined') lucide.createIcons();
          quoteEl.textContent = proj.quote;
          authorEl.textContent = proj.author;
          this._setPosition(50);
        })
        .to([afterImg, this._els.beforeClip], { opacity: 1, duration: 0.35, ease: 'power2.out' })
        .to('#ba-info', { opacity: 1, y: 0, duration: 0.35, ease: 'power2.out' }, '<0.1');
    } else {
      afterImg.src = proj.afterImg;
      beforeImg.src = proj.beforeImg;
      tagEl.textContent = proj.tag;
      titleEl.textContent = proj.title;
      metaEl.innerHTML = proj.meta;
      if (typeof lucide !== 'undefined') lucide.createIcons();
      quoteEl.textContent = proj.quote;
      authorEl.textContent = proj.author;
      this._setPosition(50);
    }
  },

  /** @private — GSAP scroll entrance animations */
  _initScrollAnimations() {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

    const projectsSection = document.getElementById('projects');
    if (!projectsSection) return;

    const headerEls = projectsSection.querySelectorAll('.projects-el');
    const sliderWrap = projectsSection.querySelector('.ba-slider-wrap');
    const infoPanel = projectsSection.querySelector('.ba-info');
    const infoChildren = infoPanel
      ? infoPanel.querySelectorAll('.ba-info__tag, .ba-info__title, .ba-info__meta, .ba-info__quote, .ba-info__author, .ba-info__cta')
      : [];
    const thumbSlides = projectsSection.querySelectorAll('.projects-thumbs-swiper .swiper-slide');
    const thumbsNav = projectsSection.querySelector('.thumbs-nav');
    const handleKnob = projectsSection.querySelector('.ba-slider__handle-knob');
    const handle = this._els.handle;

    // Set initial states
    gsap.set(headerEls, { opacity: 0, y: 40 });
    if (sliderWrap) gsap.set(sliderWrap, { opacity: 0, x: -60, scale: 0.96 });
    if (infoChildren.length) gsap.set(infoChildren, { opacity: 0, x: 30 });
    if (thumbSlides.length) gsap.set(thumbSlides, { opacity: 0, y: 30, scale: 0.95 });
    if (thumbsNav) gsap.set(thumbsNav, { opacity: 0, y: 20 });

    ScrollTrigger.create({
      trigger: projectsSection,
      start: 'top 80%',
      once: true,
      onEnter: () => {
        const tl = gsap.timeline();

        tl.to(headerEls, { opacity: 1, y: 0, duration: 0.7, ease: 'power3.out', stagger: 0.12 });

        if (sliderWrap) {
          tl.to(sliderWrap, { opacity: 1, x: 0, scale: 1, duration: 0.8, ease: 'power3.out' }, '-=0.3');
        }

        if (infoChildren.length) {
          tl.to(infoChildren, { opacity: 1, x: 0, duration: 0.6, ease: 'power3.out', stagger: 0.08 }, '-=0.5');
        }

        if (thumbSlides.length) {
          tl.to(thumbSlides, { opacity: 1, y: 0, scale: 1, duration: 0.6, ease: 'back.out(1.4)', stagger: 0.1 }, '-=0.3');
        }

        if (thumbsNav) {
          tl.to(thumbsNav, { opacity: 1, y: 0, duration: 0.5, ease: 'power3.out' }, '-=0.2');
          const bullets = thumbsNav.querySelectorAll('.swiper-pagination-bullet');
          if (bullets.length) {
            tl.fromTo(bullets,
              { opacity: 0, scaleX: 0, transformOrigin: 'left center' },
              { opacity: 1, scaleX: 1, duration: 0.4, ease: 'power2.out', stagger: 0.06 },
              '-=0.1'
            );
          }
        }

        // Slider handle wiggle hint
        if (handleKnob && handle) {
          const self = this;
          tl.call(() => self._setPosition(35), null, '+=0.4')
            .to(handle, {
              left: '35%', duration: 0.5, ease: 'power2.inOut',
              onUpdate: () => { self._setPosition(parseFloat(handle.style.left)); },
            }, '<')
            .to(handle, {
              left: '65%', duration: 0.7, ease: 'power2.inOut',
              onUpdate: () => { self._setPosition(parseFloat(handle.style.left)); },
            })
            .to(handle, {
              left: '50%', duration: 0.5, ease: 'power2.inOut',
              onUpdate: () => { self._setPosition(parseFloat(handle.style.left)); },
            });
        }
      },
    });
  },
};

/* ─────────────────────────────────────────────── */
/* Module 9 — Process Steps Accordion              */
/* ─────────────────────────────────────────────── */
const XanhProcess = {
  init() {
    const section = document.getElementById('process');
    if (!section) return;

    const panels = section.querySelectorAll('.process-panel');
    if (!panels.length) return;

    // Click handler: toggle active panel
    panels.forEach((panel) => {
      panel.addEventListener('click', () => {
        if (panel.classList.contains('is-active')) return;
        panels.forEach((p) => p.classList.remove('is-active'));
        panel.classList.add('is-active');
      });
    });

    // GSAP entrance for panels (desktop only)
    if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
      const isDesktop = mqDesktop.matches;
      if (isDesktop) {
        gsap.set(panels, { opacity: 0, y: 50, scale: 0.97 });
        ScrollTrigger.create({
          trigger: section,
          start: 'top 80%',
          once: true,
          onEnter: () => {
            gsap.to(panels, { opacity: 1, y: 0, scale: 1, duration: 0.65, ease: 'power3.out', stagger: 0.1 });
          },
        });
      }
    } else {
      // Fallback
      observeFadeIn(section);
    }
  },
};

/* ─────────────────────────────────────────────── */
/* Module 10 — CTA Contact (BG Parallax)           */
/* ─────────────────────────────────────────────── */
const XanhCTAContact = {
  init() {
    const section = document.getElementById('cta-contact');
    if (!section) return;

    const bgImg = section.querySelector('.cta-contact__bg');

    if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
      if (bgImg) {
        gsap.fromTo(bgImg,
          { scale: 1.1 },
          {
            scale: 1,
            ease: 'none',
            scrollTrigger: {
              trigger: section,
              start: 'top bottom',
              end: 'bottom top',
              scrub: 1,
            },
          }
        );
      }
    } else {
      // Fallback
      observeFadeIn(section);
    }
  },
};

/* ─────────────────────────────────────────────── */
/* Module 11 — Partner Logos Bar                    */
/* ─────────────────────────────────────────────── */
const XanhPartners = {
  _swiper: null,

  init() {
    const section = document.getElementById('partners');
    if (!section) return;

    if (typeof Swiper !== 'undefined') {
      this._swiper = new Swiper('.partners-swiper', {
        loop: true,
        speed: 3000,
        autoplay: {
          delay: 0,
          disableOnInteraction: false,
          pauseOnMouseEnter: true,
        },
        slidesPerView: 2,
        spaceBetween: 24,
        freeMode: true,
        freeModeMomentum: false,
        allowTouchMove: true,
        breakpoints: {
          640: { slidesPerView: 3, spaceBetween: 32 },
          768: { slidesPerView: 4, spaceBetween: 40 },
          1024: { slidesPerView: 5, spaceBetween: 48 },
        },
      });
    }

    // IntersectionObserver for non-GSAP fade-in
    observeFadeIn(section);
  },

  destroy() {
    if (this._swiper) {
      this._swiper.destroy(true, true);
      this._swiper = null;
    }
  },
};

/* ─────────────────────────────────────────────── */
/* Module 12 — Blog Swiper + Scroll Animation      */
/* ─────────────────────────────────────────────── */
const XanhBlog = {
  init() {
    const section = document.getElementById('blog');
    if (!section) return;

    this._initSwiper(section);
    this._initScrollAnimation(section);
  },

  /** @private */
  _initSwiper(section) {
    if (typeof Swiper === 'undefined') return;

    this._blogSwiper = new Swiper('#blog-swiper', {
      slidesPerView: 1,
      spaceBetween: 20,
      loop: true,
      grabCursor: true,
      pagination: {
        el: '.blog-pagination',
        clickable: true,
      },
      breakpoints: {
        640: { slidesPerView: 2, spaceBetween: 24 },
        1024: { slidesPerView: 3, spaceBetween: 28 },
      },
    });

    // Manual prev/next handlers
    const sliderWrap = section.querySelector('.blog-slider-wrap');
    const prevBtn = sliderWrap ? sliderWrap.querySelector('.blog-nav__prev') : null;
    const nextBtn = sliderWrap ? sliderWrap.querySelector('.blog-nav__next') : null;
    if (prevBtn) prevBtn.addEventListener('click', () => this._blogSwiper.slidePrev());
    if (nextBtn) nextBtn.addEventListener('click', () => this._blogSwiper.slideNext());

    // Mobile bottom nav
    const mobilePrev = section.querySelector('.blog-nav .blog-nav__prev');
    const mobileNext = section.querySelector('.blog-nav .blog-nav__next');
    if (mobilePrev) mobilePrev.addEventListener('click', () => this._blogSwiper.slidePrev());
    if (mobileNext) mobileNext.addEventListener('click', () => this._blogSwiper.slideNext());

    // Dynamic vertical centering of side arrows
    this._updateBlogImgCenter = xanhDebounce(() => {
      if (!sliderWrap) return;
      const firstImg = sliderWrap.querySelector('.blog-card__img');
      if (firstImg && firstImg.offsetHeight > 0) {
        sliderWrap.style.setProperty('--blog-img-center', (firstImg.offsetHeight / 2) + 'px');
      }
    });
    window.addEventListener('load', this._updateBlogImgCenter);
    window.addEventListener('resize', this._updateBlogImgCenter);
    this._updateBlogImgCenter();
  },

  /** @private */
  _initScrollAnimation(section) {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
      this._initFallback(section);
      return;
    }

    const isDesktop = mqDesktop.matches;
    const visibleSlides = section.querySelectorAll('.blog-swiper .swiper-slide:not(.swiper-slide-duplicate)');
    const visibleCards = Array.from(visibleSlides).slice(0, 3);

    if (isDesktop && visibleCards.length) {
      visibleCards.forEach((slide) => {
        const imgLink = slide.querySelector('.blog-card__img-link');
        const img = slide.querySelector('.blog-card__img');
        const body = slide.querySelector('.blog-card__body');
        if (imgLink) gsap.set(imgLink, { clipPath: 'inset(100% 0 0 0)' });
        if (img) gsap.set(img, { scale: 1.1 });
        if (body) gsap.set(body, { opacity: 0, y: 20 });
      });
    }

    ScrollTrigger.create({
      trigger: section,
      start: 'top 80%',
      once: true,
      onEnter: () => {
        const tl = gsap.timeline();

        if (isDesktop && visibleCards.length) {
          visibleCards.forEach((slide, i) => {
            const imgLink = slide.querySelector('.blog-card__img-link');
            const img = slide.querySelector('.blog-card__img');
            const body = slide.querySelector('.blog-card__body');
            const offset = i === 0 ? '-=0.4' : '<+0.12';

            if (imgLink) tl.to(imgLink, { clipPath: 'inset(0% 0 0 0)', duration: 0.75, ease: 'power3.out' }, offset);
            if (img) tl.to(img, { scale: 1, duration: 0.75, ease: 'power2.out' }, '<');
            if (body) tl.to(body, { opacity: 1, y: 0, duration: 0.55, ease: 'power2.out' }, '<+0.25');
          });
        }

        // Stagger-in pagination bullets
        if (mqTablet.matches) {
          const blogPagination = section.querySelector('.blog-pagination');
          if (blogPagination) {
            const bullets = blogPagination.querySelectorAll('.swiper-pagination-bullet');
            gsap.fromTo(bullets,
              { opacity: 0, scaleX: 0, transformOrigin: 'left center' },
              { opacity: 1, scaleX: 1, duration: 0.4, ease: 'power2.out', stagger: 0.06, delay: 0.5 }
            );
          }
        }
      },
    });
  },

  /** @private */
  _initFallback(section) {
    observeFadeIn(section);
  },

  destroy() {
    if (this._blogSwiper) {
      this._blogSwiper.destroy(true, true);
      this._blogSwiper = null;
    }
    if (this._updateBlogImgCenter) {
      window.removeEventListener('load', this._updateBlogImgCenter);
      window.removeEventListener('resize', this._updateBlogImgCenter);
    }
  },
};

/* ─────────────────────────────────────────────── */
/* Module 13 — Side Arrows Hover Reveal (PC only)  */
/* ─────────────────────────────────────────────── */
const XanhSideArrows = {
  init() {
    if (!window.matchMedia('(hover: hover)').matches) return;

    this._setupHoverReveal('.blog-slider-wrap', '.blog-slider-wrap>.blog-nav__btn');
    this._setupHoverReveal('.projects-thumbs-wrapper', '.projects-thumbs-wrapper>.thumbs-nav__btn');
    this._initThumbsImgCenter();
  },

  /** @private */
  _setupHoverReveal(wrapperSelector, btnSelector) {
    const wrapper = document.querySelector(wrapperSelector);
    if (!wrapper) return;

    const buttons = wrapper.querySelectorAll(btnSelector);
    if (!buttons.length) return;

    let hideTimer = null;
    const DELAY = 400;

    function showButtons() {
      clearTimeout(hideTimer);
      buttons.forEach((btn) => btn.classList.add('is-visible'));
    }

    function scheduleHide() {
      hideTimer = setTimeout(() => {
        buttons.forEach((btn) => btn.classList.remove('is-visible'));
      }, DELAY);
    }

    wrapper.addEventListener('mouseenter', showButtons);
    wrapper.addEventListener('mouseleave', scheduleHide);
    buttons.forEach((btn) => {
      btn.addEventListener('mouseenter', showButtons);
      btn.addEventListener('mouseleave', scheduleHide);
    });
  },

  /** @private — Dynamic vertical center for thumbs arrows */
  _initThumbsImgCenter() {
    const thumbsWrap = document.querySelector('.projects-thumbs-wrapper');
    if (!thumbsWrap) return;

    function updateCenter() {
      const imgWrap = thumbsWrap.querySelector('.project-thumb__img-wrap');
      if (imgWrap && imgWrap.offsetHeight > 0) {
        thumbsWrap.style.setProperty('--thumbs-img-center', (imgWrap.offsetHeight / 2) + 'px');
      }
    }

    const debouncedUpdate = xanhDebounce(updateCenter);
    window.addEventListener('load', updateCenter);
    window.addEventListener('resize', debouncedUpdate);
    updateCenter();
  },
};

/* ─────────────────────────────────────────────── */
/* BOOTSTRAP — Initialize all modules              */
/* ─────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
  /* Homepage-specific modules only.
   * Global modules (XanhBase, XanhMobileMenu, XanhHeader, Lenis)
   * are already initialized in main.js — do NOT call them again here.
   */
  XanhHero.init();

  if (mqReducedMotion.matches) {
    // Reduced motion: skip GSAP animations, just reveal everything
    document.querySelectorAll('.anim-fade-up, .gsap-el').forEach((el) => {
      el.style.opacity = '1';
      el.style.transform = 'none';
    });
  } else {
    XanhScrollAnimations.init();
    XanhCTA.init();
    XanhCTAContact.init();
  }

  XanhProjects.init();
  XanhProcess.init();
  XanhPartners.init();
  XanhBlog.init();
  XanhSideArrows.init();
});
