<?php
session_start(); // 🔹 Démarrer la session
include("connexion.php");
function isActive($page) {
  $current = basename($_SERVER['PHP_SELF']);  // ex: index1.php
  return ($current === $page) ? 'active' : '';
}

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
?>


<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact - Don du Sang</title>
<link rel="stylesheet" href="../css/contact.css">
<link rel="stylesheet" href="../css/chatbot.css">
  <link rel="stylesheet" href="../css/navbar.css">
<link rel="icon" type="image/png" href="../media/logo_noir.png">
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
/* Glass card pour le container */
nav ul li a{
  color: black;
}
</style>
</head>
<body>
 <!-- HEADER + NAVIGATION -->
    <header>
        <div class="logo">
            <img id="logo" src="../media/logo_noir.png" alt="100Vies Logo" width="50px">
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

</body>
</html>
