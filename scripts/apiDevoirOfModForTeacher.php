<?php
include './database.php';
include './script.php';

header("Access-Control-Allow-Origin: *");
header('content-type:application/json');
echo json_encode(apiDevoirOfModForTeacher($_GET["login"], $_GET["module"])->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
?>