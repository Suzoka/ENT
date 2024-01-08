<?php
include './database.php';
include './script.php';

header('content-type:application/json');
echo json_encode(getAllClassOfTeacher($_GET["login"])->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
?>