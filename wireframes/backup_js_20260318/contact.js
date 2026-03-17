/**
 * XANH — Design & Build
 * Contact Page JavaScript
 * =============================================
 * Module pattern: XanhContact
 */

const XanhContact = {
  /* ── Entry point ── */
  init() {
    this.initHeroEntrance();
    this.initFAQ();
    this.initFormValidation();
    this.initFormSubmit();
    this.initSelectPlaceholder();
    this.initBackToTop();
    this.initCookieConsent();
    this.initLenis();

    if (document.querySelector('[data-counter]')) {
      this.initCounters();
    }
    if (document.querySelector('.anim-fade-up, .anim-fade-left, .anim-fade-right')) {
      this.initScrollAnimations();
    }
  },

  /* ══════════════════════════════════════════
     HERO ENTRANCE
     ══════════════════════════════════════════ */
  initHeroEntrance() {
    const heroBg = document.getElementById('hero-bg');
    const heroEls = document.querySelectorAll('.contact-hero-el');

    if (heroBg) {
      requestAnimationFrame(() => {
        heroBg.classList.add('is-loaded');
      });
    }

    if (heroEls.length) {
      setTimeout(() => {
        heroEls.forEach(el => el.classList.add('is-visible'));
      }, 300);
    }
  },



  /* ══════════════════════════════════════════
     FAQ ACCORDION
     ══════════════════════════════════════════ */
  initFAQ() {
    const faqList = document.getElementById('faq-list');
    if (!faqList) return;

    faqList.addEventListener('click', (e) => {
      const btn = e.target.closest('.faq-item__question');
      if (!btn) return;

      const item = btn.closest('.faq-item');
      const isOpen = item.classList.contains('is-open');

      // Close all
      faqList.querySelectorAll('.faq-item.is-open').forEach(openItem => {
        openItem.classList.remove('is-open');
        openItem.querySelector('.faq-item__question').setAttribute('aria-expanded', 'false');
        openItem.querySelector('.faq-item__answer').style.maxHeight = '0';
      });

      // Open clicked (toggle)
      if (!isOpen) {
        item.classList.add('is-open');
        btn.setAttribute('aria-expanded', 'true');
        const answer = item.querySelector('.faq-item__answer');
        answer.style.maxHeight = answer.scrollHeight + 'px';
      }
    });
  },

  /* ══════════════════════════════════════════
     FORM — Real-time Validation
     ══════════════════════════════════════════ */
  initFormValidation() {
    const form = document.getElementById('contact-form');
    if (!form) return;

    const fields = form.querySelectorAll('.form-field[data-validate]');
    fields.forEach(field => {
      const input = field.querySelector('.form-field__input, .form-field__select, .form-field__textarea');
      if (!input) return;

      input.addEventListener('blur', () => this.validateField(field));
      input.addEventListener('input', () => {
        // Clear error on typing
        if (field.classList.contains('is-error')) {
          field.classList.remove('is-error');
        }
      });
    });
  },

  validateField(field) {
    const type = field.dataset.validate;
    const input = field.querySelector('.form-field__input, .form-field__select, .form-field__textarea');
    if (!input) return false;

    const value = input.value.trim();
    let isValid = true;

    switch (type) {
      case 'required':
        isValid = value.length > 0;
        break;
      case 'phone':
        isValid = /^[0-9+\-\s()]{8,15}$/.test(value);
        break;
      case 'select':
        isValid = value.length > 0;
        break;
      default:
        isValid = true;
    }

    field.classList.toggle('is-valid', isValid && value.length > 0);
    field.classList.toggle('is-error', !isValid && value.length > 0);
    return isValid;
  },

  /* ══════════════════════════════════════════
     FORM — Submit Handler (Mockup)
     ══════════════════════════════════════════ */
  initFormSubmit() {
    const form = document.getElementById('contact-form');
    const submitBtn = document.getElementById('form-submit-btn');
    if (!form || !submitBtn) return;

    form.addEventListener('submit', (e) => {
      e.preventDefault();

      // Validate all required fields
      const fields = form.querySelectorAll('.form-field[data-validate]');
      let allValid = true;

      fields.forEach(field => {
        if (!this.validateField(field)) {
          allValid = false;
        }
      });

      if (!allValid) return;

      // Show loading state
      submitBtn.classList.add('is-loading');
      submitBtn.disabled = true;

      // Simulate submission (2s delay)
      setTimeout(() => {
        submitBtn.classList.remove('is-loading');
        submitBtn.disabled = false;

        // Show success message (in real app → redirect to thank-you page)
        const btnText = submitBtn.querySelector('.btn__text');
        if (btnText) {
          btnText.textContent = '✓ Đã Gửi Thành Công!';
          submitBtn.classList.add('btn--success');
        }

        // Reset after 3s
        setTimeout(() => {
          if (btnText) {
            btnText.textContent = 'Đặt Lịch Tư Vấn Riêng';
            submitBtn.classList.remove('btn--success');
          }
          form.reset();
          form.querySelectorAll('.form-field').forEach(f => {
            f.classList.remove('is-valid', 'is-error');
          });
          // Reset select placeholder
          const sel = form.querySelector('.form-field__select');
          if (sel) sel.dataset.empty = 'true';
        }, 3000);
      }, 2000);
    });
  },

  /* ══════════════════════════════════════════
     SELECT — Placeholder State Tracker
     ══════════════════════════════════════════ */
  initSelectPlaceholder() {
    const selects = document.querySelectorAll('.form-field__select');
    selects.forEach(sel => {
      sel.addEventListener('change', () => {
        sel.dataset.empty = sel.value === '' ? 'true' : 'false';
      });
    });
  },

  /* ══════════════════════════════════════════
     SCROLL ANIMATIONS — IntersectionObserver
     ══════════════════════════════════════════ */
  initScrollAnimations() {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const animEls = document.querySelectorAll('.anim-fade-up, .anim-fade-left, .anim-fade-right');

    if (prefersReducedMotion) {
      animEls.forEach(el => {
        el.classList.add('is-visible');
        el.style.opacity = '1';
        el.style.transform = 'none';
      });
      return;
    }

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15 });

    animEls.forEach(el => observer.observe(el));
  },

  /* ══════════════════════════════════════════
     COUNTERS — GSAP count-up
     ══════════════════════════════════════════ */
  initCounters() {
    const counters = document.querySelectorAll('[data-counter]');
    if (!counters.length) return;

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
      gsap.registerPlugin(ScrollTrigger);

      counters.forEach(counter => {
        const target = parseInt(counter.dataset.counter, 10);

        if (prefersReducedMotion) {
          counter.textContent = target;
          return;
        }

        gsap.to(counter, {
          textContent: target,
          duration: 2,
          snap: { textContent: 1 },
          ease: 'power1.inOut',
          scrollTrigger: {
            trigger: counter,
            start: 'top 85%',
            once: true,
          },
        });
      });
    } else {
      // Fallback: show numbers immediately
      counters.forEach(counter => {
        counter.textContent = counter.dataset.counter;
      });
    }
  },

  /* ══════════════════════════════════════════
     BACK TO TOP
     ══════════════════════════════════════════ */
  initBackToTop() {
    const btn = document.getElementById('back-to-top');
    if (!btn) return;

    let ticking = false;
    window.addEventListener('scroll', () => {
      if (!ticking) {
        requestAnimationFrame(() => {
          btn.classList.toggle('is-visible', window.scrollY > 400);
          ticking = false;
        });
        ticking = true;
      }
    }, { passive: true });

    btn.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  },

  /* ══════════════════════════════════════════
     LENIS — Smooth Scroll
     ══════════════════════════════════════════ */
  initLenis() {
    if (typeof Lenis === 'undefined') return;
    if (typeof gsap === 'undefined') return;

    try {
      const lenis = new Lenis({
        lerp: 0.07,
        smoothWheel: true,
        wheelMultiplier: 0.8,
      });

      if (typeof ScrollTrigger !== 'undefined') {
        lenis.on('scroll', ScrollTrigger.update);
        gsap.ticker.add((time) => lenis.raf(time * 1000));
        gsap.ticker.lagSmoothing(0);
      }
    } catch (error) {
      console.warn('[XANH] Lenis init failed:', error.message);
    }
  },

  /* ══════════════════════════════════════════
     COOKIE CONSENT
     ══════════════════════════════════════════ */
  initCookieConsent() {
    const banner = document.getElementById('cookie-consent');
    const acceptBtn = document.getElementById('cookie-accept');
    const settingsBtn = document.getElementById('cookie-settings');
    if (!banner) return;

    // Check if already accepted
    if (localStorage.getItem('xanh_cookie_consent') === 'true') return;

    // Show after 2s
    setTimeout(() => {
      banner.classList.add('is-visible');
    }, 2000);

    if (acceptBtn) {
      acceptBtn.addEventListener('click', () => {
        localStorage.setItem('xanh_cookie_consent', 'true');
        banner.classList.remove('is-visible');
      });
    }

    if (settingsBtn) {
      settingsBtn.addEventListener('click', () => {
        // In a real app, open cookie preferences modal
        console.warn('[XANH] Cookie settings modal — not implemented in wireframe');
        banner.classList.remove('is-visible');
      });
    }
  },
};

/* ── Bootstrap ── */
document.addEventListener('DOMContentLoaded', () => {
  XanhContact.init();
});
