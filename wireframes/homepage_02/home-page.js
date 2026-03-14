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

