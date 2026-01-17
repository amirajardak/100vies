<?php
// Connexion à la base
include("connexion.php");

$id_admin = 1;
$res = mysqli_query($conn, "SELECT theme FROM admin WHERE id_admin=$id_admin");
$admin = mysqli_fetch_assoc($res);
$theme = $admin['theme'] ?? 'light';
// AFFICHAGE DES DONS

$sql = "SELECT don.id_don, donneur.nom, donneur.prenom, donneur.grp_sanguin, don.date_don, centre.nom_centre
        FROM don
        LEFT JOIN donneur ON don.id_donneur = donneur.id_donneur
        LEFT JOIN centre ON don.id_centre = centre.id_centre";

$result = mysqli_query($conn, $sql);
// AJOUT D’UN DON

if (isset($_POST["addDon"])) {
    $donneur = $_POST["donneur"];
    $groupe = $_POST["groupe"];
    $date = $_POST["datenais"];
    $centre = $_POST["adresse"];

    // Insérer le donneur
    mysqli_query($conn, "INSERT INTO donneur(nom, grp_sanguin,datenais ,adresse ) VALUES('$donneur', '$groupe','$date' ,'$centre')");
    $idDonneur = mysqli_insert_id($conn);

    // Insérer le centre
    mysqli_query($conn, "INSERT INTO centre(nom_centre) VALUES('$centre')");
    $idCentre = mysqli_insert_id($conn);

    // Insérer le don
    mysqli_query($conn, "INSERT INTO don(date_don, id_donneur, id_centre) 
                         VALUES('$date', '$idDonneur', '$idCentre')");
    header("Location: gestion_don.php");
    exit();
}


// SUPPRESSION D’UN DON

if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    mysqli_query($conn, "DELETE FROM don WHERE id_don = $id");
    header("Location: gestion_don.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/png" href="../media/logo_noir.png">
  <link rel="stylesheet" href="../css/stylegest.css">
  <title>Gestion des dons </title>
</head>
<body>
  <div class="sidebar">
    <div>
      <img src="../media/logo_blanc.png" alt="" class="logo" style="width:80px;">
      <ul>
        <li><a href="dashboard.php">Tableau de bord</a></li>
        <li><a href="gestion_utilisateur.php" >Utilisateurs</a></li>
        <li><a href="gestion_don.php" class="active">Dons</a></li>
        <li><a href="gestion_campagne.php">Campagnes</a></li>
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
      <h1>Gestion des dons</h1>
    </div>
    <div class="filter-section">
      <select id="filterBlood">
        <option value="">Filtrer par groupe sanguin</option>
        <option>O+</option>
        <option>O-</option>
        <option>A+</option>
        <option>A-</option>
        <option>B+</option>
        <option>B-</option>
        <option>AB+</option>
        <option>AB-</option>
      </select>
      <input type="date" id="filterDate" />
      <button class="add-donation-btn" onclick="openModal()">+ Ajouter un don</button>
    </div>
    <table id="donTable">
      <thead>
        <tr>
          <th>Nom du donneur</th>
          <th>Groupe sanguin</th>
          <th>Date du don</th>
          <th>Centre de collecte</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
          <td><?php echo $row["nom"] . " " . $row["prenom"]; ?></td>
          <td><?php echo $row["grp_sanguin"]; ?></td>
          <td><?php echo $row["date_don"]; ?></td>
          <td><?php echo $row["nom_centre"]; ?></td>
          <td>
            <a href="gestion_don.php?delete=<?php echo $row['id_don']; ?>" 
               class="delete-btn"
               onclick="return confirm('Voulez-vous supprimer ce don ?')">
               Supprimer
            </a>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
  <!-- MODAL AJOUT DON -->
  <div class="modal" id="addDonModal">
    <div class="modal-content">
      <h2>Ajouter un don</h2>
      <form method="POST">
        <input type="text" name="donneur" placeholder="Nom du donneur" required>
        <select name="groupe" required>
          <option value="">Groupe sanguin</option>
          <option>O+</option>
          <option>O-</option>
          <option>A+</option>
          <option>A-</option>
          <option>B+</option>
          <option>B-</option>
          <option>AB+</option>
          <option>AB-</option>
        </select>
        <input type="date" name="datenais" required>
        <input type="text" name="adresse" placeholder="Centre de collecte" required>
        <button type="submit" name="addDon">Ajouter</button>
      </form>
    </div>
  </div>
  <script src="../js/gestion_don.js"></script>
</body>
</html>