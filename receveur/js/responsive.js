(function () {
  const burger = document.querySelector('.burger');
  const nav = document.getElementById('mainNav');
  if (!burger || !nav) return; // ✅ ici return est OK (dans une fonction)

  const mqMobile = window.matchMedia('(max-width: 576px)');

  function closeAllDropdowns() {
    document.querySelectorAll('.nav-links .dropdown.open')
      .forEach(li => li.classList.remove('open'));
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

  // Dropdown: clic uniquement en mobile
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

  // Fermer après clic sur un lien en mobile
  nav.querySelectorAll('a').forEach(a => {
    a.addEventListener('click', () => {
      if (!mqMobile.matches) return;
      if (a.classList.contains('dropdown-toggle')) return;
      closeMenu();
    });
  });

  // En repassant en desktop: reset
  window.addEventListener('resize', () => {
    if (!mqMobile.matches) closeMenu();
  });
})();
