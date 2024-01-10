<?php
//API Pour assigner un professeur à un module
include './database.php';
include './script.php';

header('content-type:application/json');
if (assignProf(json_decode(file_get_contents('php://input')))) {
    echo json_encode('ok');
} else {
    echo json_encode('error');
}
?>