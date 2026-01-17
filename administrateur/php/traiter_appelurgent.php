<?php
session_start();
include "connexion.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

$id_receveur = $_SESSION['id_user'];
$groupe      = $_POST['groupe'] ?? '';
$message     = $_POST['message'] ?? '';

/* Sécurité */
if (empty($groupe) || empty($message)) {
    echo "Champs manquants.";
    exit;
}

/* Vérifier s'il existe déjà un appel actif */
$check = $conn->prepare("
    SELECT id_appel 
    FROM appelurgent 
    WHERE id_receveur = ? AND actif = 1
");
$check->bind_param("i", $id_receveur);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "Vous avez déjà un appel actif.";
    exit;
}

/* Insertion */
$stmt = $conn->prepare("
    INSERT INTO appelurgent (id_receveur, groupe_sanguin, message)
    VALUES (?, ?, ?)
");
$stmt->bind_param("iss", $id_receveur, $groupe, $message);
$stmt->execute();

echo "Appel urgent publié avec succès.";
