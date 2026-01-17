<?php
session_start();
include("connexion.php");

// Sécurité
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? null) !== 'receveur') {
    die("Accès refusé.");
}

$id_receveur = (int) $_SESSION['user_id'];
$id_notif   = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id_notif <= 0) {
    header("Location: notifications_receveur.php");
    exit();
}

// Vérifier que la notification appartient bien au receveur
$sql = "
    SELECT lien
    FROM notif
    WHERE id_notification = $id_notif
      AND id_user = $id_receveur
      AND role = 'receveur'
";

$res = mysqli_query($conn, $sql);

if ($res && mysqli_num_rows($res) === 1) {

    $notif = mysqli_fetch_assoc($res);

    // Marquer comme lue
    mysqli_query($conn, "
        UPDATE notif
        SET lu = 1
        WHERE id_notification = $id_notif
    ");

    // Redirection vers la vraie page
    $target = $notif['lien'] ?? 'notifications_receveur.php';
    header("Location: " . $target);
    exit();
}

// Fallback
header("Location: notifications_receveur.php");
exit();
