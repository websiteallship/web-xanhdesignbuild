/**
 * XANH — Design & Build
 * Contact Page JavaScript
 * =============================================
 * Module pattern: XanhContact
 */

const XanhContact = {
  /* ── Entry point ── */
  init() {
    XanhBase.initHeroReveal('#hero-bg', '.contact-hero-el');
    this.initFAQ();
    this.initFormValidation();
    this.initFormSubmit();
    this.initSelectPlaceholder();
    XanhBase.initBackToTop('back-to-top', 400);
    XanhBase.initCookieConsent();
    XanhBase.initLenis({ lerp: 0.07 });

    if (document.querySelector('[data-counter]')) {
      XanhBase.animateCounters('[data-counter]', { dataAttr: 'counter', useGSAP: true });
    }
    if (document.querySelector('.anim-fade-up, .anim-fade-left, .anim-fade-right')) {
      XanhBase.initScrollReveal('.anim-fade-up, .anim-fade-left, .anim-fade-right');
    }
    XanhBase.initLucide();
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


};

/* ── Bootstrap ── */
document.addEventListener('DOMContentLoaded', () => {
  XanhContact.init();
});
