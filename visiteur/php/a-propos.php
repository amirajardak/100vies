<?php
session_start();
include("connexion.php");

function isActive($page) {
  $current = basename($_SERVER['PHP_SELF']);  // ex: index1.php
  return ($current === $page) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>À propos - 100Vies</title>
  <!-- Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../css/a-propos.css">
<link rel="stylesheet" href="../css/chatbot.css">
<link rel="stylesheet" href="../css/navbar.css">
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
<link rel="icon" type="image/png" href="../media/logo_noir.png">
</head>
<audio id="notifSound" preload="auto">
    <source src="../media/sounds/notification.mp3" type="audio/mpeg">
</audio>
<body>

 <!-- HEADER + NAVIGATION -->
    <header>
        <div class="logo">
            <img id="logo" src="../media/logo_blanc.png" alt="100Vies Logo" width="50px">
        </div>
        <nav>
            <ul id="mainNav" class="mainNav">
  <li><a class="<?= isActive('index1.php') ?>" href="index1.php">Accueil</a></li>
  <li><a class="<?= isActive('campagnes-evenements.php') ?>" href="campagnes-evenements.php">Campagnes</a></li>
  <li><a class="<?= isActive('temoignages.php') ?>" href="temoignages.php">Témoignages</a></li>
  <li><a class="<?= isActive('a-propos.php') ?>" href="a-propos.php">À propos</a></li>
  <li><a class="<?= isActive('contact.php') ?>" href="contact.php">Contact</a></li>
</ul>
        </nav>
        <div class="nav-actions">
  <a href="../../administrateur/php/inscription.php" class="btn-connexion1">Créer compte</a>
  <a href="../../administrateur/php/form.php" class="btn-connexion">Connexion</a>
</div>
</header>
<div class="container">

  <!-- Hero -->
  <section class="hero">
    <h1>À propos de 100Vies</h1>
    <p>100Vies est une plateforme dédiée aux donneurs et receveurs pour faciliter les dons de sang et la réception rapide des dons, tout en sensibilisant la population à l’importance de ce geste vital.</p>
  </section>

  <!-- Mission Section -->
  <section class="section">
    <div class="icon"><i class="uil uil-heart"></i></div>
    <div class="content">
      <h2>Notre mission</h2>
      <p>Faciliter les dons et les réceptions de sang en mettant en relation les donneurs et les receveurs, tout en sensibilisant le public à l’importance du don. Notre objectif est de sauver le plus de vies possible grâce à une plateforme simple, rapide et sécurisée.</p>
    </div>
  </section>

  <!-- Sensibilisation -->
  <section class="section">
    <div class="icon"><i class="uil uil-bullseye"></i></div>
    <div class="content">
      <h2>Sensibilisation</h2>
      <p>Informer et éduquer la population sur l’importance du don du sang, les critères de donneurs, et comment participer aux campagnes locales.</p>
    </div>
  </section>

  <!-- Importance du don -->
  <section class="section">
    <div class="icon"><i class="uil uil-bolt-alt"></i></div>
    <div class="content">
      <h2>Pourquoi le don est vital</h2>
      <p>Chaque don peut sauver jusqu'à trois vies et soutenir des patients en urgence ou en traitement médical.</p>
    </div>
  </section>

  <!-- Fonctionnalités -->
  <section class="section">
    <div class="icon"><i class="uil uil-map-pin-alt"></i></div>
    <div class="content">
      <h2>Fonctionnalités</h2>
      <ul>
        <li>Localiser rapidement les centres de don</li>
        <li>Suivre les campagnes et événements</li>
        <li>Informations et conseils pratiques sur le don</li>
      </ul>
    </div>
  </section>

  <!-- CTA -->
  <div class="cta">
    <button onclick="window.location.href='http://localhost/sensibilisation-au-don-de-sang/administrateur/php/inscription.php'">
    Rejoignez-nous et sauvez des vies
  </button>
</div>
</div>


<footer class="main-footer">
  <div class="container py-4">
    <div class="row">
      <div class="col-md-4 mb-3">
        <h5>À propos</h5>
        <p>100Vies est une plateforme pour localiser rapidement les centres de don et contribuer à sauver des vies.</p>
      </div>
      <div class="col-md-4 mb-3">
        <h5>Liens rapides</h5>
        <ul>
          <li><a href="index1.html">Accueil</a></li>
          <li><a href="campagnes-evenements.php">Campagnes</a></li>
          <li><a href="temoignages.php">Témoignages</a></li>
          <li><a href="contact.php">Contact</a></li>
        </ul>
      </div>
      <div class="col-md-4 mb-3">
        <h5>Contact</h5>
        <p>✉️ contact@100vies.tn</p>
        <p>📞 +216 71 234 567</p>
        <div class="social-icons">
          <a href="#"><i class="uil uil-facebook-f"></i></a>
          <a href="#"><i class="uil uil-instagram"></i></a>
          <a href="#"><i class="uil uil-twitter"></i></a>
        </div>
      </div>
    </div>
    <hr>
    <p class="text-center small mb-0">&copy; 2026 100Vies. Tous droits réservés.</p>
  </div>
</footer>
<script src="../js/a-propos.js"></script>
<script src="../js/chatbot.js"></script>
<span id="notif-data" data-count="<?= $nbNotif ?>"></span>

<script src="../js/notifications.js"></script>

<script>
(function () {
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
})();
</script>

</body>
</html>
