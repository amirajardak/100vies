<?php
session_start();
include("connexion.php");

$response = ["count" => 0];

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'donneur') {
    echo json_encode($response);
    exit;
}

$id_donneur = (int) $_SESSION['user_id'];

$sql = "
    SELECT COUNT(*) AS total
    FROM notif
    WHERE id_user = $id_donneur
      AND role = 'donneur'
      AND lu = 0
";

$result = mysqli_query($conn, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $response["count"] = (int) $row['total'];
}

echo json_encode($response);
?>