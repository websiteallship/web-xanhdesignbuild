/**
 * XANH — Preloader SVG Path Line Drawing Animation
 *
 * Uses GSAP to animate stroke-dashoffset on SVG paths/polygons,
 * creating a "drawing" effect for the XANH logo.
 *
 * Display Rules (UX-safe):
 * - All pages EXCEPT single detail pages (post, project, service)
 * - Mode "session": once per session (sessionStorage)
 * - Mode "always": every page load
 * - Respects prefers-reduced-motion
 * - Skips for crawlers/bots
 * - Skips on slow connections (2g)
 * - Safety timeout: 5s force-close
 *
 * Dependencies: GSAP 3.12+
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

'use strict';

const XanhPreloader = {
  /* ── Config ── */
  SELECTORS: {
    preloader: '#page-preloader',
    drawPaths: '.draw-path',
    fillPaths: '.fill-path',
    progress: '.preloader__progress',
    logo: '.preloader__logo',
  },

  /** Session key — show preloader only once per session. */
  SESSION_KEY: 'xanh_preloader_shown',

  /** Safety timeout (ms) — force close if animation stalls. */
  SAFETY_TIMEOUT: 5000,

  /* ── State ── */
  _timeline: null,
  _safetyTimer: null,
  _mode: 'session', // 'session' | 'per-page' | 'always'

  /**
   * Initialize preloader with smart display rules.
   * Returns early (removes preloader) if any skip condition is met.
   */
  init() {
    const preloader = document.querySelector(this.SELECTORS.preloader);
    if (!preloader) return;

    /* Read display mode from data attribute */
    this._mode = preloader.dataset.mode || 'session';

    /* ── Display Rules: check all skip conditions ── */
    if (this._shouldSkip()) {
      preloader.remove();
      document.body.classList.remove('preloader-active');
      return;
    }

    /* Lock scroll */
    document.body.classList.add('preloader-active');

    /* Wait for GSAP */
    if (typeof gsap === 'undefined') {
      this._dismiss(preloader);
      return;
    }

    /* Prepare SVG paths */
    this._preparePaths(preloader);

    /* Build & play timeline */
    this._buildTimeline(preloader);

    /* Safety net */
    this._safetyTimer = setTimeout(() => {
      this._dismiss(preloader);
    }, this.SAFETY_TIMEOUT);
  },

  /**
   * Check all skip conditions.
   * @returns {boolean} true if preloader should be skipped.
   */
  _shouldSkip() {
    /* 1a. Session check — global (show once for entire session) */
    if (this._mode === 'session' && sessionStorage.getItem(this.SESSION_KEY)) {
      return true;
    }

    /* 1b. Per-page check — show once per page per session */
    if (this._mode === 'per-page') {
      const pageKey = this.SESSION_KEY + '_' + window.location.pathname;
      if (sessionStorage.getItem(pageKey)) {
        return true;
      }
    }

    /* 2. Reduced motion preference */
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      return true;
    }

    /* 3. Skip on single detail pages (blog post, project, service)
     *    WordPress adds body classes: 'single', 'single-post',
     *    'single-xanh_project', 'single-xanh_service'. */
    const body = document.body;
    const singlePageClasses = [
      'single-post',          // Blog detail
      'single-xanh_project',  // Portfolio detail
      'single-xanh_service',  // Service detail
    ];
    const isSingleDetail = singlePageClasses.some(
      (cls) => body.classList.contains(cls)
    );
    if (isSingleDetail) {
      return true;
    }

    /* 4. Slow connection — don't add more wait time */
    const conn = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
    if (conn && conn.effectiveType === '2g') {
      return true;
    }

    /* 5. Bot / crawler / automated browser */
    if (navigator.webdriver) {
      return true;
    }
    if (/bot|crawl|spider|slurp|googlebot|bingbot|yandex/i.test(navigator.userAgent)) {
      return true;
    }

    return false;
  },

  /**
   * Measure each path and set stroke-dasharray + dashoffset.
   * Supports <path>, <polygon>, <polyline>, <line>, <rect>, <circle>, <ellipse>.
   */
  _preparePaths(preloader) {
    const drawPaths = preloader.querySelectorAll(this.SELECTORS.drawPaths);

    drawPaths.forEach((path) => {
      let length;
      try {
        length = path.getTotalLength();
      } catch {
        length = 1000; // Fallback for elements that don't support getTotalLength
      }

      path.style.strokeDasharray = length;
      path.style.strokeDashoffset = length;
    });
  },

  /**
   * Build GSAP timeline with 4 phases:
   * 1) Draw big letters (XANH icon marks) — 1.5s
   * 2) Draw text paths (DESIGN & BUILD) — 1s overlap
   * 3) Fill transition (stroke → fill) — 0.4s
   *    ↑ Progress bar tracks phases 1-3 only (0% → 100%)
   * 4) Logo shrink + Slide up reveal — after bar completes
   */
  _buildTimeline(preloader) {
    const fillPaths = preloader.querySelectorAll(this.SELECTORS.fillPaths);
    const progress = preloader.querySelector(this.SELECTORS.progress);
    const logo = preloader.querySelector(this.SELECTORS.logo);

    /* Separate paths by group for staggered timing */
    const bigLetters = preloader.querySelectorAll('.draw-path[data-group="icon"]');
    const textPaths = preloader.querySelectorAll('.draw-path[data-group="text"]');

    /* ── Loading sub-timeline (phases 1-3) — drives the progress bar ── */
    const loadTl = gsap.timeline({
      onUpdate: () => {
        if (progress) {
          progress.style.width = `${Math.round(loadTl.progress() * 100)}%`;
        }
      },
    });

    /* Phase 1: Draw big icon letters (X, A, N, H) */
    loadTl.to(bigLetters.length ? bigLetters : preloader.querySelectorAll(this.SELECTORS.drawPaths), {
      strokeDashoffset: 0,
      duration: 1.5,
      ease: 'power2.inOut',
      stagger: {
        each: 0.12,
        from: 'start',
      },
    });

    /* Phase 2: Draw text paths (DESIGN & BUILD) */
    if (textPaths.length) {
      loadTl.to(textPaths, {
        strokeDashoffset: 0,
        duration: 1,
        ease: 'power2.inOut',
        stagger: {
          each: 0.05,
          from: 'start',
        },
      }, '-=0.5');
    }

    /* Phase 3: Fill transition */
    loadTl.to({}, {
      duration: 0.1,
      onComplete: () => {
        fillPaths.forEach((p) => p.classList.add('is-visible'));
      },
    });

    /* Brief pause to show filled logo */
    loadTl.to({}, { duration: 0.3 });

    /* ── Master timeline — loading then exit ── */
    const tl = gsap.timeline({
      onComplete: () => {
        this._onComplete(preloader);
      },
    });

    /* Nest the loading timeline — progress bar reaches 100% here */
    tl.add(loadTl);

    /* ── Phase 4: Logo shrink + Slide up reveal (after bar = 100%) ── */
    tl.to(logo, {
      scale: 0.85,
      opacity: 0,
      duration: 0.4,
      ease: 'power2.in',
    });

    tl.to(preloader, {
      yPercent: -100,
      duration: 0.8,
      ease: 'power3.inOut',
    }, '-=0.15');

    this._timeline = tl;
  },

  /**
   * Called when timeline completes.
   */
  _onComplete(preloader) {
    /* Clear safety timer */
    if (this._safetyTimer) {
      clearTimeout(this._safetyTimer);
      this._safetyTimer = null;
    }

    /* Mark as shown */
    if (this._mode === 'session') {
      sessionStorage.setItem(this.SESSION_KEY, '1');
    } else if (this._mode === 'per-page') {
      const pageKey = this.SESSION_KEY + '_' + window.location.pathname;
      sessionStorage.setItem(pageKey, '1');
    }

    /* Unlock scroll */
    document.body.classList.remove('preloader-active');

    /* Remove from DOM after transition */
    setTimeout(() => {
      preloader.remove();
    }, 100);
  },

  /**
   * Force-dismiss preloader (safety fallback).
   */
  _dismiss(preloader) {
    if (this._timeline) {
      this._timeline.kill();
    }

    preloader.classList.add('is-done');

    if (this._mode === 'session') {
      sessionStorage.setItem(this.SESSION_KEY, '1');
    } else if (this._mode === 'per-page') {
      const pageKey = this.SESSION_KEY + '_' + window.location.pathname;
      sessionStorage.setItem(pageKey, '1');
    }

    document.body.classList.remove('preloader-active');

    setTimeout(() => {
      preloader.remove();
    }, 1000);
  },
};

/* ── Init on DOMContentLoaded ── */
document.addEventListener('DOMContentLoaded', () => {
  XanhPreloader.init();
});
