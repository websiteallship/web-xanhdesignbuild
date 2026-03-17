/**
 * XANH — Design & Build
 * Portfolio Page Module
 * =============================================
 * Filter: by project category (Biệt Thự, Nhà Phố, etc.)
 * Entrance: IntersectionObserver-based staggered reveal
 * Load More: 9 extra cards (total 18)
 */

/* ── Animation Defaults (10 §3.2 — chuẩn hóa toàn site) ── */
const ANIM_DEFAULTS = {
  fadeUp:    { opacity: 0, y: 40, duration: 0.8, ease: 'power2.out' },
  fadeLeft:  { opacity: 0, x: -40, duration: 0.8, ease: 'power2.out' },
  fadeRight: { opacity: 0, x: 40, duration: 0.8, ease: 'power2.out' },
  scaleIn:   { opacity: 0, scale: 0.95, duration: 0.6, ease: 'power2.out' },
  counter:   { duration: 2, ease: 'power1.inOut' },
  stagger:   0.1,
};

const XanhPortfolio = {
  lenis: null,
  prefersReducedMotion: false,
  loadMorePage: 1,
  revealObserver: null,

  /* ── Cached DOM refs (10 §5 — avoid re-querying in loops) ── */
  els: {
  },

  init() {
    this.prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* Register GSAP plugins ONCE (10 §3.1) */
    if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
      gsap.registerPlugin(ScrollTrigger);
    }

    this.initLucide();
    this.initLenis();
    this.initHeroReveal();
    this.initFilterTabs();
    this.initLoadMore();
    this.initEntranceAnimation();
    if (!this.prefersReducedMotion) {
      this.initHeroParallax();
      this.initCounterAnimation();
    }
  },

  initLucide() {
    if (typeof lucide !== 'undefined') lucide.createIcons();
  },



  /* ── Lenis ── */
  initLenis() {
    if (typeof Lenis === 'undefined') return;
    try {
      this.lenis = new Lenis({ lerp: 0.1, smoothWheel: true, wheelMultiplier: 0.8 });
      if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
        this.lenis.on('scroll', ScrollTrigger.update);
        gsap.ticker.add(time => this.lenis.raf(time * 1000));
        gsap.ticker.lagSmoothing(0);
      }
    } catch (error) {
      console.warn('[XANH] Lenis init failed:', error.message);
    }
  },



  /* ── Hero ── */
  initHeroReveal() {
    setTimeout(() => {
      const bg = document.querySelector('.portfolio-hero__bg');
      if (bg) bg.classList.add('is-loaded');
      document.querySelectorAll('.portfolio-hero-el').forEach(el => el.classList.add('is-visible'));
    }, 300);
  },

  initHeroParallax() {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;
    try {
      const img = document.querySelector('.portfolio-hero__bg img');
      if (img) gsap.fromTo(img, { scale: 1.06 }, { scale: 1, ease: 'none', scrollTrigger: { trigger: '#portfolio-hero', start: 'top top', end: 'bottom top', scrub: 1 } });
    } catch (error) {
      console.warn('[XANH] Hero parallax init failed:', error.message);
    }
  },

  initCounterAnimation() {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;
    try {
      document.querySelectorAll('.counter-number').forEach(el => {
        const target = parseInt(el.dataset.target, 10);
        ScrollTrigger.create({ trigger: el, start: 'top 90%', once: true, onEnter: () => {
          const obj = { v: 0 };
          gsap.to(obj, { v: target, ...ANIM_DEFAULTS.counter, onUpdate: () => { el.textContent = Math.round(obj.v); } });
        }});
      });
    } catch (error) {
      console.warn('[XANH] Counter animation failed:', error.message);
    }
  },

  /* ── Entrance Animation (IntersectionObserver) ── */
  initEntranceAnimation() {
    if (this.prefersReducedMotion) {
      document.querySelectorAll('.project-card.anim-fade-up').forEach(c => c.classList.add('is-revealed'));
      return;
    }
    this.revealObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-revealed');
          this.revealObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

    document.querySelectorAll('.project-card.anim-fade-up').forEach(card => {
      this.revealObserver.observe(card);
    });
  },

  observeNewCards(cards) {
    if (!this.revealObserver || this.prefersReducedMotion) {
      cards.forEach(c => c.classList.add('is-revealed'));
      return;
    }
    cards.forEach(card => this.revealObserver.observe(card));
  },

  /* ── Filter Tabs (by category) ── */
  initFilterTabs() {
    const tabs = document.querySelectorAll('.filter-tab');

    tabs.forEach(tab => {
      tab.addEventListener('click', () => {
        tabs.forEach(t => { t.classList.remove('is-active'); t.setAttribute('aria-selected', 'false'); });
        tab.classList.add('is-active');
        tab.setAttribute('aria-selected', 'true');

        const filter = tab.dataset.filter;
        const allCards = document.querySelectorAll('#portfolio-grid .project-card');

        allCards.forEach(card => {
          const cat = card.dataset.category;
          const matches = filter === 'all' || cat === filter;

          if (matches) {
            card.classList.remove('is-hidden');
            card.classList.remove('is-revealed');
            void card.offsetWidth;
            card.classList.add('is-revealed');
          } else {
            card.classList.add('is-hidden');
          }
        });
      });
    });
  },

  /* ── Load More — 9 extra cards ── */
  initLoadMore() {
    const loadBtn = document.getElementById('load-more-btn');
    const grid = document.getElementById('portfolio-grid');
    const skeleton = document.getElementById('skeleton-loader');
    if (!loadBtn || !grid) return;

    const extraCards = [
      { title: 'Nhà Phố Tân Phú',       cat: 'nha-pho',    typeLabel: 'Nhà Phố',    badge: 'Hoàn Thành',    badgeCls: 'completed',   img: '../homepage_02/img/process-05.png', area: '135 m²', dur: '5 tháng', style: 'Contemporary', location: 'Tân Phú, TP.HCM' },
      { title: 'Penthouse District 1',   cat: 'can-ho',     typeLabel: 'Căn Hộ',     badge: 'Hoàn Thành',    badgeCls: 'completed',   img: '../homepage_02/img/process-06.png', area: '210 m²', dur: '6 tháng', style: 'Luxury Modern', location: 'Quận 1, TP.HCM' },
      { title: 'Boutique Hotel Quận 3',  cat: 'thuong-mai', typeLabel: 'Thương Mại',  badge: 'Đang Thi Công', badgeCls: 'in-progress', img: '../homepage_02/img/project-before-1.png', area: '800 m²', dur: '12 tháng', style: 'Art Deco', location: 'Quận 3, TP.HCM' },
      { title: 'Villa Đà Lạt',           cat: 'biet-thu',   typeLabel: 'Biệt Thự',   badge: 'Hoàn Thành',    badgeCls: 'completed',   img: '../about/image/section3-turning-bg.png', area: '350 m²', dur: '9 tháng', style: 'Warm Scandinavian', location: 'Đà Lạt' },
      { title: 'Căn Hộ Masteri',         cat: 'can-ho',     typeLabel: 'Căn Hộ',     badge: 'Concept',       badgeCls: 'concept',     img: '../about/image/cta-portrait.jpg', area: '85 m²', dur: '3 tháng', style: 'Neo-Classic', location: 'Thủ Đức, TP.HCM' },
      { title: 'Office Tower Phú Nhuận', cat: 'thuong-mai', typeLabel: 'Thương Mại',  badge: 'Hoàn Thành',    badgeCls: 'completed',   img: '../homepage_02/img/service-interior.png', area: '1.500 m²', dur: '16 tháng', style: 'Corporate Modern', location: 'Phú Nhuận, TP.HCM' },
      { title: 'Resort Hồ Tràm',        cat: 'nghi-duong', typeLabel: 'Nghỉ Dưỡng',  badge: 'Concept',       badgeCls: 'concept',     img: '../homepage_02/img/service-architecture.png', area: '2.000 m²', dur: '20 tháng', style: 'Organic Resort', location: 'Hồ Tràm, Bà Rịa' },
      { title: 'Nhà Phố Gò Vấp',        cat: 'nha-pho',    typeLabel: 'Nhà Phố',    badge: 'Đang Thi Công', badgeCls: 'in-progress', img: '../homepage_02/img/service-construction.png', area: '110 m²', dur: '4.5 tháng', style: 'Urban Green', location: 'Gò Vấp, TP.HCM' },
      { title: 'Villa Phú Mỹ Hưng',     cat: 'biet-thu',   typeLabel: 'Biệt Thự',   badge: 'Hoàn Thành',    badgeCls: 'completed',   img: '../homepage_02/img/service-renovation.png', area: '420 m²', dur: '11 tháng', style: 'Tropical Modern', location: 'Phú Mỹ Hưng, TP.HCM' },
    ];

    const makeCard = c => `
      <a href="#" class="project-card anim-fade-up" data-category="${c.cat}">
        <div class="project-card__image">
          <img src="${c.img}" alt="${c.title}" width="600" height="400" loading="lazy" />
          <div class="project-card__image-overlay"></div>
          <div class="project-card__light-sweep"></div>
          <span class="project-card__badge project-card__badge--${c.badgeCls}">${c.badge}</span>
        </div>
        <div class="project-card__info">
          <div class="project-card__meta">
            <span class="project-card__type">${c.typeLabel}</span>
            <span class="project-card__location"><i data-lucide="map-pin" class="w-3 h-3"></i> ${c.location}</span>
          </div>
          <h3 class="project-card__title">${c.title}</h3>
          <p class="project-card__tagline">Hoàn thiện sát 3D 98% ⎮ 0% Phát sinh</p>
          <div class="project-card__specs">
            <span class="spec-item"><i data-lucide="ruler" class="w-3.5 h-3.5"></i> ${c.area}</span>
            <span class="spec-item"><i data-lucide="calendar-days" class="w-3.5 h-3.5"></i> ${c.dur}</span>
            <span class="spec-item"><i data-lucide="palette" class="w-3.5 h-3.5"></i> ${c.style}</span>
          </div>
        </div>
      </a>`;

    loadBtn.addEventListener('click', () => {
      if (this.loadMorePage > 1) {
        loadBtn.disabled = true;
        loadBtn.querySelector('span').textContent = 'Đã Hiển Thị Tất Cả';
        const icon = loadBtn.querySelector('[data-lucide]');
        if (icon) icon.style.display = 'none';
        return;
      }

      if (skeleton) skeleton.classList.remove('hidden');
      loadBtn.disabled = true;
      loadBtn.querySelector('span').textContent = 'Đang Tải...';

      setTimeout(() => {
        if (skeleton) skeleton.classList.add('hidden');

        const frag = document.createDocumentFragment();
        const tmp = document.createElement('div');
        extraCards.forEach(c => { tmp.innerHTML = makeCard(c); frag.appendChild(tmp.firstElementChild); });
        grid.appendChild(frag);

        if (typeof lucide !== 'undefined') lucide.createIcons();

        const newCards = grid.querySelectorAll('.project-card:nth-last-child(-n+9)');
        this.observeNewCards(newCards);

        loadBtn.disabled = false;
        loadBtn.querySelector('span').textContent = 'Xem Thêm Dự Án';
        this.loadMorePage++;
      }, 800);
    });
  },
};

document.addEventListener('DOMContentLoaded', () => XanhPortfolio.init());
