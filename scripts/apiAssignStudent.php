<?php
//API Pour assigner un étudiant à un groupe
include './database.php';
include './script.php';

header('content-type:application/json');
if (assignStudent(json_decode(file_get_contents('php://input')))) {
    echo json_encode('ok');
} else {
    echo json_encode('error');
}
?>