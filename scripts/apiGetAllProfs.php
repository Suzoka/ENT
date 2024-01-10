<?php
//API Pour récupérer tous les professeurs existants, n'étant pas déjà assigné à un module donné
include './database.php';
include './script.php';

header('content-type:application/json');
echo json_encode(getAllProfs($_GET["id"])->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
?>