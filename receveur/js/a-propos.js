// Animation scroll
  const sections = document.querySelectorAll('.hero, .section');
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if(entry.isIntersecting) entry.target.classList.add('visible');
    });
  }, {threshold: 0.2});
  sections.forEach(sec => observer.observe(sec));


  const burger = document.querySelector('.burger');
  const nav = document.getElementById('mainNav');
  if (!burger || !nav) return;

  const mqMobile = window.matchMedia('(max-width: 576px)');

  function closeAllDropdowns() {
    document.querySelectorAll('.nav-links .dropdown.open').forEach(li => li.classList.remove('open'));
  }

  function closeMenu() {
    nav.classList.remove('open');
    burger.classList.remove('open');
    burger.setAttribute('aria-expanded', 'false');
    closeAllDropdowns();
  }

  burger.addEventListener('click', () => {
    const isOpen = nav.classList.toggle('open');
    burger.classList.toggle('open', isOpen);
    burger.setAttribute('aria-expanded', String(isOpen));
    if (!isOpen) closeAllDropdowns();
  });

  // Dropdowns: click-to-open on mobile (desktop garde :hover)
  document.querySelectorAll('.nav-links .dropdown > a.dropdown-toggle').forEach(a => {
    a.addEventListener('click', (e) => {
      if (!mqMobile.matches) return;
      e.preventDefault();
      const li = a.closest('.dropdown');
      const willOpen = !li.classList.contains('open');
      closeAllDropdowns();
      li.classList.toggle('open', willOpen);
    });
  });

  // Fermer le menu après clic sur un lien (sauf toggles dropdown)
  nav.querySelectorAll('a').forEach(a => {
    a.addEventListener('click', () => {
      if (!mqMobile.matches) return;
      if (a.classList.contains('dropdown-toggle')) return;
      closeMenu();
    });
  });

  // En repassant en desktop, on reset
  window.addEventListener('resize', () => {
    if (!mqMobile.matches) closeMenu();
  });