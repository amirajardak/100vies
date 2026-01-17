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

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : "";

$sql = "SELECT 
          nom_centre,
          adresse_centre,
          gouvernorat,
          lat,
          lng,
          tel,
          email,
          type_centre
        FROM centre
        WHERE nom_centre LIKE '%$search%'
           OR adresse_centre LIKE '%$search%'
           OR gouvernorat LIKE '%$search%'
        ORDER BY nom_centre ASC";

$result = mysqli_query($conn, $sql);
$centres = [];
while ($row = mysqli_fetch_assoc($result)) {
    $centres[] = $row;
}
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Centres de Don</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Icons -->
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
<!-- Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="../css/centres.css">
<link rel="stylesheet" href="../css/chatbot.css">
<link rel="icon" type="image/png" href="../media/logo_noir.png">
</head>
<audio id="notifSound" preload="auto">
    <source src="../media/sounds/notification.mp3" type="audio/mpeg">
</audio>
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
<div class="hero">
  <h1>Trouver un centre de don</h1>
  <p>Localisez rapidement les centres proches de vous</p>
</div>

<form method="GET">
  <div class="search-box">
    <i class="uil uil-search"></i>
    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Nom, adresse ou gouvernorat">
  </div>
</form>

<div class="split-layout">

  <!-- MAP -->
  <div class="map-side">
    <div id="map"></div>
  </div>

  <!-- LIST -->
  <div class="list-side">
    <h3 class="fw-bold mb-3">Centres disponibles</h3>
    <div class="row g-4">
      <?php foreach($centres as $index => $c): ?>
      <div class="col-md-6">
        <div class="card centre-card" data-index="<?= $index ?>">
          <div class="card-body">
            <h5 class="fw-bold"><?= $c['nom_centre'] ?></h5>
            <span class="badge-custom"><?= $c['type_centre'] ?></span>
            <p class="text-muted mt-2"><?= $c['adresse_centre'] ?> – <?= $c['gouvernorat'] ?></p>
            <p class="small">📞 <?= $c['tel'] ?></p>
            <p class="small">✉️ <?= $c['email'] ?></p>
            <?php if($c['lat'] && $c['lng']): ?>
            <a target="_blank"
               href="https://www.google.com/maps?q=<?= $c['lat'] ?>,<?= $c['lng'] ?>"
               class="btn btn-outline-danger btn-sm mt-2">
               Itinéraire
            </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
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
<script>
const centres = <?php echo json_encode($centres, JSON_UNESCAPED_UNICODE); ?>;
</script>
<script src="../js/centres.js"></script>
<script src="../js/chatbot.js"></script>
<span id="notif-data" data-count="<?= $nbNotif ?>"></span>

<script src="../js/notifications.js"></script>


</body>
</html>
