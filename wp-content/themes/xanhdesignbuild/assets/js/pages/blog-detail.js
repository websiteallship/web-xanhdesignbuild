/**
 * XANH — Design & Build
 * Blog Detail Page Scripts
 * =============================================
 * Handles Reading Progress, TOC Generation, and Social Share
 */

const XanhBlogDetail = {

  // Store references for cleanup
  _headingObserver: null,

  init() {
    this.initReadingProgress();
    this.initTOC();
    this.initSocialShare();
    this.initCleanup();
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
    });
  },
};

// Initialize on DOMContentLoaded
document.addEventListener('DOMContentLoaded', () => {
  XanhBlogDetail.init();
});
