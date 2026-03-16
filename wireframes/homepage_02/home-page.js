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
    const isDesktop = window.innerWidth >= 1024;

    // Set initial states
    gsap.set(ctaEls, { opacity: 0, y: 36 });
    if (ctaImgPanel) gsap.set(ctaImgPanel, { opacity: 0, x: 40 });

    // PC-only: individual badge & card animations
    const ctaCard     = ctaSection.querySelector('.cta-card');
    const ctaTextPanel = ctaSection.querySelector('.cta-panel--text');
    const ctaBadges   = ctaSection.querySelectorAll('.cta-badge');
    const ctaBadgeSeps = ctaSection.querySelectorAll('.cta-badge-sep');
    if (isDesktop && ctaCard) {
      gsap.set(ctaCard, { clipPath: 'inset(0 100% 0 0)' });
    }
    if (isDesktop && ctaBadges.length) {
      gsap.set(ctaBadges, { opacity: 0, y: 20 });
      gsap.set(ctaBadgeSeps, { opacity: 0, scaleY: 0 });
    }

    ScrollTrigger.create({
      trigger: ctaSection,
      start: 'top 78%',
      once: true,
      onEnter: () => {
        if (isDesktop) {
          // ── Desktop: multi-stage cinematic reveal ──
          const tl = gsap.timeline({ onComplete: () => setTimeout(animateCounters, 200) });

          // 1. Card slides open from left (clip-path wipe)
          if (ctaCard) {
            tl.to(ctaCard, {
              clipPath: 'inset(0 0% 0 0)',
              duration: 0.9,
              ease: 'power3.out',
            });
          }

          // 2. Text panel elements stagger up
          tl.to(ctaEls, {
            opacity: 1,
            y: 0,
            duration: 0.7,
            ease: 'power3.out',
            stagger: 0.1,
          }, '-=0.55');

          // 3. Image panel slides in from right
          if (ctaImgPanel) {
            tl.to(ctaImgPanel, {
              opacity: 1,
              x: 0,
              duration: 0.8,
              ease: 'power3.out',
            }, '-=0.6');
          }

          // 4. Badges sweep up one by one
          if (ctaBadges.length) {
            tl.to(ctaBadges, {
              opacity: 1,
              y: 0,
              duration: 0.5,
              ease: 'back.out(1.4)',
              stagger: 0.12,
            }, '-=0.3');
            tl.to(ctaBadgeSeps, {
              opacity: 1,
              scaleY: 1,
              duration: 0.4,
              transformOrigin: 'center center',
              ease: 'power2.out',
              stagger: 0.08,
            }, '<+0.1');
          }
        } else {
          // ── Mobile: simple stagger fade-up ──
          gsap.to(ctaEls, {
            opacity: 1,
            y: 0,
            duration: 0.75,
            ease: 'power3.out',
            stagger: 0.12,
          });
          if (ctaImgPanel) {
            gsap.to(ctaImgPanel, {
              opacity: 1,
              x: 0,
              duration: 0.9,
              ease: 'power3.out',
              delay: 0.1,
            });
          }
          setTimeout(animateCounters, 500);
        }
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
    {
      beforeImg: '../img/project-2.png',
      afterImg:  '../img/project-1.png',
      tag:       'Cải tạo & mở rộng',
      title:     'Nhà Vườn Gò Vấp',
      meta:      buildMetaHTML('180 m²', '9 tháng', '2024'),
      quote:     '"Ngôi nhà cũ kỹ nay trở thành không gian sống hiện đại, thoáng đãng. XANH đã lắng nghe và hiểu chúng tôi từng chi tiết nhỏ."',
      author:    '— Chị Mai & Anh Bảo, Gò Vấp, TP.HCM',
    },
    {
      beforeImg: '../img/project-4.png',
      afterImg:  '../img/project-2.png',
      tag:       'Thiết kế & thi công',
      title:     'Shophouse Phú Mỹ Hưng',
      meta:      buildMetaHTML('240 m²', '11 tháng', '2023'),
      quote:     '"Từ không gian thương mại đến nhà ở — XANH tích hợp khéo léo, vừa chuyên nghiệp vừa ấm áp như một ngôi nhà thật thụ."',
      author:    '— Anh Khoa, Phú Mỹ Hưng, Q7',
    },
    {
      beforeImg: '../img/project-3.png',
      afterImg:  '../img/project-3.png',
      tag:       'Xây mới trọn gói',
      title:     'Biệt Thự Nhà Bè',
      meta:      buildMetaHTML('420 m²', '22 tháng', '2022'),
      quote:     '"Chúng tôi tin tưởng giao toàn bộ dự án cho XANH — và kết quả vượt xa mong đợi. Một ngôi nhà đẹp, bền, đúng tiến độ."',
      author:    '— Gia đình anh Hùng, Nhà Bè, TP.HCM',
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

  /* ── Thumbs Swiper (desktop) ── */
  let thumbsSwiper = null;
  if (typeof Swiper !== 'undefined') {
    thumbsSwiper = new Swiper('.projects-thumbs-swiper', {
      slidesPerView: 2,
      spaceBetween: 12,
      loop: true,
      watchOverflow: true,
      navigation: {
        prevEl: '.projects-thumbs-prev',
        nextEl: '.projects-thumbs-next',
      },
      breakpoints: {
        640: {
          slidesPerView: 3,
          spaceBetween: 16,
        },
        1024: {
          slidesPerView: 4,
          spaceBetween: 20,
        },
      },
    });

    /* Mobile bottom nav for thumbs */
    const thumbsMobPrev = document.querySelector('.thumbs-mobile-prev');
    const thumbsMobNext = document.querySelector('.thumbs-mobile-next');
    if (thumbsMobPrev) thumbsMobPrev.addEventListener('click', () => thumbsSwiper.slidePrev());
    if (thumbsMobNext) thumbsMobNext.addEventListener('click', () => thumbsSwiper.slideNext());

    /* ── Pagination bullets entrance animation (desktop only) ── */
    if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined' && window.innerWidth >= 640) {
      const thumbsPagination = document.querySelector('.thumbs-pagination');
      if (thumbsPagination) {
        ScrollTrigger.create({
          trigger: thumbsPagination,
          start: 'top 90%',
          once: true,
          onEnter: () => {
            const bullets = thumbsPagination.querySelectorAll('.swiper-pagination-bullet');
            gsap.fromTo(bullets,
              { opacity: 0, scaleX: 0, transformOrigin: 'left center' },
              { opacity: 1, scaleX: 1, duration: 0.4, ease: 'power2.out', stagger: 0.06 }
            );
          },
        });
      }
    }

    /* ── Mobile Projects Swiper (≤1023px) — owns the pagination dots ── */
    const mobileSwiper = new Swiper('.projects-mobile-swiper', {
      slidesPerView: 1,
      spaceBetween: 16,
      loop: true,
      pagination: {
        el: '.thumbs-pagination',
        clickable: true,
      },
    });

    /* Wire mobile buttons to also control the card swiper on mobile */
    if (thumbsMobPrev) thumbsMobPrev.addEventListener('click', () => mobileSwiper.slidePrev());
    if (thumbsMobNext) thumbsMobNext.addEventListener('click', () => mobileSwiper.slideNext());
  }

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

    // Slide clicked thumb to first position (works correctly with loop:true)
    if (thumbsSwiper) {
      thumbsSwiper.slideToLoop(index);
    }

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
      // Thumbnail slides + thumbs-nav initial states
      const thumbSlides = projectsSection.querySelectorAll('.projects-thumbs-swiper .swiper-slide');
      const thumbsNav = projectsSection.querySelector('.thumbs-nav');
      // Handle for wiggle hint
      const handleKnob = projectsSection.querySelector('.ba-slider__handle-knob');

      // Set initial states
      gsap.set(headerEls, { opacity: 0, y: 40 });
      if (sliderWrap) gsap.set(sliderWrap, { opacity: 0, x: -60, scale: 0.96 });
      if (infoChildren.length) gsap.set(infoChildren, { opacity: 0, x: 30 });
      if (thumbSlides.length) gsap.set(thumbSlides, { opacity: 0, y: 30, scale: 0.95 });
      if (thumbsNav) gsap.set(thumbsNav, { opacity: 0, y: 20 });

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

          // 4) Thumbnail slides cascade from bottom
          if (thumbSlides.length) {
            tl.to(thumbSlides, {
              opacity: 1,
              y: 0,
              scale: 1,
              duration: 0.6,
              ease: 'back.out(1.4)',
              stagger: 0.1,
            }, '-=0.3');
          }

          // 5) Thumbs nav bar fade up
          if (thumbsNav) {
            tl.to(thumbsNav, {
              opacity: 1,
              y: 0,
              duration: 0.5,
              ease: 'power3.out',
            }, '-=0.2');

            // Stagger bullets inside pagination
            const bullets = thumbsNav.querySelectorAll('.swiper-pagination-bullet');
            if (bullets.length) {
              tl.fromTo(bullets,
                { opacity: 0, scaleX: 0, transformOrigin: 'left center' },
                { opacity: 1, scaleX: 1, duration: 0.4, ease: 'power2.out', stagger: 0.06 },
                '-=0.1'
              );
            }
          }

          // 6) Slider handle wiggle hint (after entrance is done)
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

    // PC only: stagger in each accordion panel
    const isDesktop = window.innerWidth >= 1024;
    if (isDesktop && panels.length) {
      gsap.set(panels, { opacity: 0, y: 50, scale: 0.97 });
    }

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

        // PC only: accordion panels cascade in from bottom, left-to-right
        if (isDesktop && panels.length) {
          tl.to(panels, {
            opacity: 1,
            y: 0,
            scale: 1,
            duration: 0.65,
            ease: 'power3.out',
            stagger: 0.1,
          }, '-=0.3');
        }
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

/* ============================================= */
/* SECTION 8 — CTA Contact Scroll Animation      */
/* ============================================= */
(function () {
  'use strict';

  const section = document.getElementById('cta-contact');
  if (!section) return;

  const els = section.querySelectorAll('.cta-contact-el');
  const bgImg = section.querySelector('.cta-contact__bg');

  if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
    // Set initial GSAP states
    gsap.set(els, { opacity: 0, y: 40 });

    // Subtle BG parallax zoom
    if (bgImg) {
      gsap.fromTo(bgImg,
        { scale: 1.1 },
        {
          scale: 1,
          ease: 'none',
          scrollTrigger: {
            trigger: section,
            start: 'top bottom',
            end: 'bottom top',
            scrub: 1,
          },
        }
      );
    }

    // Entrance animation
    ScrollTrigger.create({
      trigger: section,
      start: 'top 80%',
      once: true,
      onEnter: () => {
        gsap.to(els, {
          opacity: 1,
          y: 0,
          duration: 0.8,
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
            els.forEach((el, i) => {
              setTimeout(() => {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
                el.style.transition = 'opacity 0.7s ease, transform 0.7s ease';
              }, i * 120);
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

/* ============================================= */
/* SECTION 8.5 — Partner Logos Bar (Swiper)      */
/* ============================================= */
(function () {
  'use strict';

  const partnersSection = document.getElementById('partners');
  if (!partnersSection) return;

  /* ── Swiper: Continuous Ribbon ── */
  if (typeof Swiper !== 'undefined') {
    new Swiper('.partners-swiper', {
      loop: true,
      speed: 3000,
      autoplay: {
        delay: 0,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
      },
      slidesPerView: 2,
      spaceBetween: 24,
      freeMode: true,
      freeModeMomentum: false,
      allowTouchMove: true,
      breakpoints: {
        640: {
          slidesPerView: 3,
          spaceBetween: 32,
        },
        768: {
          slidesPerView: 4,
          spaceBetween: 40,
        },
        1024: {
          slidesPerView: 5,
          spaceBetween: 48,
        },
      },
    });
  }

  /* ── GSAP Scroll entrance animation ── */
  if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
    const partnerEls = partnersSection.querySelectorAll('.partners-el');

    gsap.set(partnerEls, { opacity: 0, y: 24 });

    ScrollTrigger.create({
      trigger: partnersSection,
      start: 'top 85%',
      once: true,
      onEnter: () => {
        gsap.to(partnerEls, {
          opacity: 1,
          y: 0,
          duration: 0.7,
          ease: 'power3.out',
          stagger: 0.15,
        });
      },
    });
  } else {
    // Fallback: IntersectionObserver
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            partnersSection.querySelectorAll('.partners-el').forEach((el, i) => {
              setTimeout(() => {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
                el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
              }, i * 150);
            });
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.15 }
    );
    observer.observe(partnersSection);
  }
})();

/* ============================================= */
/* SECTION 9 — Blog Swiper + Scroll Animation    */
/* ============================================= */
(function () {
  'use strict';

  const section = document.getElementById('blog');
  if (!section) return;

  /* ── Swiper: 3-card slider ── */
  if (typeof Swiper !== 'undefined') {
    const blogSwiper = new Swiper('#blog-swiper', {
      slidesPerView: 1,
      spaceBetween: 20,
      loop: true,
      pagination: {
        el: '.blog-pagination',
        clickable: true,
      },
      breakpoints: {
        640: {
          slidesPerView: 2,
          spaceBetween: 24,
        },
        1024: {
          slidesPerView: 3,
          spaceBetween: 28,
        },
      },
    });

    /* Manual prev/next handlers (buttons now inside .blog-slider-wrap) */
    const sliderWrap = section.querySelector('.blog-slider-wrap');
    const prevBtn = sliderWrap ? sliderWrap.querySelector('.blog-nav__prev') : null;
    const nextBtn = sliderWrap ? sliderWrap.querySelector('.blog-nav__next') : null;
    if (prevBtn) prevBtn.addEventListener('click', () => blogSwiper.slidePrev());
    if (nextBtn) nextBtn.addEventListener('click', () => blogSwiper.slideNext());

    /* Mobile bottom nav buttons (inside .blog-nav) */
    const mobilePrev = section.querySelector('.blog-nav .blog-nav__prev');
    const mobileNext = section.querySelector('.blog-nav .blog-nav__next');
    if (mobilePrev) mobilePrev.addEventListener('click', () => blogSwiper.slidePrev());
    if (mobileNext) mobileNext.addEventListener('click', () => blogSwiper.slideNext());

    /* ── Dynamic vertical centering of side arrows at image midpoint ── */
    function updateBlogImgCenter() {
      if (!sliderWrap) return;
      const firstImg = sliderWrap.querySelector('.blog-card__img');
      if (firstImg && firstImg.offsetHeight > 0) {
        sliderWrap.style.setProperty('--blog-img-center', (firstImg.offsetHeight / 2) + 'px');
      }
    }
    // Run after images load and on resize
    window.addEventListener('load', updateBlogImgCenter);
    window.addEventListener('resize', updateBlogImgCenter);
    // Also run immediately in case images are already cached
    updateBlogImgCenter();
  }

  /* ── GSAP Scroll entrance animation ── */
  if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
    const blogEls = section.querySelectorAll('.blog-el');
    const isDesktop = window.innerWidth >= 1024;

    gsap.set(blogEls, { opacity: 0, y: 40 });

    // Desktop: also set initial states for the first 3 visible cards
    const visibleSlides = section.querySelectorAll('.blog-swiper .swiper-slide:not(.swiper-slide-duplicate)');
    const visibleCards  = Array.from(visibleSlides).slice(0, 3);
    if (isDesktop && visibleCards.length) {
      visibleCards.forEach((slide) => {
        const imgLink = slide.querySelector('.blog-card__img-link');
        const img     = slide.querySelector('.blog-card__img');
        const body    = slide.querySelector('.blog-card__body');
        if (imgLink) gsap.set(imgLink, { clipPath: 'inset(100% 0 0 0)' });
        if (img)     gsap.set(img,     { scale: 1.1 });
        if (body)    gsap.set(body,    { opacity: 0, y: 20 });
      });
    }

    ScrollTrigger.create({
      trigger: section,
      start: 'top 80%',
      once: true,
      onEnter: () => {
        const tl = gsap.timeline();

        // 1) Section header elements stagger up
        tl.to(blogEls, {
          opacity: 1,
          y: 0,
          duration: 0.8,
          ease: 'power3.out',
          stagger: 0.15,
        });

        // 2) Desktop: per-card image reveal cascade
        if (isDesktop && visibleCards.length) {
          visibleCards.forEach((slide, i) => {
            const imgLink = slide.querySelector('.blog-card__img-link');
            const img     = slide.querySelector('.blog-card__img');
            const body    = slide.querySelector('.blog-card__body');
            const offset  = i === 0 ? '-=0.4' : `<+0.12`;

            if (imgLink) {
              tl.to(imgLink,
                { clipPath: 'inset(0% 0 0 0)', duration: 0.75, ease: 'power3.out' },
                offset
              );
            }
            if (img) {
              tl.to(img,
                { scale: 1, duration: 0.75, ease: 'power2.out' },
                '<'
              );
            }
            if (body) {
              tl.to(body,
                { opacity: 1, y: 0, duration: 0.55, ease: 'power2.out' },
                '<+0.25'
              );
            }
          });
        }

        /* Stagger-in the pagination bullets after main reveal (desktop only) */
        if (window.innerWidth >= 640) {
          const blogPagination = section.querySelector('.blog-pagination');
          if (blogPagination) {
            const bullets = blogPagination.querySelectorAll('.swiper-pagination-bullet');
            gsap.fromTo(bullets,
              { opacity: 0, scaleX: 0, transformOrigin: 'left center' },
              { opacity: 1, scaleX: 1, duration: 0.4, ease: 'power2.out', stagger: 0.06, delay: 0.5 }
            );
          }
        }
      },
    });
  } else {
    // Fallback: IntersectionObserver
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            section.querySelectorAll('.blog-el').forEach((el, i) => {
              setTimeout(() => {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
                el.style.transition = 'opacity 0.7s ease, transform 0.7s ease';
              }, i * 150);
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

/* ============================================= */
/* SIDE ARROWS — Hover with delay (PC only)      */
/* ============================================= */
(function () {
  'use strict';

  // Only on devices with a mouse
  if (!window.matchMedia('(hover: hover)').matches) return;

  /**
   * Sets up hover-reveal with a delay timer so buttons
   * don't vanish while the cursor travels across the gap.
   */
  function setupHoverReveal(wrapperSelector, btnSelector) {
    const wrapper = document.querySelector(wrapperSelector);
    if (!wrapper) return;

    const buttons = wrapper.querySelectorAll(btnSelector);
    if (!buttons.length) return;

    let hideTimer = null;
    const DELAY = 400; // ms grace period

    function showButtons() {
      clearTimeout(hideTimer);
      buttons.forEach(function (btn) { btn.classList.add('is-visible'); });
    }

    function scheduleHide() {
      hideTimer = setTimeout(function () {
        buttons.forEach(function (btn) { btn.classList.remove('is-visible'); });
      }, DELAY);
    }

    // Wrapper enter/leave
    wrapper.addEventListener('mouseenter', showButtons);
    wrapper.addEventListener('mouseleave', scheduleHide);

    // Buttons themselves: cancel hide when cursor reaches them
    buttons.forEach(function (btn) {
      btn.addEventListener('mouseenter', showButtons);
      btn.addEventListener('mouseleave', scheduleHide);
    });
  }

  // Blog section
  setupHoverReveal('.blog-slider-wrap', '.blog-slider-wrap>.blog-nav__btn');

  // Projects thumbs section
  setupHoverReveal('.projects-thumbs-wrapper', '.projects-thumbs-wrapper>.thumbs-nav__btn');

  /* ── Dynamic vertical center for thumbs side arrows (image area only) ── */
  (function () {
    const thumbsWrap = document.querySelector('.projects-thumbs-wrapper');
    if (!thumbsWrap) return;

    function updateThumbsImgCenter() {
      const imgWrap = thumbsWrap.querySelector('.project-thumb__img-wrap');
      if (imgWrap && imgWrap.offsetHeight > 0) {
        thumbsWrap.style.setProperty('--thumbs-img-center', (imgWrap.offsetHeight / 2) + 'px');
      }
    }

    window.addEventListener('load', updateThumbsImgCenter);
    window.addEventListener('resize', updateThumbsImgCenter);
    updateThumbsImgCenter();
  })();
})();
