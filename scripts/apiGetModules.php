<?php
//API Pour récupérer tout les modules assignés à une classe
include './database.php';
include './script.php';

header('content-type:application/json');
echo json_encode(getAllModOfClass($_GET["id"])->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
?>