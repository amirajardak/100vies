<?php
session_start();
include("connexion.php");

// Exemple : id_donneur connecté
$id_donneur = 1; // ici tu peux remplacer par $_SESSION['id_donneur']

// Récupération des infos du donneur
$sql = "SELECT * FROM donneur WHERE id_donneur = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_donneur);
$stmt->execute();
$result = $stmt->get_result();
$donneur = $result->fetch_assoc();

// Récupération historique des dons
$sql2 = "SELECT don.date_don, don.type_don, don.etat_don, centre.nom_centre
         FROM don
         LEFT JOIN centre ON don.id_centre = centre.id_centre
         WHERE don.id_donneur = ?
         ORDER BY don.date_don DESC";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $id_donneur);
$stmt2->execute();
$dons = $stmt2->get_result();

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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profil Utilisateur | 100Vies</title>
<link href="https://unicons.iconscout.com/release/v4.0.8/css/line.css" rel="stylesheet">
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../css/profil.css">
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
 <!-- COLONNE DROITE : contenu -->
   <div class="profile-section" style="display:flex; gap:20px; flex-wrap:wrap;">
  
  <!-- Informations personnelles -->
  <div class="tab-content glass-card" style="flex:1; min-width:300px;">
    <h5 class="text-danger">Informations personnelles</h5>
    <div style="display:flex; gap:20px; align-items:center; margin-bottom:20px;"> 
  <img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="ui-w-80"> 
  <div class="avatar-btns">
    <label class="btn-avatar-upload"> 
      Modifier la photo
      <input type="file" id="avatarInput" style="display:none;"> 
    </label> 
    <button class="btn-avatar-reset" id="resetAvatar">Réinitialiser</button> 
  </div> 
</div>
    <label>Nom</label>
    <input class="form-control" value="<?php echo $donneur['nom'] . ' ' . $donneur['prenom']; ?>">
    <label>Groupe sanguin</label>
    <input class="form-control" value="<?php echo $donneur['grp_sanguin']; ?>">
    <label>Email</label>
    <input class="form-control" value="<?php echo $donneur['email']; ?>">
    <label>Adresse</label>
    <input class="form-control" value="<?php echo $donneur['adresse']; ?>">
  </div>

  <!-- Historique des dons -->
  <div class="tab-content glass-card" style="flex:1; min-width:300px;">
    <h5 class="text-danger">Historique des dons</h5>
    <div class="recent-dons">
      <?php while($don = $dons->fetch_assoc()): ?>
        <div class="don-card">
          <div class="don-type">
            <i class="uil uil-heart"></i> <?php echo $don['type_don']; ?>
          </div>
          <div class="don-info">
            <span><?php echo $don['date_don']; ?></span>
            <span><?php echo ucfirst($don['etat_don']); ?></span>
            <?php if($don['nom_centre']): ?>
              <span class="don-center"><?php echo "Centre: " . $don['nom_centre']; ?></span>
            <?php endif; ?>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
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
      </div>
    </div>
    <hr style="border-color: rgba(255,255,255,0.2)">
    <p class="text-center small mb-0">&copy; 2025 100Vies. Tous droits réservés.</p>
  </div>
</footer>
<script src="../js/profil.js"></script>
<script src="../js/chatbot.js"></script>
<span id="notif-data" data-count="<?= $nbNotif ?>"></span>
<script src="../js/notifications.js"></script>
</body>
</html>
