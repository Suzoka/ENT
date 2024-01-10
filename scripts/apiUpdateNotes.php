<?php
//API Pour mettre à jour les notes d'un devoir, ou son coefficient
include './database.php';
include './script.php';

header('content-type:application/json');
if (updateNotes($_GET["devoir"], json_decode(file_get_contents('php://input')))) {
    echo json_encode('ok');
} else {
    echo json_encode('error');
}
?>