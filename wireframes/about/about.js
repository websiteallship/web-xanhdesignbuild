/**
 * XANH - Design & Build
 * About Page Wireframe: Section 1 Hero Banner
 * =============================================
 * Libraries: GSAP, ScrollTrigger, Lenis, Lucide
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

    // Force hamburger icon white when drawer is open
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

    // Restore hamburger color based on scroll
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

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !mobileDrawer.classList.contains('translate-x-full')) {
      closeDrawer();
    }
  });

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

  // ── Header Scroll Behavior ──
  const header = document.getElementById('site-header');

  function handleHeaderScroll() {
    const scrollY = window.scrollY || window.pageYOffset;
    const menuBtn = document.getElementById('mobile-menu-btn');

    if (scrollY > 80) {
      header.classList.add('is-scrolled');
      if (menuBtn && !isDrawerOpen) {
        menuBtn.querySelectorAll('.hamburger-line').forEach(l => {
          l.classList.remove('bg-white');
          l.classList.add('bg-dark');
        });
      }
    } else {
      header.classList.remove('is-scrolled');
      if (menuBtn && !isDrawerOpen) {
        menuBtn.querySelectorAll('.hamburger-line').forEach(l => {
          l.classList.remove('bg-dark');
          l.classList.add('bg-white');
        });
      }
    }
  }

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

  // ── Hero Content Reveal Animation ──
  function revealHeroContent() {
    const heroBg = document.querySelector('.about-hero__bg');
    const heroEls = document.querySelectorAll('.about-hero-el');

    // Trigger background zoom
    if (heroBg) {
      heroBg.classList.add('is-loaded');
    }

    // Reveal text elements (CSS transition handles stagger)
    heroEls.forEach(el => {
      el.classList.add('is-visible');
    });
  }

  // Reveal hero after short delay
  setTimeout(revealHeroContent, 300);

  // ── GSAP Parallax on Hero Background ──
  if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
    gsap.registerPlugin(ScrollTrigger);

    const heroBgImg = document.querySelector('.about-hero__bg img');
    if (heroBgImg) {
      gsap.fromTo(heroBgImg,
        { scale: 1.06 },
        {
          scale: 1,
          ease: 'none',
          scrollTrigger: {
            trigger: '#about-hero',
            start: 'top top',
            end: 'bottom top',
            scrub: 1,
          },
        }
      );
    }
  }

  // ── Video Modal ──
  const videoPlayBtn = document.getElementById('video-play-btn');
  const videoModal = document.getElementById('video-modal');
  const videoModalBackdrop = document.getElementById('video-modal-backdrop');
  const videoModalClose = document.getElementById('video-modal-close');
  const videoIframe = document.getElementById('video-iframe');

  // Placeholder video URL — replace with actual company video
  const VIDEO_URL = 'https://www.youtube.com/embed/dQw4w9WgXcQ?autoplay=1&rel=0&modestbranding=1';

  function openVideoModal() {
    videoIframe.src = VIDEO_URL;
    videoModal.classList.add('is-open');
    document.body.style.overflow = 'hidden';

    // Pause Lenis while modal is open
    if (lenis) lenis.stop();
  }

  function closeVideoModal() {
    videoModal.classList.remove('is-open');
    document.body.style.overflow = '';

    // Stop video by clearing iframe src
    setTimeout(() => {
      videoIframe.src = '';
    }, 400); // Wait for fade-out transition

    // Resume Lenis
    if (lenis) lenis.start();
  }

  if (videoPlayBtn) {
    videoPlayBtn.addEventListener('click', openVideoModal);
  }

  if (videoModalClose) {
    videoModalClose.addEventListener('click', closeVideoModal);
  }

  if (videoModalBackdrop) {
    videoModalBackdrop.addEventListener('click', closeVideoModal);
  }

  // Close modal on Escape
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && videoModal.classList.contains('is-open')) {
      closeVideoModal();
    }
  });

  // ── Unified Entrance Animations ──
  // Based on rules/08-cross-section-consistency.md
  if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
    gsap.utils.toArray('.anim-fade-up').forEach(el => {
      // Skip sections with dedicated animations below
      if (el.closest('#about-pain') || el.closest('#about-promise')) return;
      gsap.from(el, {
        scrollTrigger: { trigger: el, start: 'top 85%' },
        opacity: 0,
        y: 40,
        duration: 0.8,
        ease: 'power2.out'
      });
    });

    // ── Section 2: The Pain — Dedicated Entrance Animations ──
    const painSection = document.getElementById('about-pain');
    if (painSection) {
      // Header: slide in from the left
      const painHeader = painSection.querySelector('.about-pain-header');
      if (painHeader) {
        gsap.from(painHeader, {
          scrollTrigger: { trigger: painHeader, start: 'top 85%' },
          opacity: 0,
          x: -60,
          duration: 1,
          ease: 'power3.out'
        });
      }

      // Vertical divider line: grow from top
      const painDivider = painSection.querySelector('.pain-divider-line');
      if (painDivider) {
        gsap.from(painDivider, {
          scrollTrigger: { trigger: painDivider, start: 'top 80%' },
          scaleY: 0,
          transformOrigin: 'top center',
          duration: 1.2,
          ease: 'power2.inOut'
        });
      }

      // Pain items: staggered entrance
      const painItems = gsap.utils.toArray('.pain-el');
      if (painItems.length) {
        painItems.forEach((item, i) => {
          const iconCircle = item.querySelector('.icon-circle');
          const textContent = item.querySelector('div:last-child'); // h3 + p wrapper

          // Card fade-up with stagger
          gsap.from(item, {
            scrollTrigger: { trigger: item, start: 'top 88%' },
            opacity: 0,
            y: 50,
            duration: 0.8,
            delay: i * 0.08,
            ease: 'power2.out'
          });

          // Icon circle: scale-bounce entrance
          if (iconCircle) {
            gsap.from(iconCircle, {
              scrollTrigger: { trigger: item, start: 'top 88%' },
              scale: 0,
              opacity: 0,
              duration: 0.6,
              delay: 0.2 + i * 0.08,
              ease: 'back.out(1.7)'
            });
          }

          // Text content: slight slide from right
          if (textContent) {
            gsap.from(textContent, {
              scrollTrigger: { trigger: item, start: 'top 88%' },
              opacity: 0,
              x: 30,
              duration: 0.7,
              delay: 0.15 + i * 0.08,
              ease: 'power2.out'
            });
          }
        });
      }
    }

    // ── Section 4: The Promise — Dedicated Entrance Animations ──
    const promiseSection = document.getElementById('about-promise');
    if (promiseSection) {
      // Left column: eyebrow + title
      const promiseEyebrow = promiseSection.querySelector('.section-eyebrow');
      const promiseTitle = promiseSection.querySelector('.section-title');

      if (promiseEyebrow) {
        gsap.from(promiseEyebrow, {
          scrollTrigger: { trigger: promiseSection, start: 'top 75%' },
          opacity: 0,
          y: 20,
          duration: 0.7,
          ease: 'power2.out'
        });
      }

      if (promiseTitle) {
        gsap.from(promiseTitle, {
          scrollTrigger: { trigger: promiseSection, start: 'top 75%' },
          opacity: 0,
          y: 40,
          duration: 0.9,
          delay: 0.15,
          ease: 'power3.out'
        });
      }

      // Right column: text block
      const promiseTextBlock = promiseSection.querySelector('.promise-el.max-w-xl');
      if (promiseTextBlock) {
        gsap.from(promiseTextBlock, {
          scrollTrigger: { trigger: promiseTextBlock, start: 'top 85%' },
          opacity: 0,
          y: 40,
          duration: 0.8,
          ease: 'power2.out'
        });
      }

      // Highlight items: stagger with check-icon bounce
      const highlightItems = promiseSection.querySelectorAll('.grid > .flex');
      if (highlightItems.length) {
        highlightItems.forEach((item, i) => {
          const icon = item.querySelector('[data-lucide]');
          const text = item.querySelector('span');

          gsap.from(item, {
            scrollTrigger: { trigger: item, start: 'top 90%' },
            opacity: 0,
            x: -25,
            duration: 0.6,
            delay: i * 0.1,
            ease: 'power2.out'
          });

          if (icon) {
            gsap.from(icon, {
              scrollTrigger: { trigger: item, start: 'top 90%' },
              scale: 0,
              opacity: 0,
              duration: 0.5,
              delay: 0.15 + i * 0.1,
              ease: 'back.out(2)'
            });
          }
        });
      }

      // CTA button: fade up
      const promiseCTA = promiseSection.querySelector('.promise-el.mt-14');
      if (promiseCTA) {
        gsap.from(promiseCTA, {
          scrollTrigger: { trigger: promiseCTA, start: 'top 90%' },
          opacity: 0,
          y: 30,
          duration: 0.7,
          ease: 'power2.out'
        });
      }
    }

    // ── Section 3: Turning Point Animations (Custom SVG logic) ──

    // SVG circle draw animation (stroke-dashoffset → 0)
    const progressCircle = document.getElementById('turning-progress-circle');
    if (progressCircle) {
      gsap.to(progressCircle, {
        scrollTrigger: {
          trigger: '#about-turning',
          start: 'top 60%',
        },
        strokeDashoffset: 0,
        duration: 2.5,
        ease: 'power2.inOut'
      });
    }

    // Stagger reveal each SVG node
    gsap.utils.toArray('.turn-node').forEach((node, i) => {
      gsap.from(node, {
        scrollTrigger: {
          trigger: '#about-turning',
          start: 'top 60%',
        },
        scale: 0,
        opacity: 0,
        transformOrigin: 'center center',
        duration: 0.6,
        delay: 0.4 + i * 0.25, // stagger after circle starts drawing
        ease: 'back.out(1.7)'
      });
    });

    // Stagger reveal each SVG arrow connecting the nodes
    gsap.utils.toArray('.turn-arrow').forEach((arrow, i) => {
      gsap.to(arrow, {
        scrollTrigger: {
          trigger: '#about-turning',
          start: 'top 60%',
        },
        opacity: 1,
        duration: 0.8,
        delay: 0.8 + i * 0.25, // stagger so arrow appears slightly after preceding node
        ease: 'power2.out'
      });
    });
  }

  // ── Section 3: Node Hover → Center Overlay ──
  const centerOverlay = document.getElementById('turn-center-overlay');
  const detailTitle = document.getElementById('turn-detail-title');
  const detailDesc = document.getElementById('turn-detail-desc');

  // Node title mapping (by data-index)
  const nodeTitles = ['Thiết Kế', 'Dự Toán', 'Vật Liệu', 'Thi Công', 'Bảo Hành'];

  if (centerOverlay && detailTitle && detailDesc) {
    const nodes = document.querySelectorAll('.turn-node');

    nodes.forEach(node => {
      node.addEventListener('mouseenter', () => {
        const desc = node.getAttribute('data-desc');
        const idx = parseInt(node.getAttribute('data-index'), 10);
        if (!desc) return;

        detailTitle.textContent = nodeTitles[idx] || '';
        detailDesc.textContent = desc;

        // Activate hover state
        node.classList.add('is-active');
        centerOverlay.classList.add('is-hovering');
      });

      node.addEventListener('mouseleave', () => {
        node.classList.remove('is-active');
        centerOverlay.classList.remove('is-hovering');
      });
    });

    // ── Section 4.5: Team Members — Staggered Card Entrance ──
    const teamCards = gsap.utils.toArray('.team-card');
    if (teamCards.length) {
      gsap.from(teamCards, {
        scrollTrigger: {
          trigger: '#about-team',
          start: 'top 80%',
        },
        opacity: 0,
        y: 40,
        duration: 0.8,
        stagger: 0.15,
        ease: 'power2.out',
        clearProps: 'transform'
      });
    }

    // ── Section 5: Core Values — Staggered Card Entrance ──
    const cvCards = gsap.utils.toArray('.cv-card');
    if (cvCards.length) {
      // Main timeline for entire section
      const cvTl = gsap.timeline({
        scrollTrigger: {
          trigger: '#about-core-values',
          start: 'top 70%',
        }
      });

      // Cards: stagger reveal with scale + fade
      cvTl.from(cvCards, {
        opacity: 0,
        y: 60,
        scale: 0.92,
        duration: 0.7,
        stagger: 0.12,
        ease: 'power3.out',
        clearProps: 'transform'
      });

      // Internal elements: stagger within each card (after card appears)
      cvCards.forEach((card, i) => {
        const number = card.querySelector('.cv-card__number');
        const iconWrap = card.querySelector('.cv-card__icon-wrap');
        const title = card.querySelector('.cv-card__title');
        const desc = card.querySelector('.cv-card__desc');

        const innerTl = gsap.timeline({
          scrollTrigger: {
            trigger: card,
            start: 'top 80%',
          }
        });

        if (number) {
          innerTl.from(number, {
            opacity: 0,
            x: 20,
            duration: 0.5,
            ease: 'power2.out'
          }, 0.3);
        }

        if (iconWrap) {
          innerTl.from(iconWrap, {
            opacity: 0,
            scale: 0.7,
            duration: 0.5,
            ease: 'back.out(1.7)'
          }, 0.35);
        }

        if (title) {
          innerTl.from(title, {
            opacity: 0,
            y: 16,
            duration: 0.5,
            ease: 'power2.out'
          }, 0.45);
        }

        if (desc) {
          innerTl.from(desc, {
            opacity: 0,
            y: 12,
            duration: 0.5,
            ease: 'power2.out'
          }, 0.55);
        }
      });
    }
  }
});
