<?php
include './scripts/database.php';

function checkConnectionUsr($login, $mdp)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM `utilisateurs` where username=:username && role=1");
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
    $stmt = $db->prepare("SELECT * FROM `utilisateurs` where username=:username && role=2");
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
function deconection()
{
    $_SESSION = array();
    session_destroy();
}

function getImage($id)
{
    if (file_exists("../img/pdp/pdp$id.png")) {
        return "../img/pdp/pdp$id.png";
    } else {
        return "../img/pdp/default.png";
    }
}

function getIdentite($id)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM `utilisateurs` where id=:id");
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

function sendMessage($from, $to, $message)
{
    global $db;
    $stmt = $db->prepare("INSERT INTO `messages` (`ext_id_sender`, `ext_id_receiver`, `message`, `date`) VALUES (:from, :to, :message, NOW())");
    $stmt->bindValue(':from', $from, PDO::PARAM_INT);
    $stmt->bindValue(':to', $to, PDO::PARAM_INT);
    $stmt->bindValue(':message', $message, PDO::PARAM_STR);
    $stmt->execute();
    return true;
}

function getCurrentConversation($id, $to)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM `messages` where (ext_id_sender=:id && ext_id_receiver=:to) || (ext_id_sender=:to && ext_id_receiver=:id) order by date");
    $stmt->bindValue(':to', $to, PDO::PARAM_INT);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

function getLastConversation($id) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM `messages` where ext_id_sender=:id || ext_id_receiver=:id order by date desc limit 1");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result["ext_id_sender"] == $id) {
        return $result["ext_id_receiver"];
    } else {
        return $result["ext_id_sender"];
    }
}
?>