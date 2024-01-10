<?php
include './database.php';
include './script.php';

header('content-type:application/json');
if (deleteStudentOfGroup($_GET['idStudent'], $_GET['idGroupe'])) {
    echo json_encode('ok');
} else {
    echo json_encode('error');
}
?>