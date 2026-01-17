<?php
include("connexion.php");
$id_admin = 1;
$res = mysqli_query($conn, "SELECT theme FROM admin WHERE id_admin=$id_admin");
$admin = mysqli_fetch_assoc($res);
$theme = $admin['theme'] ?? 'light';
// Total donneurs
$nbr_donneurs = $conn->query("SELECT COUNT(*) AS total FROM donneur")->fetch_assoc()["total"];

// Dons du mois (exemple)
$nbr_dons = $conn->query("SELECT COUNT(*) AS total FROM don")->fetch_assoc()["total"];

// Événements à venir
$nbr_events = $conn->query("SELECT COUNT(*) AS total FROM campagne WHERE status_cmp='Prévu'")->fetch_assoc()["total"];

// Dons récents (3 derniers)
$dons_recents = $conn->query("
    SELECT d.date_don, d.heure, d.type_don, d.etat_don, dn.nom, dn.grp_sanguin
    FROM don d 
    LEFT JOIN donneur dn ON d.id_donneur = dn.id_donneur
    ORDER BY d.id_don DESC
    LIMIT 3
");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/styledashboard.css">
  <link rel="stylesheet" href="../css/stylegest.css">
  <link rel="icon" type="image/png" href="../media/logo_noir.png">
  <title>Admin </title>
  </head>
<body>
 <div class="sidebar">
    <div>
      <img src="../media/logo_blanc.png" alt="" class="logo" style="width:80px;">
      <ul>
        <li><a href="dashboard.php" class="active">Tableau de bord</a></li>
        <li><a href="gestion_utilisateur.php" >Utilisateurs</a></li>
        <li><a href="gestion_don.php">Dons</a></li>
        <li><a href="gestion_campagne.php">Campagnes</a></li>
        <li><a href="gestion_msg.php">Messages</a></li>
        <li><a href="parametres.php">Paramètres</a></li>
      </ul>
    </div>
  </div>
  <div class="main">
    <div class="header">
      <h2>Tableau de bord</h2>
      <form action="logout.php" method="POST">
        <button type="submit" class="logout-btn">Déconnexion</button>
      </form>
    </div>
    <div class="stats">
      <div class="card">
        <h3>Total donneurs</h3>
        <p><?php echo $nbr_donneurs; ?></p>
      </div>
      <div class="card">
        <h3>Dons ce mois</h3>
        <p><?php echo $nbr_donneurs; ?></p>
      </div>
      <div class="card">
        <h3>Stocks critiques</h3>
        <p>A-, B-</p>
      </div>
      <div class="card">
        <h3>Événements à venir</h3>
        <p><?php echo $nbr_events; ?></p>
      </div>
    </div>
    <h3>Dons récents</h3>
    <table>
  <tr>
    <th>Donneur</th>
    <th>Groupe</th>
    <th>Date</th>
    <th>Heure</th>
    <th>Statut</th>
</tr>
  <?php while ($row = $dons_recents->fetch_assoc()) { ?>
      <tr>
        <td><?php echo $row['nom']; ?></td>
        <td><?php echo $row['grp_sanguin']; ?></td>
        <td><?php echo $row['date_don']; ?></td>
        <td><?php echo $row['heure']; ?></td>
        <td><?php echo $row['etat_don']; ?></td>
      </tr>
  <?php } ?>
</table>
  </div>
</body>
</html>