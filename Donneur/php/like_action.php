<?php
include("connexion.php");
session_start();
$id_user = $_SESSION['id_user'] ?? 1; // utilisateur connecté

header('Content-Type: application/json');

$type = $_POST['type'] ?? '';
$id_pub = $_POST['id_pub'] ?? '';

if(!$type || !$id_pub){
    echo json_encode(['success'=>false,'error'=>'Paramètres manquants']);
    exit;
}

// Vérifier si l'utilisateur a déjà liké
$stmt = $conn->prepare("SELECT * FROM likes WHERE type=? AND id_pub=? AND id_user=?");
$stmt->bind_param("sii",$type,$id_pub,$id_user);
$stmt->execute();
$res = $stmt->get_result();

if($res->num_rows > 0){
    // Supprimer le like
    $del = $conn->prepare("DELETE FROM likes WHERE type=? AND id_pub=? AND id_user=?");
    $del->bind_param("sii",$type,$id_pub,$id_user);
    $del->execute();
    $user_liked = false;
} else {
    // Ajouter le like
    $ins = $conn->prepare("INSERT INTO likes (type,id_pub,id_user) VALUES (?,?,?)");
    $ins->bind_param("sii",$type,$id_pub,$id_user);
    $ins->execute();
    $user_liked = true;
}

// Recalculer le total
$total = $conn->prepare("SELECT COUNT(*) as total FROM likes WHERE type=? AND id_pub=?");
$total->bind_param("si",$type,$id_pub);
$total->execute();
$total_res = $total->get_result()->fetch_assoc();

echo json_encode([
    'success'=>true,
    'total'=>$total_res['total'],
    'user_liked'=>$user_liked
]);
