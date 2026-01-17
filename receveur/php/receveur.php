<?php
include("connexion.php");
session_start();
$current = basename($_SERVER['PHP_SELF']); // ✅ page courante

function active($page) {
  global $current;
  return $current === $page ? 'active' : '';
}

function activeIn(array $pages) {
  global $current;
  return in_array($current, $pages, true) ? 'active' : '';
}
/* ===================== AJOUT APPEL URGENT ===================== */
if (isset($_POST['publier_appel'])) {

    $id_receveur = $_SESSION['id_receveur'] ?? 1;
    $description = trim($_POST['description_appel']);
    $date_appel  = date('Y-m-d');

    if (!empty($description)) {
        $stmt = $conn->prepare("
            INSERT INTO appelurgent (id_receveur, description_appel, date_appel)
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("iss", $id_receveur, $description, $date_appel);
        $stmt->execute();
    }

    header("Location: receveur.php");
    exit;
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* ===================== UTILISATEUR ===================== */
$id_user = $_SESSION['id_user'] ?? 0;

/* ===================== APPELS URGENTS ===================== */
$appels = mysqli_query($conn, "
  SELECT a.*, r.nom_receveur, r.prenom_receveur, r.groupe_sanguin_receveur, r.hopital
  FROM appelurgent a
  JOIN receveur r ON a.id_receveur = r.id_receveur
  ORDER BY a.date_appel DESC
");

/* ===================== CAMPAGNES ===================== */
$campagnes = mysqli_query($conn, "
  SELECT * FROM campagne ORDER BY date_deb_cmp DESC
");

/* ===================== SENSIBILISATION ===================== */
$infos = mysqli_query($conn, "
  SELECT * FROM temoignages ORDER BY created_at DESC
");
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="../css/donneur.css">
<link rel="stylesheet" href="../css/receveur.css">
<link rel="stylesheet" href="../css/chatbot.css">
<link rel="icon" type="image/png" href="../media/logo_noir.png">

<!-- Icons -->
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
<title>100Vies – Fil d’actualité</title>
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
<main>
  <div class="feed-layout">
  <!-- ===== SIDEBAR FILTRES ===== -->
  <aside class="filter-panel">
  <h4>Filtrer les publications</h4>
  <!-- TYPE DE PUBLICATION -->
  <div class="filter-section">
    <p class="filter-title">Type de publication</p>

    <label class="checkbox-item">
      <input type="checkbox" value="appel" class="filter-type" checked>
      <span class="checkmark"></span> Appels urgents
    </label>

    <label class="checkbox-item">
      <input type="checkbox" value="campagne" class="filter-type" checked>
      <span class="checkmark"></span> Campagnes
    </label>

    <label class="checkbox-item">
      <input type="checkbox" value="info" class="filter-type" checked>
      <span class="checkmark"></span> Sensibilisation
    </label>
  </div>

  <!-- DATE -->
  <div class="filter-section">
    <p class="filter-title">Date de publication</p>

    <label class="checkbox-item">
      <input type="checkbox" value="today" class="filter-date">
      <span class="checkmark"></span> Aujourd’hui
    </label>

    <label class="checkbox-item">
      <input type="checkbox" value="week" class="filter-date">
      <span class="checkmark"></span> Cette semaine
    </label>

    <label class="checkbox-item">
      <input type="checkbox" value="month" class="filter-date">
      <span class="checkmark"></span> Ce mois
    </label>
  </div>

  <!-- GROUPE SANGUIN -->
  <div class="filter-section">
    <p class="filter-title">Groupe sanguin</p>

    <div class="blood-grid">
      <label><input type="checkbox" value="A+" class="filter-blood"> A+</label>
      <label><input type="checkbox" value="A-" class="filter-blood"> A-</label>
      <label><input type="checkbox" value="B+" class="filter-blood"> B+</label>
      <label><input type="checkbox" value="B-" class="filter-blood"> B-</label>
      <label><input type="checkbox" value="AB+" class="filter-blood"> AB+</label>
      <label><input type="checkbox" value="AB-" class="filter-blood"> AB-</label>
      <label><input type="checkbox" value="O+" class="filter-blood"> O+</label>
      <label><input type="checkbox" value="O-" class="filter-blood"> O-</label>
    </div>
  </div>

  <!-- ACTIONS -->
  <div class="filter-actions">
    <button onclick="applyFilters()" class="btn-apply">Appliquer</button>
    <button onclick="resetFilters()" class="btn-reset">Réinitialiser</button>
  </div>

</aside>


  <!-- ===== FEED ===== -->
  <section class="feed-content">
<!-- ===================== PUBLIER UN APPEL URGENT ===================== -->
<div class="publish-box">
  <h3>Publier un appel urgent</h3>

  <?php if (!empty($publish_error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($publish_error) ?></div>
  <?php endif; ?>

  <?php if (isset($_GET['published'])): ?>
    <div class="alert alert-success">Votre appel urgent a été publié.</div>
  <?php endif; ?>

  <form method="POST" class="publish-form">
    <textarea name="description_appel"
      placeholder="Décrivez votre besoin (groupe sanguin, hôpital, urgence...)"
      required></textarea>

    <button type="submit" name="publier_appel">Publier l’appel</button>
  </form>
</div>
<!-- ===================== APPELS URGENTS ===================== -->
<?php while($a = mysqli_fetch_assoc($appels)):
$type='appel'; $id_pub=$a['id_appel'];
$likes_query = $conn->prepare("SELECT COUNT(*) as total, SUM(CASE WHEN id_user=? THEN 1 ELSE 0 END) as user_liked FROM likes WHERE type=? AND id_pub=?");
$likes_query->bind_param("isi",$id_user,$type,$id_pub);
$likes_query->execute();
$res = $likes_query->get_result()->fetch_assoc();
$total_likes = $res['total']; $user_liked = $res['user_liked']>0;
?>
<article 
  data-type="appel"
  data-date="<?= $a['date_appel'] ?>"
  data-blood="<?= $a['groupe_sanguin_receveur'] ?>"
>
  <p><strong>Appel urgent – <?= $a['groupe_sanguin_receveur'] ?></strong></p>
  <p class="meta"><?= htmlspecialchars($a['hopital']) ?> • <?= date("d M Y", strtotime($a['date_appel'])) ?></p>
  <p><?= htmlspecialchars($a['description_appel']) ?></p>

  <div class="actions">
    <button class="like-btn" data-type="<?= $type ?>" data-id="<?= $id_pub ?>">
      <span class="heart <?= $user_liked?'liked':'' ?>">❤️</span> <span class="like-count"><?= $total_likes ?></span>
    </button>
    <button onclick="openCommentModal('<?= $type ?>', <?= $id_pub ?>)">💬 Commenter</button>
    <button class="share-btn" data-type="<?= $type ?>" data-id="<?= $id_pub ?>">🔁 Partager</button>
  </div>

  <div id="comments-<?= $type ?>-<?= $id_pub ?>">
    <?php
    $comments = $conn->prepare("SELECT contenu FROM commentaires_appel WHERE id_appel=? ORDER BY id_commentaire ASC");
    $comments->bind_param("i", $id_pub);
    $comments->execute();
    $res_c = $comments->get_result();
    while($com = $res_c->fetch_assoc()){
        echo '<div class="comment">'.htmlspecialchars($com['contenu']).'</div>';
    }
    ?>
  </div>
</article>
<?php endwhile; ?>

<!-- ===================== CAMPAGNES ===================== -->
<?php while($c = mysqli_fetch_assoc($campagnes)):
$type='campagne'; $id_pub=$c['id_cmp'];
$likes_query = $conn->prepare("SELECT COUNT(*) as total, SUM(CASE WHEN id_user=? THEN 1 ELSE 0 END) as user_liked FROM likes WHERE type=? AND id_pub=?");
$likes_query->bind_param("isi",$id_user,$type,$id_pub);
$likes_query->execute();
$res = $likes_query->get_result()->fetch_assoc();
$total_likes = $res['total']; $user_liked = $res['user_liked']>0;
?>
<article 
  data-type="campagne"
  data-date="<?= $c['date_deb_cmp'] ?>"
  data-blood="all"
>

  <p><strong><?= htmlspecialchars($c['nom_cmp']) ?></strong></p>
  <p class="meta"><?= htmlspecialchars($c['lieu']) ?> • <?= $c['date_deb_cmp'] ?> → <?= $c['date_fin_cmp'] ?></p>
  <p><?= htmlspecialchars($c['description_cmp']) ?></p>
  <a href="campagnes-evenements.php?id=<?= $c['id_cmp'] ?>">Voir la campagne →</a>

  <div class="actions">
    <button class="like-btn" data-type="<?= $type ?>" data-id="<?= $id_pub ?>">
      <span class="heart <?= $user_liked?'liked':'' ?>">❤️</span> <span class="like-count"><?= $total_likes ?></span>
    </button>
    <button onclick="openCommentModal('<?= $type ?>', <?= $id_pub ?>)">💬 Commenter</button>
    <button class="share-btn" data-type="<?= $type ?>" data-id="<?= $id_pub ?>">🔁 Partager</button>
  </div>

  <div id="comments-<?= $type ?>-<?= $id_pub ?>">
    <?php
    $comments = $conn->prepare("SELECT contenu FROM commentaires_campagne WHERE id_cmp=? ORDER BY id_commentaire ASC");
    $comments->bind_param("i", $id_pub);
    $comments->execute();
    $res_c = $comments->get_result();
    while($com = $res_c->fetch_assoc()){
        echo '<div class="comment">'.htmlspecialchars($com['contenu']).'</div>';
    }

    ?>
  </div>
</article>
<?php endwhile; ?>

<!-- ===================== SENSIBILISATION ===================== -->
<?php while($i = mysqli_fetch_assoc($infos)):
$type='info'; $id_pub=$i['id'];
$likes_query = $conn->prepare("SELECT COUNT(*) as total, SUM(CASE WHEN id_user=? THEN 1 ELSE 0 END) as user_liked FROM likes WHERE type=? AND id_pub=?");
$likes_query->bind_param("isi",$id_user,$type,$id_pub);
$likes_query->execute();
$res = $likes_query->get_result()->fetch_assoc();
$total_likes = $res['total']; $user_liked = $res['user_liked']>0;
?>
<article 
  data-type="info"
  data-date="<?= $i['created_at'] ?>"
  data-blood="all">


  <p><strong><?= htmlspecialchars($i['nom']) ?> (<?= htmlspecialchars($i['role']) ?>)</strong></p>
  <p class="meta"><?= date("d M Y", strtotime($i['created_at'])) ?></p>
  <p><?= htmlspecialchars($i['histoire']) ?></p>

  <div class="actions">
    <button class="like-btn" data-type="<?= $type ?>" data-id="<?= $id_pub ?>">
      <span class="heart <?= $user_liked?'liked':'' ?>">❤️</span> <span class="like-count"><?= $total_likes ?></span>
    </button>
    <button onclick="openCommentModal('<?= $type ?>', <?= $id_pub ?>)">💬 Commenter</button>
    <button class="share-btn" data-type="<?= $type ?>" data-id="<?= $id_pub ?>">🔁 Partager</button>
  </div>

  <div id="comments-<?= $type ?>-<?= $id_pub ?>">
    <?php
    $comments = $conn->prepare("SELECT contenu FROM commentaires_info WHERE id_info=? ORDER BY id_commentaire ASC");
    $comments->bind_param("i", $id_pub);
    $comments->execute();
    $res_c = $comments->get_result();
    while($com = $res_c->fetch_assoc()){
        echo '<div class="comment">'.htmlspecialchars($com['contenu']).'</div>';
    }
    ?>
  </div>
</article>
<?php endwhile; ?>
  </section>
  <div class="extra-panel">
  <!-- Top campagnes -->
  <div class="extra-section">
    <h4>Top Campagnes</h4>
    <div class="extra-item">
      <strong>Collecte à Tunis</strong>
      <p>5 – 12 Janvier • Centre Habib Bourguiba</p>
      <a href="campagnes-evenements.php?id=1">Voir plus →</a>
    </div>
    <div class="extra-item">
      <strong>Collecte à Sfax</strong>
      <p>10 – 18 Janvier • Centre Sfax</p>
      <a href="campagnes-evenements.php?id=2">Voir plus →</a>
    </div>
  </div>

  <!-- Suggestions -->
  <div class="extra-section">
    <h4>Suggestions</h4>
    <div class="extra-item">
      <strong>Appel urgent : O+</strong>
      <p>Hôpital La Rabta, Tunis</p>
    </div>
    <div class="extra-item">
      <strong>Appel urgent : A-</strong>
      <p>Hôpital Sahloul, Sousse</p>
    </div>
  </div>

  <!-- Stats rapides -->
  <div class="extra-section">
    <h4>Stats rapides</h4>
    <div class="stat">
      <span class="stat-number">128</span>
      <span class="stat-label">Dons ce mois</span>
    </div>
    <div class="stat">
      <span class="stat-number">54</span>
      <span class="stat-label">Appels urgents actifs</span>
    </div>
    <div class="stat">
      <span class="stat-number">1024</span>
      <span class="stat-label">Utilisateurs inscrits</span>
    </div>
  </div>
</div>
</div>
</main>
<!-- ===================== footer ===================== -->
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
<!-- MODAL COMMENTAIRE -->
<div id="commentModal">
  <div class="modal-content">
    <button class="close-btn" onclick="closeCommentModal()">✖️</button>
    <h2>Ajouter un commentaire</h2>
    <textarea id="commentText" oninput="handleMention(event,this)" placeholder="Écrire un commentaire..."></textarea>
    <div id="mention-box"></div>
    <input type="hidden" id="commentType">
    <input type="hidden" id="commentId">
    <button onclick="submitComment()">Envoyer</button>
  </div>
</div>
<script src="../js/receveur.js"></script>
<script src="../js/chatbot.js"></script>
<span id="notif-data" data-count="<?= $nbNotif ?>"></span>
<script src="../js/notifications.js"></script>
</body>
</html>
