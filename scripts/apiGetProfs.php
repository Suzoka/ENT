<?php
//API Pour récupérer tout les professeurs assignés à un module
include './database.php';
include './script.php';

header('content-type:application/json');
echo json_encode(getAllProfsOfMod($_GET["id"])->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
?>