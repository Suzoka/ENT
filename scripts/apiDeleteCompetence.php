<?php
include './database.php';
include './script.php';

header('content-type:application/json');
echo json_encode(deleteCompetence($_GET["id"]), JSON_UNESCAPED_UNICODE);
?>