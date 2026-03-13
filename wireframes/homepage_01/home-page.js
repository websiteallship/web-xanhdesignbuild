/* ============================================
   XANH Design & Build — Home Page Demo JS
   Features: Preloader, Scroll Nav, Reveal,
   Counter, Testimonials, Mobile Nav, Back to Top
   ============================================ */

document.addEventListener('DOMContentLoaded', () => {

  // === PRELOADER ===
  const preloader = document.getElementById('preloader');
  if (preloader) {
    const alreadyLoaded = sessionStorage.getItem('xanh_preloaded');
    if (alreadyLoaded) {
      preloader.classList.add('hidden');
    } else {
      setTimeout(() => {
        preloader.classList.add('hidden');
        sessionStorage.setItem('xanh_preloaded', '1');
      }, 1800);
    }
  }

  // === HERO ENTRANCE ANIMATION ===
  setTimeout(() => {
    const heroElements = ['.hero__subtitle', '.hero__title', '.hero__desc', '.hero__actions'];
    heroElements.forEach((sel, i) => {
      const el = document.querySelector(sel);
      if (el) {
        setTimeout(() => {
          el.style.transition = `opacity 0.8s cubic-bezier(.2,0,0,1), transform 0.8s cubic-bezier(.2,0,0,1)`;
          el.style.opacity = '1';
          el.style.transform = 'translateY(0)';
        }, i * 250);
      }
    });
  }, 2000);

  // === SCROLL → NAV STYLE ===
  const nav = document.getElementById('nav');
  const backToTop = document.getElementById('backToTop');

  function handleScroll() {
    const scrollY = window.scrollY;
    
    // Nav background
    if (nav) {
      if (scrollY > 80) {
        nav.classList.add('scrolled');
      } else {
        nav.classList.remove('scrolled');
      }
    }

    // Back to top button
    if (backToTop) {
      if (scrollY > 500) {
        backToTop.classList.add('show');
      } else {
        backToTop.classList.remove('show');
      }
    }
  }

  window.addEventListener('scroll', handleScroll, { passive: true });
  handleScroll();

  // Back to top click
  if (backToTop) {
    backToTop.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // === MOBILE NAV ===
  const hamburger = document.getElementById('hamburger');
  const mobileNav = document.getElementById('mobileNav');
  const mobileNavClose = document.getElementById('mobileNavClose');
  const navOverlay = document.getElementById('navOverlay');

  function openMobileNav() {
    mobileNav.classList.add('open');
    navOverlay.classList.add('show');
    document.body.style.overflow = 'hidden';
  }
  function closeMobileNav() {
    mobileNav.classList.remove('open');
    navOverlay.classList.remove('show');
    document.body.style.overflow = '';
  }

  if (hamburger) hamburger.addEventListener('click', openMobileNav);
  if (mobileNavClose) mobileNavClose.addEventListener('click', closeMobileNav);
  if (navOverlay) navOverlay.addEventListener('click', closeMobileNav);

  // Close mobile nav on link click
  document.querySelectorAll('.mobile-nav__link').forEach(link => {
    link.addEventListener('click', closeMobileNav);
  });

  // === SCROLL REVEAL (IntersectionObserver) ===
  const revealElements = document.querySelectorAll('.reveal');
  
  const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        revealObserver.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.15,
    rootMargin: '0px 0px -50px 0px'
  });

  revealElements.forEach(el => revealObserver.observe(el));

  // === ANIMATED COUNTER ===
  const counterElements = document.querySelectorAll('.counter__number');
  let counterAnimated = false;

  const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting && !counterAnimated) {
        counterAnimated = true;
        animateCounters();
        counterObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.5 });

  const counterSection = document.getElementById('counter');
  if (counterSection) counterObserver.observe(counterSection);

  function animateCounters() {
    counterElements.forEach(el => {
      const target = parseInt(el.dataset.target);
      const suffix = el.dataset.suffix || '';
      const duration = 2000;
      const startTime = performance.now();

      function updateCounter(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        // easeOutExpo
        const eased = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
        const current = Math.floor(eased * target);
        
        el.textContent = current + suffix;

        if (progress < 1) {
          requestAnimationFrame(updateCounter);
        } else {
          el.textContent = target + suffix;
        }
      }

      requestAnimationFrame(updateCounter);
    });
  }

  // === PROCESS STEPPER ANIMATION ===
  const processSteps = document.querySelectorAll('.process-step');
  
  const processObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        // Activate steps sequentially
        const step = entry.target;
        const stepIndex = parseInt(step.dataset.step);
        
        setTimeout(() => {
          step.classList.add('active');
        }, stepIndex * 200);

        processObserver.unobserve(step);
      }
    });
  }, { threshold: 0.3 });

  processSteps.forEach(step => processObserver.observe(step));

  // === TESTIMONIAL SLIDER ===
  const slides = document.querySelectorAll('.testimonial-slide');
  const dots = document.querySelectorAll('.testimonial-dot');
  let currentSlide = 0;
  let testimonialInterval;

  function showSlide(index) {
    slides.forEach(s => s.classList.remove('active'));
    dots.forEach(d => d.classList.remove('active'));
    
    currentSlide = index;
    if (slides[currentSlide]) slides[currentSlide].classList.add('active');
    if (dots[currentSlide]) dots[currentSlide].classList.add('active');
  }

  function nextSlide() {
    showSlide((currentSlide + 1) % slides.length);
  }

  // Dot click
  dots.forEach(dot => {
    dot.addEventListener('click', () => {
      const slideIndex = parseInt(dot.dataset.slide);
      showSlide(slideIndex);
      resetAutoplay();
    });
  });

  // Autoplay
  function startAutoplay() {
    testimonialInterval = setInterval(nextSlide, 5000);
  }
  function resetAutoplay() {
    clearInterval(testimonialInterval);
    startAutoplay();
  }

  if (slides.length > 0) startAutoplay();

  // === SMOOTH SCROLL FOR NAV LINKS ===
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      const targetId = this.getAttribute('href');
      if (targetId === '#') return;
      
      const targetEl = document.querySelector(targetId);
      if (targetEl) {
        e.preventDefault();
        const offsetTop = targetEl.offsetTop - 80; // Nav height offset
        window.scrollTo({ top: offsetTop, behavior: 'smooth' });
      }
    });
  });

  // === ACTIVE NAV LINK ON SCROLL ===
  const sections = document.querySelectorAll('section[id]');
  
  function updateActiveLink() {
    const scrollY = window.scrollY + 120;
    
    sections.forEach(section => {
      const top = section.offsetTop;
      const height = section.offsetHeight;
      const id = section.getAttribute('id');
      
      if (scrollY >= top && scrollY < top + height) {
        document.querySelectorAll('.nav__link').forEach(link => {
          link.classList.remove('active');
          if (link.getAttribute('href') === `#${id}`) {
            link.classList.add('active');
          }
        });
      }
    });
  }

  window.addEventListener('scroll', updateActiveLink, { passive: true });

});
