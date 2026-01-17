<?php
include("connexion.php");


$id_admin = 1;
$res = mysqli_query($conn, "SELECT theme FROM admin WHERE id_admin=$id_admin");
$admin = mysqli_fetch_assoc($res);
$theme = $admin['theme'] ?? 'light';
// 1. RÉCUPÉRATION DES DONNEURS
$sql = "SELECT * FROM donneur";
$users = mysqli_query($conn, $sql);

// 2. AJOUT D’UN DONNEUR
if (isset($_POST["addUser"])) {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $datenais = $_POST["datenais"];
    $sexe = $_POST["sexe"];
    $grp_sanguin = $_POST["grp_sanguin"];
    $email = $_POST["email"];
    $mdp = $_POST["mdp"];
    $adresse = $_POST["adresse"];
    $sql = "INSERT INTO donneur (nom, prenom, datenais, sexe, grp_sanguin, email, mdp, adresse)
            VALUES ('$nom', '$prenom', '$datenais', '$sexe', '$grp_sanguin', '$email', '$mdp', '$adresse')";
    mysqli_query($conn, $sql);
    header("Location: gestion_utilisateur.php");
    exit();
}

// 3. SUPPRESSION
if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    mysqli_query($conn, "DELETE FROM donneur WHERE id_donneur = $id");
    header("Location: gestion_utilisateur.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/png" href="../media/logo_noir.png">
  <link rel="stylesheet" href="../css/gestutilisateur.css">
  <title>Gestion des utilisateurs</title>
</head>
<body>
  <div class="sidebar">
    <div>
      <img src="../media/logo_blanc.png" alt="" class="logo" style="width:80px;">
      <ul>
        <li><a href="dashboard.php">Tableau de bord</a></li>
        <li><a href="gestion_utilisateur.php" class="active">Utilisateurs</a></li>
        <li><a href="gestion_don.php">Dons</a></li>
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
    <h1>Gestion des utilisateurs</h1>
    <button class="add-user-btn" onclick="openModal()">+ Ajouter un donneur</button>
    <table>
      <thead>
        <tr>
          <th>Nom</th>
          <th>Email</th>
          <th>Groupe sanguin</th>
          <th>Ville</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($users)) { ?>
        <tr>
          <td><?php echo $row["nom"] . " " . $row["prenom"]; ?></td>
          <td><?php echo $row["email"]; ?></td>
          <td><?php echo $row["grp_sanguin"]; ?></td>
          <td><?php echo $row["adresse"]; ?></td>
          <td>
            <a class="delete-btn"
               href="gestion_utilisateur.php?delete=<?php echo $row['id_donneur']; ?>"
               onclick="return confirm('Supprimer ce donneur ?')">
               Supprimer
            </a>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
  <!-- MODAL AJOUT D’UTILISATEUR -->
  <div class="modal" id="addUserModal">
    <div class="modal-content">
  <h2>Ajouter un donneur</h2>
  <form method="POST">
    <input type="text" name="nom" placeholder="Nom" required>
    <input type="text" name="prenom" placeholder="Prénom" required>
    <label>Date de naissance :</label>
    <input type="date" name="datenais" required>
    <select name="sexe" required>
      <option value="" selected disabled>Sexe</option>
      <option value="F">Féminin</option>
      <option value="M">Masculin</option>
    </select>
    <select name="grp_sanguin" required>
      <option value="" selected disabled>Groupe sanguin</option>
      <option>A+</option><option>A-</option>
      <option>B+</option><option>B-</option>
      <option>AB+</option><option>AB-</option>
      <option>O+</option><option>O-</option>
    </select>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="mdp" placeholder="Mot de passe" required>
    <input type="text" name="adresse" placeholder="Adresse" required>
    <button type="submit" name="addUser">Ajouter</button>
  </form>
</div>
  </div>
  <script src="../js/gestion_utilisateur.js"></script>
</body>
</html>