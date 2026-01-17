<?php
include("connexion.php");

// 🔹 Récupération des filtres (GET)
$search = $_GET['search'] ?? "";
$lieu   = $_GET['lieu'] ?? "";
$date   = $_GET['date'] ?? "";
$role   = $_GET['role'] ?? "";

// 🔹 Requête de base
$sql = "SELECT 
          id_cmp,
          nom_cmp AS titre,
          date_deb_cmp AS date,
          lieu,
          description_cmp AS description,
          image,
          'Campagne' AS type
        FROM campagne
        WHERE 1=1";

// 🔍 Recherche globale
if (!empty($search)) {
    $sql .= " AND (
                nom_cmp LIKE '%$search%' 
                OR description_cmp LIKE '%$search%' 
                OR lieu LIKE '%$search%'
             )";
}

// 📍 Filtre par lieu
if (!empty($lieu)) {
    $sql .= " AND lieu LIKE '%$lieu%'";
}

// 📅 Filtre par date
if (!empty($date)) {
    $sql .= " AND date_deb_cmp = '$date'";
}

// 👤 Filtre par rôle (si la colonne existe plus tard)
// if (!empty($role)) {
//     $sql .= " AND role = '$role'";
// }

// 📌 Tri
$sql .= " ORDER BY date_deb_cmp DESC";

// 🔹 Exécution
$result = mysqli_query($conn, $sql);

$current = basename($_SERVER['PHP_SELF']); // ✅ page courante

function active($page) {
  global $current;
  return $current === $page ? 'active' : '';
}

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
  <title>Campagnes & Événements</title>
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
  <link rel="stylesheet" href="../css/campagne.css">
  <link rel="stylesheet" href="../css/chatbot.css">
<link rel="icon" type="image/png" href="../media/logo_noir.png">
</head>
<audio id="notifSound" preload="auto">
    <source src="../media/sounds/notification.mp3" type="audio/mpeg">
</audio>
<body class="custom-bg">
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
  <!-- Barre de recherche en haut -->
<div class="container mt-4">
  <form method="GET" class="search-wrapper mx-auto">
    <i class="uil uil-search search-icon"></i>
    <input type="text" name="search" placeholder="Rechercher une campagne, un lieu..." value="<?= htmlspecialchars($search) ?>" class="search-input">
  </form>
</div>

<!-- Page : filtre à gauche et cards à droite -->
<main class="page-wrapper">
  <div class="page-layout container">
    <!-- Filtre à gauche -->
    <aside class="filter-panel">
  <form method="GET" id="filterForm">
    <h5 class="filter-title">Filtrer</h5>

    <div class="filter-group">
      <label>Rôle</label>
      <select name="role">
        <option value="">Tous</option>
        <option>Donneur</option>
        <option>Receveur</option>
        <option>Famille</option>
      </select>
    </div>

    <div class="filter-group">
      <label>Lieu</label>
      <input type="text" name="lieu" placeholder="Ville ou centre">
    </div>

    <div class="filter-group">
      <label>Date</label>
      <input type="date" name="date">
    </div>

    <button type="button" class="btn-filter-reset" id="resetFilter" onclick="window.location.href='campagnes-evenements.php'">Réinitialiser</button>
  </form>
</aside>

    <!-- Cards -->
    <section class="content-area">
      <div class="row gy-4">
        <?php while ($ev = mysqli_fetch_assoc($result)): ?>
        <div class="col-md-6">
          <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
            <img src="../media/campagne/<?= htmlspecialchars($ev['image']) ?>" 
                 class="card-img-top" 
                 alt="<?= htmlspecialchars($ev['titre']) ?>" 
                 style="height:200px; object-fit: cover;">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title fw-bold"><?= htmlspecialchars($ev['titre']) ?></h5>
              <p class="text-muted mb-1" style="font-size:0.9rem;">
                <?= $ev['type'] ?> — <?= date('d/m/Y', strtotime($ev['date'])) ?> — <?= htmlspecialchars($ev['lieu']) ?>
              </p>
              <p class="card-text mb-3" style="flex-grow:1; font-size:0.95rem;">
                <?= strlen($ev['description']) > 120 ? substr(htmlspecialchars($ev['description']),0,120).'...' : htmlspecialchars($ev['description']) ?>
              </p>
              <a href="participer.php?id=<?= $ev['id_cmp'] ?>" class="btn btn-danger fw-bold mt-auto">Participer</a>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
      </div>
    </section>
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
<script src="../js/campagne.js">  
</script>
  <script src="../js/chatbot.js"></script>
<span id="notif-data" data-count="<?= $nbNotif ?>"></span>

<script src="../js/notifications.js"></script>
<script src="../js/responsive.js"></script>

</body>
</html>
