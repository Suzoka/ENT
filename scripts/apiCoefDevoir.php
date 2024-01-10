<?php
//API Pour récupérer le coefficient d'un devoir
include './database.php';
include './script.php';

header('content-type:application/json');
echo json_encode(getCoefDevoir($_GET["devoir"])->fetchColumn(), JSON_UNESCAPED_UNICODE);
?>