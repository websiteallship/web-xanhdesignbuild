/**
 * XANH — Design & Build
 * Blog Detail Page Scripts
 * =============================================
 * Handles Reading Progress, TOC Generation, and Social Share
 */

const XanhBlogDetail = {

  // Store references for cleanup
  _headingObserver: null,
  _lightbox: null,

  init() {
    this.initReadingProgress();
    this.initTOC();
    this.initSocialShare();
    this.initLightbox();
    this.initLeadMagnet();
    this.initCleanup();
  },

  /* ============================================= */
  /* LEAD MAGNET — 3D Book Tilt                     */
  /* ============================================= */
  initLeadMagnet() {
    const wrapper = document.getElementById('lead-magnet-book-wrapper');
    const book    = document.getElementById('lead-magnet-book');
    if (!wrapper || !book) return;

    /* Respect reduced motion */
    const prefersReducedMotion = typeof XanhBase !== 'undefined' ? XanhBase.prefersReducedMotion() : window.matchMedia('(prefers-reduced-motion: reduce)').matches;
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

  // 0. Lightbox for article images (custom — no navigation, zoom in/out)
  initLightbox() {
    const articleBody = document.querySelector('.article-body');
    if (!articleBody) return;

    const images = articleBody.querySelectorAll('img');
    if (images.length === 0) return;

    // Build the lightbox overlay once
    this._buildLightboxDOM();

    images.forEach((img) => {
      if (img.closest('a.article-lightbox-trigger') || img.closest('.toc')) return;
      if (img.naturalWidth && img.naturalWidth < 100) return;

      // Get best quality src
      let fullSrc = img.getAttribute('src') || '';
      const srcset = img.getAttribute('srcset');
      if (srcset) {
        const sources = srcset.split(',').map(s => s.trim().split(/\s+/));
        let maxWidth = 0;
        sources.forEach(([url, descriptor]) => {
          const w = parseInt(descriptor, 10) || 0;
          if (w > maxWidth) { maxWidth = w; fullSrc = url; }
        });
      } else {
        fullSrc = fullSrc.replace(/-\d+x\d+(\.\w+)$/, '$1');
      }
      if (!fullSrc) return;

      // Create clickable wrapper
      const wrapper = document.createElement('a');
      wrapper.href = fullSrc;
      wrapper.className = 'article-lightbox-trigger';
      wrapper.setAttribute('role', 'button');
      wrapper.setAttribute('aria-label', 'Phóng to ảnh');

      const altText = img.getAttribute('alt') || '';
      wrapper.dataset.caption = altText;

      wrapper.addEventListener('click', (e) => {
        e.preventDefault();
        this._openLightbox(fullSrc, altText);
      });

      img.parentNode.insertBefore(wrapper, img);
      wrapper.appendChild(img);
    });
  },

  /* ── Custom Lightbox DOM ── */
  _buildLightboxDOM() {
    if (document.getElementById('xanh-img-lightbox')) return;

    const overlay = document.createElement('div');
    overlay.id = 'xanh-img-lightbox';
    overlay.className = 'img-lightbox';
    overlay.innerHTML = `
      <div class="img-lightbox__backdrop"></div>
      <div class="img-lightbox__container">
        <img class="img-lightbox__img" src="" alt="" draggable="false" />
      </div>
      <button class="img-lightbox__close" id="lb-close" aria-label="Đóng">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
      <div class="img-lightbox__bottom">
        <div class="img-lightbox__zoom-bar">
          <button class="img-lightbox__btn" id="lb-zoom-out" aria-label="Thu nhỏ">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
          </button>
          <span class="img-lightbox__zoom-level" id="lb-zoom-level">100%</span>
          <button class="img-lightbox__btn" id="lb-zoom-in" aria-label="Phóng to">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
          </button>
        </div>
        <div class="img-lightbox__caption">
          <span class="img-lightbox__caption-text"></span>
        </div>
      </div>
    `;
    document.body.appendChild(overlay);

    // Cache elements
    this._lb = {
      overlay,
      backdrop: overlay.querySelector('.img-lightbox__backdrop'),
      container: overlay.querySelector('.img-lightbox__container'),
      img: overlay.querySelector('.img-lightbox__img'),
      captionText: overlay.querySelector('.img-lightbox__caption-text'),
      zoomLevel: overlay.querySelector('#lb-zoom-level'),
      scale: 1,
      panX: 0,
      panY: 0,
      isDragging: false,
      startX: 0,
      startY: 0,
    };

    // Bind events
    this._bindLightboxEvents();
  },

  _bindLightboxEvents() {
    const lb = this._lb;

    // Close
    lb.backdrop.addEventListener('click', () => this._closeLightbox());
    document.getElementById('lb-close').addEventListener('click', () => this._closeLightbox());

    // Zoom buttons
    document.getElementById('lb-zoom-in').addEventListener('click', () => this._zoom(0.25));
    document.getElementById('lb-zoom-out').addEventListener('click', () => this._zoom(-0.25));

    // Mouse wheel zoom
    lb.container.addEventListener('wheel', (e) => {
      e.preventDefault();
      const delta = e.deltaY < 0 ? 0.15 : -0.15;
      this._zoom(delta);
    }, { passive: false });

    // Double-click to toggle zoom
    lb.img.addEventListener('dblclick', (e) => {
      e.preventDefault();
      if (lb.scale > 1) {
        lb.scale = 1; lb.panX = 0; lb.panY = 0;
      } else {
        lb.scale = 2;
      }
      this._applyTransform();
    });

    // Drag to pan (when zoomed)
    lb.container.addEventListener('pointerdown', (e) => {
      if (lb.scale <= 1) return;
      lb.isDragging = true;
      lb.startX = e.clientX - lb.panX;
      lb.startY = e.clientY - lb.panY;
      lb.container.style.cursor = 'grabbing';
      lb.container.setPointerCapture(e.pointerId);
    });

    lb.container.addEventListener('pointermove', (e) => {
      if (!lb.isDragging) return;
      lb.panX = e.clientX - lb.startX;
      lb.panY = e.clientY - lb.startY;
      this._applyTransform();
    });

    lb.container.addEventListener('pointerup', () => {
      lb.isDragging = false;
      lb.container.style.cursor = lb.scale > 1 ? 'grab' : '';
    });
    lb.container.addEventListener('pointercancel', () => {
      lb.isDragging = false;
      lb.container.style.cursor = lb.scale > 1 ? 'grab' : '';
    });

    this._bindLightboxKeyboard();
  },

  /* Keyboard shortcuts for lightbox (Esc, +/-, 0) */
  _bindLightboxKeyboard() {
    const lb = this._lb;
    document.addEventListener('keydown', (e) => {
      if (!lb.overlay.classList.contains('is-open')) return;
      if (e.key === 'Escape') this._closeLightbox();
      if (e.key === '+' || e.key === '=') this._zoom(0.25);
      if (e.key === '-') this._zoom(-0.25);
      if (e.key === '0') { lb.scale = 1; lb.panX = 0; lb.panY = 0; this._applyTransform(); }
    });
  },

  _openLightbox(src, caption) {
    const lb = this._lb;
    lb.img.src = src;
    lb.img.alt = caption;
    lb.captionText.textContent = caption;
    lb.scale = 1;
    lb.panX = 0;
    lb.panY = 0;
    this._applyTransform();
    lb.overlay.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  },

  _closeLightbox() {
    const lb = this._lb;
    lb.overlay.classList.remove('is-open');
    document.body.style.overflow = '';
    setTimeout(() => { lb.img.src = ''; }, 300);
  },

  _zoom(delta) {
    const lb = this._lb;
    const newScale = Math.max(0.5, Math.min(5, lb.scale + delta));
    lb.scale = Math.round(newScale * 100) / 100;
    if (lb.scale <= 1) { lb.panX = 0; lb.panY = 0; }
    lb.container.style.cursor = lb.scale > 1 ? 'grab' : '';
    this._applyTransform();
  },

  _applyTransform() {
    const lb = this._lb;
    lb.img.style.transform = `translate(${lb.panX}px, ${lb.panY}px) scale(${lb.scale})`;
    lb.zoomLevel.textContent = Math.round(lb.scale * 100) + '%';
  },

  // 1. Reading Progress Bar
  initReadingProgress() {
    const progressBar = document.querySelector('.reading-progress__bar');
    if (!progressBar) return;

    // Throttle scroll event using requestAnimationFrame
    let isTicking = false;

    window.addEventListener('scroll', () => {
      if (!isTicking) {
        window.requestAnimationFrame(() => {
          const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
          const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
          const scrolled = (winScroll / height) * 100;

          progressBar.style.width = scrolled + '%';
          isTicking = false;
        });
        isTicking = true;
      }
    }, { passive: true });
  },

  // 2. Table of Contents (TOC)
  initTOC() {
    const articleBody = document.querySelector('.article-body');
    const tocBody = document.getElementById('toc-body');
    const tocToggle = document.getElementById('toc-toggle');

    if (!articleBody || !tocBody) return;

    // Collect H2 and H3
    const headings = articleBody.querySelectorAll('h2, h3');
    if (headings.length === 0) {
      const tocEl = document.querySelector('.toc');
      if (tocEl) tocEl.style.display = 'none';
      return;
    }

    this._generateTOCList(headings, tocBody);
    this._initTOCToggle(tocToggle, tocBody);
    this._initTOCHighlight(headings);
  },

  _generateTOCList(headings, tocBody) {
    const VISIBLE_LIMIT = 5; // items visible before "Show more"
    const ul = document.createElement('ul');
    ul.className = 'toc-list font-body';

    headings.forEach((heading, index) => {
      // Create ID if not exists
      if (!heading.id) {
        heading.id = 'heading-' + index + '-' + heading.textContent
          .toLowerCase()
          .replace(/[áàảãạăắằẳẵặâấầẩẫậ]/g, 'a')
          .replace(/[đ]/g, 'd')
          .replace(/[éèẻẽẹêếềểễệ]/g, 'e')
          .replace(/[íìỉĩị]/g, 'i')
          .replace(/[óòỏõọôốồổỗộơớờởỡợ]/g, 'o')
          .replace(/[úùủũụưứừửữự]/g, 'u')
          .replace(/[ýỳỷỹỵ]/g, 'y')
          .replace(/[^a-z0-9\s]/g, '')
          .trim()
          .replace(/\s+/g, '-');
      }

      const isH3 = heading.tagName.toLowerCase() === 'h3';
      const li = document.createElement('li');
      li.className = `toc-item ${isH3 ? 'toc-item--h3' : 'toc-item--h2'}`;

      // Hide items beyond VISIBLE_LIMIT initially
      if (index >= VISIBLE_LIMIT) {
        li.classList.add('toc-item--hidden');
      }

      const a = document.createElement('a');
      a.href = `#${heading.id}`;
      a.className = 'toc-link';
      a.textContent = heading.textContent;

      // Smooth scroll
      a.addEventListener('click', (e) => {
        e.preventDefault();
        const target = document.getElementById(heading.id);
        if (target) {
          const offsetTop = target.getBoundingClientRect().top + window.pageYOffset - 120;
          window.scrollTo({ top: offsetTop, behavior: 'smooth' });
        }
      });

      li.appendChild(a);
      ul.appendChild(li);
    });

    tocBody.appendChild(ul);

    // Inject "Show more" button if needed
    if (headings.length > VISIBLE_LIMIT) {
      let expanded = false;
      const hiddenItems = ul.querySelectorAll('.toc-item--hidden');
      const showMoreBtn = document.createElement('button');
      showMoreBtn.className = 'toc-show-more';

      const _updateShowMoreBtn = (isExpanded) => {
        showMoreBtn.textContent = '';
        const span = document.createElement('span');
        span.textContent = isExpanded ? 'Thu gọn' : `Xem thêm (${headings.length - VISIBLE_LIMIT})`;
        const icon = document.createElement('i');
        icon.className = 'toc-show-more__icon w-4 h-4';
        icon.setAttribute('data-lucide', isExpanded ? 'chevron-up' : 'chevron-down');
        showMoreBtn.appendChild(span);
        showMoreBtn.appendChild(icon);
        if (typeof lucide !== 'undefined') lucide.createIcons({ nodes: [showMoreBtn] });
      };

      _updateShowMoreBtn(false);

      showMoreBtn.addEventListener('click', () => {
        expanded = !expanded;
        hiddenItems.forEach(item => {
          item.style.display = expanded ? '' : 'none';
        });
        _updateShowMoreBtn(expanded);
      });

      // Initial state: hide the extra items
      hiddenItems.forEach(item => item.style.display = 'none');

      tocBody.appendChild(showMoreBtn);
    }

    // Init lucide icons inside TOC
    if (typeof lucide !== 'undefined') lucide.createIcons();
  },

  _initTOCToggle(tocToggle, tocBody) {
    if (!tocToggle) return;

    let isTocOpen = true;
    tocBody.classList.add('is-open');
    tocToggle.classList.add('is-open');

    tocToggle.addEventListener('click', () => {
      isTocOpen = !isTocOpen;
      tocBody.classList.toggle('is-open', isTocOpen);
      tocToggle.classList.toggle('is-open', isTocOpen);
    });
  },

  _initTOCHighlight(headings) {
    const tocLinks = document.querySelectorAll('.toc-link');
    if (tocLinks.length === 0) return;

    let activeHeadingId = null;

    const observerOptions = {
      root: null,
      rootMargin: '-120px 0px -60% 0px',
      threshold: 0,
    };

    this._headingObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          activeHeadingId = entry.target.id;

          tocLinks.forEach(link => {
            if (link.getAttribute('href') === `#${activeHeadingId}`) {
              link.classList.add('is-active');
            } else {
              link.classList.remove('is-active');
            }
          });
        }
      });
    }, observerOptions);

    headings.forEach(h => this._headingObserver.observe(h));
  },

  // 3. Social Share (Copy Link)
  initSocialShare() {
    const copyBtn = document.getElementById('copy-link-btn');
    const copyTooltip = document.getElementById('copy-tooltip');

    if (!copyBtn || !copyTooltip) return;

    copyBtn.addEventListener('click', async () => {
      const urlToCopy = window.location.href;
      
      try {
        // Modern approach (requires HTTPS or localhost)
        if (navigator.clipboard && window.isSecureContext) {
          await navigator.clipboard.writeText(urlToCopy);
        } else {
          // Fallback for HTTP (e.g. xanh.local)
          const tempInput = document.createElement('input');
          tempInput.value = urlToCopy;
          document.body.appendChild(tempInput);
          tempInput.select();
          document.execCommand('copy');
          document.body.removeChild(tempInput);
        }

        copyTooltip.classList.add('show');

        setTimeout(() => {
          copyTooltip.classList.remove('show');
        }, 2000);
      } catch (err) {
        console.warn('[XANH] Failed to copy link:', err.message);
      }
    });
  },

  // 4. Cleanup
  initCleanup() {
    window.addEventListener('beforeunload', () => {
      if (this._headingObserver) {
        this._headingObserver.disconnect();
        this._headingObserver = null;
      }
      const lbEl = document.getElementById('xanh-img-lightbox');
      if (lbEl) lbEl.remove();
    });
  },
};

// Initialize on DOMContentLoaded
document.addEventListener('DOMContentLoaded', () => {
  XanhBlogDetail.init();
});
