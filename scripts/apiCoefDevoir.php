<?php
include './database.php';
include './script.php';

header("Access-Control-Allow-Origin: *");
header('content-type:application/json');
echo json_encode(getCoefDevoir($_GET["devoir"])->fetchColumn(), JSON_UNESCAPED_UNICODE);
?>