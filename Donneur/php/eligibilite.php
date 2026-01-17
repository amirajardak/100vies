<?php
session_start();

$questions = [
    ["text" => "As-tu entre 18 et 65 ans ?", "answers" => [["t"=>"Oui","s"=>1],["t"=>"Non","s"=>-5]]],
    ["text" => "Pèses-tu plus de 50 kg ?", "answers" => [["t"=>"Oui","s"=>1],["t"=>"Non","s"=>-3]]],
    ["text" => "As-tu eu une maladie récente ?", "answers" => [["t"=>"Non","s"=>1],["t"=>"Oui","s"=>-2]]],
    ["text" => "Tatouage ou piercing récent (moins de 4 mois) ?", "answers" => [["t"=>"Non","s"=>1],["t"=>"Oui","s"=>-2]]],
    ["text" => "As-tu pris des antibiotiques récemment ?", "answers" => [["t"=>"Non","s"=>1],["t"=>"Oui","s"=>-1]]],
    ["text" => "Es-tu enceinte ou as-tu accouché récemment ?", "answers" => [["t"=>"Non","s"=>1],["t"=>"Oui","s"=>-5]]],
    ["text" => "As-tu consommé de l’alcool récemment ?", "answers" => [["t"=>"Non","s"=>1],["t"=>"Oui","s"=>-2]]]
];

$totalQuestions = count($questions);

if (isset($_GET["restart"])) {
    session_destroy();
    header("Location: eligibilite.php");
    exit;
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
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Éligibilité au don de sang</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
<link rel="stylesheet" href="../css/eligibilite.css">
<link rel="stylesheet" href="../css/chatbot.css">
<link rel="icon" type="image/png" href="../media/logo_noir.png">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
    <li><a href="donneur.php">Accueil</a></li> 
    <li class="dropdown">
  <a href="#" class="active dropdown-toggle">Pourquoi </a>
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
      <a href="#" class="dropdown-toggle">
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
         <a href="http://localhost/sensibilisation-au-don-de-sang/administrateur/php/inscription.php"><i class="uil uil-plus"></i> Ajouter un compte</a> 
        <a href="http://localhost/sensibilisation-au-don-de-sang/receveur/php/receveur.php"><i class="uil uil-exchange"></i> Modifier statut</a> 
        <a href="http://localhost/sensibilisation-au-don-de-sang/administrateur/php/form.php"><i class="uil uil-signout"></i> Déconnexion</a>  
      </div> 
    </li> 
  </ul> 
</header>

<div class="container my-5">
    <div class="card mx-auto p-4" style="max-width: 600px;">
        <h1 class="text-center fw-bold mb-3">Test d’éligibilité</h1>
        <div class="progress">
            <div class="progress-bar" style="width:0%"></div>
        </div>
        <div id="quiz-area"></div>
        <div class="text-center mt-4">
            <a href="eligibilite.php?restart=1" class="text-decoration-underline small">Refaire le test</a>
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
const questions = <?php echo json_encode($questions, JSON_UNESCAPED_UNICODE); ?>;
</script>
<script src="../js/eligibilite.js">
<script src="../js/chatbot.js"></script>
<span id="notif-data" data-count="<?= $nbNotif ?>"></span>

<script src="../js/notifications.js"></script>
</script>

<script src="../js/responsive.js"></script>

</body>
</html>
