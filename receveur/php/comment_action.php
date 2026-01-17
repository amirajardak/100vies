<?php
include("connexion.php");
session_start();
$id_user = $_SESSION['id_user'] ?? 1;

$type = $_POST['type'];
$id_pub = $_POST['id_pub'];
$contenu = $_POST['contenu'];

// Choisir la bonne table
$table = '';
$id_col = '';
switch($type){
    case 'appel': $table='commentaires_appel'; $id_col='id_appel'; break;
    case 'campagne': $table='commentaires_campagne'; $id_col='id_cmp'; break;
    case 'info': $table='commentaires_info'; $id_col='id_info'; break;
}

$stmt = $conn->prepare("INSERT INTO $table ($id_col, contenu) VALUES (?, ?)");
$stmt->bind_param("is", $id_pub, $contenu);

if($stmt->execute()){
    echo json_encode(['success'=>true]);
}else{
    echo json_encode(['success'=>false]);
}
