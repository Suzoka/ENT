<?php
include './database.php';
include './script.php';

header('content-type:application/json');
echo json_encode(getGroupesOfClass($_GET["id"])->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
?>