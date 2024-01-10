<?php
include './database.php';
include './script.php';

header('content-type:application/json');
if (deleteProfOfMod($_GET['idProf'], $_GET['idMod'])) {
    echo json_encode('ok');
} else {
    echo json_encode('error');
}
?>