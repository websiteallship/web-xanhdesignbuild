/**
 * XANH — Design & Build
 * Blog List Page Scripts (WordPress version)
 * =============================================
 * Module: XanhBlog — Hero, Search, Animations, AJAX Load More
 */

const XanhBlog = {
  /* ── Config ── */
  SEARCH_PLACEHOLDERS: [
    'Tìm kiếm "kinh nghiệm xây nhà"...',
    'Tìm kiếm "vật liệu xanh"...',
    'Tìm kiếm "xu hướng thiết kế 2026"...',
    'Tìm kiếm "chi phí xây dựng"...',
  ],
  TYPING_SPEED: 80,
  ERASE_SPEED: 40,
  PAUSE_BETWEEN: 2000,

  /* ── Entry point ── */
  init() {
    /* Hero reveal (page-specific selector) */
    XanhBase.initHeroReveal('.blog-hero__bg', '.hero-el--fast');

    /* Page-specific modules only — global init (Lenis, Lucide,
       BackToTop, ScrollReveal) already runs in main.js */
    this.initFilterTabs();
    this.initSearchPlaceholder();
    this.initSearchDropdown();
    this.initLoadMore();
    this.initLeadMagnet();
  },

  /* ============================================= */
  /* FILTER TABS — AJAX Loading                    */
  /* ============================================= */
  initFilterTabs() {
    const tabsContainer = document.getElementById('category-tabs-bar');
    if (!tabsContainer) return;

    tabsContainer.addEventListener('click', (e) => {
      const tab = e.target.closest('button.filter-tab');
      if (!tab) return;

      const allTabs = tabsContainer.querySelectorAll('.filter-tab');
      allTabs.forEach(t => { t.classList.remove('is-active'); t.setAttribute('aria-selected', 'false'); });
      tab.classList.add('is-active');
      tab.setAttribute('aria-selected', 'true');

      const category = tab.dataset.category || '';
      this._ajaxFilterBlog(category);
    });
  },

  async _ajaxFilterBlog(category) {
    const grid = document.getElementById('article-grid-container');
    const loadBtn = document.getElementById('load-more-btn');
    const featuredSection = document.getElementById('featured-articles');
    if (!grid) return;

    /* Toggle featured section visibility */
    if (featuredSection) {
      if (category) {
        featuredSection.style.transition = 'opacity 0.4s ease, max-height 0.5s ease, margin 0.5s ease, padding 0.5s ease';
        featuredSection.style.opacity = '0';
        featuredSection.style.maxHeight = '0';
        featuredSection.style.overflow = 'hidden';
        featuredSection.style.marginTop = '0';
        featuredSection.style.marginBottom = '0';
        featuredSection.style.paddingTop = '0';
        featuredSection.style.paddingBottom = '0';
      } else {
        featuredSection.style.transition = 'opacity 0.4s ease 0.2s, max-height 0.5s ease, margin 0.5s ease, padding 0.5s ease';
        featuredSection.style.opacity = '1';
        featuredSection.style.maxHeight = '2000px';
        featuredSection.style.overflow = '';
        featuredSection.style.marginTop = '';
        featuredSection.style.marginBottom = '';
        featuredSection.style.paddingTop = '';
        featuredSection.style.paddingBottom = '';
      }
    }

    /* Loading state */
    grid.style.transition = 'opacity 0.3s ease';
    grid.style.opacity = '0.5';
    grid.style.pointerEvents = 'none';

    if (loadBtn) {
      loadBtn.dataset.category = category;
      loadBtn.dataset.page = 1;
      const btnSpan = loadBtn.querySelector('span');
      if (btnSpan) btnSpan.textContent = 'Đang tải...';
    }

    const ajaxUrl = typeof xanhBlogAjax !== 'undefined' ? xanhBlogAjax.url : '/wp-admin/admin-ajax.php';
    const formData = new FormData();
    formData.append('action', 'xanh_blog_load_more');
    formData.append('nonce', typeof xanhBlogAjax !== 'undefined' ? xanhBlogAjax.nonce : '');
    formData.append('paged', 1);
    if (category) formData.append('category', category);
    if (loadBtn && loadBtn.dataset.exclude) formData.append('exclude', loadBtn.dataset.exclude);

    try {
      const res = await fetch(ajaxUrl, { method: 'POST', body: formData });
      if (!res.ok) throw new Error(`HTTP ${res.status}`);

      const data = await res.json();
      if (!data.success) throw new Error(data.data?.message || 'Server error');

      grid.innerHTML = ''; /* Clear existing cards */

      if (data.data.html) {
        const temp = document.createElement('div');
        temp.innerHTML = data.data.html;
        const cardArr = [...temp.children];
        const fragment = document.createDocumentFragment();
        cardArr.forEach(card => fragment.appendChild(card));
        grid.appendChild(fragment);

        requestAnimationFrame(() => {
          cardArr.forEach(card => card.classList.add('is-visible'));
        });
        if (typeof lucide !== 'undefined') lucide.createIcons();
      }

      grid.style.opacity = '1';
      grid.style.pointerEvents = 'auto';

      if (loadBtn) {
        const btnSpan = loadBtn.querySelector('span');
        this._updateLoadMoreButton(loadBtn, btnSpan, 'Xem Thêm Bài Viết', 1, data.data.pages);
      }
    } catch (error) {
      console.warn('[XANH] Filter failed:', error.message);
      grid.style.opacity = '1';
      grid.style.pointerEvents = 'auto';
      if (loadBtn) {
        const btnSpan = loadBtn.querySelector('span');
        if (btnSpan) btnSpan.textContent = 'Xem Thêm Bài Viết';
      }
    }
  },

  /* ============================================= */
  /* SEARCH — Animated Placeholder (Typing Effect) */
  /* ============================================= */
  initSearchPlaceholder() {
    const input = document.getElementById('blog-search-input');
    const placeholder = document.getElementById('blog-search-placeholder');
    if (!input || !placeholder) return;

    if (XanhBase.prefersReducedMotion()) {
      this._initReducedMotionPlaceholder(input, placeholder);
      return;
    }

    this._startTypingAnimation(input, placeholder);
  },

  /* Reduced-motion fallback: static placeholder */
  _initReducedMotionPlaceholder(input, placeholder) {
    placeholder.textContent = this.SEARCH_PLACEHOLDERS[0].replace('...', '');
    placeholder.style.display = 'block';
    input.addEventListener('focus', () => { placeholder.style.display = 'none'; });
    input.addEventListener('blur', () => {
      if (!input.value) placeholder.style.display = 'block';
    });
  },

  /* Full typing animation with focus/blur bindings */
  _startTypingAnimation(input, placeholder) {
    let currentIndex = 0;
    let charIndex = 0;
    let isErasing = false;
    let timer = null;

    const type = () => {
      const text = this.SEARCH_PLACEHOLDERS[currentIndex];

      if (!isErasing) {
        charIndex++;
        placeholder.textContent = text.substring(0, charIndex);
        if (charIndex === text.length) {
          isErasing = true;
          timer = setTimeout(type, this.PAUSE_BETWEEN);
          return;
        }
        timer = setTimeout(type, this.TYPING_SPEED);
      } else {
        charIndex--;
        placeholder.textContent = text.substring(0, charIndex);
        if (charIndex === 0) {
          isErasing = false;
          currentIndex = (currentIndex + 1) % this.SEARCH_PLACEHOLDERS.length;
          timer = setTimeout(type, 400);
          return;
        }
        timer = setTimeout(type, this.ERASE_SPEED);
      }
    };

    input.addEventListener('focus', () => {
      placeholder.style.opacity = '0';
      clearTimeout(timer);
    });
    input.addEventListener('blur', () => {
      if (!input.value) {
        placeholder.style.opacity = '1';
        charIndex = 0;
        isErasing = false;
        currentIndex = 0;
        timer = setTimeout(type, 600);
      }
    });

    timer = setTimeout(type, 800);
    this._searchPlaceholderTimer = timer;
  },

  /* Cleanup typing animation timer */
  destroySearchPlaceholder() {
    if (this._searchPlaceholderTimer) {
      clearTimeout(this._searchPlaceholderTimer);
      this._searchPlaceholderTimer = null;
    }
  },

  /* ============================================= */
  /* SEARCH — Autocomplete Dropdown Demo           */
  /* ============================================= */
  initSearchDropdown() {
    const input = document.getElementById('blog-search-input');
    const dropdown = document.getElementById('blog-search-dropdown');
    if (!input || !dropdown) return;

    const debounce = XanhBase.debounce;

    const showDropdown = debounce((value) => {
      if (value.length >= 2) {
        dropdown.classList.remove('hidden');
      } else {
        dropdown.classList.add('hidden');
      }
    }, 300);

    input.addEventListener('input', (e) => {
      showDropdown(e.target.value);
    });

    input.addEventListener('blur', () => {
      setTimeout(() => dropdown.classList.add('hidden'), 200);
    });
  },

  /* ============================================= */
  /* LOAD MORE — AJAX Pagination                   */
  /* ============================================= */
  initLoadMore() {
    const btn = document.getElementById('load-more-btn');
    if (!btn) return;

    btn.addEventListener('click', () => {
      this._ajaxLoadMore(btn);
    });
  },

  async _ajaxLoadMore(btn) {
    const currentPage = parseInt(btn.dataset.page, 10) || 1;
    const maxPages    = parseInt(btn.dataset.max, 10) || 1;
    const nextPage    = currentPage + 1;

    if (nextPage > maxPages) return;

    /* Loading state */
    const btnSpan = btn.querySelector('span');
    const originalText = btnSpan ? btnSpan.textContent : '';
    if (btnSpan) btnSpan.textContent = 'Đang tải...';
    btn.disabled = true;

    const ajaxUrl = typeof xanhBlogAjax !== 'undefined' ? xanhBlogAjax.url : '/wp-admin/admin-ajax.php';

    const formData = new FormData();
    formData.append('action', 'xanh_blog_load_more');
    formData.append('nonce', typeof xanhBlogAjax !== 'undefined' ? xanhBlogAjax.nonce : '');
    formData.append('paged', nextPage);

    const exclude = btn.dataset.exclude || '';
    if (exclude) formData.append('exclude', exclude);

    const category = btn.dataset.category || '';
    if (category) formData.append('category', category);

    try {
      const res = await fetch(ajaxUrl, { method: 'POST', body: formData });
      if (!res.ok) throw new Error(`HTTP ${res.status}`);

      const data = await res.json();
      if (!data.success) throw new Error(data.data?.message || 'Server error');

      if (data.data.html) {
        this._renderLoadMoreItems(data.data.html);
        this._updateLoadMoreButton(btn, btnSpan, originalText, nextPage, maxPages);
      }
    } catch (error) {
      console.warn('[XANH] Load more failed:', error.message);
      btn.disabled = false;
      if (btnSpan) btnSpan.textContent = originalText;
    }
  },

  /* Insert new cards into grid via DocumentFragment (1 reflow) */
  _renderLoadMoreItems(html) {
    const grid = document.getElementById('article-grid-container');
    if (!grid) return;

    const temp = document.createElement('div');
    temp.innerHTML = html;
    const cardArr = [...temp.children];
    const fragment = document.createDocumentFragment();
    cardArr.forEach(card => fragment.appendChild(card));
    grid.appendChild(fragment);

    /* Trigger reveal animation */
    requestAnimationFrame(() => {
      cardArr.forEach(card => card.classList.add('is-visible'));
    });

    /* Smooth scroll to first new card */
    if (cardArr.length) {
      requestAnimationFrame(() => {
        const firstCard = cardArr[0];
        const headerOffset = 100;
        const top = firstCard.getBoundingClientRect().top + window.scrollY - headerOffset;
        window.scrollTo({ top, behavior: 'smooth' });
      });
    }

    /* Reinitialize Lucide icons for new cards */
    if (typeof lucide !== 'undefined') lucide.createIcons();
  },

  /* Update Load More button state after successful fetch */
  _updateLoadMoreButton(btn, btnSpan, originalText, nextPage, maxPages) {
    btn.dataset.page = nextPage;

    if (nextPage >= maxPages) {
      btn.disabled = true;
      if (btnSpan) btnSpan.textContent = 'Đã hiển thị tất cả';
    } else {
      btn.disabled = false;
      if (btnSpan) btnSpan.textContent = originalText;
    }
  },



  /* ============================================= */
  /* LEAD MAGNET — 3D Book Tilt + Form Submit       */
  /* ============================================= */
  initLeadMagnet() {
    this._initBookTilt();
    this._initLeadMagnetForm();
  },

  _initBookTilt() {
    const wrapper = document.getElementById('lead-magnet-book-wrapper');
    const book    = document.getElementById('lead-magnet-book');
    if (!wrapper || !book) return;

    /* Respect reduced motion */
    const prefersReducedMotion = XanhBase.prefersReducedMotion();
    if (prefersReducedMotion) return;

    const MAX_TILT = 8; /* degrees */
    const BASE_TRANSFORM = 'rotateY(-18deg) rotateX(4deg)';
    let rafId = null;
    let targetX = 0;
    let targetY = 0;

    const onMove = (e) => {
      const rect = wrapper.getBoundingClientRect();
      /* Normalized -1 → +1 */
      const nx = ((e.clientX - rect.left)  / rect.width  - 0.5) * 2;
      const ny = ((e.clientY - rect.top)   / rect.height - 0.5) * 2;

      targetY =  nx * MAX_TILT;   /* left/right → rotateY */
      targetX = -ny * MAX_TILT;   /* up/down    → rotateX */

      if (!rafId) {
        rafId = requestAnimationFrame(() => {
          book.style.transform =
            `rotateY(${(-18 + targetY).toFixed(2)}deg) rotateX(${(4 + targetX).toFixed(2)}deg)`;
          rafId = null;
        });
      }
    };

    const onLeave = () => {
      if (rafId) { cancelAnimationFrame(rafId); rafId = null; }
      book.style.transform = BASE_TRANSFORM;
    };

    wrapper.addEventListener('mousemove', onMove, { passive: true });
    wrapper.addEventListener('mouseleave', onLeave);
  },

  _initLeadMagnetForm() {
    const form = document.querySelector('.lead-magnet__form');
    const btn  = document.getElementById('lead-magnet-btn');
    if (!form || !btn) return;

    form.addEventListener('submit', (e) => {
      e.preventDefault();

      try {
        /* Basic validation */
        const name  = form.querySelector('#lm-name');
        const phone = form.querySelector('#lm-phone');
        if (!name || !phone) return;
        if (!name.value.trim() || !phone.value.trim()) {
          name.focus();
          return;
        }

        /* Loading state */
        btn.classList.add('is-loading');
        btn.disabled = true;
        const originalText = btn.querySelector('span');
        if (originalText) originalText.textContent = 'Đang gửi...';

        /* Simulate async submit */
        setTimeout(() => {
          btn.classList.remove('is-loading');
          btn.disabled = false;
          if (originalText) originalText.textContent = '✓ Đã gửi thành công!';
          form.reset();

          setTimeout(() => {
            if (originalText) originalText.textContent = 'Gửi Cho Tôi Ngay';
          }, 3000);
        }, 1800);
      } catch (error) {
        console.warn('[XANH] Form submit failed:', error.message);
        btn.classList.remove('is-loading');
        btn.disabled = false;
      }
    });
  },
};

/* ── Bootstrap ── */
document.addEventListener('DOMContentLoaded', () => {
  XanhBlog.init();
});
