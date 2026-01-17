<?php
session_start();
include("connexion.php");

// Vérifier si le receveur est connecté
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? null) !== 'receveur') {
    die("Receveur non connecté.");
}

$id_receveur = (int) $_SESSION['user_id'];

// Nombre de notifications non lues (utile pour l'UI/JS)
$sqlCount = "
    SELECT COUNT(*) AS total
    FROM notif
    WHERE id_user = $id_receveur
      AND role = 'receveur'
      AND lu = 0
";
$nbNotif = 0;
$resCount = mysqli_query($conn, $sqlCount);
if ($resCount) {
    $row = mysqli_fetch_assoc($resCount);
    $nbNotif = (int) ($row['total'] ?? 0);
}

// Récupérer toutes les notifications pour ce receveur
$sqlNotif = "
    SELECT id_notification, titre, message, lien, date_notif, lu
    FROM notif
    WHERE id_user = $id_receveur AND role = 'receveur'
    ORDER BY date_notif DESC
";

$notifListResult = mysqli_query($conn, $sqlNotif);
$current = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

function active($page) {
  global $current;
  return $current === $page ? 'active' : '';
}

// Si tu veux qu’un menu soit actif sur plusieurs pages (ex: campagnes + participer)
function activeIn(array $pages) {
  global $current;
  return in_array($current, $pages, true) ? 'active' : '';
}
?>

<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Notifications - 100Vies (Receveur)</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../css/rdv.css">
<link rel="stylesheet" href="../css/notif.css">
<link rel="icon" type="image/png" href="../media/logo_noir.png">
</head>
<audio id="notifSound" preload="auto">
    <source src="../media/sounds/notification.mp3" type="audio/mpeg">
</audio>
<body>

<header class="main-header"> 
  <img src="../media/logo_noir.png" width="50" alt="Logo"> 
  
  <!-- Burger (mobile) -->
  <button class="burger" type="button" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="mainNav">
    <span></span><span></span><span></span>
  </button>

  <ul class="nav-links" id="mainNav"> 
    <li><a href="receveur.php" class="<?= active('receveur.php') ?>">Accueil</a></li> 
     
    <li><a href="campagnes-evenements.php" class="<?= activeIn(['campagnes-evenements.php','participer.php']) ?>">Campagnes</a></li>
    <li><a href="centres.php" class="<?= active('centres.php') ?>">Centres</a></li>
    <li><a href="temoignages.php" class="<?= active('temoignages.php') ?>">Témoignages</a></li>

    <li><a href="contact.php" class="<?= active('contact.php') ?>">Contact</a></li>
    <li><a href="a-propos.php" class="<?= active('a-propos.php') ?>">À propos</a></li>

    <!-- Profil -->
    <li class="dropdown <?= activeIn(['profil.php','notifications_receveur.php']) ?>"> 
      <a href="#" class="dropdown-toggle">
        <i class="uil uil-user-circle"></i> Profil
      </a> 
      <div class="dropdown-content"> 
        <div class="status">
          <a href="profil.php" class="<?= active('profil.php') ?>"> <i class="uil uil-heart"></i> Receveur </a>
        </div> 

        <!-- Notifications -->
        <a href="notifications_receveur.php" class="<?= active('notifications_receveur.php') ?>">
          <i class="uil uil-bell"></i>
          Mes notifications 
        </a>

        <a href="http://localhost/sensibilisation-au-don-de-sang/administrateur/php/inscription.php">
          <i class="uil uil-plus"></i> Ajouter un compte
        </a> 

        <a href="http://localhost/sensibilisation-au-don-de-sang/Donneur/php/donneur.php">
          <i class="uil uil-exchange"></i> Modifier statut
        </a> 

        <a href="http://localhost/sensibilisation-au-don-de-sang/administrateur/php/form.php">
          <i class="uil uil-signout"></i> Déconnexion
        </a> 
      </div> 
    </li> 
  </ul> 
</header>

<div class="notifications-wrapper">
    <h1 class="notifications-title">Centre de notifications</h1>

    <div class="notifications-grid">
        <?php if ($notifListResult && mysqli_num_rows($notifListResult) > 0): ?>
            <?php while ($notif = mysqli_fetch_assoc($notifListResult)): ?>
                <div class="notif-card <?= ((int)$notif['lu'] === 1) ? 'read' : 'unread' ?>">
                    <div class="notif-title">
                        <?= htmlspecialchars($notif['titre'] ?? '') ?>
                    </div>

                    <div class="notif-message">
                        <?= htmlspecialchars($notif['message'] ?? '') ?>
                    </div>

                    <div class="notif-footer">
                        <span><?= date('d/m/Y H:i', strtotime($notif['date_notif'])) ?></span>
                        <a href="ouvrir_notification_receveur.php?id=<?= (int)$notif['id_notification'] ?>">Ouvrir</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">Aucune notification pour le moment.</p>
        <?php endif; ?>
    </div>
</div>

<!-- ===================== footer ===================== -->
<footer class="main-footer">
  <div class="container py-4">
    <div class="row">
      <!-- À propos -->
      <div class="col-md-4 mb-3">
        <h5>À propos</h5>
        <p>100Vies est une plateforme pour localiser rapidement les centres de don et contribuer à sauver des vies.</p>
      </div>

      <!-- Liens rapides -->
      <div class="col-md-4 mb-3">
        <h5>Liens rapides</h5>
        <ul class="footer-links">
          <li><a href="index1.html">Accueil</a></li>
          <li><a href="campagnes-evenements.php">Campagnes</a></li>
          <li><a href="temoignages.php">Témoignages</a></li>
          <li><a href="contact.php">Contact</a></li>
        </ul>
  </div>
      <!-- Contact -->
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
    <p class="text-center small mb-0">&copy; 2025 100Vies. Tous droits réservés.</p>
  </div>
</footer>

<span id="notif-data" data-count="<?= (int)$nbNotif ?>"></span>

<!-- Si ton JS fait un fetch vers check_notifications.php, duplique-le pour receveur (voir fichier ci-dessous) -->
<script src="../js/notifications.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
