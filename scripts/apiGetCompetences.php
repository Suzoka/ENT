<?php
//API Pour récupérer toutes les compétences assignés à une classe
include './database.php';
include './script.php';

header('content-type:application/json');
echo json_encode(getCompOfClass($_GET["id"])->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
?>