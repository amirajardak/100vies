<?php
session_start();
include("connexion.php");

// Sécurité
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'donneur') {
    die("Accès refusé.");
}

$id_donneur = (int) $_SESSION['user_id'];
$id_notif   = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id_notif <= 0) {
    header("Location: notifications.php");
    exit();
}

// Vérifier que la notification appartient bien au donneur
$sql = "
    SELECT lien
    FROM notif
    WHERE id_notification = $id_notif
      AND id_user = $id_donneur
      AND role = 'donneur'
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
    header("Location: " . $notif['lien']);
    exit();

}

// Fallback
header("Location: notifications.php");
exit();
