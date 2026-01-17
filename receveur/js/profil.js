const fileInput = document.getElementById('avatarInput');
const imgPreview = document.querySelector('.ui-w-80');
const resetBtn = document.getElementById('resetAvatar');
const defaultAvatar = imgPreview.src;

fileInput.addEventListener('change', function(){
  const file = this.files[0];
  if(file){
    const reader = new FileReader();
    reader.onload = e => imgPreview.src = e.target.result;
    reader.readAsDataURL(file);
  }
});
resetBtn.addEventListener('click', () => {
  imgPreview.src = defaultAvatar;
  fileInput.value = "";
});

// onglets
const tabs = document.querySelectorAll('.tab-link');
const contents = document.querySelectorAll('.tab-content');
tabs.forEach(tab => {
  tab.addEventListener('click', () => {
    tabs.forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    contents.forEach(c => c.style.display='none');
    document.getElementById(tab.dataset.tab).style.display='block';
  });
});
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

  // Dropdowns: clic sur mobile (desktop garde :hover)
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