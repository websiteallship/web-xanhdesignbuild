/**
 * XANH Theme — Popup Modal Manager
 *
 * Integrates with XanhBase (main.js):
 * - Uses XanhBase.prefersReducedMotion() for animation gating.
 * - Pauses/resumes Lenis smooth scroll on open/close.
 * - Follows the same module pattern (IIFE, DOMContentLoaded).
 *
 * Manages popup lifecycle: trigger binding, open/close transitions,
 * cookie/localStorage frequency control, focus trap, and ESC handling.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

(function () {
  'use strict';

  /* ══════════════════════════════════════════
     1. FREQUENCY / COOKIE MANAGER
     ══════════════════════════════════════════ */

  const FrequencyManager = {
    /**
     * Get the storage key for a popup.
     * @param {number} id Popup post ID.
     * @returns {string}
     */
    key(id) {
      return `xanh_popup_closed_${id}`;
    },

    /**
     * Check if a popup should be suppressed.
     * @param {number} id       Popup ID.
     * @param {string} frequency  always|once_session|once_day|once_week
     * @returns {boolean} true if popup should NOT show.
     */
    isSuppressed(id, frequency) {
      if (frequency === 'always') return false;

      if (frequency === 'once_session') {
        return sessionStorage.getItem(this.key(id)) === '1';
      }

      // once_day / once_week — stored in localStorage with timestamp.
      const stored = localStorage.getItem(this.key(id));
      if (!stored) return false;

      const closedAt = parseInt(stored, 10);
      const now      = Date.now();
      const dayMs    = 86400000;
      const maxMs    = frequency === 'once_week' ? dayMs * 7 : dayMs;

      return (now - closedAt) < maxMs;
    },

    /**
     * Mark a popup as closed.
     * @param {number} id
     * @param {string} frequency
     */
    markClosed(id, frequency) {
      if (frequency === 'always') return;

      if (frequency === 'once_session') {
        sessionStorage.setItem(this.key(id), '1');
        return;
      }

      localStorage.setItem(this.key(id), String(Date.now()));
    },
  };

  /* ══════════════════════════════════════════
     2. FOCUS TRAP
     ══════════════════════════════════════════ */

  const FOCUSABLE = 'a[href], button:not([disabled]), input:not([disabled]), textarea:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])';

  function trapFocus(modal, e) {
    const focusable = modal.querySelectorAll(FOCUSABLE);
    if (!focusable.length) return;

    const first = focusable[0];
    const last  = focusable[focusable.length - 1];

    if (e.shiftKey && document.activeElement === first) {
      e.preventDefault();
      last.focus();
    } else if (!e.shiftKey && document.activeElement === last) {
      e.preventDefault();
      first.focus();
    }
  }

  /* ══════════════════════════════════════════
     3. MODAL CONTROLLER
     ══════════════════════════════════════════ */

  class PopupModalManager {
    constructor() {
      /** @type {HTMLElement[]} */
      this.modals       = [];
      /** @type {HTMLElement|null} */
      this.activeModal  = null;
      /** @type {HTMLElement|null} */
      this.lastFocusEl  = null;
      /** @type {Function|null} */
      this._focusHandler = null;
      /** @type {boolean} */
      this._exitBound   = false;
      /** @type {Map<number, boolean>} */
      this._scrollFired = new Map();
    }

    /* ── Init ── */
    init() {
      this.modals = [...document.querySelectorAll('.x-modal')];
      if (!this.modals.length) return;

      this.modals.forEach((modal) => this._bindTrigger(modal));
      this._bindClickLinks();
      this._bindEsc();
    }

    /* ── Bind trigger per modal ── */
    _bindTrigger(modal) {
      const trigger   = modal.dataset.trigger;
      const id        = parseInt(modal.dataset.popupId, 10);
      const frequency = modal.dataset.frequency || 'once_session';

      // Check reduced motion preference from XanhBase if available.
      const reducedMotion = (typeof XanhBase !== 'undefined' && XanhBase.prefersReducedMotion)
        ? XanhBase.prefersReducedMotion()
        : window.matchMedia('(prefers-reduced-motion: reduce)').matches;

      switch (trigger) {
        case 'click': {
          // Also bind any custom CSS selector.
          const selector = modal.dataset.clickSelector;
          if (selector) {
            document.querySelectorAll(selector).forEach((el) => {
              el.addEventListener('click', (e) => {
                e.preventDefault();
                this.open(modal);
              });
            });
          }
          break;
        }

        case 'delay': {
          if (FrequencyManager.isSuppressed(id, frequency)) return;
          const delay = parseInt(modal.dataset.delay, 10) || 5;
          setTimeout(() => {
            if (!FrequencyManager.isSuppressed(id, frequency)) {
              this.open(modal);
            }
          }, delay * 1000);
          break;
        }

        case 'scroll': {
          if (FrequencyManager.isSuppressed(id, frequency)) return;
          const pct = parseInt(modal.dataset.scroll, 10) || 50;
          this._scrollFired.set(id, false);

          const scrollHandler = () => {
            if (this._scrollFired.get(id)) return;

            const scrolled    = window.scrollY + window.innerHeight;
            const docHeight   = document.documentElement.scrollHeight;
            const scrollPct   = (scrolled / docHeight) * 100;

            if (scrollPct >= pct) {
              this._scrollFired.set(id, true);
              if (!FrequencyManager.isSuppressed(id, frequency)) {
                this.open(modal);
              }
              window.removeEventListener('scroll', scrollHandler);
            }
          };

          window.addEventListener('scroll', scrollHandler, { passive: true });
          break;
        }

        case 'exit_intent': {
          if (FrequencyManager.isSuppressed(id, frequency)) return;
          // Exit intent only works on desktop (no mouseleave on touch).
          if (reducedMotion) return;
          if (!this._exitBound) {
            this._exitBound = true;
            document.addEventListener('mouseleave', (e) => {
              if (e.clientY <= 0 && !this.activeModal) {
                // Find first exit_intent modal that isn't suppressed.
                const exitModal = this.modals.find(
                  (m) => m.dataset.trigger === 'exit_intent' &&
                         !FrequencyManager.isSuppressed(
                           parseInt(m.dataset.popupId, 10),
                           m.dataset.frequency || 'once_session'
                         )
                );
                if (exitModal) this.open(exitModal);
              }
            });
          }
          break;
        }
      }
    }

    /* ── Bind href="#xanh-popup-{id}" links ── */
    _bindClickLinks() {
      document.addEventListener('click', (e) => {
        const link = e.target.closest('a[href*="#xanh-popup-"]');
        if (!link) return;

        const href  = link.getAttribute('href');
        const match = href.match(/#xanh-popup-(\d+)/);
        if (!match) return;

        e.preventDefault();
        const modalEl = document.getElementById('xanh-popup-' + match[1]);
        if (modalEl) this.open(modalEl);
      });
    }

    /* ── Keyboard: ESC to close ── */
    _bindEsc() {
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && this.activeModal) {
          this.close(this.activeModal);
        }
      });
    }

    /* ── OPEN ── */
    open(modal) {
      if (this.activeModal) {
        this.close(this.activeModal);
      }

      this.lastFocusEl = document.activeElement;
      this.activeModal = modal;

      modal.classList.add('is-open');
      modal.setAttribute('aria-hidden', 'false');
      document.body.classList.add('is-popup-open');

      // Pause Lenis smooth scroll if available (from XanhBase).
      if (typeof XanhBase !== 'undefined' && XanhBase.getLenis) {
        const lenis = XanhBase.getLenis();
        if (lenis && lenis.stop) lenis.stop();
      }

      // Prevent Lenis from intercepting touch events inside the modal.
      // This ensures mobile users can scroll the popup's internal content.
      this._touchHandler = (e) => e.stopPropagation();
      modal.addEventListener('touchstart', this._touchHandler, { passive: true });
      modal.addEventListener('touchmove',  this._touchHandler, { passive: true });
      modal.addEventListener('wheel',      this._touchHandler, { passive: true });

      // Inject YouTube iframe lazily for video type.
      const videoWrap = modal.querySelector('.x-modal__video-wrap[data-embed-url]');
      if (videoWrap && !videoWrap.querySelector('iframe')) {
        const embedUrl = videoWrap.dataset.embedUrl;
        if (embedUrl) {
          const iframe  = document.createElement('iframe');
          iframe.src    = embedUrl;
          iframe.allow  = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
          iframe.allowFullscreen = true;
          iframe.title  = 'Video';
          videoWrap.appendChild(iframe);
        }
      }

      // Focus trap.
      this._focusHandler = (e) => {
        if (e.key === 'Tab') trapFocus(modal, e);
      };
      document.addEventListener('keydown', this._focusHandler);

      // Focus first focusable inside.
      requestAnimationFrame(() => {
        const first = modal.querySelector(FOCUSABLE);
        if (first) first.focus();
      });

      // Bind close buttons.
      modal.querySelectorAll('[data-modal-close]').forEach((btn) => {
        btn.addEventListener('click', () => this.close(modal), { once: true });
      });
    }

    /* ── CLOSE ── */
    close(modal) {
      const id        = parseInt(modal.dataset.popupId, 10);
      const frequency = modal.dataset.frequency || 'once_session';

      modal.classList.remove('is-open');
      modal.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('is-popup-open');

      // Remove touch/wheel handlers that prevented Lenis interference.
      if (this._touchHandler) {
        modal.removeEventListener('touchstart', this._touchHandler);
        modal.removeEventListener('touchmove',  this._touchHandler);
        modal.removeEventListener('wheel',      this._touchHandler);
        this._touchHandler = null;
      }

      // Resume Lenis smooth scroll if available.
      if (typeof XanhBase !== 'undefined' && XanhBase.getLenis) {
        const lenis = XanhBase.getLenis();
        if (lenis && lenis.start) lenis.start();
      }

      // Mark as closed for frequency management.
      FrequencyManager.markClosed(id, frequency);

      // Remove video iframe to stop playback.
      const iframe = modal.querySelector('.x-modal__video-wrap iframe');
      if (iframe) iframe.remove();

      // Restore focus.
      if (this._focusHandler) {
        document.removeEventListener('keydown', this._focusHandler);
        this._focusHandler = null;
      }
      if (this.lastFocusEl) {
        this.lastFocusEl.focus();
        this.lastFocusEl = null;
      }

      this.activeModal = null;
    }
  }

  /* ══════════════════════════════════════════
     4. INIT ON DOM READY
     ══════════════════════════════════════════ */
  const manager = new PopupModalManager();

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => manager.init());
  } else {
    manager.init();
  }

  // Expose globally for manual triggering if needed.
  window.XanhPopupManager = manager;
})();
