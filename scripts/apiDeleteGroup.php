<?php
//API Pour supprimer un groupe
include './database.php';
include './script.php';

header('content-type:application/json');
if (deleteGroup($_GET['id'])) {
    echo json_encode('ok');
} else {
    echo json_encode('error');
}
?>