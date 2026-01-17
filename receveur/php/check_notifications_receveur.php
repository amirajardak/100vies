<?php
session_start();
include("connexion.php");

$response = ["count" => 0];

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? null) !== 'receveur') {
    echo json_encode($response);
    exit;
}

$id_receveur = (int) $_SESSION['user_id'];

$sql = "
    SELECT COUNT(*) AS total
    FROM notif
    WHERE id_user = $id_receveur
      AND role = 'receveur'
      AND lu = 0
";

$result = mysqli_query($conn, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $response["count"] = (int) ($row['total'] ?? 0);
}

echo json_encode($response);
?>
