/**
 * XANH — Design & Build
 * Portfolio Detail Page Module
 * =============================================
 * Scope: Section D1 (Breadcrumb) + Section D2 (Hero Image) + Section D3 (Stats Bar)
 *        + D4 (Story) + D5 (Before/After) + D5b (Video) + D7 (Gallery) + D8 + D9 + D10
 * Pattern: XanhPortfolioDetail object module with init()
 */

/* ── Animation Defaults: inherited from base.js (ANIM_DEFAULTS) ── */

const XanhPortfolioDetail = {
  lenis: null,
  prefersReducedMotion: false,

  init() {
    this.prefersReducedMotion = XanhBase.prefersReducedMotion();

    XanhBase.initLucide();
    this.lenis = XanhBase.initLenis({ lerp: 0.07 });
    this.initHeroReveal();

    if (!this.prefersReducedMotion) {
      this.initHeroParallax();
    }

    XanhBase.animateCounters('.stats-bar__counter', { dataAttr: 'target', duration: 1500, decimals: true });
    XanhBase.initScrollReveal('.anim-fade-up', { className: 'is-revealed' });
    this.initBeforeAfterSlider();
    this.initLightbox();
    this.initGallery();
    this.initVideoLightbox();
    this.initRelatedProjects();
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

      const mainSwiper = new Swiper(mainEl, {
        spaceBetween: 0,
        slidesPerView: 1,
        loop: true,
        allowTouchMove: false,
        thumbs: { swiper: thumbsSwiper },
      });

      // Init custom drag sliders after Swiper is ready
      this._initCustomDragSliders();

      // Re-init drag sliders when slide changes (loop creates duplicates)
      mainSwiper.on('slideChangeTransitionEnd', () => {
        this._initCustomDragSliders();
      });
    } catch (error) {
      console.warn('[XANH] Before/After slider init failed:', error.message);
    }
  },

  /* ── Custom Drag Slider Logic (pointer-based, matching homepage) ── */
  _initCustomDragSliders() {
    const sliders = document.querySelectorAll('.ba-custom-slider');
    sliders.forEach(slider => {
      // Skip if already initialized
      if (slider._dragInit) return;
      slider._dragInit = true;

      const beforeClip = slider.querySelector('.ba-custom-slider__before');
      const handle = slider.querySelector('.ba-custom-slider__handle');
      if (!beforeClip || !handle) return;

      let isDragging = false;

      function setPosition(pct) {
        pct = Math.max(0, Math.min(100, pct));
        beforeClip.style.clipPath = 'inset(0 ' + (100 - pct) + '% 0 0)';
        handle.style.left = pct + '%';
      }

      function getPercent(e) {
        const rect = slider.getBoundingClientRect();
        return ((e.clientX - rect.left) / rect.width) * 100;
      }

      slider.addEventListener('pointerdown', (e) => {
        isDragging = true;
        slider.setPointerCapture(e.pointerId);
        setPosition(getPercent(e));
      });

      slider.addEventListener('pointermove', (e) => {
        if (!isDragging) return;
        setPosition(getPercent(e));
      });

      slider.addEventListener('pointerup', () => { isDragging = false; });
      slider.addEventListener('pointercancel', () => { isDragging = false; });

      // Set initial position at 50%
      setPosition(50);
    });
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
        <div class="ba-custom-slider">
          <img src="${s.second}" alt="Thực tế — ${s.title}" class="ba-custom-slider__after" draggable="false" />
          <div class="ba-custom-slider__before">
            <img src="${s.first}" alt="Concept 3D — ${s.title}" draggable="false" />
          </div>
          <div class="ba-custom-slider__handle">
            <div class="ba-custom-slider__handle-line"></div>
            <div class="ba-custom-slider__handle-knob">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m9 18-6-6 6-6" /><path d="m15 6 6 6-6 6" />
              </svg>
            </div>
            <div class="ba-custom-slider__handle-line"></div>
          </div>
        </div>
      `;
      // Init drag on the new lightbox slider
      this._initCustomDragSliders();
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
