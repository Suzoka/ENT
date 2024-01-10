<?php
//API Pour créer un nouveau devoir
include './database.php';
include './script.php';

header('content-type:application/json');
if (createDevoir(json_decode(file_get_contents('php://input')))) {
    echo json_encode('ok');
} else {
    echo json_encode('error');
}
?>