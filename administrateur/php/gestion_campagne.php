<?php
include("connexion.php");

$id_admin = 1;
$res = mysqli_query($conn, "SELECT theme FROM admin WHERE id_admin=$id_admin");
$admin = mysqli_fetch_assoc($res);
$theme = $admin['theme'] ?? 'light';

// 1. RÉCUPÉRER LES CAMPAGNES
$sql = "SELECT * FROM campagne";
$events = mysqli_query($conn, $sql);

// 2. AJOUT D’ÉVÉNEMENT (CAMPAGNE) + NOTIFICATIONS RECEVEURS
if (isset($_POST["addEvent"])) {
    // sécuriser un minimum (évite casse quotes)
    $titre    = mysqli_real_escape_string($conn, $_POST["nom_cmp"] ?? "");
    $date_deb = mysqli_real_escape_string($conn, $_POST["date_deb_cmp"] ?? "");
    $date_fin = mysqli_real_escape_string($conn, $_POST["date_fin_cmp"] ?? "");
    $lieu     = mysqli_real_escape_string($conn, $_POST["lieu"] ?? "");
    $desc     = mysqli_real_escape_string($conn, $_POST["description_cmp"] ?? "");
    $status   = mysqli_real_escape_string($conn, $_POST["status_cmp"] ?? "");
    $centre   = (int)($_POST["id_centre"] ?? 0);

    // 2.1 Insertion campagne D'ABORD
    mysqli_query($conn,
        "INSERT INTO campagne(nom_cmp, date_deb_cmp, date_fin_cmp, lieu, description_cmp, status_cmp, id_centre)
         VALUES ('$titre', '$date_deb', '$date_fin', '$lieu', '$desc', '$status', $centre)"
    );

    // 2.2 Récupérer l'id de la campagne nouvellement créée APRÈS l'INSERT
    $id_campagne = mysqli_insert_id($conn);

    // 2.3 Notif pour TOUS les receveurs
    // Ta table notif utilise lu (0/1), pas "statut"
    $titreNotif = mysqli_real_escape_string($conn, "Nouvelle campagne");
    $message    = mysqli_real_escape_string($conn, "Nouvelle campagne disponible : " . $titre);

    // Lien : tu peux choisir ce que tu veux ouvrir
    // - soit la page des campagnes
    // $lien = mysqli_real_escape_string($conn, "campagnes-evenements.php");
    // - soit directement participer (plus logique dans ton projet)
    $lien = mysqli_real_escape_string($conn, "participer.php?id=" . $id_campagne);

    $sqlNotif = "
        INSERT INTO notif (id_user, role, titre, message, lien, date_notif, lu)
        SELECT id_receveur, 'receveur', '$titreNotif', '$message', '$lien', NOW(), 0
        FROM receveur
    ";
    mysqli_query($conn, $sqlNotif);

    header("Location: gestion_campagne.php");
    exit();
}

// 3. SUPPRESSION
if (isset($_GET["delete"])) {
    $id = (int)$_GET["delete"];
    mysqli_query($conn, "DELETE FROM campagne WHERE id_cmp = $id");
    header("Location: gestion_campagne.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/png" href="../media/logo_noir.png">
  <link rel="stylesheet" href="../css/gestionevenement.css">
  <title>Gestion des campagnes</title>
</head>
<body>
 <div class="sidebar">
    <div>
      <img src="../media/logo_blanc.png" alt="" class="logo" style="width:80px;">
      <ul>
        <li><a href="dashboard.php">Tableau de bord</a></li>
        <li><a href="gestion_utilisateur.php" >Utilisateurs</a></li>
        <li><a href="gestion_don.php">Dons</a></li>
        <li><a href="gestion_campagne.php" class="active">Campagnes</a></li>
        <li><a href="gestion_msg.php">Messages</a></li>
        <li><a href="parametres.php">Paramètres</a></li>
      </ul>
    </div>
    <form action="logout.php" method="POST">
        <button type="submit" class="logout-btn">Déconnexion</button>
      </form>
  </div>

  <div class="main">
    <div class="header">
      <h1>Gestion des campagnes</h1>

    </div>
    <div class="filter-section">
      <input type="text" id="filterLieu" placeholder="Filtrer par lieu" />
      <input type="date" id="filterDate" />
      <button class="add-event-btn" onclick="openModal()">+ Ajouter une campagne</button>
    </div>

    <table id="eventTable">
      <thead>
        <tr>
          <th>Titre</th>
          <th>Date</th>
          <th>Lieu</th>
          <th>Description</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>

        <?php while ($row = mysqli_fetch_assoc($events)) { ?>
        <tr>
          <td><?php echo $row["nom_cmp"]; ?></td>
          <td><?php echo $row["date_deb_cmp"]; ?></td>
          <td><?php echo $row["date_fin_cmp"]; ?></td>
          <td><?php echo $row["lieu"]; ?></td>
          <td><?php echo $row["description_cmp"]; ?></td>
          <td><?php echo $row["status_cmp"]; ?></td>
          <td><?php echo $row["id_centre"]; ?></td>
          <td>
            <a href="gestion_campagne.php?delete=<?php echo $row['id_cmp']; ?>"
               class="delete-btn"
               onclick="return confirm('Supprimer cet événement ?')">
               Supprimer
            </a>
          </td>
        </tr>
        <?php } ?>

      </tbody>
    </table>
  </div>

  <!--AJOUT ÉVÉNEMENT -->
  <div class="modal" id="addEventModal">
  <div class="modal-content">
    <h2>Ajouter un événement</h2>

    <form method="POST">

      <input type="text" name="nom_cmp" placeholder="Nom de la campagne" required>

      <label>Date de début :</label>
      <input type="date" name="date_deb_cmp" required>

      <label>Date de fin :</label>
      <input type="date" name="date_fin_cmp" required>

      <input type="text" name="lieu" placeholder="Lieu" required>

      <textarea name="description_cmp" rows="3" placeholder="Description" required></textarea>

      <select name="status_cmp" required>
        <option value="" disabled selected>Status</option>
        <option value="Prévu">Prévu</option>
        <option value="En cours">En cours</option>
        <option value="Terminée">Terminée</option>
      </select>

      <input type="number" name="id_centre" placeholder="ID du centre" required>

      <button type="submit" name="addEvent">Ajouter</button>
    </form>

  </div>
</div>

<script src="../js/gestion_campagne.js"></script>
</body>
</html>
