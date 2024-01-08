<?php
include './database.php';
include './script.php';

header('content-type:application/json');
echo json_encode(getAllModOfClasseWhereTeacherIsJury($_GET["login"], $_GET["classe"])->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
?>