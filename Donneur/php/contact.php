<?php
session_start(); // 🔹 Démarrer la session
include("connexion.php");


$error = "";
// 1️⃣ Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['name'];
    $email = $_POST['email'];
    $sujet = $_POST['subject'];
    $message = $_POST['message'];

    // Insérer dans la table notification
    $sql = "INSERT INTO notification (nom_donneur, email_donneur, sujet, contenu_message, date_envoi, statu) 
            VALUES (?, ?, ?, ?, NOW(), 'Non lu')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nom, $email, $sujet, $message);
    $stmt->execute();
    $stmt->close();

    // 🔹 Stocker un indicateur de succès dans la session
    $_SESSION['success'] = "✅ Votre message a été envoyé avec succès.";

    // Redirection pour éviter la resoumission
    header("Location: contact.php");
    exit();
}

// 🔹 Vérifier si le message de succès est présent
$success = "";
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']); // 🔹 Supprimer pour que le message n'apparaisse qu'une seule fois
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


<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact - Don du Sang</title>
<link rel="stylesheet" href="../css/contact.css">
<link rel="stylesheet" href="../css/chatbot.css">
<link rel="icon" type="image/png" href="../media/logo_noir.png">
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

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
<main>
<section class="contact">
<div class="container glass-card">
    <h2>Contactez-nous</h2>

    <?php if ($success): ?>
  <div class="alert-success">
    <?= $success ?>
  </div>
<?php endif; ?>

    <?php if($error): ?>
        <div class="alert-error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="input-box">
            <input type="text" name="name" placeholder="Nom complet" required>
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="input-box">
            <input type="text" name="subject" placeholder="Sujet" required>
        </div>
        <div class="input-box">
            <textarea name="message" placeholder="Votre message" rows="6" required></textarea>
        </div>
        <div class="submit-btn">
            <button type="submit">Envoyer</button>
        </div>
    </form>
</div>
</section>
</main>

<footer class="main-footer">
  <div class="container py-4">
    <div class="row">
      <div class="col-md-4 mb-3">
        <h5>À propos</h5>
        <p>100Vies est une plateforme pour localiser rapidement les centres de don et contribuer à sauver des vies.</p>
      </div>
      <div class="col-md-4 mb-3">
        <h5>Liens rapides</h5>
        <ul class="footer-links">
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
    <p class="text-center small mb-0">&copy; 2025 100Vies. Tous droits réservés.</p>
  </div>
  
</footer>
<script src="../js/chatbot.js"></script>
<span id="notif-data" data-count="<?= $nbNotif ?>"></span>

<script src="../js/notifications.js"></script>

<script src="../js/responsive.js"></script>

</body>
</html>
