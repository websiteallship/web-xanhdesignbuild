/**
 * XANH — Design & Build
 * Services Archive Page Module (WP Version)
 * =============================================
 * Entrance: IntersectionObserver-based staggered reveal
 * Load More: AJAX via xanhAjax endpoint
 */

const XanhServices = {
  lenis: null,
  prefersReducedMotion: false,
  loadMorePage: 1,
  revealObserver: null,

  init() {
    this.prefersReducedMotion = XanhBase.prefersReducedMotion();

    /* Page-specific init only — global init (Lenis, Lucide,
       GSAP register, ScrollReveal) already runs in main.js */
    XanhBase.initHeroReveal('.services-hero__bg', '.hero-el--fast', 300);
    this.initLoadMore();

    this.revealObserver = XanhBase.initScrollReveal('.service-card.anim-fade-up', { className: 'is-revealed', rootMargin: '0px 0px -40px 0px' });

    if (!this.prefersReducedMotion) {
      XanhBase.initHeroParallax('.services-hero__bg img', '#services-hero');
      XanhBase.animateCounters('.counter-number', { dataAttr: 'target', useGSAP: true });
    }
  },

  observeNewCards(cards) {
    if (!this.revealObserver || this.prefersReducedMotion) {
      cards.forEach(c => c.classList.add('is-revealed'));
      return;
    }
    cards.forEach(card => this.revealObserver.observe(card));
  },

  /* ── Load More — AJAX via xanhAjax ── */
  initLoadMore() {
    const loadBtn = document.getElementById('services-load-more-btn');
    const grid = document.getElementById('services-grid');
    const skeleton = document.getElementById('services-skeleton-loader');
    if (!loadBtn || !grid) return;

    loadBtn.addEventListener('click', () => {
      this._loadMoreCards(loadBtn, grid, skeleton);
    });
  },

  async _loadMoreCards(loadBtn, grid, skeleton) {
    this.loadMorePage++;

    if (skeleton) skeleton.classList.remove('hidden');
    loadBtn.disabled = true;
    loadBtn.querySelector('span').textContent = 'Đang Tải...';

    /* Check if xanhAjax is available for AJAX loading */
    if (typeof xanhAjax === 'undefined') {
      /* Fallback: no AJAX available */
      setTimeout(() => {
        if (skeleton) skeleton.classList.add('hidden');
        loadBtn.disabled = true;
        loadBtn.querySelector('span').textContent = 'Đã Hiển Thị Tất Cả';
        const icon = loadBtn.querySelector('[data-lucide]');
        if (icon) icon.style.display = 'none';
      }, 600);
      return;
    }

    const formData = new FormData();
    formData.append('action', 'xanh_load_more_services');
    formData.append('nonce', xanhAjax.nonce);
    formData.append('paged', this.loadMorePage);

    try {
      const res = await fetch(xanhAjax.url, { method: 'POST', body: formData });
      if (!res.ok) throw new Error(`HTTP ${res.status}`);

      const data = await res.json();
      if (skeleton) skeleton.classList.add('hidden');

      if (data.success && data.data.html) {
        const tmp = document.createElement('div');
        tmp.innerHTML = data.data.html;
        const newCards = tmp.querySelectorAll('.service-card');

        newCards.forEach(card => {
          card.classList.add('anim-fade-up');
          grid.appendChild(card);
        });

        if (typeof lucide !== 'undefined') lucide.createIcons();
        this.observeNewCards(newCards);

        /* Smooth scroll to first new card so IO triggers reveal */
        if (newCards.length) {
          requestAnimationFrame(() => {
            const firstCard = newCards[0];
            const headerOffset = 100;
            const top = firstCard.getBoundingClientRect().top + window.scrollY - headerOffset;
            window.scrollTo({ top, behavior: 'smooth' });
          });
        }

        /* Disable if no more pages */
        if (this.loadMorePage >= data.data.pages) {
          loadBtn.disabled = true;
          loadBtn.querySelector('span').textContent = 'Đã Hiển Thị Tất Cả';
          const icon = loadBtn.querySelector('[data-lucide]');
          if (icon) icon.style.display = 'none';
        } else {
          loadBtn.disabled = false;
          loadBtn.querySelector('span').textContent = 'Xem Thêm Dịch Vụ';
        }
      } else {
        loadBtn.disabled = true;
        loadBtn.querySelector('span').textContent = 'Đã Hiển Thị Tất Cả';
        const icon = loadBtn.querySelector('[data-lucide]');
        if (icon) icon.style.display = 'none';
      }
    } catch (error) {
      console.warn('[XANH] Load more services failed:', error.message);
      if (skeleton) skeleton.classList.add('hidden');
      loadBtn.disabled = false;
      loadBtn.querySelector('span').textContent = 'Xem Thêm Dịch Vụ';
    }
  },
};

document.addEventListener('DOMContentLoaded', () => XanhServices.init());
