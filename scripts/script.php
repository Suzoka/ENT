<?php
include './scripts/database.php';

function checkConnectionUsr($login, $mdp)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM `etudiants` where username=:username");
    $stmt->bindValue(':username', $login, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->rowCount();
    if ($count) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($mdp, $result["password"])) {
            $_SESSION['login'] = $result["id"];
            $_SESSION['profil'] = "usr";
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function checkConnectionProf($login, $mdp)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM `enseignants` where username=:username");
    $stmt->bindValue(':username', $login, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->rowCount();
    if ($count) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($mdp, $result["password"])) {
            $_SESSION['login'] = $result["id"];
            $_SESSION['profil'] = "prof";
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
function deconection()
{
    $_SESSION = array();
    session_destroy();
}

function getImage($id, $profil)
{
    if ($profil == "usr") {
        return getImageUsr($id);
    } else {
        return getImageProf($id);
    }
}

function getImageUsr($id)
{
    if (file_exists("../img/pdp/usr/pdp$id.png")) {
        return "../img/pdp/usr/pdp$id.png";
    } else {
        return "../img/pdp/default.png";
    }
}

function getImageProf($id)
{
    if (file_exists("../img/pdp/prof/pdp$id.png")) {
        return "../img/pdp/prof/pdp$id.png";
    } else {
        return "../img/pdp/default.png";
    }
}

function getIdentite($id, $profil)
{
    global $db;
    if ($_SESSION['profil'] == "usr") {
        $stmt = $db->prepare("SELECT * FROM `etudiants` where id=:id");
    } else if ($_SESSION['profil'] == "prof") {
        $stmt = $db->prepare("SELECT * FROM `enseignants` where id=:id");
    }
    else{
        return "Erreur";
    }
    $stmt->bindValue(':id', $id, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result["prenom"] . " " . $result["nom"];
}

function getClasses($id)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM `groupes` g inner join `classes` c on g.ext_id_classe = c.id_classe inner join `promotions` p on p.ext_id_groupe = g.id_groupe where p.ext_id_usr=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($result) == 1) {
        return $result[0]["nom_classe"] . " - " . $result[0]["nom_groupe"];
    } else {
        $classes = "";
        foreach ($result as $key => $row) {
            $classes .= $row["nom_classe"];
            if ($key != sizeof($result) - 1) {
                $classes .= "; ";
            }
        }
        return $classes;
    }
}
?>