<?php
include './database.php';
include './script.php';

header('content-type:application/json');
echo json_encode(getCoefDevoir($_GET["devoir"])->fetchColumn(), JSON_UNESCAPED_UNICODE);
?>