<?php
function isActive($page) {
    // DEBUG (à enlever après)
  $current = basename($_SERVER['PHP_SELF']); // ex: donneur.php
    // DEBUG (à enlever après)
  return $current === $page ? 'active' : '';
  
}

function isActiveGroup($pages) {
  $current = basename($_SERVER['PHP_SELF']);
  return in_array($current, $pages) ? 'active' : '';
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
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
<link rel="icon" type="image/png" href="../media/logo_noir.png">
</head>
<audio id="notifSound" preload="auto">
    <source src="../media/sounds/notification.mp3" type="audio/mpeg">
</audio>
<body>

<header class="main-header"> 
  <img src="../media/logo_blanc.png" width="50" alt="Logo"> 
  
  <!-- Burger (mobile) -->
  <button class="burger" type="button" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="mainNav">
    <span></span><span></span><span></span>
  </button>
<ul class="nav-links" id="mainNav"> 
  <li><a class="<?= isActive('donneur.php') ?>" href="donneur.php">Accueil</a></li> 

  <li class="dropdown">
    <a href="#" class="dropdown-toggle <?= isActiveGroup(['pourquoi-donner.html','centres.php']) ?>">Pourquoi</a>
    <div class="dropdown-content">
      <a class="<?= isActive('pourquoi-donner.html') ?>" href="pourquoi-donner.html">Pourquoi donner ?</a>
      <a class="<?= isActive('centres.php') ?>" href="centres.php">Où donner ?</a>
    </div>
  </li> 

  <li><a class="<?= isActive('campagnes-evenements.php') ?>" href="campagnes-evenements.php">Campagnes</a></li>
  <li><a class="<?= isActive('temoignages.php') ?>" href="temoignages.php">Témoignages</a></li>
  <li><a class="<?= isActive('appelurgent.php') ?>" href="appelurgent.php">Appel urgent</a></li>
  <li><a class="<?= isActive('contact.php') ?>" href="contact.php">Contact</a></li>
  <li><a class="<?= isActive('a-propos.php') ?>" href="a-propos.php">À propos</a></li>

  <!-- Profil -->
  <li class="dropdown"> 
    <a href="#" class="dropdown-toggle">
      <i class="uil uil-user-circle"></i> Profil
    </a> 
    <div class="dropdown-content"> 
      <div class="status">
        <a class="<?= isActive('profil.php') ?>" href="profil.php"> <i class="uil uil-heart"></i> Donneur </a>
      </div> 

      <a class="<?= isActive('notifications.php') ?>" href="notifications.php">
        <i class="uil uil-bell"></i>
        Mes notifications 
      </a>

      <a href="http://localhost/100vies/administrateur/php/inscription.php">
        <i class="uil uil-plus"></i> Ajouter un compte
      </a> 
      <a href="http://localhost/100vies/receveur/php/receveur.php">
        <i class="uil uil-exchange"></i> Modifier statut
      </a> 
      <a href="http://localhost/100vies/administrateur/php/form.php">
        <i class="uil uil-signout"></i> Déconnexion
      </a>  
    </div> 
  </li> 
</ul>

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
    <button onclick="window.location.href='http://localhost/100vies/administrateur/php/inscription.php'">
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

<script src="../js/responsive.js"></script>

</body>
</html>
