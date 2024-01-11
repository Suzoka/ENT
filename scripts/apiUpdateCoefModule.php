<?php
//API Pour mettre à jour les coefficients entre un module et une compétence (mise à jour de coefficient, ajout de lien entre un module et une compétence, et suppression de lien entre un module et une compétence)
include './database.php';
include './script.php';

header('content-type:application/json');
if (updateCoefModule(json_decode(file_get_contents('php://input')))) {
    echo json_encode('ok');
} else {
    echo json_encode('error');
}
?>