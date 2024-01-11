<?php
//API Pour récupérer tout les étudiants existants, n'étant pas déjà inscrit à un groupe donné
include './database.php';
include './script.php';

header('content-type:application/json');
echo json_encode(getAllStudents($_GET["id"])->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
?>