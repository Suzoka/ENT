<?php
include './database.php';
include './script.php';

header('content-type:application/json');
echo json_encode(getAllProfs()->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
?>