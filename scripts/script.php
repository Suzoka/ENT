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
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
?>