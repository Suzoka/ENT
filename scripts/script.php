<?php
// Vériication des identifiants et du mot de passe pour les étudiants
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

// Vériication des identifiants et du mot de passe pour les professeurs
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

// Vériication des identifiants et du mot de passe pour les administrateurs
function checkConnectionAdmin($login, $mdp)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM `utilisateurs` where username=:username && role=3");
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

//Fonction pour la déconnexion
function deconection()
{
    $_SESSION = array();
    session_destroy();
}

//Fonction pour récupérer l'url de l'image de profil (ou image par défaut si elle n'existe pas)
function getImage($id)
{
    if (file_exists("./img/pdp/pdp$id.png")) {
        return "../img/pdp/pdp$id.png";
    } else {
        return "../img/pdp/default.png";
    }
}

//Fonction pour afficher l'identité d'un utilisateur, avec une casse spécifique
function getIdentite($id)
{
    global $db;
    $stmt = $db->prepare("SELECT prenom, nom FROM `utilisateurs` where id=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result["prenom"] . " " . $result["nom"];
}

//Fonction pour récupérer la ou les classes de l'utilisateur, avec une casse spécifique
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

//Fonction pour envoyer un message
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

//Fonction pour récupérer la conversation ouverte sur la pae de messagerie
function getCurrentConversation($id, $to)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM `messages` where (ext_id_sender=:id && ext_id_receiver=:to) || (ext_id_sender=:to && ext_id_receiver=:id) order by date");
    $stmt->bindValue(':to', $to, PDO::PARAM_INT);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

//Fonction pour récupérer la conversation la plus récente
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

//Fonction pour récupérer toutes les conversations en cours de l'utilisateur
function getAllConversation($id)
{
    global $db;
    $stmt = $db->prepare("SELECT other_user_id, date, message FROM (SELECT CASE WHEN ext_id_sender = :id THEN ext_id_receiver ELSE ext_id_sender END AS other_user_id, MAX(date) as max_date FROM (SELECT * FROM messages WHERE ext_id_sender = :id UNION ALL SELECT * FROM messages WHERE ext_id_receiver = :id) AS subquery GROUP BY other_user_id) AS lasts_messages inner JOIN messages ON messages.date = lasts_messages.max_date && ((messages.ext_id_sender = :id && messages.ext_id_receiver = lasts_messages.other_user_id)||(messages.ext_id_receiver = :id && messages.ext_id_sender = lasts_messages.other_user_id)) ORDER BY date DESC;");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

//Fonction de recherche d'un utilisateur
function getUsers($recherche)
{
    global $db;
    $stmt = $db->prepare("SELECT concat(prenom, ' ', nom, ' ', COALESCE(numEtud, '')) AS identite, id FROM `utilisateurs` where concat(prenom, ' ', nom, ' ', COALESCE(numEtud, '')) like :recherche order by concat(prenom, ' ', nom, ' ', COALESCE(numEtud, '')) limit 5;");
    $stmt->bindValue(':recherche', "%" . $recherche . "%", PDO::PARAM_STR);
    $stmt->execute();
    return $stmt;
}

//Fonction pour récupérer toutes les compétences pour lesquels un étudiant est inscrit
function getCompetences($id)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM `competences` co inner join `classes` cl on co.ext_id_classe = cl.id_classe inner join `groupes` gr on gr.ext_id_classe = cl.id_classe inner join `promotions` pr on pr.ext_id_groupe = gr.id_groupe where pr.ext_id_usr=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

//Fonction pour récupérer la moyenne d'un étudiant pour une compétence donnée
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

//Fonction pour récupérer la moyenne d'un étudiant pour un module donné
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

//Fonction pour récupérer toutes les modules d'une compétence
function getAllModsOfComp($id)
{
    global $db;
    $stmt = $db->prepare("SELECT * from `modules` m inner join `coef_modules` cm on m.id_module = cm.ext_id_module where cm.ext_id_competence=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

//Fonction pour formatter la note comme souhaité
function formatNote($note)
{
    $formatedNote = round($note, 2);
    if ($note < 10) {
        return ("0" . number_format($formatedNote, 2, ',', ''));
    } else {
        return (number_format($formatedNote, 2, ',', ''));
    }
}

//Fonction pour récupérer toutes les devoirs d'un module
function getAllDevoirsOfMod($id, $student)
{
    global $db;
    $stmt = $db->prepare("SELECT * from `devoirs` d inner join `notes` n on d.id_devoir = n.ext_id_devoir where d.ext_id_module=:id && n.ext_id_student=:student");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':student', $student, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

//Fonction pour récupérer le role d'un utilisateur (Pour les vérifications de droits)
function getRole($id)
{
    global $db;
    $stmt = $db->prepare("select role from `utilisateurs` where id=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

//Fonction pour récupérer toutes les classes d'un professeur
function getAllClassOfTeacher($id)
{
    global $db;
    $stmt = $db->prepare("select * from `classes` cl inner join `competences` co on cl.id_classe = co.ext_id_classe inner join `coef_modules` cm on co.id_competence = cm.ext_id_competence inner join `modules` mo on cm.ext_id_module = mo.id_module inner join `jury` ju on mo.id_module = ju.ext_id_module where ju.ext_id_prof=:id GROUP BY cl.id_classe;");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

//Fonction pour récupérer tous les modules d'une classe pour lesquels un professeur est jury
function getAllModOfClasseWhereTeacherIsJury($id, $classe)
{
    global $db;
    $stmt = $db->prepare("select * from `modules` mo inner join `jury` ju on mo.id_module = ju.ext_id_module inner join `coef_modules` cm on mo.id_module = cm.ext_id_module inner join `competences` co on cm.ext_id_competence = co.id_competence inner join `classes` cl on co.ext_id_classe = cl.id_classe where ju.ext_id_prof=:id && cl.id_classe=:classe GROUP BY mo.id_module;");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':classe', $classe, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

//Fonction pour que l'API récupère toutes les devoirs d'un module qui ont été créés un professeur donné
function apiDevoirOfModForTeacher($id, $module)
{
    global $db;
    $stmt = $db->prepare("select * from `devoirs` d inner join `modules` mo on d.ext_id_module = mo.id_module inner join `jury` ju on mo.id_module = ju.ext_id_module where ju.ext_id_prof=:id && mo.id_module=:module && d.ext_id_prof=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':module', $module, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

//Fonction pour récupérer toutes les notes d'un devoir
function getNotesOfWork($devoir, $classe)
{
    global $db;
    $stmt = $db->prepare("select * from `utilisateurs` u INNER JOIN `promotions` p on u.id = p.ext_id_usr INNER JOIN `groupes` g on p.ext_id_groupe = g.id_groupe INNER JOIN `classes` c ON c.id_classe = g.ext_id_classe left join `notes` n on n.ext_id_student = u.id && n.ext_id_devoir = :devoir WHERE c.id_classe = :classe && u.role = 1 ORDER BY u.nom, u.prenom");
    $stmt->bindValue(':devoir', $devoir, PDO::PARAM_INT);
    $stmt->bindValue(':classe', $classe, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

//Fonction pour metrre à jour les notes ou le coefficient d'un devoir
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

//Fonction pour récupérer le coefficient d'un devoir
function getCoefDevoir($id_devoir)
{
    global $db;
    $stmt = $db->prepare("select coef_devoir from `devoirs` where id_devoir=:id_devoir");
    $stmt->bindValue(':id_devoir', $id_devoir, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

//Fonction pour créer un devoir
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

//Fonction pour récupérer tous les utilisateurs
function getUser($id)
{
    global $db;
    $stmt = $db->prepare("select * from `utilisateurs` where id=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

//Fonction pour mettre à jour le profil d'un utilisateur
function updateProfil($id, $modifs, $files)
{
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

//Fonction pour récupérer tous les projets d'un utilisateur
function getProjets($id)
{
    global $db;
    $stmt = $db->prepare("select * from `projets` where ext_id_user=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

//Fonction pour récupérer tous les thèmes d'un projet
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

//Fonction pour créer un projet
function createProjet($id, $infos, $picture)
{
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

//Fonction pour qu'un utilisateur puisse réinitialiser son mot de passe
function resetMdp($id, $oldMdp, $newMdp)
{
    global $db;
    $stmt = $db->prepare("select * from `utilisateurs` where id=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (password_verify($oldMdp, $result["password"])) {
        $stmt = $db->prepare("update `utilisateurs` set password=:password where id=:id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':password', password_hash($newMdp, PASSWORD_DEFAULT), PDO::PARAM_STR);
        $stmt->execute();
        return true;
    } else {
        return false;
    }
}

//Fonction pour créer un utilisateur
function createUser($infos)
{
    global $db;
    $mdp = generatePassword();
    $username = strtolower($infos["prenom"] . "." . $infos["nom"]);
    $stmt = $db->prepare("select * from `utilisateurs` where username=:username");
    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $stmt = $db->query("SELECT MAX(id) AS max_id FROM `utilisateurs`");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $next_id = $row['max_id'] + 1;
        $username .= $next_id;
    }
    $mail = strtolower($username) . "@";
    if ($infos["role"] == 1) {
        $mail .= "edu.univ-eiffel.fr";
    } else if ($infos["role"] == 2) {
        $mail .= "univ-eiffel.fr";
    } else if ($infos["role"] == 3) {
        $mail .= "admin.univ-eiffel.fr";
    }
    $requete = "insert into `utilisateurs` (nom, prenom, username, password, email, role";
    if (isset($infos["dateNaissance"]) && !empty($infos["dateNaissance"])) {
        $requete .= ", date_naissance";
    }
    if (isset($infos["numEtudiant"]) && !empty($infos["numEtudiant"])) {
        $requete .= ", numEtud";
    }
    $requete .= ") values (:nom, :prenom, :username, :password, :mail, :role";
    if (isset($infos["dateNaissance"]) && !empty($infos["dateNaissance"])) {
        $requete .= ", :date_naissance";
    }
    if (isset($infos["numEtudiant"]) && !empty($infos["numEtudiant"])) {
        $requete .= ", :num_etudiant";
    }
    $requete .= ")";
    $stmt = $db->prepare($requete);
    $stmt->bindValue(':nom', $infos["nom"], PDO::PARAM_STR);
    $stmt->bindValue(':prenom', $infos["prenom"], PDO::PARAM_STR);
    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->bindValue(':password', password_hash($mdp, PASSWORD_DEFAULT), PDO::PARAM_STR);
    $stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
    $stmt->bindValue(':role', $infos["role"], PDO::PARAM_INT);
    if (isset($infos["dateNaissance"]) && !empty($infos["dateNaissance"])) {
        $stmt->bindValue(':date_naissance', $infos["dateNaissance"], PDO::PARAM_STR);
    }
    if (isset($infos["numEtudiant"]) && !empty($infos["numEtudiant"])) {
        $stmt->bindValue(':num_etudiant', $infos["numEtudiant"], PDO::PARAM_INT);
    }
    $stmt->execute();
    echo "<p>Le compte a bien été créé. Voici le résumé des informations :</p><ul><li>Nom : " . $infos["nom"] . "</li><li>Prénom : " . $infos["prenom"] . "</li><li>Identifiant : " . $username . "</li><li>Mot de passe : " . $mdp . "</li></ul><br>";
    return true;
}

//Fonction pour qu'un administrateur puisse réinitialiser le mot de passe d'un utilisateur pour un mot de passe aléatoire
function resetAdminPassword($id)
{
    global $db;
    $mdp = generatePassword();
    $stmt = $db->prepare("update `utilisateurs` set password=:password where id=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':password', password_hash($mdp, PASSWORD_DEFAULT), PDO::PARAM_STR);
    $stmt->execute();
    echo "<p>Le mot de passe a bien été réinitialisé. Voici le nouveau mot de passe :</p><ul><li>Mot de passe : " . $mdp . "</li></ul><br>";
    return true;
}

//Fonction pour généer un mot de passe aléatoire
function generatePassword()
{
    $mdpPossibilitys = "abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789?./!$%*+";
    $mdp = "";
    for ($i = 0; $i < 8; $i++) {
        $mdp .= $mdpPossibilitys[rand(0, strlen($mdpPossibilitys) - 1)];
    }
    return $mdp;
}

//Fonction pour supprimer un utilisateur
function deleteUser($id)
{
    global $db;
    $stmt = $db->prepare("delete from `utilisateurs` where id=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $stmt = $db->prepare("delete from `messages` where ext_id_sender=:id || ext_id_receiver=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $stmt = $db->prepare("delete from `notes` where ext_id_student=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $stmt = $db->prepare("select * from `projets` where ext_id_user=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = $db->prepare("delete from `themes` where ext_id_projet=:id");
    foreach ($result as $projet) {
        if (file_exists("./img/projets/projet" . $projet["id_projet"] . ".png")) {
            unlink("./img/projets/projet" . $projet["id_projet"] . ".png");
        }
        $stmt->bindValue(':id', $projet["id_projet"], PDO::PARAM_INT);
        $stmt->execute();
    }
    $stmt = $db->prepare("delete from `projets` where ext_id_user=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $stmt = $db->prepare("delete from `promotions` where ext_id_usr=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $stmt = $db->prepare("select * from `devoirs` where ext_id_prof=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = $db->prepare("delete from `notes` where ext_id_devoir=:id");
    foreach ($result as $devoir) {
        $stmt->bindValue(':id', $devoir["id_devoir"], PDO::PARAM_INT);
        $stmt->execute();
    }
    $stmt = $db->prepare("delete from `devoirs` where ext_id_prof=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $stmt = $db->prepare("delete from `jury` where ext_id_prof=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    if (file_exists("./img/pdp/pdp" . $id . ".png")) {
        unlink("./img/pdp/pdp" . $id . ".png");
    }
    if (file_exists("./docs/cv/cv" . $id . ".pdf")) {
        unlink("./docs/cv/cv" . $id . ".pdf");
    }
    if (file_exists("./docs/cs/cs" . $id . ".txt")) {
        unlink("./docs/cs/cs" . $id . ".txt");
    }
    return true;
}

//Fonction pour récupérer toutes les classes
function getAllClass()
{
    global $db;
    $stmt = $db->prepare("select * from `classes`");
    $stmt->execute();
    return $stmt;
}

//Fonction pour récupérer toutes les compétences d'une classe donnée
function getCompOfClass($id)
{
    global $db;
    $stmt = $db->prepare("select * from `competences` where ext_id_classe=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

//Fonction pour supprimer une compétence
function deleteCompetence($id)
{
    global $db;
    $stmt = $db->prepare("delete from `competences` where id_competence=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $stmt = $db->prepare("delete from `coef_modules` where ext_id_competence=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return "ok";
}

//Fonction pour créer une classe
function createClass($nom)
{
    global $db;
    $stmt = $db->prepare("insert into `classes` (nom_classe) values (:nom)");
    $stmt->bindValue(':nom', $nom->nom, PDO::PARAM_STR);
    $stmt->execute();
    return true;
}

//Fonction pour créer une compétence
function createComp($nom)
{
    global $db;
    $stmt = $db->prepare("insert into `competences` (nom_competence, ext_id_classe) values (:nom, :classe)");
    $stmt->bindValue(':nom', $nom->nom, PDO::PARAM_STR);
    $stmt->bindValue(':classe', $nom->id, PDO::PARAM_INT);
    $stmt->execute();
    return true;
}

//Fonction pour récupérer tous les modules d'une classe
function getAllModOfClass($id)
{
    global $db;
    $stmt = $db->prepare("select * from `modules` m inner join `coef_modules` cm on cm.ext_id_module=m.id_module inner join `competences` c on c.id_competence = cm.ext_id_competence where c.ext_id_classe=:id GROUP BY m.id_module;");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

//Fonction pour récupérer les informations d'un module
function getModuleInfo($id)
{
    global $db;
    $stmt = $db->prepare("select * from `modules` m inner join `coef_modules` co on m.id_module =co.ext_id_module where m.id_module=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

//Fonction pour mettre à jour les coefficients d'un module
function updateCoefModule($modifs)
{
    global $db;
    $idMod = $modifs->id;
    foreach ($modifs->modules as $module) {
        $stmt = $db->prepare("select * from `coef_modules` where ext_id_module=:id_module && ext_id_competence=:id_competence");
        $stmt->bindValue(':id_module', $idMod, PDO::PARAM_INT);
        $stmt->bindValue(':id_competence', $module->idComp, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            if ($module->coef == 0 || $module->coef == null || $module->coef == "") {
                $stmt = $db->prepare("delete from `coef_modules` where ext_id_module=:id_module && ext_id_competence=:id_competence");
                $stmt->bindValue(':id_module', $idMod, PDO::PARAM_INT);
                $stmt->bindValue(':id_competence', $module->idComp, PDO::PARAM_INT);
                $stmt->execute();
            } else {
                $stmt = $db->prepare("update `coef_modules` set coef_module=:coef where ext_id_module=:id_module && ext_id_competence=:id_competence");
                $stmt->bindValue(':id_module', $idMod, PDO::PARAM_INT);
                $stmt->bindValue(':id_competence', $module->idComp, PDO::PARAM_INT);
                $stmt->bindValue(':coef', $module->coef, PDO::PARAM_INT);
                $stmt->execute();
            }
        } else {
            if ($module->coef == 0 || $module->coef == null || $module->coef == "") {
                continue;
            } else {
                $stmt = $db->prepare("insert into `coef_modules` (ext_id_module, ext_id_competence, coef_module) values (:id_module, :id_competence, :coef)");
                $stmt->bindValue(':id_module', $idMod, PDO::PARAM_INT);
                $stmt->bindValue(':id_competence', $module->idComp, PDO::PARAM_INT);
                $stmt->bindValue(':coef', $module->coef, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
    }
    $stmt = $db->prepare("select * from `coef_modules` where ext_id_module=:id_module");
    $stmt->bindValue(':id_module', $idMod, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
        $stmt = $db->prepare("delete from `modules` where id_module=:id_module");
        $stmt->bindValue(':id_module', $idMod, PDO::PARAM_INT);
        $stmt->execute();
    }
    return true;
}

//Fonction pour récupérer tous les professeurs qui sont jury d'un module
function getAllProfsOfMod($id)
{
    global $db;
    $stmt = $db->prepare("select * from `jury` j inner join `utilisateurs` u on j.ext_id_prof = u.id where j.ext_id_module=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

//Fonction pour récupérer tous les professeurs qui ne sont pas jury d'un module
function getAllProfs($idModule)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM `utilisateurs` WHERE role = 2 AND id NOT IN (SELECT ext_id_prof FROM `jury` WHERE ext_id_module = :idModule);");
    $stmt->bindValue(':idModule', $idModule, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

//Fonction pour assigner un professeur à un module
function assignProf($infos)
{
    global $db;
    $stmt = $db->prepare("insert into `jury` (ext_id_prof, ext_id_module) values (:id_prof, :id_module)");
    $stmt->bindValue(':id_prof', $infos->idProf, PDO::PARAM_INT);
    $stmt->bindValue(':id_module', $infos->idMod, PDO::PARAM_INT);
    $stmt->execute();
    return true;
}

//Fonction pour supprimer un professeur d'un module
function deleteProfOfMod($prof, $module)
{
    global $db;
    $stmt = $db->prepare("delete from `jury` where ext_id_prof=:id_prof && ext_id_module=:id_module");
    $stmt->bindValue(':id_prof', $prof, PDO::PARAM_INT);
    $stmt->bindValue(':id_module', $module, PDO::PARAM_INT);
    $stmt->execute();
    return true;
}

//Fonction pour créer un module
function createMod($infos)
{
    global $db;
    $stmt = $db->prepare("insert into `modules` (nom_module) values (:nom)");
    $stmt->bindValue(':nom', $infos->nom, PDO::PARAM_STR);
    $stmt->execute();
    $idMod = $db->lastInsertId();
    $stmt = $db->prepare("insert into `coef_modules` (ext_id_module, ext_id_competence, coef_module) values (:id_module, :id_competence, :coef)");
    foreach ($infos->coefs as $coef) {
        if ($coef->coef == 0 || $coef->coef == null || $coef->coef == "") {
            continue;
        }
        $stmt->bindValue(':id_module', $idMod, PDO::PARAM_INT);
        $stmt->bindValue(':id_competence', $coef->idComp, PDO::PARAM_INT);
        $stmt->bindValue(':coef', $coef->coef, PDO::PARAM_INT);
        $stmt->execute();
    }
    return true;
}

//Fonction pour récupérer tous les groupes d'une classe
function getGroupesOfClass($id)
{
    global $db;
    $stmt = $db->prepare("select * from `groupes` where ext_id_classe=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

//Fonction pour supprimer un groupe
function deleteGroup($id)
{
    global $db;
    $stmt = $db->prepare("delete from `groupes` where id_groupe=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $stmt = $db->prepare("delete from `promotions` where ext_id_groupe=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return true;
}

//Fonction pour créer un groupe
function createGroup($infos) {
    global $db;
    $stmt = $db->prepare("insert into `groupes` (nom_groupe, ext_id_classe) values (:nom, :classe)");
    $stmt->bindValue(':nom', $infos->nom, PDO::PARAM_STR);
    $stmt->bindValue(':classe', $infos->classe, PDO::PARAM_INT);
    $stmt->execute();
    return true;
}

//Fonction pour récupérer tous les étudiants d'un groupe
function getStudentsOfGroup($id) {
    global $db;
    $stmt = $db->prepare("select * from `utilisateurs` u inner join `promotions` p on u.id = p.ext_id_usr inner join `groupes` g on p.ext_id_groupe = g.id_groupe where g.id_groupe=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

//Fonction pour supprimer un étudiant d'un groupe
function deleteStudentOfGroup($idStudent, $idGroupe) {
    global $db;
    $stmt = $db->prepare("delete from `promotions` where ext_id_usr=:idStudent && ext_id_groupe=:idGroupe");
    $stmt->bindValue(':idStudent', $idStudent, PDO::PARAM_INT);
    $stmt->bindValue(':idGroupe', $idGroupe, PDO::PARAM_INT);
    $stmt->execute();
    return true;
}

//Fonction pour récupérer tous les étudiants qui ne sont pas dans un groupe
function getAllStudents($idGroupe)
{
    global $db;
    $stmt = $db->prepare("select * from `utilisateurs` where role=1 && id NOT IN (select ext_id_usr from `promotions` where ext_id_groupe=:idGroupe)");
    $stmt->bindValue(':idGroupe', $idGroupe, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

//Fonction pour assigner un étudiant à un groupe
function assignStudent($infos)
{
    global $db;
    $stmt = $db->prepare("insert into `promotions` (ext_id_usr, ext_id_groupe) values (:idStudent, :idGroupe)");
    $stmt->bindValue(':idStudent', $infos->idEtudiant, PDO::PARAM_INT);
    $stmt->bindValue(':idGroupe', $infos->idGroupe, PDO::PARAM_INT);
    $stmt->execute();
    return true;
}

//Fonction pour supprimer un étudiant
function deleteClass($id)
{
    global $db;
    $stmt = $db->prepare("delete from `classes` where id_classe=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $stmt = $db->prepare("SELECT id_groupe FROM `groupes` WHERE ext_id_classe=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $groupIds = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    foreach ($groupIds as $groupId) {
        $stmt = $db->prepare("DELETE FROM `promotions` WHERE ext_id_groupe=:groupId");
        $stmt->bindValue(':groupId', $groupId, PDO::PARAM_INT);
        $stmt->execute();
        $stmt = $db->prepare("DELETE FROM `groupes` WHERE id_groupe=:groupId");
        $stmt->bindValue(':groupId', $groupId, PDO::PARAM_INT);
        $stmt->execute();
    }

    $stmt = $db->prepare("select id_module from `modules` m inner join `coef_modules` cm on m.id_module = cm.ext_id_module inner join `competences` c on cm.ext_id_competence = c.id_competence where c.ext_id_classe=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $moduleIds = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    foreach ($moduleIds as $moduleId) {
        $stmt = $db->prepare("select id_devoir from `devoirs` where ext_id_module=:moduleId");
        $stmt->bindValue(':moduleId', $moduleId, PDO::PARAM_INT);
        $stmt->execute();
        $devoirIds = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        foreach ($devoirIds as $devoirId) {
            $stmt = $db->prepare("delete from `notes` where ext_id_devoir=:devoirId");
            $stmt->bindValue(':devoirId', $devoirId, PDO::PARAM_INT);
            $stmt->execute();
            $stmt = $db->prepare("delete from `devoirs` where id_devoir=:devoirId");
            $stmt->bindValue(':devoirId', $devoirId, PDO::PARAM_INT);
            $stmt->execute();
        }
        $stmt = $db->prepare("delete from `jury` where ext_id_module=:moduleId");
        $stmt->bindValue(':moduleId', $moduleId, PDO::PARAM_INT);
        $stmt->execute();
        $stmt = $db->prepare("delete from `coef_modules` where ext_id_module=:moduleId");
        $stmt->bindValue(':moduleId', $moduleId, PDO::PARAM_INT);
        $stmt->execute();
        $stmt = $db->prepare("delete from `modules` where id_module=:moduleId");
        $stmt->bindValue(':moduleId', $moduleId, PDO::PARAM_INT);
        $stmt->execute();
    }

    $stmt = $db->prepare("delete from `competences` where ext_id_classe=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return true;
}
?>