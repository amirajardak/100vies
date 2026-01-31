<?php
include("connexion.php");

// 🔹 Récupérer l'ID de la campagne depuis l'URL
$id_campagne = isset($_GET['id']) ? (int)$_GET['id'] : null;
if (!$id_campagne) {
    echo "<p style='color:red; font-weight:bold;'>Campagne introuvable.</p>";
    exit();
}

// 🔹 Récupérer les infos de la campagne
$stmt = $conn->prepare("SELECT * FROM campagne WHERE id_cmp = ?");
$stmt->bind_param("i", $id_campagne);
$stmt->execute();
$campagne = $stmt->get_result()->fetch_assoc();

if (!$campagne) {
    echo "<p style='color:red; font-weight:bold;'>Campagne introuvable.</p>";
    exit();
}

// 🔹 Confirmer la participation (simple insertion)
$message = "";
if (isset($_POST['confirm'])) {
    // Ici on peut mettre un ID fictif ou fixe car pas de session
    $id_donneur = 1; // 🔹 Exemple : ID fixe du donneur
    $stmtInsert = $conn->prepare("INSERT INTO participation (id_donneur, id_cmp, date_participation) VALUES (?, ?, NOW())");
    $stmtInsert->bind_param("ii", $id_donneur, $id_campagne);
    $stmtInsert->execute();
    $message = "✅ Participation confirmée avec succès !";
}

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

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Participer à la campagne</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="../css/campagne.css">
<link rel="stylesheet" href="../css/chatbot.css">
<link rel="icon" type="image/png" href="../media/logo_noir.png">
<audio id="notifSound" preload="auto">
    <source src="../media/sounds/notification.mp3" type="audio/mpeg">
</audio>
</head>

<body>
<header class="main-header"> 
  <img src="../media/logo_blanc.png" width="50" alt="Logo"> 
  
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

        <a href="http://localhost/100vies/administrateur/php/inscription.php">
          <i class="uil uil-plus"></i> Ajouter un compte
        </a> 

        <a href="http://localhost/100vies/Donneur/php/donneur.php">
          <i class="uil uil-exchange"></i> Modifier statut
        </a> 

        <a href="http://localhost/100vies/administrateur/php/form.php">
          <i class="uil uil-signout"></i> Déconnexion
        </a> 
      </div> 
    </li> 
  </ul> 
</header>
<div class="container mt-5">
  <div class="card shadow p-4 mx-auto" style="max-width:600px">

    <h2 class="mb-3"><?= htmlspecialchars($campagne['nom_cmp']) ?></h2>

    <p><strong>Date :</strong>
      <?= date('d/m/Y', strtotime($campagne['date_deb_cmp'])) ?>
      → <?= date('d/m/Y', strtotime($campagne['date_fin_cmp'])) ?>
    </p>

    <p><strong>Lieu :</strong> <?= htmlspecialchars($campagne['lieu']) ?></p>
    <p><?= htmlspecialchars($campagne['description_cmp']) ?></p>

    <?php if (!empty($campagne['image'])): ?>
      <img src="../media/campagne/<?= htmlspecialchars($campagne['image']) ?>"
           class="img-fluid rounded mb-3">
    <?php endif; ?>

    <?php if ($message): ?>
      <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" action="participer.php?id=<?= $id_campagne ?>">
      <button name="confirm" class="btn btn-danger w-100 mt-2">
        Confirmer la participation
      </button>
    </form>

    <a href="campagnes-evenements.php" class="btn btn-secondary w-100 mt-2">
      Retour
    </a>

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
<script src="../js/chatbot.js"></script>
<span id="notif-data" data-count="<?= $nbNotif ?>"></span>

<script src="../js/notifications.js"></script>
</body>
</html>

