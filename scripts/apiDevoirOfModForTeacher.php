<?php
//API Pour récupérer les devoirs crées par un professeur, qui comptent pour un module donné
include './database.php';
include './script.php';

header('content-type:application/json');
echo json_encode(apiDevoirOfModForTeacher($_GET["login"], $_GET["module"])->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
?>