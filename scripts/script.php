<?php

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

function getAllConversation($id) {
    global $db;
    $stmt = $db->prepare("SELECT other_user_id, date, message FROM (SELECT CASE WHEN ext_id_sender = :id THEN ext_id_receiver ELSE ext_id_sender END AS other_user_id, MAX(date) as max_date FROM (SELECT * FROM messages WHERE ext_id_sender = :id UNION ALL SELECT * FROM messages WHERE ext_id_receiver = :id) AS subquery GROUP BY other_user_id) AS lasts_messages inner JOIN messages ON messages.date = lasts_messages.max_date && ((messages.ext_id_sender = :id && messages.ext_id_receiver = lasts_messages.other_user_id)||(messages.ext_id_receiver = :id && messages.ext_id_sender = lasts_messages.other_user_id)) ORDER BY date DESC;");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

function getUsers($recherche)
{
    global $db;
    $stmt = $db->prepare("SELECT concat(prenom, ' ', nom) AS identite, id FROM `utilisateurs` where concat(prenom, ' ', nom) like :recherche;");
    $stmt->bindValue(':recherche', "%".$recherche."%", PDO::PARAM_STR);
    $stmt->execute();
    return $stmt;
}

function getCompetences($id)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM `competences` co inner join `classes` cl on co.ext_id_classe = cl.id_classe inner join `groupes` gr on gr.ext_id_classe = cl.id_classe inner join `promotions` pr on pr.ext_id_groupe = gr.id_groupe where pr.ext_id_usr=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

function getMoyenneComp($id, $student){
    global $db;
    $stmt = $db->prepare("SELECT * from `modules` m inner join `coef_modules` cm on m.id_module = cm.ext_id_module inner join `competences` c on cm.ext_id_competence = c.id_competence where c.id_competence=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $moyenne = 0;
    $diviseur = 0;
    foreach ($result as $module) {
        $moyenne += getMoyenneMod($module["id_module"], $student)*$module["coef_module"];
        $diviseur += 20*$module["coef_module"];
    }
    return (($moyenne/$diviseur)*20);
}

function getMoyenneMod($id, $student){
    global $db;
    $stmt = $db->prepare("SELECT * from `notes` n inner join `devoirs` d on n.ext_id_devoir = d.id_devoir inner join `modules` m on d.ext_id_module = m.id_module where m.id_module=:id && n.ext_id_student=:student");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':student', $student, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $moyenne = 0;
    $diviseur = 0;
    foreach ($result as $note) {
        $moyenne += $note["valeur"]*$note["coef_devoir"];
        $diviseur += 20*$note["coef_devoir"];
    }
    return (($moyenne/$diviseur)*20);
}
?>