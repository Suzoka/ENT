<?php
//API Pour créer une nouvelle compétence au sein d'une classe
include './database.php';
include './script.php';

header('content-type:application/json');
if (createComp(json_decode(file_get_contents('php://input')))) {
    echo json_encode('ok');
} else {
    echo json_encode('error');
}
?>