/**
 * XANH — Design & Build
 * Portfolio Page Module (WP Version)
 * =============================================
 * Filter: by project category via client-side + AJAX
 * Entrance: IntersectionObserver-based staggered reveal
 * Load More: AJAX via xanhAjax endpoint
 */

const XanhPortfolio = {
  lenis: null,
  prefersReducedMotion: false,
  loadMorePage: 1,
  revealObserver: null,
  currentFilter: 'all',

  init() {
    this.prefersReducedMotion = XanhBase.prefersReducedMotion();

    /* Register GSAP plugins ONCE */
    XanhBase.registerGSAP();

    XanhBase.initLucide();
    XanhBase.initLenis();
    XanhBase.initHeroReveal('.portfolio-hero__bg', '.portfolio-hero-el', 300);
    this.initFilterTabs();
    this.initLoadMore();

    this.revealObserver = XanhBase.initScrollReveal('.project-card.anim-fade-up', { className: 'is-revealed', rootMargin: '0px 0px -40px 0px' });

    if (!this.prefersReducedMotion) {
      XanhBase.initHeroParallax('.portfolio-hero__bg img', '#portfolio-hero');
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

  /* ── Filter Tabs (by category) ── */
  initFilterTabs() {
    const tabs = document.querySelectorAll('.filter-tab');

    tabs.forEach(tab => {
      tab.addEventListener('click', () => {
        tabs.forEach(t => { t.classList.remove('is-active'); t.setAttribute('aria-selected', 'false'); });
        tab.classList.add('is-active');
        tab.setAttribute('aria-selected', 'true');

        const filter = tab.dataset.filter;
        this.currentFilter = filter;
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

  /* ── Load More — AJAX via xanhAjax ── */
  initLoadMore() {
    const loadBtn = document.getElementById('load-more-btn');
    const grid = document.getElementById('portfolio-grid');
    const skeleton = document.getElementById('skeleton-loader');
    if (!loadBtn || !grid) return;

    loadBtn.addEventListener('click', () => {
      this.loadMorePage++;

      if (skeleton) skeleton.classList.remove('hidden');
      loadBtn.disabled = true;
      loadBtn.querySelector('span').textContent = 'Đang Tải...';

      /* Check if xanhAjax is available for AJAX loading */
      if (typeof xanhAjax !== 'undefined') {
        const formData = new FormData();
        formData.append('action', 'xanh_filter_projects');
        formData.append('nonce', xanhAjax.nonce);
        formData.append('type', this.currentFilter);
        formData.append('paged', this.loadMorePage);

        fetch(xanhAjax.url, {
          method: 'POST',
          body: formData,
        })
          .then(res => res.json())
          .then(data => {
            if (skeleton) skeleton.classList.add('hidden');

            if (data.success && data.data.html) {
              const tmp = document.createElement('div');
              tmp.innerHTML = data.data.html;
              const newCards = tmp.querySelectorAll('.project-card');

              newCards.forEach(card => {
                card.classList.add('anim-fade-up');
                grid.appendChild(card);
              });

              if (typeof lucide !== 'undefined') lucide.createIcons();
              this.observeNewCards(newCards);

              /* Disable if no more pages */
              if (this.loadMorePage >= data.data.pages) {
                loadBtn.disabled = true;
                loadBtn.querySelector('span').textContent = 'Đã Hiển Thị Tất Cả';
                const icon = loadBtn.querySelector('[data-lucide]');
                if (icon) icon.style.display = 'none';
              } else {
                loadBtn.disabled = false;
                loadBtn.querySelector('span').textContent = 'Xem Thêm Dự Án';
              }
            } else {
              loadBtn.disabled = true;
              loadBtn.querySelector('span').textContent = 'Đã Hiển Thị Tất Cả';
              const icon = loadBtn.querySelector('[data-lucide]');
              if (icon) icon.style.display = 'none';
            }
          })
          .catch(() => {
            if (skeleton) skeleton.classList.add('hidden');
            loadBtn.disabled = false;
            loadBtn.querySelector('span').textContent = 'Xem Thêm Dự Án';
          });
      } else {
        /* Fallback: no AJAX available */
        setTimeout(() => {
          if (skeleton) skeleton.classList.add('hidden');
          loadBtn.disabled = true;
          loadBtn.querySelector('span').textContent = 'Đã Hiển Thị Tất Cả';
          const icon = loadBtn.querySelector('[data-lucide]');
          if (icon) icon.style.display = 'none';
        }, 600);
      }
    });
  },
};

document.addEventListener('DOMContentLoaded', () => XanhPortfolio.init());
