<?php
//API Pour récupérer toutes les notes enregistrées pour un devoir donné à une classe
include './database.php';
include './script.php';

header('content-type:application/json');
echo json_encode(getNotesOfWork($_GET["devoir"], $_GET["classe"])->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
?>