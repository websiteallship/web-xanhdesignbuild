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
    this.initBeforeAfterSlider();
    this.initLightbox();
    this.initGallery();
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
      link.style.transform = 'translateX(-16px)';
      setTimeout(() => {
        link.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        link.style.opacity = '1';
        link.style.transform = 'translateX(0)';
      }, 80 + i * 60);
    });
  },

  updateHamburgerColor(btn) {
    if (!btn) return;
    const header = document.querySelector('.site-header');
    if (!header) return;
    const isScrolled = header.classList.contains('is-scrolled');
    btn.style.color = isScrolled ? '' : 'white';
  },

  /* ── Lenis Smooth Scroll ── */
  initLenis() {
    if (typeof Lenis === 'undefined') return;
    try {
      this.lenis = new Lenis({ duration: 1.2, easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)) });
      const raf = (time) => {
        this.lenis.raf(time);
        requestAnimationFrame(raf);
      };
      requestAnimationFrame(raf);
    } catch (err) {
      console.warn('[XANH] Lenis init failed:', err.message);
    }
  },

  /* ── Header Scroll State ── */
  initHeaderScroll() {
    const header = document.querySelector('.site-header');
    if (!header) return;

    const update = () => {
      const scrolled = window.scrollY > 50;
      header.classList.toggle('is-scrolled', scrolled);
      const btn = document.getElementById('mobile-menu-btn');
      if (btn && !this.isDrawerOpen) {
        btn.style.color = scrolled ? '' : 'white';
      }
    };

    window.addEventListener('scroll', update, { passive: true });
    update();
  },

  /* ── Hero Reveal ── */
  initHeroReveal() {
    const hero = document.getElementById('hero');
    if (!hero) return;
    setTimeout(() => {
      hero.querySelectorAll('.hero-reveal').forEach((el) => el.classList.add('is-visible'));
    }, 200);
  },

  /* ── Hero Parallax ── */
  initHeroParallax() {
    const heroImg = document.querySelector('#hero .hero-img');
    if (!heroImg) return;

    const onScroll = () => {
      const scrollY = window.scrollY;
      heroImg.style.transform = `translateY(${scrollY * 0.3}px)`;
    };
    window.addEventListener('scroll', onScroll, { passive: true });
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

  /* ── Before/After Slider (D5) — Swiper + img-comparison-slider ── */
  initBeforeAfterSlider() {
    if (typeof Swiper === 'undefined') {
      console.warn('[XANH] Swiper not loaded — Before/After slider skipped.');
      return;
    }

    const mainEl = document.getElementById('ba-main-swiper');
    const thumbsEl = document.getElementById('ba-thumbs-swiper');
    if (!mainEl || !thumbsEl) return;

    try {
      // 1) Initialize Thumbs Swiper first
      const thumbsSwiper = new Swiper(thumbsEl, {
        spaceBetween: 12,
        slidesPerView: 4,
        loop: true,
        watchSlidesProgress: true,
        navigation: {
          prevEl: '.ba-nav-prev',
          nextEl: '.ba-nav-next',
        },
        pagination: {
          el: '.ba-thumbs-pagination',
          clickable: true,
          dynamicBullets: false,
        },
        breakpoints: {
          0:   { slidesPerView: 3, spaceBetween: 8 },
          640: { slidesPerView: 4, spaceBetween: 12 },
        },
      });

      // Wire mobile prev/next buttons to thumbsSwiper
      const mobPrev = document.querySelector('.ba-thumbs-mobile-prev');
      const mobNext = document.querySelector('.ba-thumbs-mobile-next');
      if (mobPrev) mobPrev.addEventListener('click', () => thumbsSwiper.slidePrev());
      if (mobNext) mobNext.addEventListener('click', () => thumbsSwiper.slideNext());

      // 2) Initialize Main Swiper linked to thumbs
      new Swiper(mainEl, {
        spaceBetween: 0,
        slidesPerView: 1,
        loop: true,
        allowTouchMove: false, // Prevent swipe conflict with comparison drag
        thumbs: {
          swiper: thumbsSwiper,
        },
      });

      console.log('[XANH] Before/After slider initialized.');
    } catch (error) {
      console.warn('[XANH] Before/After slider init failed:', error.message);
    }
  },

  /* ── Lightbox for Before/After (D5) ── */
  initLightbox() {
    const lightbox = document.getElementById('ba-lightbox');
    const sliderWrap = document.getElementById('ba-lightbox-slider');
    const titleEl = document.getElementById('ba-lightbox-title');
    const counterEl = document.getElementById('ba-lightbox-counter');
    const closeBtn = document.getElementById('ba-lightbox-close');
    const backdrop = lightbox ? lightbox.querySelector('.ba-lightbox__backdrop') : null;
    const thumbsWrap = document.getElementById('ba-lightbox-thumbs');
    const prevBtn = document.getElementById('ba-lb-prev');
    const nextBtn = document.getElementById('ba-lb-next');
    if (!lightbox || !sliderWrap || !closeBtn) return;

    // Collect slide data from ORIGINAL zoom buttons only (exclude Swiper loop duplicates)
    const zoomBtns = Array.from(
      document.querySelectorAll('.swiper-slide:not(.swiper-slide-duplicate) > .ba-slide > .ba-zoom-btn')
    );
    if (!zoomBtns.length) return;

    const slides = zoomBtns.map((btn) => ({
      first: btn.dataset.first,
      second: btn.dataset.second,
      title: btn.dataset.title || '',
      thumb: btn.dataset.second.replace('w=1920&h=1080', 'w=300&h=200'),
    }));

    let currentIndex = 0;

    // Build lightbox thumbnails
    if (thumbsWrap) {
      thumbsWrap.innerHTML = slides.map((s, i) => `
        <button class="ba-lb-thumb${i === 0 ? ' is-active' : ''}" type="button" data-index="${i}" aria-label="${s.title}">
          <div class="ba-lb-thumb__img">
            <img src="${s.thumb}" alt="${s.title}" width="300" height="200" loading="lazy" />
          </div>
          <span class="ba-lb-thumb__name">${s.title.split(' — ')[0].split(' & ')[0]}</span>
          <div class="ba-lb-thumb__bar"></div>
        </button>
      `).join('');
    }

    const buildSlider = (index) => {
      const s = slides[index];
      sliderWrap.innerHTML = `
        <img-comparison-slider class="ba-comparison">
          <img slot="first" src="${s.first}" alt="Concept 3D — ${s.title}" />
          <img slot="second" src="${s.second}" alt="Thực tế — ${s.title}" />
          <div slot="handle" class="ba-handle">
            <div class="ba-handle__line"></div>
            <div class="ba-handle__knob">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m9 18-6-6 6-6" />
                <path d="m15 6 6 6-6 6" />
              </svg>
            </div>
            <div class="ba-handle__line"></div>
          </div>
        </img-comparison-slider>
      `;
      titleEl.textContent = s.title;
      if (counterEl) counterEl.textContent = `${index + 1} / ${slides.length}`;
      // Update thumb active states
      if (thumbsWrap) {
        thumbsWrap.querySelectorAll('.ba-lb-thumb').forEach((t, i) => {
          t.classList.toggle('is-active', i === index);
        });
        // Scroll active thumb into view (manual, avoids parent scroll)
        const activeThumb = thumbsWrap.querySelector('.ba-lb-thumb.is-active');
        if (activeThumb) {
          const thumbRect = activeThumb.getBoundingClientRect();
          const wrapRect = thumbsWrap.getBoundingClientRect();
          const scrollTarget = thumbsWrap.scrollLeft + (thumbRect.left - wrapRect.left) - (wrapRect.width / 2) + (thumbRect.width / 2);
          thumbsWrap.scrollTo({ left: scrollTarget, behavior: 'smooth' });
        }
      }
      currentIndex = index;
    };

    const goTo = (index) => {
      const wrapped = ((index % slides.length) + slides.length) % slides.length;
      buildSlider(wrapped);
    };

    const open = (startIndex) => {
      buildSlider(startIndex);
      lightbox.classList.add('is-open');
      document.body.style.overflow = 'hidden';
    };

    const close = () => {
      lightbox.classList.remove('is-open');
      document.body.style.overflow = '';
      setTimeout(() => { sliderWrap.innerHTML = ''; }, 350);
    };

    // Delegate click on zoom buttons — match by title (handles Swiper duplicates)
    document.addEventListener('click', (e) => {
      const btn = e.target.closest('.ba-zoom-btn');
      if (btn) {
        e.preventDefault();
        e.stopPropagation();
        const title = btn.dataset.title || '';
        const idx = slides.findIndex((s) => s.title === title);
        open(idx >= 0 ? idx : 0);
      }
    });

    // Thumb clicks inside lightbox
    if (thumbsWrap) {
      thumbsWrap.addEventListener('click', (e) => {
        const thumb = e.target.closest('.ba-lb-thumb');
        if (thumb) {
          goTo(parseInt(thumb.dataset.index, 10));
        }
      });
    }

    // Prev / Next arrows
    if (prevBtn) prevBtn.addEventListener('click', () => goTo(currentIndex - 1));
    if (nextBtn) nextBtn.addEventListener('click', () => goTo(currentIndex + 1));

    // Close handlers
    closeBtn.addEventListener('click', close);
    if (backdrop) backdrop.addEventListener('click', close);

    // Keyboard: Escape, ←, →
    document.addEventListener('keydown', (e) => {
      if (!lightbox.classList.contains('is-open')) return;
      if (e.key === 'Escape') close();
      if (e.key === 'ArrowLeft') goTo(currentIndex - 1);
      if (e.key === 'ArrowRight') goTo(currentIndex + 1);
    });
  },

  /* ── D7 Gallery — GLightbox + GSAP stagger entrance ── */
  initGallery() {
    const section = document.getElementById('d7-gallery');
    if (!section) return;

    // 1) GLightbox — touch, keyboard, loop, fade
    if (typeof GLightbox !== 'undefined') {
      try {
        GLightbox({
          selector: '[data-glightbox]',
          touchNavigation: true,
          keyboardNavigation: true,
          loop: true,
          openEffect: 'fade',
          closeEffect: 'fade',
          slideEffect: 'slide',
          moreLength: 0,
        });
        console.log('[XANH] GLightbox gallery initialized.');
      } catch (err) {
        console.warn('[XANH] GLightbox init failed:', err.message);
      }
    }

    // 2) GSAP stagger fade-up for gallery items
    const items = section.querySelectorAll('.gallery-grid__item');
    if (!items.length) return;

    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
      // Fallback: show items immediately
      items.forEach((el) => { el.style.opacity = '1'; });
      return;
    }

    gsap.set(items, { opacity: 0, y: 30 });

    ScrollTrigger.create({
      trigger: section,
      start: 'top 80%',
      once: true,
      onEnter: () => {
        gsap.to(items, {
          opacity: 1,
          y: 0,
          duration: 0.65,
          ease: 'power3.out',
          stagger: 0.07,
        });
      },
    });
  },

};

document.addEventListener('DOMContentLoaded', () => XanhPortfolioDetail.init());
