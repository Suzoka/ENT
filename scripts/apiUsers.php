<?php
include './database.php';
include './script.php';

header('content-type:application/json');
echo json_encode(getUsers($_GET["recherche"])->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
?>