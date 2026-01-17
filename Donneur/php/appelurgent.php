<?php
include("connexion.php");

/* Requête SQL */
$sql = "SELECT * FROM appelurgent ORDER BY urgence DESC, date_appel DESC";
$result = mysqli_query($conn, $sql);

/* Sécurité : vérifier la requête */
if (!$result) {
    die("Erreur SQL : " . mysqli_error($conn));
}

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

<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>appel urgent </title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
<link rel="stylesheet" href="../css/appelurgent.css">
<link rel="stylesheet" href="../css/chatbot.css">
<link rel="icon" type="image/png" href="../media/logo_noir.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<audio id="notifSound" preload="auto">
    <source src="../media/sounds/notification.mp3" type="audio/mpeg">
</audio>

<body>

<!-- ================= NAVBAR ================= -->
<header class="main-header">
  <img src="../media/logo_noir.png" width="50" alt="Logo">

  <!-- ✅ Bouton toggler (visible uniquement en mobile via CSS) -->
  <button class="burger" type="button"
          aria-label="Ouvrir le menu"
          aria-expanded="false"
          aria-controls="mainNav">
    <span></span><span></span><span></span>
  </button>

  <!-- ✅ IMPORTANT: id="mainNav" pour le toggle -->
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

      <a href="http://localhost/sensibilisation-au-don-de-sang/administrateur/php/inscription.php">
        <i class="uil uil-plus"></i> Ajouter un compte
      </a> 
      <a href="http://localhost/sensibilisation-au-don-de-sang/receveur/php/receveur.php">
        <i class="uil uil-exchange"></i> Modifier statut
      </a> 
      <a href="http://localhost/sensibilisation-au-don-de-sang/administrateur/php/form.php">
        <i class="uil uil-signout"></i> Déconnexion
      </a>  
    </div> 
  </li> 
</ul>

</header>

<!-- ================= APPELS URGENTS ================= -->
<section class="urgent-section">

  <div class="urgent-title">
    <i class="uil uil-heartbeat"></i>
    Appels urgents au don
  </div>

  <?php if(mysqli_num_rows($result) === 0): ?>
    <div class="empty">Aucun appel urgent pour le moment.</div>
  <?php endif; ?>

  <?php while($appel = mysqli_fetch_assoc($result)): ?>
    <div class="urgent-card" onclick="toggleCard(this)">
      <div class="urgent-header">
        <strong><?= htmlspecialchars($appel['type_appel']) ?></strong>
        <?php if($appel['urgence'] == 1): ?>
          <div class="pulse"></div>
        <?php endif; ?>
      </div>

      <div class="urgent-meta">
        <i class="uil uil-calendar-alt"></i>
        <?= date("d/m/Y", strtotime($appel['date_appel'])) ?>
      </div>

      <div class="urgent-desc">
        <p><?= nl2br(htmlspecialchars($appel['description_appel'])) ?></p>
       <a href="eligibilite.php" class="btn-help-link">
  <button class="btn-help" type="button">
    <i class="uil uil-heart"></i> Je veux aider
  </button>
</a>
      </div>
    </div>
  <?php endwhile; ?>

</section>

<!-- ================= FOOTER (INCHANGÉ) ================= -->
<footer class="main-footer">
  <div class="container py-4">
    <div class="row">
      <div class="col-md-4">
        <h5>À propos</h5>
        <p>100Vies est une plateforme pour localiser rapidement les centres de don.</p>
      </div>
      <div class="col-md-4">
        <h5>Liens rapides</h5>
        <ul class="footer-links">
          <li><a href="index1.html">Accueil</a></li>
          <li><a href="campagnes-evenements.php">Campagnes</a></li>
          <li><a href="temoignages.php">Témoignages</a></li>
          <li><a href="contact.php">Contact</a></li>
        </ul>
      </div>
      <div class="col-md-4">
        <h5>Contact</h5>
        <p>✉️ contact@100vies.tn</p>
        <p>📞 +216 71 234 567</p>
      </div>
    </div>
  </div>
</footer>

<script src="../js/appelurgent.js"></script>
<script src="../js/chatbot.js"></script>
<span id="notif-data" data-count="<?= $nbNotif ?>"></span>

<script src="../js/notifications.js"></script>

<script src="../js/responsive.js"></script>
</body>
</html>
