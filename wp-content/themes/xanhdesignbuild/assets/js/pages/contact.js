/**
 * XANH — Design & Build
 * Contact Page JavaScript
 * =============================================
 * Module pattern: XanhContact
 */

const XanhContact = {
  /* ── Entry point ── */
  init() {
    /* Hero reveal — page-specific selectors */
    XanhBase.initHeroReveal('#hero-bg', '.hero-el--fast');

    /* Page-specific modules only — global init (Lenis, Lucide,
       BackToTop, ScrollReveal, CookieConsent) already runs in main.js */
    this.initFAQ();
    this.initFormValidation();
    this.initFormSubmit();
    this.initSelectPlaceholder();

    /* Counter animation for hero stats (if present) */
    if (document.querySelector('[data-counter]')) {
      XanhBase.animateCounters('[data-counter]', { dataAttr: 'counter', useGSAP: true });
    }
  },

  /* ══════════════════════════════════════════
     FAQ ACCORDION — delegate to shared XanhBase
     ══════════════════════════════════════════ */
  initFAQ() {
    XanhBase.initFAQ('faq-list');
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


};

/* ── Bootstrap ── */
document.addEventListener('DOMContentLoaded', () => {
  XanhContact.init();
});
