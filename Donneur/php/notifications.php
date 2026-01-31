<?php
session_start();
include("connexion.php");

// Vérifier si le donneur est connecté
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'donneur') {
    die("Donneur non connecté.");
}

$id_donneur = (int) $_SESSION['user_id'];

// Récupérer toutes les notifications pour ce donneur
$sqlNotif = "
    SELECT id_notification, titre, message, lien, date_notif, lu
    FROM notif
    WHERE id_user = $id_donneur AND role = 'donneur'
    ORDER BY date_notif DESC
";

$notifListResult = mysqli_query($conn, $sqlNotif);
?>

<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Notifications - 100Vies</title>
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
  <ul class="nav-links"> 
    <li><a href="donneur.php">Accueil</a></li> 
    <li class="dropdown">
  <a href="#" class="active">Pourquoi <i class="uil uil-angle-down"></i></a>
  <div class="dropdown-content">
    <a href="pourquoi-donner.html">Pourquoi donner ?</a>
    <a href="centres.php">Où donner ?</a>
  </div>
</li> 
    <li><a href="campagnes-evenements.php">Campagnes</a></li>
    <li><a href="temoignages.php">Témoignages</a></li>
    <li><a href="appelurgent.php">Appel urgent</a></li>
    <li><a href="contact.php">Contact</a></li>
    <li><a href="a-propos.php">À propos</a></li>
    <!-- Profil -->
    <li class="dropdown"> 
      <a href="#">
        <i class="uil uil-user-circle"></i> Profil
      </a> 
      <div class="dropdown-content"> 
        <div class="status">
          <a href="profil.php"> <i class="uil uil-heart"></i> Donneur </a>
        </div> 
        <!-- Notifications -->
    <a href="notifications.php">
      <i class="uil uil-bell"></i>
      Mes notifications 
      
    </a>
         <a href="http://localhost/100vies/administrateur/php/inscription.php"><i class="uil uil-plus"></i> Ajouter un compte</a> 
        <a href="http://localhost/100vies/receveur/php/receveur.php"><i class="uil uil-exchange"></i> Modifier statut</a> 
        <a href="http://localhost/100vies/administrateur/php/form.php"><i class="uil uil-signout"></i> Déconnexion</a>  
      </div> 
    </li> 
  </ul> 
</header>

<div class="notifications-wrapper">
    <h1 class="notifications-title">Centre de notifications</h1>

    <div class="notifications-grid">
        <?php if ($notifListResult && mysqli_num_rows($notifListResult) > 0): ?>
            <?php while ($notif = mysqli_fetch_assoc($notifListResult)): ?>
                <div class="notif-card <?= $notif['lu'] ? 'read' : 'unread' ?>">
                    
                    <div class="notif-title">
                        <?= htmlspecialchars($notif['titre']) ?>
                    </div>

                    <div class="notif-message">
                        <?= htmlspecialchars($notif['message']) ?>
                    </div>

                    <div class="notif-footer">
                        <span><?= date('d/m/Y H:i', strtotime($notif['date_notif'])) ?></span>
                       <a href="ouvrir_notification.php?id=<?= $notif['id_notification'] ?>">
                        Ouvrir
                       </a>
                    </div>

                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">Aucune notification pour le moment.</p>
        <?php endif; ?>
    </div>
</div>

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
<span id="notif-data" data-count="<?= $nbNotif ?>"></span>
<script src="../js/responsive.js"></script>
<script src="../js/notifications.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
