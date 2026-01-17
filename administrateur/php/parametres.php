<?php
include("connexion.php");


// ID de l'admin connecté 
$id_admin = 1;


// 1. RÉCUPÉRER L'ADMIN

$sql = "SELECT * FROM admin WHERE id_admin = $id_admin";
$result = mysqli_query($conn, $sql);
$admin = mysqli_fetch_assoc($result);


// 2. METTRE À JOUR LES PARAMÈTRES

$message = "";

if (isset($_POST["save"])) {
    
    $name = $_POST["name"];
    $email = $_POST["email"];

    // Mis à jour sans mot de passe d’abord
    mysqli_query($conn, 
        "UPDATE admin SET nom_admin='$name', email_admin='$email' 
        WHERE id_admin=$id_admin"
    );

    // Si l'admin change son mot de passe
    if (!empty($_POST["newPassword"]) && $_POST["newPassword"] === $_POST["confirmPassword"]) {

        $newPass = $_POST["newPassword"];

        // Version simple = sans hash
        mysqli_query($conn, 
            "UPDATE admin SET mdp_admin='$newPass' WHERE id_admin=$id_admin"
        );

        $message = "Mot de passe mis à jour avec succès.";
    }

    $message = "Paramètres mis à jour avec succès.";

    // Recharge les nouvelles données
    header("Location: parametres.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/parametre.css">
  <link rel="stylesheet" href="../css/gestutilisateur.css">
  <link rel="icon" type="image/png" href="../media/logo_noir.png">
  <title>Paramètres</title>
</head>

<body>
<div class="sidebar">
    <div>
      <img src="../media/logo_blanc.png" alt="" class="logo" style="width:80px;">
      <ul>
        <li><a href="dashboard.php">Tableau de bord</a></li>
        <li><a href="gestion_utilisateur.php" >Utilisateurs</a></li>
        <li><a href="gestion_don.php">Dons</a></li>
        <li><a href="gestion_campagne.php">Campagnes</a></li>
        <li><a href="gestion_msg.php">Messages</a></li>
        <li><a href="parametres.php" class="active">Paramètres</a></li>
      </ul>
    </div>
    <form action="logout.php" method="POST">
        <button type="submit" class="logout-btn">Déconnexion</button>
      </form>
</div>

  <div class="container">
    <h2>Gestion des paramètres de l’administrateur</h2>

    <?php if (!empty($message)) { ?>
      <p style="color: green; font-weight:bold;"><?= $message ?></p>
    <?php } ?>

    <form method="POST">
      <!-- Informations personnelles -->
      <div class="section">
        <h3>Informations personnelles</h3>

        <label>Nom complet :</label>
        <input type="text" name="name" value="<?= $admin['nom_admin'] ?>" required>

        <label>Email :</label>
        <input type="email" name="email" value="<?= $admin['email_admin'] ?>" required>

      </div>

      <!-- Sécurité -->
      <div class="section">
        <h3>Sécurité</h3>

        <label>Nouveau mot de passe :</label>
        <input type="password" name="newPassword">

        <label>Confirmer le mot de passe :</label>
        <input type="password" name="confirmPassword">
      </div>

      <!-- Préférences (optionnel, version débutant) -->
      <div class="section">
        <h3>Préférences</h3>
        <label>Thème du tableau de bord :</label>
        <select name="theme">
          <option value="light">Clair</option>
          <option value="dark">Sombre</option>
        </select>

        <label>Notifications :</label>
        <select name="notifications">
          <option value="enabled">Activées</option>
          <option value="disabled">Désactivées</option>
        </select>
      </div>

      <button type="submit" name="save" class="btn">Enregistrer les modifications</button>
    </form>
  </div>

</body>
</html>
