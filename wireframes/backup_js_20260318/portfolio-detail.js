/**
 * XANH — Design & Build
 * Portfolio Detail Page Module
 * =============================================
 * Scope: Section D1 (Breadcrumb) + Section D2 (Hero Image) + Section D3 (Stats Bar)
 *        + D4 (Story) + D5 (Before/After) + D5b (Video) + D7 (Gallery) + D8 + D9 + D10
 * Pattern: XanhPortfolioDetail object module with init()
 */

/* ── Animation Defaults (08-cross-section-consistency) ── */
const ANIM_DEFAULTS = {
  fadeUp:   { opacity: 0, y: 40, duration: 0.8, ease: 'power2.out' },
  fadeLeft: { opacity: 0, x: -40, duration: 0.8, ease: 'power2.out' },
  fadeRight:{ opacity: 0, x: 40, duration: 0.8, ease: 'power2.out' },
  scaleIn:  { opacity: 0, scale: 0.95, duration: 0.6, ease: 'power2.out' },
  stagger:  0.1,
};

const XanhPortfolioDetail = {
  lenis: null,
  prefersReducedMotion: false,

  init() {
    this.prefersReducedMotion = window.matchMedia(
      '(prefers-reduced-motion: reduce)'
    ).matches;

    this.initLucide();
    this.initLenis();
    this.initHeroReveal();

    if (!this.prefersReducedMotion) {
      this.initHeroParallax();
    }

    this.initStatsCounter();
    this.initEntranceAnimations();
    this.initBeforeAfterSlider();
    this.initLightbox();
    this.initGallery();
    this.initVideoLightbox();
    this.initRelatedProjects();
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



  /* ── Lenis Smooth Scroll — synced with GSAP ticker ── */
  initLenis() {
    if (typeof Lenis === 'undefined') return;
    if (typeof gsap === 'undefined') return;

    try {
      this.lenis = new Lenis({
        lerp: 0.07,
        smoothWheel: true,
        wheelMultiplier: 0.8,
      });

      // Sync with GSAP ScrollTrigger (NOT rAF loop)
      if (typeof ScrollTrigger !== 'undefined') {
        this.lenis.on('scroll', ScrollTrigger.update);
      }
      gsap.ticker.add((time) => this.lenis.raf(time * 1000));
      gsap.ticker.lagSmoothing(0);
    } catch (err) {
      console.warn('[XANH] Lenis init failed:', err.message);
    }
  },



  /* ── Hero Reveal ── */
  initHeroReveal() {
    const hero = document.getElementById('detail-hero');
    if (!hero) return;

    const bg = hero.querySelector('.detail-hero__bg');
    if (bg) {
      const img = bg.querySelector('img');
      if (img && img.complete) {
        bg.classList.add('is-loaded');
      } else if (img) {
        img.addEventListener('load', () => bg.classList.add('is-loaded'));
      }
    }

    const revealEls = hero.querySelectorAll(
      '.breadcrumb--hero, .detail-hero__title, .detail-hero__eyebrow, .detail-hero__tagline'
    );
    setTimeout(() => {
      revealEls.forEach((el) => el.classList.add('is-visible'));
    }, 200);
  },

  /* ── Hero Parallax — rAF throttled ── */
  initHeroParallax() {
    const heroImg = document.querySelector('#detail-hero .detail-hero__bg img');
    if (!heroImg) return;

    let ticking = false;
    window.addEventListener('scroll', () => {
      if (!ticking) {
        requestAnimationFrame(() => {
          heroImg.style.transform = `translateY(${window.scrollY * 0.15}px) scale(1)`;
          ticking = false;
        });
        ticking = true;
      }
    }, { passive: true });
  },

  /* ── Stats Bar Counter Animation ── */
  initStatsCounter() {
    const counters = document.querySelectorAll('.stats-bar__counter');
    if (!counters.length) return;

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

    const animateCounter = (el) => {
      const target = parseFloat(el.dataset.target);
      const decimals = parseInt(el.dataset.decimals || '0', 10);
      const duration = 1500;
      const start = performance.now();

      const tick = (now) => {
        const elapsed = now - start;
        const progress = Math.min(elapsed / duration, 1);
        const eased = 1 - (1 - progress) * (1 - progress);
        const current = eased * target;
        el.textContent = decimals > 0
          ? current.toFixed(decimals)
          : Math.round(current).toString();
        if (progress < 1) requestAnimationFrame(tick);
      };
      requestAnimationFrame(tick);
    };

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.querySelectorAll('.stats-bar__counter')
              .forEach((el) => animateCounter(el));
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

    els.forEach((el) => {
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

      const mobPrev = document.querySelector('.ba-thumbs-mobile-prev');
      const mobNext = document.querySelector('.ba-thumbs-mobile-next');
      if (mobPrev) mobPrev.addEventListener('click', () => thumbsSwiper.slidePrev());
      if (mobNext) mobNext.addEventListener('click', () => thumbsSwiper.slideNext());

      new Swiper(mainEl, {
        spaceBetween: 0,
        slidesPerView: 1,
        loop: true,
        allowTouchMove: false,
        thumbs: { swiper: thumbsSwiper },
      });
    } catch (error) {
      console.warn('[XANH] Before/After slider init failed:', error.message);
    }
  },

  /* ══════════════════════════════════════════
     Lightbox for Before/After (D5)
     Split into sub-methods per Rule 10 §1.1
     ══════════════════════════════════════════ */
  initLightbox() {
    const lightbox = document.getElementById('ba-lightbox');
    const sliderWrap = document.getElementById('ba-lightbox-slider');
    const closeBtn = document.getElementById('ba-lightbox-close');
    if (!lightbox || !sliderWrap || !closeBtn) return;

    const slides = this._collectLightboxSlides();
    if (!slides.length) return;

    this._buildLightboxThumbs(lightbox, slides);
    this._bindLightboxEvents(lightbox, sliderWrap, slides, closeBtn);
  },

  _collectLightboxSlides() {
    const zoomBtns = Array.from(
      document.querySelectorAll(
        '.swiper-slide:not(.swiper-slide-duplicate) > .ba-slide > .ba-zoom-btn'
      )
    );
    return zoomBtns.map((btn) => ({
      first: btn.dataset.first,
      second: btn.dataset.second,
      title: btn.dataset.title || '',
      thumb: btn.dataset.second.replace('w=1920&h=1080', 'w=300&h=200'),
    }));
  },

  _buildLightboxThumbs(lightbox, slides) {
    const thumbsWrap = document.getElementById('ba-lightbox-thumbs');
    if (!thumbsWrap) return;

    thumbsWrap.innerHTML = slides.map((s, i) => `
      <button class="ba-lb-thumb${i === 0 ? ' is-active' : ''}"
              type="button" data-index="${i}" aria-label="${s.title}">
        <div class="ba-lb-thumb__img">
          <img src="${s.thumb}" alt="${s.title}"
               width="300" height="200" loading="lazy" />
        </div>
        <span class="ba-lb-thumb__name">${s.title.split(' — ')[0].split(' & ')[0]}</span>
        <div class="ba-lb-thumb__bar"></div>
      </button>
    `).join('');
  },

  _bindLightboxEvents(lightbox, sliderWrap, slides, closeBtn) {
    const titleEl = document.getElementById('ba-lightbox-title');
    const counterEl = document.getElementById('ba-lightbox-counter');
    const thumbsWrap = document.getElementById('ba-lightbox-thumbs');
    const backdrop = lightbox.querySelector('.ba-lightbox__backdrop');
    const prevBtn = document.getElementById('ba-lb-prev');
    const nextBtn = document.getElementById('ba-lb-next');

    let currentIndex = 0;

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
                <path d="m9 18-6-6 6-6" /><path d="m15 6 6 6-6 6" />
              </svg>
            </div>
            <div class="ba-handle__line"></div>
          </div>
        </img-comparison-slider>
      `;
      if (titleEl) titleEl.textContent = s.title;
      if (counterEl) counterEl.textContent = `${index + 1} / ${slides.length}`;
      this._updateLightboxThumbs(thumbsWrap, index);
      currentIndex = index;
    };

    const goTo = (index) => {
      const wrapped = ((index % slides.length) + slides.length) % slides.length;
      if (wrapped === currentIndex) return;
      sliderWrap.classList.add('is-fading');
      setTimeout(() => {
        buildSlider(wrapped);
        sliderWrap.classList.remove('is-fading');
      }, 250);
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

    // Event delegation for zoom buttons
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

    if (thumbsWrap) {
      thumbsWrap.addEventListener('click', (e) => {
        const thumb = e.target.closest('.ba-lb-thumb');
        if (thumb) goTo(parseInt(thumb.dataset.index, 10));
      });
    }

    if (prevBtn) prevBtn.addEventListener('click', () => goTo(currentIndex - 1));
    if (nextBtn) nextBtn.addEventListener('click', () => goTo(currentIndex + 1));
    closeBtn.addEventListener('click', close);
    if (backdrop) backdrop.addEventListener('click', close);

    document.addEventListener('keydown', (e) => {
      if (!lightbox.classList.contains('is-open')) return;
      if (e.key === 'Escape') close();
      if (e.key === 'ArrowLeft') goTo(currentIndex - 1);
      if (e.key === 'ArrowRight') goTo(currentIndex + 1);
    });
  },

  _updateLightboxThumbs(thumbsWrap, index) {
    if (!thumbsWrap) return;
    thumbsWrap.querySelectorAll('.ba-lb-thumb').forEach((t, i) => {
      t.classList.toggle('is-active', i === index);
    });
    const activeThumb = thumbsWrap.querySelector('.ba-lb-thumb.is-active');
    if (activeThumb) {
      const thumbRect = activeThumb.getBoundingClientRect();
      const wrapRect = thumbsWrap.getBoundingClientRect();
      const scrollTarget = thumbsWrap.scrollLeft
        + (thumbRect.left - wrapRect.left)
        - (wrapRect.width / 2) + (thumbRect.width / 2);
      thumbsWrap.scrollTo({ left: scrollTarget, behavior: 'smooth' });
    }
  },

  /* ── Video Hero — GLightbox for video play button ── */
  initVideoLightbox() {
    if (typeof GLightbox === 'undefined') return;
    try {
      GLightbox({
        selector: '.glightbox-video',
        touchNavigation: true,
        openEffect: 'fade',
        closeEffect: 'fade',
      });
    } catch (err) {
      console.warn('[XANH] Video GLightbox init failed:', err.message);
    }
  },

  /* ══════════════════════════════════════════
     D7 Gallery — Split into sub-methods
     ══════════════════════════════════════════ */
  initGallery() {
    const section = document.getElementById('d7-gallery');
    if (!section) return;

    this._initGalleryLightbox(section);
    this._initGalleryAnimations(section);
    this._initGalleryLoadMore(section);
  },

  _initGalleryLightbox(section) {
    const lightbox = document.getElementById('gallery-lb');
    const lbImg = document.getElementById('gallery-lb-img');
    const lbTitle = document.getElementById('gallery-lb-title');
    const lbCounter = document.getElementById('gallery-lb-counter');
    const lbThumbs = document.getElementById('gallery-lb-thumbs');
    const links = section.querySelectorAll('[data-gallery-lb]');

    if (!lightbox || !links.length) return;

    const slides = [];
    links.forEach((link) => {
      const thumbImg = link.querySelector('img');
      slides.push({
        href: link.getAttribute('href'),
        title: link.getAttribute('data-gallery-title') || '',
        thumbSrc: thumbImg ? thumbImg.getAttribute('src') : '',
      });
    });

    let currentIndex = 0;

    // Build thumbnails via createElement (avoid innerHTML with data)
    slides.forEach((slide, i) => {
      const btn = document.createElement('button');
      btn.className = 'gallery-lb-thumb';
      btn.type = 'button';
      btn.setAttribute('aria-label', slide.title);
      const imgWrap = document.createElement('div');
      imgWrap.className = 'gallery-lb-thumb__img';
      const img = document.createElement('img');
      img.src = slide.thumbSrc;
      img.alt = slide.title;
      img.loading = 'lazy';
      imgWrap.appendChild(img);
      const nameSpan = document.createElement('span');
      nameSpan.className = 'gallery-lb-thumb__name';
      nameSpan.textContent = slide.title;
      btn.appendChild(imgWrap);
      btn.appendChild(nameSpan);
      btn.addEventListener('click', () => goTo(i));
      lbThumbs.appendChild(btn);
    });

    const thumbBtns = lbThumbs.querySelectorAll('.gallery-lb-thumb');

    function goTo(index) {
      if (index < 0) index = slides.length - 1;
      if (index >= slides.length) index = 0;
      currentIndex = index;
      const slide = slides[index];
      lbImg.src = slide.href;
      lbImg.alt = slide.title;
      lbTitle.textContent = slide.title;
      lbCounter.textContent = `${index + 1} / ${slides.length}`;
      thumbBtns.forEach((t, i) => t.classList.toggle('is-active', i === index));
      const activeThumb = thumbBtns[index];
      if (activeThumb) {
        activeThumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
      }
    }

    function open(index) {
      goTo(index);
      lightbox.classList.add('is-open');
      document.body.style.overflow = 'hidden';
    }

    function close() {
      lightbox.classList.remove('is-open');
      document.body.style.overflow = '';
    }

    links.forEach((link, i) => {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        open(i);
      });
    });

    document.getElementById('gallery-lb-close').addEventListener('click', close);
    lightbox.querySelector('.gallery-lb__backdrop').addEventListener('click', close);
    document.getElementById('gallery-lb-prev').addEventListener('click', () => goTo(currentIndex - 1));
    document.getElementById('gallery-lb-next').addEventListener('click', () => goTo(currentIndex + 1));

    document.addEventListener('keydown', (e) => {
      if (!lightbox.classList.contains('is-open')) return;
      if (e.key === 'Escape') close();
      if (e.key === 'ArrowLeft') goTo(currentIndex - 1);
      if (e.key === 'ArrowRight') goTo(currentIndex + 1);
    });
  },

  _initGalleryAnimations(section) {
    const items = section.querySelectorAll('.gallery-grid__item');
    if (!items.length) return;

    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
      items.forEach((el) => { el.style.opacity = '1'; });
      return;
    }

    gsap.registerPlugin(ScrollTrigger);
    gsap.set(items, { opacity: 0, y: ANIM_DEFAULTS.fadeUp.y });

    const grid = section.querySelector('.gallery-grid');
    if (!grid) return;

    ScrollTrigger.create({
      trigger: grid,
      start: 'top 85%',
      once: true,
      onEnter: () => {
        gsap.to(items, {
          opacity: 1,
          y: 0,
          duration: ANIM_DEFAULTS.fadeUp.duration,
          ease: ANIM_DEFAULTS.fadeUp.ease,
          stagger: ANIM_DEFAULTS.stagger,
        });
      },
    });
  },

  _initGalleryLoadMore(section) {
    const grid = document.getElementById('gallery-grid');
    const loadMoreBtn = document.getElementById('gallery-load-more');
    if (!grid || !loadMoreBtn) return;

    loadMoreBtn.addEventListener('click', () => {
      grid.classList.add('is-expanded');

      if (typeof gsap !== 'undefined') {
        const hiddenItems = grid.querySelectorAll('.gallery-grid__item:nth-child(n+5)');
        gsap.fromTo(hiddenItems,
          { opacity: 0, y: 20 },
          {
            opacity: 1,
            y: 0,
            duration: 0.5,
            ease: ANIM_DEFAULTS.fadeUp.ease,
            stagger: 0.06,
          }
        );
      }

      // Switch to "all shown" disabled state
      loadMoreBtn.disabled = true;
      loadMoreBtn.innerHTML = '<span>Đã Hiển Thị Tất Cả</span>';
    });
  },

  /* ── D9: Related Projects — Scroll Reveal ── */
  initRelatedProjects() {
    const cards = document.querySelectorAll('.d9-related .project-card.anim-fade-up');
    if (!cards.length) return;

    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const card = entry.target;
          const index = Array.from(cards).indexOf(card);
          const delay = index * 120;
          setTimeout(() => card.classList.add('is-revealed'), delay);
          observer.unobserve(card);
        }
      });
    }, { threshold: 0.15 });

    cards.forEach((card) => observer.observe(card));
  },

};

document.addEventListener('DOMContentLoaded', () => XanhPortfolioDetail.init());
