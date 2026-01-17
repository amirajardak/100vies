<?php
session_start();
include("connexion.php");

$centresSql = "SELECT id_centre, nom_centre FROM centre ORDER BY nom_centre";
$centresResult = mysqli_query($conn, $centresSql);
// 🔐 ID du donneur connecté
$id_donneur = $_SESSION['id_donneur'] ?? 0;
$id_donneur = (int) $id_donneur;

// Message de retour
$message = "";

// 🔔 Nombre de notifications non lues
$nbNotif = 0;

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nom_donneur = trim($_POST["id_donneur"]);
    $id_centre   = (int) $_POST["centre"];
    $date_don    = $_POST["date_don"];
    $heure       = $_POST["heure"];
    $type_don    = $_POST["type_don"];
    $volume      = $_POST["volume"];
    $etat_don    = "En attente";

    if (empty($nom_donneur) || empty($id_centre) || empty($date_don) || empty($heure) || empty($type_don) || empty($volume)) {
        $message = "❌ Veuillez remplir tous les champs.";
    } else {

        // Récupérer ID du donneur depuis le nom
        $donneurSql = "SELECT id_donneur FROM donneur WHERE nom = '$nom_donneur' LIMIT 1";
        $donneurResult = mysqli_query($conn, $donneurSql);

        if ($donneurResult && mysqli_num_rows($donneurResult) === 1) {
            $donneur = mysqli_fetch_assoc($donneurResult);
            $id_donneur = (int) $donneur['id_donneur'];

            // Vérification dernier don
            $lastDonSql = "SELECT date_don FROM don WHERE id_donneur = $id_donneur ORDER BY date_don DESC LIMIT 1";
            $lastDonResult = mysqli_query($conn, $lastDonSql);
            $autoriserInsertion = true;

            if ($lastDonResult && mysqli_num_rows($lastDonResult) > 0) {
                $lastDon = mysqli_fetch_assoc($lastDonResult);
                $dateDernierDon = $lastDon['date_don'];
                $dateAutorisee = date('Y-m-d', strtotime($dateDernierDon . ' +3 months'));

                if ($date_don < $dateAutorisee) {
                    $message = "⛔ Dernier don effectué le "
                             . date('d/m/Y', strtotime($dateDernierDon))
                             . ". Nouveau rendez-vous possible à partir du "
                             . date('d/m/Y', strtotime($dateAutorisee)) . ".";
                    $autoriserInsertion = false;
                }
            }

            // Insertion don
            if ($autoriserInsertion) {
                $insertDonSql = "
                    INSERT INTO don (date_don, heure, id_donneur, id_centre, type_don, etat_don, volume)
                    VALUES ('$date_don', '$heure', $id_donneur, $id_centre, '$type_don', '$etat_don', '$volume')
                ";

                if (mysqli_query($conn, $insertDonSql)) {
                    // 🔔 Ajout de la notification
                    $titre = "Rendez-vous confirmé";
                    $messageNotif = "Votre rendez-vous de don du "
                                    . date('d/m/Y', strtotime($date_don))
                                    . " a été enregistré.";
                    $lien = "notifications.php"; // lien vers la page notifications
                    $notifSql = "
                        INSERT INTO notif (id_user, role, titre, message, lien, lu)
                        VALUES ($id_donneur, 'donneur', '$titre', '$messageNotif', '$lien', 0)
                    ";
                    mysqli_query($conn, $notifSql);

                    $message = "✅ Rendez-vous enregistré et notification ajoutée.";
                } else {
                    $message = "❌ Erreur SQL : " . mysqli_error($conn);
                }
            }

        } else {
            $message = "❌ Donneur introuvable.";
        }
    }
}
?>

<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Enregistrer un don</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
<link rel="stylesheet" href="../css/rdv.css">
<link rel="stylesheet" href="../css/chatbot.css">
<link rel="icon" type="image/png" href="../media/logo_noir.png">
</head>
<audio id="notifSound" preload="auto">
    <source src="../media/sounds/notification.mp3" type="audio/mpeg">
</audio>
<body class="bg-light">

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

<main class="container mt-5">
<?php if ($message): ?>
    <div class="alert alert-info text-center">
        <?= $message ?>
    </div>
<?php endif; ?>
<div class="card shadow-sm mx-auto" style="max-width: 600px;">
<div class="card-body">
<form method="POST" class="row g-3">

    <div class="col-12">
      <label class="form-label">Nom du donneur</label>
      <input type="text" name="id_donneur" required class="form-control" placeholder="Tapez le nom du donneur">
    </div>
    <div class="col-12">
        <label class="form-label">Choisir un centre</label>
        <select name="centre" required class="form-select">
            <option value="">-- Choisir --</option>
         <?php if ($centresResult && mysqli_num_rows($centresResult) > 0): ?>
        <?php while ($centre = mysqli_fetch_assoc($centresResult)): ?>
            <option value="<?= $centre['id_centre'] ?>">
                <?= htmlspecialchars($centre['nom_centre']) ?>
            </option>
        <?php endwhile; ?>
    <?php else: ?>
        <option disabled>Aucun centre disponible</option>
    <?php endif; ?>
</select>
    </div>
    <div class="col-6">
        <label class="form-label">Date du don</label>
        <input type="date" name="date_don" required class="form-control">
    </div>
    <div class="col-6">
        <label class="form-label">Heure du don</label>
        <input type="time" name="heure" required class="form-control">
    </div>
    <div class="col-12">
        <label class="form-label">Type de don</label>
        <input type="text" name="type_don" required class="form-control" placeholder="Ex: Sang, Plasma">
    </div>
    <div class="col-12">
        <label class="form-label">Volume (ml)</label>
        <input type="number" name="volume" required class="form-control">
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-danger w-100">Enregistrer le don</button>
    </div>
</form>
</div>
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
<script src="../js/chatbot.js"></script>
<span id="notif-data" data-count="<?= $nbNotif ?>"></span>

<script src="../js/notifications.js"></script>

<script src="../js/responsive.js"></script>

</body>
</html>
