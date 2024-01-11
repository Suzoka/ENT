<?php
//API Pour supprimer une classe
include './database.php';
include './script.php';

header('content-type:application/json');
if (deleteClass($_GET["id"])) {
    echo json_encode('ok');
} else {
    echo json_encode('error');
}
?>