<?php
//API Pour récupérer toutes les classes existantes
include './database.php';
include './script.php';

header('content-type:application/json');
echo json_encode(getAllClass()->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
?>