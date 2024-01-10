<?php
//API Pour créer une nouvelle classe
include './database.php';
include './script.php';

header('content-type:application/json');
if (createClass(json_decode(file_get_contents('php://input')))) {
    echo json_encode('ok');
} else {
    echo json_encode('error');
}
?>