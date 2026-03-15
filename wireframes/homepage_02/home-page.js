/**
 * XANH - Design & Build
 * Homepage 02 Wireframe: Minnaro-inspired
 * =========================================
 * Libraries: Swiper, GSAP, ScrollTrigger, Lenis, Lucide
 */

document.addEventListener('DOMContentLoaded', () => {
  // ── Initialize Lucide Icons ──
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }

  // ── Mobile Drawer Menu ──
  const mobileMenuBtn = document.getElementById('mobile-menu-btn');
  const mobileDrawer = document.getElementById('mobile-drawer');
  const mobileOverlay = document.getElementById('mobile-overlay');
  const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');

  let isDrawerOpen = false;

  function toggleDrawer() {
    if (isDrawerOpen) {
      closeDrawer();
    } else {
      openDrawer();
    }
  }

  function openDrawer() {
    isDrawerOpen = true;
    mobileMenuBtn.classList.add('is-active');

    mobileDrawer.classList.remove('translate-x-full');
    mobileDrawer.classList.add('translate-x-0');
    mobileOverlay.classList.remove('opacity-0', 'pointer-events-none');
    mobileOverlay.classList.add('opacity-100', 'pointer-events-auto');
    document.body.style.overflow = 'hidden';

    // Force hamburger icon white when drawer is open (dark green background)
    mobileMenuBtn.querySelectorAll('.hamburger-line').forEach(l => {
      l.classList.remove('bg-dark');
      l.classList.add('bg-white');
    });

    // Staggered link reveal
    mobileNavLinks.forEach((link, i) => {
      link.style.opacity = '0';
      link.style.transform = 'translateX(20px)';
      setTimeout(() => {
        link.style.transition = 'opacity 0.35s ease, transform 0.35s ease';
        link.style.opacity = '1';
        link.style.transform = 'translateX(0)';
      }, 80 + i * 60);
    });
  }

  function closeDrawer() {
    isDrawerOpen = false;
    mobileMenuBtn.classList.remove('is-active');

    mobileDrawer.classList.remove('translate-x-0');
    mobileDrawer.classList.add('translate-x-full');
    mobileOverlay.classList.remove('opacity-100', 'pointer-events-auto');
    mobileOverlay.classList.add('opacity-0', 'pointer-events-none');
    document.body.style.overflow = '';

    // Restore hamburger color based on current scroll position
    const scrollY = window.scrollY || window.pageYOffset;
    mobileMenuBtn.querySelectorAll('.hamburger-line').forEach(l => {
      if (scrollY > 80) {
        l.classList.remove('bg-white');
        l.classList.add('bg-dark');
      } else {
        l.classList.remove('bg-dark');
        l.classList.add('bg-white');
      }
    });
  }

  if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', toggleDrawer);
  if (mobileOverlay) mobileOverlay.addEventListener('click', closeDrawer);

  // Close on Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !mobileDrawer.classList.contains('translate-x-full')) {
      closeDrawer();
    }
  });

  // Close drawer when clicking a nav link
  mobileNavLinks.forEach(link => {
    link.addEventListener('click', () => {
      closeDrawer();
    });
  });

  // ── Lenis Smooth Scroll ──
  let lenis;
  if (typeof Lenis !== 'undefined') {
    lenis = new Lenis({
      lerp: 0.1,
      smoothWheel: true,
      wheelMultiplier: 0.8,
    });

    function raf(time) {
      lenis.raf(time);
      requestAnimationFrame(raf);
    }
    requestAnimationFrame(raf);

    // Sync Lenis with GSAP ScrollTrigger
    if (typeof ScrollTrigger !== 'undefined') {
      lenis.on('scroll', ScrollTrigger.update);
      gsap.ticker.add((time) => {
        lenis.raf(time * 1000);
      });
      gsap.ticker.lagSmoothing(0);
    }
  }

  // ── Hero Fixed Content Reveal ──
  function revealHeroContent() {
    const els = document.querySelectorAll('.hero-headline, .hero-subheadline, .hero-cta');
    els.forEach(el => {
      el.classList.add('is-visible');
    });
  }

  // Reveal hero content after a short delay (after page load)
  setTimeout(revealHeroContent, 400);

  // ── Hero Swiper Slider (Background only) ──
  if (typeof Swiper !== 'undefined') {
    new Swiper('.hero-swiper', {
      loop: true,
      speed: 1500,
      effect: 'fade',
      fadeEffect: {
        crossFade: true,
      },
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
      },
      pagination: {
        el: '.hero-pagination',
        clickable: true,
      },
    });
  }

  // ── Header Scroll Behavior ──
  const header = document.getElementById('site-header');
  let lastScrollY = 0;

  function handleHeaderScroll() {
    const scrollY = window.scrollY || window.pageYOffset;
    const menuBtn = document.getElementById('mobile-menu-btn');

    if (scrollY > 80) {
      header.classList.add('is-scrolled');
      // Toggle hamburger lines to dark
      if (menuBtn) {
        menuBtn.querySelectorAll('.hamburger-line').forEach(l => {
          l.classList.remove('bg-white');
          l.classList.add('bg-dark');
        });
      }
    } else {
      header.classList.remove('is-scrolled');
      // Toggle hamburger lines to white
      if (menuBtn) {
        menuBtn.querySelectorAll('.hamburger-line').forEach(l => {
          l.classList.remove('bg-dark');
          l.classList.add('bg-white');
        });
      }
    }

    lastScrollY = scrollY;
  }

  // Use requestAnimationFrame for performance
  let headerTicking = false;
  window.addEventListener('scroll', () => {
    if (!headerTicking) {
      requestAnimationFrame(() => {
        handleHeaderScroll();
        headerTicking = false;
      });
      headerTicking = true;
    }
  }, { passive: true });

  // ── GSAP ScrollTrigger Animations ──
  if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
    gsap.registerPlugin(ScrollTrigger);

    // Empathy section — gentle scale-only parallax (no yPercent to prevent gap)
    const empathyBg = document.querySelector('.empathy-bg img');
    if (empathyBg) {
      gsap.fromTo(empathyBg,
        { scale: 1.08 },
        {
          scale: 1,
          ease: 'none',
          scrollTrigger: {
            trigger: '#empathy',
            start: 'top bottom',
            end: 'bottom top',
            scrub: 1,
          },
        }
      );
    }

    // Empathy section — sequential text reveal (fromTo for clean translateY)
    const empathyEls = gsap.utils.toArray('.empathy-el');
    if (empathyEls.length > 0) {
      gsap.fromTo(empathyEls, 
        { y: 30, opacity: 0 },
        {
          y: 0,
          opacity: 1,
          duration: 0.8,
          stagger: 0.15,
          ease: "power2.out",
          scrollTrigger: {
            trigger: '#empathy',
            start: 'top 75%',
            once: true
          }
        }
      );
    }

    // Core Values section — stagger fade-up entrance
    const valuesEls = gsap.utils.toArray('.values-el');
    if (valuesEls.length > 0) {
      gsap.fromTo(valuesEls, 
        { y: 30, opacity: 0 },
        {
          y: 0,
          opacity: 1,
          duration: 0.8,
          stagger: 0.1,
          ease: "power2.out",
          scrollTrigger: {
            trigger: '#core-values',
            start: 'top 75%',
            once: true
          }
        }
      );
    }

    // Services section — multi-stage premium reveal
    const servicesTl = gsap.timeline({
      scrollTrigger: {
        trigger: '#services',
        start: 'top 70%',
        once: true
      }
    });

    // 1. Section header text fades in first
    const servicesHeader = document.querySelectorAll('#services .services-el:not(.service-card)');
    if (servicesHeader.length > 0) {
      servicesTl.fromTo(servicesHeader,
        { y: 32, opacity: 0 },
        { y: 0, opacity: 1, duration: 0.75, stagger: 0.12, ease: 'power2.out' }
      );
    }

    // 2. Each card's image clips up from bottom (staggered cascade)
    const serviceCards = document.querySelectorAll('.service-card');
    if (serviceCards.length > 0) {
      // Image clip-path reveal — each card one after another
      serviceCards.forEach((card, i) => {
        const imgWrap = card.querySelector('.service-card__img-wrap');
        const img = card.querySelector('.service-card__img');
        if (imgWrap && img) {
          servicesTl.fromTo(imgWrap,
            { clipPath: 'inset(100% 0 0 0)' },
            { clipPath: 'inset(0% 0 0 0)', duration: 0.85, ease: 'power3.out' },
            i === 0 ? '-=0.3' : `-=0.6`  // stagger by overlapping
          );
          // Image scale down simultaneously with reveal
          servicesTl.fromTo(img,
            { scale: 1.12 },
            { scale: 1, duration: 0.85, ease: 'power2.out' },
            '<'  // start at same time as imgWrap
          );
        }
      });

      // 3. Card body content (icon → title → desc → link) fade-up in sequence
      servicesTl.fromTo(
        document.querySelectorAll('.service-card__icon'),
        { y: 16, opacity: 0 },
        { y: 0, opacity: 0.7, duration: 0.6, stagger: 0.08, ease: 'power2.out' },
        '-=1.0'
      );
      servicesTl.fromTo(
        document.querySelectorAll('.service-card__title'),
        { y: 16, opacity: 0 },
        { y: 0, opacity: 1, duration: 0.6, stagger: 0.08, ease: 'power2.out' },
        '<+0.1'
      );
      servicesTl.fromTo(
        document.querySelectorAll('.service-card__desc'),
        { y: 12, opacity: 0 },
        { y: 0, opacity: 1, duration: 0.55, stagger: 0.08, ease: 'power2.out' },
        '<+0.08'
      );
    }

    // Hero images — subtle scale-only parallax (no yPercent to prevent gap)
    gsap.utils.toArray('.hero-swiper .swiper-slide img').forEach((img) => {
      gsap.fromTo(img,
        { scale: 1.05 },
        {
          scale: 1,
          ease: 'none',
          scrollTrigger: {
            trigger: '#hero',
            start: 'top top',
            end: 'bottom top',
            scrub: 1,
          },
        }
      );
    });
  }

  // ── Intersection Observer fallback for Empathy section ──
  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
    const empathyObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            const elements = entry.target.querySelectorAll('.empathy-el');
            elements.forEach((el, index) => {
              setTimeout(() => {
                el.classList.add('is-visible');
              }, index * 200);
            });
            empathyObserver.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.15 }
    );

    const empathySection = document.getElementById('empathy');
    if (empathySection) {
      empathyObserver.observe(empathySection);
    }
  }
});

/* ============================================= */
/* SECTION CTA — Scroll entrance animation        */
/* ============================================= */
(function () {
  'use strict';

  const ctaSection = document.getElementById('cta');
  if (!ctaSection) return;

  const ctaEls = ctaSection.querySelectorAll('.cta-el');
  const ctaImgPanel = ctaSection.querySelector('.cta-panel--image');
  const counterEls = ctaSection.querySelectorAll('.cta-badge__num[data-count]');

  /* ── Counter animation ── */
  function animateCounters() {
    counterEls.forEach((el) => {
      const target = parseInt(el.dataset.count, 10);
      const suffix = el.dataset.suffix || '';
      const duration = 1800; // ms
      const start = performance.now();

      function update(now) {
        const elapsed = now - start;
        const progress = Math.min(elapsed / duration, 1);
        // easeOutQuart for a snappy feel
        const ease = 1 - Math.pow(1 - progress, 4);
        const current = Math.round(ease * target);
        el.textContent = current + suffix;
        if (progress < 1) requestAnimationFrame(update);
      }

      requestAnimationFrame(update);
    });
  }

  if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
    // Set initial states
    gsap.set(ctaEls, { opacity: 0, y: 36 });
    if (ctaImgPanel) gsap.set(ctaImgPanel, { opacity: 0, x: 40 });

    ScrollTrigger.create({
      trigger: ctaSection,
      start: 'top 78%',
      once: true,
      onEnter: () => {
        // Stagger in the text elements
        gsap.to(ctaEls, {
          opacity: 1,
          y: 0,
          duration: 0.75,
          ease: 'power3.out',
          stagger: 0.12,
        });
        // Slide in the image panel
        if (ctaImgPanel) {
          gsap.to(ctaImgPanel, {
            opacity: 1,
            x: 0,
            duration: 0.9,
            ease: 'power3.out',
            delay: 0.1,
          });
        }
        // Start counter animation after badges become visible
        setTimeout(animateCounters, 500);
      },
    });
  } else {
    // Fallback: IntersectionObserver
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            ctaEls.forEach((el, i) => {
              setTimeout(() => el.classList.add('is-visible'), i * 120);
            });
            if (ctaImgPanel) ctaImgPanel.classList.add('is-visible');
            setTimeout(animateCounters, 500);
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.15 }
    );
    observer.observe(ctaSection);
  }
})();

/* ============================================= */
/* SECTION 5 — Before / After Slider + Thumbnails */
/* ============================================= */
(function () {
  'use strict';

  /* ── Project data ── */
  function buildMetaHTML(area, duration, year) {
    return `
      <span class="meta-item"><i data-lucide="maximize"></i> ${area}</span>
      <span class="meta-sep"></span>
      <span class="meta-item"><i data-lucide="clock"></i> ${duration}</span>
      <span class="meta-sep"></span>
      <span class="meta-item"><i data-lucide="calendar"></i> ${year}</span>
    `;
  }

  const PROJECTS = [
    {
      beforeImg: 'img/project-before-1.png',
      afterImg:  'img/project-after-1.png',
      tag:       'Cải tạo toàn diện',
      title:     'Nhà Phố Quận 7',
      meta:      buildMetaHTML('120 m²', '6 tháng', '2025'),
      quote:     '"Chúng tôi không nghĩ ngôi nhà 20 năm tuổi có thể trở nên đẹp đến vậy. XANH đã biến giấc mơ thành hiện thực — đúng tiến độ, đúng chi phí."',
      author:    '— Anh Minh & Chị Hương, Q7, TP.HCM',
    },
    {
      beforeImg: '../img/project-3.png',
      afterImg:  '../img/project-2.png',
      tag:       'Xây mới trọn gói',
      title:     'Biệt Thự Thảo Điền',
      meta:      buildMetaHTML('280 m²', '14 tháng', '2024'),
      quote:     '"Từ mảnh đất trống đến ngôi nhà mơ ước — XANH tận tâm từ bản vẽ đầu tiên đến ngày bàn giao chìa khoá."',
      author:    '— Gia đình anh Tuấn, Thảo Điền, Q2',
    },
    {
      beforeImg: '../img/project-4.png',
      afterImg:  '../img/project-3.png',
      tag:       'Thiết kế nội thất',
      title:     'Penthouse Quận 2',
      meta:      buildMetaHTML('95 m²', '3 tháng', '2024'),
      quote:     '"Không gian sống thay đổi hoàn toàn — sang trọng, tinh tế nhưng vẫn ấm cúng cho gia đình nhỏ."',
      author:    '— Chị Linh, Thủ Thiêm, Q2',
    },
    {
      beforeImg: '../img/project-1.png',
      afterImg:  '../img/project-4.png',
      tag:       'Xây mới & nội thất',
      title:     'Villa Bình Dương',
      meta:      buildMetaHTML('350 m²', '18 tháng', '2023'),
      quote:     '"XANH đã giúp chúng tôi xây dựng không chỉ một ngôi nhà, mà cả một phong cách sống mới — hòa mình với thiên nhiên."',
      author:    '— Anh Phúc & Chị Ngọc, Bình Dương',
    },
  ];

  let currentIndex = 0;
  let isDragging = false;

  /* ── DOM refs ── */
  const slider      = document.getElementById('ba-slider');
  const beforeClip  = document.getElementById('ba-before-clip');
  const beforeImg   = document.getElementById('ba-before-img');
  const afterImg    = document.getElementById('ba-after-img');
  const handle      = document.getElementById('ba-handle');
  const tagEl       = document.getElementById('ba-tag');
  const titleEl     = document.getElementById('ba-title');
  const metaEl      = document.getElementById('ba-meta');
  const quoteEl     = document.getElementById('ba-quote');
  const authorEl    = document.getElementById('ba-author');
  const thumbs      = document.querySelectorAll('.project-thumb');

  if (!slider) return; // Section not in DOM

  /* ── Before/After drag logic ── */
  function setSliderPosition(pct) {
    pct = Math.max(0, Math.min(100, pct));
    // clip-path: inset(0 <right>% 0 0) — right is 100-pct
    beforeClip.style.clipPath = 'inset(0 ' + (100 - pct) + '% 0 0)';
    handle.style.left = pct + '%';
  }

  function getPercentFromEvent(e) {
    const rect = slider.getBoundingClientRect();
    const x = e.clientX - rect.left;
    return (x / rect.width) * 100;
  }

  slider.addEventListener('pointerdown', (e) => {
    isDragging = true;
    slider.setPointerCapture(e.pointerId);
    setSliderPosition(getPercentFromEvent(e));
  });

  slider.addEventListener('pointermove', (e) => {
    if (!isDragging) return;
    setSliderPosition(getPercentFromEvent(e));
  });

  slider.addEventListener('pointerup', () => { isDragging = false; });
  slider.addEventListener('pointercancel', () => { isDragging = false; });

  // Initial position
  window.addEventListener('load', () => {
    setSliderPosition(50);
  });
  setSliderPosition(50);

  /* ── Thumbnail click → switch project ── */
  function switchProject(index) {
    if (index === currentIndex) return;
    currentIndex = index;
    const proj = PROJECTS[index];

    // Update active thumbnail
    thumbs.forEach((t) => t.classList.remove('is-active'));
    thumbs[index]?.classList.add('is-active');

    // Cross-fade images + info text using GSAP if available
    if (typeof gsap !== 'undefined') {
      const tl = gsap.timeline();
      tl.to([afterImg, beforeClip], { opacity: 0, duration: 0.3, ease: 'power2.in' })
        .to('#ba-info', { opacity: 0, y: 12, duration: 0.25, ease: 'power2.in' }, '<')
        .call(() => {
          afterImg.src   = proj.afterImg;
          beforeImg.src  = proj.beforeImg;
          tagEl.textContent    = proj.tag;
          titleEl.textContent  = proj.title;
          metaEl.innerHTML     = proj.meta;
          if (typeof lucide !== 'undefined') lucide.createIcons();
          quoteEl.textContent  = proj.quote;
          authorEl.textContent = proj.author;
          setSliderPosition(50);
        })
        .to([afterImg, beforeClip], { opacity: 1, duration: 0.35, ease: 'power2.out' })
        .to('#ba-info', { opacity: 1, y: 0, duration: 0.35, ease: 'power2.out' }, '<0.1');
    } else {
      // Fallback: instant swap
      afterImg.src   = proj.afterImg;
      beforeImg.src  = proj.beforeImg;
      tagEl.textContent    = proj.tag;
      titleEl.textContent  = proj.title;
      metaEl.innerHTML     = proj.meta;
      if (typeof lucide !== 'undefined') lucide.createIcons();
      quoteEl.textContent  = proj.quote;
      authorEl.textContent = proj.author;
      setSliderPosition(50);
    }
  }

  thumbs.forEach((btn) => {
    btn.addEventListener('click', () => {
      const idx = parseInt(btn.dataset.index, 10);
      switchProject(idx);
    });
  });

  /* ── GSAP scroll entrance animations ── */
  if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
    const projectsSection = document.getElementById('projects');
    if (projectsSection) {
      // Header elements
      const headerEls = projectsSection.querySelectorAll('.projects-el');
      // Slider + Info panel children
      const sliderWrap = projectsSection.querySelector('.ba-slider-wrap');
      const infoPanel = projectsSection.querySelector('.ba-info');
      const infoChildren = infoPanel
        ? infoPanel.querySelectorAll('.ba-info__tag, .ba-info__title, .ba-info__meta, .ba-info__quote, .ba-info__author, .ba-info__cta')
        : [];
      // Thumbnails
      const thumbCards = projectsSection.querySelectorAll('.project-thumb');
      // Handle for wiggle hint
      const handleKnob = projectsSection.querySelector('.ba-slider__handle-knob');

      // Set initial states
      gsap.set(headerEls, { opacity: 0, y: 40 });
      if (sliderWrap) gsap.set(sliderWrap, { opacity: 0, x: -60, scale: 0.96 });
      if (infoChildren.length) gsap.set(infoChildren, { opacity: 0, x: 30 });
      if (thumbCards.length) gsap.set(thumbCards, { opacity: 0, y: 30, scale: 0.95 });

      ScrollTrigger.create({
        trigger: projectsSection,
        start: 'top 80%',
        once: true,
        onEnter: () => {
          const tl = gsap.timeline();

          // 1) Header stagger in
          tl.to(headerEls, {
            opacity: 1,
            y: 0,
            duration: 0.7,
            ease: 'power3.out',
            stagger: 0.12,
          });

          // 2) Slider slides in from left
          if (sliderWrap) {
            tl.to(sliderWrap, {
              opacity: 1,
              x: 0,
              scale: 1,
              duration: 0.8,
              ease: 'power3.out',
            }, '-=0.3');
          }

          // 3) Info panel children stagger in from right
          if (infoChildren.length) {
            tl.to(infoChildren, {
              opacity: 1,
              x: 0,
              duration: 0.6,
              ease: 'power3.out',
              stagger: 0.08,
            }, '-=0.5');
          }

          // 4) Thumbnails cascade from bottom
          if (thumbCards.length) {
            tl.to(thumbCards, {
              opacity: 1,
              y: 0,
              scale: 1,
              duration: 0.6,
              ease: 'back.out(1.4)',
              stagger: 0.1,
            }, '-=0.3');
          }

          // 5) Slider handle wiggle hint (after entrance is done)
          if (handleKnob) {
            tl.call(() => setSliderPosition(35), null, '+=0.4')
            .to(handle, {
              left: '35%',
              duration: 0.5,
              ease: 'power2.inOut',
              onUpdate: () => {
                const pct = parseFloat(handle.style.left);
                setSliderPosition(pct);
              },
            }, '<')
            .to(handle, {
              left: '65%',
              duration: 0.7,
              ease: 'power2.inOut',
              onUpdate: () => {
                const pct = parseFloat(handle.style.left);
                setSliderPosition(pct);
              },
            })
            .to(handle, {
              left: '50%',
              duration: 0.5,
              ease: 'power2.inOut',
              onUpdate: () => {
                const pct = parseFloat(handle.style.left);
                setSliderPosition(pct);
              },
            });
          }
        },
      });
    }
  }
})();

/* ============================================= */
/* SECTION 7 — Process Steps Accordion           */
/* ============================================= */
(function () {
  'use strict';

  const section = document.getElementById('process');
  if (!section) return;

  const panels = section.querySelectorAll('.process-panel');

  /* ── Click handler: toggle active panel ── */
  panels.forEach((panel) => {
    panel.addEventListener('click', () => {
      if (panel.classList.contains('is-active')) return;
      // Remove active from all
      panels.forEach((p) => p.classList.remove('is-active'));
      // Set clicked as active
      panel.classList.add('is-active');
    });
  });

  /* ── GSAP Scroll entrance animation ── */
  if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
    const headerEls = section.querySelectorAll('.process-el');
    const accordion = section.querySelector('.process-accordion');

    gsap.set(headerEls, { opacity: 0, y: 40 });

    ScrollTrigger.create({
      trigger: section,
      start: 'top 80%',
      once: true,
      onEnter: () => {
        const tl = gsap.timeline();

        // Header stagger in
        tl.to(headerEls, {
          opacity: 1,
          y: 0,
          duration: 0.7,
          ease: 'power3.out',
          stagger: 0.12,
        });
      },
    });
  } else {
    // Fallback: IntersectionObserver
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            section.querySelectorAll('.process-el').forEach((el, i) => {
              setTimeout(() => el.classList.add('is-visible'), i * 120);
            });
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.15 }
    );
    observer.observe(section);
  }
})();
