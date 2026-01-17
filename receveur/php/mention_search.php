<?php
include("connexion.php");

$q = "%".$_GET['q']."%";

$res = $conn->prepare("
    SELECT nom FROM donneur WHERE nom LIKE ?
    UNION
    SELECT nom_receveur FROM receveur WHERE nom_receveur LIKE ?
    LIMIT 5
");
$res->bind_param("ss",$q,$q);
$res->execute();

$data = [];
$r = $res->get_result();
while($row = $r->fetch_assoc()){
    $data[] = ["nom"=>$row[array_key_first($row)]];
}

echo json_encode($data);
