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
    if (file_exists("./img/pdp/pdp$id.png")) {
        return "../img/pdp/pdp$id.png";
    } else {
        return "../img/pdp/default.png";
    }
}

function getIdentite($id)
{
    global $db;
    $stmt = $db->prepare("SELECT prenom, nom FROM `utilisateurs` where id=:id");
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

function getLastConversation($id)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM `messages` where ext_id_sender=:id || ext_id_receiver=:id order by date desc limit 1");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result == null) {
        return null;
    }
    if ($result["ext_id_sender"] == $id) {
        return $result["ext_id_receiver"];
    } else {
        return $result["ext_id_sender"];
    }
}

function getAllConversation($id)
{
    global $db;
    $stmt = $db->prepare("SELECT other_user_id, date, message FROM (SELECT CASE WHEN ext_id_sender = :id THEN ext_id_receiver ELSE ext_id_sender END AS other_user_id, MAX(date) as max_date FROM (SELECT * FROM messages WHERE ext_id_sender = :id UNION ALL SELECT * FROM messages WHERE ext_id_receiver = :id) AS subquery GROUP BY other_user_id) AS lasts_messages inner JOIN messages ON messages.date = lasts_messages.max_date && ((messages.ext_id_sender = :id && messages.ext_id_receiver = lasts_messages.other_user_id)||(messages.ext_id_receiver = :id && messages.ext_id_sender = lasts_messages.other_user_id)) ORDER BY date DESC;");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

function getUsers($recherche)
{
    global $db;
    $stmt = $db->prepare("SELECT concat(prenom, ' ', nom) AS identite, id FROM `utilisateurs` where concat(prenom, ' ', nom) like :recherche order by concat(prenom, ' ', nom) limit 5;");
    $stmt->bindValue(':recherche', "%" . $recherche . "%", PDO::PARAM_STR);
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

function getMoyenneComp($id, $student)
{
    global $db;
    $stmt = $db->prepare("SELECT * from `modules` m inner join `coef_modules` cm on m.id_module = cm.ext_id_module inner join `competences` c on cm.ext_id_competence = c.id_competence where c.id_competence=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $moyenne = 0;
    $diviseur = 0;
    $testNull = null;
    foreach ($result as $module) {
        $moyenneMod = getMoyenneMod($module["id_module"], $student);
        $testNull += $moyenneMod;
        if ($moyenneMod == null) {
            continue;
        }
        $moyenne += $moyenneMod * $module["coef_module"];
        $diviseur += 20 * $module["coef_module"];
    }
    if ($testNull == 0) {
        return "NN";
    }
    return (($moyenne / $diviseur) * 20);
}

function getMoyenneMod($id, $student)
{
    global $db;
    $stmt = $db->prepare("SELECT * from `notes` n inner join `devoirs` d on n.ext_id_devoir = d.id_devoir inner join `modules` m on d.ext_id_module = m.id_module where m.id_module=:id && n.ext_id_student=:student");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':student', $student, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
        return null;
    } else {
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $moyenne = 0;
        $diviseur = 0;
        foreach ($result as $note) {
            $moyenne += $note["valeur"] * $note["coef_devoir"];
            $diviseur += 20 * $note["coef_devoir"];
        }
        return (($moyenne / $diviseur) * 20);
    }
}

function getAllModsOfComp($id)
{
    global $db;
    $stmt = $db->prepare("SELECT * from `modules` m inner join `coef_modules` cm on m.id_module = cm.ext_id_module where cm.ext_id_competence=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

function formatNote($note)
{
    $formatedNote = round($note, 2);
    if ($note < 10) {
        return ("0" . number_format($formatedNote, 2, ',', ''));
    } else {
        return (number_format($formatedNote, 2, ',', ''));
    }
}

function getAllDevoirsOfMod($id, $student)
{
    global $db;
    $stmt = $db->prepare("SELECT * from `devoirs` d inner join `notes` n on d.id_devoir = n.ext_id_devoir where d.ext_id_module=:id && n.ext_id_student=:student");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':student', $student, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

function getRole($id)
{
    global $db;
    $stmt = $db->prepare("select role from `utilisateurs` where id=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

function getAllClassOfTeacher($id)
{
    global $db;
    $stmt = $db->prepare("select * from `classes` cl inner join `competences` co on cl.id_classe = co.ext_id_classe inner join `coef_modules` cm on co.id_competence = cm.ext_id_competence inner join `modules` mo on cm.ext_id_module = mo.id_module inner join `jury` ju on mo.id_module = ju.ext_id_module where ju.ext_id_prof=:id GROUP BY cl.id_classe;");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

function getAllModOfClasseWhereTeacherIsJury($id, $classe)
{
    global $db;
    $stmt = $db->prepare("select * from `modules` mo inner join `jury` ju on mo.id_module = ju.ext_id_module inner join `coef_modules` cm on mo.id_module = cm.ext_id_module inner join `competences` co on cm.ext_id_competence = co.id_competence inner join `classes` cl on co.ext_id_classe = cl.id_classe where ju.ext_id_prof=:id && cl.id_classe=:classe GROUP BY mo.id_module;");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':classe', $classe, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

function apiDevoirOfModForTeacher($id, $module)
{
    global $db;
    $stmt = $db->prepare("select * from `devoirs` d inner join `modules` mo on d.ext_id_module = mo.id_module inner join `jury` ju on mo.id_module = ju.ext_id_module where ju.ext_id_prof=:id && mo.id_module=:module && d.ext_id_prof=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':module', $module, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

function getNotesOfWork($devoir, $classe)
{
    global $db;
    $stmt = $db->prepare("select * from `utilisateurs` u INNER JOIN `promotions` p on u.id = p.ext_id_usr INNER JOIN `groupes` g on p.ext_id_groupe = g.id_groupe INNER JOIN `classes` c ON c.id_classe = g.ext_id_classe left join `notes` n on n.ext_id_student = u.id && n.ext_id_devoir = :devoir WHERE c.id_classe = :classe && u.role = 1 ORDER BY u.nom, u.prenom");
    $stmt->bindValue(':devoir', $devoir, PDO::PARAM_INT);
    $stmt->bindValue(':classe', $classe, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

function updateNotes($devoir, $modifs)
{
    global $db;
    foreach ($modifs as $modification) {
        if ($modification->type == 1) {
            $stmt = $db->prepare("update `devoirs` set coef_devoir=:coef where id_devoir=:devoir");
            $stmt->bindValue(':coef', $modification->new_coef, PDO::PARAM_INT);
            $stmt->bindValue(':devoir', $devoir, PDO::PARAM_INT);
            $stmt->execute();
        }
        if ($modification->type == 2) {
            $stmt = $db->prepare("select * from `notes` where ext_id_student=:student && ext_id_devoir=:devoir");
            $stmt->bindValue(':student', $modification->id, PDO::PARAM_INT);
            $stmt->bindValue(':devoir', $devoir, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $stmt = $db->prepare("update `notes` set valeur=:valeur where ext_id_student=:student && ext_id_devoir=:devoir");
                $stmt->bindValue(':student', $modification->id, PDO::PARAM_INT);
                $stmt->bindValue(':devoir', $devoir, PDO::PARAM_INT);
                $stmt->bindValue(':valeur', $modification->new_valeur, PDO::PARAM_INT);
                $stmt->execute();
            } else {
                $stmt = $db->prepare("insert into `notes` (ext_id_student, ext_id_devoir, valeur) values (:student, :devoir, :valeur)");
                $stmt->bindValue(':student', $modification->id, PDO::PARAM_INT);
                $stmt->bindValue(':devoir', $devoir, PDO::PARAM_INT);
                $stmt->bindValue(':valeur', $modification->new_valeur, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
    }
    return true;
}

function getCoefDevoir($id_devoir)
{
    global $db;
    $stmt = $db->prepare("select coef_devoir from `devoirs` where id_devoir=:id_devoir");
    $stmt->bindValue(':id_devoir', $id_devoir, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

function createDevoir($nouveauDevoir)
{
    global $db;
    $stmt = $db->prepare("insert into `devoirs` (nom_devoir, coef_devoir, ext_id_module, ext_id_prof) values (:nom_devoir, :coef_devoir, :ext_id_module, :ext_id_prof)");
    $stmt->bindValue(':nom_devoir', $nouveauDevoir->nom, PDO::PARAM_STR);
    $stmt->bindValue(':coef_devoir', $nouveauDevoir->coef, PDO::PARAM_INT);
    $stmt->bindValue(':ext_id_module', $nouveauDevoir->module, PDO::PARAM_INT);
    $stmt->bindValue(':ext_id_prof', $nouveauDevoir->prof, PDO::PARAM_INT);
    $stmt->execute();
    return true;
}

function getUser($id)
{
    global $db;
    $stmt = $db->prepare("select * from `utilisateurs` where id=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

function updateProfil($id, $modifs, $files)
{
    var_dump($files);
    global $db;
    if ($files["image"]["name"] != '' && !empty($files)) {
        if (file_exists("../img/pdp/pdp$id.png")) {
            unlink("../img/pdp/pdp$id.png");
        }
        move_uploaded_file($files["image"]["tmp_name"], "./img/pdp/pdp$id.png");
    }
    if ($files["CV"]["name"] != '' && !empty($files)) {
        if (file_exists("./docs/cv/cv$id.pdf")) {
            unlink("./docs/cv/cv$id.pdf");
        }
        move_uploaded_file($files["CV"]["tmp_name"], "./docs/cv/cv$id.pdf");
    }
    $stmt = $db->prepare("update `utilisateurs` set description=:description, statut=:statut where id=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':description', $modifs["description"], PDO::PARAM_STR);
    $stmt->bindValue(':statut', $modifs["statut"], PDO::PARAM_STR);
    $stmt->execute();
    return true;
}

function getProjets($id)
{
    global $db;
    $stmt = $db->prepare("select * from `projets` where ext_id_user=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

function getThemes($id)
{
    global $db;
    $stmt = $db->prepare("select * from `themes` where ext_id_projet=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
        return "";
    }
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $themes = "";
    foreach ($result as $key => $row) {
        $themes .= $row["nom_theme"];
        if ($key != sizeof($result) - 1) {
            $themes .= "; ";
        }
    }
    return $themes;
}

function createProjet($id, $infos, $picture) {
    global $db;
    $stmt = $db->prepare("insert into `projets` (nom_projet, lien_projet, ext_id_user) values (:nom_projet, :lien_projet, :ext_id_user)");
    $stmt->bindValue(':nom_projet', $infos["nom"], PDO::PARAM_STR);
    $stmt->bindValue(':lien_projet', $infos["lien"], PDO::PARAM_STR);
    $stmt->bindValue(':ext_id_user', $id, PDO::PARAM_INT);
    $stmt->execute();
    $idProjet = $db->lastInsertId();
    if (isset($infos["themes"]) && !empty($infos["themes"])) {
        $themes = explode(";", $infos["themes"]);
        $stmt = $db->prepare("insert into `themes` (nom_theme, ext_id_projet) values (:nom_theme, :ext_id_projet)");
        foreach ($themes as $theme) {
            if ($theme == "") {
                continue;
            }
            $stmt->bindValue(':nom_theme', $theme, PDO::PARAM_STR);
            $stmt->bindValue(':ext_id_projet', $idProjet, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
    if (file_exists("./img/projets/projet$idProjet.png")) {
        unlink("./img/projets/projet$idProjet.png");
    }
    move_uploaded_file($picture["tmp_name"], "./img/projets/projet$idProjet.png");
    return true;
}
?>