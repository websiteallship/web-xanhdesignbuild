/**
 * XANH — Design & Build
 * Service Detail Page Module
 * =============================================
 * Libraries: GSAP, ScrollTrigger, Lenis, Lucide
 * Pattern:   XanhServiceDetail module
 * Hero:      Kế thừa portfolio.html (centered + counter)
 * Section 2: Asymmetric Editorial — IO-based animation
 */

const XanhServiceDetail = {
  lenis: null,
  prefersReducedMotion: false,

  /* ── Entry Point ── */
  init() {
    this.prefersReducedMotion = XanhBase.prefersReducedMotion();

    XanhBase.initLucide();
    this.lenis = XanhBase.initLenis();

    /* Hero reveal — bg + text elements */
    this.initHeroReveal();

    /* Counter animation in hero strip */
    this.initCounterAnimation();

    /* Section 2: Empathy scroll reveal */
    this.initEmpathyReveal();

    /* Section 3: Features Grid scroll reveal */
    this.initFeaturesReveal();

    /* Section 4: Process timeline reveal + active step */
    this.initProcessReveal();

    /* Section 5: Portfolio reveal */
    this.initPortfolioReveal();

    /* Section 6: Testimonial carousel */
    this.initTestimonialCarousel();

    /* Section 7: FAQ accordion */
    this.initFAQ();

    /* Section 8: CTA reveal */
    this.initCTAReveal();
  },

  /* ── S1: Hero reveal: bg scale + text fade-up ── */
  initHeroReveal() {
    const bg  = document.querySelector('.service-hero__bg');
    const els = document.querySelectorAll('.service-hero-el');

    if (!bg) return;

    requestAnimationFrame(() => { bg.classList.add('is-loaded'); });

    els.forEach((el, i) => {
      setTimeout(() => { el.classList.add('is-visible'); }, 300 + i * 200);
    });
  },

  /* ── S1: Counter number animation (IO) ── */
  initCounterAnimation() {
    const counters = document.querySelectorAll('.counter-number');
    if (!counters.length) return;

    const animateCounter = (el) => {
      const target   = parseInt(el.dataset.target, 10);
      const duration = 2000;
      const start    = performance.now();

      const step = (now) => {
        const elapsed  = now - start;
        const progress = Math.min(elapsed / duration, 1);
        const ease     = 1 - Math.pow(1 - progress, 3); /* ease-out cubic */
        el.textContent = Math.round(target * ease);
        if (progress < 1) requestAnimationFrame(step);
      };

      requestAnimationFrame(step);
    };

    if (this.prefersReducedMotion) {
      counters.forEach(el => { el.textContent = el.dataset.target; });
      return;
    }

    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          animateCounter(entry.target);
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });

    counters.forEach(el => io.observe(el));
  },

  /* ── S2: Empathy scroll reveal (IO, stagger via CSS delays) ── */
  initEmpathyReveal() {
    const items = document.querySelectorAll('.empathy-anim');
    if (!items.length) return;

    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          io.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.12,
      rootMargin: '0px 0px -60px 0px'
    });

    items.forEach(el => io.observe(el));
  },

  /* ── S3: Features Grid scroll reveal (IO, reuse anim-fade-up from base.css) ── */
  initFeaturesReveal() {
    const section = document.querySelector('#service-features');
    if (!section) return;

    const items = section.querySelectorAll('.anim-fade-up');
    if (!items.length) return;

    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          io.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.08,
      rootMargin: '0px 0px -50px 0px'
    });

    items.forEach(el => io.observe(el));
  },

  /* ── S4: Process timeline — scroll reveal + dynamic active step ── */
  initProcessReveal() {
    const section = document.querySelector('#service-process');
    if (!section) return;

    /* 1. Reveal sticky panel elements */
    const stickyAnims = section.querySelectorAll('.process-sticky .anim-fade-up');
    if (stickyAnims.length) {
      const stickyIO = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            stickyIO.unobserve(entry.target);
          }
        });
      }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
      stickyAnims.forEach(el => stickyIO.observe(el));
    }

    /* 2. Reveal steps (stagger via CSS transition-delay) */
    const steps = section.querySelectorAll('.process-step');
    if (!steps.length) return;

    const stepIO = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          stepIO.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15, rootMargin: '0px 0px -60px 0px' });

    steps.forEach(el => stepIO.observe(el));

    /* 3. Dynamic active step on scroll */
    if (this.prefersReducedMotion) return;

    const activeIO = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          /* Remove all active, set current */
          steps.forEach(s => s.classList.remove('is-active'));
          entry.target.classList.add('is-active');
        }
      });
    }, { threshold: 0.5, rootMargin: '-20% 0px -40% 0px' });

    steps.forEach(el => activeIO.observe(el));
  },

  /* ── S5: Portfolio cards scroll reveal ── */
  initPortfolioReveal() {
    const section = document.querySelector('#service-portfolio');
    if (!section) return;

    const items = section.querySelectorAll('.anim-fade-up');
    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

    items.forEach(el => io.observe(el));
  },

  /* ── S6: Testimonial carousel + auto-play ── */
  initTestimonialCarousel() {
    const section = document.querySelector('#service-testimonial');
    if (!section) return;

    const slides = section.querySelectorAll('.s6-testimonial__slide');
    const dots   = section.querySelectorAll('.s6-testimonial__dot');
    if (!slides.length) return;

    let current  = 0;
    let timer    = null;
    const INTERVAL = 5000;

    const goTo = (index) => {
      /* Mark current slide as leaving (exit to left) */
      const prev = slides[current];
      prev.classList.add('is-leaving');
      prev.classList.remove('is-active');

      dots.forEach(d => { d.classList.remove('is-active'); d.setAttribute('aria-selected', 'false'); });

      current = index;
      slides[current].classList.add('is-active');
      dots[current].classList.add('is-active');
      dots[current].setAttribute('aria-selected', 'true');

      /* Clean up leaving class after transition */
      setTimeout(() => prev.classList.remove('is-leaving'), 650);
    };

    const next = () => {
      goTo((current + 1) % slides.length);
    };

    const startAutoPlay = () => {
      stopAutoPlay();
      timer = setInterval(next, INTERVAL);
    };

    const stopAutoPlay = () => {
      if (timer) { clearInterval(timer); timer = null; }
    };

    /* Dot click */
    dots.forEach(dot => {
      dot.addEventListener('click', () => {
        goTo(parseInt(dot.dataset.goto, 10));
        startAutoPlay(); /* restart timer */
      });
    });

    /* IO: auto-play only when visible */
    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) { startAutoPlay(); }
        else { stopAutoPlay(); }
      });
    }, { threshold: 0.3 });
    io.observe(section);

    /* Reveal header + wrapper */
    const revealEls = section.querySelectorAll('.s6-testimonial__header, .s6-testimonial__wrapper');
    if (revealEls.length) {
      const revealIO = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            revealIO.unobserve(entry.target);
          }
        });
      }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
      revealEls.forEach(el => revealIO.observe(el));
    }
  },

  /* ── S7: FAQ Accordion ── */
  initFAQ() {
    const section = document.querySelector('.s7-faq');
    const faqList = document.getElementById('faq-list');
    if (!section || !faqList) return;

    /* Reveal fade-up elements in FAQ */
    const revealEls = section.querySelectorAll('.anim-fade-up');
    if (revealEls.length) {
      const revealIO = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            revealIO.unobserve(entry.target);
          }
        });
      }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
      revealEls.forEach(el => revealIO.observe(el));
    }

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

  /* ── S8: CTA Reveal ── */
  initCTAReveal() {
    const section = document.querySelector('#service-cta');
    if (!section) return;

    const items = section.querySelectorAll('.anim-fade-up');
    if (!items.length) return;

    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

    items.forEach(el => io.observe(el));
  },
};

/* ── Bootstrap ── */
document.addEventListener('DOMContentLoaded', () => {
  XanhServiceDetail.init();
});
