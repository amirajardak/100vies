<?php
include("connexion.php");
$current = basename($_SERVER['PHP_SELF']); // ✅ page courante

function active($page) {
  global $current;
  return $current === $page ? 'active' : '';
}

function activeIn(array $pages) {
  global $current;
  return in_array($current, $pages, true) ? 'active' : '';
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom   = mysqli_real_escape_string($conn, $_POST["name"]);
    $role  = mysqli_real_escape_string($conn, $_POST["role"]);
    $story = mysqli_real_escape_string($conn, $_POST["story"]);

    $sql = "INSERT INTO temoignages (nom, role, histoire) VALUES ('$nom','$role','$story')";
    if (mysqli_query($conn, $sql)) {
        // Stocke le message dans la session
        session_start();
        $_SESSION['message'] = "Merci ! Votre témoignage a été publié.";
        // Redirection pour éviter le double submit
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $message = "Erreur lors de l’enregistrement.";
    }
}

// Récupérer le message de session si disponible
session_start();
if(isset($_SESSION['message'])){
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}


$result = mysqli_query($conn,"SELECT * FROM temoignages ORDER BY id DESC");
$temoignages = mysqli_fetch_all($result, MYSQLI_ASSOC);


?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Témoignages</title>
<link rel="icon" type="image/png" href="../media/logo_noir.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
<link rel="stylesheet" href="../css/temoignage.css">
<link rel="stylesheet" href="../css/chatbot.css">
<audio id="notifSound" preload="auto">
    <source src="../media/sounds/notification.mp3" type="audio/mpeg">
</audio>
</head>
<body>
<header class="main-header"> 
  <img src="../media/logo_blanc.png" width="50" alt="Logo"> 
  
 
 <ul class="nav-links" id="mainNav"> 
  <li><a href="receveur.php" class="<?= active('receveur.php') ?>">Accueil</a></li> 
  <li><a href="campagnes-evenements.php" class="<?= active('campagnes-evenements.php') ?>">Campagnes</a></li>
  <li><a href="centres.php" class="<?= active('centres.php') ?>">Centres</a></li>
  <li><a href="temoignages.php" class="<?= active('temoignages.php') ?>">Témoignages</a></li>
  <li><a href="contact.php" class="<?= active('contact.php') ?>">Contact</a></li>
  <li><a href="a-propos.php" class="<?= active('a-propos.php') ?>">À propos</a></li>

  <li class="dropdown <?= activeIn(['profil.php','notifications_receveur.php']) ?>"> 
    <a href="#" class="dropdown-toggle">
      <i class="uil uil-user-circle"></i> Profil
    </a> 
    <div class="dropdown-content"> 
      <div class="status">
        <a href="profil.php" class="<?= active('profil.php') ?>"> <i class="uil uil-heart"></i> Receveur </a>
      </div> 
      <a href="notifications_receveur.php" class="<?= active('notifications_receveur.php') ?>">
        <i class="uil uil-bell"></i> Mes notifications 
      </a>

          <a href="http://localhost/100vies/administrateur/php/inscription.php"><i class="uil uil-plus"></i> Ajouter un compte</a> 
          <a href="http://localhost/100vies/Donneur/php/donneur.php"><i class="uil uil-exchange"></i> Modifier statut</a> 
          <a href="http://localhost/100vies/administrateur/php/form.php"><i class="uil uil-signout"></i> Déconnexion</a> 
        </div> 
    </li> 
  </ul> 
</header>

<div class="hero">
  <h1>Témoignages</h1>
  <p>Découvrez des expériences inspirantes</p>
</div>

<div class="open-form-wrapper">
  <button class="btn-open-form">
    <span class="icon-circle"><i class="uil uil-plus"></i></span>
    Ajouter un témoignage
  </button>
</div>

<main class="container py-4">
<?php if($message): ?>
<div class="alert alert-success text-center"><?= $message ?></div>
<?php endif; ?>

<div class="row g-4">
<?php foreach($temoignages as $t): ?>
  <div class="col-md-6">
    <div class="temoignage-card pt-4 ps-4">
      <h5><?= htmlspecialchars($t['nom']) ?></h5>
      <span class="badge-custom"><?= htmlspecialchars($t['role']) ?></span>
      <p class="mt-3"><?= nl2br(htmlspecialchars($t['histoire'])) ?></p>
    </div>
  </div>
<?php endforeach; ?>
<?php if(empty($temoignages)): ?>
<p class="text-muted">Aucun témoignage pour le moment.</p>
<?php endif; ?>
</div>
</main>

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

<!-- MODAL -->
<div class="modal-overlay" id="temoignageModal">
  <div class="modal-form neo-form">
    <button class="close-modal">&times;</button>
    <h3 class="fw-bold text-center mb-3">Votre histoire</h3>

    <form method="POST">
      <input type="text" name="name" required placeholder="Votre nom">

      <div class="select-wrapper">
        <select name="role" required>
          <option value="" disabled selected>Votre rôle</option>
          <option>Donneur</option>
          <option>Receveur</option>
          <option>Famille d’un patient</option>
        </select>
        <i class="uil uil-angle-down"></i>
      </div>

      <textarea name="story" rows="4" required placeholder="Racontez votre expérience"></textarea>

      <button type="submit"><i class="uil uil-upload"></i> Publier</button>
    </form>
  </div>
</div>
<script src="../js/temoignage.js"></script>
<script src="../js/chatbot.js"></script>
<span id="notif-data" data-count="<?= $nbNotif ?>"></span>

<script src="../js/notifications.js"></script>

</body>
</html>
