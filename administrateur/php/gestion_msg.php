<?php
include("connexion.php");


$id_admin = 1;
$res = mysqli_query($conn, "SELECT theme FROM admin WHERE id_admin=$id_admin");
$admin = mysqli_fetch_assoc($res);
$theme = $admin['theme'] ?? 'light';
// 1️ Récupérer les messages
$sql = "SELECT id_notif, nom_donneur, email_donneur, sujet, contenu_message, date_envoi, statu
        FROM notification
        ORDER BY date_envoi DESC";
$messages = mysqli_query($conn, $sql);

// 2️ Supprimer un message
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM notification WHERE id_notif = $id");
    header("Location: gestion_msg.php");
    exit();
}

// 3️ Toggle statut (Lu / Non lu)
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $q = mysqli_query($conn, "SELECT statu FROM notification WHERE id_notif = $id");
    $data = mysqli_fetch_assoc($q);
    $newStatus = ($data['statu'] == 'Lu') ? 'Non lu' : 'Lu';
    mysqli_query($conn, "UPDATE notification SET statu='$newStatus' WHERE id_notif = $id");
    header("Location: gestion_msg.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Messages</title>
    <link rel="stylesheet" href="../css/gestmsg.css">
    <link rel="icon" type="image/png" href="../media/logo_noir.png">
    <link rel="stylesheet" href="../css/gestutilisateur.css">
</head>
<body>
<div class="sidebar">
    <div>
        <img src="../media/logo_blanc.png" alt="" class="logo" style="width:80px;">
        <ul>
            <li><a href="dashboard.php">Tableau de bord</a></li>
            <li><a href="gestion_utilisateur.php">Utilisateurs</a></li>
            <li><a href="gestion_don.php">Dons</a></li>
            <li><a href="gestion_campagne.php">Campagnes</a></li>
            <li><a href="gestion_msg.php" class="active">Messages</a></li>
            <li><a href="parametres.php">Paramètres</a></li>
        </ul>
    </div>
    <form action="logout.php" method="POST">
        <button type="submit" class="logout-btn">Déconnexion</button>
      </form>
</div>

<div class="container">
    <h2>Boîte de réception</h2>

    <div class="search-box">
        <input type="text" id="searchInput" placeholder="Rechercher un message..." onkeyup="searchMessages()">
    </div>

    <table id="messagesTable">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Sujet</th>
                <th>Message</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($messages)): ?>
                <tr>
                    <td><?= !empty($row['nom_donneur']) ? htmlspecialchars($row['nom_donneur']) : "-" ?></td>
                    <td><?= !empty($row['email_donneur']) ? htmlspecialchars($row['email_donneur']) : "-" ?></td>
                    <td><?= !empty($row['sujet']) ? htmlspecialchars($row['sujet']) : "-" ?></td>
                    <td><?= !empty($row['contenu_message']) ? htmlspecialchars($row['contenu_message']) : "-" ?></td>
                    <td><?= $row['date_envoi'] ?></td>
                    <td><?= $row['statu'] ?></td>
                    <td>
                        <a href="gestion_msg.php?toggle=<?= $row['id_notif'] ?>">Toggle Statut</a> |
                        <a href="gestion_msg.php?delete=<?= $row['id_notif'] ?>" onclick="return confirm('Supprimer ce message ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<script src="../js/gest_msg"></script>
</body>
</html>
